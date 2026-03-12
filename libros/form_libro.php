<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/ver_emp_adm.php");
include("../include/tables.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }
function jsq($value){ return str_replace(array("\\", "'", "\r", "\n"), array("\\\\", "\\'", "", "\\n"), (string)$value); }
function alertExpr($value){
	$value = trim((string)$value);
	if($value === "") return "";
	if(preg_match('/^[_A-Za-z][_A-Za-z0-9]*$/', $value)) return "alert(" . $value . ");";
	return "alert('" . jsq($value) . "');";
}

$sMsgJs = isset($_GET["sMsgJs"]) ? trim((string)$_GET["sMsgJs"]) : "";
$alertCall = alertExpr($sMsgJs);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="shortcut icon" href="/favicon.ico">
	<title>Carga de Libro - Portal DTE</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<base href="<?php echo h($_LINK_BASE); ?>" />
	<script type="text/javascript" src="javascript/common.js"></script>
	<script type="text/javascript" src="javascript/msg.js"></script>
	<link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/general.css">
	<link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/custom.css">
	<link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/layout.css">
	<link rel="stylesheet" type="text/nonsense" href="skins/<?php echo h($_SKINS); ?>/css/misc.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
		.page-shell{max-width:920px;margin:0 auto;padding:1rem}
		.form-card{border:0;border-radius:18px;box-shadow:0 18px 40px rgba(15,23,42,.12);overflow:hidden}
		.form-card .card-header{background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;padding:1.1rem 1.35rem}
		.section-note{background:#f8fafc;border:1px solid #dbe5f1;border-radius:14px;padding:1rem}
		.upload-panel{border:1px dashed #bfd0e5;border-radius:16px;background:#f8fbff;padding:1.25rem}
		#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}
		#loaderContainerWH{vertical-align:middle;text-align:center}
		#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
	</style>
	<script type="text/javascript">
	function _body_onload(){ try{ loff(); }catch(e){} <?php if($alertCall !== ""){ echo $alertCall; } ?> try{ SetContext('cl_ed'); }catch(e){} }
	function _body_onunload(){ try{ lon(); }catch(e){} }
	</script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	<form name="_FFORM" enctype="multipart/form-data" action="libros/pro_libro.php" method="post">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo h($_MAX_FILE_LIBRO); ?>">
		<a href="#" name="top" id="top"></a>
		<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
		<div class="page-shell">
			<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
				<div>
					<div class="small text-secondary">Libros &gt; Carga de Libro</div>
					<h1 class="h3 mb-0">Carga de Libro Electronico</h1>
				</div>
				<a href="main.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Volver</a>
			</div>
			<div class="card form-card">
				<div class="card-header"><i class="bi bi-journal-arrow-up me-2"></i>Formulario de libro</div>
				<div class="card-body p-4">
					<div class="section-note mb-4 small text-secondary">Se conserva el envio <code>multipart/form-data</code>, el campo <code>sFileCaf</code> y el flujo original hacia <strong>libros/pro_libro.php</strong>.</div>
					<div class="upload-panel">
						<label for="sFileCaf" class="form-label fw-semibold">Archivo Libro <span class="text-danger">*</span></label>
						<input type="file" class="form-control" name="sFileCaf" id="sFileCaf" size="25" maxlength="1000">
						<div class="form-text">Seleccione el archivo de libro compatible con el proceso actual del modulo.</div>
					</div>
				</div>
				<div class="card-footer bg-white border-0 px-4 pb-4 pt-0">
					<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
						<div class="small text-secondary"><span class="text-danger">*</span> Campos requeridos.</div>
						<div class="d-flex gap-2">
							<button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-2"></i>Aceptar</button>
							<a href="main.php" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<script type="text/javascript">try{ lsetup(); }catch(e){}</script>
</body>
</html>
