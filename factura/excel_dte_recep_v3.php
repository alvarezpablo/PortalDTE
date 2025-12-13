<?php 
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");	
	ini_set('memory_limit', '100024M'); // or you could use 1G    

	include("../include/config.php");
	include("../include/db_lib.php");  
	include("../include/ver_aut.php");
    include("../include/ver_emp_adm.php"); 
	require_once '../dte/PHPExcel-1.8/PHPExcel.php';

	$tipo = trim($_GET["tipo"]);
	$folio = trim($_GET["folio"]);
	$fecha1 = trim($_GET["fecha1"]);
	$fecha2 = trim($_GET["fecha2"]);
	$fechac1 = trim($_GET["fechac1"]);
	$fechac2 = trim($_GET["fechac2"]);
	$rut = trim($_GET["rut"]);
	$pagina = trim($_GET["pagina"]);

	if($rut != ""){
		$aRut = explode("-",$rut);
		$rut = $aRut[0];
	}

	$AAR = trim($_GET["AAR"]);		// Acuse de recibo ok
	$SAR = trim($_GET["SAR"]);		// sin acuse de recibo
	$AAC = trim($_GET["AAC"]);		// acptado comercialmente
	$RAC = trim($_GET["RAC"]);		// rechazado comercialmente
	$SAC = trim($_GET["SAC"]);		// sin respuesta comercial
	$CRM = trim($_GET["CRM"]);		// con recibo de mercaderia
	$SRM = trim($_GET["SRM"]);		// sin recibo de mercaderia

	function poneEstado($nEstadoDte){
		$sEstadoDte = $nEstadoDte;
		switch ($nEstadoDte) {
			case 0:
			$sEstadoDte = "Cargado";
			break;
			case 1:
			$sEstadoDte = "Firmado";
			break;
			case 3:
			$sEstadoDte = "Con ERROR";
			break;
			case 5:
			$sEstadoDte = "Empaquetado";
			break; 
			case 13:
			$sEstadoDte = "Enviado SII";
			break;
			case 29:
			$sEstadoDte = "Aceptado SII";
			break;
			case 45:
			$sEstadoDte = "Con Reparo SII";
			break;
			case 77:
			$sEstadoDte = "Rechazado SII";
			break;
			case 157:
			$sEstadoDte = "Enviado a Cliente";
			break;
			case 173:
			$sEstadoDte = "Con Reparo Enviado a Cliente";
			break;
			case 413:
			$sEstadoDte = "Aceptado por Cliente";
			break;
			case 429:
			$sEstadoDte = "Con Reparo Aceptado por Cliente";
			break; 
			case 1181:
			$sEstadoDte = "Rechazado Automáticamente";
			break; 
			case 1437:
			$sEstadoDte = "Rechazado Comercialmente";
			break; 
		}
		return $sEstadoDte;
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
		if ($sEstadoDte == "")
			$sEstadoDte = $tipo_docu;
		else
			$sEstadoDte = "$sEstadoDte ($tipo_docu)";

		return $sEstadoDte;
	}

	if($_GET){

		// Se crea el objeto PHPExcel
		$objPHPExcel = new PHPExcel();
		// Se asignan las propiedades del libro
		$objPHPExcel->getProperties()->setCreator("OpenB") // Nombre del autor
			->setLastModifiedBy("OpenB") //Ultimo usuario que lo modificó
			->setTitle("Reporte Excel DTE Recibidos") // Titulo
			->setSubject("Reporte Excel DTE Recibidos") //Asunto
			->setDescription("Reporte Excel DTE Recibidos") //Descripción
			->setKeywords("Reporte Excel DTE Recibidos") //Etiquetas
			->setCategory("Reporte Excel DTE Recibidos"); //Categoria

		$tituloReporte = "Reporte Excel DTE Recibidos " . date("Y-m-d");
//		$titulosColumnas = array('Tipo', 'Folio', 'F.Emisión','F.Recepcion', 'Exento', 'Neto', 'IVA', 'Total', 'Rut', 'Receptor', 'Dirección', 'Comuna','Acuse','Comercial','Mercadería');
		$titulosColumnas = array('Tipo', 'Folio', 'F.Emisión','F.Recepcion', 'Exento', 'Neto', 'IVA', 'Total', 'Rut', 'Receptor', 'Dirección', 'Comuna','Acuse','Comercial','Mercadería','Msg.ERP');

		// Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D1');
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1',$tituloReporte) // Titulo del reporte
			->setCellValue('A3',  $titulosColumnas[0])  //Titulo de las columnas
			->setCellValue('B3',  $titulosColumnas[1])
			->setCellValue('C3',  $titulosColumnas[2])
			->setCellValue('D3',  $titulosColumnas[3])
			->setCellValue('E3',  $titulosColumnas[4])
			->setCellValue('F3',  $titulosColumnas[5])
			->setCellValue('G3',  $titulosColumnas[6])
			->setCellValue('H3',  $titulosColumnas[7])
			->setCellValue('I3',  $titulosColumnas[8])
			->setCellValue('J3',  $titulosColumnas[9])
			->setCellValue('K3',  $titulosColumnas[10])
			->setCellValue('L3',  $titulosColumnas[11])
			->setCellValue('M3',  $titulosColumnas[12])			
			->setCellValue('N3',  $titulosColumnas[13])
			->setCellValue('O3',  $titulosColumnas[14])
			->setCellValue('P3',  $titulosColumnas[15]);
		$conn = conn();
		$campos = "SELECT  
						correl_doc, 
						fact_ref, 
						fec_emi_doc, 
						to_char(fecha_recep,'yyyy-mm-dd') fec_rece_doc, 
						rut_rec_dte, 
						dig_rec_dte, 
						nom_rec_dte, 
						dir_rec_dte, 
						com_rec_dte, 
						mntneto_dte,  
						mnt_exen_dte,  
						tasa_iva_dte,  
						iva_dte,  
						mont_tot_dte, 
						tipo_docu, 
						est_doc, 
						xml_respuesta, 
						xml_recibo_mercaderia, 
						xml_est_res_rev, (select gls_rec from dte_recep where rut_rec = documentoscompras_temp.rut_rec_dte and ndte_rec= documentoscompras_temp.fact_ref and tipo_docu= documentoscompras_temp.tipo_docu and documentoscompras_temp.codi_empr=codi_empr) as gls ";

		$sql = "	FROM 
						documentoscompras_temp 
					WHERE 
						codi_empr = '" . str_replace("'","''",$_SESSION["_COD_EMP_USU_SESS"]) . "' ";

		if($tipo != "")	$sql .= " AND tipo_docu = '" . str_replace("'","''",$tipo) . "'";
		if($folio != "")	$sql .= " AND CAST(fact_ref as varchar)= '" . str_replace("'","''",$folio) . "'";
		if($rut != "")	$sql .= " AND rut_rec_dte = '" . str_replace("'","''",$rut) . "'";
		if($fecha1 != "" || $fecha2 != ""){
			$_STRING_SEARCH0 = $fecha1;
			$_STRING_SEARCH1 = $fecha2;
			if($_STRING_SEARCH0 != "" && $_STRING_SEARCH1 == "") 
				$_STRING_SEARCH1 = $_STRING_SEARCH0;
			elseif($_STRING_SEARCH0 == "" && $_STRING_SEARCH1 != "")
				$_STRING_SEARCH0 = $_STRING_SEARCH1;			
//			$sql .= " AND TO_DATE(fec_emi_doc,'YYYY-MM-DD') BETWEEN ('" . str_replace("'","''",$_STRING_SEARCH0) . "') AND ('" . str_replace("'","''",$_STRING_SEARCH1) . "') "; 
			$sql .= " AND TO_DATE(fec_emi_doc,'YYYY-MM-DD') BETWEEN TO_DATE(('" . str_replace("'","''",$_STRING_SEARCH0) . "'),'YYYY-MM-DD') AND TO_DATE(('" . str_replace("'","''",$_STRING_SEARCH1) . "'),'YYYY-MM-DD') "; 

		}
		if($fechac1 != "" || $fechac2 != ""){
			$_STRING_SEARCHC0 = $fechac1;
			$_STRING_SEARCHC1 = $fechac2;
			if($_STRING_SEARCHC0 != "" && $_STRING_SEARCHC1 == "") 
				$_STRING_SEARCHC1 = $_STRING_SEARCHC0;
			elseif($_STRING_SEARCHC0 == "" && $_STRING_SEARCHC1 != "")
				$_STRING_SEARCHC0 = $_STRING_SEARCHC1;			
//			$sql .= " AND fecha_recep BETWEEN ('" . str_replace("'","''",$_STRING_SEARCHC0) . "') AND ('" . str_replace("'","''",$_STRING_SEARCHC1) . "') "; 
			$sql .= " AND fecha_recep BETWEEN TO_DATE(('" . str_replace("'","''",$_STRING_SEARCHC0) . "'),'YYYY-MM-DD') AND TO_DATE(('" . str_replace("'","''",$_STRING_SEARCHC1) . "'),'YYYY-MM-DD') "; 
		}


		if($AAR == "1" && $SAR == "1")	// todas las opciones marcadas evita el filtro
			$NoAplica = "";
		else{
			if($AAR == "1") 
				$sql .= " AND coalesce(xml_respuesta, '') != '' ";
			if($SAR == "1") 
				$sql .= " AND coalesce(xml_respuesta, '') = '' ";
		}

		if($CRM == "1" && $SRM == "1")	// todas las opciones marcadas evita el filtro
			$NoAplica = "";
		else{
			if($CRM == "1") 
				$sql .= " AND coalesce(xml_recibo_mercaderia, '') != '' ";
			if($SRM == "1") 
				$sql .= " AND coalesce(xml_recibo_mercaderia, '') = '' ";	
		}

		if($AAC == "1" && $RAC == "1" && $SAC == "1")
			$NoAplica = "";
		if($AAC == "1" && $RAC == "1" && $SAC == "")	
			$sql .= " AND trim(coalesce(est_doc, '')) IN ('ACEPTADO','RECHAZADO') ";
		if($AAC == "1" && $RAC == "" && $SAC == "1")	
			$sql .= " AND trim(coalesce(est_doc, '')) IN ('ACEPTADO','') ";
		if($AAC == "" && $RAC == "1" && $SAC == "1")	
			$sql .= " AND trim(coalesce(est_doc, '')) IN ('RECHAZADO','') ";	
		if($AAC == "" && $RAC == "" && $SAC == "1")	
			$sql .= " AND trim(coalesce(est_doc, '')) IN ('') ";			
		if($AAC == "" && $RAC == "1" && $SAC == "")	
			$sql .= " AND trim(coalesce(est_doc, '')) IN ('RECHAZADO') ";	
		if($AAC == "1" && $RAC == "" && $SAC == "")	
			$sql .= " AND trim(coalesce(est_doc, '')) IN ('ACEPTADO') ";	

		$campos = $campos . $sql . " ORDER BY fecha_recep DESC LIMIT 20000"; 
		$result = rCursor($conn, $campos);
		$i=4;
		while (!$result->EOF) {
			$nCodDoc = trim($result->fields["correl_doc"]);			
			$folio_dte  = trim($result->fields["fact_ref"]);
			$fec_emi_doc = trim($result->fields["fec_emi_doc"]);
			$fec_rece_doc = trim($result->fields["fec_rece_doc"]);
			$rut_rec_dte = trim($result->fields["rut_rec_dte"]) . "-" . trim($result->fields["dig_rec_dte"]);
//			$dig_rec_dte = trim($result->fields["dig_rec_dte"]);
			$nom_rec_dte = trim($result->fields["nom_rec_dte"]);
			$dir_rec_dte = trim($result->fields["dir_rec_dte"]);
			$com_rec_dte = trim($result->fields["com_rec_dte"]);
			$mntneto_dte = trim($result->fields["mntneto_dte"]);
			$mnt_exen_dte = trim($result->fields["mnt_exen_dte"]);
			$tasa_iva_dte = trim($result->fields["tasa_iva_dte"]);
			$iva_dte = trim($result->fields["iva_dte"]);
			$mont_tot_dte = trim($result->fields["mont_tot_dte"]);
			$tipo_docu = trim($result->fields["tipo_docu"]);
			$sEstado = trim($result->fields["est_doc"]); 
			if($sEstado == "") $sEstado = "No Generado";
			$sAcuseRecibo = trim($result->fields["xml_respuesta"]); 
			$sReciboMerca = trim($result->fields["xml_recibo_mercaderia"]); 
			$sAcuseComer = trim($result->fields["xml_est_res_rev"]);
			$gls = trim($result->fields["gls"]);

			if($mnt_exen_dte == "")	$mnt_exen_dte = "0";
			if($mntneto_dte == "")	$mntneto_dte = "0";
			if($iva_dte == "")	$iva_dte = "0";
			if($mont_tot_dte == "")	$mont_tot_dte = "0";

			if($sAcuseRecibo == "") $sAcuseRecibo = "No Generado"; else $sAcuseRecibo = "Generado";
			if($sReciboMerca == "") $sReciboMerca = "No Generado"; else $sReciboMerca = "Generado";

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i, poneTipo($tipo_docu))
				->setCellValue('B'.$i, $folio_dte)
				->setCellValue('C'.$i, $fec_emi_doc)
				->setCellValue('D'.$i, $fec_rece_doc)
				->setCellValue('E'.$i, $mnt_exen_dte)
				->setCellValue('F'.$i, $mntneto_dte)
				->setCellValue('G'.$i, $iva_dte)
				->setCellValue('H'.$i, $mont_tot_dte)
				->setCellValue('I'.$i, $rut_rec_dte)
				->setCellValue('J'.$i, $nom_rec_dte)
				->setCellValue('K'.$i, $dir_rec_dte)
				->setCellValue('L'.$i, $com_rec_dte)
				->setCellValue('M'.$i, $sAcuseRecibo)
				->setCellValue('N'.$i, $sEstado)
				->setCellValue('O'.$i, $sReciboMerca)
				->setCellValue('P'.$i, $gls);
			$i++;
			$result->MoveNext(); 
		}
		if($i > 4){
			for($i = 'A'; $i <= 'N'; $i++){
				$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
			}
		}
		else{
				echo "<script>alert('La busqueda no ha dado resultados');window.close();</script>";
		}
		// Se asigna el nombre a la hoja
		$objPHPExcel->getActiveSheet()->setTitle('DTE Recibidos');		 
		// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
		$objPHPExcel->setActiveSheetIndex(0);		 
		// Inmovilizar paneles
		//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
		$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);
		// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="DTERecibidos' . date("Y-m-d H:i:s") . '.xlsx"');
		header('Cache-Control: max-age=0');
		 
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	else{
			echo "<script>alert('Debe realizar la busqueda para descargar excel');window.close();</script>";
	}
?>
