<?php 
        include("../include/config.php");
//        include("../include/ver_aut.php");
        include("../include/db_lib.php");  
	$conn = conn();	

//	$sql = "SELECT codi_empr, tipo_docu, ini_num_caf, xml_caf FROM caf WHERE vence_caf is null ";
        $sql = "SELECT codi_empr, tipo_docu, ini_num_caf, xml_caf FROM caf WHERE estado = 1 and tipo_docu in (33,43,46,56,61) and codi_empr != 68"; 
	$result = rCursor($conn, $sql);
	while (!$result->EOF) {  
		$codi_empr = trim($result->fields["codi_empr"]);
		$tipo_docu = trim($result->fields["tipo_docu"]);
		$ini_num_caf = trim($result->fields["ini_num_caf"]);
		$xml_caf = trim($result->fields["xml_caf"]);
		$fech_solicita = extraeValor("<FA>", "</FA>", $xml_caf);
		$fecIni = intval(str_replace("-","",$fech_solicita));	

		if($fecIni < 20180701){
			$fech_vence = date("Y-m-d",strtotime($fech_solicita."+ 18 month"));
			$fecTer = intval(str_replace("-","",$fech_vence)); 
			if($fecTer > 20181231)
				$fech_vence = "2018-12-31";
		}
		else
			$fech_vence = date("Y-m-d",strtotime($fech_solicita."+ 6 month -3 days"));               

		$fecTer = intval(str_replace("-","",$fech_vence));	// fecha vencimiento
		$estado = "";
		$hoy = intval(date("Ymd"));
		if($fecTer < $hoy)
			$estado = "2";

		$sql = "UPDATE CAF set 
			vence_caf=to_date('" . $fech_vence . "','YYYY-MM-DD') ,
			solicita_caf=to_date('" . $fech_solicita . "','YYYY-MM-DD') ";
		if($estado == "2")
			$sql .= ",estado=2 ";
		
		$sql .= " WHERE codi_empr = '" . $codi_empr . "' AND 
			tipo_docu = '" . $tipo_docu . "' AND 
			ini_num_caf = '" . $ini_num_caf . "' ";
		nrExecuta($conn, $sql);

		$result->MoveNext();
	}  


	function extraeValor($iniTag, $finTag, $valor){
		try {
			$aValor = explode($iniTag,$valor);
			$aValor = explode($finTag,$aValor[1]);
			return $aValor[0];
		}
		catch (Exception $e) {
			return "";
		}
	}
?>
