<?php

include("../include/config.php");
$_NO_MSG = true;

include("../include/db_lib.php");
include("../include/tables.php");

/*
*
*	Para Descargar PDF No requiero estar autenticado
*

include("../include/ver_aut.php");
include("../include/ver_emp_adm.php");


*/


$conn = conn();

  // Enviaremos un PDF
	$sUrlPdf = trim($_GET['sUrlPdf']);
	$t = $_GET['t'];
	$c = $_GET['c'];
	$f = $_GET['f'];
	$cd = $_GET['cd'];
       
	if ($sUrlPdf != "") {
		$sPDF = $sUrlPdf;
//		$rutE = trim($result->fields["rut"]);
		$archivo = $sPDF;
		
		$aPdf = explode("/",str_replace("%2F","/",$sPDF));
		$aPdf = explode("-",$aPdf[sizeof($aPdf)-1]);
		$cd=false;
		if($aPdf[0] == "cedible") 
			$cd=true;

		if($cd == false){
		  if(sizeof($aPdf) == 5){
			$rutSinDv = $aPdf[1];
			$rutE = $aPdf[1] . "-" . $aPdf[2];
			$t = $aPdf[3];
			$aPdf = explode(".",$aPdf[4]);
			$f = $aPdf[0];
		  }
		  else{
                        $rutSinDv = $aPdf[1];
                        $rutE = agregar_dv($aPdf[1]);//$aPdf[1] . "-" . calculaDV($aPdf[1]);
                        $t = $aPdf[2];
                        $aPdf = explode(".",$aPdf[3]);
                        $f = $aPdf[0];   
		   }
		}
		else {
		  if(sizeof($aPdf) == 6){
			$rutSinDv = $aPdf[2];
			$rutE = $aPdf[2] . "-" . $aPdf[3];
			$t = $aPdf[4];
			$aPdf = explode(".",$aPdf[5]);
			$f = $aPdf[0];			
		  }
		  else{
                        $rutSinDv = $aPdf[2];
                        $rutE = agregar_dv($aPdf[2]);// $aPdf[2] . "-" . calculaDV($aPdf[2]);
                        $t = $aPdf[3];
                        $aPdf = explode(".",$aPdf[4]);
                        $f = $aPdf[0];  
		  }
		}

$sql = "SELECT codi_empr FROM empresa WHERE rut_empr = '" . str_replace("'","''",$rutSinDv) . "' and fec_ter_contrato < now()";
$result = rCursor($conn, $sql);
if (!$result->EOF) {
//	$archivo = "Advertencia: El contrato asociado a su empresa ha expirado. Debe contactarse con su ejecutivo comercial";
	$archivo = "/opt/opendte/httpdocs/noaut.pdf";
	header("Content-Disposition: attachment; filename=noaut.pdf");
	header ("Content-Type: application/pdf");
	header ("Content-Length: ".filesize($archivo));
	readfile($archivo); 
//echo $archivo;
	exit;
}


		if (file_exists($archivo) == false){
// NEW
			$emisor = $rutE;
			$tipo = $t;
			$folio = $f;
			$apikey = "";

			$_WSDL_FIRMA = $_LINK_BASE_WS . "OpenDTEWS/services/ConsultaDTE";
			$resp = obtienePDFBase64($_WSDL_FIRMA, $emisor, $tipo, $folio,$apikey);
		//	echo $resp;

			foreach ($resp as $valor){
				$resXML=new SimpleXMLElement("<?xml version='1.0' encoding='ISO-8859-1'?>" . $valor);
				$estado=$resXML->Estado;
				$resulGlosa=$resXML->Glosa;

				if($estado== "1"){
					$DTERecibidos=$resXML->DTERecibidos;
		//			echo $DTERecibidos;
					$iniTag = "<pdf_base64>";
					$finTag = "</pdf_base64>";

					if($cd == true){
						$iniTag = "<pdfCedible_base64>";
						$finTag = "</pdfCedible_base64>";
					}

					$aXml = explode($iniTag,$DTERecibidos);
					if(sizeof($aXml) > 1){
						$aXml = explode($finTag,$aXml[1]);				
						//header("Content-type: application/pdf");
						if($cd == true)
							$sName  = "cedible-dte-$emisor-$tipo-$folio.pdf";
						else
							$sName  = "dte-$emisor-$tipo-$folio.pdf";
						header("Content-Disposition: attachment; filename=".basename($sName));
						header ("Content-Type: application/pdf");

						echo base64_decode($aXml[0]);
						echo "<script>window.close();</script>";
					}
					else
						echo "Error al generar PDF";
				}
				else
					echo "Error " . $resulGlosa;

				break;
			}
	exit;
// NEW
//	        $sBatch = "/opt/opendte/binJ7/bin/GeneraPDF.sh " . $rutE . " " . $t . " " . $f;
			$sBatch = "/opt/temp/binJ7/bin/GeneraPDF.sh " . $rutE . " " . $t . " " . $f;
			$locale='es_CL.ISO-8859-1';
			setlocale(LC_ALL,$locale);
			putenv('LC_ALL='.$locale);
			echo exec('locale charmap');
	        $p=exec($sBatch,$a,$b);

			$sql = "SELECT path_pdf, path_pdf_cedible FROM xmldte WHERE tipo_docu = '" . str_replace("'","''",$t) . "'
				 AND folio_dte = '" . str_replace("'","''",$f) . "' and 
				 codi_empr IN (SELECT codi_empr FROM empresa WHERE rut_empr = '" . str_replace("'","''",$rutSinDv) . "' )";
			$result = rCursor($conn, $sql);
			if (!$result->EOF) {
				if ($cd==true)
					$sPDF = trim($result->fields["path_pdf_cedible"]);
				else
					$sPDF = trim($result->fields["path_pdf"]); 
				$archivo = $sPDF;
//				header("Location:http://portaldte.opendte.cl/dte/view_pdf.php?sUrlPdf=" . $archivo);
//exit;
				header("Content-Disposition: attachment; filename=".basename($sPDF));
				header ("Content-Type: application/pdf");
				header ("Content-Length: ".filesize($archivo));
				readfile($archivo); 				
				echo "<script>window.close();</script>";

			}
		}
		else {
			header("Content-Disposition: attachment; filename=".basename($sPDF));
			header ("Content-Type: application/pdf");
			header ("Content-Length: ".filesize($archivo));
			readfile($archivo);
		}
	}
	else{
		echo "<script>window.close();</script>";
	}


function calculaDV($r){
     $s=1;
     for($m=0;$r!=0;$r/=10)
         $s=($s+$r%10*(9-$m++%6))%11;
  return $s;
}

function agregar_dv($_rol) {
    /* Bonus: remuevo los ceros del comienzo. */
    while($_rol[0] == "0") {
        $_rol = substr($_rol, 1);
    }
    $factor = 2;
    $suma = 0;
    for($i = strlen($_rol) - 1; $i >= 0; $i--) {
        $suma += $factor * $_rol[$i];
        $factor = $factor % 7 == 0 ? 2 : $factor + 1;
    }
    $dv = 11 - $suma % 11;
    /* Por alguna razón me daba que 11 % 11 = 11. Esto lo resuelve. */
    $dv = $dv == 11 ? 0 : ($dv == 10 ? "K" : $dv);
    return $_rol . "-" . $dv;
}


function url_origin($s, $use_forwarded_host=false) {

  $ssl = ( ! empty($s['HTTPS']) && $s['HTTPS'] == 'on' ) ? true:false;
  $sp = strtolower( $s['SERVER_PROTOCOL'] );
  $protocol = substr( $sp, 0, strpos( $sp, '/'  )) . ( ( $ssl ) ? 's' : '' );

  $port = $s['SERVER_PORT'];
  $port = ( ( ! $ssl && $port == '80' ) || ( $ssl && $port=='443' ) ) ? '' : ':' . $port;
  
  $host = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
  $host = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;

  return $protocol . '://' . $host;

}

function full_url( $s, $use_forwarded_host=false ) {
  return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
}

	function obtienePDFBase64($_WSDL_FIRMA, $emisor, $tipo, $folio,$apikey){
	//		$soapClient=new SoapClient($_WSDL_FIRMA.'?wsdl',array('encoding'=>'ISO-8859-1'));
			$soapClient=new SoapClient($_WSDL_FIRMA.'?wsdl');
			$soapClient->__setLocation($_WSDL_FIRMA);
			$parametros=array();
			$parametros["emisor"]=$emisor;
			$parametros["tipo"]=$tipo;
			$parametros["folio"]=$folio;
			$parametros["apikey"]=$apikey;
			$r = $soapClient->obtienePDFBase64($parametros);
	//		print_r($r);
			return $r;//->return;
	}

?>
