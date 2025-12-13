<?php

  include("../include/config.php");
  include("../include/db_lib.php");
  include("../include/tables.php");

  $conn = conn();
  $nClcv = $_GET["nClcvcorrel"];

  // Enviaremos un xml

	$sql = "SELECT signed_lcv FROM lcvxml WHERE clcv_correl = '" . str_replace("'","''",$nClcv) . "'";
	$result = rCursor($conn, $sql);

	if (!$result->EOF) {
		$sXml = trim($result->fields["signed_lcv"]);
        	$sName = "libroxml.xml";
		$archivo = "../clie_elec/" . $sName;
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
