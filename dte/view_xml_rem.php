<?php

  include("../include/config.php");
$_NO_MSG=true;
  include("../include/db_lib.php");
  include("../include/tables.php");

        include("../include/ver_aut.php");
    include("../include/ver_emp_adm.php");


  $conn = conn();
  $nFolioDte = $_GET["f"];
  $nTipoDocu = $_GET["t"];
  $c         = $_GET["c"];

  // Enviaremos un xml

	$sql = "SELECT signed_xdte FROM xmldte WHERE tipo_docu = '" . str_replace("'","''",$nTipoDocu) . "' AND folio_dte = '" . str_replace("'","''",$nFolioDte) . "' and codi_empr = '". $c . "'";
	$result = rCursor($conn, $sql);
        
	if (!$result->EOF) {
		$sXml = trim($result->fields["signed_xdte"]);
        	$sName = "dte-$nTipoDocu-$nFolioDte.xml";
		$archivo = "../caf_file/" . $sName;
		$fp = fopen($archivo, "w");
		$write = fputs($fp, $sXml);
		fclose($fp); 

		header("Content-Disposition: attachment; filename=".$sName);
		header ("Content-Type: application/xml");
		header ("Content-Length: ".filesize($archivo));
		readfile($archivo);
		unlink($archivo);
	}
	else{
		echo "<script>window.close();</script>";
	}
?>
