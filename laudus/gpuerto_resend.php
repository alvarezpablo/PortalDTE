<?php
	include("../include/config.php");
	include("../include/ver_aut.php");
	include("../include/ver_aut_adm_super.php");
	include("../include/db_lib.php");
	include("class_laudus.php");

	function reenviarXML($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){
		global $_URL_WS_DTE_, $_LINK_BASE_WS;
		$service = $_LINK_BASE_WS . "OpenDTEWS/services/ReenviaEmailDTE?wsdl";
		$aParam = array();
		$aParam["emisor"] = $sEmisor;
		$aParam["folioDTE"] = $nFolio;
		$aParam["tipoDTE"] = $nTipoDTE;
		$aParam["tipoEnvio"] = $nTipoEnvio;
		$aParam["destinatario"] = $sDestinatario;
		try {
			$client = new SoapClient($service, $aParam);
			$result = $client->reenviaEmailDTE($aParam);
			$resp = $result->return;
			$xmlget = simplexml_load_string($resp);
			if(trim($xmlget->Codigo) == "1") return "XML Enviado";
			return (string)$xmlget->Glosa;
		} catch (Exception $e) {
			return "Email no enviado: $sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario.";
		}
	}

	function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }
	function requestValue($key, $default = ""){
		return isset($_GET[$key]) ? trim((string)$_GET[$key]) : $default;
	}

	$accion = requestValue("op");
	$anio = requestValue("anio");
	$mes = requestValue("mes");
	$email = requestValue("email");
	if(strpos($email, "@") === false) $email = "";

	$fechaActual = getdate();
	if($anio === "") $anio = (string)$fechaActual['year'];
	if($mes === "") $mes = date("m");

	$resultados = array();
	$mensajeProceso = "";
	if($accion == "R"){
		$conn = conn();
		$sql = "SELECT tipo_docu, folio_dte, rut_emis_dte, digi_emis_dte, rut_rec_dte, dig_rec_dte, nom_rec_dte FROM dte_enc WHERE codi_empr IN (SELECT codi_empr FROM empresa WHERE is_gpuerto = 1) AND to_char(to_date(fec_emi_dte,'YYYY-MM'),'YYYY-MM') = '$anio-$mes' ORDER BY rut_emis_dte, rut_rec_dte, tipo_docu, folio_dte ";
		$result = rCursor($conn, $sql);
		while(!$result->EOF){
			$rutEmisor = trim($result->fields["rut_emis_dte"]);
			$dvEmisor = trim($result->fields["digi_emis_dte"]);
			$tipoDocu = trim($result->fields["tipo_docu"]);
			$folioDte = trim($result->fields["folio_dte"]);
			$rutRecep = trim($result->fields["rut_rec_dte"]);
			$dvRecep = trim($result->fields["dig_rec_dte"]);
			$nomClie = trim($result->fields["nom_rec_dte"]);
			$emailDest = "dte@duemint.com";
			$msgCopia = "";
			if($email != "") $msgCopia = reenviarXML($rutEmisor . "-" . $dvEmisor, $folioDte, $tipoDocu, "XML", $email);
			$msg = reenviarXML($rutEmisor . "-" . $dvEmisor, $folioDte, $tipoDocu, "XML", $emailDest);
			$resultados[] = array(
				"emisor" => $rutEmisor . "-" . $dvEmisor,
				"receptor" => $rutRecep . ($dvRecep !== "" ? "-" . $dvRecep : ""),
				"nombre" => $nomClie,
				"tipo" => $tipoDocu,
				"folio" => $folioDte,
				"destino" => $emailDest . ($email != "" ? " y " . $email : ""),
				"resultado" => $msg,
				"copia" => $msgCopia,
				"ok" => ($msg == "XML Enviado")
			);
			sleep(1);
			$result->MoveNext();
		}
		$mensajeProceso = (count($resultados) > 0)
			? "Proceso ejecutado para el periodo " . $anio . "-" . $mes . " sobre " . count($resultados) . " documento(s)."
			: "No se encontraron documentos GPUERTO para el periodo seleccionado.";
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Grupo Puerto</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
		.page-shell{max-width:1180px;margin:0 auto;padding:1rem}
		.panel{border:0;border-radius:18px;box-shadow:0 18px 40px rgba(15,23,42,.12);overflow:hidden;margin-bottom:1rem}
		.panel-header{background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;padding:1.1rem 1.35rem}
		.section-note{background:#f8fbff;border:1px solid #d7e3f0;border-radius:14px;padding:1rem}
		.table thead th{background:#001f3f;color:#fff;white-space:nowrap}
		.table tbody td{vertical-align:middle}
		.table tbody tr.error-row{background:#fdecef}
	</style>
	<script>
	function desac(){
		if(document._FFORM && document._FFORM.b) document._FFORM.b.disabled = true;
	}
	</script>
</head>
<body>
	<div class="page-shell">
		<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
			<div>
				<div class="small text-secondary">Laudus &gt; Reenv&iacute;o</div>
				<h1 class="h3 mb-0">Reenviar periodo a Duemint</h1>
			</div>
			<a href="../main.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Volver</a>
		</div>

		<div class="card panel">
			<div class="panel-header">
				<div class="fw-semibold"><i class="bi bi-send-check me-2"></i>Filtros de proceso</div>
				<div class="small mt-1">Se conserva el mismo flujo GET del formulario y el reenv&iacute;o SOAP hacia <strong>dte@duemint.com</strong>, con copia opcional adicional.</div>
			</div>
			<div class="card-body p-4">
				<div class="section-note mb-4 small text-secondary">
					El proceso mantiene el contrato de <code>gpuerto_resend.php?op=R&amp;anio=...&amp;mes=...&amp;email=...</code> para no alterar el uso existente.
				</div>
				<form name="_FFORM" action="gpuerto_resend.php" method="get" onsubmit="desac();" class="row g-3 align-items-end">
					<input type="hidden" name="op" value="R">
					<div class="col-md-4">
						<label class="form-label fw-semibold">A&ntilde;o</label>
						<select name="anio" class="form-select">
							<?php for($i = (int)$fechaActual['year']; $i > 2021; $i--): ?>
							<option value="<?php echo $i; ?>"<?php echo ($anio == (string)$i ? ' selected' : ''); ?>><?php echo $i; ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="col-md-4">
						<label class="form-label fw-semibold">Mes</label>
						<select name="mes" class="form-select">
							<?php
							$meses = array(
								"01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril",
								"05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto",
								"09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"
							);
							foreach($meses as $value => $label): ?>
							<option value="<?php echo h($value); ?>"<?php echo ($mes === $value ? ' selected' : ''); ?>><?php echo h($label); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-4">
						<label class="form-label fw-semibold">Email para copia opcional</label>
						<input type="text" name="email" class="form-control" maxlength="150" value="<?php echo h($email); ?>" placeholder="usuario@dominio.cl">
					</div>
					<div class="col-12 d-flex justify-content-end gap-2">
						<button type="submit" name="b" class="btn btn-primary"><i class="bi bi-arrow-repeat me-2"></i>Reenviar</button>
						<a href="gpuerto_resend.php" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-2"></i>Limpiar</a>
					</div>
				</form>
			</div>
		</div>

		<?php if($accion == "R"): ?>
		<div class="card panel">
			<div class="panel-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
				<div>
					<div class="fw-semibold"><i class="bi bi-list-check me-2"></i>Resultado del proceso</div>
					<div class="small mt-1"><?php echo h($mensajeProceso); ?></div>
				</div>
				<span class="badge rounded-pill text-bg-light text-primary-emphasis"><?php echo count($resultados); ?> registro(s)</span>
			</div>
			<div class="card-body p-4">
				<?php if(count($resultados) > 0): ?>
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead>
							<tr>
								<th>Emisor</th>
								<th>Receptor</th>
								<th>Nombre receptor</th>
								<th>Tipo</th>
								<th>Folio</th>
								<th>Email destino</th>
								<th>Resultado</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($resultados as $fila): ?>
							<tr class="<?php echo ($fila['ok'] ? '' : 'error-row'); ?>">
								<td><?php echo h($fila['emisor']); ?></td>
								<td><?php echo h($fila['receptor']); ?></td>
								<td><?php echo h($fila['nombre']); ?></td>
								<td><?php echo h($fila['tipo']); ?></td>
								<td><?php echo h($fila['folio']); ?></td>
								<td><?php echo h($fila['destino']); ?></td>
								<td>
									<div><?php echo h($fila['resultado']); ?></div>
									<?php if($fila['copia'] !== ""): ?><div class="small text-secondary">Copia: <?php echo h($fila['copia']); ?></div><?php endif; ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php else: ?>
				<div class="alert alert-secondary mb-0"><i class="bi bi-inbox me-2"></i><?php echo h($mensajeProceso); ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</body>
</html>