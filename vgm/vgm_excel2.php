<?php 
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");



	include("../include/config.php");
	include("../include/db_lib.php");
	include("../include/ver_aut.php");
//    include("../include/ver_emp_adm.php"); 

	$accion = trim($_GET["accion"]);
	$fini = trim($_GET["fecIni"]);
	$ffin = trim($_GET["fecFin"]);
	$emp = trim($_GET["emp"]);

	if($accion == "OK"){
		$conn = conn();
		$sql = "SELECT 
					tipo_docu, folio_dte, nom_rec_dte, rut_rec_dte, dig_rec_dte, mont_tot_dte, 
					to_char(to_date(fec_emi_dte,'yyyy-mm-dd'),'dd/mm/yyyy') as fec_emi_dte
				FROM 
					dte_enc 
				WHERE 
					to_date(fec_emi_dte,'yyyy-mm-dd') BETWEEN to_date('" . str_replace("'","''",$fini) . "','yyyy-mm-dd') AND to_date('" . str_replace("'","''",$ffin) . "','yyyy-mm-dd') AND 
					codi_empr in (SELECT codi_empr FROM empresa WHERE rut_empr='" . str_replace("'","''",$emp) .  "') ORDER BY tipo_docu, folio_dte ";
//					codi_empr='". trim($_SESSION["_COD_EMP_USU_SESS"]) . "' AND 
//echo $sql;
//exit;
		$result = rCursor($conn, $sql);

		while (!$result->EOF) {
			$tipo_docu = trim($result->fields["tipo_docu"]);  
			$folio_dte = trim($result->fields["folio_dte"]);  
			$nom_rec_dte = str_replace(",","",trim($result->fields["nom_rec_dte"]));  
			$rut_rec_dte = trim($result->fields["rut_rec_dte"]);  
			$dig_rec_dte = trim($result->fields["dig_rec_dte"]);  
			$mont_tot_dte = trim($result->fields["mont_tot_dte"]);  
			$fec_emi_dte = trim($result->fields["fec_emi_dte"]);  
			$sNomMov = "FE-$folio_dte $nom_rec_dte";

			if($tipo_docu == "61"){
				$sNomMov = "NC-$folio_dte $nom_rec_dte";
				$strCSV .= "1-1-04-02,,$mont_tot_dte,$sNomMov,,,,,,,,,,,,259,,,$rut_rec_dte-$dig_rec_dte,NC,$folio_dte,$fec_emi_dte,$fec_emi_dte,NC,$folio_dte,,$mont_tot_dte,,,,,,,,,$mont_tot_dte\n";
				$strCSV .= "5-1-01-01,$mont_tot_dte,,$sNomMov,,,,,,,,,,,,259,,,,,,,,,,,,,,,,,,,,\n";
			}
			else{
				$strCSV .= "1-1-04-02,$mont_tot_dte,,$sNomMov,,,,,,,,,,,,259,,,$rut_rec_dte-$dig_rec_dte,FE,$folio_dte,$fec_emi_dte,$fec_emi_dte,FE,$folio_dte,,$mont_tot_dte,,,,,,,,,$mont_tot_dte\n";
				$strCSV .= "5-1-01-01,,$mont_tot_dte,$sNomMov,,,,,,,,,,,,259,,,,,,,,,,,,,,,,,,,,\n";
			}	

			$result->MoveNext();
		}

//		echo $strCSV;
//exit;
			$sName = "softland_$fini_$ffin.csv";
			$archivo = "../caf_file/" . $sName;
			$fp = fopen($archivo, "w");
			$write = fputs($fp, $strCSV);
			fclose($fp);

			header("Content-Disposition: attachment; filename=".$sName);
			header("Content-Type: text/csv;"); 
			header ("Content-Length: ".filesize($archivo));
			readfile($archivo);
			unlink($archivo);

	}

?>