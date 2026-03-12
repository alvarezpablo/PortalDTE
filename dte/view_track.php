<?php

include("../include/config.php");
$_NO_MSG = true;
include("../include/db_lib.php");
include("../include/tables.php");
include("../include/ver_aut.php");
include("../include/ver_emp_adm.php");

$conn = conn();
$nFolioDte = isset($_GET["nFolioDte"]) ? (string) $_GET["nFolioDte"] : "";
$nTipoDocu = isset($_GET["nTipoDocu"]) ? (string) $_GET["nTipoDocu"] : "";

if (!function_exists('h')) {
	function h($value) {
		return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
	}
}

$movimientos = array();
$sql = "SELECT tipo_docu, folio_dte, to_char(ts,'dd-mm-yyyy hh:MI') as ts2 ,est_dte, msg_track_dte FROM trackdte WHERE tipo_docu = '" . str_replace("'","''", $nTipoDocu) . "' AND folio_dte = '" . str_replace("'","''", $nFolioDte) . "' and codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "' order by ts asc";
$result = rCursor($conn, $sql);

while (!$result->EOF) {
	$tipo_docu = trim($result->fields["tipo_docu"]);
	$est_dte = trim($result->fields["est_dte"]);
	$ts = trim($result->fields["ts2"]);
	$msg_track_dte = trim($result->fields["msg_track_dte"]);
	$descextra = "";
	$numboleta = "";

	if (($tipo_docu == "39" || $tipo_docu == "41") && ($est_dte == "64" || $est_dte == "77")) {
		$sql = "SELECT msg_xdte, num_envioboleta FROM xmldte WHERE tipo_docu = '" . str_replace("'","''", $nTipoDocu) . "' AND folio_dte = '" . str_replace("'","''", $nFolioDte) . "' and codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";
		$result2 = rCursor($conn, $sql);
		if (!$result2->EOF) {
			$descextra = str_replace("\n", "<br>", trim($result2->fields["msg_xdte"]));
			$numboleta = str_replace("\n", "<br>", trim($result2->fields["num_envioboleta"]));
		}

		if (trim(str_replace("<br>", "", $descextra)) == "" && $numboleta != "") {
			$sql = "SELECT msg_sii FROM xmlenvioboleta WHERE num_xed = '" . $numboleta . "'";
			$result3 = rCursor($conn, $sql);
			if (!$result3->EOF) {
				$msgsii = trim($result3->fields["msg_sii"]);
			}
		}
	}

	$detalle = $msg_track_dte;
	if (trim($descextra) != "") {
		$detalle .= "<br>" . $descextra;
	}

	$movimientos[] = array(
		"fecha" => $ts,
		"detalle" => $detalle
	);

	$result->MoveNext();
}

if (count($movimientos) == 0 && ($nTipoDocu == "39" || $nTipoDocu == "41")) {
	$msg_track_dte = "";
	$descextra = "";
	$numboleta = "";
	$ts = "";
	$sql = "SELECT msg_xdte, num_envioboleta FROM xmldte WHERE tipo_docu = '" . str_replace("'","''", $nTipoDocu) . "' AND folio_dte = '" . str_replace("'","''", $nFolioDte) . "' and codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";
	$result2 = rCursor($conn, $sql);
	if (!$result2->EOF) {
		$descextra = str_replace("\n", "<br>", trim($result2->fields["msg_xdte"]));
		$numboleta = str_replace("\n", "<br>", trim($result2->fields["num_envioboleta"]));
	}

	if (trim($numboleta) != "") {
		$sql = "SELECT msg_sii FROM xmlenvioboleta WHERE num_xed = '" . $numboleta . "'";
		$result3 = rCursor($conn, $sql);
		if (!$result3->EOF) {
			$msgsii = trim($result3->fields["msg_sii"]);
			$data = json_decode($msgsii, true);
			$estado = isset($data['estado']) ? $data['estado'] : "";

			if ($estado == "RSC") {
				$msg_track_dte = isset($data['detalle_rep_rech'][0]["descripcion"]) ? $data['detalle_rep_rech'][0]["descripcion"] : "";
				$descextra = isset($data['detalle_rep_rech'][0]['error'][0]['descripcion']) ? $data['detalle_rep_rech'][0]['error'][0]['descripcion'] : $descextra;
			}
		}
	}

	if (trim($msg_track_dte) != "" || trim(str_replace("<br>", "", $descextra)) != "") {
		$detalle = $msg_track_dte;
		if (trim($descextra) != "") {
			$detalle .= ($detalle != "" ? "<br>" : "") . $descextra;
		}

		$movimientos[] = array(
			"fecha" => $ts,
			"detalle" => $detalle
		);
	}
}

$hayMovimientos = count($movimientos) > 0;
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Track DTE</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
		<style>
			body{margin:0;background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
			.page-shell{max-width:920px;margin:0 auto;padding:16px}
			.topbar{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;margin-bottom:16px}
			.topbar-eyebrow{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#0b5ed7;margin-bottom:4px}
			.topbar-title{margin:0;font-size:26px;font-weight:700;color:#001f3f}
			.topbar-meta{margin-top:6px;font-size:13px;color:#64748b;max-width:680px}
			.topbar-chips{display:flex;flex-wrap:wrap;gap:8px;justify-content:flex-end}
			.topbar-chip{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border:1px solid #cfe0f5;border-radius:999px;background:#f8fbff;color:#0b5ed7;font-size:12px;font-weight:700}
			.panel{border:1px solid rgba(15,23,42,.08);border-radius:20px;box-shadow:0 16px 40px rgba(15,23,42,.08);overflow:hidden;background:#fff}
			.panel-header{padding:16px 20px;background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff}
			.panel-title{font-size:18px;font-weight:700}
			.panel-subtitle{margin-top:4px;font-size:13px;opacity:.92}
			.panel-body{padding:20px}
			.panel-note{background:#f8fbff;border:1px solid #d8e4f0;border-radius:16px;padding:14px 16px;margin-bottom:16px;font-size:13px;color:#334155}
			.table thead th{background:#001f3f;color:#fff;border-color:#001f3f;white-space:nowrap}
			.table tbody td{vertical-align:top}
			.track-date{white-space:nowrap;font-weight:600;color:#0f172a}
			.track-detail{font-size:13px;line-height:1.5;color:#334155}
			.empty-state{padding:32px 16px;text-align:center;color:#64748b}
			.empty-state i{font-size:32px;color:#94a3b8}
			.actions-row{display:flex;justify-content:flex-end;gap:12px;flex-wrap:wrap;margin-top:18px}
			@media (max-width:768px){.page-shell{padding:12px}.topbar-title{font-size:22px}.panel-body{padding:16px}}
		</style>
	</head>
	<body>
		<div class="page-shell">
			<div class="topbar">
				<div>
					<div class="topbar-eyebrow">DTE emitidos</div>
					<h1 class="topbar-title">Seguimiento del documento</h1>
					<div class="topbar-meta">Se conserva la consulta original a <strong>trackdte</strong> y, cuando corresponde, las validaciones sobre <strong>xmldte</strong> y <strong>xmlenvioboleta</strong> dentro del mismo popup.</div>
				</div>
				<div class="topbar-chips">
					<div class="topbar-chip"><i class="bi bi-file-earmark-text"></i> Tipo <?php echo h($nTipoDocu); ?></div>
					<div class="topbar-chip"><i class="bi bi-hash"></i> Folio <?php echo h($nFolioDte); ?></div>
				</div>
			</div>

			<div class="card panel">
				<div class="panel-header">
					<div class="panel-title"><i class="bi bi-diagram-3 me-2"></i>Movimientos registrados</div>
					<div class="panel-subtitle">Popup de trazabilidad del DTE consultado.</div>
				</div>
				<div class="card-body panel-body">
					<div class="panel-note">
						Se mantienen intactos los par&aacute;metros <strong>nFolioDte</strong> y <strong>nTipoDocu</strong>, junto con el criterio original de b&uacute;squeda y despliegue del track.
					</div>

					<div class="table-responsive">
						<table class="table table-hover align-middle mb-0">
							<thead>
								<tr>
									<th style="width: 180px;">Fecha</th>
									<th>Glosa</th>
								</tr>
							</thead>
							<tbody>
							<?php if ($hayMovimientos): ?>
								<?php foreach ($movimientos as $movimiento): ?>
									<tr>
										<td class="track-date"><?php echo h($movimiento['fecha']); ?></td>
										<td class="track-detail"><?php echo $movimiento['detalle']; ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="2">
										<div class="empty-state">
											<i class="bi bi-inbox"></i>
											<div class="mt-3 fw-semibold">No hay movimientos para mostrar</div>
											<div class="small">El documento consultado no devolvi&oacute; trazas visibles para esta empresa.</div>
										</div>
									</td>
								</tr>
							<?php endif; ?>
							</tbody>
						</table>
					</div>

					<div class="actions-row">
						<button type="button" class="btn btn-outline-secondary" onclick="window.close();">
							<i class="bi bi-x-circle me-2"></i>Cerrar
						</button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
