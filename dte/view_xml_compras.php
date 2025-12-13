<?php

include("../include/config.php");
include("../include/db_lib.php");
include("../include/tables.php");

include("../include/ver_aut.php");
include("../include/ver_emp_adm.php");


$conn = conn();

  // Enviaremos un XML
	$t = $_GET['t'];
	$c = $_GET['c'];
	$f = trim($_GET['f']);
	$r = trim($_GET['r']);
	$x = trim($_GET['x']);

	$sql = "SELECT xml_respuesta, xml_recibo_mercaderia, xml_est_res_rev FROM v_dte_recep WHERE tipo_docu = '" . str_replace("'","''",$t) . "' AND fact_ref = '" . str_replace("'","''",$f) . "' and codi_empr = '". $c . "'";
	$sql .= " AND rut_emite ='". $r ."'";

	$result = rCursor($conn, $sql);
        
	if (!$result->EOF) {
		if ($x == "AR")
                	$sXml = trim($result->fields["xml_respuesta"]);
		else if ($x == "RM")
                	$sXml = trim($result->fields["xml_recibo_mercaderia"]);
		else
                	$sXml = trim($result->fields["xml_est_res_rev"]);

                $sName = "dte-$r-$t-$f.xml";
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
