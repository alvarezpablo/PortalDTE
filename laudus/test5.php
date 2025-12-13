<?php
		
	function reenviarXML($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){	
		global $_URL_WS_DTE_;
/*echo "p1 ".$sEmisor."<br>";
echo "p2 ".$nFolio."<br>";
echo "p3 ".$nTipoDTE."<br>";
echo "p4 ".$nTipoEnvio."<br>";
echo "p5 ".$sDestinatario."<br>";
exit();*/
//		$service = "http://localhost:9000/OpenDTEWS/services/ReenviaEmailDTE?WSDL"; //url del servicio
		$service = $_URL_WS_DTE_ . "/OpenDTEWS/services/ReenviaEmailDTE?wsdl";
		$aParam = array();	// parametros de la llamada

		$aParam["emisor"]=$sEmisor;			// rut con gion
		$aParam["folioDTE"]=$nFolio;
		$aParam["tipoDTE"]=$nTipoDTE;
		$aParam["tipoEnvio"]=$nTipoEnvio;				// PDF o XML
		$aParam["destinatario"]=$sDestinatario;
		
		$client = new SoapClient($service, $aParam);
		$result = $client->reenviaEmailDTE($aParam);	// llamamos al métdo de reenviar PDF
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

	$_URL_WS_DTE_ = "http://cloud-ws.opendte.cl:8080/";
	$rutEmpr = "76361106-K";
	$sDestinatario = "mauricio.escobar.a@gmail.com";
	$folio = "1141";
	$tipo = "33";
	

	reenviarXML($rutEmpr, $folio, $tipo, "XML", $sDestinatario); 

?>
