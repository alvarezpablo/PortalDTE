<?php
	include("../include/config.php");
	include("../include/ver_aut.php");
	include("../include/db_lib.php");

	function reenviarXMLNew($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){
		global $_LINK_BASE_WS;
		$serviceUrl = $_LINK_BASE_WS . "OpenDTEWS/services/ReenviaEmailDTE?wsdl";
		try {
			$client = new SoapClient($serviceUrl);
			$params = array('emisor' => $sEmisor, 'folioDTE' => $nFolio, 'tipoDTE' => $nTipoDTE, 'tipoEnvio' => $nTipoEnvio, 'destinatario' => $sDestinatario);
			$result = $client->reenviaEmailDTE($params);
			var_dump($result);
			return $result;
		} catch (SoapFault $e) {
			echo "Error: " . $e->getMessage();
			return $e;
		}
	}

	function reenviarXMLOld($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){
		global $_URL_WS_DTE_, $_LINK_BASE_WS;
		$service = $_LINK_BASE_WS . "OpenDTEWS/services/ReenviaEmailDTE?wsdl";
		$aParam = array();
		$aParam["emisor"] = $sEmisor;
		$aParam["folioDTE"] = $nFolio;
		$aParam["tipoDTE"] = $nTipoDTE;
		$aParam["tipoEnvio"] = $nTipoEnvio;
		$aParam["destinatario"] = $sDestinatario;
		sleep(3);
		try {
			$client = new SoapClient($service, $aParam);
			$result = $client->reenviaEmailDTE($aParam);
			return $result;
		} catch (Exception $e) {
			return $e;
		}
	}

	function reenviarXML($sEmisor, $nFolio, $nTipoDTE, $nTipoEnvio, $sDestinatario){
		global $_URL_WS_DTE_;
		try {
			$xmlRequest = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
			<soapenv:Envelope xmlns:soapenv=\"http://www.w3.org/2003/05/soap-envelope\" xmlns:ns=\"http://ws.opendte.cl\">
			   <soapenv:Header/>
			   <soapenv:Body>
				  <ns:reenviaEmailDTE>
					 <ns:emisor>$sEmisor</ns:emisor>
					 <ns:folioDTE>$nFolio</ns:folioDTE>
					 <ns:tipoDTE>$nTipoDTE</ns:tipoDTE>
					 <ns:tipoEnvio>$nTipoEnvio</ns:tipoEnvio>
					 <ns:destinatario>$sDestinatario</ns:destinatario>
				  </ns:reenviaEmailDTE>
			   </soapenv:Body>
			</soapenv:Envelope>";
			$url = 'http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/ReenviaEmailDTE.ReenviaEmailDTEHttpsEndpoint/';
			$headers = array('Content-Type: application/soap+xml; charset=utf-8', 'Content-Length: ' . strlen($xmlRequest));
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
			$aResp = explode("<ns:return>", $response);
			$aResp = explode("</ns:return>", $aResp[1]);
			$response = html_entity_decode($aResp[0] . "");
			return $response;
		} catch (Exception $e) {
			return "<RespuestaReenviaEmailDTE><Codigo>0</Codigo><Glosa>" . $e->getMessage() . "</Glosa></RespuestaReenviaEmailDTE>";
		}
	}

	function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }

	require_once("PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
	$conn = conn();
	$file = isset($_FILES['sFileCaf']) ? $_FILES['sFileCaf'] : null;
	$resultados = array();
	$errores = array();
	$procesoEjecutado = false;
	$mensajeProceso = "";

	if(is_array($file) && isset($file['error']) && $file['error'] == 0 && $file['name'] != "") {
		$procesoEjecutado = true;
		$inputFileName = $file['tmp_name'];
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch(Exception $e) {
			$errores[] = $e->getMessage();
		}

		if(count($errores) == 0){
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			for ($row = 1; $row <= $highestRow; $row++) {
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
				$iniDTE = trim($rowData[0][0]);
				if($iniDTE == "ENC"){
					$tipoDTE = trim($rowData[0][1]);
					$folioDTE = trim($rowData[0][2]);
					$rutEmisor = strtoupper(trim($rowData[0][4]));
					$emailCliente = trim($rowData[0][10]);
					$sDestinatario = str_replace(",", ";", $emailCliente);
					$aEmail = explode(";", $sDestinatario);
					for($i = 0; $i < sizeof($aEmail); $i++){
						$emailActual = trim($aEmail[$i]);
						if(strpos($emailActual, "@") !== false){
							$msg = reenviarXML($rutEmisor, $folioDTE, $tipoDTE, "PDF", $emailActual);
							$estado = "0";
							$xml = simplexml_load_string($msg);
							if($xml !== false && isset($xml->Codigo)) $estado = (string)$xml->Codigo;
							$resultados[] = array(
								"folio" => $folioDTE,
								"tipo" => $tipoDTE,
								"email" => $emailActual,
								"ok" => ($estado == "1"),
								"respuesta" => ($estado == "1") ? "Folio " . $folioDTE . " enviado" : "Error al enviar Folio " . $folioDTE,
								"detalle" => $msg
							);
							sleep(1);
						}
					}
				}
			}
		}
		$mensajeProceso = (count($resultados) > 0)
			? "Proceso ejecutado sobre " . count($resultados) . " destinatario(s)."
			: "No se encontraron correos v&aacute;lidos en el archivo cargado.";
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Grupo VGM</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
		.page-shell{max-width:1080px;margin:0 auto;padding:1rem}
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
				<div class="small text-secondary">VGM &gt; Reenv&iacute;o</div>
				<h1 class="h3 mb-0">Reenviar email desde archivo</h1>
			</div>
			<a href="../main.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Volver</a>
		</div>

		<div class="card panel">
			<div class="panel-header">
				<div class="fw-semibold"><i class="bi bi-upload me-2"></i>Carga de archivo</div>
				<div class="small mt-1">Se conserva el env&iacute;o POST al mismo archivo, el campo <code>sFileCaf</code> y el tama&ntilde;o m&aacute;ximo legado definido para esta carga.</div>
			</div>
			<div class="card-body p-4">
				<div class="section-note mb-4 small text-secondary">
					Use el formato vigente del m&oacute;dulo para reenviar correos por lote. El procesamiento SOAP y la lectura del Excel siguen intactos.
				</div>
				<form name="_FFORM" enctype="multipart/form-data" action="vgm_reenviar.php" method="post" onsubmit="desac();" class="row g-3 align-items-end">
					<input type="hidden" name="MAX_FILE_SIZE" value="504857600">
					<div class="col-lg-8">
						<label for="sFileCaf" class="form-label fw-semibold">Archivo Excel</label>
						<input type="file" name="sFileCaf" id="sFileCaf" class="form-control" size="25" maxlength="1000">
					</div>
					<div class="col-lg-4 d-flex justify-content-lg-end gap-2">
						<button type="submit" name="b" class="btn btn-primary"><i class="bi bi-send me-2"></i>Procesar</button>
						<a href="vgm_reenviar.php" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-2"></i>Limpiar</a>
					</div>
				</form>
				<div class="d-flex flex-wrap gap-3 mt-4 small">
					<a href="doc/formato.xls" target="_blank" class="link-primary text-decoration-none"><i class="bi bi-filetype-xls me-1"></i>Definici&oacute;n de formato</a>
					<a href="doc/ejemplo2.xlsx" target="_blank" class="link-primary text-decoration-none"><i class="bi bi-file-earmark-spreadsheet me-1"></i>Archivo de ejemplo</a>
				</div>
			</div>
		</div>

		<?php if($procesoEjecutado): ?>
		<div class="card panel">
			<div class="panel-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
				<div>
					<div class="fw-semibold"><i class="bi bi-list-check me-2"></i>Resultado del proceso</div>
					<div class="small mt-1"><?php echo $mensajeProceso; ?></div>
				</div>
				<span class="badge rounded-pill text-bg-light text-primary-emphasis"><?php echo count($resultados); ?> destinatario(s)</span>
			</div>
			<div class="card-body p-4">
				<?php if(count($errores) > 0): ?>
				<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i><?php echo h(implode(' | ', $errores)); ?></div>
				<?php endif; ?>
				<?php if(count($resultados) > 0): ?>
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead>
							<tr>
								<th>Respuesta</th>
								<th>Tipo</th>
								<th>Folio</th>
								<th>Email</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($resultados as $fila): ?>
							<tr class="<?php echo ($fila['ok'] ? '' : 'error-row'); ?>">
								<td><?php echo h($fila['respuesta']); ?></td>
								<td><?php echo h($fila['tipo']); ?></td>
								<td><?php echo h($fila['folio']); ?></td>
								<td>
									<div><?php echo h($fila['email']); ?></div>
									<div class="small text-secondary"><?php echo h($fila['detalle']); ?></div>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php elseif(count($errores) == 0): ?>
				<div class="alert alert-secondary mb-0"><i class="bi bi-inbox me-2"></i><?php echo $mensajeProceso; ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</body>
</html>