<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/ver_emp_adm.php");
include("../include/db_lib.php");
include("../include/tables.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }
function estadoLibro($estado){
	switch((string)$estado){
		case "0": return "Cargado";
		case "1": return "Enviado a SII";
		case "3": return "Aceptado SII";
		case "5": return "Rechazado SII";
		case "8": return "Rechazado SII";
		case "13": return "Aceptado con Reparos";
		default: return "Sin estado";
	}
}

$conn = conn();
$sTipo = isset($_GET["sTipo"]) ? (string)$_GET["sTipo"] : "";
$sLinkActual = "libros/list_libro.php?sTipo=" . $sTipo;
$sql = "  SELECT 
				L.clcv_correl, 
				L.clcv_rut_emisor,
				L.clcv_dv_emisor,
				L.clcv_per_trib,
				L.clcv_tip_lib,
				L.clcv_tip_env,
				LX.est_lcx,
				'<a href=\"libros/view_xml.php?nClcvcorrel=' || LX.clcv_correl || '\">Ver</a>' as xml,
				LX.trackid
			FROM
				lcv L,
				lcvxml LX
			WHERE
				L.clcv_tip_oper = '" . str_replace("'","''",$sTipo) . "' AND
				L.codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "' AND
				L.clcv_correl = LX.clcv_correl
			ORDER BY L.clcv_per_trib DESC ";

$result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);
$sPaginaResult = sPagina($conn, $sql, $sLinkActual);
$libros = array();

while(!$result->EOF){
	$nCodClcv = trim($result->fields["clcv_correl"]);
	$nEstLcx = trim($result->fields["est_lcx"]);
	$libros[] = array(
		"correl" => $nCodClcv,
		"periodo" => trim($result->fields["clcv_per_trib"]),
		"tipo_libro" => trim($result->fields["clcv_tip_lib"]),
		"tipo_envio" => trim($result->fields["clcv_tip_env"]),
		"estado_codigo" => $nEstLcx,
		"estado_texto" => estadoLibro($nEstLcx),
		"xml" => trim($result->fields["xml"]),
		"trackid" => trim($result->fields["trackid"]),
		"permite_eliminar" => ($nEstLcx == 8 || $nEstLcx == 0 || $nEstLcx == 13 || $nEstLcx == 5)
	);
	$result->MoveNext();
}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>Libros - Portal DTE</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<base href="<?php echo h($_LINK_BASE); ?>" />
		<script type="text/javascript" src="javascript/common.js"></script>
		<link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/general.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/custom.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/layout.css">
		<link rel="stylesheet" type="text/nonsense" href="skins/<?php echo h($_SKINS); ?>/css/misc.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
		<style>
			body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}.page-shell{max-width:1280px;margin:0 auto;padding:1rem}.topbar{display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1rem}.panel{border:1px solid rgba(15,23,42,.06);border-radius:18px;box-shadow:0 14px 32px rgba(15,23,42,.08);overflow:hidden}.panel-header{background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;padding:1rem 1.25rem}.panel-note{background:#f8fbff;border:1px solid #d7e3f0;border-radius:14px;padding:.95rem 1rem}.table thead th{background:#001f3f;color:#fff;white-space:nowrap}.table tbody td{vertical-align:middle}.table tbody tr:hover{background:#f8fbff}.status-pill{display:inline-flex;align-items:center;padding:.35rem .75rem;border-radius:999px;font-size:.8rem;font-weight:600;background:#e7f1ff;color:#0b5ed7}.paging{display:flex;gap:.45rem;flex-wrap:wrap;align-items:center}.paging a,.paging span{display:inline-flex;align-items:center;justify-content:center;min-width:2rem;height:2rem;border:1px solid #d0d7e2;border-radius:999px;padding:0 .7rem;background:#fff;color:#0f172a;text-decoration:none;font-size:.85rem}.paging a:hover{background:#eff6ff;border-color:#93c5fd}.empty-state{padding:4rem 1rem;text-align:center;color:#6b7280}.empty-state i{font-size:3rem}#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
		</style>
		<script type="text/javascript">
		function _body_onload(){ try{ SetContext('clients'); setActiveButtonByName('clients'); }catch(e){} try{ loff(); }catch(e){} }
		function _body_onunload(){ try{ lon(); }catch(e){} }
		var opt_no_frames = false, opt_integrated_mode = false;
		function delLibro(url){ if(confirm('Eliminar Libro')) location.href = url; return false; }
		</script>
	</head>
	<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
		<a href="#" name="top" id="top"></a>
		<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>

		<div class="page-shell">
			<div class="topbar">
				<div>
					<div class="small text-secondary">Libros &gt; Listado</div>
					<h1 class="h3 mb-0">Libro de <?php echo h($sTipo); ?></h1>
				</div>
				<div class="d-flex gap-2 flex-wrap">
					<span class="badge rounded-pill text-bg-light text-primary-emphasis">Paginaci&oacute;n original preservada</span>
					<a href="main.php" class="btn btn-outline-secondary"><i class="bi bi-house-door me-2"></i>Inicio</a>
				</div>
			</div>

			<div class="card panel">
				<div class="panel-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
					<div>
						<div class="fw-semibold"><i class="bi bi-journal-text me-2"></i>Listado de libros electr&oacute;nicos</div>
						<div class="small mt-1">Se mantiene la consulta original sobre <strong>lcv</strong> y <strong>lcvxml</strong>, junto con los accesos a detalle, XML y eliminaci&oacute;n.</div>
					</div>
					<span class="badge rounded-pill text-bg-light text-primary-emphasis"><?php echo count($libros); ?> registro(s) en la p&aacute;gina</span>
				</div>
				<div class="card-body p-4">
					<div class="panel-note mb-4">
						<div class="fw-semibold mb-1"><i class="bi bi-info-circle me-2"></i>Nota operativa</div>
						<div class="text-secondary small">Solo se pueden borrar los libros con error, manteniendo el mismo flujo hacia <code>libros/del_libro.php</code>.</div>
					</div>
					<div class="table-responsive">
						<table class="table table-hover align-middle mb-0">
							<thead>
								<tr>
									<th>Estado</th>
									<th>Trackid</th>
									<th>Periodo</th>
									<th>Tipo Libro</th>
									<th>Tipo Env&iacute;o</th>
									<th>Detalle</th>
									<th>XML</th>
									<th>Acci&oacute;n</th>
								</tr>
							</thead>
							<tbody>
								<?php if(count($libros) > 0): ?>
									<?php foreach($libros as $libro): ?>
										<?php
										$detailUrl = 'libros/det_libro.php?sTipo=' . rawurlencode($sTipo) . '&nCodClcv=' . rawurlencode($libro['correl']);
										$deleteUrl = 'libros/del_libro.php?sTipo=' . rawurlencode($sTipo) . '&nCodClcv=' . rawurlencode($libro['correl']);
										?>
										<tr>
											<td><span class="status-pill"><?php echo h($libro['estado_texto']); ?></span></td>
											<td><?php echo h($libro['trackid']); ?></td>
											<td><?php echo h($libro['periodo']); ?></td>
											<td><?php echo h($libro['tipo_libro']); ?></td>
											<td><?php echo h($libro['tipo_envio']); ?></td>
											<td><a href="<?php echo h($detailUrl); ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye me-1"></i>Ver</a></td>
											<td><?php echo $libro['xml']; ?></td>
											<td><?php if($libro['permite_eliminar']): ?><a href="#" class="btn btn-outline-danger btn-sm" onclick="return delLibro('<?php echo h($deleteUrl); ?>');"><i class="bi bi-trash me-1"></i>Eliminar</a><?php else: ?><span class="text-muted small">No disponible</span><?php endif; ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="8">
											<div class="empty-state">
												<i class="bi bi-journal-x"></i>
												<h5 class="mt-3">No hay libros para mostrar</h5>
												<p class="mb-0">Pruebe con otro tipo de libro o revise si existen registros asociados a la empresa actual.</p>
											</div>
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
				<?php if(trim($sPaginaResult) != ""): ?>
				<div class="card-footer bg-white">
					<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
						<span class="text-muted small">La navegaci&oacute;n sigue usando la paginaci&oacute;n legacy del m&oacute;dulo.</span>
						<div class="paging"><?php echo $sPaginaResult; ?></div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<script type="text/javascript">try{ lsetup(); }catch(e){}</script>
	</body>
</html>