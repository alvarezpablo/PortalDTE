<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/ver_aut_adm.php");
include("../include/db_lib.php");
include("../include/tables.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }
function jsq($value){ return str_replace(array("\\", "'", "\r", "\n"), array("\\\\", "\\'", "", "\\n"), (string)$value); }
function rq($key){ return isset($_GET[$key]) ? trim((string)$_GET[$key]) : ""; }
function alertExpr($value){
	$value = trim((string)$value);
	if($value === "") return "";
	if(preg_match('/^[_A-Za-z][_A-Za-z0-9]*$/', $value)) return "alert(" . $value . ");";
	return "alert('" . jsq($value) . "');";
}

$nCodUsu = rq("nCodUsu");
$sIdUsu = rq("sIdUsu");
$sIdUsuNew = rq("sIdUsuNew");
$sPathCert = rq("sPathCert");
$sEstUsu = rq("sEstUsu");
$sCodRolUsu = rq("sCodRolUsu");
$sDescRol = rq("sDescRol");
$sMsgJs = rq("sMsgJs");
$sAccion = rq("sAccion");
$sMsgJs2 = rq("sMsgJs2");
$conn = conn();

$alertMsgJs = alertExpr($sMsgJs);
$alertMsgJs2 = (trim($sMsgJs2) !== "") ? "alert('" . jsq($sMsgJs2) . "');" : "";
$returnHref = "usuario/list_user.php";
$sEstUsu = ($sEstUsu === "0") ? "0" : "1";
$isEdit = ($sAccion === "M");
$pageTitle = $isEdit ? "Editar usuario" : "Ingresar usuario";
$currentLabel = trim($sIdUsu != "" ? $sIdUsu : $sIdUsuNew);
$passwordHelp = $isEdit ? "Ingrese solo si desea cambiarla." : "Campo requerido para usuarios nuevos.";

$roleOptions = array();
$sql = "SELECT cod_rol, desc_rol FROM rol ORDER BY desc_rol";
$result = rCursor($conn, $sql);
while(!$result->EOF) {
	$roleOptions[] = array(
		"code" => trim($result->fields["cod_rol"]),
		"name" => trim($result->fields["desc_rol"])
	);
	$result->MoveNext();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="shortcut icon" href="/favicon.ico">
	<title>Usuarios - Portal DTE</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<base href="<?php echo h($_LINK_BASE); ?>" />
	<script type="text/javascript" src="javascript/common.js"></script>
	<script type="text/javascript" src="javascript/msg.js"></script>
	<script type="text/javascript" src="javascript/funciones.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}.page-shell{max-width:1080px;margin:0 auto;padding:1rem}.hero-card,.form-card,.section-card{border:0;border-radius:18px;box-shadow:0 18px 40px rgba(15,23,42,.12)}
		.hero-card{background:linear-gradient(135deg,#0f172a 0%,#0b5ed7 100%);color:#fff;padding:1.5rem;margin-bottom:1rem}.form-card{overflow:hidden}.form-card .card-header{background:#0f172a;color:#fff;padding:1rem 1.25rem}
		.section-card{background:#f8fafc;border:1px solid #dbe5f1;padding:1rem}.section-title{font-size:.95rem;font-weight:700;color:#0f172a;margin-bottom:.85rem}.helper-note{font-size:.85rem;color:#64748b}
		#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
		@media (max-width:767.98px){.page-shell{padding:.75rem}}
	</style>
	<script type="text/javascript">
	function _body_onload(){
		try{loff();}catch(e){}
		<?php if($alertMsgJs !== ""){ echo $alertMsgJs; } ?>
		<?php if($alertMsgJs2 !== ""){ echo $alertMsgJs2; } ?>
		try{SetContext('cl_ed');}catch(e){}
	}
	function _body_onunload(){ try{lon();}catch(e){} }
	function valida(){
		var F = document._FFORM;
		if(vacio(F.sIdUsuNew.value,_MSG_USU) == false){ F.sIdUsuNew.select(); return false; }
		if(F.sAccion.value == "I"){
			if(vacio(F.sClaveUsu.value,_MSG_CLAVE_USU) == false){ F.sClaveUsu.select(); return false; }
		}
		return true;
	}
	</script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	<form name="_FFORM" enctype="multipart/form-data" action="usuario/pro_usu.php" method="post" onsubmit="return valida();">
		<input type="hidden" name="nCodUsu" value="<?php echo h($nCodUsu); ?>">
		<input type="hidden" name="sIdUsu" value="<?php echo h($sIdUsu); ?>">
		<input type="hidden" name="sAccion" value="<?php echo h($sAccion); ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo h($_MAX_FILE_CERT); ?>">
		<input type="hidden" name="sPathCert" value="<?php echo h($sPathCert); ?>">
		<input type="hidden" name="sClaveCert" value="">
		<input type="hidden" name="start" value="">
		<a href="#" name="top" id="top"></a>
		<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
		<div class="page-shell">
			<div class="hero-card">
				<div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
					<div>
						<div class="small opacity-75 mb-1">Administracion &gt; Usuarios</div>
						<h1 class="h3 mb-2"><?php echo h($pageTitle); ?></h1>
						<p class="mb-0 opacity-75">Se conserva el alta y la edicion de usuarios, junto con el rol, estado y mensajes legacy del procesador.</p>
					</div>
					<div class="d-flex flex-wrap gap-2 align-items-center">
						<?php if($currentLabel != ""): ?><span class="badge text-bg-light text-dark border px-3 py-2">Usuario actual: <?php echo h($currentLabel); ?></span><?php endif; ?>
						<a href="<?php echo h($returnHref); ?>" class="btn btn-outline-light"><i class="bi bi-arrow-left-circle me-2"></i>Volver al listado</a>
					</div>
				</div>
			</div>
			<div class="card form-card">
				<div class="card-header"><i class="bi bi-person-gear me-2"></i>Formulario de Usuarios</div>
				<div class="card-body p-4">
					<div class="row g-4">
						<div class="col-lg-7">
							<div class="section-card h-100">
								<div class="section-title">Datos de acceso</div>
								<div class="row g-3">
									<div class="col-12">
										<label for="sIdUsuNew" class="form-label">Identificacion de Usuario *</label>
										<input type="text" class="form-control" name="sIdUsuNew" id="sIdUsuNew" value="<?php echo h($sIdUsuNew); ?>" maxlength="100">
									</div>
									<div class="col-12">
										<label for="sClaveUsu" class="form-label">Clave <?php echo $isEdit ? '' : '*'; ?></label>
										<input type="password" class="form-control" name="sClaveUsu" id="sClaveUsu" value="" maxlength="20">
										<div class="helper-note mt-2"><?php echo h($passwordHelp); ?></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-5">
							<div class="section-card h-100">
								<div class="section-title">Configuracion del usuario</div>
								<div class="mb-3">
									<label class="form-label d-block">Estado *</label>
									<div class="d-flex flex-wrap gap-3">
										<div class="form-check"><input class="form-check-input" type="radio" name="sEstUsu" id="sEstUsu1" value="1"<?php if($sEstUsu != "0") echo ' checked'; ?>><label class="form-check-label" for="sEstUsu1">Activo</label></div>
										<div class="form-check"><input class="form-check-input" type="radio" name="sEstUsu" id="sEstUsu0" value="0"<?php if($sEstUsu == "0") echo ' checked'; ?>><label class="form-check-label" for="sEstUsu0">Desactivo</label></div>
									</div>
								</div>
								<div class="mb-3">
									<label for="sCodRolUsu" class="form-label">Rol *</label>
									<select name="sCodRolUsu" id="sCodRolUsu" class="form-select">
										<?php foreach($roleOptions as $role): ?>
										<option value="<?php echo h($role['code']); ?>"<?php if($role['code'] == $sCodRolUsu) echo ' selected'; ?>><?php echo h($role['name']); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="helper-note">
									<?php if($sPathCert != ""): ?>Certificado configurado: <?php echo h($sPathCert); ?><?php else: ?>Sin certificado configurado visible en este flujo.<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer bg-white border-0 px-4 pb-4 pt-0">
					<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
						<div class="small text-secondary"><span class="text-danger">*</span> Campos requeridos.</div>
						<div class="d-flex gap-2">
							<button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-2"></i>Aceptar</button>
							<a href="<?php echo h($returnHref); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<script type="text/javascript">try{lsetup();}catch(e){}</script>
</body>
</html>
