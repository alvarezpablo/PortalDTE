<?php include("../include/config.php"); ?>
<?php include("../include/tables.php"); ?>
<?php function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); } ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="shortcut icon" href="/favicon.ico">
	<title>DTE enviados SII - Portal DTE</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<base href="<?php echo h($_LINK_BASE); ?>" />
	<script type="text/javascript" src="javascript/common.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}.page-shell{max-width:1450px;margin:0 auto;padding:1rem}.page-hero{background:linear-gradient(135deg,#0f172a 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(15,23,42,.18);margin-bottom:1.25rem}.hero-pill{display:inline-flex;align-items:center;gap:.35rem;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:999px;padding:.45rem .85rem;font-size:.82rem}.card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden}.card-header{background:#0f172a;color:#fff}.toolbar{display:flex;gap:.75rem;flex-wrap:wrap;align-items:center}.toolbar .form-control{min-width:240px}.table thead th{background:#0f172a;color:#fff;white-space:nowrap}.table tbody td{vertical-align:middle}.table tbody tr:hover{background:#f8fbff}.status-icon a{display:inline-flex;align-items:center;justify-content:center;width:2.25rem;height:2.25rem;border-radius:.7rem;border:1px solid #dbe7f3;background:#fff}.status-icon a:hover{background:#eff6ff;border-color:#93c5fd}.paging{display:flex;flex-wrap:wrap;gap:.45rem}.paging span{display:inline-flex;align-items:center;justify-content:center;min-width:2rem;height:2rem;border:1px solid #d0d7e2;border-radius:999px;padding:0 .7rem;background:#fff;color:#0f172a;font-size:.85rem}.paging span.active{background:#0b5ed7;border-color:#0b5ed7;color:#fff}#loaderContainer{position:fixed;top:0;right:0;bottom:0;left:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{text-align:center;vertical-align:middle}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
	</style>
	<script type="text/javascript">
		function _body_onload(){ try{SetContext('clients');setActiveButtonByName('clients');}catch(e){} try{loff();}catch(e){} }
		function _body_onunload(){ try{lon();}catch(e){} }
		function open_factura(){ try{ window.open('<?php echo h($_LINK_BASE); ?>dte/dte-33-68.pdf','DTE','toolbar=no,width=500,height=400,innerHeight=400,innerWidth=500,scrollbars=yes,resizable=yes'); }catch(e){ return false; } }
		function open_error(){ try{ window.open('<?php echo h($_LINK_BASE); ?>dte/error.php','DTE','toolbar=no,width=250,height=250,innerHeight=400,innerWidth=500,scrollbars=yes,resizable=yes'); }catch(e){ return false; } }
		var opt_no_frames = false, opt_integrated_mode = false;
		try{setActiveButtonByName("clients");}catch(e){}
	</script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2" /><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
	<div class="page-shell">
		<div class="page-hero d-flex flex-wrap justify-content-between gap-3 align-items-center">
			<div><h1 class="h3 mb-2">DTE enviados al SII</h1><p class="mb-0 opacity-75">Pantalla demo legacy remaquetada con Bootstrap 5 manteniendo los popups de detalle y error existentes.</p></div>
			<div class="hero-pill"><i class="bi bi-send-check"></i>2 registros de ejemplo</div>
		</div>
		<div class="card">
			<div class="card-header d-flex flex-wrap justify-content-between gap-2 align-items-center"><div><div class="fw-semibold">Listado demo</div><div class="small">Se conservan <code>open_factura()</code> y <code>open_error()</code> con el mismo comportamiento popup.</div></div><div class="toolbar"><input type="text" name="filter" id="searchInput" value="" size="20" maxlength="245" class="form-control form-control-sm" placeholder="Buscar"><button type="button" class="btn btn-light btn-sm"><i class="bi bi-search me-1"></i>Buscar</button><button type="button" class="btn btn-outline-light btn-sm">Mostrar todo</button><button type="button" class="btn btn-outline-light btn-sm"><i class="bi bi-trash me-1"></i>Eliminar selecci&oacute;n</button></div></div>
			<div class="card-body">
				<div class="table-responsive"><table class="table table-sm table-hover align-middle mb-3"><thead><tr><th>N&ordm; DTE</th><th>Folio</th><th>Fecha</th><th>Rut</th><th>Raz&oacute;n Social</th><th>Giro</th><th>Direcci&oacute;n</th><th>Comuna</th><th>Ciudad</th><th>Estado</th></tr></thead><tbody><tr><td>000001</td><td>000125</td><td>01-01-2005</td><td>11.111.111-1</td><td>OpenB S.A</td><td>Ingenieria de Software</td><td>Av 11 de Septiembre 1881</td><td>Providencia</td><td>Santiago</td><td class="text-center status-icon"><a href="javascript:open_factura();" title="Aceptado"><i class="bi bi-check-circle-fill text-success"></i></a></td></tr><tr><td>000002</td><td>000126</td><td>01-01-2005</td><td>11.111.111-1</td><td>OpenB S.A</td><td>Ingenieria de Software</td><td>Av 11 de Septiembre 1881</td><td>Providencia</td><td>Santiago</td><td class="text-center status-icon"><a href="javascript:open_error();" title="Rechazado"><i class="bi bi-x-circle-fill text-danger"></i></a></td></tr></tbody></table></div>
				<div class="paging"><span class="active">1</span><span>2</span><span>3</span><span>4</span><span>5</span><span>6</span><span>7</span><span>8</span><span>9</span><span>10</span></div>
			</div>
		</div>
	</div>
</body>
</html>