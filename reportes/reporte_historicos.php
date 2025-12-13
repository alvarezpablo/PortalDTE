<?php 
include("../include/config.php");  
include("../include/db_lib.php"); 
?>
<?php
// Iniciar la sesión si aún no ha sido iniciada
session_start();


// Verificar si la variable de sesión existe
if (!isset($_SESSION["_COD_EMP_USU_SESS"])) {
    die("Acceso denegado.");
}

$codi_empr = $_SESSION["_COD_EMP_USU_SESS"];

// Obtener la conexión
$conn = conn();

try {
    // Preparar la consulta SQL utilizando ADOdb
    $query = "SELECT year, month, cantidad FROM reporte_mensual_dtes_historicos WHERE codi_empr = ? ORDER BY year, month";
    $stmt = $conn->Prepare($query);

    // Ejecutar la consulta
    $result = $conn->Execute($stmt, array($codi_empr));

    if ($result === false) {
        die("Error en la consulta: " . $conn->ErrorMsg());
    }

    // Iniciar la tabla HTML
    echo "<table border='1'>";
    echo "<tr><th>Año</th><th>Mes</th><th>Cantidad</th></tr>";

    // Iterar sobre los resultados y agregarlos a la tabla
    while (!$result->EOF) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($result->fields['year']) . "</td>";
        echo "<td>" . htmlspecialchars($result->fields['month']) . "</td>";
        echo "<td>" . htmlspecialchars($result->fields['cantidad']) . "</td>";
        echo "</tr>";

        $result->MoveNext();
    }

    // Finalizar la tabla
    echo "</table>";

    // Cerrar la conexión
    $result->Close();

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

