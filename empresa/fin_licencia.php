<?php
  include("../include/config.php");
  include("../include/ver_aut.php");
  include("../include/ver_aut_adm_super.php");
  include("../include/ver_emp_adm.php");
  include("../include/tables.php");

  function h($value){
    return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
  }

  function rq($key){
    return isset($_GET[$key]) ? trim((string)$_GET[$key]) : "";
  }

  $sRutEmp = rq("sRutEmp");
  $sDvEmp = rq("sDvEmp");
  $returnHref = "empresa/licencia.php";
  $companyLabel = trim($sRutEmp . (($sRutEmp !== "" && $sDvEmp !== "") ? "-" : "") . $sDvEmp);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <link rel="shortcut icon" href="/favicon.ico">
  <title>Licencia - Portal DTE</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <base href="<?php echo h($_LINK_BASE); ?>" />
  <script type="text/javascript" src="javascript/common.js"></script>
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/general.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/custom.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/layout.css">
  <link rel="stylesheet" type="text/nonsense" href="skins/<?php echo h($_SKINS); ?>/css/misc.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
    .page-shell{max-width:960px;margin:0 auto;padding:1rem}
    .page-hero{background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(0,31,63,.18);margin-bottom:1.25rem}
    .hero-icon{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:1.4rem}
    .card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden}
    .card-header{background:#001f3f;color:#fff;padding:.95rem 1rem}.card-header .small{color:rgba(255,255,255,.75)}
    .status-chip{display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .75rem;border-radius:999px;background:#f8fafc;border:1px solid #dbe7f3;font-size:.82rem;color:#334155}
    #loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}
    #loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
  </style>
  <script type="text/javascript">
    function _body_onload(){ try{loff();}catch(e){} try{SetContext('cl_ed');}catch(e){} }
    function _body_onunload(){ try{lon();}catch(e){} }
  </script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
  <a href="#" name="top" id="top"></a>
  <table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>

  <div class="page-shell">
    <div class="page-hero">
      <div class="d-flex align-items-start gap-3">
        <div class="hero-icon"><i class="bi bi-shield-check"></i></div>
        <div>
          <h1 class="h3 mb-2">Licencia cargada</h1>
          <p class="mb-0 opacity-75">Se mantiene la confirmacion final y el retorno conservador a la administracion de la licencia.</p>
        </div>
      </div>
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <div class="d-flex flex-wrap gap-2">
        <div class="status-chip"><i class="bi bi-building"></i><a href="<?php echo h($_LINK_BASE . $returnHref); ?>" class="text-decoration-none">Empresas</a></div>
<?php if($companyLabel !== ""){ ?>
        <div class="status-chip"><i class="bi bi-upc-scan"></i><?php echo h($companyLabel); ?></div>
<?php } ?>
      </div>
      <a href="<?php echo h($_LINK_BASE . $returnHref); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-up-circle me-1"></i>Subir nivel</a>
    </div>

    <div class="card">
      <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
          <div class="fw-semibold"><i class="bi bi-check2-circle me-2"></i>Licencia cargada</div>
          <div class="small mt-1">Pantalla final conservadora luego de la carga de la licencia digital.</div>
        </div>
        <span class="badge rounded-pill text-bg-light text-primary-emphasis">Flujo legacy preservado</span>
      </div>
      <div class="card-body p-4 text-center">
        <div class="display-6 text-success mb-3"><i class="bi bi-check-circle-fill"></i></div>
        <h2 class="h4 mb-2">Carga completada</h2>
        <p class="text-muted mb-4">La licencia fue cargada correctamente<?php echo ($companyLabel !== "" ? ' para la empresa <strong>' . h($companyLabel) . '</strong>' : ''); ?>.</p>
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
          <a href="<?php echo h($_LINK_BASE . $returnHref); ?>" class="btn btn-primary"><i class="bi bi-arrow-left-circle me-1"></i>Volver a la licencia</a>
          <a href="<?php echo h($_LINK_BASE . $returnHref); ?>" class="btn btn-outline-secondary"><i class="bi bi-check2 me-1"></i>Aceptar</a>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    try{ lsetup(); }catch(e){}
  </script>
</body>
</html>