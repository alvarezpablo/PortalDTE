<?php
include("../include/config.php");
include("../include/tables.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }

$formHref = "caf/form_caf.php";
$items = array(
	array("title" => "Factura Electr&oacute;nica", "icon" => "bi-receipt"),
	array("title" => "Nota de Cr&eacute;dito Electr&oacute;nica", "icon" => "bi-file-earmark-minus"),
	array("title" => "Nota de D&eacute;bito Electr&oacute;nica", "icon" => "bi-file-earmark-plus")
);
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>Tipos de CAF - Portal DTE</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<base href="<?php echo h($_LINK_BASE); ?>" />
		<script type="text/javascript" src="javascript/common.js"></script>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
		<style>
			body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
			.page-shell{max-width:1080px;margin:0 auto;padding:1rem}.page-hero{background:linear-gradient(135deg,#0f3d6e 0%,#198754 100%);color:#fff;border-radius:22px;padding:1.75rem;box-shadow:0 18px 44px rgba(15,23,42,.18);margin-bottom:1.5rem}
			.hero-icon{width:68px;height:68px;border-radius:20px;background:rgba(255,255,255,.16);display:flex;align-items:center;justify-content:center;font-size:1.9rem;flex-shrink:0}.hero-pill{display:inline-flex;align-items:center;gap:.45rem;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.18);border-radius:999px;padding:.45rem .8rem;font-size:.85rem}
			.selector-card{display:block;text-decoration:none;color:inherit;border:0;border-radius:20px;background:#fff;box-shadow:0 16px 36px rgba(15,23,42,.08);padding:1.4rem;height:100%;transition:transform .18s ease,box-shadow .18s ease}.selector-card:hover{transform:translateY(-2px);box-shadow:0 20px 42px rgba(15,23,42,.12);color:inherit}.selector-icon{width:60px;height:60px;border-radius:18px;background:rgba(25,135,84,.12);color:#198754;display:flex;align-items:center;justify-content:center;font-size:1.6rem;margin-bottom:1rem}
			.helper-card{border:0;border-radius:20px;background:#fff;box-shadow:0 16px 36px rgba(15,23,42,.08)}.small-muted{color:#64748b;font-size:.92rem}
			#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
		</style>
		<script type="text/javascript">
		function _body_onload(){ try{loff();}catch(e){} try{SetContext('cl_ed');}catch(e){} }
		function _body_onunload(){ try{lon();}catch(e){} }
		var opt_no_frames = false, opt_integrated_mode = false;
		</script>
	</head>
	<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
		<a href="#" name="top" id="top"></a>
		<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
		<div class="page-shell">
			<div class="page-hero">
				<div class="row g-3 align-items-center">
					<div class="col-lg-8">
						<div class="d-flex align-items-start gap-3">
							<div class="hero-icon"><i class="bi bi-folder2-open"></i></div>
							<div>
								<h1 class="h3 mb-2">Carga CAF cl&aacute;sica</h1>
								<p class="mb-0 opacity-75">Se conserva la selecci&oacute;n visual del m&oacute;dulo antiguo y cada acceso sigue redirigiendo al formulario legacy <strong>caf/form_caf.php</strong>.</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4 text-lg-end">
						<span class="hero-pill"><i class="bi bi-link-45deg"></i>Flujo legacy preservado</span>
					</div>
				</div>
			</div>

			<div class="row g-4">
				<?php foreach($items as $item): ?>
				<div class="col-md-6 col-xl-4">
					<a href="<?php echo h($formHref); ?>" class="selector-card">
						<div class="selector-icon"><i class="bi <?php echo h($item["icon"]); ?>"></i></div>
						<h2 class="h5 mb-2"><?php echo $item["title"]; ?></h2>
						<p class="small-muted mb-0">Mantiene la entrada al mismo formulario CAF que usa el flujo cl&aacute;sico del men&uacute;.</p>
					</a>
				</div>
				<?php endforeach; ?>
			</div>

			<div class="helper-card card mt-4">
				<div class="card-body p-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
					<div>
						<div class="fw-semibold mb-1"><i class="bi bi-info-circle me-2"></i>Compatibilidad conservadora</div>
						<div class="small-muted">Se mantienen `SetContext('cl_ed')`, `loff()/lon()` y los campos legacy auxiliares para no alterar el comportamiento del m&oacute;dulo.</div>
					</div>
					<a href="main.php" class="btn btn-outline-secondary"><i class="bi bi-house-door me-2"></i>Ir al inicio</a>
				</div>
			</div>

			<form name="_FSTATE" method="post" action="<?php echo h($formHref); ?>" class="d-none">
				<input type="hidden" name="start" value="">
				<input type="hidden" name="cmd" value="update">
				<input type="hidden" name="lock" value="false">
				<input type="hidden" name="previous_page" value="cl_ed">
			</form>
		</div>
		<script type="text/javascript">try{lsetup();}catch(e){}</script>
	</body>
</html>