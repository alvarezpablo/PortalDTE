<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/ver_aut_adm_super.php");
include("../include/ver_emp_adm.php");
include("../include/db_lib.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }
function requestValue($key, $default = ""){
	return isset($_GET[$key]) ? trim((string)$_GET[$key]) : $default;
}

$es = requestValue("e");
$tipo = requestValue("tipo", "33");
$folio = requestValue("folio");
$rut = requestValue("rut");
$fIni = requestValue("fini", date("Y-m-d"));
$fFin = requestValue("ffin", date("Y-m-d"));
$tipoEnvio = requestValue("tipoEnvio", "xml");
$resultados = array();
$mensajeProceso = "";

if($es == "1"){
	require_once(__DIR__ . '/nusoap-0.9.5/lib/nusoap.php');
	$conn = conn();
	$sql = "SELECT D.folio_dte, D.tipo_docu, D.rut_emis_dte, D.digi_emis_dte,
					(SELECT email_contr FROM contrib_elec WHERE rut_contr = D.rut_rec_dte) as email
				FROM dte_enc D, xmldte X
				WHERE
					D.codi_empr = X.codi_empr AND
					D.folio_dte = X.folio_dte AND
					D.tipo_docu = X.tipo_docu AND
					X.est_xdte > 28 AND X.est_xdte != 77 AND
					D.codi_empr=" . $_SESSION["_COD_EMP_USU_SESS"];

	if($folio != "") $sql .= " AND D.folio_dte = '$folio' ";
	if($rut != "") $sql .= " AND D.rut_rec_dte = '$rut' ";
	if($folio != "") $sql .= " AND D.folio_dte = '$folio' ";
	if($fIni != "" && $fFin == "") $sql .= " AND D.fec_emi_dte = to_date('$fIni','YYYY-MM-DD') ";
	if($fIni == "" && $fFin != "") $sql .= " AND D.fec_emi_dte = to_date('$fFin','YYYY-MM-DD') ";
	if($fIni != "" && $fFin != "") $sql .= " AND to_date(D.fec_emi_dte,'YYYY-MM-DD') between to_date('$fIni','YYYY-MM-DD') AND to_date('$fFin','YYYY-MM-DD') ";

	$result = rCursor($conn, $sql);
	while(!$result->EOF){
		$folio_dte = trim($result->fields["folio_dte"]);
		$tipo_docu = trim($result->fields["tipo_docu"]);
		$rut_emis_dte = trim($result->fields["rut_emis_dte"]) . "-" . trim($result->fields["digi_emis_dte"]);
		$email = trim($result->fields["email"]);
		$aResp = reEnviarDTE($rut_emis_dte, $folio_dte, $tipo_docu, $tipoEnvio, $email, $conn);
		$resultados[] = array(
			"estado" => ($aResp["estado"] == "1" ? "OK" : "ERROR"),
			"tipo" => $tipo_docu,
			"folio" => $folio_dte,
			"glosa" => $aResp["glosa"],
			"ok" => ($aResp["estado"] == "1")
		);
		$result->MoveNext();
	}

	$mensajeProceso = (count($resultados) > 0)
		? "Proceso ejecutado sobre " . count($resultados) . " documento(s)."
		: "No se encontraron documentos para reenviar con los filtros indicados.";
}

$tiposDte = array(
	"33" => "Factura Electr&oacute;nica",
	"34" => "Factura No Afecta o Exenta Electr&oacute;nica",
	"39" => "Boleta Electr&oacute;nica",
	"41" => "Boleta Exenta Electr&oacute;nica",
	"43" => "Liquidaci&oacute;n Factura Electr&oacute;nica",
	"46" => "Factura de Compra Electr&oacute;nica",
	"52" => "Gu&iacute;a de Despacho Electr&oacute;nica",
	"56" => "Nota de D&eacute;bito Electr&oacute;nica",
	"61" => "Nota de Cr&eacute;dito Electr&oacute;nica",
	"110" => "Factura de Exportaci&oacute;n Electr&oacute;nica",
	"111" => "Nota de D&eacute;bito de Exportaci&oacute;n Electr&oacute;nica",
	"112" => "Nota de Cr&eacute;dito de Exportaci&oacute;n Electr&oacute;nica",
	"" => "Todos"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="shortcut icon" href="/favicon.ico">
	<title>Reenv&iacute;o DTE - Portal DTE</title>
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
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}.page-shell{max-width:1180px;margin:0 auto;padding:1rem}.topbar{display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1rem}.panel{border:1px solid rgba(15,23,42,.06);border-radius:18px;box-shadow:0 14px 32px rgba(15,23,42,.08);overflow:hidden;margin-bottom:1rem}.panel-header{background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;padding:1rem 1.25rem}.panel-note{background:#f8fbff;border:1px solid #d7e3f0;border-radius:14px;padding:.95rem 1rem}.table thead th{background:#001f3f;color:#fff;white-space:nowrap}.table tbody td{vertical-align:middle}.table tbody tr.error-row{background:#fdecef}.table tbody tr:hover{background:#f8fbff}#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
	</style>
	<script type="text/javascript">
	function desac(){ if(document._FFORM && document._FFORM.b) document._FFORM.b.disabled = true; }
	function _body_onload(){ try{ loff(); }catch(e){} }
	function _body_onunload(){ try{ lon(); }catch(e){} }
	</script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>

	<div class="page-shell">
		<div class="topbar">
			<div>
				<div class="small text-secondary">Reenv&iacute;o &gt; Documentos</div>
				<h1 class="h3 mb-0">Reenv&iacute;o manual de DTE</h1>
			</div>
			<a href="main.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Volver</a>
		</div>

		<div class="card panel">
			<div class="panel-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
				<div>
					<div class="fw-semibold"><i class="bi bi-send-check me-2"></i>Filtros de reenv&iacute;o</div>
					<div class="small mt-1">Se conserva el flujo GET del formulario y la ejecuci&oacute;n v&iacute;a SOAP al servicio existente.</div>
				</div>
				<span class="badge rounded-pill text-bg-light text-primary-emphasis">nusoap + reEnviarDTE()</span>
			</div>
			<div class="card-body p-4">
				<div class="panel-note mb-4">
					<div class="fw-semibold mb-1"><i class="bi bi-info-circle me-2"></i>Uso operativo</div>
					<div class="text-secondary small">El formulario mantiene los mismos par&aacute;metros `e`, `tipo`, `folio`, `rut`, `fini`, `ffin` y `tipoEnvio` para no alterar el flujo legacy del m&oacute;dulo.</div>
				</div>

				<form name="_FFORM" action="reenvio/reenvio.php" method="get" onsubmit="desac();" class="row g-3 align-items-end">
					<input type="hidden" name="e" value="1">
					<div class="col-lg-6">
						<label class="form-label fw-semibold">Tipo DTE</label>
						<select name="tipo" class="form-select">
							<?php foreach($tiposDte as $value => $label): ?>
							<option value="<?php echo h($value); ?>"<?php echo ($tipo === (string)$value ? ' selected' : ''); ?>><?php echo $label; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-lg-3">
						<label class="form-label fw-semibold">Folio DTE</label>
						<input type="text" name="folio" class="form-control" value="<?php echo h($folio); ?>">
					</div>
					<div class="col-lg-3">
						<label class="form-label fw-semibold">Rut receptor</label>
						<input type="text" name="rut" class="form-control" value="<?php echo h($rut); ?>" placeholder="Sin d&iacute;gito verificador">
					</div>
					<div class="col-md-4">
						<label class="form-label fw-semibold">Fecha desde</label>
						<input type="text" name="fini" class="form-control" value="<?php echo h($fIni); ?>" placeholder="YYYY-MM-DD">
					</div>
					<div class="col-md-4">
						<label class="form-label fw-semibold">Fecha hasta</label>
						<input type="text" name="ffin" class="form-control" value="<?php echo h($fFin); ?>" placeholder="YYYY-MM-DD">
					</div>
					<div class="col-md-4">
						<label class="form-label fw-semibold">Enviar</label>
						<select name="tipoEnvio" class="form-select">
							<option value="xml"<?php echo ($tipoEnvio === 'xml' ? ' selected' : ''); ?>>XML</option>
							<option value="pdf"<?php echo ($tipoEnvio === 'pdf' ? ' selected' : ''); ?>>PDF</option>
						</select>
					</div>
					<div class="col-12 d-flex flex-wrap gap-2 justify-content-end">
						<button type="submit" name="b" class="btn btn-primary"><i class="bi bi-arrow-repeat me-2"></i>Re-Enviar</button>
						<a href="reenvio/reenvio.php" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-2"></i>Limpiar</a>
					</div>
				</form>
			</div>
		</div>

		<?php if($es == "1"): ?>
		<div class="card panel">
			<div class="panel-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
				<div>
					<div class="fw-semibold"><i class="bi bi-list-check me-2"></i>Resultado del proceso</div>
					<div class="small mt-1"><?php echo h($mensajeProceso); ?></div>
				</div>
				<span class="badge rounded-pill text-bg-light text-primary-emphasis"><?php echo count($resultados); ?> respuesta(s)</span>
			</div>
			<div class="card-body p-4">
				<?php if(count($resultados) > 0): ?>
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead>
							<tr>
								<th>Estado</th>
								<th>Tipo</th>
								<th>Folio</th>
								<th>Respuesta</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($resultados as $fila): ?>
							<tr class="<?php echo ($fila['ok'] ? '' : 'error-row'); ?>">
								<td><span class="badge <?php echo ($fila['ok'] ? 'text-bg-success' : 'text-bg-danger'); ?>"><?php echo h($fila['estado']); ?></span></td>
								<td><?php echo h($fila['tipo']); ?></td>
								<td><?php echo h($fila['folio']); ?></td>
								<td><?php echo h($fila['glosa']); ?></td>
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
	<script type="text/javascript">try{ lsetup(); }catch(e){}</script>
</body>
</html>

<?php
function reEnviarDTE($rutEmpr, $folio, $tipo, $tipoEnvio, $destinatario, $conn){
	try {
		$parametros = array();
		$parametros["emisor"] = $rutEmpr;
		$parametros["folioDTE"] = $folio;
		$parametros["tipoDTE"] = $tipo;
		$parametros["tipoEnvio"] = $tipoEnvio;
		$parametros["destinatario"] = $destinatario;

		$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
		$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
		$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
		$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
		$client = new nusoap_client('http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/ReenviaEmailDTE?wsdl', 'wsdl', $proxyhost, $proxyport, $proxyusername, $proxypassword);
		$client->setEndpoint("http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/ReenviaEmailDTE.ReenviaEmailDTEHttpSoap11Endpoint/");
		$err = $client->getError();
		if($err){
			return array('estado' => 0, 'glosa' => 'Error al consumir WS al procesar rut emisor ' . $rutEmpr . ', tipo ' . $tipo . ' y folio ' . $folio);
		}

		$result = $client->call('reenviaEmailDTE', array('parameters' => $parametros), '', '', false, true);
		$estado = 0;
		$resulGlosa = 'Formato respuesta incorrecto';

		if(is_array($result)){
			foreach($result as $valor){
				$resXML = new SimpleXMLElement("<?xml version='1.0' encoding='ISO-8859-1'?>" . $valor);
				$estado = $resXML->Codigo;
				$resulGlosa = $resXML->Glosa;
				break;
			}
		}

		if($estado == '1')
			return array('estado' => '1', 'glosa' => $resulGlosa);

		return array('estado' => '0', 'glosa' => 'Error al procesar rut emisor ' . $rutEmpr . ', tipo ' . $tipo . ' y folio ' . $folio . '. ' . $resulGlosa);
	} catch (Exception $e) {
		return array('estado' => 0, 'glosa' => 'Error Excepci&oacute;n capturada al procesar rut emisor ' . $rutEmpr . ', tipo ' . $tipo . ' y folio ' . $folio . ' ' . $e->getMessage());
	}
}
?>