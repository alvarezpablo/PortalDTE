<?php
include("../include/ver_aut.php");
include("../include/ver_emp_adm.php");
require_once(__DIR__ . "/config.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }

$sNomEmp = isset($_SESSION["_NOM_EMP_USU_SESS"]) ? (string)$_SESSION["_NOM_EMP_USU_SESS"] : "";
$rut_empr = "";
$archivos = array();
$errorMessage = "";
$infoMessage = "";

if($codi_empr > 0){
	try {
		$conn = new PDO("pgsql:host={$db_config['host']};dbname={$db_config['database']}", $db_config['user'], $db_config['password']);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$query = "SELECT rut_empr FROM empresa WHERE codi_empr = :codi_empr";
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':codi_empr', $codi_empr, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if($result){
			$rut_empr = $result['rut_empr'];
		}

		$query = "SELECT year, month, filename, created_at, updated_at FROM archivos_generados WHERE codi_empr = :codi_empr ORDER BY year DESC, month DESC, updated_at ASC";
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':codi_empr', $codi_empr, PDO::PARAM_INT);
		$stmt->execute();
		$archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(count($archivos) == 0){
			$infoMessage = "No se encontraron archivos respaldados.";
		}
	} catch (PDOException $e) {
		$errorMessage = "Error al conectar a la base de datos: " . $e->getMessage();
	}
} else {
	$errorMessage = "Debe especificar un c&oacute;digo de empresa v&aacute;lido.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="shortcut icon" href="/favicon.ico">
	<title>Respaldo XML exportado - Portal DTE</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
		.page-shell{max-width:1100px;margin:0 auto;padding:1rem}.topbar{display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1rem}.panel{border:0;border-radius:18px;box-shadow:0 16px 36px rgba(15,23,42,.10);overflow:hidden}.panel-header{background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;padding:1rem 1.25rem}.panel-note{background:#f8fbff;border:1px solid #d7e3f0;border-radius:14px;padding:1rem}.table thead th{background:#001f3f;color:#fff;white-space:nowrap}.table tbody td{vertical-align:middle}.table tbody tr.updated-row{background:#fff9db}.table tbody tr:hover{background:#f8fbff}.badge-soft{background:#e7f1ff;color:#0b5ed7;border:1px solid #bdd3ff}.muted{color:#64748b;font-size:.92rem}
	</style>
</head>
<body>
	<div class="page-shell">
		<div class="topbar">
			<div>
				<div class="small text-secondary">Exportaci&oacute;n XML &gt; Respaldos</div>
				<h1 class="h3 mb-0">Archivos XML exportados</h1>
			</div>
			<a href="../main.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Volver</a>
		</div>

		<div class="card panel">
			<div class="panel-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
				<div>
					<div class="fw-semibold"><i class="bi bi-cloud-arrow-down me-2"></i>Respaldos disponibles</div>
					<div class="small mt-1">Se mantiene la descarga directa mediante <strong>download.php</strong> con el mismo contrato de par&aacute;metros.</div>
				</div>
				<?php if($sNomEmp != ""): ?><span class="badge rounded-pill text-bg-light text-primary-emphasis"><?php echo h($sNomEmp); ?></span><?php endif; ?>
			</div>
			<div class="card-body p-4">
				<div class="panel-note mb-4">
					<div class="fw-semibold mb-1"><i class="bi bi-info-circle me-2"></i>Uso del respaldo</div>
					<div class="muted">Descomprima los archivos usando WinRAR, 7zip, Winzip o una herramienta equivalente. Los registros resaltados corresponden a respaldos actualizados posteriormente a su creaci&oacute;n.</div>
				</div>

				<?php if($errorMessage != ""): ?>
					<div class="alert alert-danger mb-0"><i class="bi bi-exclamation-triangle me-2"></i><?php echo $errorMessage; ?></div>
				<?php else: ?>
					<?php if($infoMessage != ""): ?>
						<div class="alert alert-secondary"><i class="bi bi-inbox me-2"></i><?php echo $infoMessage; ?></div>
					<?php endif; ?>
					<div class="table-responsive">
						<table class="table align-middle mb-0">
							<thead>
								<tr>
									<th>A&ntilde;o</th>
									<th>Mes</th>
									<th>Archivo</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($archivos as $archivo): ?>
									<?php
									$year = $archivo['year'];
									$month = $archivo['month'];
									$filename = $archivo['filename'];
									$created_at = $archivo['created_at'];
									$updated_at = $archivo['updated_at'];
									$isUpdated = ($updated_at > $created_at);
									$downloadUrl = 'download.php?filename=' . rawurlencode($filename) . '&year=' . rawurlencode($year) . '&month=' . rawurlencode($month) . '&rut=' . rawurlencode($rut_empr);
									?>
									<tr class="<?php echo ($isUpdated ? 'updated-row' : ''); ?>">
										<td><?php echo h($year); ?></td>
										<td><?php echo h($month); ?></td>
										<td><a href="<?php echo h($downloadUrl); ?>" class="link-primary fw-semibold"><?php echo h($filename); ?></a></td>
										<td><?php if($isUpdated): ?><span class="badge rounded-pill badge-soft">Actualizado</span><?php else: ?><span class="text-muted small">Original</span><?php endif; ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</body>
</html>