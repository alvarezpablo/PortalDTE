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
		$archivo = $sPDF;
		
		$aPdf = explode("/",str_replace("%2F","/",$sPDF));
		$aPdf = explode("-",$aPdf[sizeof($aPdf)-1]);
		$cd=false;
		if($aPdf[0] == "cedible") 
			$cd=true;

		if($aPdf[0] == "dteBoleta") {
				$t = $aPdf[1];				
				$f = $aPdf[2];	
				$aPdf3 = explode(".",$f);
				$f = $aPdf3[0];	

				$aPdf2 = explode("/",str_replace("%2F","/",$sPDF));
				$rutSinDv = $aPdf2[4];
				$rutE = agregar_dv($rutSinDv);
		}
		else{
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
		}
	}
	else{
		$sql = "SELECT rut_empr, dv_empr FROM empresa WHERE codi_empr = '" . str_replace("'","''",$c) . "'"; // and fec_ter_contrato < now()";
		$result = rCursor($conn, $sql);
		if (!$result->EOF) {
			$rutSinDv = trim($result->fields["rut_empr"]);
			$dv_empr = trim($result->fields["dv_empr"]);
			$rutE = $rutSinDv . "-" . $dv_empr;
		}
	}

			$emisor = $rutE;
			$tipo = $t;
			$folio = $f;
			$apikey = "";

			$_WSDL_FIRMA2 = $_LINK_BASE_WS . "OpenDTEWS/services/ConsultaDTE";
			echo "$emisor, $tipo, $folio,$apikey";
			$resp = obtienePDFBase64($_WSDL_FIRMA2, $emisor, $tipo, $folio,$apikey);
print_r($resp);
exit;
			foreach ($resp as $valor){
				$resXML=new SimpleXMLElement("<?xml version='1.0' encoding='ISO-8859-1'?>" . $valor);
				$estado=$resXML->Estado;
				$resulGlosa=$resXML->Glosa;

				if($estado== "1"){
					$DTERecibidos=$resXML->DTERecibidos;
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

	function obtienePDFBase64($_WSDL_FIRMA2, $emisor, $tipo, $folio,$apikey){
	//		$soapClient=new SoapClient($_WSDL_FIRMA.'?wsdl',array('encoding'=>'ISO-8859-1'));
			$parametros=array();
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
