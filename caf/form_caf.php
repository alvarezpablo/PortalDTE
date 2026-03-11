<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/ver_emp_adm.php");
include("../include/ver_aut_adm_super.php");
include("../include/tables.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }
function jsq($value){ return str_replace(array("\\", "'", "\r", "\n"), array("\\\\", "\\'", "", "\\n"), (string)$value); }
function alertExpr($value){
	$value = trim((string)$value);
	if($value === "") return "";
	if(preg_match('/^[_A-Za-z][_A-Za-z0-9]*$/', $value)) return "alert(" . $value . ");";
	return "alert('" . jsq($value) . "');";
}

$sMsgJs = isset($_GET["sMsgJs"]) ? trim($_GET["sMsgJs"]) : "";
$alertCall = alertExpr($sMsgJs);
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>Cargar CAF - Portal DTE</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<base href="<?php echo h($_LINK_BASE); ?>" />
		<script type="text/javascript" src="javascript/common.js"></script>
		<script type="text/javascript" src="javascript/msg.js"></script>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
		<style>
			body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
			.page-shell{max-width:980px;margin:0 auto;padding:1rem}.page-hero{background:linear-gradient(135deg,#0f3d6e 0%,#0b5ed7 100%);color:#fff;border-radius:22px;padding:1.75rem;box-shadow:0 18px 44px rgba(15,23,42,.18);margin-bottom:1.5rem}
			.hero-icon{width:68px;height:68px;border-radius:20px;background:rgba(255,255,255,.16);display:flex;align-items:center;justify-content:center;font-size:1.9rem;flex-shrink:0}.hero-pill{display:inline-flex;align-items:center;gap:.45rem;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.18);border-radius:999px;padding:.45rem .8rem;font-size:.85rem}
			.card{border:0;border-radius:20px;box-shadow:0 16px 36px rgba(15,23,42,.08)}.upload-panel{border:2px dashed #c7d2fe;border-radius:18px;background:#f8fafc;padding:2rem 1.5rem;text-align:center}.upload-icon{width:78px;height:78px;border-radius:22px;background:rgba(13,110,253,.12);color:#0d6efd;display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 1rem}
			.info-card{border-radius:18px;background:#f8fbff;border:1px solid #dbeafe;padding:1.25rem}.info-card ul{padding-left:1.1rem;margin-bottom:0}.action-bar{display:flex;gap:.75rem;flex-wrap:wrap}.small-muted{color:#64748b;font-size:.92rem}
			#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
		</style>
		<script type="text/javascript">
		function _body_onload(){
			try{loff();}catch(e){}
			<?php if($alertCall != ""): ?><?php echo $alertCall; ?><?php endif; ?>
			try{SetContext('cl_ed');}catch(e){}
		}
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
							<div class="hero-icon"><i class="bi bi-upload"></i></div>
							<div>
								<h1 class="h3 mb-2">Carga de CAF</h1>
								<p class="mb-0 opacity-75">Se conserva el flujo original de carga hacia <strong>caf/pro_caf.php</strong>, manteniendo el upload, los hooks legacy y la compatibilidad <strong>ISO-8859-1</strong>.</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4 text-lg-end">
						<span class="hero-pill"><i class="bi bi-file-earmark-code"></i>Archivo CAF XML</span>
					</div>
				</div>
			</div>

			<form name="_FFORM" enctype="multipart/form-data" action="caf/pro_caf.php" method="post" class="card">
				<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo h($_MAX_FILE_CAF); ?>">
				<div class="card-body p-4 p-lg-5">
					<div class="row g-4 align-items-stretch">
						<div class="col-lg-7">
							<div class="upload-panel h-100 d-flex flex-column justify-content-center">
								<div class="upload-icon"><i class="bi bi-cloud-arrow-up"></i></div>
								<h2 class="h5 mb-2">Seleccione el archivo CAF</h2>
								<p class="small-muted mb-4">Se mantiene el nombre de campo legacy <strong>sFileCaf</strong> y el l&iacute;mite definido por <strong>MAX_FILE_SIZE</strong>.</p>
								<div class="mx-auto" style="max-width:460px;width:100%">
									<label for="sFileCaf" class="form-label fw-semibold">Archivo CAF</label>
									<input type="file" name="sFileCaf" id="sFileCaf" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-lg-5">
							<div class="info-card h-100">
								<h2 class="h6 mb-3"><i class="bi bi-info-circle me-2"></i>Consideraciones</h2>
								<ul class="small-muted">
									<li>Cargue el archivo CAF descargado desde el SII.</li>
									<li>El procesamiento posterior sigue ejecut&aacute;ndose en el backend actual.</li>
									<li>Ante error, el procesador seguir&aacute; devolviendo mensajes por <code>sMsgJs</code>.</li>
									<li>El bot&oacute;n cancelar mantiene el retorno directo a <code>main.php</code>.</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mt-4 pt-3 border-top">
						<div class="small-muted"><span class="text-danger fw-semibold">*</span> Mantiene el formulario original de carga CAF.</div>
						<div class="action-bar">
							<button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-2"></i>Aceptar</button>
							<a href="main.php" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
						</div>
					</div>
				</div>
			</form>
		</div>
		<script type="text/javascript">try{lsetup();}catch(e){}</script>
	</body>
</html>