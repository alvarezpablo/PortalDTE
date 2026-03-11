<?php
	include("../include/config.php");
	include("../include/db_lib.php");
	include("../include/tables.php");
	include("../include/ver_aut.php");
	include("../include/ver_emp_adm.php");

	$conn = conn();
	$_EST_DTE = isset($_GET["_EST_DTE"]) ? $_GET["_EST_DTE"] : "";
	$nTipoDocu = isset($_GET["nTipoDocu"]) ? $_GET["nTipoDocu"] : "";
	$nAnio = isset($_GET["nAnio"]) ? $_GET["nAnio"] : "";
	$_OK = isset($_GET["OK"]) ? $_GET["OK"] : "";
	$_COLUM_SEARCH = (isset($_COLUM_SEARCH) && trim($_COLUM_SEARCH) != "") ? $_COLUM_SEARCH : "D.folio_dte";
	$_STRING_SEARCH = isset($_STRING_SEARCH) ? $_STRING_SEARCH : "";
	$_STRING_SEARCH0 = isset($_STRING_SEARCH0) ? $_STRING_SEARCH0 : "";
	$_STRING_SEARCH2 = isset($_STRING_SEARCH2) ? $_STRING_SEARCH2 : "";
	$_ORDER_BY_COLUM = isset($_ORDER_BY_COLUM) ? $_ORDER_BY_COLUM : "";
	$_NIVEL_BY_ORDER = isset($_NIVEL_BY_ORDER) ? $_NIVEL_BY_ORDER : "";
	$_IMG_BY_ORDER = isset($_IMG_BY_ORDER) ? $_IMG_BY_ORDER : "";
	$sLinkActual = "dte/list_dtetmp.php?_EST_DTE=" . $_EST_DTE . "&nTipoDocu=" . $nTipoDocu . "&nAnio=" . $nAnio . "&OK=" . $_OK . "&";

	function h($value){
		return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
	}

	function estadoDteTexto($nEstadoDte){
		switch ((int)$nEstadoDte) {
			case 0: return "Cargado";
			case 1: return "Firmado";
			case 3: return "Con ERROR";
			case 5: return "Empaquetado";
			case 13: return "Enviado SII";
			case 29: return "Aceptado SII";
			case 45: return "Con Reparo SII";
			case 77: return "Rechazado SII";
			case 157: return "Enviado a Cliente";
			case 173: return "Con Reparo Enviado a Cliente";
			case 413: return "Aceptado por Cliente";
			case 429: return "Con Reparo Aceptado por Cliente";
			case 1024: return "Rechazo Comercial (por Cliente)";
		}
		return (string)$nEstadoDte;
	}

	function estadoBadgeClass($nEstadoDte){
		switch ((int)$nEstadoDte) {
			case 0:
			case 1:
			case 3:
			case 5:
				return "status-pending";
			case 13:
			case 157:
				return "status-progress";
			case 29:
			case 413:
				return "status-ok";
			case 45:
			case 173:
			case 429:
				return "status-warning";
			case 77:
			case 1024:
				return "status-danger";
		}
		return "status-muted";
	}

	function buildSortUrl($sLinkActual, $sColumn){
		global $_LINK_BASE, $_NIVEL_BY_ORDER, $_COLUM_SEARCH, $_STRING_SEARCH0, $_STRING_SEARCH, $_STRING_SEARCH2, $_EST_DTE;
		return $_LINK_BASE . $sLinkActual
			. "_ORDER_BY_COLUM=" . urlencode($sColumn)
			. "&_NIVEL_BY_ORDER=" . urlencode($_NIVEL_BY_ORDER)
			. "&_COLUM_SEARCH=" . urlencode($_COLUM_SEARCH)
			. "&_STRING_SEARCH0=" . urlencode($_STRING_SEARCH0)
			. "&_STRING_SEARCH=" . urlencode($_STRING_SEARCH)
			. "&_STRING_SEARCH2=" . urlencode($_STRING_SEARCH2)
			. "&_ORDER_CAMBIA=Y&_EST_DTE=" . urlencode($_EST_DTE);
	}

	function buildPdfRemUrl($nCodEmp, $nFolioDte, $nTipDoc, $cedible = false){
		global $_LINK_BASE;
		$url = $_LINK_BASE . "dte/view_pdf_rem.php?c=" . urlencode($nCodEmp) . "&f=" . urlencode($nFolioDte) . "&t=" . urlencode($nTipDoc);
		if($cedible){
			$url .= "&cd=true";
		}
		return $url;
	}

	$filtrosResumen = array();
	if(trim($nTipoDocu) != "") $filtrosResumen[] = "Tipo: " . $nTipoDocu;
	if(trim($nAnio) != "") $filtrosResumen[] = "A&ntilde;o: " . $nAnio;
	if(trim($_EST_DTE) != "") $filtrosResumen[] = "Estado: " . estadoDteTexto($_EST_DTE);
	if(trim($_COLUM_SEARCH) == "D.fec_emi_dte"){
		if(trim($_STRING_SEARCH0) != "" || trim($_STRING_SEARCH2) != ""){
			$filtrosResumen[] = "Emisi&oacute;n: " . (trim($_STRING_SEARCH0) != "" ? $_STRING_SEARCH0 : "...") . " a " . (trim($_STRING_SEARCH2) != "" ? $_STRING_SEARCH2 : "...");
		}
	}
	elseif(trim($_STRING_SEARCH) != ""){
		$filtrosResumen[] = "B&uacute;squeda: " . $_STRING_SEARCH;
	}

	$requiereFiltro = ($_OK != "OK" || (trim($_STRING_SEARCH) == "" && trim($_EST_DTE) == "" && trim($nTipoDocu) == "" && trim($nAnio) == "" && trim($_STRING_SEARCH0) == "" && trim($_STRING_SEARCH2) == ""));

	$sqlTipos = "SELECT tipo_docu, desc_tipo_docu FROM dte_tipo ORDER BY desc_tipo_docu";
	$resultTipos = rCursor($conn, $sqlTipos);
	$nAnioMenor = 2005;
	$nAnioMayor = date("Y");

	$sClassFol = ""; $sClassTD = ""; $sClassFED = "";
	$sImgFol = ""; $sImgTD = ""; $sImgFED = "";
	$result = null;
	$sPaginaResult = "";

	if(!$requiereFiltro){
		$sql = "  SELECT
					D.codi_empr,
					D.tipo_docu,
					D.folio_dte,
					D.fec_emi_dte,
					D.fec_venc_dte,
					D.rut_emis_dte,
					D.digi_emis_dte,
					D.nom_emis_dte,
					D.giro_emis_dte,
					D.dir_orig_dte,
					D.com_orig_dte,
					D.ciud_orig_dte,
					D.rut_rec_dte,
					D.dig_rec_dte,
					D.nom_rec_dte,
					D.giro_rec_dte,
					D.dir_rec_dte,
					D.com_rec_dte,
					D.ciud_rec_dte,
					D.mntneto_dte,
					D.tasa_iva_dte,
					D.iva_dte,
					D.mont_tot_dte,
					D.valo_pag_dte,
					D.fech_carg,
					DT.desc_tipo_docu,
					X.path_pdf,
					X.path_pdf_cedible,
					X.est_xdte,
					'<a href=\"dte/view_pdf.php?sUrlPdf=' || X.path_pdf || '\">PDF</a>' as pdf,
					'<a href=\"dte/view_pdf.php?sUrlPdf=' || X.path_pdf_cedible || '\">PDF Cedible</a>' as pdf_cedible,
					'<a href=\"dte/view_xml.php?nFolioDte=' || D.folio_dte || '&nTipoDocu=' || D.tipo_docu || '\">Ver</a>' as xml,
					(SELECT trackid_xed FROM xmlenviodte WHERE codi_empr = X.codi_empr AND num_xed = X.num_xed) AS track_id
				FROM
					dte_enc D,
					dte_tipo DT,
					xmldte X
				WHERE
					D.codi_empr = X.codi_empr AND
					DT.tipo_docu = D.tipo_docu AND
					D.folio_dte = X.folio_dte AND
					D.tipo_docu = X.tipo_docu AND
					X.codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";

		if($_EST_DTE != "") $sql .= " AND X.est_xdte = $_EST_DTE ";
		if($nTipoDocu != "") $sql .= " AND D.tipo_docu = $nTipoDocu ";
		if($nAnio != "") $sql .= " AND to_char(to_date(D.fec_emi_dte, 'YYYY-MM-DD'), 'YYYY') = '" . $nAnio . "' ";
		if($_COLUM_SEARCH == "D.fec_emi_dte"){
			if($_STRING_SEARCH0 != "" && $_STRING_SEARCH2 != ""){
				$sql .= " AND TO_DATE(D.fec_emi_dte,'YYYY/MM/DD') BETWEEN ('" . str_replace("'","''",$_STRING_SEARCH0) . "') AND ('" . str_replace("'","''",$_STRING_SEARCH2) . "') ";
			}
		}
		if($_COLUM_SEARCH == "D.folio_dte" && trim($_STRING_SEARCH) != ""){
			$sql .= " AND " . $_COLUM_SEARCH . " = '" . str_replace("'","''",trim($_STRING_SEARCH)) . "' ";
		}
		if($_COLUM_SEARCH == "D.fec_venc_dte"){
			if($_STRING_SEARCH0 != "" && $_STRING_SEARCH2 != ""){
				$sql .= " AND TO_DATE(D.fec_emi_dte,'YYYY/MM/DD') BETWEEN ('" . str_replace("'","''",$_STRING_SEARCH0) . "') AND ('" . str_replace("'","''",$_STRING_SEARCH2) . "') ";
			}
		}
		if(($_COLUM_SEARCH == "D.nom_rec_dte" || $_COLUM_SEARCH == "D.giro_rec_dte") && trim($_STRING_SEARCH) != ""){
			$sql .= " AND UPPER(" . $_COLUM_SEARCH . ") LIKE UPPER('" . str_replace("'","''",$_STRING_SEARCH) . "%') ";
		}
		if(trim($_ORDER_BY_COLUM) == "") $sql .= " ORDER BY D.fec_emi_dte DESC, D.folio_dte DESC ";
		else $sql .= " ORDER BY " . $_ORDER_BY_COLUM . " " . $_NIVEL_BY_ORDER;

		if($_ORDER_BY_COLUM == "D.folio_dte"){
			$sClassFol = "table-active";
			$sImgFol = $_IMG_BY_ORDER != "" ? " <img src='" . $_IMG_BY_ORDER . "' alt=''>" : "";
		}
		elseif($_ORDER_BY_COLUM == "D.fec_emi_dte"){
			$sClassFED = "table-active";
			$sImgFED = $_IMG_BY_ORDER != "" ? " <img src='" . $_IMG_BY_ORDER . "' alt=''>" : "";
		}
		elseif($_ORDER_BY_COLUM == "DT.desc_tipo_docu"){
			$sClassTD = "table-active";
			$sImgTD = $_IMG_BY_ORDER != "" ? " <img src='" . $_IMG_BY_ORDER . "' alt=''>" : "";
		}
		else{
			$sClassFol = "table-active";
			$sImgFol = $_IMG_BY_ORDER != "" ? " <img src='" . $_IMG_BY_ORDER . "' alt=''>" : "";
		}

		$result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);
		$sPaginaResult = sPagina($conn, $sql, $sLinkActual);
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="shortcut icon" href="/favicon.ico">
	<title>DTE temporales - Portal DTE</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<base href="<?php echo h($_LINK_BASE); ?>" />
	<script type="text/javascript" src="javascript/common.js"></script>
	<script type="text/javascript" src="javascript/msg.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
	<script type="text/javascript" src="javascript/calendar.js"></script>
	<script type="text/javascript" src="javascript/lang/calendar-es.js"></script>
	<script type="text/javascript" src="javascript/calendar-setup.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}.page-shell{max-width:1700px;margin:0 auto;padding:1rem}.page-hero{background:linear-gradient(135deg,#0f172a 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(15,23,42,.18);margin-bottom:1.25rem}.hero-icon{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:1.4rem}.hero-pills,.actions-stack,.paging{display:flex;flex-wrap:wrap}.hero-pills{gap:.75rem}.hero-pill{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:999px;padding:.45rem .85rem;font-size:.82rem}.card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden;margin-bottom:1rem}.card-header{background:#0f172a;color:#fff;padding:.9rem 1rem}.card-header .small{color:rgba(255,255,255,.75)}.filter-summary{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:14px;padding:.9rem 1rem}.filter-chip{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .75rem;background:#fff;border:1px solid #dbe7f3;border-radius:999px;font-size:.8rem;color:#334155;margin:.2rem}.actions-stack{gap:.65rem}.table-wrap{max-height:68vh;overflow:auto}.table thead th{background:#0f172a;color:#fff;white-space:nowrap;vertical-align:middle;position:sticky;top:0;z-index:2}.table thead th.table-active{background:#0b5ed7;color:#fff}.table tbody td{vertical-align:middle;font-size:.88rem}.table tbody tr:hover{background:#f8fbff}.sort-link{color:#fff;text-decoration:none}.sort-link:hover{color:#dbeafe}.status-badge{display:inline-flex;align-items:center;border-radius:999px;padding:.38rem .75rem;font-size:.78rem;font-weight:600}.status-pending{background:#e2e8f0;color:#334155}.status-progress{background:#dbeafe;color:#1d4ed8}.status-ok{background:#dcfce7;color:#166534}.status-warning{background:#fef3c7;color:#92400e}.status-danger{background:#fee2e2;color:#b91c1c}.status-muted{background:#e5e7eb;color:#4b5563}.doc-meta{color:#64748b;font-size:.78rem}.money{white-space:nowrap}.paging{gap:.45rem;align-items:center}.paging a,.paging span{display:inline-flex;align-items:center;justify-content:center;min-width:2rem;height:2rem;border:1px solid #d0d7e2;border-radius:999px;padding:0 .7rem;background:#fff;color:#0f172a;text-decoration:none;font-size:.85rem}.paging a:hover{background:#eff6ff;border-color:#93c5fd}.empty-state{padding:3rem 1rem;text-align:center;color:#6b7280}.empty-state i{font-size:2.75rem}.action-links a{display:inline-flex;align-items:center;justify-content:center;min-width:2.35rem;height:2.1rem;border-radius:.6rem;border:1px solid #cbd5e1;background:#fff;color:#0f172a;text-decoration:none;margin:.1rem;padding:0 .55rem}.action-links a:hover{background:#eff6ff;border-color:#93c5fd}.form-label{font-weight:600;color:#334155}.search-switch{min-height:42px}#loaderContainer{position:fixed;top:0;right:0;bottom:0;left:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}@media (max-width:991.98px){.page-shell{padding:.75rem}.page-hero{padding:1.1rem}.table-wrap{max-height:none}}
	</style>
	<script type="text/javascript">
		function ceder_documento(nFolioDte,nTipDoc,nMontTot,nCodEmp){ var sUrl = "<?php echo $_LINK_BASE; ?>dte/ceder_documento.php?nFolio=" + nFolioDte + "&nTipoDTE=" + nTipDoc + "&nMontTot=" + nMontTot + "&nCodEmp=" + nCodEmp; wTipoMot = window.open(sUrl,"reenviar","dependent=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,width=1000,height=800"); var centroAncho=(screen.width/2)-(1000); var centroAlto=(screen.height/2)-(400); wTipoMot.moveTo(centroAlto,centroAncho); }
		function Reenviar(nFolioDte,nTipDoc){ var sUrl = "<?php echo $_LINK_BASE; ?>dte/form_reenvio.php?nFolio=" + nFolioDte + "&nTipoDTE=" + nTipDoc; wTipoMot = window.open(sUrl,"reenviar","dependent=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,width=400,height=400"); var centroAncho=(screen.width/2)-(400); var centroAlto=(screen.height/2)-(200); wTipoMot.moveTo(centroAlto,centroAncho); }
		function _body_onload(){ try{SetContext('clients');setActiveButtonByName('clients');}catch(e){} try{loff();}catch(e){} try{muestraDivCampos();}catch(e){} }
		function _body_onunload(){ try{lon();}catch(e){} }
		var opt_no_frames = false, opt_integrated_mode = false;
		try{setActiveButtonByName("clients");}catch(e){}
		function chListBoxSearch(){ var F = document._FSEARCH; if(!F || !F._COLUM_SEARCH) return; for(var i=0; i < F._COLUM_SEARCH.length; i++){ if(F._COLUM_SEARCH.options[i].value == "<?php echo h($_COLUM_SEARCH); ?>") F._COLUM_SEARCH.options[i].selected = true; } }
		function chSelDelEmp(){ var F = document._FDEL; if(!F) return false; for(var i=0; i < F.elements.length; i++){ if(F.elements[i].name == "del[]" && F.elements[i].checked == true) return true; } return false; }
		function chDelEmp(){ if(chSelDelEmp() == true){ if(confirm(_MSG_DEL_CONFIR)) document._FDEL.submit(); } else alert(_MSG_DTE_DEL); }
		function chDchALL(){ var F = document._FDEL; if(!F || !F.clientslistSelectAll) return; var obj = F.clientslistSelectAll; for(var i=0; i < F.elements.length; i++){ if(F.elements[i].name == "del[]") F.elements[i].checked = !!obj.checked; } }
		function chListBoxEstado(){ var F = document._FSEARCH; if(!F || !F._EST_DTE) return; for(var i=0; i < F._EST_DTE.length; i++){ if(F._EST_DTE.options[i].value == "<?php echo h($_EST_DTE); ?>") F._EST_DTE.options[i].selected = true; } }
		function muestraDivCampos(){ var F = document._FSEARCH; if(!F || !F._COLUM_SEARCH) return; var opt = F._COLUM_SEARCH.options[F._COLUM_SEARCH.selectedIndex].value; var div1 = document.getElementById('fechaEmisionFin'); var div2 = document.getElementById('noFechaEmisionFin'); if(!div1 || !div2) return; if(opt == "D.fec_emi_dte"){ div1.style.display = 'block'; div2.style.display = 'none'; } else { div1.style.display = 'none'; div2.style.display = 'block'; } }
	</script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2" /><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
	<div class="page-shell">
		<div class="page-hero">
			<div class="row g-3 align-items-center">
				<div class="col-lg-8"><div class="d-flex align-items-start gap-3"><div class="hero-icon"><i class="bi bi-files"></i></div><div><h1 class="h3 mb-2">DTE temporales</h1><p class="mb-0 opacity-75">Consulte documentos temporales preservando filtro obligatorio, paginaci&oacute;n legacy, PDF REM, XML, cesi&oacute;n, reenv&iacute;o y eliminaci&oacute;n.</p></div></div></div>
				<div class="col-lg-4"><div class="hero-pills justify-content-lg-end"><span class="hero-pill"><i class="bi bi-funnel me-1"></i><?php echo count($filtrosResumen); ?> filtros activos</span><span class="hero-pill"><i class="bi bi-filetype-pdf me-1"></i>PDF REM / Cedible</span><span class="hero-pill"><i class="bi bi-box-arrow-up-right me-1"></i>Cesi&oacute;n y reenv&iacute;o</span></div></div>
			</div>
		</div>

		<div class="card">
			<div class="card-header d-flex flex-wrap justify-content-between gap-2 align-items-center">
				<div><div class="fw-semibold">Filtros y acciones</div><div class="small">Se conservan el formulario GET original, `OK=OK` y los mismos nombres de par&aacute;metros.</div></div>
				<div class="actions-stack"><button type="submit" form="formSearchDteTmp" class="btn btn-light btn-sm"><i class="bi bi-search me-1"></i>Buscar</button><button type="button" class="btn btn-outline-light btn-sm" onclick="chDelEmp();"><i class="bi bi-trash me-1"></i>Eliminar selecci&oacute;n</button></div>
			</div>
			<div class="card-body">
				<form name="_FSEARCH" id="formSearchDteTmp" method="get" action="<?php echo h($_LINK_BASE . $sLinkActual); ?>">
					<input type="hidden" name="OK" value="OK">
					<div class="row g-3 align-items-end">
						<div class="col-xl-2 col-lg-3 col-md-6"><label class="form-label mb-1">Tipo documento</label><select name="nTipoDocu" class="form-select form-select-sm"><option value="">Tipo Documento (Todos)</option><?php while(!$resultTipos->EOF){ $nTipoDocuTmp = trim($resultTipos->fields["tipo_docu"]); $sDescTipoDocu = trim($resultTipos->fields["desc_tipo_docu"]); ?><option value="<?php echo h($nTipoDocuTmp); ?>"<?php echo (trim($nTipoDocuTmp) == trim($nTipoDocu) ? " selected" : ""); ?>><?php echo h($sDescTipoDocu); ?></option><?php $resultTipos->MoveNext(); } ?></select></div>
						<div class="col-xl-2 col-lg-3 col-md-6"><label class="form-label mb-1">A&ntilde;o</label><select name="nAnio" class="form-select form-select-sm"><option value="">A&ntilde;o (Todos)</option><?php for($i = $nAnioMayor; $i >= $nAnioMenor; $i--){ ?><option value="<?php echo h($i); ?>"<?php echo (trim($i) == trim($nAnio) ? " selected" : ""); ?>><?php echo h($i); ?></option><?php } ?></select></div>
						<div class="col-xl-2 col-lg-3 col-md-6"><label class="form-label mb-1">Estado</label><select name="_EST_DTE" class="form-select form-select-sm"><option value="">Estado Todos</option><option value="0">DTE Cargados</option><option value="1">DTE Firmados</option><option value="3">DTE Con ERROR</option><option value="5">DTE Empaquetados</option><option value="13">DTE Enviados SII</option><option value="29">DTE Aceptados SII</option><option value="45">DTE Con Reparos SII</option><option value="77">DTE Rechazados SII</option><option value="157">DTE Enviados a Clientes</option><option value="173">DTE Con Reparos Enviados a Clientes</option><option value="413">DTE Aceptados por Clientes</option><option value="429">DTE Con Reparos Aceptados por Clientes</option><option value="1024">Rechazo Comercial (por cliente)</option></select><script>chListBoxEstado();</script></div>
						<div class="col-xl-2 col-lg-3 col-md-6"><label class="form-label mb-1">Campo</label><select name="_COLUM_SEARCH" class="form-select form-select-sm" onchange="muestraDivCampos();"><option value="D.folio_dte">Folio Dte</option><option value="D.fec_emi_dte">Fecha Emisi&oacute;n</option><option value="D.nom_rec_dte">Razon Social Receptor</option></select><script>chListBoxSearch();</script></div>
						<div class="col-xl-4 col-lg-12"><label class="form-label mb-1">Valor</label><div id="noFechaEmisionFin" class="search-switch" style="display:none;"><div class="input-group input-group-sm"><input type="text" name="_STRING_SEARCH" value="<?php echo h($_STRING_SEARCH); ?>" size="20" maxlength="245" class="form-control"><button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button></div></div><div id="fechaEmisionFin" class="search-switch" style="display:none;"><div class="row g-2"><div class="col-md-5"><div class="input-group input-group-sm"><input type="text" id="_STRING_SEARCH0" name="_STRING_SEARCH0" onfocus="this.blur();" value="<?php echo h($_STRING_SEARCH0); ?>" size="20" maxlength="245" class="form-control"><button type="button" class="btn btn-outline-secondary" id="f_trigger_ini"><i class="bi bi-calendar3"></i></button></div></div><div class="col-md-5"><div class="input-group input-group-sm"><input type="text" id="_STRING_SEARCH2" name="_STRING_SEARCH2" onfocus="this.blur();" value="<?php echo h($_STRING_SEARCH2); ?>" size="20" maxlength="245" class="form-control"><button type="button" class="btn btn-outline-secondary" id="f_trigger_ter"><i class="bi bi-calendar3"></i></button></div></div><div class="col-md-2 d-grid"><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Buscar</button></div></div></div><script type="text/javascript">Calendar.setup({inputField:"_STRING_SEARCH0",ifFormat:"%Y/%m/%d",button:"f_trigger_ini",align:"Tl",singleClick:true}); Calendar.setup({inputField:"_STRING_SEARCH2",ifFormat:"%Y/%m/%d",button:"f_trigger_ter",align:"Tl",singleClick:true}); muestraDivCampos();</script></div>
					</div>
				</form>
				<div class="filter-summary mt-3"><div class="d-flex flex-wrap align-items-center justify-content-between gap-2"><div><strong>Resumen actual</strong><div class="small text-muted">Este listado exige al menos un filtro activo antes de consultar, manteniendo la regla legacy.</div></div><div class="text-muted small"><?php echo count($filtrosResumen); ?> filtro(s)</div></div><div class="mt-2"><?php if(count($filtrosResumen) > 0){ foreach($filtrosResumen as $filtro){ ?><span class="filter-chip"><?php echo $filtro; ?></span><?php } } else { ?><span class="text-muted small">Seleccione tipo, a&ntilde;o, estado o b&uacute;squeda antes de consultar.</span><?php } ?></div></div>
			</div>
		</div>

		<div class="card">
			<div class="card-header d-flex flex-wrap justify-content-between gap-2 align-items-center"><div><div class="fw-semibold">Listado temporal de DTE</div><div class="small">Se conserva `sPagina(...)`, el borrado a `dte/pro_dte.php`, el popup de cesi&oacute;n y el popup de reenv&iacute;o.</div></div><div class="small">PDF REM, PDF cedible, XML y eliminaci&oacute;n selectiva siguen intactos.</div></div>
			<?php if($requiereFiltro){ ?>
				<div class="card-body"><div class="alert alert-warning mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Debe seleccionar un filtro.</div></div>
			<?php } else { ?>
				<div class="card-body p-0">
					<form name="_FDEL" method="post" action="dte/pro_dte.php" class="mb-0">
						<input type="hidden" name="sAccion" value="E">
						<div class="table-wrap">
							<table class="table table-sm table-hover align-middle mb-0">
								<thead>
									<tr>
										<th class="<?php echo $sClassFol; ?>"><a class="sort-link" href="<?php echo h(buildSortUrl($sLinkActual, 'D.folio_dte')); ?>">Folio</a><?php echo $sImgFol; ?></th>
										<th>TrackID</th>
										<th>Estado</th>
										<th class="<?php echo $sClassTD; ?>"><a class="sort-link" href="<?php echo h(buildSortUrl($sLinkActual, 'DT.desc_tipo_docu')); ?>">Tipo de Doc.</a><?php echo $sImgTD; ?></th>
										<th class="<?php echo $sClassFED; ?>"><a class="sort-link" href="<?php echo h(buildSortUrl($sLinkActual, 'D.fec_emi_dte')); ?>">Fecha Emis.</a><?php echo $sImgFED; ?></th>
										<th>Rut Receptor</th>
										<th>Nombre Receptor</th>
										<th>Neto</th>
										<th>Iva</th>
										<th>Total</th>
										<th>PDF</th>
										<th>PDF Cedible</th>
										<th>Ceder DTE</th>
										<th>XML</th>
										<th>Re-Enviar</th>
										<th class="text-center"><input type="checkbox" class="form-check-input" name="clientslistSelectAll" value="true" onclick="chDchALL();"></th>
									</tr>
								</thead>
								<tbody>
								<?php if($result->EOF){ ?>
									<tr><td colspan="16"><div class="empty-state"><i class="bi bi-inbox"></i><div class="fw-semibold mt-3">No hay DTE temporales para los filtros seleccionados</div><div class="small">Ajuste el estado, tipo, a&ntilde;o o criterio de b&uacute;squeda y vuelva a consultar.</div></div></td></tr>
								<?php } else { while(!$result->EOF){
									$nCodEmp = trim($result->fields["codi_empr"]);
									$nTipDoc = trim($result->fields["tipo_docu"]);
									$nTrackID = trim($result->fields["track_id"]);
									$nFolioDte = trim($result->fields["folio_dte"]);
									$dFecEmiDte = trim($result->fields["fec_emi_dte"]);
									$nRutRec = trim($result->fields["rut_rec_dte"]);
									$sDigRec = trim($result->fields["dig_rec_dte"]);
									$sNomRec = trim($result->fields["nom_rec_dte"]);
									$nMntNeto = trim($result->fields["mntneto_dte"]);
									$nIvaDte = trim($result->fields["iva_dte"]);
									$nMontTot = trim($result->fields["mont_tot_dte"]);
									$sDescTipoDoc = trim($result->fields["desc_tipo_docu"]);
									$nEstadoDte = trim($result->fields["est_xdte"]);
									$linkXml = trim($result->fields["xml"]);
									$sEstadoDte = estadoDteTexto($nEstadoDte);
									$mostrarCedible = ($nTipDoc == "33" || $nTipDoc == "34" || $nTipDoc == "46" || $nTipDoc == "52" || $nTipDoc == "110");
									$permiteEliminar = ($nEstadoDte == "0" || $nEstadoDte == "1" || $nEstadoDte == "3" || $nEstadoDte == "77");
									$permiteCeder = ((int)$nEstadoDte >= 29);
								?>
									<tr>
										<td><div class="fw-semibold"><?php echo h($nFolioDte); ?></div><div class="doc-meta">Tipo <?php echo h($nTipDoc); ?></div></td>
										<td><?php echo h($nTrackID); ?></td>
										<td><span class="status-badge <?php echo h(estadoBadgeClass($nEstadoDte)); ?>"><?php echo h($sEstadoDte); ?></span></td>
										<td><?php echo h($sDescTipoDoc); ?></td>
										<td><?php echo h($dFecEmiDte); ?></td>
										<td><?php echo h($nRutRec . "-" . $sDigRec); ?></td>
										<td><?php echo h($sNomRec); ?></td>
										<td class="money">$<?php echo number_format($nMntNeto, 0, ',', '.'); ?></td>
										<td class="money">$<?php echo number_format($nIvaDte, 0, ',', '.'); ?></td>
										<td class="money">$<?php echo number_format($nMontTot, 0, ',', '.'); ?></td>
										<td class="action-links"><a href="<?php echo h(buildPdfRemUrl($nCodEmp, $nFolioDte, $nTipDoc)); ?>" target="_blank" title="PDF">PDF</a></td>
										<td class="action-links"><?php if($mostrarCedible){ ?><a href="<?php echo h(buildPdfRemUrl($nCodEmp, $nFolioDte, $nTipDoc, true)); ?>" target="_blank" title="PDF Cedible">Ced.</a><?php } else { ?>No Aplica<?php } ?></td>
										<td class="action-links"><?php if($permiteCeder){ ?><a href="javascript:ceder_documento('<?php echo h($nFolioDte); ?>','<?php echo h($nTipDoc); ?>','<?php echo h($nMontTot); ?>','<?php echo h($nCodEmp); ?>');" title="Ceder DTE">Ceder</a><?php } else { ?>&nbsp;<?php } ?></td>
										<td class="action-links"><?php echo $linkXml; ?></td>
										<td class="action-links"><?php if($nEstadoDte > 28){ ?><a href="javascript:Reenviar('<?php echo h($nFolioDte); ?>','<?php echo h($nTipDoc); ?>');" title="Re-Enviar">Re</a><?php } else { ?>&nbsp;<?php } ?></td>
										<?php if($permiteEliminar){ ?><td class="text-center"><input type="checkbox" class="form-check-input" name="del[]" value="<?php echo h($nFolioDte . '|' . $nTipDoc); ?>"></td><?php } else { ?><td class="text-center">&nbsp;</td><?php } ?>
									</tr>
								<?php $result->MoveNext(); } } ?>
								</tbody>
							</table>
						</div>
					</form>
				</div>
				<div class="card-footer bg-white border-0 pt-0 pb-3 px-3"><div class="paging"><?php echo $sPaginaResult; ?></div></div>
			<?php } ?>
		</div>
	</div>
</body>
</html>