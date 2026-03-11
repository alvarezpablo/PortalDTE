<?php
include("../include/config.php");
include("../include/ver_aut.php");
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

$nRutCli = rq("nRutCli");
$nRutCliNew = rq("nRutCliNew");
$sRazClie = rq("sRazClie");
$sDvClie = rq("sDvClie");
$sDvClieNew = rq("sDvClieNew");
$sEmiElecClie = rq("sEmiElecClie");
$sRecElecClie = rq("sRecElecClie");
$sEnvEmail = rq("sEnvEmail");
$sRazEmp = rq("sRazEmp");
$sCodEmp = rq("sCodEmp");
$sCodEmpNew = rq("sCodEmpNew");
$sFono = rq("sFono");
$sGuiaClie = rq("sGuiaClie");
$sCiudClie = rq("sCiudClie");
$sGiroClie = rq("sGiroClie");
$sComClie = rq("sComClie");
$sDirClie = rq("sDirClie");
$sNomTec = rq("sNomTec");
$sFonoTec = rq("sFonoTec");
$sEmailTec = rq("sEmailTec");
$sNomAdm = rq("sNomAdm");
$sFonoAdm = rq("sFonoAdm");
$sEmailAdm = rq("sEmailAdm");
$sAccion = rq("sAccion");
$sMsgJs = rq("sMsgJs");
$conn = conn();
$alertCall = alertExpr($sMsgJs);
$returnHref = "mantencion/list_clie.php";
$sRecElecClie = ($sRecElecClie === "S") ? "S" : "N";
$isEdit = ($sAccion === "M");
$pageTitle = $isEdit ? "Editar cliente" : "Ingresar cliente";
$clientLabel = trim($nRutCli . (($sDvClie != "") ? "-" . $sDvClie : ""));

$companyOptions = array();
$sql = "SELECT codi_empr, rs_empr FROM empresa ";
if(trim($_SESSION["_COD_ROL_SESS"]) != "1") {
	$sql .= " WHERE codi_empr IN(SELECT codi_empr FROM empr_usu WHERE cod_usu = '" . str_replace("'","''",$_SESSION["_COD_USU_SESS"]) . "') ";
}
$sql .= " ORDER BY rs_empr";
$result = rCursor($conn, $sql);
while(!$result->EOF) {
	$companyOptions[] = array(
		"code" => trim($result->fields["codi_empr"]),
		"name" => trim($result->fields["rs_empr"])
	);
	$result->MoveNext();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="shortcut icon" href="/favicon.ico">
	<title>Clientes - Portal DTE</title>
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
		.page-shell{max-width:1180px;margin:0 auto;padding:1rem}.hero-card,.form-card,.section-card{border:0;border-radius:18px;box-shadow:0 18px 40px rgba(15,23,42,.12)}
		.hero-card{background:linear-gradient(135deg,#0f172a 0%,#0b5ed7 100%);color:#fff;padding:1.5rem;margin-bottom:1rem}.form-card{overflow:hidden}.form-card .card-header{background:#0f172a;color:#fff;padding:1rem 1.25rem}
		.section-card{background:#f8fafc;border:1px solid #dbe5f1;padding:1rem}.section-title{font-size:.95rem;font-weight:700;color:#0f172a;margin-bottom:.85rem}.helper-note{font-size:.85rem;color:#64748b}
		#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
		@media (max-width:767.98px){.page-shell{padding:.75rem}}
	</style>
	<script type="text/javascript">
	function _body_onload(){
		try{loff();}catch(e){}
		<?php if($alertCall !== ""){ echo $alertCall; } ?>
		try{SetContext('cl_ed');}catch(e){}
	}
	function _body_onunload(){ try{lon();}catch(e){} }
	function valida(){
		var F = document._FFORM;
		var rutaux = Trim(F.nRutCliNew.value) + "-" + Trim(F.sDvClieNew.value);
		if(rut(rutaux,_MSG_RUT) == false){ F.nRutCliNew.select(); return false; }
		if(vacio(F.sRazClie.value,_MSG_RAZON_SOCIAL) == false){ F.sRazClie.select(); return false; }
		if(vacio(F.sDirClie.value,_MSG_DIR) == false){ F.sDirClie.select(); return false; }
		if(vacio(F.sComClie.value,_MSG_COMUNA) == false){ F.sComClie.select(); return false; }
		if(vacio(F.sGiroClie.value,_MSG_GIRO) == false){ F.sGiroClie.select(); return false; }
		if(vacio(F.sCiudClie.value,_MSG_CIUDAD) == false){ F.sCiudClie.select(); return false; }
		if(vacio(F.sFono.value,_MSG_FONO) == false){ F.sFono.select(); return false; }
		if(Trim(F.sEnvEmail.value) != "" && email(F.sEnvEmail.value, _MSG_EMAIL) == false){ F.sEnvEmail.select(); return false; }
		if(vacio_sm(F.sNomTec.value) == false && vacio_sm(F.sNomAdm.value) == false){ alert(_MSG_TEC_ADM); return false; }
		if(Trim(F.sEmailTec.value) != "" && email(F.sEmailTec.value, _MSG_EMAIL) == false){ F.sEmailTec.select(); return false; }
		if(Trim(F.sEmailAdm.value) != "" && email(F.sEmailAdm.value, _MSG_EMAIL) == false){ F.sEmailAdm.select(); return false; }
		return true;
	}
	</script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	<form name="_FFORM" action="mantencion/pro_clie.php" method="post" onsubmit="return valida();">
		<input type="hidden" name="nRutCli" value="<?php echo h($nRutCli); ?>">
		<input type="hidden" name="sDvClie" value="<?php echo h($sDvClie); ?>">
		<input type="hidden" name="sAccion" value="<?php echo h($sAccion); ?>">
		<input type="hidden" name="sCodEmp" value="<?php echo h($sCodEmp); ?>">
		<input type="hidden" name="sRazEmp" value="<?php echo h($sRazEmp); ?>">
		<input type="hidden" name="sGuiaClie" value="<?php echo h($sGuiaClie); ?>">
		<input type="hidden" name="sEmiElecClie" value="<?php echo h($sEmiElecClie); ?>">
		<a href="#" name="top" id="top"></a>
		<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>
		<div class="page-shell">
			<div class="hero-card">
				<div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
					<div>
						<div class="small opacity-75 mb-1">Mantencion &gt; Clientes</div>
						<h1 class="h3 mb-2"><?php echo h($pageTitle); ?></h1>
						<p class="mb-0 opacity-75">Se mantiene el mismo flujo legacy de alta y edicion de clientes, incluyendo validaciones, empresa asociada y contactos.</p>
					</div>
					<div class="d-flex flex-wrap gap-2 align-items-center">
						<?php if($clientLabel != ""): ?><span class="badge text-bg-light text-dark border px-3 py-2">Cliente actual: <?php echo h($clientLabel); ?></span><?php endif; ?>
						<a href="<?php echo h($returnHref); ?>" class="btn btn-outline-light"><i class="bi bi-arrow-left-circle me-2"></i>Volver al listado</a>
					</div>
				</div>
			</div>
			<div class="card form-card">
				<div class="card-header"><i class="bi bi-people me-2"></i>Formulario de Cliente</div>
				<div class="card-body p-4">
					<div class="row g-4">
						<div class="col-12">
							<div class="section-card">
								<div class="section-title">Datos principales</div>
								<div class="row g-3">
									<div class="col-md-4">
										<label for="nRutCliNew" class="form-label">Rut de Cliente *</label>
										<div class="row g-2">
											<div class="col-8"><input type="text" class="form-control" name="nRutCliNew" id="nRutCliNew" value="<?php echo h($nRutCliNew); ?>" maxlength="10"></div>
											<div class="col-4"><input type="text" class="form-control text-uppercase" name="sDvClieNew" id="sDvClieNew" value="<?php echo h($sDvClieNew); ?>" maxlength="1"></div>
										</div>
									</div>
									<div class="col-md-8">
										<label for="sRazClie" class="form-label">Raz&oacute;n Social *</label>
										<input type="text" class="form-control" name="sRazClie" id="sRazClie" value="<?php echo h($sRazClie); ?>" maxlength="80">
									</div>
									<div class="col-md-6">
										<label for="sDirClie" class="form-label">Direcci&oacute;n *</label>
										<input type="text" class="form-control" name="sDirClie" id="sDirClie" value="<?php echo h($sDirClie); ?>" maxlength="60">
									</div>
									<div class="col-md-3">
										<label for="sComClie" class="form-label">Comuna *</label>
										<input type="text" class="form-control" name="sComClie" id="sComClie" value="<?php echo h($sComClie); ?>" maxlength="60">
									</div>
									<div class="col-md-3">
										<label for="sCiudClie" class="form-label">Ciudad *</label>
										<input type="text" class="form-control" name="sCiudClie" id="sCiudClie" value="<?php echo h($sCiudClie); ?>" maxlength="60">
									</div>
									<div class="col-md-6">
										<label for="sGiroClie" class="form-label">Giro *</label>
										<input type="text" class="form-control" name="sGiroClie" id="sGiroClie" value="<?php echo h($sGiroClie); ?>" maxlength="60">
									</div>
									<div class="col-md-3">
										<label for="sFono" class="form-label">Fono *</label>
										<input type="text" class="form-control" name="sFono" id="sFono" value="<?php echo h($sFono); ?>" maxlength="20">
									</div>
									<div class="col-md-3">
										<label for="sEnvEmail" class="form-label">Email Envio</label>
										<input type="text" class="form-control" name="sEnvEmail" id="sEnvEmail" value="<?php echo h($sEnvEmail); ?>" maxlength="100">
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-5">
							<div class="section-card h-100">
								<div class="section-title">Configuracion del cliente</div>
								<div class="mb-3">
									<label class="form-label d-block">Acepta Recepci&oacute;n de Email *</label>
									<div class="d-flex flex-wrap gap-3">
										<div class="form-check"><input class="form-check-input" type="radio" name="sRecElecClie" id="sRecElecClieS" value="S"<?php if($sRecElecClie == "S") echo ' checked'; ?>><label class="form-check-label" for="sRecElecClieS">Si</label></div>
										<div class="form-check"><input class="form-check-input" type="radio" name="sRecElecClie" id="sRecElecClieN" value="N"<?php if($sRecElecClie != "S") echo ' checked'; ?>><label class="form-check-label" for="sRecElecClieN">No</label></div>
									</div>
								</div>
								<div>
									<label for="sCodEmpNew" class="form-label">Empresa *</label>
									<select name="sCodEmpNew" id="sCodEmpNew" class="form-select">
										<?php foreach($companyOptions as $company): ?>
										<option value="<?php echo h($company['code']); ?>"<?php if($company['code'] == $sCodEmpNew) echo ' selected'; ?>><?php echo h($company['name']); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<p class="helper-note mt-3 mb-0">Se conserva la restriccion de empresas visibles segun el rol y las asociaciones del usuario.</p>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="section-card h-100">
								<div class="section-title">Contacto Tecnico</div>
								<div class="row g-3 mb-4">
									<div class="col-md-6"><label for="sNomTec" class="form-label">Nombre ***</label><input type="text" class="form-control" name="sNomTec" id="sNomTec" value="<?php echo h($sNomTec); ?>" maxlength="100"></div>
									<div class="col-md-3"><label for="sFonoTec" class="form-label">Fono</label><input type="text" class="form-control" name="sFonoTec" id="sFonoTec" value="<?php echo h($sFonoTec); ?>" maxlength="20"></div>
									<div class="col-md-3"><label for="sEmailTec" class="form-label">Email</label><input type="text" class="form-control" name="sEmailTec" id="sEmailTec" value="<?php echo h($sEmailTec); ?>" maxlength="100"></div>
								</div>
								<div class="section-title">Contacto Administrativo</div>
								<div class="row g-3">
									<div class="col-md-6"><label for="sNomAdm" class="form-label">Nombre ***</label><input type="text" class="form-control" name="sNomAdm" id="sNomAdm" value="<?php echo h($sNomAdm); ?>" maxlength="100"></div>
									<div class="col-md-3"><label for="sFonoAdm" class="form-label">Fono</label><input type="text" class="form-control" name="sFonoAdm" id="sFonoAdm" value="<?php echo h($sFonoAdm); ?>" maxlength="20"></div>
									<div class="col-md-3"><label for="sEmailAdm" class="form-label">Email</label><input type="text" class="form-control" name="sEmailAdm" id="sEmailAdm" value="<?php echo h($sEmailAdm); ?>" maxlength="100"></div>
								</div>
								<p class="helper-note mt-3 mb-0"><strong>***</strong> Debe informar al menos un contacto: tecnico y/o administrativo.</p>
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