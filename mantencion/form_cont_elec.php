<?php
  ini_set('post_max_size', '800M');
  ini_set('upload_max_filesize', '800M');
  ini_set('memory_limit', '1024M');
  ini_set('max_execution_time', '36000');
  ini_set('max_input_time', '36000');

  include("../include/config.php");
  include("../include/ver_aut.php");
  include("../include/db_lib.php");
  include("../include/tables.php");

  function h($value){
    return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
  }

  function jsq($value){
    return str_replace(array("\\", "'", "\r", "\n"), array("\\\\", "\\'", "", "\\n"), (string)$value);
  }

  function selectedAttr($value, $current){
    return ((string)$value === (string)$current) ? ' selected="selected"' : "";
  }

  function buildOrderUrl($baseListUrl, $column, $orderDir, $searchCol, $searchStr){
    return $baseListUrl
      . "?_ORDER_BY_COLUM=" . urlencode($column)
      . "&_NIVEL_BY_ORDER=" . urlencode($orderDir)
      . "&_COLUM_SEARCH=" . urlencode($searchCol)
      . "&_STRING_SEARCH=" . urlencode($searchStr)
      . "&_ORDER_CAMBIA=Y";
  }

  function sortImageHtml($path){
    if(trim((string)$path) == "")
      return "";

    return '<img src="' . h($path) . '" alt="" class="ms-1 align-text-bottom">';
  }

  $sMsgJs = isset($_GET["sMsgJs"]) ? trim((string)$_GET["sMsgJs"]) : "";
  $sLinkActual = "mantencion/form_cont_elec.php";
  $_NUM_ROW_LIST = 200;
  $conn = conn();
  $baseListUrl = $_LINK_BASE . $sLinkActual;

  $searchLabels = array(
    "rut_contr" => "Rut",
    "rs_contr" => html_entity_decode("Raz&oacute;n Social", ENT_QUOTES, 'ISO-8859-1'),
    "email_contr" => "Email"
  );

  $searchCol = trim((string)$_COLUM_SEARCH);
  if($searchCol == "" || !isset($searchLabels[$searchCol]))
    $searchCol = "rut_contr";

  $stringSearch = trim((string)$_STRING_SEARCH);
  $orderCol = trim((string)$_ORDER_BY_COLUM);
  $orderDir = trim((string)$_NIVEL_BY_ORDER);

  $_COLUM_SEARCH = $searchCol;
  $_STRING_SEARCH = $stringSearch;
  $_ORDER_BY_COLUM = $orderCol;
  $_NIVEL_BY_ORDER = $orderDir;

  $sql = " SELECT rut_contr, rs_contr, nrores_contr, fecres_contr, email_contr FROM contrib_elec WHERE 1 = 1 ";

  if($stringSearch != "")
    $sql .= " AND UPPER(cast(" . $searchCol . " as varchar)) LIKE UPPER('" . str_replace("'", "''", $stringSearch) . "%') ";

  if($orderCol == "")
    $sql .= " ORDER BY rs_contr ";
  else
    $sql .= " ORDER BY " . $orderCol . " " . $orderDir . " ";

  $sClassRut = "";
  $sClassRs = "";
  $sClassFe = "";
  $sClassEm = "";
  $sImgRut = "";
  $sImgRs = "";
  $sImgFe = "";
  $sImgEm = "";
  $sortImg = sortImageHtml($_IMG_BY_ORDER);

  if($orderCol == "rut_contr"){
    $sClassRut = 'class="table-active"';
    $sImgRut = $sortImg;
  }
  else if($orderCol == "rs_contr"){
    $sClassRs = 'class="table-active"';
    $sImgRs = $sortImg;
  }
  else if($orderCol == "fecres_contr"){
    $sClassFe = 'class="table-active"';
    $sImgFe = $sortImg;
  }
  else if($orderCol == "email_contr"){
    $sClassEm = 'class="table-active"';
    $sImgEm = $sortImg;
  }
  else{
    $sClassRut = 'class="table-active"';
    $sImgRut = $sortImg;
  }

  $result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);
  $sPaginaResult = sPagina($conn, $sql, $sLinkActual, 100);
  $hayResultados = !$result->EOF;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <link rel="shortcut icon" href="/favicon.ico">
  <title>Contribuyentes Electr&oacute;nicos - Portal DTE</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <base href="<?php echo h($_LINK_BASE); ?>" />
  <script type="text/javascript" src="javascript/common.js"></script>
  <script type="text/javascript" src="javascript/msg.js"></script>
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/general.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/custom.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/layout.css">
  <link rel="stylesheet" type="text/nonsense" href="skins/<?php echo h($_SKINS); ?>/css/misc.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
    .page-shell{max-width:1360px;margin:0 auto;padding:1rem}
    .page-hero{background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(0,31,63,.18);margin-bottom:1.25rem}
    .hero-icon{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:1.4rem}
    .hero-pills,.paging{display:flex;flex-wrap:wrap;gap:.65rem}
    .hero-pill{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:999px;padding:.45rem .85rem;font-size:.82rem}
    .card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden;margin-bottom:1rem}
    .card-header{background:#001f3f;color:#fff;padding:.9rem 1rem}
    .card-header .small{color:rgba(255,255,255,.75)}
    .helper-box{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:14px;padding:.9rem 1rem}
    .filter-chip{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .75rem;background:#fff;border:1px solid #dbe7f3;border-radius:999px;font-size:.8rem;color:#334155}
    .table thead th{background:#001f3f;color:#fff;white-space:nowrap;vertical-align:middle}
    .table thead th.table-active{background:#0b5ed7;color:#fff}
    .table tbody td{vertical-align:middle;font-size:.9rem}
    .table tbody tr:hover{background:#f8fbff}
    .sort-link{color:#fff;text-decoration:none}
    .sort-link:hover{color:#dbeafe}
    .empty-state{padding:4rem 1rem;text-align:center;color:#6b7280}
    .empty-state i{font-size:3rem}
    .paging a,.paging span{display:inline-flex;align-items:center;justify-content:center;min-width:2rem;height:2rem;border:1px solid #d0d7e2;border-radius:999px;padding:0 .7rem;background:#fff;color:#0f172a;text-decoration:none;font-size:.85rem}
    .paging a:hover{background:#eff6ff;border-color:#93c5fd}
    #loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}
    #loaderContainerWH{vertical-align:middle;text-align:center}
    #loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
    @media (max-width:767.98px){.page-shell{padding:.75rem}.page-hero{padding:1.1rem}}
  </style>
  <script type="text/javascript">
    function _body_onload(){
      try{ loff(); }catch(e){}
<?php if($sMsgJs != ""){ ?>
      alert('<?php echo jsq($sMsgJs); ?>');
<?php } ?>
      try{ SetContext('cl_ed'); }catch(e){}
    }

    function _body_onunload(){
      try{ lon(); }catch(e){}
    }
  </script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
  <a href="#" name="top" id="top"></a>
  <table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2" /><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>

  <div class="page-shell">
    <div class="page-hero">
      <div class="row g-3 align-items-center">
        <div class="col-lg-8">
          <div class="d-flex align-items-start gap-3">
            <div class="hero-icon"><i class="bi bi-people-fill"></i></div>
            <div>
              <h1 class="h3 mb-2">Carga de Contribuyentes Electr&oacute;nicos</h1>
              <p class="mb-0 opacity-75">Mantiene la ruta antigua, el upload hacia el procesador vigente y la paginaci&oacute;n legacy del listado.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="hero-pills justify-content-lg-end">
            <span class="hero-pill"><i class="bi bi-upload me-1"></i>Carga por archivo</span>
            <span class="hero-pill"><i class="bi bi-funnel me-1"></i>Filtros por Rut, raz&oacute;n social o email</span>
            <span class="hero-pill"><i class="bi bi-table me-1"></i><?php echo h($_NUM_ROW_LIST); ?> filas por p&aacute;gina</span>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
        <div>
          <div class="fw-semibold"><i class="bi bi-cloud-upload me-2"></i>Carga de archivo</div>
          <div class="small mt-1">Se conserva `MAX_FILE_SIZE`, el nombre `sFileClieElec` y el POST a `mantencion/pro_clie_elec_v2.php`.</div>
        </div>
        <a href="<?php echo h($_LINK_BASE . 'main.php'); ?>" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Volver a inicio</a>
      </div>
      <div class="card-body">
        <form name="_FFORM" enctype="multipart/form-data" action="mantencion/pro_clie_elec_v2.php" method="post" class="row g-3 align-items-end">
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo h($_MAX_FILE_CLIE_ELEC); ?>">
          <div class="col-lg-7">
            <label for="sFileClieElec" class="form-label fw-semibold">Archivo <span class="text-danger">*</span></label>
            <input type="file" name="sFileClieElec" id="sFileClieElec" class="form-control" size="25" maxlength="1000">
            <div class="form-text">Formato esperado del proceso actual: archivo de contribuyentes electr&oacute;nicos.</div>
          </div>
          <div class="col-lg-5">
            <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
              <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i>Aceptar</button>
              <a href="<?php echo h($_LINK_BASE . 'main.php'); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
            </div>
            <div class="small text-muted mt-2 text-lg-end"><span class="text-danger">*</span> Campo requerido.</div>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
        <div>
          <div class="fw-semibold"><i class="bi bi-search me-2"></i>B&uacute;squeda y filtros</div>
          <div class="small mt-1">Mantiene `_COLUM_SEARCH`, `_STRING_SEARCH`, `_ORDER_BY_COLUM`, `_NIVEL_BY_ORDER` y `_NUM_PAG_ACT`.</div>
        </div>
        <span class="badge rounded-pill text-bg-light text-primary-emphasis">Ruta antigua preservada</span>
      </div>
      <div class="card-body">
        <?php if($stringSearch != ""){ ?>
        <div class="helper-box mb-4">
          <span class="filter-chip"><i class="bi bi-funnel-fill"></i><?php echo h($searchLabels[$searchCol]); ?>: <?php echo h($stringSearch); ?></span>
        </div>
        <?php } ?>

        <form name="_FSEARCH" method="get" action="<?php echo h($baseListUrl); ?>" class="row g-3 align-items-end">
          <div class="col-md-4 col-lg-3">
            <label for="_COLUM_SEARCH" class="form-label fw-semibold">Campo</label>
            <select name="_COLUM_SEARCH" id="_COLUM_SEARCH" class="form-select">
              <option value="rut_contr"<?php echo selectedAttr('rut_contr', $searchCol); ?>>Rut</option>
              <option value="rs_contr"<?php echo selectedAttr('rs_contr', $searchCol); ?>>Raz&oacute;n Social</option>
              <option value="email_contr"<?php echo selectedAttr('email_contr', $searchCol); ?>>Email</option>
            </select>
          </div>
          <div class="col-md-8 col-lg-5">
            <label for="searchInput" class="form-label fw-semibold">Texto a buscar</label>
            <input type="text" name="_STRING_SEARCH" id="searchInput" class="form-control" value="<?php echo h($stringSearch); ?>" size="20" maxlength="245" placeholder="Ingrese un criterio de b&uacute;squeda">
          </div>
          <div class="col-lg-4">
            <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
              <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Buscar</button>
              <a href="<?php echo h($baseListUrl); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i>Limpiar</a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
        <div>
          <div class="fw-semibold"><i class="bi bi-table me-2"></i>Listado de contribuyentes</div>
          <div class="small mt-1">La consulta SQL, el orden y `sPagina(...)` se mantienen sobre el route antiguo.</div>
        </div>
        <span class="badge rounded-pill text-bg-light text-primary-emphasis">Paginaci&oacute;n legacy</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th width="18%" <?php echo $sClassRut; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'rut_contr', $_NIVEL_BY_ORDER, $searchCol, $stringSearch)); ?>">Rut<?php echo $sImgRut; ?></a></th>
                <th width="36%" <?php echo $sClassRs; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'rs_contr', $_NIVEL_BY_ORDER, $searchCol, $stringSearch)); ?>">Raz&oacute;n Social<?php echo $sImgRs; ?></a></th>
                <th width="18%" <?php echo $sClassFe; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'fecres_contr', $_NIVEL_BY_ORDER, $searchCol, $stringSearch)); ?>">Fecha Creaci&oacute;n<?php echo $sImgFe; ?></a></th>
                <th width="28%" <?php echo $sClassEm; ?>><a class="sort-link" href="<?php echo h(buildOrderUrl($baseListUrl, 'email_contr', $_NIVEL_BY_ORDER, $searchCol, $stringSearch)); ?>">Email<?php echo $sImgEm; ?></a></th>
              </tr>
            </thead>
            <tbody>
<?php if($hayResultados){ ?>
<?php while(!$result->EOF){ ?>
<?php
  $sRut = trim((string)$result->fields["rut_contr"]);
  $sRs = trim((string)$result->fields["rs_contr"]);
  $dFec = trim((string)$result->fields["fecres_contr"]);
  $sEm = trim((string)$result->fields["email_contr"]);
?>
              <tr>
                <td class="fw-semibold"><?php echo h($sRut); ?></td>
                <td><?php echo h($sRs); ?></td>
                <td><?php echo h($dFec); ?></td>
                <td><?php echo h($sEm); ?></td>
              </tr>
<?php $result->MoveNext(); ?>
<?php } ?>
<?php } else { ?>
              <tr>
                <td colspan="4">
                  <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5 class="mt-3">No hay contribuyentes para mostrar</h5>
                    <p class="mb-0">Pruebe con otro criterio de b&uacute;squeda o limpie los filtros para revisar el listado completo.</p>
                  </div>
                </td>
              </tr>
<?php } ?>
            </tbody>
          </table>
        </div>
      </div>
<?php if(trim($sPaginaResult) != ""){ ?>
      <div class="card-footer bg-white">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
          <span class="text-muted small">Se conserva el paginador original generado por `sPagina(...)`.</span>
          <div class="paging"><?php echo $sPaginaResult; ?></div>
        </div>
      </div>
<?php } ?>
    </div>
  </div>

  <script type="text/javascript">
    try{
      lsetup();
    }catch(e){}
  </script>
</body>
</html>