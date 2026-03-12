<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/ver_aut_adm.php");
include("../include/db_lib.php");
include("../include/tables.php");

$conn = conn();
$sLinkActual = "mantencion/list_tip_doc.php";
$baseListUrl = $_LINK_BASE . $sLinkActual;
$searchOptions = array("desc_tipo_docu" => "Tipo Documento", "tipo_docu" => html_entity_decode('C&oacute;digo Documento', ENT_QUOTES, 'ISO-8859-1'));
$orderColumns = array("tipo_docu", "desc_tipo_docu");
$searchColumn = trim($_COLUM_SEARCH);
if(!isset($searchOptions[$searchColumn])) $searchColumn = "desc_tipo_docu";
$stringSearch = trim($_STRING_SEARCH);
$orderByColumn = trim($_ORDER_BY_COLUM);
if(!in_array($orderByColumn, $orderColumns)) $orderByColumn = "";
$nivelByOrder = strtoupper(trim($_NIVEL_BY_ORDER));
if($nivelByOrder != "ASC" && $nivelByOrder != "DESC") $nivelByOrder = "";
$_COLUM_SEARCH = $searchColumn; $_STRING_SEARCH = $stringSearch;
$_ORDER_BY_COLUM = $orderByColumn; $_NIVEL_BY_ORDER = $nivelByOrder;
function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }
function buildOrderUrl($baseListUrl, $column, $nivelByOrder, $searchColumn, $stringSearch){ return $baseListUrl . "?_ORDER_BY_COLUM=" . urlencode($column) . "&_NIVEL_BY_ORDER=" . urlencode($nivelByOrder) . "&_COLUM_SEARCH=" . urlencode($searchColumn) . "&_STRING_SEARCH=" . urlencode($stringSearch) . "&_ORDER_CAMBIA=Y"; }

$sql = "SELECT tipo_docu, desc_tipo_docu FROM dte_tipo WHERE 1=1 ";
if($stringSearch != ""){
    if($searchColumn == "tipo_docu") $sql .= " AND UPPER(CAST(tipo_docu AS VARCHAR)) LIKE UPPER('" . str_replace("'", "''", $stringSearch) . "%') ";
    else $sql .= " AND UPPER(desc_tipo_docu) LIKE UPPER('" . str_replace("'", "''", $stringSearch) . "%') ";
}
if($orderByColumn == "") $sql .= " ORDER BY desc_tipo_docu "; else $sql .= " ORDER BY " . $orderByColumn . " " . $nivelByOrder . " ";
$activeSort = ($orderByColumn == "") ? "desc_tipo_docu" : $orderByColumn;
$sortClasses = array("tipo_docu" => "", "desc_tipo_docu" => "");
$sortIcons = array("tipo_docu" => "", "desc_tipo_docu" => "");
$sortClasses[$activeSort] = "class=\"table-active\"";
$sortIcons[$activeSort] = "<img src='" . $_IMG_BY_ORDER . "' alt='' class='ms-1'>";
$result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);
$sPaginaResult = sPagina($conn, $sql, $sLinkActual);
$hayResultados = !$result->EOF;
$cantidadHerramientas = isset($aBotonTipDocHerramienta["ID"]) ? count($aBotonTipDocHerramienta["ID"]) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="shortcut icon" href="/favicon.ico">
    <title>Tipo Documento - Portal DTE</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <base href="<?php echo $_LINK_BASE; ?>" />
    <script type="text/javascript" src="javascript/common.js"></script>
    <script type="text/javascript" src="javascript/msg.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}.page-shell{max-width:1100px;margin:0 auto;padding:1rem}.page-hero{background:linear-gradient(135deg,#1e293b 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(15,23,42,.18);margin-bottom:1.25rem}.hero-icon{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:1.4rem}.hero-pills,.quick-actions,.paging{display:flex;flex-wrap:wrap}.hero-pills{gap:.75rem}.hero-pill{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:999px;padding:.45rem .85rem;font-size:.82rem}.card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden;margin-bottom:1rem}.card-header{background:#1e293b;color:#fff;padding:.9rem 1rem}.card-header .small{color:rgba(255,255,255,.75)}.quick-actions{gap:.65rem}.filter-summary{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:14px;padding:.9rem 1rem}.filter-chip{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .75rem;background:#fff;border:1px solid #dbe7f3;border-radius:999px;font-size:.8rem;color:#334155}.table thead th{background:#1e293b;color:#fff;white-space:nowrap;vertical-align:middle}.table thead th.table-active{background:#0b5ed7;color:#fff}.table tbody td{vertical-align:middle;font-size:.92rem}.table tbody tr:hover{background:#f8fbff}.sort-link{color:#fff;text-decoration:none}.sort-link:hover{color:#dbeafe}.doc-code{display:inline-flex;align-items:center;padding:.35rem .7rem;border-radius:999px;background:#e2e8f0;color:#0f172a;font-weight:700}.doc-meta{color:#64748b;font-size:.8rem}.empty-state{padding:4rem 1rem;text-align:center;color:#6b7280}.empty-state i{font-size:3rem}.paging{gap:.45rem;align-items:center}.paging a,.paging span{display:inline-flex;align-items:center;justify-content:center;min-width:2rem;height:2rem;border:1px solid #d0d7e2;border-radius:999px;padding:0 .7rem;background:#fff;color:#0f172a;text-decoration:none;font-size:.85rem}.paging a:hover{background:#eff6ff;border-color:#93c5fd}#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}@media (max-width:767.98px){.page-shell{padding:.75rem}.page-hero{padding:1.1rem}}
    </style>
    <script type="text/javascript">
        function _body_onload(){ try{SetContext('clients');setActiveButtonByName('clients');}catch(e){} try{loff();}catch(e){} }
        function _body_onunload(){ try{lon();}catch(e){} }
        var opt_no_frames = false, opt_integrated_mode = false;
        function chSelDelEmp(){ var F = document._FDEL; for(var i=0;i<F.elements.length;i++){ if(F.elements[i].name=='del[]' && F.elements[i].checked===true) return true; } return false; }
        function chDelEmp(){ if(chSelDelEmp()){ if(confirm(_MSG_DEL_TDOC)) document._FDEL.submit(); } else alert(_MSG_SEL_TDOC_DEL); }
        function chDchALL(){ var F = document._FDEL; var obj = document.getElementById('clientslistSelectAll'); for(var i=0;i<F.elements.length;i++){ if(F.elements[i].name=='del[]') F.elements[i].checked = obj.checked; } }
    </script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP">
    <a href="#" name="top" id="top"></a>
    <table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2" /><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
    <div class="page-shell">
	        <div class="page-hero"><div class="row g-3 align-items-center"><div class="col-lg-7"><div class="d-flex align-items-start gap-3"><div class="hero-icon"><i class="bi bi-file-earmark-text"></i></div><div><h1 class="h3 mb-2">Administraci&oacute;n de Tipos de Documento</h1></div></div></div><div class="col-lg-5"><div class="hero-pills justify-content-lg-end"><span class="hero-pill"><i class="bi bi-search me-1"></i>Filtro por c&oacute;digo o descripci&oacute;n</span><span class="hero-pill"><i class="bi bi-grid-3x3-gap me-1"></i><?php echo $cantidadHerramientas; ?> acceso r&aacute;pido</span><span class="hero-pill"><i class="bi bi-arrow-down-up me-1"></i>Orden por c&oacute;digo o tipo</span></div></div></div></div>
	        <div class="card"><div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2"><div><div class="fw-semibold"><i class="bi bi-lightning-charge me-2"></i>Herramientas r&aacute;pidas</div></div><span class="badge rounded-pill text-bg-light text-primary-emphasis">Operaciones del m&oacute;dulo</span></div><div class="card-body"><div class="quick-actions"><?php if($cantidadHerramientas > 0): ?><?php for($i = 0; $i < $cantidadHerramientas; $i++): ?><button type="button" class="btn btn-outline-primary" onclick="<?php echo h($aBotonTipDocHerramienta['ONCLICK'][$i]); ?>"><i class="bi bi-arrow-right-circle me-1"></i><?php echo h($aBotonTipDocHerramienta['SETIQUETA'][$i]); ?></button><?php endfor; ?><?php else: ?><span class="text-muted">No hay herramientas configuradas para esta pantalla.</span><?php endif; ?></div></div></div>
	        <div class="card"><div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2"><div><div class="fw-semibold"><i class="bi bi-search me-2"></i>B&uacute;squeda de tipos de documento</div></div><button type="button" class="btn btn-danger btn-sm" onclick="chDelEmp();"><i class="bi bi-trash me-1"></i>Eliminar selecci&oacute;n</button></div><div class="card-body"><?php if($stringSearch != ""): ?><div class="filter-summary mb-4"><span class="filter-chip"><i class="bi bi-funnel-fill"></i><?php echo h($searchOptions[$searchColumn]); ?>: <?php echo h($stringSearch); ?></span></div><?php endif; ?><form name="_FSEARCH" method="get" action="<?php echo h($baseListUrl); ?>" class="row g-3 align-items-end"><div class="col-md-4"><label class="form-label fw-semibold">Buscar por</label><select name="_COLUM_SEARCH" class="form-select"><?php foreach($searchOptions as $value => $label): ?><option value="<?php echo h($value); ?>"<?php if($searchColumn == $value) echo ' selected'; ?>><?php echo h($label); ?></option><?php endforeach; ?></select></div><div class="col-md-5"><label class="form-label fw-semibold">Texto de b&uacute;squeda</label><input type="text" name="_STRING_SEARCH" class="form-control" maxlength="100" value="<?php echo h($stringSearch); ?>" placeholder="Ingrese un criterio de b&uacute;squeda"></div><div class="col-md-3"><div class="d-flex flex-wrap gap-2 justify-content-md-end"><button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Buscar</button><a href="<?php echo h($baseListUrl); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Limpiar</a></div></div></form></div></div>
	        <div class="card"><div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2"><div><div class="fw-semibold"><i class="bi bi-table me-2"></i>Listado de tipos de documento</div></div><span class="badge rounded-pill text-bg-light text-primary-emphasis">Paginaci&oacute;n original preservada</span></div><div class="card-body p-0"><form name="_FDEL" method="post" action="mantencion/pro_tc.php"><input type="hidden" name="sAccion" value="E"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th width="24%" <?php echo $sortClasses['tipo_docu']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'tipo_docu', $nivelByOrder, $searchColumn, $stringSearch)); ?>">C&oacute;digo Documento <?php echo $sortIcons['tipo_docu']; ?></a></th><th width="68%" <?php echo $sortClasses['desc_tipo_docu']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'desc_tipo_docu', $nivelByOrder, $searchColumn, $stringSearch)); ?>">Tipo Documento <?php echo $sortIcons['desc_tipo_docu']; ?></a></th><th width="8%" class="text-center"><input type="checkbox" class="form-check-input" name="clientslistSelectAll" id="clientslistSelectAll" value="true" onclick="chDchALL();"></th></tr></thead><tbody><?php if($hayResultados): ?><?php while(!$result->EOF): ?><?php $nCodDoc = trim($result->fields['tipo_docu']); $sDescDoc = trim($result->fields['desc_tipo_docu']); $strParamLink = 'nCodDoc=' . urlencode($nCodDoc); $strParamLink .= '&nCodDocNew=' . urlencode($nCodDoc); $strParamLink .= '&sDescDoc=' . urlencode($sDescDoc); $strParamLink .= '&sAccion=M'; $editUrl = $_LINK_BASE . 'mantencion/form_tc.php?' . $strParamLink; ?><tr><td><span class="doc-code"><?php echo h($nCodDoc); ?></span></td><td><a href="<?php echo h($editUrl); ?>" class="fw-semibold text-decoration-none"><?php echo h($sDescDoc); ?></a><div class="doc-meta mt-1">Edici&oacute;n legacy preservada en formulario de tipo de documento.</div></td><td class="text-center"><input type="checkbox" class="form-check-input" name="del[]" value="<?php echo h($nCodDoc); ?>"></td></tr><?php $result->MoveNext(); ?><?php endwhile; ?><?php else: ?><tr><td colspan="3"><div class="empty-state"><i class="bi bi-inbox"></i><h5 class="mt-3">No hay tipos de documento para mostrar</h5><p class="mb-0">Pruebe con otro criterio de b&uacute;squeda o limpie los filtros para revisar el listado completo.</p></div></td></tr><?php endif; ?></tbody></table></div></form></div><?php if(trim($sPaginaResult) != ""): ?><div class="card-footer bg-white"><div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2"><span class="text-muted small">La paginaci&oacute;n original se mantiene para preservar la navegaci&oacute;n del m&oacute;dulo.</span><div class="paging"><?php echo $sPaginaResult; ?></div></div></div><?php endif; ?></div>
    </div>
</body>
</html>
