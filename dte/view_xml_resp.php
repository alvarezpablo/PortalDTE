<?php

  include("../include/config.php");
  include("../include/db_lib.php");
  include("../include/tables.php");

        include("../include/ver_aut.php");
    include("../include/ver_emp_adm.php");


  $conn = conn();
  $nFolioDte = $_GET["f"];
  $nTipoDocu = $_GET["t"];
  $c         = $_GET["c"];
  $o         = $_GET["o"]; // RM, AR, ARC

  // Enviaremos un xml

	$sql = "SELECT xml_recibo_mercaderias, xml_est_rec_doc, xml_est_res_rev FROM xmldte WHERE tipo_docu = '" . str_replace("'","''",$nTipoDocu) . "' AND folio_dte = '" . str_replace("'","''",$nFolioDte) . "' and codi_empr = '". $c . "'";
	$result = rCursor($conn, $sql);
        
	if (!$result->EOF) {
		if ($o == 'RM'){
			$sXml = trim($result->fields["xml_recibo_mercaderias"]);
		}
		else if ($o == 'AR'){
			$sXml = trim($result->fields["xml_est_rec_doc"]);
		}
		else {
			$sXml = trim($result->fields["xml_est_res_rev"]);
		}

        	$sName = "resp-$nTipoDocu-$nFolioDte-$c-$o.xml";
		$archivo = "/tmp/" . $sName;
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
