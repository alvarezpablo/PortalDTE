<?php
//**************************************************************
// PROGRAMA QUE REENVIA XML DE FORMA INDIVIDUAL, INTERCOMPANY DE TAYLOR.
//**************************************************************


  include("../include/config.php");
//  include("../include/ver_aut.php");
//  include("../iFnclude/ver_aut_adm.php");
//        include("../include/ver_aut.php");
//    include("../include/ver_emp_adm.php");
  include("../include/db_lib.php");

	function reenviarXML($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){	
/*echo "p1 ".$sEmisor."<br>";
echo "p2 ".$nFolio."<br>";
echo "p3 ".$nTipoDTE."<br>";
echo "p4 ".$nTipoEnvio."<br>";
echo "p5 ".$sDestinatario."<br>";
exit();*/
//		$service = "http://localhost:9000/OpenDTEWS/services/ReenviaEmailDTE?WSDL"; //url del servicio
		$service = "http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/ReenviaEmailDTE?wsdl";
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
		return $result; //"envio realizado...";
	}

	function obj2array($obj) {
	  $out = array();
	  foreach ($obj as $key => $val) {
		switch(true) {
			case is_object($val):
			 $out[$key] = obj2array($val);
			 break;
		  case is_array($val):
			 $out[$key] = obj2array($val);
			 break;
		  default:
			$out[$key] = $val;
		}
	  }
	  return $out;
	}


  $conn = conn();
  $sql = " SELECT D.codi_empr, D.tipo_docu, D.folio_dte, D.rut_emis_dte, D.digi_emis_dte, D.rut_rec_dte, (select email_contr from contrib_elec where rut_contr=D.rut_rec_dte) AS email ";
  $sql .= " FROM dte_enc D, xmldte X WHERE D.fech_carg > (CURRENT_TIMESTAMP - CAST('7 days' AS INTERVAL)) and X.est_xdte>28 AND D.esta_inter IS NULL AND ";
  $sql .= " D.codi_empr = X.codi_empr AND D.folio_dte = X.folio_dte AND D.tipo_docu = X.tipo_docu AND D.rut_emis_dte IN (78782450, 82728500, 76075441, 87532700, 79665080) AND ";
  $sql .= " D.rut_rec_dte IN (78782450, 82728500, 76075441, 87532700, 79665080) ";
  $result = rCursor($conn, $sql);

  while (!$result->EOF) {
	$nCodEmpr = trim($result->fields["codi_empr"]);	  
    $sEmisor = trim($result->fields["rut_emis_dte"]) . "-" . trim($result->fields["digi_emis_dte"]);
	$nFolio = trim($result->fields["folio_dte"]);
	$nTipoDTE = trim($result->fields["tipo_docu"]);
	$nTipoEnvio = "XML";
	$sDestinatario = trim($result->fields["email"]);

	$p = reenviarXML($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario);		// Llamada a reenviar
	$a = explode("<Codigo>",$p->return);
	$a = explode("</Codigo>",$a[1]);

	if($a[0] == "1"){
		$sql = "UPDATE dte_enc SET esta_inter = 1 WHERE folio_dte = $nFolio AND tipo_docu= $nTipoDTE AND codi_empr = $nCodEmpr";
		nrExecuta($conn, $sql);
		echo "Enviado Folio $nTipoDTE, Tipo $nTipoDTE, Emisor $sEmisor, Receptor $sDestinatario\n<br>";
	}
	else
		echo "No Enviado Folio $nTipoDTE, Tipo $nTipoDTE, Emisor $sEmisor, Receptor $sDestinatario\n<br>";

	sleep(2);
	$result->MoveNext(); 

  }
	exit;
?>
