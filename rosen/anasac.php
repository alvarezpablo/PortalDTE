<?php 

	include("../include/config.php");
	include("../include/db_lib.php");
	$conn = conn();

	$sql = "SELECT codi_empr, tipo_docu, folio_dte, signed_xdte FROM xmldte WHERE 
					codi_empr in (70) 	and signed_xdte like '%<RUTMandante>%'				
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

		// Elimino giro receptor
		$segs=$bol->xpath('//RUTMandante');
		if (count($segs)>=1) {
			$rut = trim($segs[0]);

			$idPlantilla = "4";
			if($rut == "99568400-4") $idPlantilla = "3";

			$sql = "UPDATE xmldte SET 
									id_plantilla=$idPlantilla 
					WHERE	folio_dte = '" . str_replace("'","''",$folio) . "' AND  
							tipo_docu = '" . str_replace("'","''",$tipo) . "' AND  
							codi_empr = $codi_empr";
							echo $sql . "<br><br>";
			nrExecuta($conn, $sql);
		}

//		echo $sql . "<br>";


		$result->MoveNext();
	}  

//	echo $xml;

	echo "FIN !!!";
?>