<?php 

	include("../include/config.php");
	include("../include/db_lib.php");
	$conn = conn();

	$sql = "SELECT codi_empr, tipo_docu, folio_dte, signed_xdte FROM xmldte WHERE 
					codi_empr in (70,73,93,167,193,334,7,231,259,69,335,70,71,229,337) AND est_xdte=77 and tipo_docu in (39,41) AND ts >= to_date('2022-08-01','YYYY-MM-DD') 
 and repara_bole is null 
					
					";

//					and COALESCE(xml_temp,'') = '' 
// 					and folio_dte=522200

	$result = rCursor($conn, $sql);

	while (!$result->EOF) {
		$codi_empr = trim($result->fields["codi_empr"]);
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

		// Elimino codigo vendedor si es blanco
		foreach ($bol->xpath('//Detalle') as $valor) {
			$segs=$bol->xpath('//QtyRef');
			if (count($segs)>=1) {
				unset($segs[0][0]);
//					break;
			}
			$segs=$bol->xpath('//PrcRef');
			if (count($segs)>=1) {
				unset($segs[0][0]);
//					break;
			}

		}

		// Elimino Ted receptor
		$segs=$bol->xpath('//TED');
		if (count($segs)>=1) {
			unset($segs[0][0]);
		}


		// Elimino Ted receptor
		$segs=$bol->xpath('//SubDscto');
		if (count($segs)>=1) {
			unset($segs[0][0]);
		}


		// Elimino Extranjero 
		$segs=$bol->xpath('//Extranjero');
		if (count($segs)>=1) {
			unset($segs[0][0]);
		}

		// Elimino Transporte 
		$segs=$bol->xpath('//Transporte');
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

		// Elimino tasa de iva
		$segs=$bol->xpath('//TasaIVA');
		if (count($segs)>=1) {
			unset($segs[0][0]);
		}

		
		// obtengo valor de dirpostal
//		foreach ($bol->xpath('//Receptor') as $valor) {
//			$valDirPostal = trim($valor->DirPostal);
//			break;
//		}
		

		// Elimino codigo vendedor si es blanco
		foreach ($bol->xpath('//Totales') as $valor) {
			$nValIva = trim($valor->IVA);
			break;
		}
		
		$xml = $bol->asXML();	


		if($codi_empr == "229"){
			if($nValIva == "" || $nValIva == "0" || $nValIva == "0.0"){
				$aTemp = explode("<IVA>",$xml);		/// divido string xml para incorporar dirpostal
				if($valDirPostal == "")
					$xml = $aTemp[0] . "<IVA>1" . $aTemp[1];	// Nuevo XML modificado
			}
		}

		$sql = "UPDATE xmldte SET 
								repara_bole=1,
								stqueue=0,
								est_xdte=0, 
								xml_xdte='" . str_replace("'","''",$xml) . "',
								xml_temp='" . str_replace("'","''",$xml) . "', 
								signed_xdte='" . str_replace("'","''",$xml) . "' 
				WHERE	folio_dte = '" . str_replace("'","''",$folio) . "' AND  
						tipo_docu = '" . str_replace("'","''",$tipo) . "' AND  
						codi_empr = $codi_empr";
		nrExecuta($conn, $sql);

//		echo $sql . "<br>";


		$result->MoveNext();
	}  


        $sql = "SELECT codi_empr, tipo_docu, folio_dte,  repara_bole FROM xmldte WHERE
                                        codi_empr in (70,73,93,167,193,334,7,231,259,69,335,70,71,229,337) AND est_xdte=77 and tipo_docu in (39,41) AND ts >= to_date('2022-08-01','YYYY-MM-DD')
 and repara_bole is not null and COALESCE(repara_bole,0) < 100

                                        ";

//                                      and COALESCE(xml_temp,'') = ''
//                                      and folio_dte=522200

        $result = rCursor($conn, $sql);

        while (!$result->EOF) {
                $codi_empr = trim($result->fields["codi_empr"]);
                $tipo = trim($result->fields["tipo_docu"]);
                $folio = trim($result->fields["folio_dte"]);      
		$repara = intval(trim($result->fields["repara_bole"])) + 1;

                $sql = "UPDATE xmldte SET
                                                                repara_bole=$repara,
                                                                stqueue=0,
                                                                est_xdte=1
                                WHERE   folio_dte = '" . str_replace("'","''",$folio) . "' AND
                                                tipo_docu = '" . str_replace("'","''",$tipo) . "' AND
                                                codi_empr = $codi_empr";
                nrExecuta($conn, $sql);   

                $result->MoveNext();
        }      

//	echo $xml;

	echo "FIN !!!";
?>
