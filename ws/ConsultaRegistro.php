<?php
//	ini_set('default_charset', '');

	include("nusoap-0.9.5/lib/nusoap.php");
	include("../include/config.php");
	include("../include/db_lib.php");
	$conn = conn();

// Configurando el web service
	$server = new soap_server();
	$server->configureWSDL("BajarLibro", "urn:XMLwsdl");
	$server->wsdl->schemaTargetNamespace = "urn:XMLwsdl";


//	$r = descargaRegistro("93129000-2", "201901", "REGISTRO", "");
//	print_r($r);
//	exit;

 	// Nuestra función que proporcionaremos
	function descargaRegistro($RutEmpresa, $periodo, $estado, $apikey) {
		global $conn;
		try{
			$aRut = explode("-",$RutEmpresa);

			$sql = "select path_certificado, clave_certificado, is_expired from certificado where rut_empresa='" . $aRut[0] . "' limit 1";
			$result = rCursor($conn, $sql);

			if (!$result->EOF) {
				$pathCert = trim($result->fields["path_certificado"]);
				$claveCert = trim($result->fields["clave_certificado"]);
				$isExpired = trim($result->fields["is_expired"]);

				$fileCert = file_get_contents($pathCert);
				$data64 = base64_encode($fileCert);

				 $client = new SoapClient("http://10.0.0.51/WSDescargaDTE.asmx?WSDL", array( "trace" => "1" ) );
				 $result = $client->DescargaDTECompraString( array( "cert" => $data64 ,"clave_cert" => $claveCert,"rutEmpr" => $aRut[0],"dvEmpr" => $aRut[1],"periodo" => $periodo,"estado" => $estado) );
//				 return $result->DescargaDTECompraStringResult; //$result["DescargaDTECompraStringResult"]; //"<RespuestaConsultaRegistroSII><Estado>0</Estado><Glosa>Error: Excepcin capturada: </Glosa></RespuestaConsultaRegistroSII>"; //$result["DescargaDTECompraStringResult"];

				 return "<RespuestaConsultaRegistroSII><Estado>1</Estado><Glosa>".  $result->DescargaDTECompraStringResult . "</Glosa></RespuestaConsultaRegistroSII>";

			}					
		}
		catch(Exception $e){
			return "<RespuestaConsultaRegistroSII><Estado>0</Estado><Glosa>Error: Excepción capturada: ".  $e->getMessage() . "</Glosa></RespuestaConsultaRegistroSII>";
		}

	}
 	// Registrando nuestra función descargaRegistro 
	$server->register(
			'descargaRegistro', // Nombre del método
			array('RutEmpresa' => 'xsd:string','periodo' => 'xsd:string','estado' => 'xsd:string','apikey' => 'xsd:string'), // Parámetros de entrada
			array('return' => 'xsd:string'), // Parámetros de salida
			'urn:XMLwsdl', // Nombre del workspace
			'urn:XMLwsdl#descargaRegistro', // Acción soap
			'rpc', // Estilo
			'encoded', // Uso
			'Bajar XML de Registro' // Documentación
	);


/***********************************************************/

	header("Content-type: text/xml");


	$HTTP_RAW_POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
	$server->service($HTTP_RAW_POST_DATA);

	exit;
?>