<?php
  include("../include/config.php");
  include("../include/ver_aut.php");
  include("../include/ver_aut_adm_super.php");
  include("../include/ver_emp_adm.php");
  include("../include/tables.php");
  include("../include/db_lib.php");

  function h($value){
    return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
  }

  function jsq($value){
    return str_replace(array("\\", "'", "\r", "\n"), array("\\\\", "\\'", "", "\\n"), (string)$value);
  }

  function rq($key){
    return isset($_GET[$key]) ? trim((string)$_GET[$key]) : "";
  }

  function alertExpr($value){
    $value = trim((string)$value);
    if($value === "") return "";
    if(preg_match('/^[_A-Za-z][_A-Za-z0-9]*$/', $value)) return "alert(" . $value . ");";
    return "alert('" . jsq($value) . "');";
  }

  $conn = conn();
  include("../include/rubros.php");

  $nCodEmp = trim(isset($_SESSION["_COD_EMP_USU_SESS"]) ? (string)$_SESSION["_COD_EMP_USU_SESS"] : "");
  $sAccion = rq("sAccion");
  $sMsgJs = rq("sMsgJs");
  $sRutEmp = rq("sRutEmp");
  $sDvEmp = rq("sDvEmp");
  if($sAccion === "") $sAccion = "M";

  if($nCodEmp !== "" && ($sRutEmp === "" || $sDvEmp === "")){
    $sqlEmp = "SELECT rut_empr, dv_empr FROM empresa WHERE codi_empr = '" . str_replace("'","''",$nCodEmp) . "'";
    $resultEmp = rCursor($conn, $sqlEmp);
    if(!$resultEmp->EOF){
      if($sRutEmp === "") $sRutEmp = trim($resultEmp->fields["rut_empr"]);
      if($sDvEmp === "") $sDvEmp = trim($resultEmp->fields["dv_empr"]);
    }
  }

  $sPathCert = "";
  $sClaveCert = "";
  $sPathLicencia = "";
  $sPropiedades = "";

  if($sRutEmp !== ""){
    $sql = "SELECT C.path_certificado, C.clave_certificado, E.path_licencia, E.propiedades ";
    $sql .= "FROM certificado C, empresa E ";
    $sql .= "WHERE C.rut_empresa = E.rut_empr ";
    $sql .= "AND C.rut_empresa = '" . str_replace("'","''",$sRutEmp) . "'";
    $result = rCursor($conn, $sql);

    if(!$result->EOF) {
      $sPathCert = trim($result->fields["path_certificado"]);
      $sClaveCert = trim($result->fields["clave_certificado"]);
      $sPathLicencia = trim($result->fields["path_licencia"]);
      $sPropiedades = trim($result->fields["propiedades"]);
    }
  }

  $alertMsgJs = alertExpr($sMsgJs);
  $companyLabel = trim($sRutEmp . (($sRutEmp !== "" && $sDvEmp !== "") ? "-" : "") . $sDvEmp);
  $currentFileLabel = trim($sPathLicencia);
  $returnHref = "empresa/listempre.php";
  $homeHref = "../main.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <link rel="shortcut icon" href="/favicon.ico">
  <title>Licencia Empresa - Portal DTE</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <base href="<?php echo h($_LINK_BASE); ?>" />
  <script type="text/javascript" src="javascript/common.js"></script>
  <script type="text/javascript" src="javascript/msg.js"></script>
  <script type="text/javascript" src="javascript/funciones.js"></script>
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/general.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/custom.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/layout.css">
  <link rel="stylesheet" type="text/nonsense" href="skins/<?php echo h($_SKINS); ?>/css/misc.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
    .page-shell{max-width:1040px;margin:0 auto;padding:1rem}
    .page-hero{background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(0,31,63,.18);margin-bottom:1.25rem}
    .hero-icon{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:1.4rem}
    .card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden}
    .card-header{background:#001f3f;color:#fff;padding:1rem 1.25rem}.card-header .small{color:rgba(255,255,255,.78)}
    .status-chip{display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .75rem;border-radius:999px;background:#f8fafc;border:1px solid #dbe7f3;font-size:.82rem;color:#334155}
    .note-panel{background:#f8fafc;border:1px solid #dbe7f3;border-radius:14px;padding:1rem}
    .current-file{font-size:.92rem;color:#dc3545;word-break:break-all}
    #loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}
    #loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
  </style>
  <script type="text/javascript">
    function _body_onload(){
      try{loff();}catch(e){}
<?php if($alertMsgJs !== ""){ ?>
      <?php echo $alertMsgJs; ?>
<?php } ?>
      try{SetContext('cl_ed');}catch(e){}
    }
    function _body_onunload(){ try{lon();}catch(e){} }
    function valida(){ return true; }
  </script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
  <form name="_FFORM" enctype="multipart/form-data" action="empresa/pro_licencia.php" method="post" onsubmit="return valida();">
    <input type="hidden" name="nCodEmp" value="<?php echo h($nCodEmp); ?>">
    <input type="hidden" name="sAccion" value="<?php echo h($sAccion); ?>">
    <input type="hidden" name="sClaveCert" value="">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo h($_MAX_FILE_LIC); ?>">
    <input type="hidden" name="start" value="">
    <input type="hidden" name="cmd" value="update">
    <input type="hidden" name="lock" value="false">
    <input type="hidden" name="previous_page" value="cl_ed">

    <a href="#" name="top" id="top"></a>
    <table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>

    <div class="page-shell">
      <div class="page-hero">
        <div class="d-flex align-items-start gap-3">
          <div class="hero-icon"><i class="bi bi-key"></i></div>
          <div>
            <h1 class="h3 mb-2">Licencia digital de empresa</h1>
            <p class="mb-0 opacity-75">Cargue la licencia digital de la empresa desde esta misma pantalla.</p>
          </div>
        </div>
      </div>

      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div class="d-flex flex-wrap gap-2">
          <div class="status-chip"><i class="bi bi-house-door"></i><a href="<?php echo h($_LINK_BASE . $homeHref); ?>" class="text-decoration-none">Home</a></div>
          <div class="status-chip"><i class="bi bi-building"></i><a href="<?php echo h($_LINK_BASE . $returnHref); ?>" class="text-decoration-none">Empresas</a></div>
<?php if($companyLabel !== ""){ ?>
          <div class="status-chip"><i class="bi bi-upc-scan"></i><?php echo h($companyLabel); ?></div>
<?php } ?>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <a href="<?php echo h($_LINK_BASE . $homeHref); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-up-circle me-1"></i>Subir nivel</a>
          <a href="<?php echo h($_LINK_BASE . $returnHref); ?>" class="btn btn-outline-primary"><i class="bi bi-arrow-left-circle me-1"></i>Volver</a>
        </div>
      </div>

      <div class="card">
        <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
          <div>
            <div class="fw-semibold"><i class="bi bi-shield-check me-2"></i>Formulario de licencia</div>
          </div>
          <span class="badge rounded-pill text-bg-light text-primary-emphasis">ISO-8859-1</span>
        </div>
        <div class="card-body p-4">
          <div class="row g-4 align-items-start">
            <div class="col-lg-8">
              <div class="mb-0">
                <label for="sPathLicencia" class="form-label fw-semibold">Licencia OpenB <span class="text-danger">*</span></label>
                <input type="file" name="sPathLicencia" id="sPathLicencia" class="form-control">
                <div class="form-text">Seleccione el archivo de licencia a cargar para la empresa activa.</div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="note-panel h-100">
                <div class="fw-semibold mb-2"><i class="bi bi-info-circle me-1"></i>Estado actual</div>
<?php if($currentFileLabel !== ""){ ?>
                <div class="small text-muted mb-2">Licencia registrada actualmente:</div>
                <div class="current-file"><?php echo h($currentFileLabel); ?></div>
<?php }else{ ?>
                <div class="small text-muted mb-0">No hay una licencia visible en esta consulta legacy para la empresa actual.</div>
<?php } ?>
              </div>
            </div>
          </div>

          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-4 pt-3 border-top">
            <div class="small text-muted"><span class="text-danger fw-semibold">*</span> Campos requeridos.</div>
            <div class="d-flex flex-wrap gap-2">
              <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i>Aceptar</button>
              <a href="<?php echo h($_LINK_BASE . $returnHref); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  <script type="text/javascript">
    try{ lsetup(); }catch(e){}
  </script>
</body>
</html>