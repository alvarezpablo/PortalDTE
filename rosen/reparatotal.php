<?php 

	include("../include/config.php");
	include("../include/db_lib.php");
	$conn = conn();

	$sql = "SELECT tipo_docu, folio_dte, signed_xdte, codi_empr FROM xmldte WHERE 
					codi_empr=68 AND tipo_docu in (39,41) AND ts >= to_date('2022-07-30','YYYY-MM-DD')
					";

//					and COALESCE(xml_temp,'') = '' 
// 					and folio_dte=5110357

	$result = rCursor($conn, $sql);

	while (!$result->EOF) {
		$codi = trim($result->fields["codi_empr"]);
		$tipo = trim($result->fields["tipo_docu"]);
		$folio = trim($result->fields["folio_dte"]);
		$xml = str_replace("</DTE></DTE>","</DTE>",trim($result->fields["signed_xdte"]));	
		$xmlOrig = $xml;							// Xml sin modificar, para respaldo 

		$aTemp = explode("<Signature",$xml);		/// Elimino firma
		$xml = $aTemp[0] . "</DTE>";
		$xml = str_replace("</DTE></DTE>","</DTE>",$xml);	
		$bol = new SimpleXMLElement($xml);

		$MntNeto = 0;
		$MntExe = 0;
		$IVA = 0;
		// saco MntNeto
		$segs=$bol->xpath('//MntNeto');
		if (count($segs)>=1) {
			$MntNeto = intval(trim($segs[0]));
		}
		// saco MntExe
		$segs=$bol->xpath('//MntExe');
		if (count($segs)>=1) {
			$MntExe = intval(trim($segs[0]));
		}
		// saco IVA
		$segs=$bol->xpath('//IVA');
		if (count($segs)>=1) {
			$IVA = intval(trim($segs[0]));
		}

		if($MntNeto == "") $MntNeto = 0;
		if($MntExe == "") $MntExe = 0;
		if($IVA == "") $IVA = 0;
		
		$sql = "UPDATE dte_enc SET 
						mntneto_dte = '" . str_replace("'","''",$MntNeto) . "', 
						iva_dte = '" . str_replace("'","''",$IVA) . "',
						mnt_exen_dte = '" . str_replace("'","''",$MntExe) . "'	
				WHERE	folio_dte = '" . str_replace("'","''",$folio) . "' AND  
						tipo_docu = '" . str_replace("'","''",$tipo) . "' AND  
						codi_empr = '" . str_replace("'","''",$codi) . "'";
		nrExecuta($conn, $sql);

//		echo $sql . "<br>";


		$result->MoveNext();
	}  

//	echo $xml;

	echo "FIN !!!";

	

?>