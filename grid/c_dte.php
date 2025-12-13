<?php

require_once("phpGrid/conf.php");

// select codi_empr, tipo_docu, folio_dte from v_dte where codi_empr=9;


$sql = "select codi_empr, tipo_docu, folio_dte from v_dte";

echo $sql;

$dg = new C_DataGrid($sql, "codi_empr, tipo_docu, folio_dte", "v_dte");

$dg -> set_query_filter("codi_empr = 14");

// set height and weight of datagrid
$dg -> set_dimension(800, 600);

$dg -> set_caption("Consulta Estado DTE Recibidos");



// increase pagination size to 40 from default 20
$dg -> set_pagesize(40);

$dg -> enable_debug(true);
 
$dg -> display();

?>
