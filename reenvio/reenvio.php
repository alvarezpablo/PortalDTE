<?php 
	  include("../include/config.php");
	  include("../include/ver_aut.php");
	  include("../include/ver_aut_adm_super.php");
	  include("../include/ver_emp_adm.php");
	  include("../include/db_lib.php");

?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="latin1">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Grupo Puerto</title>
  <script>
	function desac(){
		document._FFORM.b.disabled = true;
	}

    function chListBoxSelect(obj, valor){
		obj.options[0].selected = true;

        for(i=0; i < obj.length; i++){
          if(obj.options[i].value == valor){
            obj.options[i].selected = true;
			break;
		  }
        }
      }

  </script>

 </head>
 <body>
 <br>
	<form name="_FFORM" action="reenvio.php" method="get" onsubmit="desac();">
		<input type="hidden" name="e" value="1">

	<table>
		<tr>
			<td>Tipo DTE:  </td>
			<td><select name="tipo">
					<option value="33" selected="">Factura Electr&oacute;nica</option>
					<option value="34">Factura No Afecta o Exenta Electr&oacute;nica</option>
					<option value="39">Boleta Electr&oacute;nica</option>
					<option value="41">Boleta Exenta Electr&oacute;nica</option>
					<option value="43">Liquidaci&oacute;n Factura Electr&oacute;nica</option>
					<option value="46">Factura de Compra Electr&oacute;nica</option>
					<option value="52">Guía de Despacho Electr&oacute;nica</option>
					<option value="56">Nota de D&eacute;bito Electr&oacute;nica</option>
					<option value="61">Nota de Cr&eacute;dito Electr&oacute;nica</option>
					<option value="110">Factura de Exportaci&oacute;n Electr&oacute;nica</option>
					<option value="111">Nota de D&eacute;bito de Exportaci&oacute;n Electr&oacute;nica</option>
					<option value="112">Nota de Cr&eacute;dito de Exportaci&oacute;n Electr&oacute;nica</option>
					<option value="">Todos</option>
				</select>
				<script> chListBoxSelect(document._FFORM.tipo, "<?php echo $_GET["tipo"]; ?>"); </script>
			</td>
		  </tr>
		<tr>
			<td>Folio DTE: </td>
			<td><input type="text" name="folio" value="<?php echo $_GET["folio"]; ?>"> </td>
		</tr>
		<tr>
			<td>Rut Receptor (Sin digito Verificador): </td>
			<td><input type="text" name="rut" value="<?php echo $_GET["rut"]; ?>"> </td>
		</tr>
		<tr>
			<td>Fecha Entre (YYYY-MM-DD): </td>
			<td><input type="text" name="fini" value="<?php echo date("Y-m-d"); ?>"> a <input type="text" name="ffin" value="<?php echo date("Y-m-d"); ?>"> </td>
		</tr>
			<td>Enviar:  </td>
			<td><select name="tipoEnvio">
					<option value="xml" selected="">XML</option>
					<option value="pdf">PDF</option>
				</select>
				<script> chListBoxSelect(document._FFORM.tipoEnvio, "<?php echo $_GET["tipoEnvio"]; ?>"); </script>
			</td>
		  </tr>
		<tr>
			<td colspan="2"><input type="submit" name="b" value="Re-Enviar"></td>			
		</tr>

	  </table>
		
	</form>
 </body>
</html>

<?php

  $es = $_GET["e"];

  if($es == "1"){
	  echo "<script>document._FFORM.b.disabled = true;</script>";
	  echo "<script>document._FFORM.fini.value = '" . $_GET["fini"] . "';</script>";
	  echo "<script>document._FFORM.ffin.value = '" . $_GET["ffin"] . "';</script>";

	//  include("../include/upload_class.php");

	  require_once('nusoap-0.9.5/lib/nusoap.php');

	  $conn = conn();

	  $fIni = trim($_GET["fini"]);
	  $fFin = trim($_GET["ffin"]);
	  $folio = trim($_GET["folio"]);
	  $tipo = trim($_GET["tipo"]);
	  $rut = trim($_GET["rut"]);
	  $tipoEnvio = trim($_GET["tipoEnvio"]);

	  echo "<br><br><h3>Iniciando Proceso de Reenvi&acute;o...</h3><table border=1><tr><th>Estado</th><th>Tipo</th><th>Folio</th><th>Respuesta</th></tr>";

		$sql = "SELECT D.folio_dte, D.tipo_docu, D.rut_emis_dte, D.digi_emis_dte, 
					(SELECT email_contr FROM contrib_elec WHERE rut_contr = D.rut_rec_dte) as email		 			
				FROM dte_enc D, xmldte X
				WHERE 
					D.codi_empr = X.codi_empr AND 
					D.folio_dte = X.folio_dte AND 
					D.tipo_docu = X.tipo_docu AND 
					X.est_xdte > 28 AND X.est_xdte != 77 AND 
					D.codi_empr=" . $_SESSION["_COD_EMP_USU_SESS"] ; 

				if($folio != "") $sql .= " AND D.folio_dte = '$folio' ";
				if($rut != "") $sql .= " AND D.rut_rec_dte = '$rut' "; 
				if($folio != "") $sql .= " AND D.folio_dte = '$folio' ";
				if($fIni != "" and $fFin == "") $sql .= " AND D.fec_emi_dte = to_date('$fIni','YYYY-MM-DD') "; 
				if($fIni == "" and $fFin != "") $sql .= " AND D.fec_emi_dte = to_date('$fFin','YYYY-MM-DD') "; 
				if($fIni != "" and $fFin != "") $sql .= " AND to_date(D.fec_emi_dte,'YYYY-MM-DD') between to_date('$fIni','YYYY-MM-DD') AND to_date('$fFin','YYYY-MM-DD') "; 
		$result = rCursor($conn, $sql);
		while (!$result->EOF) {
			$folio_dte = trim($result->fields["folio_dte"]);
			$tipo_docu = trim($result->fields["tipo_docu"]);
			$rut_emis_dte = trim($result->fields["rut_emis_dte"]) . "-" . trim($result->fields["digi_emis_dte"]);
			$email = trim($result->fields["email"]);
//			$email = "mauricio.escobar.a@gmail.com";
//			
			$aResp = reEnviarDTE($rut_emis_dte, $folio_dte, $tipo_docu, $tipoEnvio, $email, $conn);
			
			if($aResp["estado"] == "1")
				echo "<tr><th>OK</th><th>$tipo_docu</th><th>$folio_dte</th><th>" . $aResp["glosa"] . "</th></tr>";
			else
				echo "<tr bgcolor='#FCA7B2'><th bgcolor='#FCA7B2'>ERROR</th><th bgcolor='#FCA7B2'>$tipo_docu</th><th bgcolor='#FCA7B2'>$folio_dte</th><th>" . $aResp["glosa"] . "</th></tr>";

			$result->MoveNext();
		} 

	// echo "<tr><td bgcolor='#FCA7B2' colspan='3'>Error al crear registro, no se puede procesar rut emisor $rutEmisor y folio " . $folioDTE . "</td></tr>";								
		
		echo "</table><br><h3>Fin Proceso del Archivo</h3>";

		//  Return to the front end after success 
	//	echo "OK";

		echo "<script>document._FFORM.b.disabled = false;</script>";
	}
    



  function reEnviarDTE($rutEmpr, $folio, $tipo, $tipoEnvio, $destinatario, $conn){

	try {

		$parametros=array();
		$parametros["emisor"]=$rutEmpr; 
		$parametros["folioDTE"]=$folio;
		$parametros["tipoDTE"]=$tipo;
		$parametros["tipoEnvio"]=$tipoEnvio;
		$parametros["destinatario"]=$destinatario;


		$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
		$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
		$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
		$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
		$client = new nusoap_client('http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/ReenviaEmailDTE?wsdl', 'wsdl',
								$proxyhost, $proxyport, $proxyusername, $proxypassword);
		$client->setEndpoint("http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/ReenviaEmailDTE.ReenviaEmailDTEHttpSoap11Endpoint/");
		$err = $client->getError();
		if ($err) {
//			echo '<h2>Error al consumir WS</h2><pre>' . $err . '</pre>';
			return array('estado' => 0, "glosa" => "Error al consumir WS al procesar rut emisor $rutEmpr, tipo $tipo y folio " . $folio );
		}
		// Doc/lit parameters get wrapped
//		$param = array('Symbol' => 'IBM');
		$result = $client->call('reenviaEmailDTE', array('parameters' => $parametros), '', '', false, true);

//		echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//		echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
//		echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

		$err = $client->getError();
	/*	if ($err) {
			return array('estado' => 0, "glosa" => "Error getError al procesar rut emisor $rutEmpr, tipo $tipo y folio " . $folio );
		} else { */
//			print_r($result);

			$estado=0;
			$resulGlosa="Formato respuesta incorrecto";
			$respOpen = $result["return"];

			foreach ($result as $valor){
				$resXML=new SimpleXMLElement("<?xml version='1.0' encoding='ISO-8859-1'?>" . $valor);
				$estado=$resXML->Codigo;
				$resulGlosa=$resXML->Glosa;
				break;
			}  

			if($estado == "1")
				return array('estado' => "1", "glosa" => $resulGlosa );
			else
				return array('estado' => "0", "glosa" => "Error al procesar rut emisor $rutEmpr, tipo $tipo y folio " . $folio . ". " . $resulGlosa );
		//}

//		echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//		echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
//		echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
//		echo "<br><br>";

	} catch (Exception $e) {
//		echo 'Excepción capturada: ',  $e->getMessage(), "\n";
//		return array(0,"Error Excepción capturada al procesar folio " $folio . " " . $e->getMessage() );
		return array('estado' => 0, "glosa" => "Error Excepción capturada al procesar rut emisor $rutEmpr, tipo $tipo y folio " . $folio . " " . $e->getMessage());
	}
  }

?>



