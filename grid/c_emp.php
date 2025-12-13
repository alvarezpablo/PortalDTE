<?php

require_once("phpGrid/conf.php");

$sql="select codi_empr, rs_empr from empresa";

echo $sql;


$dg = new C_DataGrid($sql, "codi_empr", "empresa");


// set height and weight of datagrid
//$dg -> set_dimension(800, 600);

$dg -> set_caption("Consulta Estado DTE Recibidos");

// increase pagination size to 40 from default 20
//$dg -> set_pagesize(40);

$dg -> enable_debug(true);
 
$dg -> display();

?>
