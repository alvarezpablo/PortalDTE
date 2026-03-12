<?php
	include("../include/config.php");
	include("../include/tables.php");

	$sMsgJs = isset($_GET["sMsgJs"]) ? trim((string)$_GET["sMsgJs"]) : "";
	if ($sMsgJs == "") {
		$sMsgJs = isset($_POST["sMsgJs"]) ? trim((string)$_POST["sMsgJs"]) : "";
	}

	if (!function_exists('h')) {
		function h($value) {
			return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
		}
	}

	$hasError = ($sMsgJs != "");
	$returnUrl = $_LINK_BASE . "libros/form_libro.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>OpenB</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<base href="<?php echo h($_LINK_BASE); ?>" />
		<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
		<link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/general.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/custom.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/layout.css">
		<link rel="stylesheet" type="text/nonsense" href="skins/<?php echo h($_SKINS); ?>/css/misc.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
		<style type="text/css">
			body{background:#eef2f7;color:#1f2937;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;}
			.page-shell{max-width:920px;margin:0 auto;padding:1rem;}
			.topbar{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1rem 1.15rem;box-shadow:0 18px 40px rgba(15,23,42,.16);margin-bottom:1rem;}
			.topbar-eyebrow{font-size:.78rem;text-transform:uppercase;letter-spacing:.08em;opacity:.82;margin-bottom:.2rem;}
			.topbar-title{margin:0;font-size:1.35rem;font-weight:700;line-height:1.15;}
			.topbar-meta{font-size:.92rem;opacity:.9;margin-top:.35rem;}
			.topbar-chip{display:inline-flex;align-items:center;padding:.35rem .7rem;border-radius:999px;background:rgba(255,255,255,.16);font-size:.8rem;font-weight:600;}
			.panel{background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:18px;box-shadow:0 16px 36px rgba(15,23,42,.08);overflow:hidden;}
			.panel-body{padding:1.4rem;}
			.success-card{border:1px solid #dbe3ee;border-radius:16px;background:#f8fafc;padding:1.5rem;text-align:center;}
			.success-icon{width:64px;height:64px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:1.7rem;font-weight:700;margin-bottom:1rem;}
			.success-icon-ok{background:#dbeafe;color:#0b5ed7;}
			.success-icon-error{background:#fee2e2;color:#dc2626;}
			.success-title{font-size:1.1rem;font-weight:700;color:#0f172a;margin-bottom:.35rem;}
			.success-text{color:#64748b;margin-bottom:1rem;}
			.panel-note{max-width:720px;margin:0 auto 1rem;text-align:left;background:#fff;border:1px solid #fecaca;border-radius:14px;padding:1rem;}
			.panel-note textarea{width:100%;min-height:220px;border:1px solid #d1d5db;border-radius:10px;padding:.75rem;resize:vertical;}
			.form-actions{display:flex;justify-content:center;gap:.65rem;flex-wrap:wrap;}
			@media (max-width: 991px){.topbar{flex-direction:column;}}
		</style>
		<script type="text/javascript">
		<!--
		function _body_onload(){ loff(); SetContext('cl_ed'); }
		function _body_onunload(){ lon(); }
		//-->
		</script>
	</head>
	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
		<a href="#" name="top" id="top"></a>
		<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>
		<div class="page-shell">
			<div class="topbar">
				<div>
					<div class="topbar-eyebrow">Libros</div>
					<h1 class="topbar-title">Resultado de carga de libro</h1>
					<div class="topbar-meta">Se conserva el flujo original del proceso y el retorno directo a la carga de libros.</div>
				</div>
				<span class="topbar-chip"><?php echo $hasError ? 'Con errores' : 'Procesado'; ?></span>
			</div>
			<div class="panel">
				<div class="panel-body">
					<div class="success-card">
						<div class="success-icon <?php echo $hasError ? 'success-icon-error' : 'success-icon-ok'; ?>"><?php echo $hasError ? '!' : '&#10003;'; ?></div>
						<div class="success-title"><?php echo $hasError ? 'Se produjo un error al procesar el libro' : 'Libro cargado exitosamente'; ?></div>
						<div class="success-text"><?php echo $hasError ? 'Revise el detalle informado por el proceso antes de reintentar la carga.' : 'La carga termino correctamente y puede continuar con una nueva operacion.'; ?></div>
						<?php if ($hasError) { ?>
						<div class="panel-note">
							<label for="sMsgJs" class="form-label fw-semibold">Detalle del proceso</label>
							<textarea id="sMsgJs" readonly><?php echo h($sMsgJs); ?></textarea>
						</div>
						<?php } ?>
						<div class="form-actions">
							<a href="<?php echo h($returnUrl); ?>" class="btn btn-primary">Aceptar</a>
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