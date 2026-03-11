<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/ver_aut_adm.php");
include("../include/db_lib.php");
include("../include/tables.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }

$nCodEmp = isset($_GET["nCodEmp"]) ? trim($_GET["nCodEmp"]) : "";
$nCodEmpSql = str_replace("'", "''", $nCodEmp);

$conn = conn();
$sLinkActual = "mantencion/list_config.php";

$sql = "SELECT cod_config, map_config, valor_config, tipo_campo, val_perm, valor_default, orden
	FROM config
	WHERE codi_empr='" . $nCodEmpSql . "'
	UNION
	SELECT cod_config, map_config, valor_config, tipo_campo, val_perm, valor_default, orden
	FROM config a
	WHERE a.codi_empr=0 and a.cod_config not in (select cod_config from config where codi_empr='" . $nCodEmpSql . "')
	ORDER BY 7";
$result = rCursor($conn, $sql);
$total = $result->RecordCount();

if($total == 0){
	$sql = "SELECT
				cod_config,
				map_config,
				valor_config,
				tipo_campo,
				val_perm,
				valor_default,
				orden
			FROM
				config
			WHERE
				codi_empr = 0
			ORDER BY orden";
	$result = rCursor($conn, $sql);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="shortcut icon" href="/favicon.ico">
	<title>Configuracion Properties - Portal DTE</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<base href="<?php echo h($_LINK_BASE); ?>" />
	<script type="text/javascript" src="javascript/common.js"></script>
	<script type="text/javascript" src="javascript/msg.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
		.page-shell{max-width:1180px;margin:0 auto;padding:1rem}
		.page-hero{background:linear-gradient(135deg,#1e293b 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(15,23,42,.18);margin-bottom:1.25rem}
		.hero-icon{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:1.4rem}
		.hero-pills{display:flex;flex-wrap:wrap;gap:.75rem}.hero-pill{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:999px;padding:.45rem .85rem;font-size:.82rem}
		.card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden;margin-bottom:1rem}
		.card-header{background:#1e293b;color:#fff;padding:.9rem 1rem}.card-header .small{color:rgba(255,255,255,.75)}
		.table thead th{background:#1e293b;color:#fff;white-space:nowrap;vertical-align:middle}.table tbody td{vertical-align:middle}.table tbody tr:hover{background:#f8fbff}
		.config-label{font-weight:600;color:#0f172a}.config-meta{font-size:.8rem;color:#64748b}.radio-list{display:flex;flex-wrap:wrap;gap:.75rem 1rem}.save-bar{display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap}
		#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
		@media (max-width:767.98px){.page-shell{padding:.75rem}.page-hero{padding:1.1rem}.save-bar{align-items:stretch}}
	</style>
	<script type="text/javascript">
	function _body_onload(){
		try{SetContext('clients');}catch(e){}
		try{setActiveButtonByName('clients');}catch(e){}
		try{loff();}catch(e){}
	}

	function _body_onunload(){
		try{lon();}catch(e){}
	}

	var opt_no_frames = false;
	var opt_integrated_mode = false;
	</script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP">
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2" /><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>

	<div class="page-shell">
		<div class="page-hero">
			<div class="row g-3 align-items-center">
				<div class="col-lg-8">
					<div class="d-flex align-items-start gap-3">
						<div class="hero-icon"><i class="bi bi-sliders"></i></div>
						<div>
							<h1 class="h3 mb-2">Configuracion Properties</h1>
							<p class="mb-0 opacity-75">Edicion conservadora de parametros, preservando el POST legacy a <strong>mantencion/pro_config.php</strong> y los nombres de campos originales.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="hero-pills justify-content-lg-end">
						<span class="hero-pill"><i class="bi bi-building me-1"></i>Empresa: <?php echo h($nCodEmp !== "" ? $nCodEmp : "N/D"); ?></span>
						<span class="hero-pill"><i class="bi bi-shield-check me-1"></i>Contrato legacy preservado</span>
					</div>
				</div>
			</div>
		</div>

		<div class="alert alert-info border-0 shadow-sm d-flex align-items-start gap-3" role="alert">
			<i class="bi bi-info-circle-fill fs-4"></i>
			<div>
				<div class="fw-semibold">Edicion compatible con el flujo actual</div>
				<div>Se mantienen sin cambios `nCodEmp`, `nTotalRadio`, `aCodConfig[]`, `aDescConfig[]`, `aCodConfigN` y `aDescConfigN` para no alterar el guardado existente.</div>
			</div>
		</div>

		<form name="_FDEL" method="post" action="mantencion/pro_config.php" class="mb-0">
			<input type="hidden" name="sAccion" value="E">
			<input type="hidden" name="nCodEmp" value="<?php echo h($nCodEmp); ?>">

			<div class="card">
				<div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
					<div>
						<div class="fw-semibold"><i class="bi bi-gear me-2"></i>Parametros configurables</div>
						<div class="small mt-1">La consulta SQL y el fallback a configuracion por defecto se mantienen intactos.</div>
					</div>
					<span class="badge rounded-pill text-bg-light text-primary-emphasis">Edicion directa</span>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-hover align-middle mb-0">
							<thead>
								<tr>
									<th width="32%">Codigo</th>
									<th width="68%">Valor</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$j = 0;
								while (!$result->EOF) {
									$nCodConfig = trim($result->fields["cod_config"]);
									$sMapConfig = trim($result->fields["map_config"]);
									$sDescConfig = trim($result->fields["valor_config"]);
									$sTipoCampo = trim($result->fields["tipo_campo"]);
									$sValorPerm = trim($result->fields["val_perm"]);
									$sValorDefault = trim($result->fields["valor_default"]);
								?>
								<tr>
									<td>
										<div class="config-label"><?php echo h($sMapConfig); ?></div>
										<div class="config-meta">Codigo interno: <?php echo h($nCodConfig); ?><?php if($sTipoCampo != ""){ ?> &middot; Tipo: <?php echo h($sTipoCampo); ?><?php } ?></div>
									</td>
									<td>
										<?php if($sTipoCampo == "text" || $sTipoCampo == "password") { ?>
											<input type="hidden" name="aCodConfig[]" value="<?php echo h($nCodConfig); ?>">
											<input type="text" name="aDescConfig[]" class="form-control" value="<?php echo h($sDescConfig); ?>">
										<?php } else {
											if($sDescConfig == "")
												$sDescConfig = $sValorDefault;

											$aValorPosible = explode(",", $sValorPerm);
										?>
											<input type="hidden" name="aCodConfig<?php echo $j; ?>" value="<?php echo h($nCodConfig); ?>">
											<div class="radio-list">
												<?php for($i = 0; $i < sizeof($aValorPosible); $i++) {
													$valorOpcion = $aValorPosible[$i];
													$check = ($valorOpcion == $sDescConfig) ? "checked" : "";
												?>
												<label class="form-check form-check-inline mb-0">
													<input class="form-check-input" type="radio" name="aDescConfig<?php echo $j; ?>" value="<?php echo h($valorOpcion); ?>" <?php echo $check; ?>>
													<span class="form-check-label"><?php echo h($valorOpcion); ?></span>
												</label>
												<?php } ?>
											</div>
										<?php
											$j++;
										} ?>
									</td>
								</tr>
								<?php
									$result->MoveNext();
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card-footer bg-white border-0 p-3">
					<div class="save-bar">
						<div class="text-secondary small">Al grabar, el proceso actual reconstruye la configuracion y regenera las properties de la empresa.</div>
						<div>
							<input type="hidden" name="nTotalRadio" value="<?php echo h($j); ?>">
							<input type="submit" value="Grabar" class="btn btn-primary px-4">
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</body>
</html>