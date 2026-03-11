<?php
	include("../include/config.php");
	include("../include/db_lib.php");
	include("../include/tables.php");
	$conn = conn();
	$sBuscar = isset($_GET["_Buscar"]) ? $_GET["_Buscar"] : "";
	session_start();
	if($sBuscar == "OK"){
		$_SESSION["_EST_DTES"] = isset($_GET["_EST_DTE"]) ? trim($_GET["_EST_DTE"]) : "";
		$_SESSION["nTipoDocuS"] = isset($_GET["nTipoDocu"]) ? trim($_GET["nTipoDocu"]) : "";
		$_SESSION["nAnioS"] = isset($_GET["nAnio"]) ? trim($_GET["nAnio"]) : "";
		$_SESSION["_COLUM_SEARCHS"] = isset($_GET["_COLUM_SEARCH"]) ? trim($_GET["_COLUM_SEARCH"]) : "";
		$_SESSION["_STRING_SEARCHS"] = isset($_GET["_STRING_SEARCH"]) ? trim($_GET["_STRING_SEARCH"]) : "";
		$_SESSION["_STRING_SEARCH0S"] = isset($_GET["_STRING_SEARCH0"]) ? trim($_GET["_STRING_SEARCH0"]) : "";
		$_SESSION["_STRING_SEARCH2S"] = isset($_GET["_STRING_SEARCH2"]) ? trim($_GET["_STRING_SEARCH2"]) : "";
	}
	$_EST_DTE = isset($_SESSION["_EST_DTES"]) ? $_SESSION["_EST_DTES"] : "";
	$nTipoDocu = isset($_SESSION["nTipoDocuS"]) ? $_SESSION["nTipoDocuS"] : "";
	$nAnio = isset($_SESSION["nAnioS"]) ? $_SESSION["nAnioS"] : "";
	$_COLUM_SEARCH = isset($_SESSION["_COLUM_SEARCHS"]) ? $_SESSION["_COLUM_SEARCHS"] : "";
	$_STRING_SEARCH = isset($_SESSION["_STRING_SEARCHS"]) ? $_SESSION["_STRING_SEARCHS"] : "";
	$_STRING_SEARCH0 = isset($_SESSION["_STRING_SEARCH0S"]) ? $_SESSION["_STRING_SEARCH0S"] : "";
	$_STRING_SEARCH2 = isset($_SESSION["_STRING_SEARCH2S"]) ? $_SESSION["_STRING_SEARCH2S"] : "";
	if(trim($_COLUM_SEARCH) == "") $_COLUM_SEARCH = "D.folio_dte";
	$sLinkActual = "dte/list_dtepro.php?_EST_DTE=" . $_EST_DTE . "&nTipoDocu=" . $nTipoDocu . "&nAnio=" . $nAnio . "&";
	include("include/phpgrid.php");
	function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }
	$filtrosResumen = array();
	if(trim($nTipoDocu) != "") $filtrosResumen[] = "Tipo: " . $nTipoDocu;
	if(trim($nAnio) != "") $filtrosResumen[] = "A&ntilde;o: " . $nAnio;
	if(trim($_EST_DTE) != "") $filtrosResumen[] = "Estado: " . $_EST_DTE;
	if(trim($_COLUM_SEARCH) == "D.fec_emi_dte"){
		if(trim($_STRING_SEARCH0) != "" || trim($_STRING_SEARCH2) != "") $filtrosResumen[] = "Rango: " . (trim($_STRING_SEARCH0) != "" ? $_STRING_SEARCH0 : "...") . " a " . (trim($_STRING_SEARCH2) != "" ? $_STRING_SEARCH2 : "...");
	}
	elseif(trim($_STRING_SEARCH) != "") $filtrosResumen[] = "B&uacute;squeda: " . $_STRING_SEARCH;
	$sqlTipos = "SELECT tipo_docu, desc_tipo_docu FROM dte_tipo ORDER BY desc_tipo_docu";
	$resultTipos = rCursor($conn, $sqlTipos);
	$sqlAnio = "SELECT MIN(TO_CHAR(TO_DATE(fec_emi_dte, 'YYYY-MM-DD'), 'YYYY')) AS anio_menor, MAX(TO_CHAR(TO_DATE(fec_emi_dte, 'YYYY-MM-DD'), 'YYYY')) AS anio_mayor FROM dte_enc";
	$resultAnio = rCursor($conn, $sqlAnio);
	if(!$resultAnio->EOF){ $nAnioMenor = trim($resultAnio->fields["anio_menor"]); $nAnioMayor = trim($resultAnio->fields["anio_mayor"]); }
	else{ $nAnioMenor = date("Y"); $nAnioMayor = date("Y"); }
	$hostName = $_SERVER_DB; $userName = $_USER_DB; $password = $_PASS_DB; $dbName = $_DATABASE; $dbType = "postgres";
	$sql = "SELECT to_char(to_date(DE.fec_emi_dte, 'YYYY-MM-DD'),'YYYY') AS anio, mes_nombre(to_char(to_date(DE.fec_emi_dte, 'YYYY-MM-DD'),'MM')) AS periodo, DE.tipo_docu AS tipo, TD.desc_tipo_docu AS desc_tipo, DE.folio_dte AS folio, to_char(to_date(DE.fec_emi_dte, 'YYYY-MM-DD'),'DD-MM-YYYY') AS fecha_doc, dte_estado(XD.est_xdte) AS estado, DE.rut_rec_dte || '-' || DE.dig_rec_dte AS rut_rece, DE.nom_rec_dte AS nom_rece, DE.mntneto_dte AS neto, DE.mnt_exen_dte AS exento, DE.iva_dte AS iva, DE.mont_tot_dte AS total, CASE WHEN CE.rut_contr IS NOT NULL THEN 'Si' ELSE 'No' END AS rece_elec FROM dte_enc DE LEFT JOIN contrib_elec CE ON CE.rut_contr = DE.rut_rec_dte, dte_tipo TD, xmldte XD WHERE DE.tipo_docu = TD.tipo_docu AND XD.tipo_docu = DE.tipo_docu AND XD.folio_dte = DE.folio_dte AND XD.codi_empr = DE.codi_empr ";
	if($_EST_DTE != "") $sql .= " AND XD.est_xdte = $_EST_DTE ";
	if($nTipoDocu != "") $sql .= " AND DE.tipo_docu = $nTipoDocu ";
	if($nAnio != "") $sql .= " AND to_char(to_date(DE.fec_emi_dte, 'YYYY-MM-DD'), 'YYYY') = '" . $nAnio . "' ";
	if($_COLUM_SEARCH == "DE.fec_emi_dte"){
		if($_STRING_SEARCH0 != "" && $_STRING_SEARCH2 != "") $sql .= " AND TO_DATE(DE.fec_emi_dte,'YYYY/MM/DD') BETWEEN ('" . str_replace("'","''",$_STRING_SEARCH0) . "') AND ('" . str_replace("'","''",$_STRING_SEARCH2) . "') ";
	}
	else{
		if($_STRING_SEARCH != "") $sql .= " AND UPPER(" . $_COLUM_SEARCH . ") LIKE UPPER('" . str_replace("'","''",$_STRING_SEARCH) . "%') ";
	}
	$dg = new C_DataGrid($hostName, $userName, $password, $dbName, $dbType);
	$dg->set_gridpath($_LINK_BASE . "dte/include/");
	$dg->set_sql($sql);
	$dg->set_page_size(15);
	$dg->set_allow_export(true);
	$dg->set_col_title("anio", "Anio");
	$dg->set_col_title("periodo", "Periodo");
	$dg->set_col_title("tipo", "Tipo");
	$dg->set_col_title("folio", "Folio");
	$dg->set_col_title("estado", "Estado");
	$dg->set_col_title("neto", "Neto");
	$dg->set_col_title("exento", "Exento");
	$dg->set_col_title("iva", "Iva");
	$dg->set_col_title("total", "Total");
	$dg->set_col_title("desc_tipo", "Descripcion de Tipo");
	$dg->set_col_title("fecha_doc", "Fecha Doc.");
	$dg->set_col_title("rut_rece", "Rut Receptor");
	$dg->set_col_title("nom_rece", "Nombre Receptor");
	$dg->set_col_title("rece_elec", "Receptor Electronico");
	$dg->set_theme("sweet");
	$dg->set_ok_showcredit(false);
	ob_start(); $dg->display(); $gridHtml = ob_get_clean();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="shortcut icon" href="/favicon.ico">
	<title>DTE procesados - Portal DTE</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<script type="text/javascript" src="<?php echo h($_LINK_BASE); ?>javascript/common.js"></script>
	<script type="text/javascript" src="<?php echo h($_LINK_BASE); ?>javascript/msg.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo h($_LINK_BASE); ?>css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
	<script type="text/javascript" src="<?php echo h($_LINK_BASE); ?>javascript/calendar.js"></script>
	<script type="text/javascript" src="<?php echo h($_LINK_BASE); ?>javascript/lang/calendar-es.js"></script>
	<script type="text/javascript" src="<?php echo h($_LINK_BASE); ?>javascript/calendar-setup.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}.page-shell{max-width:1600px;margin:0 auto;padding:1rem}.page-hero{background:linear-gradient(135deg,#0f172a 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(15,23,42,.18);margin-bottom:1.25rem}.hero-pill{display:inline-flex;align-items:center;gap:.35rem;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:999px;padding:.45rem .85rem;font-size:.82rem}.card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden;margin-bottom:1rem}.card-header{background:#0f172a;color:#fff}.card-header .small{color:rgba(255,255,255,.75)}.filter-summary{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:14px;padding:.9rem 1rem}.filter-chip{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .75rem;background:#fff;border:1px solid #dbe7f3;border-radius:999px;font-size:.8rem;color:#334155;margin:.2rem}.table-wrap{overflow:auto}.form-label{font-weight:600;color:#334155}.search-switch{min-height:42px}.phpgrid table{width:100%}#loaderContainer{position:fixed;top:0;right:0;bottom:0;left:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{text-align:center;vertical-align:middle}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
	</style>
	<script type="text/javascript">
		function _body_onload(){ try{SetContext('clients');setActiveButtonByName('clients');}catch(e){} try{loff();}catch(e){} try{muestraDivCampos();}catch(e){} }
		function _body_onunload(){ try{lon();}catch(e){} }
		var opt_no_frames = false, opt_integrated_mode = false;
		try{setActiveButtonByName("clients");}catch(e){}
		function chListBoxSearch(){ var F = document._FSEARCH; if(!F || !F._COLUM_SEARCH) return; for(var i=0; i < F._COLUM_SEARCH.length; i++){ if(F._COLUM_SEARCH.options[i].value == "<?php echo h($_COLUM_SEARCH); ?>") F._COLUM_SEARCH.options[i].selected = true; } }
		function chSelDelEmp(){ var F = document._FDEL; if(!F) return false; for(var i=0; i < F.elements.length; i++){ if(F.elements[i].name == "del[]" && F.elements[i].checked == true) return true; } return false; }
		function chDelEmp(){ if(chSelDelEmp() == true){ if(confirm(_MSG_DEL_CONFIR)) document._FDEL.submit(); } else alert(_MSG_DTE_DEL); }
		function chDchALL(){ var F = document._FDEL; if(!F || !F.clientslistSelectAll) return; for(var i=0; i < F.elements.length; i++){ if(F.elements[i].name == "del[]") F.elements[i].checked = !!F.clientslistSelectAll.checked; } }
		function chListBoxEstado(){ var F = document._FSEARCH; if(!F || !F._EST_DTE) return; for(var i=0; i < F._EST_DTE.length; i++){ if(F._EST_DTE.options[i].value == "<?php echo h($_EST_DTE); ?>") F._EST_DTE.options[i].selected = true; } }
		function muestraDivCampos(){ var F = document._FSEARCH; if(!F || !F._COLUM_SEARCH) return; var opt = F._COLUM_SEARCH.options[F._COLUM_SEARCH.selectedIndex].value; var div1 = document.getElementById('fechaEmisionFin'); var div2 = document.getElementById('noFechaEmisionFin'); if(!div1 || !div2) return; if(opt == "D.fec_emi_dte"){ div1.style.display = 'block'; div2.style.display = 'none'; } else { div1.style.display = 'none'; div2.style.display = 'block'; } }
	</script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="<?php echo h($_LINK_BASE); ?>skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2" /><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
	<div class="page-shell">
		<div class="page-hero d-flex flex-wrap justify-content-between gap-3 align-items-center"><div><h1 class="h3 mb-2">DTE procesados</h1><p class="mb-0 opacity-75">Vista remaquetada conservando <code>phpgrid</code>, sesi&oacute;n, calendario legacy, exportaci&oacute;n y el formulario POST a <code>dte/pro_dte.php</code>.</p></div><div class="d-flex flex-wrap gap-2"><span class="hero-pill"><i class="bi bi-funnel"></i><?php echo count($filtrosResumen); ?> filtros activos</span><span class="hero-pill"><i class="bi bi-table"></i>C_DataGrid</span><span class="hero-pill"><i class="bi bi-download"></i>Exportaci&oacute;n habilitada</span></div></div>
		<div class="card">
			<div class="card-header d-flex flex-wrap justify-content-between gap-2 align-items-center"><div><div class="fw-semibold">Filtros y b&uacute;squeda</div><div class="small">Se conservan <code>_Buscar=OK</code>, sesi&oacute;n persistida y los nombres originales de GET.</div></div><button type="submit" form="formSearchDtePro" class="btn btn-light btn-sm"><i class="bi bi-search me-1"></i>Buscar</button></div>
			<div class="card-body">
				<form name="_FSEARCH" id="formSearchDtePro" method="get" action="<?php echo h($_LINK_BASE . $sLinkActual); ?>">
					<input type="hidden" name="_Buscar" value="OK">
					<div class="row g-3 align-items-end">
						<div class="col-xl-2 col-lg-3 col-md-6"><label class="form-label mb-1">Tipo documento</label><select name="nTipoDocu" class="form-select form-select-sm"><option value="">Tipo Documento (Todos)</option><?php while(!$resultTipos->EOF){ $nTipoDocuTmp = trim($resultTipos->fields["tipo_docu"]); $sDescTipoDocu = trim($resultTipos->fields["desc_tipo_docu"]); ?><option value="<?php echo h($nTipoDocuTmp); ?>"<?php echo (trim($nTipoDocuTmp) == trim($nTipoDocu) ? " selected" : ""); ?>><?php echo h($sDescTipoDocu); ?></option><?php $resultTipos->MoveNext(); } ?></select></div>
						<div class="col-xl-2 col-lg-3 col-md-6"><label class="form-label mb-1">A&ntilde;o</label><select name="nAnio" class="form-select form-select-sm"><option value="">A&ntilde;o (Todos)</option><?php for($i = $nAnioMayor; $i >= $nAnioMenor; $i--){ ?><option value="<?php echo h($i); ?>"<?php echo (trim($i) == trim($nAnio) ? " selected" : ""); ?>><?php echo h($i); ?></option><?php } ?></select></div>
						<div class="col-xl-2 col-lg-3 col-md-6"><label class="form-label mb-1">Estado</label><select name="_EST_DTE" class="form-select form-select-sm"><option value="">Estado Todos</option><option value="0">DTE Cargados</option><option value="1">DTE Firmados</option><option value="3">DTE Con ERROR</option><option value="5">DTE Empaquetados</option><option value="13">DTE Enviados SII</option><option value="29">DTE Aceptados SII</option><option value="45">DTE Con Reparos SII</option><option value="77">DTE Rechazados SII</option><option value="157">DTE Enviados a Clientes</option><option value="413">DTE Aceptados por Clientes</option></select><script>chListBoxEstado();</script></div>
						<div class="col-xl-3 col-lg-3 col-md-6"><label class="form-label mb-1">Campo de b&uacute;squeda</label><select name="_COLUM_SEARCH" class="form-select form-select-sm" onchange="muestraDivCampos();"><option value="D.folio_dte">Folio Dte</option><option value="D.fec_emi_dte">Fecha Emisi&oacute;n</option><option value="D.fec_venc_dte">Fecha Vencimiento</option><option value="D.nom_rec_dte">Razon Social Receptor</option><option value="D.giro_rec_dte">Giro Receptor</option></select><script>chListBoxSearch();</script></div>
						<div class="col-xl-3 col-lg-12 col-md-12"><label class="form-label mb-1">Valor</label><div id="noFechaEmisionFin" class="search-switch"><input type="text" name="_STRING_SEARCH" id="searchInput" value="<?php echo h($_STRING_SEARCH); ?>" size="20" maxlength="245" class="form-control form-control-sm"></div><div id="fechaEmisionFin" class="search-switch" style="display:none;"><div class="row g-2"><div class="col-sm-6"><input type="text" id="_STRING_SEARCH0" name="_STRING_SEARCH0" onfocus="this.blur();" value="<?php echo h($_STRING_SEARCH0); ?>" size="20" maxlength="245" class="form-control form-control-sm" placeholder="Desde"></div><div class="col-sm-6"><input type="text" id="_STRING_SEARCH2" name="_STRING_SEARCH2" onfocus="this.blur();" value="<?php echo h($_STRING_SEARCH2); ?>" size="20" maxlength="245" class="form-control form-control-sm" placeholder="Hasta"></div></div></div></div>
					</div>
				</form>
				<script type="text/javascript">Calendar.setup({inputField:"_STRING_SEARCH0",ifFormat:"%Y/%m/%d",button:"_STRING_SEARCH0",align:"Tl",singleClick:true});Calendar.setup({inputField:"_STRING_SEARCH2",ifFormat:"%Y/%m/%d",button:"_STRING_SEARCH2",align:"Tl",singleClick:true});muestraDivCampos();</script>
				<div class="filter-summary mt-3"><?php if(count($filtrosResumen) > 0){ foreach($filtrosResumen as $filtro){ ?><span class="filter-chip"><i class="bi bi-dot"></i><?php echo $filtro; ?></span><?php } } else { ?><span class="text-muted">Sin filtros activos. Se mantiene la persistencia por sesi&oacute;n original.</span><?php } ?></div>
			</div>
		</div>
		<div class="card">
			<div class="card-header"><div class="fw-semibold">Listado con phpgrid</div><div class="small">Se mantiene <code>set_allow_export(true)</code>, la consulta SQL original y el formulario <code>_FDEL</code>.</div></div>
			<div class="card-body">
				<form name="_FDEL" method="post" action="dte/pro_dte.php"><input type="hidden" name="sAccion" value="E"><div class="table-wrap"><?php echo $gridHtml; ?></div></form>
			</div>
		</div>
	</div>
</body>
</html>