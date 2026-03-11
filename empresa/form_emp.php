<?php
  include("../include/config.php");
  include("../include/ver_aut.php");
  include("../include/ver_aut_adm.php");
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

  $nCodEmp = rq("nCodEmp");
  $sRutEmp = rq("sRutEmp");
  $sDvEmp = rq("sDvEmp");
  $sRzSclEmp = rq("sRzSclEmp");
  $sDirEmp = rq("sDirEmp");
  $sAccion = rq("sAccion");
  $sMsgJs = rq("sMsgJs");
  $nCodAct = rq("nCodAct");
  $sGiroEmp = rq("sGiroEmp");
  $sComEmp = rq("sComEmp");
  $dFecRes = rq("dFecRes");
  $nResSii = rq("nResSii");

  $sPathCert = "";
  $sClaveCertDb = "";
  $sPathLicencia = "";
  $sPropiedades = "";
  $nEmiteWeb = "";

  $sql = "SELECT C.path_certificado, C.clave_certificado, E.path_licencia, E.propiedades, E.emite_web as emite_web ";
  $sql .= "FROM certificado C, empresa E ";
  $sql .= "WHERE cast(C.rut_empresa as varchar) = cast(E.rut_empr as varchar) ";
  $sql .= "AND C.rut_empresa = '" . str_replace("'","''",$sRutEmp) . "'";
  $result = rCursor($conn, $sql);

  if(!$result->EOF){
    $sPathCert = trim($result->fields["path_certificado"]);
    $sClaveCertDb = trim($result->fields["clave_certificado"]);
    $sPathLicencia = trim($result->fields["path_licencia"]);
    $sPropiedades = trim($result->fields["propiedades"]);
    $nEmiteWeb = trim($result->fields["emite_web"]);
  }

  $alertMsgJs = alertExpr($sMsgJs);
  $returnHref = "empresa/listempre.php";
  $isEdit = ($sAccion === "M");
  $companyRut = trim($sRutEmp . (($sRutEmp !== "" && $sDvEmp !== "") ? "-" : "") . $sDvEmp);
  $pageTitle = $isEdit ? "Editar empresa" : "Ingresar empresa";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <link rel="shortcut icon" href="/favicon.ico">
  <title>Empresas - Portal DTE</title>
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
  <link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
  <script type="text/javascript" src="javascript/calendar.js"></script>
  <script type="text/javascript" src="javascript/lang/calendar-es.js"></script>
  <script type="text/javascript" src="javascript/calendar-setup.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
    .page-shell{max-width:1180px;margin:0 auto;padding:1rem}
    .page-hero{background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(0,31,63,.18);margin-bottom:1.25rem}
    .hero-icon{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:1.4rem}
    .hero-pills{display:flex;flex-wrap:wrap;gap:.75rem}
    .hero-pill{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:999px;padding:.45rem .85rem;font-size:.82rem}
    .card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden}
    .card-header{background:#001f3f;color:#fff;padding:.95rem 1rem}.card-header .small{color:rgba(255,255,255,.75)}
    .section-note,.upload-note{font-size:.85rem;color:#64748b}.required-dot{color:#dc3545}.helper-note{font-size:.82rem;color:#64748b}
    .form-label{font-weight:600}.form-control,.form-select{border-radius:.8rem;padding:.65rem .8rem}.btn{border-radius:.8rem}
    .status-chip{display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .75rem;border-radius:999px;background:#f8fafc;border:1px solid #dbe7f3;font-size:.82rem;color:#334155}
    #loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}
    #loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
    @media (max-width:767.98px){.page-shell{padding:.75rem}.page-hero{padding:1.1rem}}
  </style>
  <script type="text/javascript">
  function _body_onload(){
    try{loff();}catch(e){}
    <?php if($alertMsgJs !== ""){ echo $alertMsgJs; } ?>
    try{SetContext('cl_ed');}catch(e){}
  }

  function _body_onunload(){
    try{lon();}catch(e){}
  }

  function valida(){
    var F = document._FFORM;
    var rutaux = Trim(F.sRutEmp.value) + "-" + Trim(F.sDvEmp.value);
    if(rut(rutaux,_MSG_RUT) == false){ F.sRutEmp.select(); return false; }
    if(vacio(F.sRzSclEmp.value,_MSG_RAZON_SOCIAL) == false){ F.sRzSclEmp.select(); return false; }
    if(vacio(F.sDirEmp.value,_MSG_DIR) == false){ F.sDirEmp.select(); return false; }
    if(vacio(F.sComEmp.value,_MSG_COMUNA) == false){ F.sComEmp.select(); return false; }
    if(vacio(F.sGiroEmp.value,_MSG_GIRO_EMP) == false){ F.sGiroEmp.select(); return false; }
    if(F.nCodAct.options[F.nCodAct.selectedIndex].value == ""){ alert(_MSG_ACT_EMP); F.nCodAct.focus(); return false; }
    if(F.dFecRes.value == ""){ alert(_MSG_FECRESOL_EMP); F.dFecRes.focus(); return false; }
    if(vacio(F.nResSii.value,_MSG_NUMRESOL_EMP) == false){ F.nResSii.focus(); return false; }
    F.submit();
    return false;
  }
  </script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
  <form name="_FFORM" enctype="multipart/form-data" action="empresa/pro_emp.php" method="post" onsubmit="return valida();">
    <input type="hidden" name="nCodEmp" value="<?php echo h($nCodEmp); ?>">
    <input type="hidden" name="sAccion" value="<?php echo h($sAccion); ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo h($_MAX_FILE_CERT); ?>">
    <input type="hidden" name="sPropiedades" value="<?php echo h($sPropiedades); ?>">
    <input type="hidden" name="start" value="">
    <input type="hidden" name="cmd" value="update">
    <input type="hidden" name="lock" value="false">
    <input type="hidden" name="previous_page" value="cl_ed">
    <a href="#" name="top" id="top"></a>
    <table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>

    <div class="page-shell">
      <div class="page-hero">
        <div class="row g-3 align-items-center">
          <div class="col-lg-7">
            <div class="d-flex align-items-start gap-3">
              <div class="hero-icon"><i class="bi bi-building"></i></div>
              <div>
                <h1 class="h3 mb-2"><?php echo h($pageTitle); ?></h1>
                <p class="mb-0 opacity-75">Mantiene la carga de certificado, licencia, rubro, resolucion SII y el envio al procesador legacy sin alterar contratos.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="hero-pills justify-content-lg-end">
              <span class="hero-pill"><i class="bi bi-shield-lock me-1"></i>Formulario con archivos adjuntos</span>
              <span class="hero-pill"><i class="bi bi-calendar-event me-1"></i>Calendario legacy preservado</span>
              <span class="hero-pill"><i class="bi bi-arrow-left-circle me-1"></i>Retorno a empresas y configuracion</span>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div class="status-chip"><i class="bi bi-buildings"></i><a href="<?php echo h($_LINK_BASE . $returnHref); ?>" class="text-decoration-none">Empresas</a><?php if($companyRut !== ""): ?> <span class="text-muted">/ <?php echo h($companyRut); ?></span><?php endif; ?></div>
        <div class="d-flex gap-2">
          <a href="<?php echo h($_LINK_BASE . $returnHref); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-up-circle me-1"></i>Subir nivel</a>
          <button type="button" class="btn btn-primary" onclick="return valida();"><i class="bi bi-check2-circle me-1"></i>Aceptar</button>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
          <div>
            <div class="fw-semibold"><i class="bi bi-pencil-square me-2"></i>Formulario de Empresa</div>
            <div class="small mt-1">Los nombres de campos y validaciones JavaScript se conservan para el flujo original.</div>
          </div>
          <span class="badge rounded-pill text-bg-light text-primary-emphasis">Campos marcados con <span class="required-dot">*</span> son requeridos</span>
        </div>
        <div class="card-body p-4">
          <div class="alert alert-light border d-flex flex-column flex-lg-row justify-content-between gap-2">
            <div><strong>Nota:</strong> El certificado y la licencia siguen siendo opcionales al editar; carguelos solo si desea reemplazarlos.</div>
            <?php if($sClaveCertDb !== ""): ?><div class="text-muted small">Existe clave de certificado almacenada.</div><?php endif; ?>
          </div>

          <div class="row g-4">
            <div class="col-lg-7">
              <div class="row g-3">
                <div class="col-md-8">
                  <label for="sRutEmp" class="form-label">Rut de Empresa <span class="required-dot">*</span></label>
                  <div class="row g-2">
                    <div class="col-8"><input type="text" class="form-control" name="sRutEmp" id="sRutEmp" value="<?php echo h($sRutEmp); ?>" maxlength="8"></div>
                    <div class="col-4"><input type="text" class="form-control text-uppercase" name="sDvEmp" id="sDvEmp" value="<?php echo h($sDvEmp); ?>" maxlength="1"></div>
                  </div>
                </div>
                <div class="col-12">
                  <label for="sRzSclEmp" class="form-label">Razon Social <span class="required-dot">*</span></label>
                  <input type="text" class="form-control" name="sRzSclEmp" id="sRzSclEmp" value="<?php echo h($sRzSclEmp); ?>" maxlength="100">
                </div>
                <div class="col-12">
                  <label for="sDirEmp" class="form-label">Direccion <span class="required-dot">*</span></label>
                  <input type="text" class="form-control" name="sDirEmp" id="sDirEmp" value="<?php echo h($sDirEmp); ?>" maxlength="60">
                </div>
                <div class="col-md-6">
                  <label for="sComEmp" class="form-label">Comuna <span class="required-dot">*</span></label>
                  <input type="text" class="form-control" name="sComEmp" id="sComEmp" value="<?php echo h($sComEmp); ?>" maxlength="20">
                </div>
                <div class="col-md-6">
                  <label for="sGiroEmp" class="form-label">Giro <span class="required-dot">*</span></label>
                  <input type="text" class="form-control" name="sGiroEmp" id="sGiroEmp" value="<?php echo h($sGiroEmp); ?>" maxlength="80">
                </div>
                <div class="col-md-6">
                  <label for="nCodAct" class="form-label">Actividad Economica <span class="required-dot">*</span></label>
                  <select name="nCodAct" id="nCodAct" class="form-select" style="text-transform:capitalize;">
                    <script type="text/javascript">llenaRubros('<?php echo jsq($nCodAct); ?>');</script>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="nEmiteWeb" class="form-label">Emite DTE WEB <span class="required-dot">*</span></label>
                  <select name="nEmiteWeb" id="nEmiteWeb" class="form-select">
                    <option value=""<?php if($nEmiteWeb !== "1") echo ' selected'; ?>>No Autorizado</option>
                    <option value="1"<?php if($nEmiteWeb === "1") echo ' selected'; ?>>Autorizado</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="f_date_ter" class="form-label">Fecha Resolucion <span class="required-dot">*</span></label>
                  <div class="input-group">
                    <input type="text" class="form-control" name="dFecRes" id="f_date_ter" onfocus="this.blur();" value="<?php echo h($dFecRes); ?>" maxlength="10">
                    <button type="button" class="btn btn-outline-secondary" id="f_trigger_ter" title="Selector de fecha"><i class="bi bi-calendar3"></i></button>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="nResSii" class="form-label">N&ordm; Resolucion <span class="required-dot">*</span></label>
                  <input type="text" class="form-control" name="nResSii" id="nResSii" value="<?php echo h($nResSii); ?>" maxlength="20">
                </div>
              </div>
            </div>

            <div class="col-lg-5">
              <div class="border rounded-4 p-3 bg-light h-100">
                <div class="fw-semibold mb-3"><i class="bi bi-paperclip me-2"></i>Archivos y credenciales</div>
                <div class="mb-3">
                  <label for="sPathCert" class="form-label">Certificado Digital <span class="required-dot">*</span></label>
                  <input type="file" class="form-control" name="sPathCert" id="sPathCert">
                  <?php if($sPathCert !== ""): ?><div class="upload-note mt-2">Actual: <?php echo h($sPathCert); ?></div><?php endif; ?>
                </div>
                <div class="mb-3">
                  <label for="sClaveCert" class="form-label">Clave Certificado <span class="required-dot">*</span></label>
                  <input type="password" class="form-control" name="sClaveCert" id="sClaveCert" value="" maxlength="20">
                  <div class="helper-note mt-2">Ingrese solo si desea cambiarla.</div>
                </div>
                <div class="mb-3">
                  <label for="sPathLicencia" class="form-label">Licencia OpenB <span class="required-dot">*</span></label>
                  <input type="file" class="form-control" name="sPathLicencia" id="sPathLicencia">
                  <?php if($sPathLicencia !== ""): ?><div class="upload-note mt-2">Actual: <?php echo h($sPathLicencia); ?></div><?php endif; ?>
                </div>
                <div class="section-note">Se conservan los mismos nombres de archivo esperados por el procesador y la persistencia de licencia/certificado en la empresa.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
          <div class="text-muted small"><span class="required-dot">*</span> Campos requeridos.</div>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" onclick="return valida();"><i class="bi bi-check2-circle me-1"></i>Aceptar</button>
            <a href="<?php echo h($_LINK_BASE . $returnHref); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
          </div>
        </div>
      </div>
    </div>
  </form>

  <script type="text/javascript">
    Calendar.setup({
      inputField  : "f_date_ter",
      ifFormat    : _FORMAT_FECHA_FORM,
      button      : "f_trigger_ter",
      align       : "Tl",
      singleClick : true
    });
    try{ lsetup(); }catch(e){}
  </script>
</body>
</html>