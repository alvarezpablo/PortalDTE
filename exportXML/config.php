<?php
session_start();
if ($_SESSION["_COD_USU_SESS"] == ''){
	header("location: ../login.php");
}


$locale='es_CL.ISO-8859-1';
setlocale(LC_ALL,$locale);
putenv('LC_ALL='.$locale);

$db_config = [
    'host' => '10.30.1.194',
    'database' => 'opendte',
    'user' => 'opendte',
    'password' => 'root8831'
];

// Obtener el código de empresa pasado como parámetro
// $_SESSION["_NUM_EMP_USU_SESS"]

$codi_empr = isset($_SESSION["_COD_EMP_USU_SESS"]) ? intval($_SESSION["_COD_EMP_USU_SESS"]) : 0;


$PATH="/opt/opendte/exportaXML/"



?>
