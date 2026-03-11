<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/tables.php");
include("../include/db_lib.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }
function jsq($value){ return str_replace(array("\\", "'", "\r", "\n"), array("\\\\", "\\'", "", "\\n"), (string)$value); }
function alertExpr($value){
	$value = trim((string)$value);
	if($value === "") return "";
	if(preg_match('/^[_A-Za-z][_A-Za-z0-9]*$/', $value)) return "alert(" . $value . ");";
	return "alert('" . jsq($value) . "');";
}

if(trim($_SESSION["_COD_EMP_USU_SESS"]) == ""){
	$sUrl = $_PATH_NIVEL_ATRAS . "sel_emp.php?sUriRetorno=" . urlencode($_SERVER["REQUEST_URI"]);
	header("location:" . $sUrl);
	exit;
}

$conn = conn();
$nCodEmp = trim($_SESSION["_COD_EMP_USU_SESS"]);
$rut_usu = "";
$dv_usu = "";
$clave_sii = "";
$nCodDoc = isset($_GET["nCodDoc"]) ? trim((string)$_GET["nCodDoc"]) : "";
$sAccion = isset($_GET["sAccion"]) ? trim((string)$_GET["sAccion"]) : "";
$sMsgJs = isset($_GET["sMsgJs"]) ? trim((string)$_GET["sMsgJs"]) : "";
$alertCall = alertExpr($sMsgJs);
$returnHref = "main.php";

$sql = "SELECT cod_config, map_config, valor_config FROM config WHERE codi_empr = '" . str_replace("'","''",trim($nCodEmp)) ."' ORDER BY orden";
$result = rCursor($conn, $sql);
while(!$result->EOF) {
	if(trim($result->fields["cod_config"]) == "RUT_AUTENTICACION_SII") $rut_usu = trim($result->fields["valor_config"]);
	if(trim($result->fields["cod_config"]) == "DV_AUTENTICACION_SII") $dv_usu = trim($result->fields["valor_config"]);
	if(trim($result->fields["cod_config"]) == "CLAVE_AUTENTICACION_SII") $clave_sii = trim($result->fields["valor_config"]);
	$result->MoveNext();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="shortcut icon" href="/favicon.ico">
	<title>Usuario SII - Portal DTE</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<base href="<?php echo h($_LINK_BASE); ?>" />
	<script type="text/javascript" src="javascript/common.js"></script>
	<script type="text/javascript" src="javascript/msg.js"></script>
	<script type="text/javascript" src="javascript/funciones.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
		.page-shell{max-width:920px;margin:0 auto;padding:1rem}.form-card{border:0;border-radius:18px;box-shadow:0 18px 40px rgba(15,23,42,.12);overflow:hidden}
		.form-card .card-header{background:linear-gradient(135deg,#198754 0%,#20c997 100%);color:#fff;padding:1.25rem 1.5rem}.section-note{background:#f8fafc;border:1px solid #dbe5f1;border-radius:14px;padding:1rem}
		#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
	</style>
	<script type="text/javascript">
	function _body_onload(){ try{loff();}catch(e){} <?php if($alertCall !== ""){ echo $alertCall; } ?> }
	function _body_onunload(){ try{lon();}catch(e){} }
	function valida(){
		var F = document._FFORM;
		if(vacio(F.rut_usu.value,'Debe ingresar el rut de usuario') == false){ F.rut_usu.select(); return false; }
		if(vacio(F.dv_usu.value,'Debe ingresar el digito verificador del rut de usuario') == false){ F.dv_usu.select(); return false; }
		if(vacio(F.clave_sii.value,'Debe ingresar la clave del SII para el rut de usuario') == false){ F.clave_sii.select(); return false; }
		return true;
	}
	</script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP">
	<form name="_FFORM" action="mantencion/pro_user_sii.php" method="post" onsubmit="return valida();">
		<input type="hidden" name="nCodDoc" value="<?php echo h($nCodDoc); ?>">
		<input type="hidden" name="sAccion" value="<?php echo h($sAccion); ?>">
		<input type="hidden" name="start" value="">
		<input type="hidden" name="cmd" value="update">
		<input type="hidden" name="lock" value="false">
		<input type="hidden" name="previous_page" value="cl_ed">
		<a href="#" name="top" id="top"></a>
		<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
		<div class="page-shell">
			<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
				<div>
					<div class="small text-secondary">Mantencion &gt; Usuario SII</div>
					<h1 class="h3 mb-0">Actualizacion de Usuario SII</h1>
				</div>
				<a href="<?php echo h($returnHref); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Volver</a>
			</div>
			<div class="card form-card">
				<div class="card-header"><i class="bi bi-person-badge me-2"></i>Formulario Usuario SII</div>
				<div class="card-body p-4">
					<div class="section-note mb-4 small text-secondary">Estos datos se guardan para la empresa actualmente seleccionada y se usan en la autenticacion con el SII.</div>
					<div class="row g-3">
						<div class="col-md-5">
							<label for="rut_usu" class="form-label">RUT Usuario SII *</label>
							<input type="text" class="form-control" name="rut_usu" id="rut_usu" value="<?php echo h($rut_usu); ?>" maxlength="8">
						</div>
						<div class="col-md-2">
							<label for="dv_usu" class="form-label">DV RUT SII *</label>
							<input type="text" class="form-control" name="dv_usu" id="dv_usu" value="<?php echo h($dv_usu); ?>" maxlength="1">
						</div>
						<div class="col-md-5">
							<label for="clave_sii" class="form-label">Clave SII *</label>
							<input type="text" class="form-control" name="clave_sii" id="clave_sii" value="<?php echo h($clave_sii); ?>" maxlength="30">
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