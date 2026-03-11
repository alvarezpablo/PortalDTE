<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/ver_aut_adm.php");
include("../include/db_lib.php");
include("../include/tables.php");

$conn = conn();
$sLinkActual = "usuario/list_user.php";
$baseListUrl = $_LINK_BASE . $sLinkActual;
$searchColumn = "id_usu";
$orderColumns = array("est_usu", "id_usu", "R.desc_rol");
$stringSearch = trim($_STRING_SEARCH);
$orderByColumn = trim($_ORDER_BY_COLUM);
if(!in_array($orderByColumn, $orderColumns)) $orderByColumn = "";
$nivelByOrder = strtoupper(trim($_NIVEL_BY_ORDER));
if($nivelByOrder != "ASC" && $nivelByOrder != "DESC") $nivelByOrder = "";
$_COLUM_SEARCH = $searchColumn; $_STRING_SEARCH = $stringSearch;
$_ORDER_BY_COLUM = $orderByColumn; $_NIVEL_BY_ORDER = $nivelByOrder;
function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function buildOrderUrl($baseListUrl, $column, $nivelByOrder, $stringSearch){
    return $baseListUrl . "?_ORDER_BY_COLUM=" . urlencode($column)
        . "&_NIVEL_BY_ORDER=" . urlencode($nivelByOrder)
        . "&_COLUM_SEARCH=id_usu&_STRING_SEARCH=" . urlencode($stringSearch)
        . "&_ORDER_CAMBIA=Y";
}
$sql = "SELECT cod_usu, id_usu, cert_usu, est_usu, U.cod_rol, R.desc_rol FROM usuario U, rol R WHERE CAST(R.cod_rol as varchar) = U.cod_rol ";
if($stringSearch != "") $sql .= " AND UPPER(" . $searchColumn . ") LIKE UPPER('" . str_replace("'", "''", $stringSearch) . "%') ";
if($orderByColumn == "") $sql .= " ORDER BY id_usu "; else $sql .= " ORDER BY " . $orderByColumn . " " . $nivelByOrder . " ";
$activeSort = ($orderByColumn == "") ? "id_usu" : $orderByColumn;
$sortClasses = array("est_usu" => "", "id_usu" => "", "R.desc_rol" => "");
$sortIcons = array("est_usu" => "", "id_usu" => "", "R.desc_rol" => "");
$sortClasses[$activeSort] = "class=\"table-active\"";
$sortIcons[$activeSort] = "<img src='" . $_IMG_BY_ORDER . "' alt='' class='ms-1'>";
$result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);
$sPaginaResult = sPagina($conn, $sql, $sLinkActual);
$hayResultados = !$result->EOF;
$cantidadHerramientas = isset($aBotonUserHerramienta["ID"]) ? count($aBotonUserHerramienta["ID"]) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="shortcut icon" href="/favicon.ico">
    <title>Usuarios - Portal DTE</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <base href="<?php echo $_LINK_BASE; ?>" />
    <script type="text/javascript" src="javascript/common.js"></script>
    <script type="text/javascript" src="javascript/msg.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}.page-shell{max-width:1400px;margin:0 auto;padding:1rem}.page-hero{background:linear-gradient(135deg,#0f172a 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(15,23,42,.18);margin-bottom:1.25rem}.hero-icon{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:1.4rem}.hero-pills,.quick-actions,.actions-stack,.paging{display:flex;flex-wrap:wrap}.hero-pills{gap:.75rem}.hero-pill{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:999px;padding:.45rem .85rem;font-size:.82rem}.card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden;margin-bottom:1rem}.card-header{background:#0f172a;color:#fff;padding:.9rem 1rem}.card-header .small{color:rgba(255,255,255,.75)}.quick-actions,.actions-stack{gap:.65rem}.filter-summary{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:14px;padding:.9rem 1rem}.filter-chip{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .75rem;background:#fff;border:1px solid #dbe7f3;border-radius:999px;font-size:.8rem;color:#334155}.table thead th{background:#0f172a;color:#fff;white-space:nowrap;vertical-align:middle}.table thead th.table-active{background:#0b5ed7;color:#fff}.table tbody td{vertical-align:middle;font-size:.9rem}.table tbody tr:hover{background:#f8fbff}.sort-link{color:#fff;text-decoration:none}.sort-link:hover{color:#dbeafe}.status-pill{display:inline-flex;align-items:center;gap:.45rem;padding:.35rem .75rem;border-radius:999px;font-size:.8rem;font-weight:600}.status-pill img{width:14px;height:14px}.status-pill.active{background:#dcfce7;color:#166534}.status-pill.inactive{background:#fee2e2;color:#991b1b}.user-meta{color:#64748b;font-size:.78rem}.empty-state{padding:4rem 1rem;text-align:center;color:#6b7280}.empty-state i{font-size:3rem}.paging{gap:.45rem;align-items:center}.paging a,.paging span{display:inline-flex;align-items:center;justify-content:center;min-width:2rem;height:2rem;border:1px solid #d0d7e2;border-radius:999px;padding:0 .7rem;background:#fff;color:#0f172a;text-decoration:none;font-size:.85rem}.paging a:hover{background:#eff6ff;border-color:#93c5fd}#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}@media (max-width:767.98px){.page-shell{padding:.75rem}.page-hero{padding:1.1rem}}
    </style>
    <script type="text/javascript">
        function _body_onload(){ try{SetContext('clients');setActiveButtonByName('clients');}catch(e){} try{loff();}catch(e){} }
        function _body_onunload(){ try{lon();}catch(e){} }
        var opt_no_frames = false, opt_integrated_mode = false;
        function chSelDelEmp(){ var F = document._FDEL; for(var i=0;i<F.elements.length;i++){ if(F.elements[i].name=='del[]' && F.elements[i].checked==true) return true; } return false; }
        function chDelEmp(){ if(chSelDelEmp()){ if(confirm(_MSG_DEL_USER)) document._FDEL.submit(); } else alert(_MSG_SEL_USER_DEL); }
        function chDchALL(){ var F = document._FDEL; var obj = document.getElementById('clientslistSelectAll'); for(var i=0;i<F.elements.length;i++){ if(F.elements[i].name=='del[]') F.elements[i].checked = obj.checked; } }
    </script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP">
    <a href="#" name="top" id="top"></a>
    <table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2" /><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
    <div class="page-shell">
        <div class="page-hero"><div class="row g-3 align-items-center"><div class="col-lg-7"><div class="d-flex align-items-start gap-3"><div class="hero-icon"><i class="bi bi-people"></i></div><div><h1 class="h3 mb-2">Administración de Usuarios</h1><p class="mb-0 opacity-75">Gestione usuarios del portal, revise su estado y acceda rápidamente a su edición sin alterar la operatoria actual.</p></div></div></div><div class="col-lg-5"><div class="hero-pills justify-content-lg-end"><span class="hero-pill"><i class="bi bi-search me-1"></i>Búsqueda por ID de usuario</span><span class="hero-pill"><i class="bi bi-grid-3x3-gap me-1"></i><?php echo $cantidadHerramientas; ?> accesos rápidos</span><span class="hero-pill"><i class="bi bi-person-gear me-1"></i>Edición y asociaciones</span></div></div></div></div>
        <div class="card"><div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2"><div><div class="fw-semibold"><i class="bi bi-lightning-charge me-2"></i>Herramientas rápidas</div><div class="small mt-1">Se mantienen los accesos administrativos del módulo de usuarios.</div></div><span class="badge rounded-pill text-bg-light text-primary-emphasis">Operaciones administrativas</span></div><div class="card-body"><div class="quick-actions"><?php for($i = 0; $i < $cantidadHerramientas; $i++): ?><button type="button" class="btn btn-outline-primary" onclick="<?php echo h($aBotonUserHerramienta['ONCLICK'][$i]); ?>"><i class="bi bi-arrow-right-circle me-1"></i><?php echo h($aBotonUserHerramienta['SETIQUETA'][$i]); ?></button><?php endfor; ?></div></div></div>
        <div class="card"><div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2"><div><div class="fw-semibold"><i class="bi bi-search me-2"></i>Búsqueda de usuarios</div><div class="small mt-1">Mantiene búsqueda por identificador y eliminación múltiple.</div></div><button type="button" class="btn btn-danger btn-sm" onclick="chDelEmp();"><i class="bi bi-trash me-1"></i>Eliminar seleccionados</button></div><div class="card-body"><?php if($stringSearch != ""): ?><div class="filter-summary mb-4"><span class="filter-chip"><i class="bi bi-funnel-fill"></i>Usuario: <?php echo h($stringSearch); ?></span></div><?php endif; ?><form name="_FSEARCH" method="get" action="<?php echo h($baseListUrl); ?>" class="row g-3 align-items-end"><input type="hidden" name="_COLUM_SEARCH" value="id_usu"><div class="col-md-8 col-lg-6"><label class="form-label fw-semibold">ID de usuario</label><input type="text" name="_STRING_SEARCH" id="searchInput" class="form-control" maxlength="245" value="<?php echo h($stringSearch); ?>" placeholder="Ingrese el usuario a buscar"></div><div class="col-lg-6"><div class="d-flex flex-wrap gap-2 justify-content-lg-end"><button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Buscar</button><a href="<?php echo h($baseListUrl); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Limpiar</a></div></div></form></div></div>
        <div class="card"><div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2"><div><div class="fw-semibold"><i class="bi bi-table me-2"></i>Listado de usuarios</div><div class="small mt-1">Ordene por estado, usuario o rol y acceda a la edición individual.</div></div><span class="badge rounded-pill text-bg-light text-primary-emphasis">Paginación original preservada</span></div><div class="card-body p-0"><form name="_FDEL" method="post" action="usuario/pro_usu.php"><input type="hidden" name="sAccion" value="E"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th width="18%" <?php echo $sortClasses['est_usu']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'est_usu', $nivelByOrder, $stringSearch)); ?>">Estado <?php echo $sortIcons['est_usu']; ?></a></th><th width="38%" <?php echo $sortClasses['id_usu']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'id_usu', $nivelByOrder, $stringSearch)); ?>">Usuario <?php echo $sortIcons['id_usu']; ?></a></th><th width="26%" <?php echo $sortClasses['R.desc_rol']; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'R.desc_rol', $nivelByOrder, $stringSearch)); ?>">Rol <?php echo $sortIcons['R.desc_rol']; ?></a></th><th width="14%">Acciones</th><th width="4%" class="text-center"><input type="checkbox" class="form-check-input" name="clientslistSelectAll" id="clientslistSelectAll" value="true" onclick="chDchALL();"></th></tr></thead><tbody><?php if($hayResultados): ?><?php while(!$result->EOF): ?><?php $nCodUsu = trim($result->fields['cod_usu']); $sIdUsu = trim($result->fields['id_usu']); $sPathCert = trim($result->fields['cert_usu']); $sEstUsu = trim($result->fields['est_usu']); $sCodRolUsu = trim($result->fields['cod_rol']); $sDescRol = trim($result->fields['desc_rol']); $strParamLink = 'nCodUsu=' . urlencode($nCodUsu); $strParamLink .= '&sIdUsu=' . urlencode($sIdUsu); $strParamLink .= '&sIdUsuNew=' . urlencode($sIdUsu); $strParamLink .= '&sPathCert=' . urlencode($sPathCert); $strParamLink .= '&sEstUsu=' . urlencode($sEstUsu); $strParamLink .= '&sCodRolUsu=' . urlencode($sCodRolUsu); $strParamLink .= '&sDescRol=' . urlencode($sDescRol); $strParamLink .= '&sAccion=M'; $editUrl = $_LINK_BASE . 'usuario/form_user.php?' . $strParamLink; $sImgEstado = ($sEstUsu == '1') ? 'skins/' . $_SKINS . '/icons/on.gif' : 'skins/' . $_SKINS . '/icons/off.gif'; $sEstadoTexto = ($sEstUsu == '1') ? 'Activo' : 'Inactivo'; $sEstadoClase = ($sEstUsu == '1') ? 'active' : 'inactive'; ?><tr><td><span class="status-pill <?php echo $sEstadoClase; ?>"><img src="<?php echo h($sImgEstado); ?>" alt="" /><?php echo h($sEstadoTexto); ?></span></td><td><a href="<?php echo h($editUrl); ?>" class="fw-semibold text-decoration-none"><?php echo h($sIdUsu); ?></a><div class="user-meta mt-1"><?php echo ($sPathCert != '' ? 'Certificado configurado' : 'Sin certificado configurado'); ?></div></td><td><span class="badge text-bg-light border"><?php echo h($sDescRol); ?></span></td><td><div class="actions-stack"><a href="<?php echo h($editUrl); ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil-square me-1"></i>Editar</a></div></td><td class="text-center"><input type="checkbox" class="form-check-input" name="del[]" value="<?php echo h($nCodUsu); ?>"></td></tr><?php $result->MoveNext(); ?><?php endwhile; ?><?php else: ?><tr><td colspan="5"><div class="empty-state"><i class="bi bi-person-x"></i><h5 class="mt-3">No hay usuarios para mostrar</h5><p class="mb-0">Pruebe con otro criterio de búsqueda o limpie el filtro para revisar el listado completo.</p></div></td></tr><?php endif; ?></tbody></table></div></form></div><?php if(trim($sPaginaResult) != ""): ?><div class="card-footer bg-white"><div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2"><span class="text-muted small">Paginación original preservada para mantener la navegación del módulo.</span><div class="paging"><?php echo $sPaginaResult; ?></div></div></div><?php endif; ?></div>
    </div>
</body>
</html>

