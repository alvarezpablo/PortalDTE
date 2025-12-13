<?php 

	include("../include/config.php");
	include("../include/db_lib.php");
	$conn = conn();

	$sql = "SELECT tipo_docu, folio_dte, signed_xdte FROM xmldte WHERE 
					codi_empr=68 AND est_xdte=77 and tipo_docu in (39,41) AND ts >= to_date('2025-08-01','YYYY-MM-DD') and repara_bole is null
					";

//					and COALESCE(xml_temp,'') = '' 
// 					and folio_dte=5246484

	$result = rCursor($conn, $sql);

	while (!$result->EOF) {
		$tipo = trim($result->fields["tipo_docu"]);
		$folio = trim($result->fields["folio_dte"]);
		$xml = str_replace("</DTE></DTE>","</DTE>",trim($result->fields["signed_xdte"]));	
		$xmlOrig = $xml;							// Xml sin modificar, para respaldo 

		$aTemp = explode("<Signature",$xml);		/// Elimino firma
		$xml = $aTemp[0] . "</DTE>";
		$xml = str_replace("</DTE></DTE>","</DTE>",$xml);	
		$bol = new SimpleXMLElement($xml);

		// Elimino descuento global si es cero
		foreach ($bol->xpath('//DscRcgGlobal') as $valor) {
			$dscVal = intval($valor->ValorDR);

			if($dscVal == 0){
				$segs=$bol->xpath('//DscRcgGlobal');
				if (count($segs)>=1) {
					unset($segs[0][0]);
					break;
				}
			}
		}

		// Elimino codigo vendedor si es blanco
		foreach ($bol->xpath('//Referencia') as $valor) {
			$codVal = trim($valor->CodVndor);

			if($codVal == ""){
				$segs=$bol->xpath('//CodVndor');
				if (count($segs)>=1) {
					unset($segs[0][0]);
					break;
				}
			}
		}

		// Elimino Ted receptor
		$segs=$bol->xpath('//TED');
		if (count($segs)>=1) {
			unset($segs[0][0]);
		}

		// Elimino giro receptor
		$segs=$bol->xpath('//GiroRecep');
		if (count($segs)>=1) {
			unset($segs[0][0]);
		}
		// Elimino correo receptor
		$segs=$bol->xpath('//CorreoRecep');
		if (count($segs)>=1) {
			unset($segs[0][0]);
		}

		// Elimino CodCaja receptor
		$segs=$bol->xpath('//CodCaja');
		if (count($segs)>=1) {
			$caja = trim($segs[0]);
			unset($segs[0][0]);
		}


		
		// obtengo valor de dirpostal
		foreach ($bol->xpath('//Receptor') as $valor) {
			$valDirPostal = trim($valor->DirPostal);
			break;
		}
		
		
		$xml = $bol->asXML();

		$aTemp = explode("<CiudadRecep>",$xml);		/// divido string xml para incorporar dirpostal

		if($valDirPostal == "")
			$xml = $aTemp[0] . "<DirPostal>" . $caja . "</DirPostal>\n<CiudadRecep>" . $aTemp[1];	// Nuevo XML modificado


		$sql = "UPDATE xmldte SET 
								repara_bole=1,
								stqueue=0,
								est_xdte=0, 
								xml_xdte='" . str_replace("'","''",$xml) . "',
								xml_temp='" . str_replace("'","''",$xml) . "', 
								signed_xdte='" . str_replace("'","''",$xml) . "' 
				WHERE	folio_dte = '" . str_replace("'","''",$folio) . "' AND  
						tipo_docu = '" . str_replace("'","''",$tipo) . "' AND  
						codi_empr = 68";
		nrExecuta($conn, $sql);

///		echo $sql . "<br>";


		$result->MoveNext();
	}  

//	echo $xml;

	echo "FIN !!!";
?>
