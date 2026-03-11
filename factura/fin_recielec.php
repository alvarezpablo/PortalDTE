<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/tables.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }

$returnHref = "factura/list_recielec.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="shortcut icon" href="/favicon.ico">
	<title>Informacion de DTE - Portal DTE</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<base href="<?php echo h($_LINK_BASE); ?>" />
	<script type="text/javascript" src="javascript/common.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}.page-shell{max-width:820px;margin:0 auto;padding:1rem}.confirm-card{border:0;border-radius:18px;box-shadow:0 18px 40px rgba(15,23,42,.12);overflow:hidden}.confirm-body{padding:2rem 1.5rem;background:#fff}.icon-wrap{width:74px;height:74px;border-radius:22px;background:linear-gradient(135deg,#0d6efd 0%,#20c997 100%);color:#fff;display:flex;align-items:center;justify-content:center;font-size:2rem}#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}</style>
	<script type="text/javascript">function _body_onload(){ try{loff();}catch(e){} } function _body_onunload(){ try{lon();}catch(e){} } var opt_no_frames=false,opt_integrated_mode=false;</script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP">
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
	<div class="page-shell"><div class="confirm-card card mt-4"><div class="confirm-body text-center"><div class="icon-wrap mx-auto mb-3"><i class="bi bi-receipt"></i></div><h1 class="h3 mb-2">Informacion de DTE</h1><p class="text-secondary mb-4">La operacion sobre DTE recibidos fue registrada y puede volver al listado asociado.</p><div class="alert alert-success border-0 shadow-sm mb-4"><strong>Operacion completada.</strong> La pantalla final ahora usa un formato consistente con el resto de la modernizacion.</div><div class="d-flex flex-column flex-sm-row gap-2 justify-content-center"><a href="<?php echo h($returnHref); ?>" class="btn btn-primary"><i class="bi bi-arrow-left-circle me-2"></i>Volver al listado</a><a href="main.php" class="btn btn-outline-secondary"><i class="bi bi-house-door me-2"></i>Ir al inicio</a></div></div></div></div>
	<script type="text/javascript">try{lsetup();}catch(e){}</script>
</body>
</html>
