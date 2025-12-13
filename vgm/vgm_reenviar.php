<?php
  include("../include/config.php");
  include("../include/ver_aut.php");
//  include("../include/ver_aut_adm_super.php");
  include("../include/db_lib.php");
//  include("class_laudus.php");
//  include("../include/upload_class.php");


function reenviarXMLNew($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){  
global $_LINK_BASE_WS;

$serviceUrl = $_LINK_BASE_WS . "OpenDTEWS/services/ReenviaEmailDTE?wsdl";

try {
    $client = new SoapClient($serviceUrl);

    $params = array(
        'emisor' => $sEmisor,
        'folioDTE' => $nFolio,
        'tipoDTE' => $nTipoDTE,
        'tipoEnvio' => $nTipoEnvio,
        'destinatario' => $sDestinatario
    );

    $result = $client->reenviaEmailDTE($params);
    
    // Manejar la respuesta
    var_dump($result);
   return $result;
} catch (SoapFault $e) {
    // Manejar errores SOAP
    echo "Error: " . $e->getMessage();
    return $e;
}
}
	function reenviarXMLOld($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){	
		global $_URL_WS_DTE_, $_LINK_BASE_WS;
		$service = $_LINK_BASE_WS . "OpenDTEWS/services/ReenviaEmailDTE?wsdl";
		$aParam = array();	// parametros de la llamada

		$aParam["emisor"]=$sEmisor;			// rut con gion
		$aParam["folioDTE"]=$nFolio;
		$aParam["tipoDTE"]=$nTipoDTE;
		$aParam["tipoEnvio"]=$nTipoEnvio;				// PDF o XML
		$aParam["destinatario"]=$sDestinatario;
//		$aParam["args5"] = "";
		sleep(3);

		try {
			$client = new SoapClient($service, $aParam);
			$result = $client->reenviaEmailDTE($aParam);	// llamamos al métdo de reenviar PDF
//			print_r($result);
//	print_r($aParam);
//	exit;
			return $result;
		}
		catch (Exception $e) {
//			print_r($e);
//exit;
			return $e;
		}
	}

        function reenviarXML($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){
                global $_URL_WS_DTE_;

				try{

					$xmlRequest = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
				<soapenv:Envelope xmlns:soapenv=\"http://www.w3.org/2003/05/soap-envelope\" xmlns:ns=\"http://ws.opendte.cl\">
				   <soapenv:Header/>
				   <soapenv:Body>
					  <ns:reenviaEmailDTE>
						 <ns:emisor>$sEmisor</ns:emisor>
						 <ns:folioDTE>$nFolio</ns:folioDTE>
						 <ns:tipoDTE>$nTipoDTE</ns:tipoDTE>
						 <ns:tipoEnvio>$nTipoEnvio</ns:tipoEnvio>
						 <ns:destinatario>$sDestinatario</ns:destinatario>
					  </ns:reenviaEmailDTE>
				   </soapenv:Body>
				</soapenv:Envelope>";

				$url = 'http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/ReenviaEmailDTE.ReenviaEmailDTEHttpsEndpoint/';
				$headers = [
					'Content-Type: application/soap+xml; charset=utf-8',
					'Content-Length: ' . strlen($xmlRequest)
				];

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$response = curl_exec($ch);

				//if (curl_errno($ch)) {
				//    return 'Error en la solicitud cURL: ' . curl_error($ch);
				//}

				curl_close($ch);

				//echo $response;
				$aResp = explode("<ns:return>",$response);
				$aResp = explode("</ns:return>",$aResp[1]);
				$response = $aResp[0] . "";
//				echo $response;
				$response = html_entity_decode($response);
//				echo $response;
//				exit;
				return $response;

				// Procesar la respuesta SOAP recibida
				//echo 'Respuesta del servicio web:';
				//var_dump($response);

				} 
				catch (Exception $e) {
//					print_r($e);
//					exit;
					return "<RespuestaReenviaEmailDTE><Codigo>0</Codigo><Glosa>" . $e->getMessage() . "</Glosa></RespuestaReenviaEmailDTE>";

				}

        }  

  require_once("PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
//  require_once('nusoap-0.9.5/lib/nusoap.php');

  $conn = conn();


////header("Content-type:text/html; charset=utf-8");
//header("Access-Control-Allow-Origin:*");
////header('Access-Control-Allow-Methods:POST');
//header('Access-Control-Allow-Headers:x-requested-with, content-type');

//  Get the file passed in from the front end 
$file = $_FILES['sFileCaf'];
// Temporary file storage path 
$fileTmp = $file['tmp_name'];
$fileSize = $file['size'];
$fileName = $file['name'];
// error , Output 0, Indicates that the file was submitted successfully 
$fileError = $file['error'];

if($fileError==0 && $file != "") {
	$inputFileName = $fileTmp;
//	date_default_timezone_set('PRC');
	//  Read excel file 
	try {
	    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
	} catch(Exception $e) {
		echo $e->getMessage();
		exit;
	}
	//  Determine what to read sheet, What is? sheet, see excel In the lower right corner 
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();
	$highestColumn = $sheet->getHighestColumn();

	$time = date('Y-m-d h:i:s', time());
	//  The data is processed and stored in an array 
	$array = array();
	$time = date('Y-m-d h:i:s', time());
	$iniDTE = "";

	$xmlEnc = "";
	$xmlDet = "";
	$xmlRef = "";
	$numLinea = 1;
	$numLineaRef = 1;
	$iniDTEAnt = "";
	$iNum = 0;

	echo "<br><br><h3>Iniciando Proceso Archivo...</h3><table border=1><tr><th>Respuesta</th><th>Email</th></tr>";

	for ($row = 1; $row <= $highestRow; $row++) {
//		$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		$iniDTE = trim($rowData[0][0]);		

//		echo "$iniDTE OAOAO  $highestRow <br>";

		if($iniDTE == "ENC"){	// Comienza DTE	
			$tipoDTE = trim($rowData[0][1]);
			$folioDTE = trim($rowData[0][2]);
			$rutEmisor = strtoupper(trim($rowData[0][4]));
			$emailCliente = trim($rowData[0][10]);			

			$sDestinatario = str_replace(",",";",$emailCliente);
			$aEmail = explode(";",$sDestinatario);

			for($i=0; $i < sizeof($aEmail); $i++){
				if(strpos($aEmail[$i],"@") !== false){
					$msg = reenviarXML($rutEmisor, $folioDTE, $tipoDTE, "PDF", trim($aEmail[$i])); 			
					$estado = "0";
					$xml = simplexml_load_string($msg);
					$estado = (string) $xml->Codigo; // Obtener el estado como string

					if($estado == "1")
						echo "<tr><th>Folio $folioDTE enviado a </th><th>" . trim($aEmail[$i]) . "</th></tr>";
					else 
						echo "<tr><th bgcolor='#FCA7B2'>Error al enviar Folio $folioDTE a </th><th bgcolor='#FCA7B2'>" . trim($aEmail[$i]) . "</th></tr>";
//exit;
					sleep(1);
				}
			}

		}
	}

	echo "</table><br><h3>Fin Proceso del Archivo</h3>";

	//  Return to the front end after success 
//	echo "OK";
}


if($file=="") {
?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Grupo VGM</title>
  <script>
	function desac(){
		document._FFORM.b.disabled = true;
	}
  </script>

 </head>

 <style type="text/css">
body {
    padding: 0 0 0 6px;
    margin: 0px;
    margin-top: -1px;
}
body {
    background-color: #F9F9F9;
    background-image: url(../../images/left_bg.gif);
    background-position: bottom;
    background-repeat: no-repeat;
}
body {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 11px;
    font-weight: normal;
    color: #000000;

}
body {
    display: block;

}

 </style>
 <body>
 <br>
  <table>
  <tr>
	<td>
	<h2>Reenviar Email</h2>
	  <br><br>
	<form name="_FFORM" enctype="multipart/form-data" action="vgm_reenviar.php" method="post" onsubmit="desac();">
		<input type="hidden" name="MAX_FILE_SIZE" value="504857600">
		<input type="file" name="sFileCaf" id="sFileCaf" value="" size="25" maxlength="1000">
		<input type="submit" name="b">
	</form>
<br><br>
<a href="doc/formato.xls" target="_blank">Definici&oacute;n de Formato</a><br><br>
<a href="doc/ejemplo2.xlsx" target="_blank">Archivo de Ejemplo</a><br><br>
	</td>
	  </tr>
	  </table>
 </body>
</html>
<?php
		
}	
?>

