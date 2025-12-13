<?php
// Configuración de la conexión a la base de datos
require_once 'config.php';

// Obtener los nombres de archivo seleccionados para descarga
$filename = isset($_GET['filename']) ? $_GET['filename'] : [];
$year = isset($_GET['year']) ? $_GET['year'] : [];
$month = isset($_GET['month']) ? $_GET['month'] : [];
$rut_empr = isset($_GET['rut']) ? $_GET['rut'] : [];



if (!empty($filename)) {
    try {
        // Conectar a la base de datos
        $conn = new PDO("pgsql:host={$db_config['host']};dbname={$db_config['database']}", $db_config['user'], $db_config['password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar la consulta de actualización
        $query = "UPDATE archivos_generados SET updated_at = NOW() WHERE codi_empr=$codi_empr AND year=$year AND month=$month AND filename = :filename";
        $stmt = $conn->prepare($query);

        // Iniciar la transacción
        $conn->beginTransaction();

        // Iterar sobre los nombres de archivo y ejecutar la actualización
        //foreach ($filenames as $filename) {
            $stmt->bindParam(':filename', $filename);
            $stmt->execute();
        //}

        // Confirmar la transacción
        $conn->commit();

        // Obtener la ruta del primer archivo seleccionado
        $filepath = $PATH  . $rut_empr . "/" . $year . "/" . $filename;

	echo "path: $filepath";


        if (file_exists($filepath)) {
            // Configurar las cabeceras para la descarga
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($filepath));

            // Leer el contenido del archivo y enviarlo al navegador
            readfile($filepath);
            exit;
        } else {
            echo "El archivo no existe.";
        }

    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo "Error al actualizar los archivos: " . $e->getMessage();
    }
} else {
    echo "No se han seleccionado archivos para actualizar.";
}
?>
