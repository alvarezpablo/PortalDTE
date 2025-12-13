<?php 
include("../include/ver_aut.php");
include("../include/ver_emp_adm.php");  

require_once 'config.php';

if ($codi_empr > 0) {

    $sNomEmp = $_SESSION["_NOM_EMP_USU_SESS"];
    
    try {
        // Conectar a la base de datos
        $conn = new PDO("pgsql:host={$db_config['host']};dbname={$db_config['database']}", $db_config['user'], $db_config['password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta para obtener el rut_empr correspondiente al codi_empr
        $query = "SELECT rut_empr FROM empresa WHERE codi_empr = :codi_empr";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':codi_empr', $codi_empr, PDO::PARAM_INT);
        $stmt->execute();

        // Obtener el resultado de la consulta
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $rut_empr = $result['rut_empr'];
	}

        // Consulta para obtener los archivos respaldados para la empresa especificada
        // $query = "SELECT year, month, filename FROM archivos_generados WHERE codi_empr = :codi_empr ORDER BY updated_at DESC";
 	$query = "SELECT year, month, filename, created_at, updated_at FROM archivos_generados WHERE codi_empr = :codi_empr ORDER BY year DESC, month DESC, updated_at ASC";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':codi_empr', $codi_empr, PDO::PARAM_INT);
        $stmt->execute();

        // Obtener los resultados de la consulta
        $archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2>Archivos respaldados para la empresa: $sNomEmp. </h2>";
	echo "<p>Descomprimir usando: Winrar, 7zip, Winzip, etc.<br>";
        echo "<table>";
        echo "<tr><th>A&ntilde;o</th><th>Mes</th><th>Archivo</th></tr>";
        // Mostrar los archivos respaldados
        if (count($archivos) > 0) {

            foreach ($archivos as $archivo) {
                $year = $archivo['year'];
                $month = $archivo['month'];
                $filename = $archivo['filename'];
                $created_at = $archivo['created_at'];
                $updated_at = $archivo['updated_at'];

		// Verificar si updated_at es mayor que created_at
                $row_style = ($updated_at > $created_at) ? 'style="amarillo-suave;"' : '';


                echo "<tr $row_style>";
                echo "<td>$year</td>";
                echo "<td>$month</td>";
                echo "<td><a href='download.php?filename=$filename&year=$year&month=$month&rut=$rut_empr'>$filename</a></td>";
                echo "</tr>";
            }

        } else {
            echo "<p>No se encontraron archivos respaldados.</p>";
        }
            echo "</table>";
    } catch (PDOException $e) {
        echo "Error al conectar a la base de datos: " . $e->getMessage();
    }
} else {
    echo "<p>Debe especificar un código de empresa válido.</p>";
}

?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
    }

    h2 {
        color: #333333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #dddddd;
    }

    th {
        background-color: #7A888C;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr[style="amarillo-suave;"] {
        background-color: #E5E567;  /* Cambiar este color por un amarillo más suave */
    }

    a {
        color: #808080;
        text-decoration: underline;
    }

    a:hover {
        text-decoration: none;
    }
</style>
