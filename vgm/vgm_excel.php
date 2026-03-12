<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	include("../include/ver_aut.php");

	function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }
	$fini = isset($_GET["fecIni"]) ? trim((string)$_GET["fecIni"]) : "";
	$ffin = isset($_GET["fecFin"]) ? trim((string)$_GET["fecFin"]) : "";
	$emp = isset($_GET["emp"]) ? trim((string)$_GET["emp"]) : "77648628";
	$empresas = array(
		"77648628" => "SOCIEDAD DE PROFESIONALES VGM LIMITADA",
		"77648624" => "SOCIEDAD DE PROFESIONALES VGM OUTSOURCING LIMITADA",
		"77239803" => "VGM AUDITORES LIMITADA"
	);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Excel Softland</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
		.page-shell{max-width:920px;margin:0 auto;padding:1rem}
		.panel{border:0;border-radius:18px;box-shadow:0 18px 40px rgba(15,23,42,.12);overflow:hidden}
		.panel-header{background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;padding:1.1rem 1.35rem}
		.section-note{background:#f8fbff;border:1px solid #d7e3f0;border-radius:14px;padding:1rem}
	</style>
	<script>
	$(function(){
		$("#fecIni").datepicker({ dateFormat: "yy-mm-dd" });
		$("#fecFin").datepicker({ dateFormat: "yy-mm-dd" });
	});
	function generaExel(){
		var F = document._FORM_;
		var fini = F.fecIni.value;
		var ffin = F.fecFin.value;
		var ok = true;
		if(fini.trim() == ""){
			alert("Debe seleccionar la fecha desde que se genera el excel");
			ok = false;
		}
		if(ffin.trim() == ""){
			alert("Debe seleccionar la fecha hasta que se generara el excel");
			ok = false;
		}
		if(ok == true) F.submit();
	}
	</script>
</head>
<body>
	<div class="page-shell">
		<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
			<div>
				<div class="small text-secondary">VGM &gt; Exportaci&oacute;n</div>
				<h1 class="h3 mb-0">Excel de ventas para Softland</h1>
			</div>
			<a href="../main.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Volver</a>
		</div>

		<form name="_FORM_" id="_FORM_" method="get" action="vgm_excel2.php" target="_blank">
			<input type="hidden" name="accion" value="OK">
			<div class="card panel">
				<div class="panel-header">
					<div class="fw-semibold"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Generaci&oacute;n de Excel</div>
					<div class="small mt-1">Se mantiene el flujo original hacia <strong>vgm_excel2.php</strong> con los mismos par&aacute;metros <code>emp</code>, <code>fecIni</code> y <code>fecFin</code>.</div>
				</div>
				<div class="card-body p-4">
					<div class="section-note mb-4 small text-secondary">
						Seleccione la empresa y el rango de fechas para abrir el Excel en una nueva ventana, igual que en el comportamiento legacy.
					</div>
					<div class="row g-3 align-items-end">
						<div class="col-12">
							<label class="form-label fw-semibold">Empresa</label>
							<select name="emp" class="form-select">
								<?php foreach($empresas as $value => $label): ?>
								<option value="<?php echo h($value); ?>"<?php echo ($emp === $value ? ' selected' : ''); ?>><?php echo h($label); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-6">
							<label class="form-label fw-semibold" for="fecIni">Desde</label>
							<input type="text" id="fecIni" name="fecIni" class="form-control" value="<?php echo h($fini); ?>" readonly>
						</div>
						<div class="col-md-6">
							<label class="form-label fw-semibold" for="fecFin">Hasta</label>
							<input type="text" id="fecFin" name="fecFin" class="form-control" value="<?php echo h($ffin); ?>" readonly>
						</div>
					</div>
				</div>
				<div class="card-footer bg-white border-0 px-4 pb-4 pt-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
					<div class="small text-secondary">El archivo se genera en una nueva pesta&ntilde;a para no interrumpir la pantalla actual.</div>
					<div class="d-flex gap-2">
						<button type="button" class="btn btn-primary" onclick="generaExel();"><i class="bi bi-download me-2"></i>Generar Excel</button>
						<a href="../main.php" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
					</div>
				</div>
			</div>
		</form>
	</div>
</body>
</html>