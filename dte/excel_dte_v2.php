<?php 
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");	
//	header('Content-Type: text/html; charset=iso-8859-1');

 ini_set('memory_limit', '1G'); // or you could use 1G    

	include("../include/config.php");
	include("../include/db_lib.php");  
	include("../include/ver_aut.php");
    include("../include/ver_emp_adm.php"); 
	require_once 'PHPExcel-1.8/PHPExcel.php';

	$tipo = trim($_GET["tipo"]);
	$folio = trim($_GET["folio"]);
	$fecha1 = trim($_GET["fecha1"]);
	$fecha2 = trim($_GET["fecha2"]);
	$fechac1 = trim($_GET["fechac1"]);
	$fechac2 = trim($_GET["fechac2"]);
	$estado = trim($_GET["estado"]);
	$rut = trim($_GET["rut"]);
	$pagina = trim($_GET["pagina"]);

	if($rut != ""){
		$aRut = explode("-",$rut);
		$rut = $aRut[0];
	}

	$AAR = trim($_GET["AAR"]);		// Acuse de recibo ok
	$RAR = trim($_GET["RAR"]);		// acuse de recubi rechazado
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
                        case 285:
                        $sEstadoDte = "Con Reparo Recibido por Cliente";
                        break;  
                        case 301:
                        $sEstadoDte = "Con Reparo Recibido por Cliente";
                        break;  
			case 413:
			$sEstadoDte = "Aceptado por Cliente";
			break;
			case 429:
			$sEstadoDte = "Con Reparo Aceptado por Cliente";
			break; 
                        case 1181:
                        $sEstadoDte = "Rechazado Automticamente";
                        break;
                        case 1437:
                        $sEstadoDte = "Rechazado Comercialmente";
                        break;
                        case 1197:
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
			->setTitle("Reporte Excel DTE Emisión") // Titulo
			->setSubject("Reporte Excel DTE Emisión") //Asunto
			->setDescription("Reporte Excel DTE Emisión") //Descripción
			->setKeywords("Reporte Excel DTE Emisión") //Etiquetas
			->setCategory("Reporte Excel DTE Emisión"); //Categoria

		$tituloReporte = "Reporte Excel DTE Emisi&oacute;n " . date("Y-m-d");
		$titulosColumnas = array('TrackDTE', 'Tipo', 'Folio', 'Estado', 'F.Emisión','F.Carga', 'Exento', 'Neto', 'IVA', 'Total', 'Rut', 'Receptor', 'Dirección', 'Comuna');

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
			->setCellValue('N3',  $titulosColumnas[13]);

		$conn = conn();
		$campos = " SELECT 	
					D.tipo_docu, D.folio_dte, X.est_xdte,  D.fec_emi_dte, X.fec_carg, D.rut_rec_dte, D.dig_rec_dte, D.nom_rec_dte, D.dir_rec_dte, D.com_rec_dte, 
					D.mntneto_dte, D.mnt_exen_dte, D.iva_dte, D.mont_tot_dte, 
					COALESCE(X.trackid,(SELECT trackid_xed FROM xmlenviodte WHERE codi_empr = X.codi_empr AND num_xed = X.num_xed)) trackid_xed, 
					X.est_envio, X.est_recibo_mercaderias, X.est_rec_doc, 
					X.est_res_rev, X.path_pdf, X.path_pdf_cedible ";
		$sql = " FROM 
			xmldte X, 
			dte_enc D
		WHERE
			D.tipo_docu = X.tipo_docu AND
			D.folio_dte = X.folio_dte AND
			D.codi_empr = X.codi_empr AND
			D.codi_empr = '". trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";

		if($tipo != "")	$sql .= " AND D.tipo_docu = '" . str_replace("'","''",$tipo) . "'";
		if($folio != "")	$sql .= " AND CAST(D.folio_dte as varchar)= '" . str_replace("'","''",$folio) . "'";
		if($estado != "")	$sql .= " AND X.est_xdte in(" . str_replace("'","",$estado) . ")";
		if($rut != "")	$sql .= " AND D.rut_rec_dte = '" . str_replace("'","''",$rut) . "'";
		if($fecha1 != "" || $fecha2 != ""){
			$_STRING_SEARCH0 = $fecha1;
			$_STRING_SEARCH1 = $fecha2;
			if($_STRING_SEARCH0 != "" && $_STRING_SEARCH1 == "") 
				$_STRING_SEARCH1 = $_STRING_SEARCH0;
			elseif($_STRING_SEARCH0 == "" && $_STRING_SEARCH1 != "")
				$_STRING_SEARCH0 = $_STRING_SEARCH1;			
			$sql .= " AND TO_DATE(D.fec_emi_dte,'YYYY-MM-DD') BETWEEN ('" . str_replace("'","''",$_STRING_SEARCH0) . "') AND ('" . str_replace("'","''",$_STRING_SEARCH1) . "') "; 
		}
		if($fechac1 != "" || $fechac2 != ""){
			$_STRING_SEARCHC0 = $fechac1;
			$_STRING_SEARCHC1 = $fechac2;
			if($_STRING_SEARCHC0 != "" && $_STRING_SEARCHC1 == "") 
				$_STRING_SEARCHC1 = $_STRING_SEARCHC0;
			elseif($_STRING_SEARCHC0 == "" && $_STRING_SEARCHC1 != "")
				$_STRING_SEARCHC0 = $_STRING_SEARCHC1;			
			$sql .= " AND X.fec_carg BETWEEN ('" . str_replace("'","''",$_STRING_SEARCHC0) . "') AND ('" . str_replace("'","''",$_STRING_SEARCHC1) . "') "; 
		}


		$inCod = "";
		if($AAR == "1") $inCod .= "'R',";
		if($RAR == "1") $inCod .= "'X',";
		if($SAR == "1") $inCod .= "'',";
		$inCod2 = "";
		if($AAC == "1") $inCod2 .= "'R','A',";
		if($RAC == "1") $inCod2 .= "'X',";
		if($SAC == "1") $inCod2 .= "'',";
		$inCod3 = "";
		if($CRM == "1") $inCod3 .= "'R',";
		if($SRM == "1") $inCod3 .= "'',";

		if($AAR == "1" && $RAR == "1" && $SAR == "1")	// todas las opciones marcadas evita el filtro
			$NoAplica = "";
		else{
			if($inCod == "") 
				$sql .= " AND coalesce(X.est_rec_doc, '') NOT IN ('R','X','') ";
			else{
				$inCod = substr($inCod, 0, strlen($inCod) - 1);
				$sql .= " AND coalesce(X.est_rec_doc, '') IN (".$inCod.") ";	
			}
		}

		if($AAC == "1" && $RAC == "1" && $SAC == "1")	// todas las opciones marcadas evita el filtro
			$NoAplica = "";
		else{
			if($inCod2 == "") 
				$sql .= " AND coalesce(X.est_res_rev, '') NOT IN ('R','A','X','') ";
			else{
				$inCod2 = substr($inCod2, 0, strlen($inCod2) - 1);
				$sql .= " AND coalesce(X.est_res_rev, '') IN (".$inCod2.") ";	
			}
		}
		if($CRM == "1" && $SRM == "1")	// todas las opciones marcadas evita el filtro
			$NoAplica = "";
		else{
			if($inCod3 == "") 
				$sql .= " AND coalesce(X.est_recibo_mercaderias, '') NOT IN ('R','') ";
			else{
				$inCod3 = substr($inCod3, 0, strlen($inCod3) - 1);
				$sql .= " AND coalesce(X.est_recibo_mercaderias, '') IN (".$inCod3.") ";	
			}
		}

		$campos = $campos . " " . $sql;	// . " ORDER BY fec_carg DESC ";
		$campos .= " ORDER BY fec_carg DESC LIMIT 1000000"; 
		$result = rCursor($conn, $campos);
		$i=4;
		while (!$result->EOF) {
			$tipo_docu = trim($result->fields["tipo_docu"]);  
			$folio_dte = trim($result->fields["folio_dte"]);  
			$est_xdte = trim($result->fields["est_xdte"]);  
			$fec_emi_dte = trim($result->fields["fec_emi_dte"]);  
			$fec_carg = trim($result->fields["fec_carg"]);  
			$rut_rec_dte = trim($result->fields["rut_rec_dte"]) . "-" . trim($result->fields["dig_rec_dte"]) ;  
			$nom_rec_dte = eliminar_acentos(trim($result->fields["nom_rec_dte"]));  
			$dir_rec_dte = eliminar_acentos(trim($result->fields["dir_rec_dte"]));  
			$com_rec_dte = eliminar_acentos(trim($result->fields["com_rec_dte"]));  
			$mntneto_dte = trim($result->fields["mntneto_dte"]);  
			$mnt_exen_dte = trim($result->fields["mnt_exen_dte"]);  
			$iva_dte = trim($result->fields["iva_dte"]);  
			$mont_tot_dte = trim($result->fields["mont_tot_dte"]);  
			$trackid_xed = trim($result->fields["trackid_xed"]);  
			$est_envio = trim($result->fields["est_envio"]);  
			$est_recibo_mercaderias = trim($result->fields["est_recibo_mercaderias"]);  
			$est_rec_doc = trim($result->fields["est_rec_doc"]);  
			$est_res_rev = trim($result->fields["est_res_rev"]);  
			$path_pdf = trim($result->fields["path_pdf"]);  
			$path_pdf_cedible = trim($result->fields["path_pdf_cedible"]);  

			if($mnt_exen_dte == "")	$mnt_exen_dte = "0";
			if($mntneto_dte == "")	$mntneto_dte = "0";
			if($iva_dte == "")	$iva_dte = "0";
			if($mont_tot_dte == "")	$mont_tot_dte = "0";

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i, $trackid_xed)
				->setCellValue('B'.$i, eliminar_acentos(poneTipo($tipo_docu)))
				->setCellValue('C'.$i, $folio_dte)
				->setCellValue('D'.$i, eliminar_acentos(poneEstado($est_xdte)))
				->setCellValue('E'.$i, $fec_emi_dte)
				->setCellValue('F'.$i, $fec_carg)
				->setCellValue('G'.$i, $mnt_exen_dte)
				->setCellValue('H'.$i, $mntneto_dte)
				->setCellValue('I'.$i, $iva_dte)
				->setCellValue('J'.$i, $mont_tot_dte)
				->setCellValue('K'.$i, $rut_rec_dte)
				->setCellValue('L'.$i, $nom_rec_dte)
				->setCellValue('M'.$i, $dir_rec_dte)
				->setCellValue('N'.$i, $com_rec_dte);
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
		$objPHPExcel->getActiveSheet()->setTitle('DTE Emitidos');		 
		// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
		$objPHPExcel->setActiveSheetIndex(0);		 
		// Inmovilizar paneles
		//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
		$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);
		// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="DTEEmitidos' . date("Y-m-d H:i:s") . '.xlsx"');
		header('Cache-Control: max-age=0');
//		header('Content-Type: text/html; charset=UTF-8');
		 
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	else{
			echo "<script>alert('Debe realizar la busqueda para descargar excel');window.close();</script>";
	}

function eliminar_acentos($cadena){
		return $cadena;
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );

		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}
?>
