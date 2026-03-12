<?php
  include("../include/config.php");
  include("../include/ver_aut.php");
//  include("../include/ver_aut_adm_super.php");
  include("../include/db_lib.php");
//  include("class_laudus.php");
//  include("../include/upload_class.php");



	function reenviarXMLold($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){	
		global $_URL_WS_DTE_, $_LINK_BASE_WS;
/*echo "p1 ".$sEmisor."<br>";
echo "p2 ".$nFolio."<br>";
echo "p3 ".$nTipoDTE."<br>";
echo "p4 ".$nTipoEnvio."<br>";
echo "p5 ".$sDestinatario."<br>";
exit();*/
//		$service = "http://localhost:9000/OpenDTEWS/services/ReenviaEmailDTE?WSDL"; //url del servicio
//		$service = $_URL_WS_DTE_ . "/OpenDTEWS/services/ReenviaEmailDTE?wsdl";
		$service = $_LINK_BASE_WS . "OpenDTEWS/services/ReenviaEmailDTE?wsdl";
		$aParam = array();	// parametros de la llamada

		$aParam["emisor"]=$sEmisor;			// rut con gion
		$aParam["folioDTE"]=$nFolio;
		$aParam["tipoDTE"]=$nTipoDTE;
		$aParam["tipoEnvio"]=$nTipoEnvio;				// PDF o XML
		$aParam["destinatario"]=$sDestinatario;
		sleep(3);

		    $_LINK_BASE_WS = $_LINK_BASE_WS; // "http://cloud-ws.opendte.cl:8080/";
			$WSDLFirmaDTE = $_LINK_BASE_WS . "OpenDTEWS/services/ReenviaEmailDTE";

			$options = [
			'encoding' => 'ISO-8859-1',
				'trace' => true, // Habilitar el registro de la solicitud y la respuesta SOAP
				'exceptions' => true, // Habilitar el manejo de excepciones SOAP
				'connection_timeout' => 180, // Tiempo de espera de conexi�n en segundos
				'location' => $_LINK_BASE_WS . 'OpenDTEWS/services/ReenviaEmailDTE.ReenviaEmailDTEHttpSoap11Endpoint/', // Ubicaci�n  del endpoint
			'uri' => 'http://ws.opendte.cl',
			];

		try {
			//$client = new SoapClient($service, $aParam);
			$client = new SoapClient($WSDLFirmaDTE."?wsdl" , $options);		
			$result = $client->reenviaEmailDTE($aParam);	// llamamos al  de reenviar PDF
//			print_r($result);
			return $result;
		}
		catch (Exception $e) {
		//	echo "Email no enviado: $sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario.";
			print_r($e);
			return $e;
		}
		//aca bug
//$result = obj2array($result);
//print_r($result);
//exit();		
//print_r($result);
//exit();
		//if($result["reenviaEmailDTEReturn"][0] == "0")
		//	return "Se produjo un error al reenviar el DTE." . $result["reenviaEmailDTEReturn"];
		//else
		//	return "DTE Reenviado con exito.";
		//return "envio realizado...";
	}

        function reenviarXMLOld2($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){
                global $_URL_WS_DTE_, $_LINK_BASE_WS;
                $service = $_LINK_BASE_WS . "OpenDTEWS/services/ReenviaEmailDTE";
                $aParam = array();      // parametros de la llamada

                $aParam["emisor"]=$sEmisor;                     // rut con gion
                $aParam["folioDTE"]=$nFolio;
                $aParam["tipoDTE"]=$nTipoDTE;
                $aParam["tipoEnvio"]=$nTipoEnvio;                               // PDF o XML
                $aParam["destinatario"]=$sDestinatario;
//              $aParam["args5"] = "";
                sleep(3);


				$options = [
				'encoding' => 'ISO-8859-1',
					'trace' => true, // Habilitar el registro de la solicitud y la respuesta SOAP
					'exceptions' => true, // Habilitar el manejo de excepciones SOAP
					'connection_timeout' => 180, // Tiempo de espera de conexi�n en segundos
					'location' => $service . '.ReenviaEmailDTEHttpSoap11Endpoint/', // Ubicaci�n  del endpoint
				'uri' => 'http://ws.opendte.cl',
				];			


                try {
//                        $client = new SoapClient($service, $aParam);
						$client = new SoapClient($service."?wsdl" , $options);
                        $result = $client->reenviaEmailDTE($aParam);    // llamamos al mtdo de reenviar PDF
//                      print_r($result);
//      print_r($aParam);
//      exit;
                        return $result;
                }
                catch (Exception $e) {
//                      print_r($e);
//exit;
                        return $e;
                }
        }   

       function reenviarXML($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){
                global $_URL_WS_DTE_, $_LINK_BASE_WS;

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

				$url = $_LINK_BASE_WS . 'OpenDTEWS/services/ReenviaEmailDTE.ReenviaEmailDTEHttpsEndpoint/';
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

function convertEncoding($valor) {
    $encoding = mb_detect_encoding($valor, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
    if ($encoding !== 'ISO-8859-1') {
        return mb_convert_encoding($valor, 'ISO-8859-1', $encoding);
    }
    return $valor;
}

function convertUTF8($valor) {
    $encoding = mb_detect_encoding($valor, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
    if ($encoding !== 'UTF-8') {
        return mb_convert_encoding($valor, 'UTF-8', $encoding);
    }
    return $valor;
}

function renderVgmPageStart($eyebrow, $title, $meta, $panelTitle, $panelSubtitle, $chipText, $chipIcon = 'bi bi-box-seam') {
	echo <<<HTML
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Grupo VGM</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{margin:0;background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
		.page-shell{max-width:1120px;margin:0 auto;padding:16px}
		.topbar{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;margin-bottom:16px}
		.topbar-eyebrow{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#0b5ed7;margin-bottom:4px}
		.topbar-title{margin:0;font-size:28px;font-weight:700;color:#001f3f}
		.topbar-meta{margin-top:6px;max-width:760px;font-size:13px;color:#64748b}
		.topbar-chip{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border:1px solid #cfe0f5;border-radius:999px;background:#f8fbff;color:#0b5ed7;font-size:12px;font-weight:700}
		.panel{border:1px solid rgba(15,23,42,.08);border-radius:20px;box-shadow:0 16px 40px rgba(15,23,42,.08);overflow:hidden;background:#fff}
		.panel-header{padding:16px 20px;background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff}
		.panel-title{font-size:18px;font-weight:700}
		.panel-subtitle{margin-top:4px;font-size:13px;opacity:.92}
		.panel-body{padding:20px}
		.panel-note{background:#f8fbff;border:1px solid #d8e4f0;border-radius:16px;padding:14px 16px;margin-bottom:16px;font-size:13px;color:#334155}
		.upload-grid{display:flex;flex-wrap:wrap;gap:16px;align-items:end}
		.upload-field{flex:1 1 320px}
		.file-label{display:block;margin-bottom:6px;font-size:13px;font-weight:700;color:#334155}
		.file-input{display:block;width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:12px;background:#fff;color:#0f172a}
		.helper-links{display:flex;flex-wrap:wrap;gap:12px;margin-top:18px}
		.helper-link{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border:1px solid #d8e4f0;border-radius:999px;background:#fff;color:#0b5ed7;text-decoration:none;font-size:13px;font-weight:600}
		.helper-link:hover{background:#eff6ff;color:#0a58ca}
		.results-wrap{overflow-x:auto}
		.results-table{width:100%;border-collapse:collapse;background:#fff}
		.results-table th,.results-table td{padding:12px 14px;border:1px solid #d8e4f0;font-size:13px;vertical-align:top}
		.results-table th{background:#001f3f;color:#fff;font-weight:700}
		.results-table a{color:#0b5ed7;font-weight:700;text-decoration:none}
		.results-table a:hover{text-decoration:underline}
		.form-actions{display:flex;justify-content:flex-end;gap:12px;flex-wrap:wrap;margin-top:18px}
		@media (max-width: 768px){
			.page-shell{padding:12px}
			.topbar-title{font-size:24px}
			.panel-body{padding:16px}
			.form-actions .btn{width:100%}
		}
	</style>
</head>
<body>
	<div class="page-shell">
		<div class="topbar">
			<div>
				<div class="topbar-eyebrow">{$eyebrow}</div>
				<h1 class="topbar-title">{$title}</h1>
				<div class="topbar-meta">{$meta}</div>
			</div>
			<div class="topbar-chip"><i class="{$chipIcon}"></i> {$chipText}</div>
		</div>

		<div class="card panel">
			<div class="panel-header">
				<div class="panel-title">{$panelTitle}</div>
				<div class="panel-subtitle">{$panelSubtitle}</div>
			</div>
			<div class="card-body panel-body">
HTML;
}

function renderVgmPageEnd() {
	echo <<<HTML
			</div>
		</div>
	</div>
</body>
</html>
HTML;
}

//		reenviarXML("99999999-9", "290", "33", "PDF", "mauricio.escobar.a@gmail.com");
//		exit;

  // PhpSpreadsheet (reemplaza PHPExcel obsoleto)
  require_once dirname(__DIR__) . '/vendor/autoload.php';
  use PhpOffice\PhpSpreadsheet\IOFactory;
  require_once('nusoap-0.9.5/lib/nusoap.php');

  $conn = conn();

  $_USER_LAUDUS_ = "facturacion";
  $_PASS_LAUDUS_ = "facapi2022";
  $_RUT_EMP_LAUDUS_ = "76361106-K"; //"76361106-K"; // "76284914-3"; "76361105-1";
  $_URL_WS_DTE_ = $_LINK_BASE_WS;
//  $_URL_WS_DTE_ = "http://10.0.0.210:8080/";


////header("Content-type:text/html; charset=utf-8");
//header("Access-Control-Allow-Origin:*");
////header('Access-Control-Allow-Methods:POST');
//header('Access-Control-Allow-Headers:x-requested-with, content-type');
//mb_internal_encoding('latin1');


//  Get the file passed in from the front end 
$file = $_FILES['sFileCaf'];
// Temporary file storage path 
$fileTmp = $file['tmp_name'];
$fileSize = $file['size'];
$fileName = $file['name'];
// error , Output 0, Indicates that the file was submitted successfully 
$fileError = $file['error'];

if($fileError==0 && $file != "") {
		renderVgmPageStart(
			'Carga masiva VGM',
			'Emitir DTE desde Excel',
			'Se conserva intacto el flujo legacy de lectura del archivo, emision, firma y registro de documentos.',
			'<i class="bi bi-file-earmark-spreadsheet me-2"></i>Resultado del procesamiento',
			'Se muestran las respuestas generadas por el proceso actual y los enlaces PDF entregados por el flujo existente.',
			'Proceso en ejecucion',
			'bi bi-cloud-arrow-up'
		);
	$inputFileName = $fileTmp;
//	date_default_timezone_set('PRC');
	//  Read excel file 
	try {
	    $inputFileType = IOFactory::identify($inputFileName);
	    $objReader = IOFactory::createReader($inputFileType);

		$objPHPExcel = $objReader->load($inputFileName);


	} catch(Exception $e) {
			echo "<div class='alert alert-danger mb-0'>" . $e->getMessage() . "</div>";
			echo "<div class='form-actions'><a href='vgm.php' class='btn btn-outline-secondary'><i class='bi bi-arrow-left-circle me-2'></i>Volver</a></div>";
			renderVgmPageEnd();
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

		echo "<div class='panel-note'><strong>Iniciando proceso del archivo.</strong> El modulo mantiene el procesamiento original del Excel, el registro en base de datos y la emision/reenvio de cada DTE.</div>";
		echo "<div class='results-wrap'><table class='results-table'><tr><th>Respuesta</th><th>PDF</th></tr>";

	for ($row = 1; $row <= $highestRow; $row++) {
//		$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		$iniDTE = $rowData[0][0];		
//print_r($rowData);
		if($iniDTE == "ENC"){	// Comienza DTE
			if($xmlEnc != ""){	// Emite DTE
				if($conn->hasFailedTrans()){
					$conn->failTrans(); // rollback
				}
				else{
					if($existe == false){
						$xml = $xmlEnc . $xmlDet . $xmlRef;		
						$jsonString = $jsonEnc . "\"items\": [" . $jsonDet ."] } ";
//						echo $jsonString . "\n\n";
//						exit;
						$jsonLaudus = "";
						if($giroCliente == ""){
//							if($conn->hasFailedTrans()){
								echo "<tr><td bgcolor='#FCA7B2' colspan='2'>Error Falta el Giro del Receptor</td></tr>";
//								$conn->failTrans(); // rollback
//							}							
						}
						else{

							$sResp = emiteDTE($xml, $rutEmisor, $folioDTE, $tipoDTE, $conn, $jsonString, $cliExis, $emailCliente);		
		//			print_r($sResp);
		//			exit;
							if($sResp["estado"] == 1){
								if($conn->hasFailedTrans()){
									$conn->failTrans(); // rollback
									echo "<tr><td bgcolor='#FCA7B2' colspan='2'>Error al crear registro, no se puede procesar rut emisor $rutEmisor y folio " . $folioDTE . "</td></tr>";
								}
								else{
									$conn->completeTrans();	// completa 

									$aTipoDTE[$iNum] = $tipoDTE;
									$aFolioDTE[$iNum] = $folioDTE;
									$aRutEmisor[$iNum] = $rutEmisor;
									$aEmailCliente[$iNum] = $emailCliente;
									$iNum++;
									$sResp["pdf"] = str_replace("http://","https://",$sResp["pdf"]);

									if($sResp["estadoLaudus"] == "1"){
										echo "<tr><td bgcolor='#88FC71'>" . $sResp["glosa"] . " </td><td bgcolor='#88FC71'> <a href='" .$sResp["pdf"] ."' target='_blank'>Ver PDF</a></td>";
								//		echo "<td bgcolor='#88FC71'>" . $sResp["msgLaudus"] . " </td></tr>";
									}
									else{
										echo "<tr><td bgcolor='orange'>" . $sResp["glosa"] . " </td><td bgcolor='#88FC71'> <a href='" .$sResp["pdf"] ."' target='_blank'>Ver PDF</a></td>";
								//		echo "<td bgcolor='orange'>" . $sResp["msgLaudus"] . " </td></tr>";
									}
								}
							}
							else{
								echo "<tr><td bgcolor='#FCA7B2' colspan='2'>" . $sResp["glosa"] . "</td></tr>";
								//$conn->failTrans();	// rollback
							}
						}
					}
					else
						$conn->failTrans();	// rollback	
				}  
			}

			$jsonString = "";
			$jsonEnc = "";
			$jsonDet = "";
			$xmlEnc = "";
			$xmlDet = "";
			$xmlRef = "";
			$numLinea = 1;
			$numLineaRef = 1;

			$tipoDTE = trim($rowData[0][1]);
			$folioDTE = trim($rowData[0][2]);
/*			if($row == 1)
				$fechaDTE = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP(trim($rowData[0][3]))) ;
			else{
				$tim = PHPExcel_Shared_Date::ExcelToPHP(trim($rowData[0][3]));
				$tim += 86400; // Sumar un 
				$fechaDTE = date("Y-m-d", $tim);
			}
*/
			$fechaDTE = trim($rowData[0][3]);
//echo $fechaDTE;
//exit;
//		echo "Folio $folioDTE - Fech 1  $fechaDTE ";


//			echo $fechaDTE . "<br>";
//			exit;
			$rutEmisor = strtoupper(trim($rowData[0][4]));
			$rutCliente = trim($rowData[0][5]);
			$nomCliente = str_replace("&","",convertEncoding(trim($rowData[0][6])));
			$dirCliente = str_replace("&","",convertEncoding(trim($rowData[0][7])));
			$comunaCliente = str_replace("&","",convertEncoding(trim($rowData[0][8])));
			$giroCliente = str_replace("&","",convertEncoding(trim($rowData[0][9])));
			$emailCliente = trim($rowData[0][10]);
			$formaPago = trim($rowData[0][11]);
/*			$netoDTE = round(trim($rowData[0][12]));
			$exentoDTE = round(trim($rowData[0][13]));
			$ivaDTE = round(trim($rowData[0][14]));
			$tasaDTE = round(trim($rowData[0][15]));
			$totalDTE = round(trim($rowData[0][16]));
*/

                        $netoDTE = (trim($rowData[0][12]));
                        if($netoDTE == "") $netoDTE = 0;
                        $netoDTE = round($netoDTE);
                        $exentoDTE = (trim($rowData[0][13]));
                        if($exentoDTE == "") $exentoDTE = 0;
                        $exentoDTE = round($exentoDTE);
                        $ivaDTE = (trim($rowData[0][14]));
                        if($ivaDTE == "") $ivaDTE = 0;
                        $ivaDTE = round($ivaDTE);
                        $tasaDTE = (trim($rowData[0][15]));
                        if($tasaDTE == "") $tasaDTE = 0;
                        $tasaDTE = round($tasaDTE);
                        $totalDTE = (trim($rowData[0][16]));
                        if($totalDTE == "") $totalDTE = 0;
                        $totalDTE = round($totalDTE);  


			$fPagoGlosa = convertEncoding(trim($rowData[0][17]));
			$glosaMoneda = convertEncoding(trim($rowData[0][18]));

			if($exentoDTE == "") $exentoDTE = "0";

//			echo "Neto $netoDTE , IVA $ivaDTE , Total $totalDTE";
//			exit;


			$_RUT_EMP_LAUDUS_ = $rutEmisor; // "76361106-K";	// RUT EMITE

			$rutEmisor = str_replace(".","",$_RUT_EMP_LAUDUS_);  //"99999999-9";			// Rut de pruebas

			$aRut = explode("-",$rutEmisor);	
			$existe = false;

			$sql = "  SELECT tipo_docu FROM gpuerto_enc WHERE tipo_docu = '" . str_replace("'","''",$tipoDTE) . "' AND 
															rut_emisor = '" . str_replace("'","''",$aRut[0]) . "' AND 
															folio_erp = '" . str_replace("'","''",$folioDTE) . "' ";	

			$result = rCursor($conn, $sql);
			if(!$result->EOF){
				$existe = true;
				echo "<tr><td bgcolor='#FCA7B2' colspan='2'>Error registro ya existente, no se puede procesar rut emisor $rutEmisor y folio " . $folioDTE . "</td></tr>";
			} 
			else{
				$conn->startTrans();	// Comienza 

				$sql = "insert into gpuerto_enc(tipo_docu, rut_emisor, dv_emisor, folio_dte, folio_erp, fecha_dte, fecha_genera, rut_recep, nom_clie, dir_clie, comu_clie, giro_clie, email_clie, for_pago, neto, exento, iva, tasa, total, xml, resp_openb, estado, pdf, json_laudus, resp_laudus) 
				values(
				'" . str_replace("'","''", $tipoDTE) . "',
				'" . str_replace("'","''", $aRut[0]) . "',
				'" . str_replace("'","''", $aRut[1]) . "',
				'" . str_replace("'","''", $folioDTE) . "',
				'" . str_replace("'","''", $folioDTE) . "',
				'" . str_replace("'","''", $fechaDTE) . "',
				now(),
				'" . str_replace("'","''", $rutCliente) . "',
				'" . str_replace("'","''", $nomCliente) . "',
				'" . str_replace("'","''", $dirCliente) . "',
				'" . str_replace("'","''", $comunaCliente) . "',
				'" . str_replace("'","''", $giroCliente) . "',
				'" . str_replace("'","''", $emailCliente) . "',
				'" . str_replace("'","''", $formaPago) . "',
				'" . str_replace("'","''", $netoDTE) . "',
				'" . str_replace("'","''", $exentoDTE) . "',
				'" . str_replace("'","''", $ivaDTE) . "',
				'" . str_replace("'","''", $tasaDTE) . "',
				'" . str_replace("'","''", $totalDTE) . "',
				'" . str_replace("'","''", $xml) . "',
				'" . str_replace("'","''", "") . "',
				'" . str_replace("'","''", "") . "',
				'" . str_replace("'","''", "") . "',
				'" . str_replace("'","''", "") . "',
				'" . str_replace("'","''", "") . "')" ;
				nrExecuta($conn, $sql);

				$nomCliente = substr($nomCliente,0,40);
				$dirCliente = substr($dirCliente,0,70);
				$comunaCliente = substr($comunaCliente,0,20);
				$giroCliente = substr($giroCliente,0,40);
				$emailCliente = $emailCliente;

			}

			$sql = "SELECT rs_empr, giro_emp, cod_act, dir_empr, com_emp FROM empresa WHERE rut_empr = '" . str_replace("'","''",$aRut[0]) . "'";	
			$result = rCursor($conn, $sql);
			if(!$result->EOF){

				$existeEmpresa = true;
				$sRazEmpr = trim($result->fields["rs_empr"]);  
				$sGiroEmpr = trim($result->fields["giro_emp"]);
				$sCodActEmpr = trim($result->fields["cod_act"]);
				$sDirEmpr = trim($result->fields["dir_empr"]);  
				$sComuEmpr = trim($result->fields["com_emp"]);  

				$xmlEnc = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
				<DTE version=\"1.0\">
				<Documento ID=\"DTE-33-13325\">
				<Encabezado>
				<IdDoc>
				<TipoDTE>$tipoDTE</TipoDTE>
				<Folio>$folioDTE</Folio>
				<FchEmis>$fechaDTE</FchEmis>";

				if($tipoDTE == "39" || $tipoDTE == "41")
					$xmlEnc .= "<IndServicio>3</IndServicio>";
				else{
					if($tipoDTE == "56" || $tipoDTE == "61")
						$xmlEnc .= "<TpoTranVenta>1</TpoTranVenta>";
					else
						$xmlEnc .= "<TpoTranCompra>1</TpoTranCompra><TpoTranVenta>1</TpoTranVenta>";
				}

//			$fPagoGlosa = trim($rowData[0][17]);
//			$glosaMoneda = trim($rowData[0][18]);

				$xmlEnc .= "<FmaPago>$formaPago</FmaPago>
							<BcoPago>$glosaMoneda</BcoPago>
							<TermPagoGlosa>$fPagoGlosa</TermPagoGlosa>
				</IdDoc>
				<Emisor>
				<RUTEmisor>".str_replace(".","",$rutEmisor)."</RUTEmisor>";
				if($tipoDTE == "39" || $tipoDTE == "41")
					$xmlEnc .= "<RznSocEmisor>$sRazEmpr</RznSocEmisor>";
				else
					$xmlEnc .= "<RznSoc>$sRazEmpr</RznSoc>";
				$xmlEnc .= "<GiroEmis>$sGiroEmpr</GiroEmis>
				<Acteco>$sCodActEmpr</Acteco>
				<DirOrigen>$sDirEmpr</DirOrigen>
				<CmnaOrigen>$sComuEmpr</CmnaOrigen>
				<CiudadOrigen>$sComuEmpr</CiudadOrigen>
				</Emisor>
				<Receptor>
				<RUTRecep>".str_replace(".","",$rutCliente)."</RUTRecep>
				<RznSocRecep>$nomCliente</RznSocRecep>
				<GiroRecep>$giroCliente</GiroRecep>
				<DirRecep>$dirCliente</DirRecep>
				<CmnaRecep>$comunaCliente</CmnaRecep>
				</Receptor>
				<Totales>";

				if($tipoDTE == "41" || $tipoDTE == "34"){
					$xmlEnc .= "<MntExe>$exentoDTE</MntExe>";				
				}
				else{
					$xmlEnc .= "<MntNeto>$netoDTE</MntNeto>
					<MntExe>$exentoDTE</MntExe>
					<TasaIVA>$tasaDTE</TasaIVA>
					<IVA>$ivaDTE</IVA>";
				}

				$xmlEnc .= "<MntTotal>$totalDTE</MntTotal>
				</Totales>
				</Encabezado>
				";

//$jsonString  2022-02-28T20:32:51-03:00
				date_default_timezone_set('America/Santiago');
				$ltNow = new DateTime("NOW");    
				$ltNow = $ltNow->format('Y-m-d') . "T" .  $ltNow->format('H:i:s') . ".000Z";
				$cliExis = "0";


			}
			else{	// Empresa no existe
				$existeEmpresa = false;
				echo "<tr><td bgcolor='#FCA7B2' colspan='2'>Error empresa emisora no registrada en opendte, no se puede procesar rut emisor $rutEmisor y folio " . $folioDTE . "</td></tr>";
			}

		}


		if($iniDTE == "DET" && $existe == false && $existeEmpresa == true){	// LINEA DE DETALLE DTE
			$exenItem = trim($rowData[0][1]);
			$idItem = trim($rowData[0][2]);
			$nomItem = convertEncoding(trim($rowData[0][3]));
			$descItem = convertEncoding(trim($rowData[0][4]));
			$cantItem = trim($rowData[0][5]);
			if($cantItem == "") $cantItem = 0;
			$cantItem = round($cantItem);

			$precioItem = (trim($rowData[0][6]));
			if($precioItem == "") $precioItem = 0;
			$totalItem = (trim($rowData[0][7]));
			if($totalItem == "") $totalItem = 0;
			$precioItem = round($precioItem);
			$totalItem = round($totalItem);
		
			$sql = "insert into gpuerto_det(num_lin, tipo_docu, rut_emisor, folio_erp, id, nom, descrip, cant, exencion, precio, total) VALUES(
			'" . str_replace("'","''", $numLinea) . "',
			'" . str_replace("'","''", $tipoDTE) . "',
			'" . str_replace("'","''", $aRut[0]) . "',
			'" . str_replace("'","''", $folioDTE) . "',
			'" . str_replace("'","''", $idItem) . "',
			'" . str_replace("'","''", $nomItem) . "',
			'" . str_replace("'","''", $descItem) . "',
			'" . str_replace("'","''", $cantItem) . "',
			'" . str_replace("'","''", $exenItem) . "',
			'" . str_replace("'","''", $precioItem) . "',
			'" . str_replace("'","''", $totalItem) . "')"; 
			nrExecuta($conn, $sql);
			
			$xmlDet .= "<Detalle>
			<NroLinDet>$numLinea</NroLinDet>
			<CdgItem>
			<TpoCodigo>ID</TpoCodigo>
			<VlrCodigo>$idItem</VlrCodigo>
			</CdgItem>";
			if($exenItem != "0") $xmlDet .= "<IndExe>$exenItem</IndExe>";
			$xmlDet .= "<NmbItem>$nomItem</NmbItem>
			<DscItem>$descItem</DscItem>";

			if($cantItem != 0) $xmlDet .= "<QtyItem>$cantItem</QtyItem>";

			if($precioItem != 0)
				$xmlDet .= "<PrcItem>$precioItem</PrcItem>";
			
			$xmlDet .= "<MontoItem>$totalItem</MontoItem>
			</Detalle>
			";

		  $precioIva = round($precioItem * 0.19);
		  if($exenItem != "0") $precioIva = 0;



			$numLinea++;
		}

		if($iniDTE == "REF" && $existe == false && $existeEmpresa == true){	// LINEA DE DETALLE DTE
			$tipoRef = $rowData[0][1];
			$folioRef = $rowData[0][2];
//			$fechaRef = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($rowData[0][3]));
//			$tim = PHPExcel_Shared_Date::ExcelToPHP(trim($rowData[0][3]));
//			$tim += 86400; // Sumar un 
//			$fechaRef = date("Y-m-d", $tim);

			$fechaRef = $rowData[0][3];

//echo " REf $fechaRef <br><br>\n\n";
//exit;

			$codRef = $rowData[0][4];
			$motiRef = $rowData[0][5];


			$sql = "insert into gpuerto_ref(num_lin, tipo_docu, rut_emisor, folio_erp, tipo_ref, folio_ref, fecha_ref, cod_ref, motivo) VALUES(
			'" . str_replace("'","''", $numLineaRef) . "',
			'" . str_replace("'","''", $tipoDTE) . "',
			'" . str_replace("'","''", $aRut[0]) . "',
			'" . str_replace("'","''", $folioDTE) . "',
			'" . str_replace("'","''", $tipoRef) . "',
			'" . str_replace("'","''", $folioRef) . "',
			'" . str_replace("'","''", $fechaRef) . "',
			'" . str_replace("'","''", $codRef) . "',
			'" . str_replace("'","''", $motiRef) . "')"; 
			nrExecuta($conn, $sql);

			$xmlRef .= "<Referencia>
			<NroLinRef>$numLineaRef</NroLinRef>
			<TpoDocRef>$tipoRef</TpoDocRef>
			<FolioRef>$folioRef</FolioRef>
			<FchRef>$fechaRef</FchRef>";
			if($codRef != "") $xmlRef .= "<CodRef>$codRef</CodRef>";
			$xmlRef .= "<RazonRef>$motiRef</RazonRef>
			</Referencia>	
			";
			$numLineaRef++;
		}


//		echo $rowData;
//		print_r($rowData[0]);		// $rowData[0][1]
	}

	if($xmlEnc != "" && $existe == false && $existeEmpresa == true){	// Emite DTE
		$xml = $xmlEnc . $xmlDet . $xmlRef;	
		$jsonString = $jsonEnc . "\"items\": [" . $jsonDet ."] } ";

		if($conn->hasFailedTrans()){
			$conn->failTrans(); // rollback
			echo "<tr><td bgcolor='#FCA7B2' colspan='2'>Error al crear registro, no se puede procesar rut emisor $rutEmisor y folio " . $folioDTE . "</td></tr>";													
		}
		else{
			if($existe == false){
				$xml = $xmlEnc . $xmlDet . $xmlRef;		
				$jsonLaudus = "";

//				echo $jsonString . "\n\n";

				if($giroCliente == ""){
//					if($conn->hasFailedTrans()){
						echo "<tr><td bgcolor='#FCA7B2' colspan='2'>Error Falta el Giro del Receptor</td></tr>";
//						$conn->failTrans(); // rollback
//					}							
				}
				else{
					$sResp = emiteDTE($xml, $rutEmisor, $folioDTE, $tipoDTE, $conn,$jsonString, $cliExis, $emailCliente);		
			//		print_r($sResp);
			//		exit;

					if($sResp["estado"] == 1){
						if($conn->hasFailedTrans()){
							$conn->failTrans(); // rollback
							echo "<tr><td bgcolor='#FCA7B2' colspan='2'>Error al crear registro, no se puede procesar rut emisor $rutEmisor y folio " . $folioDTE . "</td></tr>";
						}
						else{
							$conn->completeTrans();	// completa 

							$aTipoDTE[$iNum] = $tipoDTE;
							$aFolioDTE[$iNum] = $folioDTE;
							$aRutEmisor[$iNum] = $rutEmisor;
							$aEmailCliente[$iNum] = $emailCliente;
							$iNum++;
							$sResp["pdf"] = str_replace("http://","https://",$sResp["pdf"]);

							if($sResp["estadoLaudus"] == "1"){
								echo "<tr><td bgcolor='#88FC71'>" . $sResp["glosa"] . " </td><td bgcolor='#88FC71'> <a href='" .$sResp["pdf"] ."' target='_blank'>Ver PDF</a></td>";
					//			echo "<td bgcolor='#88FC71'>" . $sResp["msgLaudus"] . " </td></tr>";
							}
							else{
								echo "<tr><td bgcolor='orange'>" . $sResp["glosa"] . " </td><td bgcolor='#88FC71'> <a href='" .$sResp["pdf"] ."' target='_blank'>Ver PDF</a></td>";
					//			echo "<td bgcolor='orange'>" . $sResp["msgLaudus"] . " </td></tr>";
							}
						}
					}
					else{
	//					echo $sResp["glosa"];
				//		$conn->failTrans();	// rollback
						echo "<tr><td>" . $sResp["glosa"] . "</td></tr>";
					}
				}
			}
			else
				$conn->failTrans();	// rollback	
		}  

		

/*
		$sResp = emiteDTE($xml, $rutEmisor, $folioDTE);		

		if($sResp["estado"] == 1){
			echo $sResp["pdf"] ."<br>";
			echo $nomCliente;
		}
		else{
			echo $sResp["glosa"];
		}  */
	}
	else{
		$conn->failTrans(); // rollback
	}

/**** Envio de Mail ****/	
	sleep(1);
	
	for($k=0; $k < count($aTipoDTE); $k++){
		$sDestinatario = str_replace(",",";",$aEmailCliente[$k]);
		$aEmail = explode(";",$sDestinatario);

		for($i=0; $i < count($aEmail); $i++){
			if(strpos($aEmail[$i],"@") !== false){
				$msg = reenviarXML($aRutEmisor[$k], $aFolioDTE[$k], $aTipoDTE[$k], "PDF", trim($aEmail[$i])); 				
				sleep(1);
//								echo $aRutEmisor[$k] . ' ' . $aFolioDTE[$k] . ' ' . $aTipoDTE[$k] . ' ' . "PDF" . ' ' . trim($aEmail[$i]) . " - <br>"; 
				//				print_r($msg);
			}
		}
	}

/**** Fin de Envio de Mail ****/


		echo "</table></div>";
		echo "<div class='panel-note mt-3 mb-0'><strong>Fin del proceso del archivo.</strong> Si necesita ejecutar una nueva carga, puede volver al formulario sin modificar el comportamiento del modulo.</div>";
		echo "<div class='form-actions'><a href='vgm.php' class='btn btn-outline-secondary'><i class='bi bi-arrow-left-circle me-2'></i>Cargar otro archivo</a></div>";
		renderVgmPageEnd();

	//  Return to the front end after success 
//	echo "OK";
}

  function idCliLaudus($rutCli){
	  global $_USER_LAUDUS_, $_PASS_LAUDUS_, $_RUT_EMP_LAUDUS_;
//		$rutCli = "76.720.094-3";

		$ejemplo = new laudusAPI_Ejemplos();
		if ($ejemplo->obtenerToken($_USER_LAUDUS_,$_PASS_LAUDUS_,$_RUT_EMP_LAUDUS_)) {
			if ($ejemplo->getListCliente("VATId", $rutCli)) {
				//$msgLaudus = "Cliente : " . $ejemplo->invoice->salesInvoiceId;
			//	print_r($ejemplo);
				$idc = trim($ejemplo->listCli[0]->customerId);
				if($idc == "")
					return array('estado' => "0", "glosa" => "Cliente no Existe","id" => ""); 
				else
					return array('estado' => "1", "glosa" => "OK", "id" => $idc); 
			}
			else{
//				print_r($ejemplo);
//				echo $ejemplo->mensaje;
//				exit;
				$msgLaudus = substr($ejemplo->mensaje,0,250);
				return array('estado' => "0", "glosa" => $msgLaudus,"id" => ""); 
			}
//			$respLaudus = json_encode($ejemplo->listCli);
		}
		else{
			 return array('estado' => "0", "glosa" => "Error al optener Token de Laudus","id" => ""); 
		}		
  }


//include("inc/funciones.php");
function firmarDTE($xml, $rutEmisor, $dvEmisor, $tipoDTE="")
{
try{
	$base64EncodedXml = base64_encode($xmlContent);

	$xmlRequest = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
<soapenv:Envelope xmlns:soapenv=\"http://www.w3.org/2003/05/soap-envelope\" xmlns:ns=\"http://ws.opendte.cl\">
   <soapenv:Header/>
   <soapenv:Body>
      <ns:firmaDTE>
         <ns:RUTEmisor>$rutEmisor</ns:RUTEmisor>
         <ns:DVEmisor>$dvEmisor</ns:DVEmisor>
         <ns:tipoArchivo>XML</ns:tipoArchivo>
         <ns:archivo><![CDATA[$xml]]></ns:archivo>
         <ns:apikey></ns:apikey>
      </ns:firmaDTE>
   </soapenv:Body>
</soapenv:Envelope>";

$url = 'http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/FirmaDTE.FirmaDTEHttpEndpoint/';
$headers = [
    'Content-Type: application/soap+xml; charset=ISO-8859-1',
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
//echo $response;
$response = html_entity_decode($response);
//echo "OAOA" . $response;
//exit;

return $response;

// Procesar la respuesta SOAP recibida
//echo 'Respuesta del servicio web:';
//var_dump($response);

} 
catch (Exception $e) {
//	print_r($e);
//	exit;
	return "<RespuestaFirmaDTE><Estado>3000</Estado><Glosa>" . $e->getMessage() . "</Glosa></RespuestaFirmaDTE>";

}
}
/*
function firmarDTEOld($xml, $rutEmisor, $dvEmisor, $tipoDTE=""){
	global $_LINK_BASE_WS ;



$WSDLFirmaDTE= $_LINK_BASE_WS . "OpenDTEWS/services/FirmaDTE";
if ($tipoDTE== "39" || $tipoDTE== "41")
$WSDLFirmaDTE= $_LINK_BASE_WS . "OpenDTEWS/services/FirmaBoleta";
if ($tipoDTE== "110" || $tipoDTE== "111" || $tipoDTE== "112")
$WSDLFirmaDTE= $_LINK_BASE_WS . "OpenDTEWS/services/FirmaDTEExportacion";
if ($tipoDTE== "43")
$WSDLFirmaDTE= $_LINK_BASE_WS .  "OpenDTEWS/services/FirmaDTELiquidacion";

try {

    $serviceEndpoint = $_LINK_BASE_WS . "OpenDTEWS/services/FirmaDTE.FirmaDTEHttpSoap11Endpoint/";

    $options = [
	'encoding' => 'ISO-8859-1',
        'trace' => true, // Habilitar el registro de la solicitud y la respuesta SOAP
        'exceptions' => true, // Habilitar el manejo de excepciones SOAP
        'connection_timeout' => 180, // Tiempo de espera de conexi�n en segundos
        'location' => 'http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/FirmaDTE.FirmaDTEHttpSoap11Endpoint/', // Ubicaci�n  del endpoint
	'uri' => 'http://ws.opendte.cl',
    ];
$soapClient = new SoapClient($WSDLFirmaDTE."?wsdl" , $options);
//$soapClient=new SoapClient($WSDLFirmaDTE.'?wsdl',$params);
//$soapClient->__setLocation($WSDLFirmaDTE . ".FirmaDTEHttpSoap11Endpoint/");
$parametros=array();
$parametros["RUTEmisor"]=$rutEmisor;
$parametros["DVEmisor"]=$dvEmisor;
$parametros["tipoArchivo"]="XML";
$parametros["archivo"]=$xml;
//primt_r($parametros);
//exit;
if($tipoDTE == "110" || $tipoDTE== "111" || $tipoDTE== "112" || $tipoDTE== "43"){
	$r=$soapClient->firmaDTEExportacion($parametros);
}
else{
	if ($tipoDTE== "39" || $tipoDTE== "41"){

	//	$parametros["apikey"]="";
		$r=$soapClient->firmaDTE($parametros);
	//	$r=$soapClient->__soapCall('firmaDTE', [$parametros]);
	}
	else{
		$parametros["apikey"]="";
//print_r($soapClient);
	//	$r=$soapClient->__soapCall("firmaDTE", [$parametros]); 

//print_r($parametros);
//exit;

		$r=$soapClient->firmaDTE($parametros);
/*
    $r = $soapClient->firmaDTE([
        'RUTEmisor' => '99999999',
        'DVEmisor' => '9',
        'tipoArchivo' => 'XML',
        'archivo' => $xml,
        'apikey' => ''
    ]);
* /
	 //   var_dump($r);

   }
}
 
return $r;		
} 
catch (Exception $e) {
//print_r($e);

//exit;
	return $e;

}
}

*/

  function emiteDTE($xml, $rutEmpr, $folio, $tipo, $conn, $jsonString, $cliExis, $sDestinatario){
	  global $_USER_LAUDUS_, $_PASS_LAUDUS_, $_RUT_EMP_LAUDUS_, $_URL_WS_DTE_;

		$xml .= "<TmstFirma>" . date("Y-m-d") . "T" . date("H:i:s") . "</TmstFirma></Documento></DTE>";

		$aRut = explode("-",$rutEmpr);	
//echo "RUT: $rutEmpr <br><br>";
//
//		echo $xml;
//exit;

	try {

		if(trim($folio) == ""){
			return array('estado' => 0, "glosa" => "Error al emitir, falta el folio del DTE");
		}

		try {
			$xml = convertEncoding($xml);
//echo "<textarea>$xml</textarea>";
		//	$xml = convertUTF8($xml);   
			$result = firmarDTE($xml, $aRut[0], $aRut[1]);
//print_r($result );
			$estado=0;
			$pdf="";
			$resulGlosa="Formato respuesta incorrecto";
//			$respOpen = $result["return"];
//			echo $respOpen . "OAOAO";


			$xml = simplexml_load_string($result);
			$estado = (string) $xml->Estado; // Obtener el estado como string
			$resulGlosa = (string) $xml->Glosa;   // Obtener la glosa como string
			if($estado== "1"){$pdf=(string) $xml->URLpdf;}
/*
			foreach ($result as $valor){
				$valor = (string)$valor;
//				echo $valor;
//				exit;

				$resXML=new SimpleXMLElement("<?xml version='1.0' encoding='ISO-8859-1'?>" . $valor);
				$estado=$resXML->Estado;
				$pdf="";
				if($estado== "1"){$pdf=$resXML->URLpdf;}
				$resulGlosa=$resXML->Glosa;
//				$respuesta->setValor($pdf,$estado,$resulGlosa);
				break;
			}  
			*/
//			echo $estado . " : " . $pdf . " " . $resulGlosa;

				$estdb = $estado;
				if($estdb != "1") $estdb = "0";

				$respLaudus = "";
				$msgLaudus = "";
				$estLaudus = 0;

				if($estado == "1" && trim($sDestinatario) != ""){
//					$sDestinatario = "mauricio.escobar.a@gmail.com";
//					echo "$rutEmpr, $folio, $tipo, $sDestinatario";
					$sDestinatario = str_replace(",",";",$sDestinatario);
					$aEmail = explode(";",$sDestinatario);

					for($i=0; $i < sizeof($aEmail); $i++){
						if(strpos($aEmail[$i],"@") !== false){
			//				$msg = reenviarXML($rutEmpr, $folio, $tipo, "PDF", trim($aEmail[$i])); 				
						}
					}
				}					

			if($estado == "1")
				return array('estado' => "1", "glosa" => "Procesado rut emisor $rutEmpr, folio " . $folio . " y tipo $tipo", "pdf" => $pdf, "estadoLaudus" => "1", "msgLaudus" => "Procesado rut emisor $rutEmpr, folio " . $folio . " y tipo $tipo" );
			else
				return array('estado' => $estdb, "glosa" => "Error al procesar rut emisor $rutEmpr y folio " . $folio . ". " . $resulGlosa );
		}

		catch (Exception $e) {
			return array('estado' => 0, "glosa" => "Error al consumir WS al procesar rut emisor $rutEmpr y folio " . $folio );
		}

	} catch (Exception $e) {
		return array('estado' => 0, "glosa" => "Error Excepcion capturada al procesar rut emisor $rutEmpr y folio " . $folio . " " . $e->getMessage());
	}
  }

if($file=="") {
	renderVgmPageStart(
		'Carga masiva VGM',
		'Emitir DTE',
		'Cargue el archivo Excel del proceso VGM usando el mismo endpoint, el mismo nombre de campo y el mismo comportamiento del formulario legacy.',
		'<i class="bi bi-cloud-arrow-up me-2"></i>Subida de archivo',
		'Se conserva el envio hacia vgm.php con el campo sFileCaf y el bloqueo del boton al enviar.',
		'Grupo VGM',
		'bi bi-building'
	);
	?>
	<script>
		function desac(){
			document._FFORM.b.disabled = true;
		}
	</script>

	<div class="panel-note">
		Seleccione el archivo de carga y ejecute el proceso con la misma logica actual. Los documentos de apoyo se mantienen disponibles para revisar el formato esperado.
	</div>

	<form name="_FFORM" enctype="multipart/form-data" action="vgm.php" method="post" onsubmit="desac();">
		<input type="hidden" name="MAX_FILE_SIZE" value="504857600">
		<div class="upload-grid">
			<div class="upload-field">
				<label class="file-label" for="sFileCaf">Archivo Excel</label>
				<input type="file" name="sFileCaf" id="sFileCaf" value="" size="25" maxlength="1000" class="file-input">
			</div>
			<div class="form-actions mt-0">
				<input type="submit" name="b" class="btn btn-primary" value="Procesar archivo">
			</div>
		</div>
	</form>

	<div class="helper-links">
		<a href="doc/formato.xls" target="_blank" class="helper-link"><i class="bi bi-file-earmark-text"></i>Definicion de formato</a>
		<a href="doc/ejemplo2.xlsx" target="_blank" class="helper-link"><i class="bi bi-file-earmark-spreadsheet"></i>Archivo de ejemplo</a>
	</div>
	<?php
	renderVgmPageEnd();
			
}	
?>

