<?php
  include("../include/config.php");
//  include("../include/ver_aut.php");
//  include("../iFnclude/ver_aut_adm.php");
        include("../include/ver_aut.php");
    include("../include/ver_emp_adm.php");

  include("../include/db_lib.php");

	function reenviarXML($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){	
global $_LINK_BASE_WS;
/*echo "p1 ".$sEmisor."<br>";
echo "p2 ".$nFolio."<br>";
echo "p3 ".$nTipoDTE."<br>";
echo "p4 ".$nTipoEnvio."<br>";
echo "p5 ".$sDestinatario."<br>";
exit();*/
//		$service = "http://localhost:9000/OpenDTEWS/services/ReenviaEmailDTE?WSDL"; //url del servicio
		$service = $_LINK_BASE_WS . "OpenDTEWS/services/ReenviaEmailDTE?wsdl";
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
return "envio realizado...";
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
  $sql = "SELECT rut_empr, dv_empr FROM empresa WHERE codi_empr = '" . $_SESSION["_COD_EMP_USU_SESS"] . "'";
  $result = rCursor($conn, $sql);

  if(!$result->EOF) {
    $sEmisor = trim($result->fields["rut_empr"]) . "-" . trim($result->fields["dv_empr"]);
	//echo $sEmisor;
  }else{
	echo "<h2>Se debe seleccionar una empresa.</h2>";
	exit;
  }

	$nFolio = $_POST["nFolio"];
	$nTipoDTE = $_POST["nTipoDTE"];
	$nTipoEnvio = $_POST["nTipoEnvio"];
	$sDestinatario = $_POST["sDestinatario"];
	if($sDestinatario == "")
		$p = "<BR><BR><H2 align=center>Ingrese un Email para reenviar</H2>";
	else
		$p = reenviarXML($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario);		// Llamada a reenviar
		echo "Resultado : ".$p;	
//header("location:fin_reenvio.php?msj=".$p);
	exit;
?>
