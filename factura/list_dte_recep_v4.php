<?php 
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");	

	include("../include/config.php");
	include("../include/db_lib.php");  
	include("../include/ver_aut.php");
    include("../include/ver_emp_adm.php"); 

		function h($value){
			return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
		}

		function q($value){
			return rawurlencode((string)$value);
		}

		function request_value($name){
			return isset($_GET[$name]) ? trim($_GET[$name]) : "";
		}

		function js_sq($value){
			$value = str_replace(array("\\", "'", "\r", "\n"), array("\\\\", "\\'", "\\r", "\\n"), (string)$value);
			return "'" . $value . "'";
		}

		$orden = request_value("orden");	// orden campo
		$descAsc = request_value("orni"); // orden nivel

		if($descAsc == "") $descAsc = "1";
		if($orden == "") $orden = "fecha_recep";	
		elseif($orden == "1") $orden = "fact_ref";
		elseif($orden == "2") $orden = "fec_emi_doc";
		elseif($orden == "3") $orden = "rut_rec_dte";
		elseif($orden == "4") $orden = "mont_tot_dte";
		else $orden = "fecha_recep";

		if($descAsc == "1") $descAsc = "DESC";
		elseif($descAsc == "2") $descAsc = "ASC";
		else $descAsc = "DESC";

		$tipo = request_value("tipo");
		$folio = request_value("folio");
		$fecha1 = request_value("fecha1");
		$fecha2 = request_value("fecha2");
		$fechac1 = request_value("fechac1");
		$fechac2 = request_value("fechac2");
		$rutFiltro = request_value("rut");
		$rut = $rutFiltro;
		$pagina = request_value("pagina");
		if($pagina != "" && (!ctype_digit($pagina) || intval($pagina) < 1)) $pagina = "";

	if($rut != ""){
		$aRut = explode("-",$rut);
		$rut = $aRut[0];
	}

		$AAR = request_value("AAR");		// Acuse de recibo ok
		$SAR = request_value("SAR");		// sin acuse de recibo
		$AAC = request_value("AAC");		// acptado comercialmente
		$RAC = request_value("RAC");		// rechazado comercialmente
		$SAC = request_value("SAC");		// sin respuesta comercial
		$CRM = request_value("CRM");		// con recibo de mercaderia ACEPTADO
		$RRM = request_value("RRM");		// con recibo de mercaderia RECHAZADO
		$SRM = request_value("SRM");		// sin recibo de mercaderia

		$qrstring = "&tipo=" . q($tipo);
		$qrstring .= "&folio=" . q($folio);
		$qrstring .= "&fecha1=" . q($fecha1);
		$qrstring .= "&fecha2=" . q($fecha2);
		$qrstring .= "&fechac1=" . q($fechac1);
		$qrstring .= "&fechac2=" . q($fechac2);
		$qrstring .= "&rut=" . q($rut);
		$qrstring .= "&AAR=" . q($AAR);
		$qrstring .= "&SAR=" . q($SAR);
		$qrstring .= "&AAC=" . q($AAC);
		$qrstring .= "&RAC=" . q($RAC);
		$qrstring .= "&SAC=" . q($SAC);
		$qrstring .= "&CRM=" . q($CRM);
		$qrstring .= "&RRM=" . q($RRM);	
		$qrstring .= "&SRM=" . q($SRM);	

	$fleCarga = "";
	$fleFolio = "";
	$fleFech = "";
	$fleRut = "";
	$fleTotal = "";

	$descAscCarga = "1";
	if($orden == "fecha_recep"){
		$fleCarga = "<i class='bi bi-arrow-up'></i>";
		if($descAsc == "DESC") {
			$descAscCarga = "2";
			$fleCarga = "<i class='bi bi-arrow-down'></i>";
		}
	}
	$descAscFolio = "1";
	if($orden == "fact_ref"){
		$fleFolio = "<i class='bi bi-arrow-up'></i>";
		if($descAsc == "DESC") {
			$descAscFolio = "2";
			$fleFolio = "<i class='bi bi-arrow-down'></i>";
		}
	}
	$descAscFech = "1";
	if($orden == "fec_emi_doc"){
		$fleFech = "<i class='bi bi-arrow-up'></i>";
		if($descAsc == "DESC"){
			$descAscFech = "2";
			$fleFech = "<i class='bi bi-arrow-down'></i>";
		}
	}
	$descAscRut = "1";
	if($orden == "rut_rec_dte"){
		$fleRut = "<i class='bi bi-arrow-up'></i>";
		if($descAsc == "DESC"){
			$descAscRut = "2";
			$fleRut = "<i class='bi bi-arrow-down'></i>";
		}
	}
	$descAscTotal = "1";
	if($orden == "mont_tot_dte"){
		$fleTotal = "<i class='bi bi-arrow-up'></i>";
		if($descAsc == "DESC"){ 
			$descAscTotal = "2";
			$fleTotal = "<i class='bi bi-arrow-down'></i>";
		}
	}

	$qrsCarga = $qrstring . "&orden=&orni=" . $descAscCarga;
	$qrsFolio = $qrstring . "&orden=1&orni=" . $descAscFolio;
	$qrsFech = $qrstring . "&orden=2&orni=" . $descAscFech;
	$qrsRut = $qrstring . "&orden=3&orni=" . $descAscRut;
	$qrsTotal = $qrstring . "&orden=4&orni=" . $descAscTotal;

	function poneTipo($tipo_docu){
		switch ($tipo_docu) {
			case 33: $sEstadoDte = "FA.Elect"; break;
			case 34: $sEstadoDte = "FE.Elect"; break;
			case 39: $sEstadoDte = "BA.Elect"; break;
			case 41: $sEstadoDte = "BE.Elect"; break;
			case 43: $sEstadoDte = "LQ.Elect"; break;
			case 46: $sEstadoDte = "FC.Elect"; break;
			case 52: $sEstadoDte = "GD.Elect"; break;
			case 56: $sEstadoDte = "ND.Elect"; break;
			case 61: $sEstadoDte = "NC.Elect"; break;
			case 110: $sEstadoDte = "FEE.Elect"; break;
			case 111: $sEstadoDte = "NDE.Elect"; break;
			case 112: $sEstadoDte = "NCE.Elect"; break;
			default: $sEstadoDte = "";
		}
		if ($sEstadoDte == "")
			$sEstadoDte = $tipo_docu;
		else
			$sEstadoDte = "$sEstadoDte ($tipo_docu)";
		return $sEstadoDte;
	}
?>
<?php
	$conn = conn();
		$fech_ahora = 0;
		$fech_update_sii1 = "";
		$fech_update_sii2 = 0;
		$fech_update_sii3 = "";
		$fech_update_sii4 = 0;
		$fech_update_sii = "";

	$sql = "select to_char(now(),'yyyymmddHH24MI') fech_ahora";
	$result2 = rCursor($conn, $sql);
	if(!$result2->EOF) {
		$fech_ahora = floatval(trim($result2->fields["fech_ahora"]));
	}

	$sql = "select to_char(fech_update_sii,'dd-mm-yyyy HH24:MI') fech_update_sii, to_char(fech_update_sii,'yyyymmddHH24MI') fech_update_sii2 from dte_sii_no_openb
			where codi_empr = '" . str_replace("'","''",$_SESSION["_COD_EMP_USU_SESS"]) . "' and fech_update_sii is not null order by fech_update_sii desc limit 1";
	$result2 = rCursor($conn, $sql);
	if(!$result2->EOF) {
		$fech_update_sii1 = trim($result2->fields["fech_update_sii"]);
		$fech_update_sii2 = trim($result2->fields["fech_update_sii2"]);
		if($fech_update_sii2 == "") $fech_update_sii2 = 0;
	}

	$sql = "select to_char(fech_update_sii,'dd-mm-yyyy HH24:MI') fech_update_sii, to_char(fech_update_sii,'yyyymmddHH24MI') fech_update_sii2 from documentoscompras_temp
			where codi_empr = '" . str_replace("'","''",$_SESSION["_COD_EMP_USU_SESS"]) . "' and fech_update_sii is not null order by fech_update_sii desc limit 1";
	$result2 = rCursor($conn, $sql);
	if(!$result2->EOF) {
		$fech_update_sii3 = trim($result2->fields["fech_update_sii"]);
		$fech_update_sii4 = floatval(trim($result2->fields["fech_update_sii2"]));
		if($fech_update_sii4 == "") $fech_update_sii4 = 0;
	}

	if($fech_update_sii4 > $fech_update_sii2)
		$fech_update_sii = $fech_update_sii3;
	else
		$fech_update_sii = $fech_update_sii1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DTE Recibidos - OpenDTE</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
        }
        body { background-color: #f8f9fa; }
        .card { border-radius: 0.5rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075); }
        .card-header { background-color: var(--primary-color); color: white; font-weight: 600; }
        .table-container { max-height: 60vh; overflow-y: auto; }
        .table thead th { position: sticky; top: 0; background-color: #343a40; color: white; z-index: 10; white-space: nowrap; }
        .table tbody tr:hover { background-color: rgba(13, 110, 253, 0.1); }
        .btn-action { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
        .badge-status { font-size: 0.7rem; }
        .filter-label { font-weight: 500; font-size: 0.875rem; }
        .form-check-inline { margin-right: 0.5rem; }
        .loading-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5); z-index: 9999; display: none;
            justify-content: center; align-items: center;
        }
        .loading-overlay.show { display: flex; }
        .spinner-text { color: white; margin-left: 1rem; font-size: 1.2rem; }
        a.sort-link { color: white; text-decoration: none; }
        a.sort-link:hover { color: #ccc; }
    </style>
</head>
<body class="p-3">

<!-- Loading Overlay -->
<div id="divLoading" class="loading-overlay">
    <div class="spinner-border text-light" role="status"></div>
    <span class="spinner-text">Actualizando, por favor espere...</span>
</div>

<!-- Modal Actualizar SII -->
<div class="modal fade" id="modalActualizaSII" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-cloud-download"></i> Actualizar Registro de Compra SII</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
	                    <i class="bi bi-info-circle"></i> &Uacute;ltima actualizaci&oacute;n: <strong><?php echo h($fech_update_sii); ?></strong>
                </div>
                <form id="_FORMAJAX">
                    <?php $anio = date("Y"); $mes = date("m"); ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Per&iacute;odo a Actualizar (A&ntilde;o-Mes):</label>
                        <div class="row">
                            <div class="col-6">
                                <select name="sanio" id="sanio" class="form-select">
	                                    <option value="<?php echo h($anio); ?>" selected><?php echo h($anio); ?></option>
                                    <?php for($i=$anio-1; $i > $anio-20; $i--){ echo "<option value='" . $i . "'>" . $i . "</option>\n"; } ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <select name="smes" id="smes" class="form-select">
                                    <?php
                                    for($i=1; $i < 13; $i++){
                                        $sel = ($i == $mes) ? "selected" : "";
                                        $m = ($i < 10) ? "0" . $i : $i;
                                        echo "<option value='" . $m . "' " . $sel . ">" . $m . "</option>\n";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="actualizaRegistro();"><i class="bi bi-cloud-download"></i> Actualizar SII</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Responder DTE -->
<div class="modal fade" id="modalResponder" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-reply"></i> Responder DTE</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="recibidox();"></button>
            </div>
            <div class="modal-body">
                <form id="_FENVACT">
                    <input type="hidden" name="folio_dte" id="folio_dte">
                    <input type="hidden" name="tipo_docu" id="tipo_docu">
                    <input type="hidden" name="rut_rec_dte" id="rut_rec_dte">
                    <input type="hidden" name="dig_rec_dte" id="dig_rec_dte">

                    <div class="mb-3" id="sRespuestaMerca1">
                        <label class="form-label fw-bold">Respuesta a Recibo de Mercader&iacute;as</label>
                        <select name="sRespuestaMerca" id="sRespuestaMerca" class="form-select">
                            <option value="">Seleccione Respuesta de Mercader&iacute;as</option>
                            <option value="ERM">Otorga Recibo de Mercader&iacute;as o Servicios</option>
                            <option value="RFP">Reclamo por Falta Parcial de Mercader&iacute;as</option>
                            <option value="RFT">Reclamo por Falta Total de Mercader&iacute;as</option>
                        </select>
                        <div id="sRespuestaMerca3" class="form-text text-muted"></div>
                    </div>

                    <div class="mb-3" id="sRespuesta1">
                        <label class="form-label fw-bold">Respuesta a Contenido del Documento</label>
                        <select name="sRespuesta" id="sRespuesta" class="form-select">
                            <option value="">Seleccione Respuesta al Contenido</option>
                            <option value="ACD">Acepta Contenido del Documento</option>
                            <option value="RCD">Reclamo al Contenido del Documento</option>
                        </select>
                        <div id="sRespuesta3" class="form-text text-muted"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="recibidox();">Cancelar</button>
                <button type="button" class="btn btn-primary" id="botonEnvio" onclick="enviarRespSII();"><i class="bi bi-send"></i> Enviar</button>
            </div>
        </div>
    </div>
</div>

<!-- Formulario de B&uacute;squeda -->
<div class="card mb-4">
    <div class="card-header">
        <i class="bi bi-search"></i> B&uacute;squeda de DTE Recibidos
    </div>
    <div class="card-body">
	        <form name="_BUSCA" id="_BUSCA" method="get" action="list_dte_recep_v4.php" onsubmit="return valida();">
            <div class="row g-3">
                <!-- Tipo DTE -->
                <div class="col-md-4">
                    <label class="filter-label">Tipo DTE</label>
                    <select name="tipo" class="form-select">
                        <option value="33">Factura Electr&oacute;nica</option>
                        <option value="34">Factura No Afecta o Exenta Electr&oacute;nica</option>
                        <option value="39">Boleta Electr&oacute;nica</option>
                        <option value="41">Boleta Exenta Electr&oacute;nica</option>
                        <option value="43">Liquidaci&oacute;n Factura Electr&oacute;nica</option>
                        <option value="46">Factura de Compra Electr&oacute;nica</option>
                        <option value="52">Gu&iacute;a de Despacho Electr&oacute;nica</option>
                        <option value="56">Nota de D&eacute;bito Electr&oacute;nica</option>
                        <option value="61">Nota de Cr&eacute;dito Electr&oacute;nica</option>
                        <option value="110">Factura de Exportaci&oacute;n Electr&oacute;nica</option>
                        <option value="111">Nota de D&eacute;bito de Exportaci&oacute;n Electr&oacute;nica</option>
                        <option value="112">Nota de Cr&eacute;dito de Exportaci&oacute;n Electr&oacute;nica</option>
                        <option value="">Todos</option>
                    </select>
                    <?php if($_GET){ ?>
	                    <script>document._BUSCA.tipo.value = "<?php echo h($tipo); ?>";</script>
                    <?php } ?>
                </div>

                <!-- Folio -->
                <div class="col-md-2">
                    <label class="filter-label">Folio DTE</label>
	                    <input type="text" name="folio" class="form-control" maxlength="18" value="<?php echo h($folio); ?>">
                </div>

                <!-- Rut Emisor -->
                <div class="col-md-2">
                    <label class="filter-label">Rut Emisor</label>
	                    <input type="text" name="rut" class="form-control" maxlength="10" value="<?php echo h($rutFiltro); ?>" placeholder="12345678-9">
                </div>

                <!-- Fecha Emisi&oacute;n -->
                <div class="col-md-4">
                    <label class="filter-label">Fecha Emisi&oacute;n</label>
                    <div class="input-group">
	                        <input type="text" name="fecha1" id="fecha1" class="form-control" placeholder="Desde" value="<?php echo h($fecha1); ?>" autocomplete="off">
                        <span class="input-group-text">a</span>
	                        <input type="text" name="fecha2" id="fecha2" class="form-control" placeholder="Hasta" value="<?php echo h($fecha2); ?>" autocomplete="off">
                    </div>
                </div>

                <!-- Fecha Recepci&oacute;n -->
                <div class="col-md-4">
                    <label class="filter-label">Fecha Recepci&oacute;n</label>
                    <div class="input-group">
	                        <input type="text" name="fechac1" id="fechac1" class="form-control" placeholder="Desde" value="<?php echo h($fechac1); ?>" autocomplete="off">
                        <span class="input-group-text">a</span>
	                        <input type="text" name="fechac2" id="fechac2" class="form-control" placeholder="Hasta" value="<?php echo h($fechac2); ?>" autocomplete="off">
                    </div>
                </div>

                <!-- Filtros de Estado -->
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="filter-label">Acuse de Recibo</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="AAR" value="1" id="chkAAR" checked>
                                    <label class="form-check-label" for="chkAAR">Generado</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="SAR" value="1" id="chkSAR" checked>
                                    <label class="form-check-label" for="chkSAR">No Generado</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">Respuesta Comercial</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="AAC" value="1" id="chkAAC" checked>
                                    <label class="form-check-label" for="chkAAC">Aceptado</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="RAC" value="1" id="chkRAC" checked>
                                    <label class="form-check-label" for="chkRAC">Rechazado</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="SAC" value="1" id="chkSAC" checked>
                                    <label class="form-check-label" for="chkSAC">No Generado</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">Recibo de Mercader&iacute;a</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="CRM" value="1" id="chkCRM" checked>
                                    <label class="form-check-label" for="chkCRM">Aceptado</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="RRM" value="1" id="chkRRM" checked>
                                    <label class="form-check-label" for="chkRRM">Rechazado</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="SRM" value="1" id="chkSRM" checked>
                                    <label class="form-check-label" for="chkSRM">No Generado</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($_GET){ ?>
            <script>
                document.getElementById('chkAAR').checked = <?php echo ($AAR == "1") ? "true" : "false"; ?>;
                document.getElementById('chkSAR').checked = <?php echo ($SAR == "1") ? "true" : "false"; ?>;
                document.getElementById('chkAAC').checked = <?php echo ($AAC == "1") ? "true" : "false"; ?>;
                document.getElementById('chkRAC').checked = <?php echo ($RAC == "1") ? "true" : "false"; ?>;
                document.getElementById('chkSAC').checked = <?php echo ($SAC == "1") ? "true" : "false"; ?>;
                document.getElementById('chkCRM').checked = <?php echo ($CRM == "1") ? "true" : "false"; ?>;
                document.getElementById('chkRRM').checked = <?php echo ($RRM == "1") ? "true" : "false"; ?>;
                document.getElementById('chkSRM').checked = <?php echo ($SRM == "1") ? "true" : "false"; ?>;
            </script>
            <?php } ?>

            <div class="row mt-3">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
                    <button type="button" class="btn btn-outline-success" onclick="bajarExcel();"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                    <a href="list_dte_recep_v4.php" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Limpiar</a>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalActualizaSII"><i class="bi bi-cloud-download"></i> Actualizar desde SII</button>
                    <button type="button" class="btn btn-warning" onclick="norecibido();"><i class="bi bi-exclamation-triangle"></i> No recibidos en OpenB</button>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <a href="manual_reg_compra.pdf" target="_blank" class="btn btn-link"><i class="bi bi-book"></i> Manual de Uso</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Resultados -->
<?php if($_GET){ ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-table"></i> Resultados</span>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table table-striped table-hover table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Operaciones</th>
                        <th>Tipo</th>
	                        <th><a href="list_dte_recep_v4.php?a=1<?php echo h($qrsFolio); ?>" class="sort-link">Folio <?php echo $fleFolio; ?></a></th>
	                        <th><a href="list_dte_recep_v4.php?a=1<?php echo h($qrsFech); ?>" class="sort-link">F.Emisi&oacute;n <?php echo $fleFech; ?></a></th>
	                        <th><a href="list_dte_recep_v4.php?a=1<?php echo h($qrsCarga); ?>" class="sort-link">F.Recepci&oacute;n <?php echo $fleCarga; ?></a></th>
                        <th>F.Recep SII</th>
                        <th>F.L&iacute;mite</th>
                        <th class="text-end">Exento</th>
                        <th class="text-end">Neto</th>
                        <th class="text-end">IVA</th>
	                        <th class="text-end"><a href="list_dte_recep_v4.php?a=1<?php echo h($qrsTotal); ?>" class="sort-link">Total <?php echo $fleTotal; ?></a></th>
	                        <th><a href="list_dte_recep_v4.php?a=1<?php echo h($qrsRut); ?>" class="sort-link">Rut Emisor <?php echo $fleRut; ?></a></th>
                        <th>Emisor</th>
                    </tr>
                </thead>
                <tbody>
<?php
	$cont = " SELECT correl_doc AS t ";
	$campos = "SELECT
					correl_doc,
					fact_ref,
					fec_emi_doc,
					to_char(fecha_recep,'yyyy-mm-dd') fec_rece_doc,
					to_char(fech_recep_sii,'yyyy-mm-dd HH24:MI') fec_rece_doc2,
					to_char(fech_limite_sii,'yyyy-mm-dd HH24:MI') fech_limite_sii,
					to_char(fech_limite_sii,'yyyymmddHH24MI') fech_limite_sii2,
					rut_rec_dte,
					dig_rec_dte,
					nom_rec_dte,
					dir_rec_dte,
					com_rec_dte,
					mntneto_dte,
					mnt_exen_dte,
					tasa_iva_dte,
					iva_dte,
					mont_tot_dte,
					tipo_docu,
					est_doc,
					estado_sii,
					merca_dte,to_char(fech_merca_dte,'dd-mm-yyyy HH24:MI') fech_merca_dte, acuse_dte, to_char(fech_acuse_dte,'dd-mm-yyyy HH24:MI') fech_acuse_dte,
					xml_respuesta,
					xml_recibo_mercaderia,
					xml_est_res_rev ";

	$sql = "	FROM
					documentoscompras_temp
				WHERE
					codi_empr = '" . str_replace("'","''",$_SESSION["_COD_EMP_USU_SESS"]) . "' ";

	if($tipo != "")	$sql .= " AND tipo_docu = '" . str_replace("'","''",$tipo) . "'";
	if($folio != "")	$sql .= " AND CAST(fact_ref as varchar)= '" . str_replace("'","''",$folio) . "'";
	if($rut != "")	$sql .= " AND rut_rec_dte = '" . str_replace("'","''",$rut) . "'";
	if($fecha1 != "" || $fecha2 != ""){
		$_STRING_SEARCH0 = $fecha1;
		$_STRING_SEARCH1 = $fecha2;
		if($_STRING_SEARCH0 != "" && $_STRING_SEARCH1 == "")
			$_STRING_SEARCH1 = $_STRING_SEARCH0;
		elseif($_STRING_SEARCH0 == "" && $_STRING_SEARCH1 != "")
			$_STRING_SEARCH0 = $_STRING_SEARCH1;
		$sql .= " AND TO_DATE(fec_emi_doc,'YYYY-MM-DD') BETWEEN TO_DATE(('" . str_replace("'","''",$_STRING_SEARCH0) . "'),'YYYY-MM-DD') AND TO_DATE(('" . str_replace("'","''",$_STRING_SEARCH1) . "'),'YYYY-MM-DD') ";
	}
	if($fechac1 != "" || $fechac2 != ""){
		$_STRING_SEARCHC0 = $fechac1;
		$_STRING_SEARCHC1 = $fechac2;
		if($_STRING_SEARCHC0 != "" && $_STRING_SEARCHC1 == "")
			$_STRING_SEARCHC1 = $_STRING_SEARCHC0;
		elseif($_STRING_SEARCHC0 == "" && $_STRING_SEARCHC1 != "")
			$_STRING_SEARCHC0 = $_STRING_SEARCHC1;
		$sql .= " AND fecha_recep BETWEEN TO_DATE(('" . str_replace("'","''",$_STRING_SEARCHC0) . "'),'YYYY-MM-DD') AND TO_DATE(('" . str_replace("'","''",$_STRING_SEARCHC1) . "'),'YYYY-MM-DD') ";
	}

	// Filtros de Acuse de Recibo
	if($AAR == "1" && $SAR == "1") $NoAplica = "";
	else{
		if($AAR == "1") $sql .= " AND coalesce(xml_respuesta, '') != '' ";
		if($SAR == "1") $sql .= " AND coalesce(xml_respuesta, '') = '' ";
	}

	// Filtros de Recibo de Mercaderia
	if($CRM == "1" && $RRM == "1" && $SRM == "1") $NoAplica = "";
	if($CRM == "1" && $RRM == "1" && $SRM == "") $sql .= " AND trim(coalesce(merca_dte, '')) IN ('ERM','RFP','RFT') ";
	if($CRM == "1" && $RRM == "" && $SRM == "1") $sql .= " AND trim(coalesce(merca_dte, '')) IN ('ERM','') ";
	if($CRM == "" && $RRM == "1" && $SRM == "1") $sql .= " AND trim(coalesce(merca_dte, '')) IN ('RFP','RFT','') ";
	if($CRM == "" && $RRM == "" && $SRM == "1") $sql .= " AND trim(coalesce(merca_dte, '')) IN ('') ";
	if($CRM == "" && $RRM == "1" && $SRM == "") $sql .= " AND trim(coalesce(merca_dte, '')) IN ('RFP','RFT') ";
	if($CRM == "1" && $RRM == "" && $SRM == "") $sql .= " AND trim(coalesce(merca_dte, '')) IN ('ERM') ";

	// Filtros de Respuesta Comercial
	if($AAC == "1" && $RAC == "1" && $SAC == "1") $NoAplica = "";
	if($AAC == "1" && $RAC == "1" && $SAC == "") $sql .= " AND trim(coalesce(acuse_dte, '')) IN ('ACD','RCD') ";
	if($AAC == "1" && $RAC == "" && $SAC == "1") $sql .= " AND trim(coalesce(acuse_dte, '')) IN ('ACD','') ";
	if($AAC == "" && $RAC == "1" && $SAC == "1") $sql .= " AND trim(coalesce(acuse_dte, '')) IN ('RCD','') ";
	if($AAC == "" && $RAC == "" && $SAC == "1") $sql .= " AND trim(coalesce(acuse_dte, '')) IN ('') ";
	if($AAC == "" && $RAC == "1" && $SAC == "") $sql .= " AND trim(coalesce(acuse_dte, '')) IN ('RCD') ";
	if($AAC == "1" && $RAC == "" && $SAC == "") $sql .= " AND trim(coalesce(acuse_dte, '')) IN ('ACD') ";

	$campos = $campos . " " . $sql;
	$cont = $cont . " " . $sql;

	// Paginacion
	$TAMANO_PAGINA = 40;
	if (!$pagina) { $inicio = 1; $pagina = 1; $desde = 0; }
	else { $inicio = ($pagina - 1) * $TAMANO_PAGINA; $desde = ($pagina - 1) * $TAMANO_PAGINA; }
	if($inicio == 0) $inicio = 1;

	$limiteCount = $TAMANO_PAGINA * 40;
	$cont = $cont . " LIMIT " . $limiteCount . " offset " . $desde;
	$resultCount = rCursor($conn, $cont);
	$totalFilas = 0;
	while (!$resultCount->EOF) { $totalFilas++; $resultCount->MoveNext(); }

	if($totalFilas > 0){
		$campos .= " ORDER BY " . $orden . " " . $descAsc ." LIMIT " . $TAMANO_PAGINA . " offset " . $desde;
		$result = rCursor($conn, $campos);

		while (!$result->EOF) {
			$nCodDoc = trim($result->fields["correl_doc"]);
			$folio_dte  = trim($result->fields["fact_ref"]);
			$fec_emi_doc = trim($result->fields["fec_emi_doc"]);
			$fec_rece_doc = trim($result->fields["fec_rece_doc"]);
			$fec_rece_sii = trim($result->fields["fec_rece_doc2"]);
			$fech_limite_sii = trim($result->fields["fech_limite_sii"]);
			$fech_limite_sii2 = trim($result->fields["fech_limite_sii2"]);
			$merca_dte = trim($result->fields["merca_dte"]);
			$fech_merca_dte = trim($result->fields["fech_merca_dte"]);
			$acuse_dte = trim($result->fields["acuse_dte"]);
			$fech_acuse_dte = trim($result->fields["fech_acuse_dte"]);
			if($fec_rece_sii == "") $fec_rece_sii = "Actualizar SII";
			if($fech_limite_sii == "") $fech_limite_sii = "Actualizar SII";
			$rut_rec_dte = trim($result->fields["rut_rec_dte"]);
			$dig_rec_dte = trim($result->fields["dig_rec_dte"]);
			$nom_rec_dte = trim($result->fields["nom_rec_dte"]);
			$mntneto_dte = trim($result->fields["mntneto_dte"]);
			$mnt_exen_dte = trim($result->fields["mnt_exen_dte"]);
			$iva_dte = trim($result->fields["iva_dte"]);
			$mont_tot_dte = trim($result->fields["mont_tot_dte"]);
			$tipo_docu = trim($result->fields["tipo_docu"]);
			$sAcuseRecibo = trim($result->fields["xml_respuesta"]);

			if($mnt_exen_dte == "") $mnt_exen_dte = "0";
			if($mntneto_dte == "") $mntneto_dte = "0";
			if($iva_dte == "") $iva_dte = "0";
			if($mont_tot_dte == "") $mont_tot_dte = "0";

			if($fech_limite_sii2 == "") $fech_limite_sii2 = $fech_ahora;
			else $fech_limite_sii2 = floatval($fech_limite_sii2);

				$urlPdf = "../dte/view_pdf_compras.php?c=" . q($_SESSION["_COD_EMP_USU_SESS"]) . "&f=" . q($folio_dte) . "&t=" . q($tipo_docu) . "&r=" . q($rut_rec_dte . "-" . $dig_rec_dte);
				$urlXML = "../dte/view_xmlrecibido.php?rutEmi=" . q($rut_rec_dte) . "&nFolioDte=" . q($folio_dte) . "&nTipoDocu=" . q($tipo_docu);
				$urlSET = "../dte/view_setxmlrecibido.php?rutEmi=" . q($rut_rec_dte) . "&nFolioDte=" . q($folio_dte) . "&nTipoDocu=" . q($tipo_docu);
				$responderArgs = implode(",", array(
					js_sq($folio_dte),
					js_sq($tipo_docu),
					js_sq($rut_rec_dte),
					js_sq($dig_rec_dte),
					js_sq($merca_dte),
					js_sq($fech_merca_dte),
					js_sq($acuse_dte),
					js_sq($fech_acuse_dte)
				));
?>
                    <tr>
                        <td class="text-center" style="white-space: nowrap;">
	                            <a href="<?php echo h($urlPdf); ?>" target="_blank" class="btn btn-sm btn-outline-danger" title="Ver PDF"><i class="bi bi-file-pdf"></i></a>
	                            <a href="<?php echo h($urlXML); ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Ver XML"><i class="bi bi-file-code"></i></a>
	                            <a href="<?php echo h($urlSET); ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="SET XML"><i class="bi bi-file-earmark-code"></i></a>
                            <?php if($fech_ahora <= $fech_limite_sii2 && (trim($merca_dte) == "" || trim($acuse_dte) == "")){ ?>
	                            <button type="button" class="btn btn-sm btn-success" onclick="responderSII(<?php echo h($responderArgs); ?>);" title="Responder DTE"><i class="bi bi-reply"></i></button>
                            <?php } ?>
                            <br>
                            <?php
                            // Badges de estado
                            if($tipo_docu == "39" || $tipo_docu == "41" || $tipo_docu == "110" || $tipo_docu == "111" || $tipo_docu == "112"){
                                echo "<span class='badge bg-secondary badge-status' title='No Aplica'>N/A</span> ";
                            } else {
                                // Acuse de recibo
                                if($sAcuseRecibo != "") echo "<span class='badge bg-success badge-status' title='Acuse de Recibo OK'><i class='bi bi-check'></i>AR</span> ";
                                else echo "<span class='badge bg-warning text-dark badge-status' title='Sin Acuse de Recibo'><i class='bi bi-x'></i>AR</span> ";

                                // Recibo Mercaderia
                                if($merca_dte == "ERM") echo "<span class='badge bg-success badge-status' title='Recibo Mercaderia OK'><i class='bi bi-check'></i>RM</span> ";
                                elseif($merca_dte == "RFP" || $merca_dte == "RFT") echo "<span class='badge bg-danger badge-status' title='Reclamo Mercaderia'><i class='bi bi-x'></i>RM</span> ";
                                else echo "<span class='badge bg-warning text-dark badge-status' title='Sin Recibo Mercaderia'><i class='bi bi-dash'></i>RM</span> ";

                                // Respuesta comercial
                                if($acuse_dte == "ACD") echo "<span class='badge bg-success badge-status' title='Aceptado Comercialmente'><i class='bi bi-check'></i>RC</span>";
                                elseif($acuse_dte == "RCD") echo "<span class='badge bg-danger badge-status' title='Rechazado Comercialmente'><i class='bi bi-x'></i>RC</span>";
                                else echo "<span class='badge bg-warning text-dark badge-status' title='Sin Respuesta Comercial'><i class='bi bi-dash'></i>RC</span>";
                            }
                            ?>
                        </td>
	                        <td><span class="badge bg-info"><?php echo h(poneTipo($tipo_docu)); ?></span></td>
                        <td class="text-end"><?php echo number_format($folio_dte,0,',','.'); ?></td>
	                        <td><?php echo h($fec_emi_doc); ?></td>
	                        <td><?php echo h($fec_rece_doc); ?></td>
	                        <td><?php echo h($fec_rece_sii); ?></td>
	                        <td><?php echo h($fech_limite_sii); ?></td>
                        <td class="text-end"><?php echo number_format($mnt_exen_dte,0,',','.'); ?></td>
                        <td class="text-end"><?php echo number_format($mntneto_dte,0,',','.'); ?></td>
                        <td class="text-end"><?php echo number_format($iva_dte,0,',','.'); ?></td>
                        <td class="text-end fw-bold"><?php echo number_format($mont_tot_dte,0,',','.'); ?></td>
	                        <td><?php echo h($rut_rec_dte . "-" . $dig_rec_dte); ?></td>
	                        <td><?php echo h($nom_rec_dte); ?></td>
                    </tr>
<?php
			$result->MoveNext();
		}
	} else {
?>
                    <tr><td colspan="13" class="text-center py-4"><i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i><br>No hay resultados para su b&uacute;squeda</td></tr>
<?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginaci&oacute;n -->
    <?php if($totalFilas > 0){
        $total_paginas = ceil($totalFilas / $TAMANO_PAGINA);
        $paginasLista = ($total_paginas > 20) ? 20 : $total_paginas;
	        $qrstringPag = $qrstring . "&orden=" . q($orden) . "&orni=" . q($descAsc);
        $inicio_pag = floor($pagina / $paginasLista);
        if(floor($pagina / $paginasLista) == ($pagina / $paginasLista))
            $inicio_pag = $inicio_pag * $paginasLista - $paginasLista + 1;
        else
            $inicio_pag = $inicio_pag * $paginasLista + 1;
    ?>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
	            <span class="text-muted">Mostrando p&aacute;gina <?php echo h($pagina); ?> de <?php echo h($total_paginas); ?> (<?php echo h($totalFilas); ?> registros)</span>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <?php if($pagina > 20){ ?>
	                    <li class="page-item"><a class="page-link" href="list_dte_recep_v4.php?pagina=<?php echo h($inicio_pag-1); ?><?php echo h($qrstringPag); ?>">Anterior</a></li>
                    <?php } ?>
                    <?php for($i=$inicio_pag; $i<=($paginasLista + $inicio_pag - 1); $i++){ ?>
                        <?php if($pagina == $i){ ?>
	                        <li class="page-item active"><span class="page-link"><?php echo h($i); ?></span></li>
                        <?php } else { ?>
	                        <li class="page-item"><a class="page-link" href="list_dte_recep_v4.php?pagina=<?php echo h($i); ?><?php echo h($qrstringPag); ?>"><?php echo h($i); ?></a></li>
                        <?php } ?>
                    <?php } ?>
                    <?php if($total_paginas > $paginasLista){ ?>
	                    <li class="page-item"><a class="page-link" href="list_dte_recep_v4.php?pagina=<?php echo h($i); ?><?php echo h($qrstringPag); ?>">Siguiente</a></li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
    <?php } ?>
</div>
<?php } ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Inicializar Flatpickr para fechas
document.addEventListener('DOMContentLoaded', function() {
    flatpickr("#fecha1", { dateFormat: "Y-m-d", locale: "es", disableMobile: true });
    flatpickr("#fecha2", { dateFormat: "Y-m-d", locale: "es", disableMobile: true });
    flatpickr("#fechac1", { dateFormat: "Y-m-d", locale: "es", disableMobile: true });
    flatpickr("#fechac2", { dateFormat: "Y-m-d", locale: "es", disableMobile: true });
});

var noRecActivo = false;

function valida() {
    var F = document._BUSCA;

    if(F.AAR.checked == false && F.SAR.checked == false) {
        alert("Se debe seleccionar a lo menos un estado del Acuse de Recibo");
        return false;
    }
    if(F.AAC.checked == false && F.RAC.checked == false && F.SAC.checked == false) {
        alert("Se debe seleccionar a lo menos un estado de la Respuesta Comercial");
        return false;
    }
    if(F.CRM.checked == false && F.RRM.checked == false && F.SRM.checked == false) {
        alert("Se debe seleccionar a lo menos un estado del Recibo de Mercader\u00EDa");
        return false;
    }
    return true;
}

function bajarExcel() {
    var F = document._BUSCA;

    if(valida() != true) {
        return false;
    }

    if(confirm("Bajar a Excel el resultado de la busqueda? Se descargan un maximo de 10.000 registros.")) {
        var actionOriginal = F.action;
        var targetOriginal = F.target;

        F.action = "excel_dte_recep_v3.php";
        F.target = "_blank";
        F.submit();
        F.action = actionOriginal;
        F.target = targetOriginal;
    }

    return false;
}

function actualizaRegistro() {
    $("#divLoading").addClass("show");
    $.ajax({
        type: "GET",
        url: "actualizaSII.php",
        data: $("#_FORMAJAX").serialize(),
        dataType: "json",
        success: function(obj) {
            if(obj.Error == "0") { alert(obj.msj); location.reload(); }
            if(obj.Error == "1") { alert(obj.msj); $("#divLoading").removeClass("show"); }
            if(obj.Error == "2") { alert(obj.msj); window.location.href = '../login.php'; }
        },
        error: function(r) { alert(r.responseText); $("#divLoading").removeClass("show"); }
    });
}

function responderSII(folio_dte, tipo_docu, rut_rec_dte, dig_rec_dte, merca_dte, fech_merca_dte, acuse_dte, fech_acuse_dte) {
    $("#folio_dte").val(folio_dte);
    $("#tipo_docu").val(tipo_docu);
    $("#rut_rec_dte").val(rut_rec_dte);
    $("#dig_rec_dte").val(dig_rec_dte);

    // Mostrar/ocultar opciones seg&uacute;n estado actual
    if(merca_dte != "") {
        $("#sRespuestaMerca1").hide();
        $("#sRespuestaMerca3").text("Recibo de Mercader\u00EDa ya generado: " + merca_dte + " el " + fech_merca_dte);
    } else {
        $("#sRespuestaMerca1").show();
        $("#sRespuestaMerca3").text("");
    }

    if(acuse_dte != "") {
        $("#sRespuesta1").hide();
        $("#sRespuesta3").text("Respuesta Comercial ya generada: " + acuse_dte + " el " + fech_acuse_dte);
    } else {
        $("#sRespuesta1").show();
        $("#sRespuesta3").text("");
    }

    var modal = new bootstrap.Modal(document.getElementById('modalResponder'));
    modal.show();
}

function enviarRespSII() {
    if($("#sRespuestaMerca option:selected").val() == "" && $("#sRespuesta option:selected").val() == "") {
        alert("Debe seleccionar al menos una respuesta");
        return;
    }

    if(confirm("\u00BFEst\u00E1 seguro de enviar la respuesta al SII?")) {
        $("#divLoading").addClass("show");
        $.ajax({
            type: "GET",
            url: "pro_newcambiar2.php",
            data: $("#_FENVACT").serialize(),
            dataType: "json",
            success: function(obj) {
                if(obj.Error == "0") {
                    $("#divLoading").removeClass("show");
                    if($("#sRespuesta option:selected").val() != "")
                        alert("Resultado de Respuesta a Contenido del Documento: " + obj.glosaAcuse);
                    if($("#sRespuestaMerca option:selected").val() != "")
                        alert("Resultado de Respuesta a Recibo de Mercader\u00EDas: " + obj.glosaMerca);
                    location.reload();
                }
                if(obj.Error == "1") { alert(obj.msj); $("#divLoading").removeClass("show"); }
                if(obj.Error == "2") { alert(obj.msj); window.location.href = '../login.php'; }
            },
            error: function(r) { alert(r.responseText); $("#divLoading").removeClass("show"); }
        });
    }
}

function norecibido() {
    $("#divLoading").addClass("show");
    $.ajax({
        type: "GET",
        url: "norecibido.php",
        dataType: "json",
        success: function(obj) {
            if(obj.Error == "0") {
                noRecActivo = true;
                var newWindow = window.open("", "NoRecibidos", "width=800,height=600,scrollbars=yes");
                newWindow.document.write("<html><head><title>DTE No Recibidos en OpenB</title>");
                newWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">');
                newWindow.document.write("</head><body class='p-3'>" + obj.html + "</body></html>");
                newWindow.document.close();
                $("#divLoading").removeClass("show");
            }
            if(obj.Error == "1") { alert(obj.msj); $("#divLoading").removeClass("show"); }
            if(obj.Error == "2") { alert(obj.msj); window.location.href = '../login.php'; }
        },
        error: function(r) { alert(r.responseText); $("#divLoading").removeClass("show"); }
    });
}

function recibidox() {
    // Callback cuando se cierra el modal
}
</script>
</body>
</html>

