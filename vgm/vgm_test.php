<?php
  include("../include/config.php");
  include("../include/ver_aut.php");
//  include("../include/ver_aut_adm_super.php");
  include("../include/db_lib.php");
//  include("class_laudus.php");
//  include("../include/upload_class.php");

	function reenviarXMLold($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){	
		global $_URL_WS_DTE_;
/*echo "p1 ".$sEmisor."<br>";
echo "p2 ".$nFolio."<br>";
echo "p3 ".$nTipoDTE."<br>";
echo "p4 ".$nTipoEnvio."<br>";
echo "p5 ".$sDestinatario."<br>";
exit();*/
//		$service = "http://localhost:9000/OpenDTEWS/services/ReenviaEmailDTE?WSDL"; //url del servicio
//		$service = $_URL_WS_DTE_ . "/OpenDTEWS/services/ReenviaEmailDTE?wsdl";
		$service = "http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/ReenviaEmailDTE?wsdl";
		$aParam = array();	// parametros de la llamada

		$aParam["emisor"]=$sEmisor;			// rut con gion
		$aParam["folioDTE"]=$nFolio;
		$aParam["tipoDTE"]=$nTipoDTE;
		$aParam["tipoEnvio"]=$nTipoEnvio;				// PDF o XML
		$aParam["destinatario"]=$sDestinatario;
		sleep(3);

		    $_LINK_BASE_WS = "http://cloud-ws.opendte.cl:8080/";
			$WSDLFirmaDTE = $_LINK_BASE_WS . "OpenDTEWS/services/ReenviaEmailDTE";

			$options = [
			'encoding' => 'ISO-8859-1',
				'trace' => true, // Habilitar el registro de la solicitud y la respuesta SOAP
				'exceptions' => true, // Habilitar el manejo de excepciones SOAP
				'connection_timeout' => 180, // Tiempo de espera de conexión en segundos
				'location' => 'http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/ReenviaEmailDTE.ReenviaEmailDTEHttpSoap11Endpoint/', // Ubicación  del endpoint
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
                global $_URL_WS_DTE_;
                $service = "http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/ReenviaEmailDTE";
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
					'connection_timeout' => 180, // Tiempo de espera de conexión en segundos
					'location' => $service . '.ReenviaEmailDTEHttpSoap11Endpoint/', // Ubicación  del endpoint
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

//		reenviarXML("99999999-9", "290", "33", "PDF", "mauricio.escobar.a@gmail.com");
//		exit;

  require_once("PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
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

	echo "<br><br><h3>Iniciando Proceso Archivo...</h3><table border=1><tr><th>Respuesta</th><th>PDF</th></tr>";

	for ($row = 1; $row <= $highestRow; $row++) {
//		$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		$iniDTE = $rowData[0][0];		

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

		echo "Folio $folioDTE - Fech 1  $fechaDTE ";


//			echo $fechaDTE . "<br>";
//			exit;
			$rutEmisor = strtoupper(trim($rowData[0][4]));
			$rutCliente = trim($rowData[0][5]);
			$nomCliente = str_replace("&","",trim($rowData[0][6]));
			$dirCliente = str_replace("&","",trim($rowData[0][7]));
			$comunaCliente = str_replace("&","",trim($rowData[0][8]));
			$giroCliente = str_replace("&","",trim($rowData[0][9]));
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


			$fPagoGlosa = trim($rowData[0][17]);
			$glosaMoneda = trim($rowData[0][18]);

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
			$nomItem = trim($rowData[0][3]);
			$descItem = trim($rowData[0][4]);
			$cantItem = round(trim($rowData[0][5]));
			$precioItem = round(trim($rowData[0][6]));
			$totalItem = round(trim($rowData[0][7]));
			
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
			<DscItem>$descItem</DscItem>
			<QtyItem>$cantItem</QtyItem>
			<PrcItem>$precioItem</PrcItem>
			<MontoItem>$totalItem</MontoItem>
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


	echo "</table><br><h3>Fin Proceso del Archivo</h3>";

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

	$xmlRequest = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
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
//echo $response;
$response = html_entity_decode($response);
//echo $response;
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

$_LINK_BASE_WS = "http://cloud-ws.opendte.cl:8080/";


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
        'connection_timeout' => 180, // Tiempo de espera de conexión en segundos
        'location' => 'http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/FirmaDTE.FirmaDTEHttpSoap11Endpoint/', // Ubicación  del endpoint
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

//		echo $xml;

	try {

		if(trim($folio) == ""){
			return array('estado' => 0, "glosa" => "Error al emitir, falta el folio del DTE");
		}

		try {
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
?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus">
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
	<h2>Emitir DTE</h2>
	  <br><br>
	<form name="_FFORM" enctype="multipart/form-data" action="vgm_test.php" method="post" onsubmit="desac();">
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

