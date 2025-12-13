<?php

  include("../include/config.php");
$_NO_MSG=true;
  include("../include/db_lib.php");
  include("../include/tables.php");

        include("../include/ver_aut.php");
    include("../include/ver_emp_adm.php");


  $conn = conn();
  $nFolioDte = $_GET["nFolioDte"];
  $nTipoDocu = $_GET["nTipoDocu"];
  $sRutEmi = $_GET["rutEmi"];

  // Enviaremos un xml

//	$sql = "SELECT xml FROM dte_recep WHERE tipo_docu = '" . str_replace("'","''",$nTipoDocu) . "' AND ndte_rec = '" . str_replace("'","''",$nFolioDte) . "' and codi_empr = '". trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";

$sql = "select xml_envr from envios_recibidos where correl_envr in (select correl_envr from dte_recep where rut_rec = '" . str_replace("'","''",$sRutEmi) . "' and codi_empr='". trim($_SESSION["_COD_EMP_USU_SESS"]) . "' and tipo_docu='" . str_replace("'","''",$nTipoDocu) . "' and ndte_rec='" . str_replace("'","''",$nFolioDte) . "')";
	$result = rCursor($conn, $sql);
        
	if (!$result->EOF) {
		$sXml = trim($result->fields["xml_envr"]);
        	$sName = "set-compra-$nTipoDocu-$nFolioDte.xml";
		$archivo = "../caf_file/" . $sName;
		$fp = fopen($archivo, "w");
		$write = fputs($fp, $sXml);
		fclose($fp); 

		header("Content-Disposition: attachment; filename=$sName");
		header ("Content-Type: application/octet-stream");
		header ("Content-Length: ".filesize($archivo));
		readfile($archivo);
		unlink($archivo);
	}
	else{
		echo "<script>window.close();</script>";
	}
?>
