<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/ver_aut_adm.php");
include("../include/db_lib.php");
include("../include/tables.php");

$conn = conn();
$sLinkActual = "empresa/listempre.php";
$baseListUrl = $_LINK_BASE . $sLinkActual;
$searchOptions = array("rut_empr" => "RUT", "rs_empr" => html_entity_decode('Raz&oacute;n Social', ENT_QUOTES, 'ISO-8859-1'), "dir_empr" => html_entity_decode('Direcci&oacute;n', ENT_QUOTES, 'ISO-8859-1'));
$orderColumns = array("rut_empr", "rs_empr", "dir_empr");
$columnSearch = trim($_COLUM_SEARCH);
if(!isset($searchOptions[$columnSearch])) $columnSearch = "rs_empr";
$stringSearch = trim($_STRING_SEARCH);
$orderByColumn = trim($_ORDER_BY_COLUM);
if(!in_array($orderByColumn, $orderColumns)) $orderByColumn = "";
$nivelByOrder = strtoupper(trim($_NIVEL_BY_ORDER));
if($nivelByOrder != "ASC" && $nivelByOrder != "DESC") $nivelByOrder = "";
$_COLUM_SEARCH = $columnSearch; $_STRING_SEARCH = $stringSearch;
$_ORDER_BY_COLUM = $orderByColumn; $_NIVEL_BY_ORDER = $nivelByOrder;
function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }
function buildOrderUrl($baseListUrl, $column, $nivelByOrder, $columnSearch, $stringSearch){
    return $baseListUrl . "?_ORDER_BY_COLUM=" . urlencode($column)
        . "&_NIVEL_BY_ORDER=" . urlencode($nivelByOrder)
        . "&_COLUM_SEARCH=" . urlencode($columnSearch)
        . "&_STRING_SEARCH=" . urlencode($stringSearch)
        . "&_ORDER_CAMBIA=Y";
}
$sql = "SELECT codi_empr, rut_empr, dv_empr, rs_empr, dir_empr, cod_act, giro_emp, com_emp, fec_resolucion, num_resolucion FROM empresa WHERE 1=1 ";
if($stringSearch != "") $sql .= " AND UPPER(CAST(" . $columnSearch . " AS VARCHAR)) LIKE UPPER('" . str_replace("'","''",$stringSearch) . "%') ";
if($orderByColumn == "") $sql .= " ORDER BY rs_empr "; else $sql .= " ORDER BY " . $orderByColumn . " " . $nivelByOrder . " ";
$activeSort = ($orderByColumn == "") ? "rs_empr" : $orderByColumn;
$sortClasses = array("rut_empr" => "", "rs_empr" => "", "dir_empr" => "");
$sortIcons = array("rut_empr" => "", "rs_empr" => "", "dir_empr" => "");
$sortClasses[$activeSort] = "class=\"table-active\"";
$sortIcons[$activeSort] = "<img src='" . $_IMG_BY_ORDER . "' alt='' class='ms-1'>";
$result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);
$sPaginaResult = sPagina($conn, $sql, $sLinkActual);
$hayResultados = !$result->EOF;
$cantidadHerramientas = isset($aBotonEmpHerramienta["ID"]) ? count($aBotonEmpHerramienta["ID"]) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="shortcut icon" href="/favicon.ico">
    <title>Empresas - Portal DTE</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <base href="<?php echo $_LINK_BASE; ?>" />
    <script type="text/javascript" src="javascript/common.js"></script>
    <script type="text/javascript" src="javascript/msg.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}.page-shell{max-width:1440px;margin:0 auto;padding:1rem}.page-hero{background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(0,31,63,.18);margin-bottom:1.25rem}.hero-icon{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:1.4rem}.hero-pills,.quick-actions,.actions-stack,.paging{display:flex;flex-wrap:wrap}.hero-pills{gap:.75rem}.hero-pill{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:999px;padding:.45rem .85rem;font-size:.82rem}.card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden;margin-bottom:1rem}.card-header{background:#001f3f;color:#fff;padding:.9rem 1rem}.card-header .small{color:rgba(255,255,255,.75)}.quick-actions,.actions-stack{gap:.65rem}.filter-summary{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:14px;padding:.9rem 1rem}.filter-chip{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .75rem;background:#fff;border:1px solid #dbe7f3;border-radius:999px;font-size:.8rem;color:#334155}.table thead th{background:#001f3f;color:#fff;white-space:nowrap;vertical-align:middle}.table thead th.table-active{background:#0b5ed7;color:#fff}.table tbody td{vertical-align:middle;font-size:.88rem}.table tbody tr:hover{background:#f8fbff}.sort-link{color:#fff;text-decoration:none}.sort-link:hover{color:#dbeafe}.company-meta{color:#64748b;font-size:.78rem}.empty-state{padding:4rem 1rem;text-align:center;color:#6b7280}.empty-state i{font-size:3rem}.paging{gap:.45rem;align-items:center}.paging a,.paging span{display:inline-flex;align-items:center;justify-content:center;min-width:2rem;height:2rem;border:1px solid #d0d7e2;border-radius:999px;padding:0 .7rem;background:#fff;color:#0f172a;text-decoration:none;font-size:.85rem}.paging a:hover{background:#eff6ff;border-color:#93c5fd}#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}@media (max-width:767.98px){.page-shell{padding:.75rem}.page-hero{padding:1.1rem}}
    </style>
    <script type="text/javascript">
        function _body_onload(){ try{SetContext('clients');setActiveButtonByName('clients');}catch(e){} try{loff();}catch(e){} }
        function _body_onunload(){ try{lon();}catch(e){} }
        var opt_no_frames = false, opt_integrated_mode = false;
        function chSelDelEmp(){ var F = document._FDEL; for(var i=0;i<F.elements.length;i++){ if(F.elements[i].name=='del[]' && F.elements[i].checked==true) return true; } return false; }
        function chDelEmp(){ if(chSelDelEmp()){ if(confirm(_MSG_DEL_EMP)) document._FDEL.submit(); } else alert(_MSG_SEL_EMP_DEL); }
        function chDchALL(){ var F = document._FDEL; var obj = document.getElementById('clientslistSelectAll'); for(var i=0;i<F.elements.length;i++){ if(F.elements[i].name=='del[]') F.elements[i].checked = obj.checked; } }
    </script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP">
    <a href="#" name="top" id="top"></a>
    <table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2" /><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
    <div class="page-shell">
        <div class="page-hero"><div class="row g-3 align-items-center"><div class="col-lg-7"><div class="d-flex align-items-start gap-3"><div class="hero-icon"><i class="bi bi-buildings"></i></div><div><h1 class="h3 mb-2">Administraci&oacute;n de Empresas</h1><p class="mb-0 opacity-75">Busque, edite y configure empresas del portal manteniendo la operatoria administrativa existente.</p></div></div></div><div class="col-lg-5"><div class="hero-pills justify-content-lg-end"><span class="hero-pill"><i class="bi bi-funnel me-1"></i>Filtro por RUT, raz&oacute;n social o direcci&oacute;n</span><span class="hero-pill"><i class="bi bi-grid-3x3-gap me-1"></i><?php echo $cantidadHerramientas; ?> accesos r&aacute;pidos</span><span class="hero-pill"><i class="bi bi-sliders me-1"></i>Edici&oacute;n y configuraci&oacute;n por empresa</span></div></div></div></div>
        <div class="card"><div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2"><div><div class="fw-semibold"><i class="bi bi-lightning-charge me-2"></i>Herramientas r&aacute;pidas</div><div class="small mt-1">Se mantienen los accesos administrativos del m&oacute;dulo.</div></div><span class="badge rounded-pill text-bg-light text-primary-emphasis">Operaciones administrativas</span></div><div class="card-body"><div class="quick-actions"><?php for($i = 0; $i < $cantidadHerramientas; $i++): ?><button type="button" class="btn btn-outline-primary" onclick="<?php echo h($aBotonEmpHerramienta['ONCLICK'][$i]); ?>"><i class="bi bi-arrow-right-circle me-1"></i><?php echo h($aBotonEmpHerramienta['SETIQUETA'][$i]); ?></button><?php endfor; ?></div></div></div>
        <div class="card"><div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2"><div><div class="fw-semibold"><i class="bi bi-search me-2"></i>B&uacute;squeda de empresas</div><div class="small mt-1">Mantiene filtros, ordenamiento y eliminaci&oacute;n m&uacute;ltiple del listado original.</div></div><button type="button" class="btn btn-danger btn-sm" onclick="chDelEmp();"><i class="bi bi-trash me-1"></i>Eliminar seleccionadas</button></div><div class="card-body"><?php if($stringSearch != ""): ?><div class="filter-summary mb-4"><span class="filter-chip"><i class="bi bi-funnel-fill"></i><?php echo h($searchOptions[$columnSearch]); ?>: <?php echo h($stringSearch); ?></span></div><?php endif; ?><form name="_FSEARCH" method="get" action="<?php echo h($baseListUrl); ?>" class="row g-3 align-items-end"><div class="col-md-4 col-lg-3"><label class="form-label fw-semibold">Campo de b&uacute;squeda</label><select name="_COLUM_SEARCH" class="form-select"><option value="rut_empr" <?php echo ($columnSearch == 'rut_empr' ? 'selected' : ''); ?>>Rut (Sin DV)</option><option value="rs_empr" <?php echo ($columnSearch == 'rs_empr' ? 'selected' : ''); ?>>Raz&oacute;n Social</option><option value="dir_empr" <?php echo ($columnSearch == 'dir_empr' ? 'selected' : ''); ?>>Direcci&oacute;n</option></select></div><div class="col-md-8 col-lg-5"><label class="form-label fw-semibold">Texto a buscar</label><input type="text" name="_STRING_SEARCH" id="searchInput" class="form-control" maxlength="245" value="<?php echo h($stringSearch); ?>" placeholder="Ingrese un criterio de b&uacute;squeda"></div><div class="col-lg-4"><div class="d-flex flex-wrap gap-2 justify-content-lg-end"><button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Buscar</button><a href="<?php echo h($baseListUrl); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Limpiar</a></div></div></form></div></div>
        <div class="card"><div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2"><div><div class="fw-semibold"><i class="bi bi-table me-2"></i>Listado de empresas</div><div class="small mt-1">Ordene por RUT, raz&oacute;n social o direcci&oacute;n y acceda a edici&oacute;n/configuraci&oacute;n por registro.</div></div><span class="badge rounded-pill text-bg-light text-primary-emphasis">Paginaci&oacute;n original preservada</span></div><div class="card-body p-0"><form name="_FDEL" method="post" action="empresa/pro_emp.php"><input type="hidden" name="sAccion" value="E"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th width="20%" <?php echo $sortClasses['rut_empr']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'rut_empr', $nivelByOrder, $columnSearch, $stringSearch)); ?>">RUT <?php echo $sortIcons['rut_empr']; ?></a></th><th width="32%" <?php echo $sortClasses['rs_empr']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'rs_empr', $nivelByOrder, $columnSearch, $stringSearch)); ?>">Raz&oacute;n Social <?php echo $sortIcons['rs_empr']; ?></a></th><th width="28%" <?php echo $sortClasses['dir_empr']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'dir_empr', $nivelByOrder, $columnSearch, $stringSearch)); ?>">Direcci&oacute;n <?php echo $sortIcons['dir_empr']; ?></a></th><th width="16%">Acciones</th><th width="4%" class="text-center"><input type="checkbox" class="form-check-input" name="clientslistSelectAll" id="clientslistSelectAll" value="true" onclick="chDchALL();"></th></tr></thead><tbody><?php if($hayResultados): ?><?php while(!$result->EOF): ?><?php $nCodEmp = trim($result->fields["codi_empr"]); $sRutEmp = trim($result->fields["rut_empr"]) . "-" . trim($result->fields["dv_empr"]); $sRzSclEmp = trim($result->fields["rs_empr"]); $sDirEmp = trim($result->fields["dir_empr"]); $nCodAct = trim($result->fields["cod_act"]); $sGiroEmp = trim($result->fields["giro_emp"]); $sComEmp = trim($result->fields["com_emp"]); $dFecRes = trim($result->fields["fec_resolucion"]); $nResSii = trim($result->fields["num_resolucion"]); $strParamLink = "nCodEmp=" . urlencode($nCodEmp); $strParamLink .= "&sRutEmp=" . urlencode(trim($result->fields["rut_empr"])); $strParamLink .= "&sDvEmp=" . urlencode(trim($result->fields["dv_empr"])); $strParamLink .= "&sRzSclEmp=" . urlencode($sRzSclEmp); $strParamLink .= "&sDirEmp=" . urlencode($sDirEmp); $strParamLink .= "&nCodAct=" . urlencode($nCodAct); $strParamLink .= "&sGiroEmp=" . urlencode($sGiroEmp); $strParamLink .= "&sComEmp=" . urlencode($sComEmp); $strParamLink .= "&dFecRes=" . urlencode($dFecRes); $strParamLink .= "&nResSii=" . urlencode($nResSii); $strParamLink .= "&sAccion=M"; $editUrl = $_LINK_BASE . "empresa/form_emp.php?" . $strParamLink; $configUrl = $_LINK_BASE . "mantencion/list_config.php?nCodEmp=" . urlencode($nCodEmp); ?><tr><td class="fw-semibold"><?php echo h($sRutEmp); ?></td><td><a href="<?php echo h($editUrl); ?>" class="fw-semibold text-decoration-none"><?php echo h($sRzSclEmp); ?></a><div class="company-meta mt-1"><?php if($sGiroEmp != ""): ?><div>Giro: <?php echo h($sGiroEmp); ?></div><?php endif; ?><?php if($sComEmp != ""): ?><div>Comuna: <?php echo h($sComEmp); ?></div><?php endif; ?></div></td><td><div><?php echo h($sDirEmp); ?></div><div class="company-meta mt-1">Resoluci&oacute;n SII: <?php echo h($nResSii != "" ? $nResSii : "-"); ?><?php echo ($dFecRes != "" ? " / " . h($dFecRes) : ""); ?></div></td><td><div class="actions-stack"><a href="<?php echo h($editUrl); ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil-square me-1"></i>Editar</a><a href="<?php echo h($configUrl); ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-sliders me-1"></i>Config</a></div></td><td class="text-center"><input type="checkbox" class="form-check-input" name="del[]" value="<?php echo h($nCodEmp); ?>"></td></tr><?php $result->MoveNext(); ?><?php endwhile; ?><?php else: ?><tr><td colspan="5"><div class="empty-state"><i class="bi bi-inbox"></i><h5 class="mt-3">No hay empresas para mostrar</h5><p class="mb-0">Pruebe con otro criterio de b&uacute;squeda o limpie los filtros para revisar el listado completo.</p></div></td></tr><?php endif; ?></tbody></table></div></form></div><?php if(trim($sPaginaResult) != ""): ?><div class="card-footer bg-white"><div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2"><span class="text-muted small">Paginaci&oacute;n original preservada para mantener la navegaci&oacute;n del m&oacute;dulo.</span><div class="paging"><?php echo $sPaginaResult; ?></div></div></div><?php endif; ?></div>
    </div>
</body>
</html>

