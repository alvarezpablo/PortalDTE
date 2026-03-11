<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/ver_aut_adm_super.php");
include("../include/ver_emp_adm.php");
include("../include/db_lib.php");
include("../include/tables.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }
function jsq($value){ return str_replace(array("\\", "'", "\r", "\n"), array("\\\\", "\\'", "", "\\n"), (string)$value); }
function alertExpr($value){
	$value = trim((string)$value);
	if($value === "") return "";
	if(preg_match('/^[_A-Za-z][_A-Za-z0-9]*$/', $value)) return "alert(" . $value . ");";
	return "alert('" . jsq($value) . "');";
}
function buildOrderUrl($baseListUrl, $column, $nivelByOrder, $columnSearch, $stringSearch){
	return $baseListUrl . "&_ORDER_BY_COLUM=" . urlencode($column)
		. "&_NIVEL_BY_ORDER=" . urlencode($nivelByOrder)
		. "&_COLUM_SEARCH=" . urlencode($columnSearch)
		. "&_STRING_SEARCH=" . urlencode($stringSearch)
		. "&_ORDER_CAMBIA=Y";
}
function cambiarEstado($nCodCorrel, $nTipDte){
	echo "<select name='cambia' class='form-select form-select-sm status-select' onChange='confirmCambio(" . (int)$nCodCorrel . ", \"" . h($nTipDte) . "\", this);'>\n";
	echo "<option value=''></option>\n";
	echo "<option value='ACEPTADO'>Aceptar</option>\n";
	echo "<option value='RECHAZADO'>Rechazar</option>\n";
	echo "</select>\n";
}

$conn = conn();
$sMsgJs = isset($_GET["sMsgJs"]) ? trim((string)$_GET["sMsgJs"]) : "";
$nTipDocFiltro = isset($_GET["nTipDoc"]) ? trim((string)$_GET["nTipDoc"]) : "";
$sLinkActual = "factura/list_recielec.php?nTipDoc=" . urlencode($nTipDocFiltro);
$baseListUrl = $_LINK_BASE . $sLinkActual;
$alertCall = alertExpr($sMsgJs);
$columnSearch = trim($_COLUM_SEARCH);
if($columnSearch == "") $columnSearch = "rut_rec_dte";
if($columnSearch != "rut_rec_dte") $columnSearch = "rut_rec_dte";
$stringSearch = trim($_STRING_SEARCH);
$orderColumns = array("tipo_docu", "fec_emi_doc", "rut_rec_dte", "nom_rec_dte", "mntneto_dte", "mnt_exen_dte", "iva_dte", "mont_tot_dte", "est_doc");
$orderByColumn = trim($_ORDER_BY_COLUM);
if(!in_array($orderByColumn, $orderColumns)) $orderByColumn = "";
$nivelByOrder = strtoupper(trim($_NIVEL_BY_ORDER));
if($nivelByOrder != "ASC" && $nivelByOrder != "DESC") $nivelByOrder = "";
$_COLUM_SEARCH = $columnSearch;
$_STRING_SEARCH = $stringSearch;
$_ORDER_BY_COLUM = $orderByColumn;
$_NIVEL_BY_ORDER = $nivelByOrder;

$sortClasses = array();
$sortIcons = array();
foreach($orderColumns as $column){
	$sortClasses[$column] = "";
	$sortIcons[$column] = "";
}
$activeSort = ($orderByColumn != "") ? $orderByColumn : "fec_emi_doc";
$sortClasses[$activeSort] = "class=\"table-active\"";
$sortIcons[$activeSort] = "<img src='" . h($_IMG_BY_ORDER) . "' alt='' class='ms-1'>";

$sql = " SELECT correl_doc, fact_ref, fec_emi_doc, per_desd_dte, per_hast_dte, rut_emis_dte, digi_emis_dte, nom_emis_dte, dir_orig_dte, rut_rec_dte, dig_rec_dte, nom_rec_dte, mntneto_dte, mnt_exen_dte, tasa_iva_dte, iva_dte, mont_tot_dte, tipo_docu, (SELECT desc_tipo_docu FROM dte_tipo WHERE tipo_docu = documentoscompras_temp.tipo_docu) AS desc_docu, fact_ref, est_doc, path_pdf FROM documentoscompras_temp WHERE est_doc is null AND codi_empr = '" . str_replace("'", "''", $_SESSION["_COD_EMP_USU_SESS"]) . "' ";
if($stringSearch != "")
	$sql .= " AND UPPER(" . $columnSearch . ") LIKE UPPER('" . str_replace("'", "''", $stringSearch) . "%') ";
if($orderByColumn == "")
	$sql .= " ORDER BY fec_emi_doc DESC ";
else
	$sql .= " ORDER BY " . $orderByColumn . " " . $nivelByOrder . " ";

$result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);
$sPaginaResult = sPagina($conn, $sql, $sLinkActual);
$hayResultados = !$result->EOF;
$isAdminRole = (trim($_SESSION["_COD_ROL_SESS"]) == "1");
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="shortcut icon" href="/favicon.ico">
	<title>DTE Recibidos Pendientes - Portal DTE</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<base href="<?php echo h($_LINK_BASE); ?>" />
	<script type="text/javascript" src="javascript/common.js"></script>
	<script type="text/javascript" src="javascript/msg.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}.page-shell{max-width:1500px;margin:0 auto;padding:1rem}.page-hero{background:linear-gradient(135deg,#0f172a 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(15,23,42,.18);margin-bottom:1.25rem}.hero-icon{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:1.4rem}.hero-pills,.paging{display:flex;flex-wrap:wrap}.hero-pills{gap:.75rem}.hero-pill{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:999px;padding:.45rem .85rem;font-size:.82rem}.card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden;margin-bottom:1rem}.card-header{background:#0f172a;color:#fff;padding:.9rem 1rem}.card-header .small{color:rgba(255,255,255,.75)}.filter-summary{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:14px;padding:.9rem 1rem}.filter-chip,.status-badge{display:inline-flex;align-items:center;gap:.35rem;border-radius:999px;font-size:.8rem}.filter-chip{padding:.35rem .75rem;background:#fff;border:1px solid #dbe7f3;color:#334155}.status-badge{padding:.38rem .75rem;font-weight:600}.status-pending{background:#fff7ed;color:#9a3412}.table thead th{background:#0f172a;color:#fff;white-space:nowrap;vertical-align:middle}.table thead th.table-active{background:#0b5ed7;color:#fff}.table tbody td{vertical-align:middle;font-size:.88rem}.table tbody tr:hover{background:#f8fbff}.sort-link{color:#fff;text-decoration:none}.sort-link:hover{color:#dbeafe}.doc-meta{color:#64748b;font-size:.78rem}.empty-state{padding:4rem 1rem;text-align:center;color:#6b7280}.empty-state i{font-size:3rem}.paging{gap:.45rem;align-items:center}.paging a,.paging span{display:inline-flex;align-items:center;justify-content:center;min-width:2rem;height:2rem;border:1px solid #d0d7e2;border-radius:999px;padding:0 .7rem;background:#fff;color:#0f172a;text-decoration:none;font-size:.85rem}.paging a:hover{background:#eff6ff;border-color:#93c5fd}.status-select{min-width:120px}#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}@media (max-width:767.98px){.page-shell{padding:.75rem}.page-hero{padding:1.1rem}}
	</style>
	<script type="text/javascript">
		function _body_onload(){ try{setActiveButtonByName('clients');}catch(e){} try{loff();}catch(e){} <?php echo ($alertCall != "" ? $alertCall : ""); ?> }
		function _body_onunload(){ try{lon();}catch(e){} }
		var opt_no_frames = false, opt_integrated_mode = false, objTmpSelect = null;
		function confirmCambio(nCodDoc, nTipoDte, obj){
			var F = document._FENV;
			objTmpSelect = obj;
			if(confirm(_MSG_ACEPTA_RECHAZA_DTE)){
				F.nCodCorr.value = nCodDoc;
				F.sEstado.value = obj.options[obj.selectedIndex].value;
				F.sTipFac.value = '';
				F.nMotIvaNoRec.value = '';
				if(nTipoDte == '33' || nTipoDte == '34' || nTipoDte == '30' || nTipoDte == '45' || nTipoDte == '46') enviaEstResp('factura/form_motivo.php?s=' + obj.value); else F.submit();
			}else obj.options[0].selected = true;
		}
		function enviaEstResp(URL){
			var wTipoMot = window.open(URL, 'tipoMotivo', 'dependent=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,width=500,height=400');
			var centroAncho = (screen.width/2) - 400;
			var centroAlto = (screen.height/2) - 200;
			wTipoMot.moveTo(centroAlto, centroAncho);
		}
	</script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP">
	<form name="_FENV" method="post" action="factura/pro_cambiar.php">
		<input type="hidden" name="nCodCorr" value="">
		<input type="hidden" name="sEstado" value="">
		<input type="hidden" name="sTipFac" value="">
		<input type="hidden" name="nMotIvaNoRec" value="">
		<input type="hidden" name="sRecinto" value="">
		<input type="hidden" name="sFirma" value="">
		<input type="hidden" name="nGeneraAcuse" value="">
		<input type="hidden" name="nAprobacion" value="">
		<input type="hidden" name="sRespuesta" value="">
		<input type="hidden" name="sGlosa" value="">
	</form>
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2" /><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
	<div class="page-shell">
		<div class="page-hero"><div class="row g-3 align-items-center"><div class="col-lg-8"><div class="d-flex align-items-start gap-3"><div class="hero-icon"><i class="bi bi-inbox"></i></div><div><h1 class="h3 mb-2">Recepci&oacute;n de DTE pendientes</h1><p class="mb-0 opacity-75">Revise documentos recibidos, consulte PDF/XML y mantenga el flujo vigente de aceptaci&oacute;n o rechazo sin alterar SQL ni procesadores.</p></div></div></div><div class="col-lg-4"><div class="hero-pills justify-content-lg-end"><span class="hero-pill"><i class="bi bi-search me-1"></i>Filtro por RUT emisor</span><span class="hero-pill"><i class="bi bi-arrow-left-right me-1"></i>Orden legacy preservado</span><span class="hero-pill"><i class="bi bi-check2-square me-1"></i>Cambio de estado activo</span></div></div></div></div>
		<div class="card"><div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2"><div><div class="fw-semibold"><i class="bi bi-search me-2"></i>B&uacute;squeda de documentos</div><div class="small mt-1">Se mantiene la b&uacute;squeda original por RUT emisor y la navegaci&oacute;n del listado.</div></div><span class="badge rounded-pill text-bg-light text-primary-emphasis">Pendientes</span></div><div class="card-body"><?php if($stringSearch != ""): ?><div class="filter-summary mb-4"><span class="filter-chip"><i class="bi bi-funnel-fill"></i>Rut Emisor: <?php echo h($stringSearch); ?></span></div><?php endif; ?><form name="_FSEARCH" method="get" action="<?php echo h($baseListUrl); ?>" class="row g-3 align-items-end"><input type="hidden" name="_COLUM_SEARCH" value="rut_rec_dte"><div class="col-md-8 col-lg-5"><label class="form-label fw-semibold">Rut Emisor</label><input type="text" name="_STRING_SEARCH" id="searchInput" class="form-control" value="<?php echo h($stringSearch); ?>" maxlength="8" placeholder="Ingrese RUT sin d&iacute;gito verificador"></div><div class="col-lg-4"><div class="d-flex flex-wrap gap-2 justify-content-lg-end"><button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Buscar</button><a href="<?php echo h($baseListUrl); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Limpiar</a></div></div></form></div></div>
		<div class="card"><div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2"><div><div class="fw-semibold"><i class="bi bi-table me-2"></i>Documentos recibidos</div><div class="small mt-1">Se conservan la paginaci&oacute;n `sPagina(...)`, los enlaces PDF/XML y la acci&oacute;n de cambio de estado.</div></div><span class="badge rounded-pill text-bg-light text-primary-emphasis">Empresa activa</span></div><div class="card-body p-0"><form name="_FDEL" method="post" action="factura/pro_noelecompra.php"><input type="hidden" name="sAccion" value="E"><input type="hidden" name="nTipDoc" value="<?php echo h($nTipDocFiltro); ?>"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th width="16%" <?php echo $sortClasses['tipo_docu']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'tipo_docu', $nivelByOrder, $columnSearch, $stringSearch)); ?>">Tipo Documento <?php echo $sortIcons['tipo_docu']; ?></a></th><th width="10%" <?php echo $sortClasses['fec_emi_doc']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'fec_emi_doc', $nivelByOrder, $columnSearch, $stringSearch)); ?>">Fecha Emisi&oacute;n <?php echo $sortIcons['fec_emi_doc']; ?></a></th><th width="8%">Folio DTE</th><th width="9%" <?php echo $sortClasses['rut_rec_dte']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'rut_rec_dte', $nivelByOrder, $columnSearch, $stringSearch)); ?>">Rut Emisor <?php echo $sortIcons['rut_rec_dte']; ?></a></th><th width="22%" <?php echo $sortClasses['nom_rec_dte']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'nom_rec_dte', $nivelByOrder, $columnSearch, $stringSearch)); ?>">Nombre Emisor <?php echo $sortIcons['nom_rec_dte']; ?></a></th><th width="7%" class="text-end" <?php echo $sortClasses['mntneto_dte']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'mntneto_dte', $nivelByOrder, $columnSearch, $stringSearch)); ?>">Mnt Neto <?php echo $sortIcons['mntneto_dte']; ?></a></th><th width="7%" class="text-end" <?php echo $sortClasses['mnt_exen_dte']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'mnt_exen_dte', $nivelByOrder, $columnSearch, $stringSearch)); ?>">Mnt Exento <?php echo $sortIcons['mnt_exen_dte']; ?></a></th><th width="7%" class="text-end" <?php echo $sortClasses['iva_dte']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'iva_dte', $nivelByOrder, $columnSearch, $stringSearch)); ?>">Mnt IVA <?php echo $sortIcons['iva_dte']; ?></a></th><th width="7%" class="text-end" <?php echo $sortClasses['mont_tot_dte']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'mont_tot_dte', $nivelByOrder, $columnSearch, $stringSearch)); ?>">Mnt Total <?php echo $sortIcons['mont_tot_dte']; ?></a></th><th width="4%">PDF</th><th width="4%">XML</th><?php if($isAdminRole): ?><th width="4%">SET XML</th><?php endif; ?><th width="9%" <?php echo $sortClasses['est_doc']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'est_doc', $nivelByOrder, $columnSearch, $stringSearch)); ?>">Estado <?php echo $sortIcons['est_doc']; ?></a></th></tr></thead><tbody><?php if($hayResultados): ?><?php while(!$result->EOF): ?><?php $nCodDoc = trim($result->fields["correl_doc"]); $nFolio = trim($result->fields["fact_ref"]); $dFecEmi = trim($result->fields["fec_emi_doc"]); $nRutRec = trim($result->fields["rut_rec_dte"]); $nDigRec = trim($result->fields["dig_rec_dte"]); $sNomRec = trim($result->fields["nom_rec_dte"]); $nMntNeto = trim($result->fields["mntneto_dte"]); $nMntExen = trim($result->fields["mnt_exen_dte"]); $nIva = trim($result->fields["iva_dte"]); $nMntTotal = trim($result->fields["mont_tot_dte"]); $nTipDoc = trim($result->fields["tipo_docu"]); $sDescDocu = trim($result->fields["desc_docu"]); $sEstado = trim($result->fields["est_doc"]); $_file_pddf = trim($result->fields["path_pdf"]); $pdfUrl = ""; if(is_file($_file_pddf)) $pdfUrl = $_LINK_BASE . "dte/view_pdf_file.php?sUrlPdf=" . urlencode($_file_pddf); $xmlUrl = $_LINK_BASE . "dte/view_xmlrecibido.php?nFolioDte=" . urlencode($nFolio) . "&nTipoDocu=" . urlencode($nTipDoc); $setXmlUrl = $_LINK_BASE . "dte/view_setxmlrecibido.php?nFolioDte=" . urlencode($nFolio) . "&nTipoDocu=" . urlencode($nTipDoc); ?><tr><td><div class="fw-semibold"><?php echo h($sDescDocu); ?></div><div class="doc-meta mt-1">Correlativo: <?php echo h($nCodDoc); ?></div></td><td><?php echo h($dFecEmi); ?></td><td><?php echo h($nFolio); ?></td><td><?php echo h($nRutRec . "-" . $nDigRec); ?></td><td><?php echo h($sNomRec); ?></td><td class="text-end"><?php echo h($nMntNeto); ?></td><td class="text-end"><?php echo h($nMntExen); ?></td><td class="text-end"><?php echo h($nIva); ?></td><td class="text-end"><?php echo h($nMntTotal); ?></td><td><?php if($pdfUrl != ""): ?><a href="<?php echo h($pdfUrl); ?>" class="btn btn-outline-secondary btn-sm">Ver</a><?php endif; ?></td><td><a href="<?php echo h($xmlUrl); ?>" class="btn btn-outline-secondary btn-sm">XML</a></td><?php if($isAdminRole): ?><td><a href="<?php echo h($setXmlUrl); ?>" class="btn btn-outline-secondary btn-sm">SET</a></td><?php endif; ?><td><?php if($sEstado == ""){ cambiarEstado($nCodDoc, $nTipDoc); } else { ?><span class="status-badge status-pending"><?php echo h(ucfirst(strtolower($sEstado))); ?></span><?php } ?></td></tr><?php $result->MoveNext(); endwhile; ?><?php else: ?><tr><td colspan="<?php echo ($isAdminRole ? '13' : '12'); ?>"><div class="empty-state"><i class="bi bi-inbox"></i><h5 class="mt-3">No hay DTE pendientes para mostrar</h5><p class="mb-0">Pruebe con otro RUT emisor o limpie el filtro para revisar la recepci&oacute;n completa.</p></div></td></tr><?php endif; ?></tbody></table></div></form></div><?php if(trim($sPaginaResult) != ""): ?><div class="card-footer bg-white"><div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2"><span class="text-muted small">Paginaci&oacute;n original preservada para mantener la navegaci&oacute;n del m&oacute;dulo.</span><div class="paging"><?php echo $sPaginaResult; ?></div></div></div><?php endif; ?></div>
	</div>
</body>
</html>