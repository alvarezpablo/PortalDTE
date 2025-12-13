<?php
//	ini_set('default_charset', '');

	include("nusoap-0.9.5/lib/nusoap.php");
	include("../include/config.php");
	include("../include/db_lib.php");
	$conn = conn();

// Configurando el web service
	$server = new soap_server();
	$server->configureWSDL("BajarXML", "urn:XMLwsdl");
	$server->wsdl->schemaTargetNamespace = "urn:XMLwsdl";

 	// Nuestra función que proporcionaremos
	function BajarXMLVenta($rutEmpr) {
		global $conn;

		$sql = "SELECT codi_empr, tipo_docu, folio_dte, signed_xdte FROM xmldte WHERE 
						codi_empr in (SELECT codi_empr FROM empresa where rut_empr='" . str_replace("'","''",$rutEmpr) . "') 
						AND COALESCE(is_expor,0) = 0 limit 1";
		$result = rCursor($conn, $sql);

		if (!$result->EOF) {
			$codi_empr = trim($result->fields["codi_empr"]);
			$tipo = trim($result->fields["tipo_docu"]);
			$folio_dte = trim($result->fields["folio_dte"]);
			$signed_xdte = trim($result->fields["signed_xdte"]);

			$sql = "UPDATE xmldte SET 
									is_expor=1
					WHERE	folio_dte = '" . str_replace("'","''",$folio_dte) . "' AND  
							tipo_docu = '" . str_replace("'","''",$tipo) . "' AND  
							codi_empr = $codi_empr";
			nrExecuta($conn, $sql);

			return $signed_xdte;
	///		echo $sql . "<br>";

		} 
		else
			return "";
	}

	function PendienteXMLVenta($rutEmpr) {
		global $conn;

		$sql = "SELECT count(codi_empr) as cant FROM xmldte WHERE 
						codi_empr in (SELECT codi_empr FROM empresa where rut_empr='" . str_replace("'","''",$rutEmpr) . "') 
						AND COALESCE(is_expor,0) = 0 ";
		$result = rCursor($conn, $sql);

		if (!$result->EOF) {
			$cant = trim($result->fields["cant"]);
			return "Pendientes de Descarga $cant";
	///		echo $sql . "<br>";

		} 
		else
			return "Nada Pendiente";
	}

	// Nuestra función que proporcionaremos
	function InicializarXMLVenta($rutEmpr) {
		global $conn;

		$sql = "SELECT codi_empr FROM empresa where rut_empr='" . str_replace("'","''",$rutEmpr) . "' ";
		$result = rCursor($conn, $sql);

		if (!$result->EOF) {
			$codi_empr = trim($result->fields["codi_empr"]);
			$sql = "UPDATE xmldte SET 
									is_expor=0
					WHERE codi_empr = $codi_empr";
			nrExecuta($conn, $sql);

			return "Inicializados XML de Venta de Empresa rut $rutEmpr ";
	///		echo $sql . "<br>";

		} 
		else
			return "Empresa no Existe";
	}

 	// Registrando nuestra función BajarXMLVenta 
	$server->register(
			'BajarXMLVenta', // Nombre del método
			array('rutEmpr' => 'xsd:string'), // Parámetros de entrada
			array('return' => 'xsd:string'), // Parámetros de salida
			'urn:XMLwsdl', // Nombre del workspace
			'urn:XMLwsdl#BajarXMLVenta', // Acción soap
			'rpc', // Estilo
			'encoded', // Uso
			'Bajar XML de Venta' // Documentación
	);

	$server->register(
			'InicializarXMLVenta', // Nombre del método
			array('rutEmpr' => 'xsd:string'), // Parámetros de entrada
			array('return' => 'xsd:string'), // Parámetros de salida
			'urn:XMLwsdl', // Nombre del workspace
			'urn:XMLwsdl#InicializarXMLVenta', // Acción soap
			'rpc', // Estilo
			'encoded', // Uso
			'Inicializar XML de Venta' // Documentación
	);


	$server->register(
			'PendienteXMLVenta', // Nombre del método
			array('rutEmpr' => 'xsd:string'), // Parámetros de entrada
			array('return' => 'xsd:string'), // Parámetros de salida
			'urn:XMLwsdl', // Nombre del workspace
			'urn:XMLwsdl#PendienteXMLVenta', // Acción soap
			'rpc', // Estilo
			'encoded', // Uso
			'Pendientes de Descarga XML de Venta' // Documentación
	);

/***********************************************************/

	// Nuestra función que proporcionaremos
	function BajarXMLCompra($rutEmpr) {
		global $conn;

		$sql = "SELECT xml, codi_empr, rut_rec, tipo_docu, ndte_rec FROM dte_recep WHERE 
						codi_empr in (SELECT codi_empr FROM empresa where rut_empr='" . str_replace("'","''",$rutEmpr) . "') 
						AND COALESCE(is_expor,0) = 0 limit 1";
		$result = rCursor($conn, $sql);

		if (!$result->EOF) {
			$codi_empr = trim($result->fields["codi_empr"]);
			$tipo = trim($result->fields["tipo_docu"]);
			$folio_dte = trim($result->fields["ndte_rec"]);
			$signed_xdte = trim($result->fields["xml"]);
			$sRutEmi = trim($result->fields["rut_rec"]);

			$sql = "UPDATE dte_recep SET 
									is_expor=1
					WHERE ndte_rec = '" . str_replace("'","''",$folio_dte) . "' AND  
							tipo_docu = '" . str_replace("'","''",$tipo) . "' AND  
							rut_rec = '" . str_replace("'","''",$sRutEmi) . "' AND
							codi_empr = $codi_empr";
			nrExecuta($conn, $sql);

			return $signed_xdte;
	///		echo $sql . "<br>";

		} 
		else
			return "";
	}

	function PendienteXMLCompra($rutEmpr) {
		global $conn;

		$sql = "SELECT count(codi_empr) as cant FROM dte_recep WHERE 
						codi_empr in (SELECT codi_empr FROM empresa where rut_empr='" . str_replace("'","''",$rutEmpr) . "') 
						AND COALESCE(is_expor,0) = 0 ";
		$result = rCursor($conn, $sql);

		if (!$result->EOF) {
			$cant = trim($result->fields["cant"]);
			return "Pendientes de Descarga $cant";
	///		echo $sql . "<br>";

		} 
		else
			return "Nada Pendiente";
	}

	// Nuestra función que proporcionaremos
	function InicializarXMLCompra($rutEmpr) {
		global $conn;

		$sql = "SELECT codi_empr FROM empresa where rut_empr='" . str_replace("'","''",$rutEmpr) . "' ";
		$result = rCursor($conn, $sql);

		if (!$result->EOF) {
			$codi_empr = trim($result->fields["codi_empr"]);
			$sql = "UPDATE dte_recep SET 
									is_expor=0
					WHERE codi_empr = $codi_empr";
			nrExecuta($conn, $sql);

			return "Inicializados XML de Compra de Empresa rut $rutEmpr ";
	///		echo $sql . "<br>";

		} 
		else
			return "Empresa no Existe";
	}

 	// Registrando nuestra función BajarXMLCompra 
	$server->register(
			'BajarXMLCompra', // Nombre del método
			array('rutEmpr' => 'xsd:string'), // Parámetros de entrada
			array('return' => 'xsd:string'), // Parámetros de salida
			'urn:XMLwsdl', // Nombre del workspace
			'urn:XMLwsdl#BajarXMLCompra', // Acción soap
			'rpc', // Estilo
			'encoded', // Uso
			'Bajar XML de Compra' // Documentación
	);

	$server->register(
			'InicializarXMLCompra', // Nombre del método
			array('rutEmpr' => 'xsd:string'), // Parámetros de entrada
			array('return' => 'xsd:string'), // Parámetros de salida
			'urn:XMLwsdl', // Nombre del workspace
			'urn:XMLwsdl#InicializarXMLCompra', // Acción soap
			'rpc', // Estilo
			'encoded', // Uso
			'Inicializar XML de Compra' // Documentación
	);


	$server->register(
			'PendienteXMLCompra', // Nombre del método
			array('rutEmpr' => 'xsd:string'), // Parámetros de entrada
			array('return' => 'xsd:string'), // Parámetros de salida
			'urn:XMLwsdl', // Nombre del workspace
			'urn:XMLwsdl#PendienteXMLCompra', // Acción soap
			'rpc', // Estilo
			'encoded', // Uso
			'Pendientes de Descarga XML de Compra' // Documentación
	);



	header("Content-type: text/xml");


	$HTTP_RAW_POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
	$server->service($HTTP_RAW_POST_DATA);

	exit;
?>