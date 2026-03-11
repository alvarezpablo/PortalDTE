<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/ver_aut_adm.php");
include("../include/tables.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }

$returnHref = "dte/list_dte_v3.php";
$msj = isset($_GET["msj"]) ? trim((string)$_GET["msj"]) : "";
$messageLines = array();

if($msj !== ""){
	$normalized = str_ireplace(array("<br />", "<br/>", "<br>"), "\n", $msj);
	$normalized = strip_tags($normalized);
	$normalized = str_replace("\r", "", $normalized);
	$aParts = explode("\n", $normalized);
	foreach($aParts as $line){
		$line = trim($line);
		if($line !== ""){
			$messageLines[] = $line;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="shortcut icon" href="/favicon.ico">
	<title>DTE - Portal DTE</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<base href="<?php echo h($_LINK_BASE); ?>" />
	<script type="text/javascript" src="javascript/common.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
		.page-shell{max-width:860px;margin:0 auto;padding:1rem}.confirm-card{border:0;border-radius:18px;box-shadow:0 18px 40px rgba(15,23,42,.12);overflow:hidden}
		.confirm-body{padding:2rem 1.5rem;background:#fff}.icon-wrap{width:74px;height:74px;border-radius:22px;background:linear-gradient(135deg,#0d6efd 0%,#6f42c1 100%);color:#fff;display:flex;align-items:center;justify-content:center;font-size:2rem}
		#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
	</style>
	<script type="text/javascript">function _body_onload(){ try{loff();}catch(e){} } function _body_onunload(){ try{lon();}catch(e){} } var opt_no_frames=false,opt_integrated_mode=false;</script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP">
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
	<div class="page-shell">
		<div class="confirm-card card mt-4">
			<div class="confirm-body text-center">
				<div class="icon-wrap mx-auto mb-3"><i class="bi bi-file-earmark-check"></i></div>
				<h1 class="h3 mb-2">DTE</h1>
					<p class="text-secondary mb-4">La operacion finalizo y la pantalla de termino retorna al listado activo del modulo.</p>
				<?php if(sizeof($messageLines) > 0){ ?>
				<div class="alert alert-warning text-start shadow-sm border-0 mx-auto" style="max-width:640px;">
					<strong>Detalle del proceso:</strong>
					<ul class="mb-0 mt-2">
						<?php foreach($messageLines as $line){ ?>
						<li><?php echo h($line); ?></li>
						<?php } ?>
					</ul>
				</div>
				<?php } else { ?>
				<div class="alert alert-success border-0 shadow-sm mb-4"><strong>Operacion completada.</strong> No se recibieron mensajes adicionales del proceso.</div>
				<?php } ?>
				<div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
					<a href="<?php echo h($returnHref); ?>" class="btn btn-primary"><i class="bi bi-arrow-left-circle me-2"></i>Volver a DTE</a>
					<a href="main.php" class="btn btn-outline-secondary"><i class="bi bi-house-door me-2"></i>Ir al inicio</a>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">try{lsetup();}catch(e){}</script>
</body>
</html>
