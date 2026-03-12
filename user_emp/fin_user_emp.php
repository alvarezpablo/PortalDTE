<?php
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");        
  include("../include/tables.php");  
  $op = isset($_GET["op"]) ? trim($_GET["op"]) : "";

  if (!function_exists('h')) {
    function h($value) {
      return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
  }
  
  if($op == "0")
    $url = "/empresa/listempre.php";
  else
    $url = "/usuario/list_user.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
 
 <head>
  <link rel="shortcut icon" href="/favicon.ico">
  <title>OpenB</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <base href="<?php echo $_LINK_BASE; ?>" />
  <script language="javascript" type="text/javascript" src="javascript/common.js"></script>
  
  <link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/custom.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/layout.css">
  <link rel="stylesheet" type="text/nonsense" href="skins/<?php echo $_SKINS; ?>/css/misc.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style type="text/css">
    body{background:#f3f6fb;color:#1f2937;}
    .page-shell{max-width:920px;margin:0 auto;padding:1.25rem;}
    .topbar{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;border-radius:20px;padding:1.15rem 1.35rem;box-shadow:0 18px 40px rgba(15,23,42,.18);margin-bottom:1rem;}
    .topbar-eyebrow{font-size:.78rem;letter-spacing:.08em;text-transform:uppercase;opacity:.82;margin-bottom:.25rem;}
    .topbar-title{font-size:1.45rem;font-weight:700;line-height:1.15;margin:0;}
    .topbar-meta{font-size:.92rem;opacity:.9;margin-top:.35rem;max-width:640px;}
    .topbar-chip{display:inline-flex;align-items:center;padding:.35rem .7rem;border-radius:999px;background:rgba(255,255,255,.16);font-size:.8rem;font-weight:600;}
    .panel{background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:20px;box-shadow:0 18px 40px rgba(15,23,42,.08);overflow:hidden;}
    .panel-body{padding:1.5rem;}
    .success-card{border:1px solid #dbe3ee;border-radius:18px;background:#f8fafc;padding:1.5rem;text-align:center;}
    .success-icon{width:64px;height:64px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;background:#dbeafe;color:#0b5ed7;font-size:1.75rem;font-weight:700;margin-bottom:1rem;}
    .success-title{font-size:1.15rem;font-weight:700;color:#0f172a;margin-bottom:.35rem;}
    .success-text{color:#64748b;margin-bottom:1.25rem;}
    .form-actions{display:flex;flex-wrap:wrap;gap:.65rem;justify-content:center;}
    @media (max-width: 991px){
      .topbar{flex-direction:column;align-items:stretch;}
    }
  </style>

<script type="text/javascript">
<!--

function _body_onload()
{
 loff();
 SetContext('cl_ed');
}

function _body_onunload()
{
 lon();
}

//-->
  </script>
 </head>

 <body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
  <a href="#" name="top" id="top"></a>
  <table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

  <div class="page-shell">
    <div class="topbar">
      <div>
        <div class="topbar-eyebrow">Usuario / Empresa</div>
        <h1 class="topbar-title">Relaci&oacute;n actualizada</h1>
        <div class="topbar-meta">La asignaci&oacute;n entre usuario y empresas fue procesada y el flujo conserva su retorno original.</div>
      </div>
      <div>
        <span class="topbar-chip"><?php echo ($op == "0") ? 'Volver a empresas' : 'Volver a usuarios'; ?></span>
      </div>
    </div>

    <div class="panel">
      <div class="panel-body">
        <div class="success-card">
          <div class="success-icon">✓</div>
          <div class="success-title">Cambios guardados correctamente</div>
          <div class="success-text">Puede volver al listado anterior para seguir administrando relaciones Usuario / Empresa.</div>
          <div class="form-actions">
            <a href="<?php echo h($_LINK_BASE . $url); ?>" class="btn btn-primary">Aceptar</a>
          </div>
        </div>
      </div>
    </div>
  </div>
 </body>

  <script type="text/javascript">
    try {
      lsetup();
    } catch (e) {
    }
  </script>
</html>