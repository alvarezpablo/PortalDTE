<?php 



  include("../include/config.php");  
  include("../include/ver_aut.php");      
//  include("../include/ver_aut_adm.php");        
  include("../include/db_lib.php"); 
//  include ("../include/upload_class.php"); 
//(  include ("../include/genera_dte.php"); 

  if($_SESSION){

	try{

		$conn = conn();
		   
		$nCodEmp = $_SESSION["_COD_EMP_USU_SESS"];

		$sql = "select to_char(now(),'yyyymmddHH24MI') fech_ahora";
		$result2 = rCursor($conn, $sql);

		if(!$result2->EOF) {
			$fech_ahora = floatval(trim($result2->fields["fech_ahora"]));
		}


		/***** ELIMINO LOS YA RECIBIDOS EN OPPENB **************/
		$sql = "select t.rut_prov, t.tipo_docu, t.folio_dte,
						t.acuse_dte, to_char(t.fech_acuse_dte,'DD/MM/YYYY HH24:MI:SS') fech_acuse_dte, 
						t.merca_dte, to_char(t.fech_merca_dte,'DD/MM/YYYY HH24:MI:SS') fech_merca_dte,
						to_char(t.fech_limite_sii,'DD/MM/YYYY HH24:MI:SS') fech_limite_sii
						from documentoscompras_temp D, dte_sii_no_openb t where 
						t.rut_prov = D.rut_rec_dte || '-' || D.dig_rec_dte AND t.tipo_docu = D.tipo_docu AND t.folio_dte = D.fact_ref AND t.codi_empr = D.codi_empr AND
							D.codi_empr = " . $_SESSION["_COD_EMP_USU_SESS"] . " AND t.codi_empr = "  . $_SESSION["_COD_EMP_USU_SESS"];
		$result = rCursor($conn, $sql);
		while (!$result->EOF) {
			$Trut_prov = trim($result->fields["rut_prov"]);	
			$Ttipo_docu = trim($result->fields["tipo_docu"]);	
			$Tfolio_dte = trim($result->fields["folio_dte"]);	
			$Tacuse_dte = trim($result->fields["acuse_dte"]);	
			$Tfech_acuse_dte = trim($result->fields["fech_acuse_dte"]);	
			$Tmerca_dte = trim($result->fields["merca_dte"]);	
			$Tfech_merca_dte = trim($result->fields["fech_merca_dte"]);	
			$Tfech_limite_sii = trim($result->fields["fech_limite_sii"]);	

			$sqlAcuse = "";

			if($Tacuse_dte != "") $sqlAcuse = " acuse_dte = '" . $Tacuse_dte . "' , ";
			if($Tfech_acuse_dte != "") $sqlAcuse .= " fech_acuse_dte = TO_TIMESTAMP('" . $Tfech_acuse_dte . "','DD/MM/YYYY HH24:MI:SS') , ";
			if($Tmerca_dte != "") $sqlAcuse .= " merca_dte = '" . $Tmerca_dte . "' , ";
			if($Tfech_merca_dte != "") $sqlAcuse .= " fech_merca_dte = TO_TIMESTAMP('" . $Tfech_merca_dte . "','DD/MM/YYYY HH24:MI:SS') , ";
			if($Tfech_limite_sii != "") $sqlAcuse .= " fech_limite_sii = TO_TIMESTAMP('" . $Tfech_limite_sii . "','DD/MM/YYYY HH24:MI:SS') , ";
			
			if($sqlAcuse != ""){
				$aRutProv = explode("-",$Trut_prov);
				$sql = "UPDATE documentoscompras_temp SET " . $sqlAcuse . "	saldo = saldo	
					WHERE 
						rut_rec_dte='" . $aRutProv[0] . "' AND tipo_docu = '$Ttipo_docu' AND fact_ref = '$Tfolio_dte' AND 
						codi_empr = " . $_SESSION["_COD_EMP_USU_SESS"];
				nrExecuta($conn, $sql);
			}
			$sql = "DELETE FROM documentoscompras_temp 
				WHERE 
					rut_rec_dte='" . $aRutProv[0] . "' AND tipo_docu = '$Ttipo_docu' AND fact_ref = '$Tfolio_dte' AND 
					codi_empr = " . $_SESSION["_COD_EMP_USU_SESS"];
			nrExecuta($conn, $sql);

			$result->MoveNext(); 
		}
		/***** FIN ELIMINO LOS YA RECIBIDOS EN OPPENB **************/

		$sql = "SELECT codi_empr,tipo_docu,rut_prov,razon_prov, folio_dte, TO_CHAR(fecha_dte,'DD/MM/YYYY HH24:MI:SS') fecha_dte,
		TO_CHAR(fecha_recep,'DD/MM/YYYY HH24:MI:SS') fecha_recep2, TO_CHAR(fecha_acuse,'DD/MM/YYYY HH24:MI:SS') fecha_acuse,
									exento, neto, iva, total, estado_sii, fech_update_sii, 
									to_char(fech_limite_sii,'yyyy-mm-dd HH24:MI') fech_limite_sii,
									to_char(fech_limite_sii,'yyyymmddHH24MI') fech_limite_sii2,
									merca_dte,to_char(fech_merca_dte,'dd-mm-yyyy HH24:MI') fech_merca_dte, acuse_dte, 
									to_char(fech_acuse_dte,'dd-mm-yyyy HH24:MI') fech_acuse_dte
				FROM dte_sii_no_openb WHERE codi_empr = " . $_SESSION["_COD_EMP_USU_SESS"] . "
				ORDER BY fecha_recep DESC ";

		$result = rCursor($conn, $sql);

		$html = "<table class=\"container\">
		<thead>
			<tr>
				<th>Operaciones</th>
				<th>Tipo</th>
				<th>Folio</th>
				<th>F.Emisión</th>
				<th>F.Recepción SII</th>
				<th>F:Limite Acepta/Rechaza SII</th>
				<th>Rut.Emisor</th>
				<th>Emisor</th>
				<th>Exento</th>
				<th>Neto</th>
				<th>Iva</th>
				<th>Total</th>
			</tr>
		</thead>

		<tbody>
		";


		$entrar = false;
		while (!$result->EOF) {
			$entrar = true;
			$codi_empr = trim($result->fields["codi_empr"]);			
			$tipo_docu = trim($result->fields["tipo_docu"]);			
			$rut_prov = trim($result->fields["rut_prov"]);			
			$razon_prov = trim($result->fields["razon_prov"]);			
			$folio_dte = trim($result->fields["folio_dte"]);			
			$fecha_dte = trim($result->fields["fecha_dte"]);			
			$fecha_recep = trim($result->fields["fecha_recep2"]);			
			$fecha_acuse = trim($result->fields["fecha_acuse"]);			
			$exento = trim($result->fields["exento"]);			
			$neto = trim($result->fields["neto"]);			
			$iva = trim($result->fields["iva"]);			
			$total = trim($result->fields["total"]);			
			$estado_sii = trim($result->fields["estado_sii"]);			
			$fech_update_sii = trim($result->fields["fech_update_sii"]);			
			$fech_limite_sii = trim($result->fields["fech_limite_sii"]);			
			$fech_limite_sii2 = floatval(trim($result->fields["fech_limite_sii2"]));			
			$aRutProv = explode("-",$rut_prov);

			$merca_dte = trim($result->fields["merca_dte"]);
			$fech_merca_dte = trim($result->fields["fech_merca_dte"]);
			$acuse_dte = trim($result->fields["acuse_dte"]);
			$fech_acuse_dte = trim($result->fields["fech_acuse_dte"]);

			$linkMerca = "<a href=\"javascript:alert('Recibo de Mercadería No Recepcionado');\" onMouseover=\"nm_mostra_hint(this, event, 'Recibo de Mercadería No Generado')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/rm_no.png' alt='Recibo No Recepcionado'></a>";

			if($merca_dte == "ERM")	
				$linkMerca = "<a href=\"javascript:alert('Otorgado Recibo de Mercaderías el " . $fech_merca_dte . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Otorgado Recibo de Mercaderías el " . $fech_merca_dte . "')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/rm_ok.png' alt='Otorgado Recibo de Mercaderías el " . $fech_merca_dte . "'></a>";					
			if($merca_dte == "RFP")	
				$linkMerca = "<a href=\"javascript:alert('Reclamado por Falta Parcial de Mercaderías el " . $fech_merca_dte . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Reclamado por Falta Parcial de Mercaderías el " . $fech_merca_dte . "')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_nook.png' alt='Reclamado por Falta Parcial de Mercaderías el " . $fech_merca_dte . "'></a>";
			if($merca_dte == "RFT")	
				$linkMerca = "<a href=\"javascript:alert('Reclamodo por Falta Total de Mercaderías el " . $fech_merca_dte . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Reclamodo por Falta Total de Mercaderías el " . $fech_merca_dte . "')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_nook.png' alt='Reclamodo por Falta Total de Mercaderías el " . $fech_merca_dte . "'></a>";


			$linkComer = "<a href=\"javascript:alert('Respuesta Comercial no Generada');\" onMouseover=\"nm_mostra_hint(this, event, 'Respuesta Comercial No Generada')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_no.png' alt='Respuesta Comercial no Generada'></a>";
			if($acuse_dte != ""){
				if(trim($acuse_dte) == "ACD")
					$linkComer = "<a href=\"javascript:alert('Aceptado el Contenido del Documento el " . $fech_acuse_dte . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Aceptado el Contenido del Documento el " . $fech_acuse_dte . "')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_ok.png' alt='Aceptado el Contenido del Documento el " . $fech_acuse_dte . "'></a>";						
				if(trim($acuse_dte) == "RCD")
					$linkComer = "<a href=\"javascript:alert('Reclamado el Contenido del Documento el " . $fech_acuse_dte . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Reclamado el Contenido del Documento el " . $fech_acuse_dte . "')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_nook.png' alt='Reclamado el Contenido del Documento el " . $fech_acuse_dte . "'></a>";						
			}

			$html .= "<tr> ";



					$html .= "<td>
								<table class=\"datagrid\">
											<tr>
												<td>" . $linkComer . "</td>
												<td>" . $linkMerca . "</td>";
			if($fech_ahora <= $fech_limite_sii2){ 
				if(trim($merca_dte) == "" || trim($acuse_dte) == "")	{

												$html .= "<td><a href=\"#_FENVACT\" onclick=\"responderSII2('" . $folio_dte . "', '" . $tipo_docu . "', '" . $aRutProv[0] . "', '" . $aRutProv[1] . "', '" . $merca_dte . "','" . $fech_merca_dte . "','" . $acuse_dte . "','" . $fech_acuse_dte . "');\" rel=\"modal:open\">Responder DTE</a></td> ";				
				}
				else
					$html .= "<td>&nbsp;</td>";
			}
				else
					$html .= "<td>&nbsp;</td>";

											
							$html .= "	</tr>
								</table>
							  </td>";
							
			
			
			$html .= "<td>" . poneTipo($tipo_docu) . "</td>
						<td align='right'>" . number_format($folio_dte,0,',','.') . "</td>
						<td>" . $fecha_dte . "</td>
						<td>" . $fecha_recep . "</td>
						<td>" . $fech_limite_sii . "</td>
						<td>" . $rut_prov . "</td>
						<td>" . $razon_prov . "</td>
						<td align='right'>" . number_format($exento,0,',','.') . "</td>
						<td align='right'>" . number_format($neto,0,',','.') . "</td>
						<td align='right'>" . number_format($iva,0,',','.') . "</td>
						<td align='right'>" . number_format($total,0,',','.') . "</td>
					  </tr>\n";					
					
				
			$result->MoveNext(); 
		}

		if($entrar == false)
			$html .= "<tr><td colspan='12'><h2>No hay documentos pendientes. Recuerde actualizar el Registro de Compras de el SII</h2></td></tr>";

		$html .= "</tbody>
		</table>";

/*		$arrRespuesta = array(
			"Error" => "0",
			"msj" => "Operación Realizada",
			"html" => $html
		);
*/
			$arrRespuesta = array(
				"Error" => "0",
				"msj" => "Operación Realizada",
				"html" => $html
			);
			echo json_encode($arrRespuesta);
			exit;  

// 			"query" => $query
		//print_r($arrRespuesta);
		echo json_encode($arrRespuesta);
		exit;  		

	} //try
	catch (Exception $e) {
		echo 'Excepción: ',  $e->getMessage(), "\n";
		$arrRespuesta = array(
			"Error" => "1",
			"msj" => "Excepción: ".  $e->getMessage(),
			"estadoAcuse" => "",
			"glosaAcuse" => "",
			"estadoMerca" => "",
			"glosaMerca" => ""
		);
		echo json_encode($arrRespuesta);
		exit;  		

	}
  } // if session
  else{
			$arrRespuesta = array(
				"Error" => "2",
				"msj" => "Session Expirada"
			);
			echo json_encode($arrRespuesta);
			exit;  
  }


	function poneTipo($tipo_docu){

		switch ($tipo_docu) {
			case 33:
				$sEstadoDte = "FA.Elect";
				break;
			case 34:
				$sEstadoDte = "FE.Elect";
				break;
			case 39:
				$sEstadoDte = "BA.Elect";
				break;
			case 41:
				$sEstadoDte = "BE.Elect";
				break;
			case 43:
				$sEstadoDte = "LQ.Elect";
				break;
			case 46:
				$sEstadoDte = "FC.Elect";
				break;
			case 52:
				$sEstadoDte = "GD.Elect";
				break;
			case 56:
				$sEstadoDte = "ND.Elect";
				break;
			case 61:
				$sEstadoDte = "NC.Elect";
				break;
			case 110:
				$sEstadoDte = "FEE.Elect";
				break;
			case 111:
				$sEstadoDte = "NDE.Elect";
				break;
			case 112:
				$sEstadoDte = "NCE.Elect";
				break;
		}

	}
/*  
  switch ($sEstado) {
    case "ACEPTADO": 
        dAceptar($conn);
        break;
    
    case "RECHAZADO": 
        dRechazar($conn);
        break;

  }
  header("location:fin_newrecielec.php");
  exit;     */
?>
