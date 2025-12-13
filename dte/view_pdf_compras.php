<?php

include("../include/config.php");

$_NO_MSG=true;
include("../include/db_lib.php");
include("../include/tables.php");

//include("../include/ver_aut.php");
//include("../include/ver_emp_adm.php");


$conn = conn();

  // Enviaremos un PDF
	$t = trim($_GET['t']);	// tipo docu
	$c = trim($_GET['c']);	// codigo empresa
	$f = trim($_GET['f']);	// folio
	$r = trim($_GET['r']);	// rut emisor 76158513-4

	$sql = "SELECT path_pdf, rut_empresa, rut_emite FROM v_dte_recep WHERE tipo_docu = '" . str_replace("'","''",$t) . "' AND fact_ref = '" . str_replace("'","''",$f) . "' and codi_empr = '". $c . "'";
	$sql .= " AND rut_emite ='". $r ."'";

	$result = rCursor($conn, $sql);
        
	if (!$result->EOF) {
		$sPDF = trim($result->fields["path_pdf"]);
		$receptor = trim($result->fields["rut_empresa"]);
		$rut_emp = trim($result->fields["rut_empresa"]);
		$rut_emp = trim(substr($rut_emp, 0, 8));
		$rut_emite = trim($result->fields["rut_emite"]);


			$tipo = $t;
			$folio = $f;
			$apikey = "";

			$_WSDL_FIRMA2 = $_LINK_BASE_WS . "OpenDTEWS/services/ConsultaDTECompras";
//			echo "$r, $receptor, $tipo, $folio, $apikey";
//			exit;
			$resp = obtienePDFCompraBase64($_WSDL_FIRMA2, $r, $receptor, $tipo, $folio,$apikey);

			foreach ($resp as $valor){
				$resXML=new SimpleXMLElement("<?xml version='1.0' encoding='ISO-8859-1'?>" . $valor);
				$estado=$resXML->Estado;
				$resulGlosa=$resXML->Glosa;

				if($estado== "1"){
					$DTERecibidos=$resXML->PDF;
					$iniTag = "<pdf_base64>";
					$finTag = "</pdf_base64>";


					$aXml = explode($iniTag,$DTERecibidos);
					if(sizeof($aXml) > 1){
						$aXml = explode($finTag,$aXml[1]);				
						//header("Content-type: application/pdf");
						if($cd == true)
							$sName  = "cedible-dte-$emisor-$tipo-$folio.pdf";
						else
							$sName  = "dte-$emisor-$tipo-$folio.pdf";
						header("Content-Disposition: attachment; filename=".basename($sPDF));
						header ("Content-Type: application/pdf");
						header ("Content-Length: ".filesize($archivo));

						echo base64_decode($aXml[0]);
						echo "<script>window.close();</script>";
					}
					else{
						echo "<script>alert(\"Error al generar PDF\");</script>";
						echo "<script>window.close();</script>";
					}
				}
				else{
					echo "<script>alert(\"Error " . $resulGlosa . "\");</script>";
					echo "<script>window.close();</script>";
				}
				break;

				
			} 
			
	exit;

/*
		echo "tipo DTE: " . $t . "<br>";
		echo "FOLIO DTE: " . $f . "<br>";
		echo "Emp: " . $c . "<br>";
		echo "Archivo: " . $sPDF . "<br>";

*/

	}
	else{
		echo "<script>window.close();</script>";
	}


	function obtienePDFCompraBase64($_WSDL_FIRMA2, $emisor, $receptor, $tipo, $folio, $apikey){
	//		$soapClient=new SoapClient($_WSDL_FIRMA.'?wsdl',array('encoding'=>'ISO-8859-1'));
			$parametros=array();
			$parametros["receptor"]=$receptor;
			$parametros["emisor"]=$emisor;
			$parametros["tipo"]=$tipo;
			$parametros["folio"]=$folio;
			$parametros["apikey"]=$apikey;

			try{
				$soapClient=new SoapClient($_WSDL_FIRMA2.'?wsdl');
				$soapClient->__setLocation($_WSDL_FIRMA2);
				$r = $soapClient->obtienePDFBase64($parametros);
			}
			catch (SoapFault $e){
				print_r($e);
				exit;
			}		
			return $r;//->return;
	}
?>
