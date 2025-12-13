<?php
  include("../include/config.php");
  include("../include/ver_aut.php");
  include("../include/ver_aut_adm_super.php");
  include("../include/db_lib.php");
  include("class_laudus.php");
//  include("../include/upload_class.php");

	function reenviarXML($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){	
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
		
		try {
			$client = new SoapClient($service, $aParam);
			$result = $client->reenviaEmailDTE($aParam);	// llamamos al métdo de reenviar PDF
			
			$resp = $result->return;
			$xmlget = simplexml_load_string($resp);
			
			if(trim($xmlget->Codigo) == "1")
				return "XML Enviado";
			else
				return $xmlget->Glosa;

		}
		catch (Exception $e) {
			return "Email no enviado: $sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario.";
		}
	}

?>


<?php
	$accion = trim($_GET["op"]);
	$anio = trim($_GET["anio"]);
	$mes = trim($_GET["mes"]);
	$email = trim($_GET["email"]);

	if(strpos($email,"@") !== false)
		$email = trim($email);
	else 
		$email = "";

	if($accion == "R"){
?>

	<h2>Reenviar Periodo <?php echo $anio . "-" . $mes; ?> a Duemint dte@duemint.com</h2>
	<br>

	<table border="1">
	<tr>
		<td>Emisor</td>
		<td>Receptor</td>
		<td>Nombre Receptor</td>
		<td>Tipo</td>
		<td>Folio</td>
		<td>Email Destino</td>
	</tr>
<?php

		$conn = conn();
	  
//		$sql = "SELECT resp_openb, rut_emisor, dv_emisor, tipo_docu, folio_dte, rut_recep, nom_clie, email_clie FROM gpuerto_enc WHERE to_char(to_date(fecha_dte,'YYYY-MM'),'YYYY-MM') = '$anio-$mes' order by rut_emisor, rut_recep, tipo_docu, folio_dte";
		$sql = "SELECT tipo_docu, folio_dte, rut_emis_dte,digi_emis_dte,rut_rec_dte,dig_rec_dte,nom_rec_dte from dte_enc WHERE codi_empr IN (SELECT codi_empr from empresa where is_gpuerto = 1) AND to_char(to_date(fec_emi_dte,'YYYY-MM'),'YYYY-MM') = '$anio-$mes' order by rut_emis_dte, rut_rec_dte, tipo_docu, folio_dte ";

//		echo $sql;
		$result = rCursor($conn, $sql);
		while(!$result->EOF){

			$rut_emisor = trim($result->fields["rut_emis_dte"]);  
			$dv_emisor = trim($result->fields["digi_emis_dte"]);  
			$tipo_docu = trim($result->fields["tipo_docu"]);  
			$folio_dte = trim($result->fields["folio_dte"]);  
			$rut_recep = trim($result->fields["rut_rec_dte"]);  
			$nom_clie = trim($result->fields["nom_rec_dte"]);  
//			$folio_dte = $aXml[0];
//			$email_clie = trim($result->fields["email_clie"]);  
//			$xml = trim($result->fields["resp_openb"]);  
//			$xml = str_replace("&lt;","<",$xml);  
//			$xml = str_replace("&gt;",">",$xml);  
//			$aXml = explode("<F>",$xml);
//			$aXml = explode("</F>",$aXml[1]);
//			$folio_dte = $aXml[0];

			echo "	<tr>
			<td>$rut_emisor-$dv_emisor</td>
			<td>$rut_recep</td>
			<td>$nom_clie</td>
			<td>$tipo_docu</td>
			<td>$folio_dte</td>
			";
			
			$email_clie = str_replace(",",";",$email_clie);
			$aEmail = explode(";",$email_clie);
			
			$emailDest = "dte@duemint.com"; //"dte@duemint.com";
			//$emailDest = "mauricio.escobar.a@gmail.com";

			if($email != "")
				reenviarXML($rut_emisor ."-".$dv_emisor, $folio_dte, $tipo_docu, "XML", $email); 				
			$msg = reenviarXML($rut_emisor ."-".$dv_emisor, $folio_dte, $tipo_docu, "XML", $emailDest); 				
			if($email != "")
				echo "<td>$emailDest y $email ($msg) </td>";
			else 
				echo "<td>$emailDest ($msg) </td>";

/*			for($i=0; $i < sizeof($aEmail); $i++){
				if(strpos($aEmail[$i],"@") !== false){
					$emailDest = trim($aEmail[$i]);
					$msg = reenviarXML($rutEmpr, $folio, $tipo, "XML", $emailDest); 				
					echo "$emailDest ($msg) <br>";
				}
			}
*/
			echo "</tr> ";
			
			sleep(1);

			$result->MoveNext();
		}

		echo "</table>";

		echo "<br><br><br><a href='gpuerto_resend.php' >Volver</a>";
	}
else{
?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Grupo Puerto</title>
  <script>
	function desac(){
		document._FFORM.b.disabled = true;
	}
  </script>

 </head>
 <body>
 <br>
  <h2>Reenviar Periodo a Duemint dte@duemint.com</h2>
	  <br><br>
	<form name="_FFORM" enctype="multipart/form-data" action="gpuerto_resend.php" method="get" onsubmit="desac();">
		<input type="hidden" name="op" value="R">

  <table>
  <tr>
	<td>Año</td>
	<td>
		<select name="anio">
<?php 
		$fecha_actual = getdate();
		$anio =$fecha_actual['year'];
		$mon =date("m");
		echo "<option value='$anio' selected>$anio</option>";

		for($i=$anio-1; $i > 2021; $i--)
			echo "<option value='$i'>$i</option>";

?>
		</select>
	</td>

	<td>Mes</td>
	<td>
		<select name="mes">
			<option value="01" <?php if($mon == "01") echo "selected"; ?>>Enero</option>
			<option value="02" <?php if($mon == "02") echo "selected"; ?>>Febrero</option>
			<option value="03" <?php if($mon == "03") echo "selected"; ?>>Marzo</option>
			<option value="04" <?php if($mon == "04") echo "selected"; ?>>Abril</option>
			<option value="05" <?php if($mon == "05") echo "selected"; ?>>Mayo</option>
			<option value="06" <?php if($mon == "06") echo "selected"; ?>>Junio</option>
			<option value="07" <?php if($mon == "07") echo "selected"; ?>>Julio</option>
			<option value="08" <?php if($mon == "08") echo "selected"; ?>>Agosto</option>
			<option value="09" <?php if($mon == "09") echo "selected"; ?>>Septiembre</option>
			<option value="10" <?php if($mon == "10") echo "selected"; ?>>Octubre</option>
			<option value="11" <?php if($mon == "11") echo "selected"; ?>>Noviembre</option>
			<option value="12" <?php if($mon == "12") echo "selected"; ?>>Diciembre</option>
		</select>
	</td>

  </tr>
  	<tr><td colspan="4">
		<br><br>
		</td>
	</tr>

  	<tr>
		<td colspan="3">Email Para Recibir Copia<br>de Todos los DTE (Opcional)</td>
		<td><input type="text" name="email" value="" maxlength="150"></td>
	</tr>

	<tr><td colspan="4">
		<br><br>
		</td>
	</tr>

	<tr><td colspan="4">
		<input type="submit" name="b">
		</td>
	</tr>

 </table>
	</form>

 
 </body>
</html>
<?php
		
}	
?>

