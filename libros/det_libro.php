<?php 
	include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_emp_adm.php");        
	include("../include/db_lib.php"); 
	include("../include/tables.php"); 
  
  $conn = conn();
	  $nCodClcv = isset($_GET["nCodClcv"]) ? (string) $_GET["nCodClcv"] : "";  
	  $sTipo = isset($_GET["sTipo"]) ? (string) $_GET["sTipo"] : "";  

	  $sLinkActual = "libros/det_libro.php?nCodClcv=" . $nCodClcv . "&sTipo=" . $sTipo;

	  if (!function_exists('h')) {
		function h($value) {
			return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
		}
	  }

	  $sql = "  SELECT 
					rlcv_tip_doc,
					(SELECT desc_tipo_docu from dte_tipo WHERE tipo_docu = lcv_res_seg.rlcv_tip_doc) as nom_tip_doc,
					rlcv_can_doc,
					rlcv_tot_exc,
					rlcv_tot_mto,
					rlcv_tot_iva
				FROM
					lcv_res_seg
				WHERE
					clcv_correl = '" . str_replace("'","''",$nCodClcv) . "' 
				ORDER BY rlcv_tip_doc ";

	  $result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);
	  $sPaginaResult = sPagina($conn, $sql, $sLinkActual);
?>
	<!doctype html>
	<html lang="es">
		<head>
			<link rel="shortcut icon" href="/favicon.ico">
			<title>Detalle de libro</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<meta name="viewport" content="width=device-width, initial-scale=1" />
			<base href="<?php echo $_LINK_BASE; ?>" />
			<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
			<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
			<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
			<style>
				body{margin:0;background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
				.page-shell{max-width:1120px;margin:0 auto;padding:16px}
				.topbar{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;margin-bottom:16px}
				.topbar-eyebrow{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#0b5ed7;margin-bottom:4px}
				.topbar-title{margin:0;font-size:28px;font-weight:700;color:#001f3f}
				.topbar-meta{margin-top:6px;max-width:760px;font-size:13px;color:#64748b}
				.topbar-chip{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border:1px solid #cfe0f5;border-radius:999px;background:#f8fbff;color:#0b5ed7;font-size:12px;font-weight:700}
				.panel{border:1px solid rgba(15,23,42,.08);border-radius:20px;box-shadow:0 16px 40px rgba(15,23,42,.08);overflow:hidden;background:#fff}
				.panel-header{padding:16px 20px;background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff}
				.panel-title{font-size:18px;font-weight:700}
				.panel-subtitle{margin-top:4px;font-size:13px;opacity:.92}
				.panel-body{padding:20px}
				.panel-note{background:#f8fbff;border:1px solid #d8e4f0;border-radius:16px;padding:14px 16px;margin-bottom:16px;font-size:13px;color:#334155}
				.table thead th{background:#001f3f;color:#fff;border-color:#001f3f;white-space:nowrap}
				.table tbody td{vertical-align:middle}
				.metric{text-align:right;font-variant-numeric:tabular-nums}
				.empty-state{padding:32px 16px;text-align:center;color:#64748b}
				.empty-state i{font-size:32px;color:#94a3b8}
				.paging{display:flex;flex-wrap:wrap;gap:6px;align-items:center;justify-content:flex-end}
				.paging a,.paging span{display:inline-flex;align-items:center;justify-content:center;min-width:36px;padding:7px 10px;border:1px solid #d8e4f0;border-radius:10px;background:#fff;color:#0b5ed7;text-decoration:none;font-size:13px}
				.paging strong{display:inline-flex;align-items:center;justify-content:center;min-width:36px;padding:7px 10px;border:1px solid #0b5ed7;border-radius:10px;background:#0b5ed7;color:#fff;font-size:13px}
				.actions-row{display:flex;justify-content:flex-end;gap:12px;flex-wrap:wrap;margin-top:18px}
				#loaderContainer{display:none}
				@media (max-width: 768px){.page-shell{padding:12px}.topbar-title{font-size:24px}.panel-body{padding:16px}}
			</style>
			<script type="text/javascript">
			function _body_onload(){
				SetContext('clients');
				setActiveButtonByName('clients');
				loff();
			}

			function _body_onunload(){
				lon();
			}

			var opt_no_frames = false;
			var opt_integrated_mode = false;
			setActiveButtonByName("clients");
			</script>
		</head>
		<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
			<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"></div></td></tr></table>

			<div class="page-shell">
				<div class="topbar">
					<div>
						<div class="topbar-eyebrow">Libros</div>
						<h1 class="topbar-title">Detalle del libro de <?php echo h($sTipo); ?></h1>
						<div class="topbar-meta">Se conserva la consulta original sobre el resumen del libro, junto con la paginaci&oacute;n legacy y el retorno al listado activo del m&oacute;dulo.</div>
					</div>
					<div class="topbar-chip"><i class="bi bi-journal-text"></i> Resumen por documento</div>
				</div>

				<div class="card panel">
					<div class="panel-header">
						<div class="panel-title"><i class="bi bi-table me-2"></i>Detalle consolidado</div>
						<div class="panel-subtitle">Libro de <?php echo h($sTipo); ?> para el correlativo seleccionado.</div>
					</div>
					<div class="card-body panel-body">
						<div class="panel-note">
							Se mantienen intactos los par&aacute;metros <strong>nCodClcv</strong> y <strong>sTipo</strong>, la consulta a <strong>lcv_res_seg</strong> y la navegaci&oacute;n original del detalle.
						</div>

						<div class="table-responsive">
							<table class="table table-hover align-middle mb-0">
								<thead>
									<tr>
										<th>Documento</th>
										<th class="text-end">Cantidad documento</th>
										<th class="text-end">Total exento</th>
										<th class="text-end">Total</th>
										<th class="text-end">Total IVA</th>
									</tr>
								</thead>
								<tbody>
								<?php if(!$result->EOF): ?>
									<?php while (!$result->EOF): ?>
										<?php
											$sTipDoc = trim($result->fields["nom_tip_doc"]);
											$nCanDoc = trim($result->fields["rlcv_can_doc"]);
											$nTotExc = trim($result->fields["rlcv_tot_exc"]);
											$nTotNeto = trim($result->fields["rlcv_tot_mto"]);
											$nTotIva = trim($result->fields["rlcv_tot_iva"]);
										?>
										<tr>
											<td class="fw-semibold"><?php echo h($sTipDoc); ?></td>
											<td class="metric"><?php echo number_format($nCanDoc,0,",","."); ?></td>
											<td class="metric"><?php echo number_format($nTotExc,0,",","."); ?></td>
											<td class="metric"><?php echo number_format($nTotNeto,0,",","."); ?></td>
											<td class="metric"><?php echo number_format($nTotIva,0,",","."); ?></td>
										</tr>
										<?php $result->MoveNext(); ?>
									<?php endwhile; ?>
								<?php else: ?>
									<tr>
										<td colspan="5">
											<div class="empty-state">
												<i class="bi bi-inbox"></i>
												<div class="mt-3 fw-semibold">No hay registros para mostrar</div>
												<div class="small">El correlativo seleccionado no devolvi&oacute; l&iacute;neas en el resumen del libro.</div>
											</div>
										</td>
									</tr>
								<?php endif; ?>
								</tbody>
							</table>
						</div>

						<?php if(trim($sPaginaResult) != ""): ?>
							<div class="mt-3 d-flex justify-content-end">
								<div class="paging"><?php echo $sPaginaResult; ?></div>
							</div>
						<?php endif; ?>

						<div class="actions-row">
							<a href="libros/list_libro.php?sTipo=<?php echo urlencode($sTipo); ?>" class="btn btn-outline-secondary">
								<i class="bi bi-arrow-left-circle me-2"></i>Volver al listado
							</a>
						</div>
					</div>
				</div>
			</div>
		</body>
	</html>
