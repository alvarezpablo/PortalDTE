<?php
ini_set('post_max_size', '800M');
ini_set('upload_max_filesize', '800M');
ini_set('memory_limit', '2048M');
ini_set('max_execution_time', '36000');
ini_set('max_input_time', '36000');

include("../include/config.php");
include("../include/ver_aut.php");
include("../include/db_lib.php");
include("../include/upload_class.php");

$conn = conn();
$conn->SetFetchMode(ADODB_FETCH_ASSOC);

function uploadCaf() {
    global $_MAX_FILE_CLIE_ELEC, $_PATH_REAL_CLIE_ELEC, $_ARRAY_EXT_CLIE_ELEC, $conn;

    // --- subida de archivo (igual que tu versión original) ---
    $my_upload = new file_upload;
    $my_upload->upload_dir = $_PATH_REAL_CLIE_ELEC;
    $my_upload->extensions = $_ARRAY_EXT_CLIE_ELEC;
    $my_upload->language = "es";
    $my_upload->max_length_filename = 100;
    $my_upload->rename_file = true;
    $my_upload->replace = true;
    $my_upload->the_temp_file = $_FILES['sFileClieElec']['tmp_name'];
    $my_upload->the_file = $_FILES['sFileClieElec']['name'];
    $my_upload->http_error = $_FILES['sFileClieElec']['error'];
    $my_upload->do_filename_check = "n";
    $new_name = "cont_elec_csv";

    if (!$my_upload->upload($new_name)) {
        $arrayTmp = $my_upload->message;
        $sMsgJs = implode("\n", $arrayTmp);
        header("location:form_cont_elec.php?sMsgJs=" . $sMsgJs);
        exit;
    }

    $path_csv = $_PATH_REAL_CLIE_ELEC . $new_name . $my_upload->get_extension($my_upload->the_file);
  // echo $path_csv;



    // --- crea tabla staging temporal ---
    $sql = "
DROP TABLE IF EXISTS contrib_elec_staging;
CREATE TABLE contrib_elec_staging(
        rut_contr_text TEXT,
        rs_contr TEXT,
        nrores_contr TEXT,
        fecres_contr TEXT,
        email_contr TEXT,
        url TEXT
    );
    TRUNCATE contrib_elec_staging;
    ";
    nrExecuta($conn, $sql, true);

    // --- carga masiva del CSV usando COPY FROM STDIN ---
    echo "Cargando CSV .....\n";


   $path_entrada = $path_csv;	
   $path_salida  = $_PATH_REAL_CLIE_ELEC . 'contrib_elec_LIMPIO.csv';
   $path_errores = $_PATH_REAL_CLIE_ELEC . 'contrib_elec_ERRORES.txt';
   if (file_exists($path_salida)) unlink($path_salida);
   if (file_exists($path_errores)) unlink($path_errores);

   $nuevo_csv_path = filtrarCsvPorFormato($path_entrada, $path_salida, $path_errores);

    // --- muestra log de salida ---
    if (file_exists($path_errores) && filesize($path_errores) > 0) {
        $out = htmlspecialchars(file_get_contents($path_errores));
        echo "<h4>Salida de Lineas con Error: </h4>";
        echo "<textarea rows='10' cols='150' style='width:100%;font-family:monospace;'>$out</textarea>";
    }

   cargarCsvStaging($nuevo_csv_path);


$rs = rCursor($conn, "SELECT count(*) as total FROM contrib_elec_staging");
echo "Filas cargadas en staging: " . $rs->fields['total'] . "<br>";


    echo "CSV cargado correctamente.\n";

    // --- inserta o actualiza en tabla final ---
/*    $sqlUpsert = "
    INSERT INTO contrib_elec (rut_contr, rs_contr, nrores_contr, fecres_contr, email_contr)
    SELECT
        split_part(rut_contr_text, '-', 1)::numeric AS rut_contr,
        rs_contr,
        NULLIF(nrores_contr, '')::numeric,
        TO_DATE(NULLIF(fecres_contr, ''), 'DD-MM-YYYY'),
        email_contr
    FROM contrib_elec_staging
    WHERE rut_contr_text IS NOT NULL AND rut_contr_text <> ''
    ON CONFLICT (rut_contr) DO UPDATE
    SET
        rs_contr = EXCLUDED.rs_contr,
        nrores_contr = EXCLUDED.nrores_contr,
        fecres_contr = EXCLUDED.fecres_contr,
        email_contr = CASE
            WHEN contrib_elec.email_contr IS DISTINCT FROM EXCLUDED.email_contr
            THEN EXCLUDED.email_contr
            ELSE contrib_elec.email_contr
        END;
    ";
*/

    $sqlUpsert = "INSERT INTO contrib_elec (rut_contr, rs_contr, nrores_contr, fecres_contr, email_contr)
SELECT
    CASE
        WHEN TRIM(t.rut_contr_text) ~ '^\d{1,8}-.+' 
        THEN split_part(TRIM(t.rut_contr_text), '-', 1)::numeric
        ELSE NULL 
    END AS rut_contr,

    TRIM(t.rs_contr),

    CASE 
        WHEN TRIM(t.nrores_contr) <> '' THEN
            REGEXP_REPLACE(TRIM(t.nrores_contr), '[^0-9]', '', 'g')::numeric
        ELSE NULL
    END AS nrores_contr,
    
    CASE
        WHEN TRIM(t.fecres_contr) ~ '^\d{2}-\d{2}-\d{4}$'
        THEN TO_DATE(TRIM(t.fecres_contr), 'DD-MM-YYYY')
        ELSE NULL
    END AS fecres_contr,
    
    TRIM(t.email_contr)
    
FROM contrib_elec_staging t
WHERE 
    CASE
        WHEN TRIM(t.rut_contr_text) ~ '^\d{1,8}-.+'
        THEN split_part(TRIM(t.rut_contr_text), '-', 1)::numeric IS NOT NULL
        ELSE FALSE
    END

ON CONFLICT (rut_contr) DO UPDATE
SET
    rs_contr = EXCLUDED.rs_contr,
    nrores_contr = EXCLUDED.nrores_contr,
    fecres_contr = EXCLUDED.fecres_contr,
    email_contr = EXCLUDED.email_contr";

    nrExecuta($conn, $sqlUpsert, true);

    // --- actualización masiva en clientes ---
    $sqlClientes = "
    UPDATE clientes
    SET emi_elec_cli = 'S', acrec_email = 'S'
    WHERE rut_cli IN (
        SELECT split_part(rut_contr_text, '-', 1)::numeric
        FROM contrib_elec_staging
    );
    ";
    nrExecuta($conn, $sqlClientes, true);

    echo "Actualizaci�n final completada.\n";
  // nrExecuta($conn, "DROP TABLE IF EXISTS contrib_elec_staging;", true);

}


function cargarCsvStaging($path_csv) {
    global $_SERVER_DB, $_USER_DB, $_PASS_DB, $_DATABASE, $_PATH_REAL_CLIE_ELEC;

    // --- separa host y puerto si vienen juntos (ej: "10.30.1.194:5432") ---
    $hostParts = explode(':', $_SERVER_DB);
    $host = escapeshellarg($hostParts[0]);
    $port = isset($hostParts[1]) ? escapeshellarg($hostParts[1]) : '5432';

    $user = escapeshellarg($_USER_DB);
    $db   = escapeshellarg($_DATABASE);
    $pass = escapeshellarg($_PASS_DB);
    $file = escapeshellarg($path_csv);

    // --- define rutas de log ---
    $errorLogPath = $_PATH_REAL_CLIE_ELEC . "/cont_elec_csv.csv.err.log";
    $outputLogPath = $_PATH_REAL_CLIE_ELEC . "/psql_output.log";

    // --- comando COPY con logs ---
    $cmd = "PGPASSWORD=$pass /bin/psql -h $host -p $port -U $user -d $db "
         . "-c \"\\copy contrib_elec_staging (rut_contr_text, rs_contr, nrores_contr, fecres_contr, email_contr, url) "
         . "FROM $file WITH (FORMAT csv, HEADER true, DELIMITER ';')\" "
         . "2> " . escapeshellarg($errorLogPath) . " > " . escapeshellarg($outputLogPath) . " 2>&1";

    // echo "<pre>$cmd</pre>";

    // --- ejecuta y captura salida ---
    exec($cmd, $output, $ret);

    if ($ret !== 0) {
        echo "<b>Error ejecutando COPY ($ret)</b><br>";
    } else {
        echo "<b>CSV cargado correctamente en staging</b><br>";
    }

    // --- lee el log de errores ---
    if (file_exists($errorLogPath) && filesize($errorLogPath) > 0) {
        $content = htmlspecialchars(file_get_contents($errorLogPath));
        echo "<h4>Errores detectados durante la carga:</h4>";
        echo "<textarea rows='25' cols='150' style='width:100%;font-family:monospace;'>$content</textarea>";
    } else {
        echo "No se encontraron errores en la carga del CSV<br>";
    }

    // --- muestra log de salida ---
    if (file_exists($outputLogPath) && filesize($outputLogPath) > 0) {
        $out = htmlspecialchars(file_get_contents($outputLogPath));
        echo "<h4>Salida de psql:</h4>";
        echo "<textarea rows='10' cols='150' style='width:100%;font-family:monospace;'>$out</textarea>";
    }
}


/**
 * Valida cada línea de un CSV (con encabezado) para asegurar que contenga EXACTAMENTE 5 puntos y comas.
 * Antes de validar, elimina TODOS los caracteres especiales, dejando solo alfanuméricos y el delimitador.
 * Copia líneas válidas a un nuevo CSV y registra las líneas con error en un TXT.
 *
 * @param string $path_csv_entrada Ruta del archivo CSV original de entrada.
 * @param string $path_csv_salida Ruta del nuevo archivo CSV limpio (salida).
 * @param string $path_log_errores Ruta del archivo TXT para registrar errores.
 * @return string|false La ruta del nuevo archivo CSV filtrado ($path_csv_salida), o false si falla.
 */
function filtrarCsvPorFormato(string $path_csv_entrada, &$path_csv_salida, &$path_log_errores)
{
    // --- Configuración ---
    $delimitador = ';';
    // Se esperan 6 columnas, por lo tanto, el número esperado de delimitadores es 5.
    $delimitadores_esperados = 5; 

    // Expresión regular para limpiar:
    // Permite: Letras (a-z, A-Z), Números (0-9), Espacios (\s),
    // Puntos (.), Guiones (-), Arrobas (@), y el Punto y Coma (;)
    // El modificador 'i' es para case-insensitive.
    // Lo que NO coincida con esto, será reemplazado por una cadena vacía.
    //$patron_limpieza = '/[^a-zA-Z0-9\s\.\-@;]/';
    $patron_limpieza = '/[^a-zA-Z0-9\s\.\-\_\+@;]/';

    
    // Limpiar archivo para eliminar los \r\n de algunas lineas
    limpiarArchivoCRLF($path_csv_entrada);

    // --- Abrir Archivos ---
    $handle_entrada = @fopen($path_csv_entrada, 'r');
    if (!$handle_entrada) {
        return false;
    }

    $handle_salida = @fopen($path_csv_salida, 'w');
    $handle_errores = @fopen($path_log_errores, 'w');
    
    $num_linea = 0;
    
    // --- Procesamiento Línea por Línea ---
    while (($linea = fgets($handle_entrada)) !== false) {
        $num_linea++;
        
        $linea_trim = trim($linea); // Línea sin espacios/saltos

        // 1. Limpieza de caracteres especiales (aplicado a todas las líneas, incluyendo el encabezado)
        $linea_limpia = preg_replace($patron_limpieza, '', $linea_trim);
        
        // --- MANEJO DEL ENCABEZADO (Línea 1) ---
        if ($num_linea === 1) {
            // Usar la línea ya limpiada para el encabezado y asegurar el salto de línea.
            fwrite($handle_salida, $linea_limpia . "\n"); 
            continue; 
        }
        
        // --- VALIDACIÓN DE DATOS (Líneas 2 en adelante) ---

        // 2. Validar línea en blanco después de limpieza
        if (empty($linea_limpia)) {
            fwrite($handle_errores, "Línea $num_linea: La línea está vacía o solo contenía caracteres eliminados.\n");
            continue;
        }

        // 3. Validar número de delimitadores (DEBE ser exactamente 5)
        $conteo_delimitadores = substr_count($linea_limpia, $delimitador);
        
        if ($conteo_delimitadores !== $delimitadores_esperados) {
	    // --- LÓGICA DE RESCATE: SI CONTIENE 6 DELIMITADORES (7 COLUMNAS) ---
            if ($conteo_delimitadores === 6) {

                // Realizar el split de la línea (ahora tenemos 7 elementos: [0] a [6])
                $campos = explode($delimitador, $linea_limpia);

                // ** Validación Extra: Revisar si la columna 3 (índice 2) es numérica **
                // La Columna 3 debe ser NÚMERO RESOLUCION
                if (isset($campos[3]) && is_numeric(trim($campos[3]))) {
                    // Caso Válido: Es una Razón Social partida. Proceder a la reestructuración.

                    // C1: RUT (0)
                    // C2: Razón Social (1 y 2) -> unimos C2 y C3
                    $campos[1] = trim($campos[1]) . ' ' . trim($campos[2]); 
                    
                    // C3: Nro Res (2) -> toma el valor de C4 (3)
                    $campos[2] = $campos[3]; 
                    
                    // C4: Fec Res (3) -> toma el valor de C5 (4)
                    $campos[3] = $campos[4]; 
                    
                    // C5: Email (4) -> toma el valor de C6 (5)
                    $campos[4] = $campos[5]; 
                    
                    // C6: URL (5) -> toma el valor de C7 (6)
                    $campos[5] = $campos[6]; 
                    
                    // Tomar los primeros 6 elementos corregidos (índices 0 a 5)
                    $campos_corregidos = array_slice($campos, 0, 6);
                    
                    // Unir las 6 columnas con 5 delimitadores
                    $linea_limpia = implode($delimitador, $campos_corregidos);
                    
		    // Escribir la línea corregida y continuar al siguiente ciclo
                    fwrite($handle_salida, $linea_limpia . "\n");
                    continue; // Línea rescatada, pasar a la siguiente
                }
                
                // Si la Columna 3 NO es numérica, o no existe:
                $mensaje_error = "Sobran columnas o datos extra (C3 no es NÚMERO RESOLUCION).";
                
            } else {
                // Si el conteo no es 6 (es 0, 1, 2, 3, 4, o >= 7)
                $mensaje_error = ($conteo_delimitadores < $delimitadores_esperados) 
                                 ? "Faltan columnas"
                                 : "Sobran columnas o datos extra";
            }
            // --- FIN LÓGICA DE RESCATE ---


            // --- L~SGICA DE RESCATE: SI CONTIENE 7 DELIMITADORES (8 COLUMNAS) ---
            if ($conteo_delimitadores === 7) {

                // Realizar el split de la línea (ahora tenemos 8 elementos: [0] a [7])
                $campos = explode($delimitador, $linea_limpia);

                // ** Validación Extra: Revisar si la columna 3 (índice 2) es numérica **
                // La Columna 3 debe ser N~ZMERO RESOLUCION
                if (isset($campos[4]) && is_numeric(trim($campos[4]))) {
                    // Caso Válido: Es una Razón Social partida. Proceder a la reestructuración.

                    // C1: RUT (0)
                    // C2: Razón Social (1 y 2) -> unimos C2 y C3
                    $campos[1] = trim($campos[1]) . ' ' . trim($campos[2]). ' ' . trim($campos[3]);

                    // C3: Nro Res (2) -> toma el valor de C4 (3)
                    $campos[2] = $campos[4];

                    // C4: Fec Res (3) -> toma el valor de C5 (4)
                    $campos[3] = $campos[5];

                    // C5: Email (4) -> toma el valor de C6 (5)
                    $campos[4] = $campos[6];

                    // C6: URL (5) -> toma el valor de C7 (6)
                    $campos[5] = $campos[7];

                    // Tomar los primeros 6 elementos corregidos (índices 0 a 5)
                    $campos_corregidos = array_slice($campos, 0, 6);

                    // Unir las 6 columnas con 5 delimitadores
                    $linea_limpia = implode($delimitador, $campos_corregidos);

                    // Escribir la línea corregida y continuar al siguiente ciclo
                    fwrite($handle_salida, $linea_limpia . "\n");
                    continue; // Línea rescatada, pasar a la siguiente
                }

                // Si la Columna 3 NO es numérica, o no existe:
                $mensaje_error = "Sobran columnas o datos extra (C3 no es N~ZMERO RESOLUCION).";

            } else {
                // Si el conteo no es 6 (es 0, 1, 2, 3, 4, o >= 7)
                $mensaje_error = ($conteo_delimitadores < $delimitadores_esperados)
                                 ? "Faltan columnas"
                                 : "Sobran columnas o datos extra";
            }
            // --- FIN L~SGICA DE RESCATE ---


            // --- L~SGICA DE RESCATE: SI CONTIENE 7 DELIMITADORES (8 COLUMNAS) ---
            if ($conteo_delimitadores === 8) {

                // Realizar el split de la línea (ahora tenemos 8 elementos: [0] a [8])
                $campos = explode($delimitador, $linea_limpia);

                // ** Validación Extra: Revisar si la columna 3 (índice 2) es numérica **
                // La Columna 3 debe ser N~ZMERO RESOLUCION
                if (isset($campos[5]) && is_numeric(trim($campos[5]))) {
                    // Caso Válido: Es una Razón Social partida. Proceder a la reestructuración.

                    // C1: RUT (0)
                    // C2: Razón Social (1 y 2) -> unimos C2 y C3
                    $campos[1] = trim($campos[1]) . ' ' . trim($campos[2]). ' ' . trim($campos[3]). ' ' . trim($campos[4]);

                    // C3: Nro Res (2) -> toma el valor de C4 (3)
                    $campos[2] = $campos[5];

                    // C4: Fec Res (3) -> toma el valor de C5 (4)
                    $campos[3] = $campos[6];

                    // C5: Email (4) -> toma el valor de C6 (5)
                    $campos[4] = $campos[7];

                    // C6: URL (5) -> toma el valor de C7 (6)
                    $campos[5] = $campos[8];

                    // Tomar los primeros 6 elementos corregidos (índices 0 a 5)
                    $campos_corregidos = array_slice($campos, 0, 6);

                    // Unir las 6 columnas con 5 delimitadores
                    $linea_limpia = implode($delimitador, $campos_corregidos);

                    // Escribir la línea corregida y continuar al siguiente ciclo
                    fwrite($handle_salida, $linea_limpia . "\n");
                    continue; // Línea rescatada, pasar a la siguiente
                }

                // Si la Columna 3 NO es numérica, o no existe:
                $mensaje_error = "Sobran columnas o datos extra (C3 no es N~ZMERO RESOLUCION).";

            } else {
                // Si el conteo no es 6 (es 0, 1, 2, 3, 4, o >= 7)
                $mensaje_error = ($conteo_delimitadores < $delimitadores_esperados)
                                 ? "Faltan columnas"
                                 : "Sobran columnas o datos extra";
            }
            // --- FIN L~SGICA DE RESCATE ---



            // Guardar error en el archivo TXT
            $mensaje_error = ($conteo_delimitadores < $delimitadores_esperados) 
                             ? "Faltan columnas"
                             : "Sobran columnas o datos extra";
            
            fwrite($handle_errores, "Línea $num_linea: Error de formato. $mensaje_error (Se esperaban 5 ';', se encontraron $conteo_delimitadores después de la limpieza).\n");
            fwrite($handle_errores, "  Contenido de la línea original: " . $linea_trim . "\n");
            continue;
        }

        // 4. La línea es VÁLIDA: Guardarla en el nuevo CSV, asegurando el salto de línea.
        fwrite($handle_salida, $linea_limpia . "\n"); 
    }

    // --- Cerrar Handles ---
    if ($handle_entrada) fclose($handle_entrada);
    if ($handle_salida) fclose($handle_salida);
    if ($handle_errores) fclose($handle_errores);
    
    return $path_csv_salida;
}

/**
 * Lee todo el contenido de un archivo, elimina todas las secuencias \r\n (Carriage Return + Line Feed),
 * y sobrescribe el archivo con el contenido modificado.
 *
 * @param string $path_archivo Ruta del archivo a limpiar.
 * @return bool True si la operación fue exitosa, false en caso de error (lectura o escritura).
 */
function limpiarArchivoCRLF(string $path_archivo): bool
{
    // 1. Leer todo el contenido del archivo en una sola cadena.
    // WARNING: Esto puede consumir mucha memoria para archivos grandes.
    $contenido = @file_get_contents($path_archivo);

    if ($contenido === false) {
        echo "❌ Error: No se pudo leer el archivo en la ruta: " . $path_archivo . "\n";
        return false;
    }

    // 2. Eliminar TODAS las secuencias \r\n (retorno de carro + salto de línea)
    // Esto es lo que rompe las líneas si aparece dentro de un campo CSV.
    // NOTA: Los saltos de línea (\n) se mantendrán, ya que solo se elimina la secuencia \r\n.
    $contenido_limpio = str_replace("\r\n", "", $contenido);
    $contenido_limpio = str_replace('"', '', $contenido_limpio); 

    // 3. Guardar el contenido limpio, sobrescribiendo el archivo original.
    $resultado_escritura = @file_put_contents($path_archivo, $contenido_limpio);

    if ($resultado_escritura === false) {
        echo "❌ Error: No se pudo escribir el contenido limpio en el archivo: " . $path_archivo . "\n";
        return false;
    }

    echo "✅ Archivo limpiado y guardado correctamente. Se eliminaron todas las secuencias \\r\\n.\n";
    return true;
}


uploadCaf();
//header("location:fin_cont_elec.php");
//exit;
?>

