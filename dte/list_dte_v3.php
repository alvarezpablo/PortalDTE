<?php 
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");	

	include("../include/config.php");
	include("../include/db_lib.php");  
	include("../include/ver_aut.php");
    include("../include/ver_emp_adm.php"); 

		$orden = trim($_GET["orden"] ?? "");	// orden campo
		$descAsc = trim($_GET["orni"] ?? ""); // orden nivel

	if($descAsc == "") $descAsc = "1";
	if($orden == "") $orden = "X.fec_carg";	
	if($orden == "1") $orden = "D.folio_dte";
	if($orden == "2") $orden = "D.fec_emi_dte";
	if($orden == "3") $orden = "D.rut_rec_dte";
	if($orden == "4") $orden = "D.mont_tot_dte";

	if($descAsc == "1") $descAsc = "DESC";
	if($descAsc == "2") $descAsc = "ASC";

		$tipo = trim($_GET["tipo"] ?? "");
		$folio = trim($_GET["folio"] ?? "");
		$fecha1 = trim($_GET["fecha1"] ?? "");
		$fecha2 = trim($_GET["fecha2"] ?? "");
		$fechac1 = trim($_GET["fechac1"] ?? "");
		$fechac2 = trim($_GET["fechac2"] ?? "");
		$estado = trim($_GET["estado"] ?? "");
		$rutOriginal = trim($_GET["rut"] ?? "");
		$rut = $rutOriginal;
		$pagina = trim($_GET["pagina"] ?? "");

	if($rut != ""){
		$aRut = explode("-",$rut);
		$rut = $aRut[0];
	}

		$AAR = trim($_GET["AAR"] ?? "");		// Acuse de recibo ok
		$RAR = trim($_GET["RAR"] ?? "");		// acuse de recubi rechazado
		$SAR = trim($_GET["SAR"] ?? "");		// sin acuse de recibo
		$AAC = trim($_GET["AAC"] ?? "");		// acptado comercialmente
		$RAC = trim($_GET["RAC"] ?? "");		// rechazado comercialmente
		$SAC = trim($_GET["SAC"] ?? "");		// sin respuesta comercial
		$CRM = trim($_GET["CRM"] ?? "");		// con recibo de mercaderia
		$SRM = trim($_GET["SRM"] ?? "");		// sin recibo de mercaderia
		$hayBusqueda = !empty($_GET);

	$qrstring = "&tipo=" . $tipo;
	$qrstring .= "&folio=" . $folio;
	$qrstring .= "&fecha1=" . $fecha1;
	$qrstring .= "&fecha2=" . $fecha2;
	$qrstring .= "&fechac1=" . $fechac1;
	$qrstring .= "&fechac2=" . $fechac2;
	$qrstring .= "&estado=" . $estado;
	$qrstring .= "&rut=" . $rut;
	$qrstring .= "&AAR=" . $AAR;
	$qrstring .= "&RAR=" . $RAR;
	$qrstring .= "&SAR=" . $SAR;
	$qrstring .= "&AAC=" . $AAC;
	$qrstring .= "&RAC=" . $RAC;
	$qrstring .= "&SAC=" . $SAC;
	$qrstring .= "&CRM=" . $CRM;
	$qrstring .= "&SRM=" . $SRM;	

	$fleCarga = "";
	$fleFolio = "";
	$fleFech = "";
	$fleRut = "";
	$fleTotal = "";


	$descAscCarga = "1";
	if($orden == "X.fec_carg"){
		$fleCarga = "<img src='../img/arriba.png' width='17'>";
		if($descAsc == "DESC") {
			$descAscCarga = "2";
			$fleCarga = "<img src='../img/abajo.png' width='17'>";
		}
	}
	$descAscFolio = "1";
	if($orden == "D.folio_dte"){
		$fleFolio = "<img src='../img/arriba.png' width='17'>";
		if($descAsc == "DESC") {
			$descAscFolio = "2";
			$fleFolio = "<img src='../img/abajo.png' width='17'>";
		}
	}
	$descAscFech = "1";
	if($orden == "D.fec_emi_dte"){
		$fleFech = "<img src='../img/arriba.png' width='17'>";
		if($descAsc == "DESC"){
			$descAscFech = "2";
			$fleFech = "<img src='../img/abajo.png' width='17'>";
		}
	}
	$descAscRut = "1";
	if($orden == "D.rut_rec_dte"){
		$fleRut = "<img src='../img/arriba.png' width='17'>";
		if($descAsc == "DESC"){
			$descAscRut = "2";
			$fleRut = "<img src='../img/abajo.png' width='17'>";
		}
	}
	$descAscTotal = "1";
	if($orden == "D.mont_tot_dte"){
		$fleTotal = "<img src='../img/arriba.png' width='17'>";
		if($descAsc == "DESC"){ 
			$descAscTotal = "2";
			$fleTotal = "<img src='../img/abajo.png' width='17'>";
		}
	}

	$qrsCarga = $qrstring . "&orden=&orni=" . $descAscCarga;
	$qrsFolio = $qrstring . "&orden=1&orni=" . $descAscFolio;
	$qrsFech = $qrstring . "&orden=2&orni=" . $descAscFech;
	$qrsRut = $qrstring . "&orden=3&orni=" . $descAscRut;
	$qrsTotal = $qrstring . "&orden=4&orni=" . $descAscTotal;


	function poneEstado($nEstadoDte){
		$sEstadoDte = $nEstadoDte;
		switch ($nEstadoDte) {
			case 0:
			$sEstadoDte = "Cargado";
			break;
			case 1:
			$sEstadoDte = "Firmado";
			break;
			case 3:
			$sEstadoDte = "Con ERROR";
			break;
			case 5:
			$sEstadoDte = "Empaquetado";
			break; 
			case 13:
			$sEstadoDte = "Enviado SII";
			break;
			case 29:
			$sEstadoDte = "Aceptado SII";
			break;
			case 45:
			$sEstadoDte = "Con Reparo SII";
			break;
			case 77:
			$sEstadoDte = "Rechazado SII";
			break;
			case 157:
			$sEstadoDte = "Enviado a Cliente";
			break;
			case 173:
			$sEstadoDte = "Con Reparo Enviado a Cliente";
			break;
			case 413:
			$sEstadoDte = "Aceptado por Cliente";
			break;
			case 429:
			$sEstadoDte = "Con Reparo Aceptado por Cliente";
			break; 
			case 1181:
			$sEstadoDte = "Rechazado Autom�ticamente";
			break; 
			case 1437:
			$sEstadoDte = "Rechazado Comercialmente";
			break; 
		}
		return $sEstadoDte;
	}

	function poneTipo($tipo_docu){

		switch ($tipo_docu) {
			case 33:
				$sEstadoDte = "FA.Elect";
				break;
			case 34:
				$sEstadoDte = "FE.Elect";
				break;
			case 39:
				$sEstadoDte = "BA.Elect";
				break;
			case 41:
				$sEstadoDte = "BE.Elect";
				break;
			case 43:
				$sEstadoDte = "LQ.Elect";
				break;
			case 46:
				$sEstadoDte = "FC.Elect";
				break;
			case 52:
				$sEstadoDte = "GD.Elect";
				break;
			case 56:
				$sEstadoDte = "ND.Elect";
				break;
			case 61:
				$sEstadoDte = "NC.Elect";
				break;
			case 110:
				$sEstadoDte = "FEE.Elect";
				break;
			case 111:
				$sEstadoDte = "NDE.Elect";
				break;
			case 112:
				$sEstadoDte = "NCE.Elect";
				break;
		}
		if ($sEstadoDte == "")
			$sEstadoDte = $tipo_docu;
		else
			$sEstadoDte = "$sEstadoDte ($tipo_docu)";

		return $sEstadoDte;
	}

		function h($value){
			return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
		}

		$filtrosResumen = array();
		if($tipo != "") $filtrosResumen[] = "Tipo: " . poneTipo($tipo);
		if($estado != "") $filtrosResumen[] = "Estado: " . poneEstado($estado);
		if($folio != "") $filtrosResumen[] = "Folio: " . $folio;
		if($rutOriginal != "") $filtrosResumen[] = "RUT: " . $rutOriginal;
		if($fecha1 != "" || $fecha2 != ""){
			$filtrosResumen[] = "Emision: " . ($fecha1 != "" ? $fecha1 : $fecha2) . " a " . ($fecha2 != "" ? $fecha2 : $fecha1);
		}
		if($fechac1 != "" || $fechac2 != ""){
			$filtrosResumen[] = "Carga: " . ($fechac1 != "" ? $fechac1 : $fechac2) . " a " . ($fechac2 != "" ? $fechac2 : $fechac1);
		}
		if($hayBusqueda && !($AAR == "1" && $RAR == "1" && $SAR == "1")) $filtrosResumen[] = "Acuse: filtro personalizado";
		if($hayBusqueda && !($AAC == "1" && $RAC == "1" && $SAC == "1")) $filtrosResumen[] = "Respuesta comercial: filtro personalizado";
		if($hayBusqueda && !($CRM == "1" && $SRM == "1")) $filtrosResumen[] = "Recibo mercaderia: filtro personalizado";
		$cantidadFiltros = count($filtrosResumen);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DTE Emitidos - Portal DTE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
	    <style>
	        :root { --primary-color: #001f3f; --secondary-color: #0074d9; }
	        body { background: #eef2f7; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1f2937; }
	        .page-shell { max-width: 1600px; margin: 0 auto; }
	        .page-hero { background: linear-gradient(135deg, #001f3f 0%, #0b5ed7 100%); color: #fff; border-radius: 18px; padding: 1.5rem; box-shadow: 0 12px 32px rgba(0, 31, 63, 0.18); margin-bottom: 1.25rem; }
	        .page-hero h1 { font-size: 1.65rem; margin-bottom: 0.35rem; }
	        .page-hero p { margin-bottom: 0; opacity: 0.92; }
	        .hero-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.14); font-size: 1.45rem; }
	        .hero-kpis { display: flex; flex-wrap: wrap; gap: 0.75rem; }
	        .hero-kpi { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18); border-radius: 999px; padding: 0.45rem 0.85rem; font-size: 0.82rem; }
	        .card { border: 1px solid rgba(15,23,42,0.05); border-radius: 16px; box-shadow: 0 10px 25px rgba(15,23,42,0.08); margin-bottom: 20px; overflow: hidden; }
	        .card-header { background: var(--primary-color); color: white; border-radius: 16px 16px 0 0 !important; font-weight: 600; padding: 0.9rem 1rem; }
	        .table thead th { background: var(--primary-color); color: white; font-weight: 500; font-size: 0.85rem; white-space: nowrap; position: sticky; top: 0; z-index: 1; }
	        .table tbody td { vertical-align: middle; font-size: 0.8rem; }
	        .table tbody tr:hover { background-color: #eef4fb; }
	        .btn-action { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
	        .sort-link { color: white; text-decoration: none; }
	        .sort-link:hover { color: #ccc; }
	        .form-label { font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; }
	        .form-control, .form-select { font-size: 0.85rem; }
	        .badge-estado { font-size: 0.72rem; padding: 0.45rem 0.6rem; }
	        .table-responsive { max-height: 68vh; overflow: auto; }
	        .form-check-label { font-size: 0.8rem; }
	        .filter-summary { background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 14px; padding: 0.9rem 1rem; }
	        .filter-chip { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.75rem; background: #fff; border: 1px solid #dbe7f3; border-radius: 999px; font-size: 0.8rem; color: #334155; margin: 0.25rem 0.35rem 0 0; }
	        .filter-chip i { color: var(--secondary-color); }
	        .filter-section { height: 100%; border: 1px solid #e5e7eb; border-radius: 14px; padding: 0.9rem 1rem; background: #fff; }
	        .filter-section-title { font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.7rem; display: flex; align-items: center; gap: 0.45rem; }
	        .filter-options { display: flex; flex-wrap: wrap; gap: 0.8rem 1.2rem; }
	        .quick-note { font-size: 0.8rem; color: #64748b; }
	        .btn-toolbar-wrap { display: flex; flex-wrap: wrap; justify-content: center; gap: 0.75rem; }
	        .results-toolbar { background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
	        .hint-pill { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.75rem; background: #fff; border: 1px solid #dbe2ea; border-radius: 999px; font-size: 0.78rem; color: #475569; }
	        .actions-stack { min-width: 150px; }
	        .icon-links { display: flex; flex-wrap: wrap; gap: 0.35rem; }
	        .status-links { display: flex; gap: 0.5rem; align-items: center; }
	        .track-box { min-width: 120px; }
	        .empty-state { padding: 4rem 1rem; text-align: center; color: #6b7280; }
	        .empty-state i { font-size: 3rem; }
	        .pagination-wrap { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
	        @media (max-width: 991.98px) {
	            .page-hero { padding: 1.2rem; }
	            .table-responsive { max-height: none; }
	        }
	        @media (max-width: 767.98px) {
	            body { padding: 1rem !important; }
	            .page-hero h1 { font-size: 1.35rem; }
	            .card-header { padding: 0.8rem 0.9rem; }
	            .filter-options { gap: 0.75rem 1rem; }
	        }
	    </style>
</head>
<body class="p-3">

<script>
function cederDocumento(folio, tipo, monto, codEmp) {
    var url = "ceder_documento.php?nFolio=" + folio + "&nTipoDTE=" + tipo + "&nMontTot=" + monto + "&nCodEmp=" + codEmp;
    window.open(url, "ceder", "width=1000,height=800,scrollbars=yes,resizable=yes");
}

function trackDocumento(folio, tipo, monto, codEmp) {
    var url = "view_track.php?nFolioDte=" + folio + "&nTipoDocu=" + tipo + "&nMontTot=" + monto + "&nCodEmp=" + codEmp;
    window.open(url, "track", "width=600,height=600,scrollbars=yes,resizable=yes");
}

function reenviarDTE(folio, tipo) {
    var url = "form_reenvio.php?nFolio=" + folio + "&nTipoDTE=" + tipo;
    window.open(url, "reenviar", "width=450,height=450,scrollbars=yes,resizable=yes");
}

function chSelDelEmp() {
    var checks = document.querySelectorAll('input[name="del[]"]:checked');
    return checks.length > 0;
}

function chDelEmp() {
    if (chSelDelEmp()) {
        if (confirm("Confirma la eliminacion de los DTE seleccionados?")) {
            document._FDEL.submit();
        }
    } else {
        alert("Seleccione al menos un DTE a eliminar");
    }
}

function chDchALL() {
    var selectAll = document.getElementById('selectAllCheck');
    var checks = document.querySelectorAll('input[name="del[]"]');
    checks.forEach(function(c) { c.checked = selectAll.checked; });
}

function valida() {
    var F = document._BUSCA;
    if (!F.AAR.checked && !F.RAR.checked && !F.SAR.checked) {
        alert("Seleccione al menos un estado de Acuse de Recibo");
        return false;
    }
    if (!F.AAC.checked && !F.RAC.checked && !F.SAC.checked) {
        alert("Seleccione al menos un estado de Respuesta Comercial");
        return false;
    }
    if (!F.CRM.checked && !F.SRM.checked) {
        alert("Seleccione al menos un estado de Recibo de Mercaderia");
        return false;
    }
    return true;
}

	function bajarExcel() {
	    if (confirm("Descargar a Excel? (maximo 10.000 registros)")) {
	        var form = document._BUSCA;
	        var prevAction = form.action;
	        var prevTarget = form.target;
	        form.action = "excel_dte_v2.php";
	        form.target = "_blank";
	        form.submit();
	        form.action = prevAction;
	        form.target = prevTarget;
	    }
	}

function listar() {
    if (valida()) {
        document._BUSCA.action = "list_dte_v3.php";
        document._BUSCA.target = "_self";
        document._BUSCA.submit();
    }
}

function limpiar() {
    window.location.href = 'list_dte_v3.php';
}
</script>
	<div class="page-shell">
	    <div class="page-hero">
	        <div class="row g-3 align-items-center">
	            <div class="col-lg-7">
	                <div class="d-flex align-items-start gap-3">
	                    <div class="hero-icon"><i class="bi bi-receipt-cutoff"></i></div>
	                    <div>
	                        <h1 class="h3 mb-2">DTE Emitidos</h1>
	                        <p>Revise documentos emitidos, filtre por estado o receptor y acceda r&aacute;pidamente a PDF, XML, reenv&iacute;o, cesi&oacute;n y exportaci&oacute;n a Excel.</p>
	                    </div>
	                </div>
	            </div>
	            <div class="col-lg-5">
	                <div class="hero-kpis justify-content-lg-end">
	                    <span class="hero-kpi"><i class="bi bi-funnel me-1"></i>Filtros por tipo, fechas y receptor</span>
	                    <span class="hero-kpi"><i class="bi bi-lightning-charge me-1"></i>Acciones directas por documento</span>
	                    <span class="hero-kpi"><i class="bi bi-file-earmark-excel me-1"></i>Excel hasta 10.000 registros</span>
	                </div>
	            </div>
	        </div>
	    </div>
<!-- Formulario de Busqueda -->
<div class="card mb-4">
	    <div class="card-header">
	        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
	            <div>
	                <div class="d-flex align-items-center"><i class="bi bi-search me-2"></i><span>Filtros de B&uacute;squeda</span></div>
	                <div class="small text-white-50 mt-1">Combine criterios para ubicar DTE emitidos sin alterar la operatoria actual del m&oacute;dulo.</div>
	            </div>
	            <span class="badge rounded-pill text-bg-light text-primary-emphasis">B&uacute;squeda compatible con exportaci&oacute;n Excel</span>
	        </div>
    </div>
    <div class="card-body">
	        <?php if($hayBusqueda && $cantidadFiltros > 0): ?>
	        <div class="filter-summary mb-4">
	            <div class="d-flex flex-wrap align-items-center gap-2">
	                <strong class="me-2">Filtros activos:</strong>
	                <?php foreach($filtrosResumen as $filtroResumen): ?>
	                    <span class="filter-chip"><i class="bi bi-funnel-fill"></i><?php echo h($filtroResumen); ?></span>
	                <?php endforeach; ?>
	            </div>
	        </div>
	        <?php endif; ?>
	        <p class="quick-note mb-4">Utilice los estados de acuse, respuesta comercial y recibo de mercader&iacute;a para concentrar la gesti&oacute;n documental en los casos que realmente requieren seguimiento.</p>
	        <form name="_BUSCA" id="_BUSCA" method="get" action="">
            <div class="row g-3">
                <!-- Tipo DTE -->
                <div class="col-md-4">
                    <label class="form-label">Tipo DTE</label>
                    <select name="tipo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="33" <?php echo ($tipo=="33"?"selected":""); ?>>Factura Electr&oacute;nica</option>
                        <option value="34" <?php echo ($tipo=="34"?"selected":""); ?>>Factura Exenta Electr&oacute;nica</option>
                        <option value="39" <?php echo ($tipo=="39"?"selected":""); ?>>Boleta Electr&oacute;nica</option>
                        <option value="41" <?php echo ($tipo=="41"?"selected":""); ?>>Boleta Exenta Electr&oacute;nica</option>
                        <option value="43" <?php echo ($tipo=="43"?"selected":""); ?>>Liquidaci&oacute;n Factura</option>
                        <option value="46" <?php echo ($tipo=="46"?"selected":""); ?>>Factura de Compra</option>
                        <option value="52" <?php echo ($tipo=="52"?"selected":""); ?>>Gu&iacute;a de Despacho</option>
                        <option value="56" <?php echo ($tipo=="56"?"selected":""); ?>>Nota de D&eacute;bito</option>
                        <option value="61" <?php echo ($tipo=="61"?"selected":""); ?>>Nota de Cr&eacute;dito</option>
                        <option value="110" <?php echo ($tipo=="110"?"selected":""); ?>>Factura Exportaci&oacute;n</option>
                        <option value="111" <?php echo ($tipo=="111"?"selected":""); ?>>ND Exportaci&oacute;n</option>
                        <option value="112" <?php echo ($tipo=="112"?"selected":""); ?>>NC Exportaci&oacute;n</option>
                    </select>
                </div>
                <!-- Estado DTE -->
                <div class="col-md-4">
                    <label class="form-label">Estado DTE</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="1" <?php echo ($estado=="1"?"selected":""); ?>>Firmado</option>
                        <option value="3" <?php echo ($estado=="3"?"selected":""); ?>>Error</option>
                        <option value="5" <?php echo ($estado=="5"?"selected":""); ?>>Empaquetado</option>
                        <option value="13" <?php echo ($estado=="13"?"selected":""); ?>>Enviado a SII</option>
                        <option value="29" <?php echo ($estado=="29"?"selected":""); ?>>Aceptado SII</option>
                        <option value="45" <?php echo ($estado=="45"?"selected":""); ?>>Con Reparos SII</option>
                        <option value="77" <?php echo ($estado=="77"?"selected":""); ?>>Rechazado SII</option>
                        <option value="157" <?php echo ($estado=="157"?"selected":""); ?>>Enviado a Cliente</option>
                        <option value="413" <?php echo ($estado=="413"?"selected":""); ?>>Aceptado Cliente</option>
                        <option value="1181" <?php echo ($estado=="1181"?"selected":""); ?>>Rechazado Autom&aacute;ticamente</option>
                        <option value="1437" <?php echo ($estado=="1437"?"selected":""); ?>>Rechazado Comercialmente</option>
                    </select>
                </div>
                <!-- Folio y Rut -->
                <div class="col-md-2">
                    <label class="form-label">Folio</label>
	                    <input type="text" name="folio" class="form-control form-control-sm" maxlength="18" value="<?php echo h($folio); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">RUT Receptor</label>
	                    <input type="text" name="rut" class="form-control form-control-sm" maxlength="12" placeholder="12345678-9" value="<?php echo h($rutOriginal); ?>">
                </div>
            </div>

            <div class="row g-3 mt-2">
                <!-- Fecha Emision -->
                <div class="col-md-3">
                    <label class="form-label">Fecha Emisi&oacute;n Desde</label>
	                    <input type="text" name="fecha1" id="fecha1" class="form-control form-control-sm datepicker" placeholder="YYYY-MM-DD" autocomplete="off" value="<?php echo h($fecha1); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha Emisi&oacute;n Hasta</label>
	                    <input type="text" name="fecha2" id="fecha2" class="form-control form-control-sm datepicker" placeholder="YYYY-MM-DD" autocomplete="off" value="<?php echo h($fecha2); ?>">
                </div>
                <!-- Fecha Carga -->
                <div class="col-md-3">
                    <label class="form-label">Fecha Carga Desde</label>
	                    <input type="text" name="fechac1" id="fechac1" class="form-control form-control-sm datepicker" placeholder="YYYY-MM-DD" autocomplete="off" value="<?php echo h($fechac1); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha Carga Hasta</label>
	                    <input type="text" name="fechac2" id="fechac2" class="form-control form-control-sm datepicker" placeholder="YYYY-MM-DD" autocomplete="off" value="<?php echo h($fechac2); ?>">
                </div>
            </div>

            <!-- Checkboxes de estado -->
            <div class="row g-3 mt-2">
	                <div class="col-lg-4">
	                    <div class="filter-section">
	                        <div class="filter-section-title"><i class="bi bi-envelope-paper"></i>Acuse de Recibo</div>
	                        <div class="filter-options">
                        <div class="form-check">
	                            <input type="checkbox" class="form-check-input" name="AAR" value="1" <?php echo ($AAR=="1"||!$hayBusqueda?"checked":""); ?>>
                            <label class="form-check-label">Recibido</label>
                        </div>
                        <div class="form-check">
	                            <input type="checkbox" class="form-check-input" name="RAR" value="1" <?php echo ($RAR=="1"||!$hayBusqueda?"checked":""); ?>>
                            <label class="form-check-label">Rechazado</label>
                        </div>
                        <div class="form-check">
	                            <input type="checkbox" class="form-check-input" name="SAR" value="1" <?php echo ($SAR=="1"||!$hayBusqueda?"checked":""); ?>>
                            <label class="form-check-label">No Recibido</label>
                        </div>
	                        </div>
                    </div>
                </div>
	                <div class="col-lg-4">
	                    <div class="filter-section">
	                        <div class="filter-section-title"><i class="bi bi-chat-left-text"></i>Respuesta Comercial</div>
	                        <div class="filter-options">
                        <div class="form-check">
	                            <input type="checkbox" class="form-check-input" name="AAC" value="1" <?php echo ($AAC=="1"||!$hayBusqueda?"checked":""); ?>>
                            <label class="form-check-label">Aceptado</label>
                        </div>
                        <div class="form-check">
	                            <input type="checkbox" class="form-check-input" name="RAC" value="1" <?php echo ($RAC=="1"||!$hayBusqueda?"checked":""); ?>>
                            <label class="form-check-label">Rechazado</label>
                        </div>
                        <div class="form-check">
	                            <input type="checkbox" class="form-check-input" name="SAC" value="1" <?php echo ($SAC=="1"||!$hayBusqueda?"checked":""); ?>>
                            <label class="form-check-label">No Recibida</label>
                        </div>
	                        </div>
                    </div>
                </div>
	                <div class="col-lg-4">
	                    <div class="filter-section">
	                        <div class="filter-section-title"><i class="bi bi-box-seam"></i>Recibo de Mercader&iacute;a</div>
	                        <div class="filter-options">
                        <div class="form-check">
	                            <input type="checkbox" class="form-check-input" name="CRM" value="1" <?php echo ($CRM=="1"||!$hayBusqueda?"checked":""); ?>>
                            <label class="form-check-label">Recibido</label>
                        </div>
                        <div class="form-check">
	                            <input type="checkbox" class="form-check-input" name="SRM" value="1" <?php echo ($SRM=="1"||!$hayBusqueda?"checked":""); ?>>
                            <label class="form-check-label">No Recibido</label>
                        </div>
	                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="row mt-4">
	                <div class="col-12 text-center">
	                    <div class="btn-toolbar-wrap">
                    <button type="button" class="btn btn-primary" onclick="listar();">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
	                    <button type="button" class="btn btn-success" onclick="bajarExcel();">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel
                    </button>
	                    <button type="button" class="btn btn-secondary" onclick="limpiar();">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </button>
	                    </div>
	                    <p class="quick-note text-center mt-3 mb-0">La b&uacute;squeda mantiene las acciones actuales del proceso: PDF, XML, reenv&iacute;o, cesi&oacute;n y eliminaci&oacute;n de seleccionados.</p>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
		if($hayBusqueda){
?>
<!-- Tabla de Resultados -->
<div class="card">
	    <div class="card-header">
	        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
	            <div>
	                <div class="d-flex align-items-center gap-2"><i class="bi bi-table"></i><span>Resultados de la B&uacute;squeda</span></div>
	                <div class="small text-white-50 mt-1">Ordene por folio, emisi&oacute;n, carga, total o RUT y revise las respuestas disponibles para cada documento.</div>
	            </div>
	            <div class="d-flex flex-wrap gap-2 align-items-center">
	                <span class="badge rounded-pill text-bg-light text-primary-emphasis">PDF, XML, reenv&iacute;o y cesi&oacute;n</span>
	                <button type="button" class="btn btn-danger btn-sm" onclick="chDelEmp();">
	                    <i class="bi bi-trash"></i> Eliminar Seleccionados
	                </button>
	            </div>
	        </div>
    </div>
	    <div class="results-toolbar px-3 py-2">
	        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
	            <div class="small text-muted">Seleccione documentos para eliminar solo cuando est&eacute;n cargados o rechazados. Los iconos inferiores muestran acuse, recibo de mercader&iacute;a y respuesta comercial.</div>
	            <?php if($cantidadFiltros > 0): ?>
	            <span class="hint-pill"><i class="bi bi-funnel"></i><?php echo $cantidadFiltros; ?> filtros aplicados</span>
	            <?php endif; ?>
	        </div>
	    </div>
	    <div class="card-body p-0">
        <form name="_FDEL" method="post" action="pro_dte.php">
            <input type="hidden" name="sAccion" value="E">
            <div class="table-responsive">
	                <table class="table table-striped table-hover table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center"><input type="checkbox" class="form-check-input" id="selectAllCheck" onclick="chDchALL();"></th>
                            <th>Acciones</th>
                            <th>Track</th>
                            <th>Tipo</th>
                            <th><a href="list_dte_v3.php?a=1<?php echo $qrsFolio; ?>" class="sort-link">Folio <?php echo $fleFolio; ?></a></th>
                            <th>Estado</th>
                            <th><a href="list_dte_v3.php?a=1<?php echo $qrsFech; ?>" class="sort-link">Emisi&oacute;n <?php echo $fleFech; ?></a></th>
                            <th><a href="list_dte_v3.php?a=1<?php echo $qrsCarga; ?>" class="sort-link">Carga <?php echo $fleCarga; ?></a></th>
                            <th class="text-end">Exento</th>
                            <th class="text-end">Neto</th>
                            <th class="text-end">IVA</th>
                            <th class="text-end"><a href="list_dte_v3.php?a=1<?php echo $qrsTotal; ?>" class="sort-link">Total <?php echo $fleTotal; ?></a></th>
                            <th><a href="list_dte_v3.php?a=1<?php echo $qrsRut; ?>" class="sort-link">RUT <?php echo $fleRut; ?></a></th>
                            <th>Receptor</th>
                            <th>Direcci&oacute;n</th>
                            <th>Comuna</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
//		print_r($_POST);
		$conn = conn();



//		$cont = " SELECT COUNT(D.folio_dte) t ";
		$cont = " SELECT D.codi_empr t ";
		$campos = " SELECT 	
					D.tipo_docu, D.folio_dte, X.est_xdte,  D.fec_emi_dte, X.fec_carg, D.rut_rec_dte, D.dig_rec_dte, D.nom_rec_dte, D.dir_rec_dte, D.com_rec_dte, 
					D.mntneto_dte, D.mnt_exen_dte, D.iva_dte, D.mont_tot_dte, 
					(SELECT trackid_xed FROM xmlenviodte WHERE codi_empr = X.codi_empr AND num_xed = X.num_xed) trackid_xed, 
					X.est_envio, X.est_recibo_mercaderias, X.est_rec_doc, 
					X.est_res_rev, X.path_pdf, X.path_pdf_cedible, D.rut_emis_dte ";
		$sql = " FROM 
			xmldte X, 
			dte_enc D
		WHERE
			D.tipo_docu = X.tipo_docu AND
			D.folio_dte = X.folio_dte AND
			D.codi_empr = X.codi_empr AND
			D.codi_empr = '". trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";

		if($tipo != "")	$sql .= " AND D.tipo_docu = '" . str_replace("'","''",$tipo) . "'";
		if($folio != "")	$sql .= " AND CAST(D.folio_dte as varchar)= '" . str_replace("'","''",$folio) . "'";
		if($estado != "")	$sql .= " AND X.est_xdte = '" . str_replace("'","''",$estado) . "'";
		if($rut != "")	$sql .= " AND D.rut_rec_dte = '" . str_replace("'","''",$rut) . "'";
		if($fecha1 != "" || $fecha2 != ""){
			$_STRING_SEARCH0 = $fecha1;
			$_STRING_SEARCH1 = $fecha2;
			if($_STRING_SEARCH0 != "" && $_STRING_SEARCH1 == "") 
				$_STRING_SEARCH1 = $_STRING_SEARCH0;
			elseif($_STRING_SEARCH0 == "" && $_STRING_SEARCH1 != "")
				$_STRING_SEARCH0 = $_STRING_SEARCH1;			
			$sql .= " AND TO_DATE(D.fec_emi_dte,'YYYY-MM-DD') BETWEEN ('" . str_replace("'","''",$_STRING_SEARCH0) . "') AND ('" . str_replace("'","''",$_STRING_SEARCH1) . "') "; 
		}
		if($fechac1 != "" || $fechac2 != ""){
			$_STRING_SEARCHC0 = $fechac1;
			$_STRING_SEARCHC1 = $fechac2;
			if($_STRING_SEARCHC0 != "" && $_STRING_SEARCHC1 == "") 
				$_STRING_SEARCHC1 = $_STRING_SEARCHC0;
			elseif($_STRING_SEARCHC0 == "" && $_STRING_SEARCHC1 != "")
				$_STRING_SEARCHC0 = $_STRING_SEARCHC1;			
			$sql .= " AND X.fec_carg BETWEEN ('" . str_replace("'","''",$_STRING_SEARCHC0) . "') AND ('" . str_replace("'","''",$_STRING_SEARCHC1) . "') "; 
		}


		$inCod = "";
		if($AAR == "1") $inCod .= "'R',";
		if($RAR == "1") $inCod .= "'X',";
		if($SAR == "1") $inCod .= "'',";
		$inCod2 = "";
		if($AAC == "1") $inCod2 .= "'R','A',";
		if($RAC == "1") $inCod2 .= "'X',";
		if($SAC == "1") $inCod2 .= "'',";
		$inCod3 = "";
		if($CRM == "1") $inCod3 .= "'R',";
		if($SRM == "1") $inCod3 .= "'',";

		if($AAR == "1" && $RAR == "1" && $SAR == "1")	// todas las opciones marcadas evita el filtro
			$NoAplica = "";
		else{
			if($inCod == "") 
				$sql .= " AND coalesce(X.est_rec_doc, '') NOT IN ('R','X','') ";
			else{
				$inCod = substr($inCod, 0, strlen($inCod) - 1);
				$sql .= " AND coalesce(X.est_rec_doc, '') IN (".$inCod.") ";	
			}
		}

		if($AAC == "1" && $RAC == "1" && $SAC == "1")	// todas las opciones marcadas evita el filtro
			$NoAplica = "";
		else{
			if($inCod2 == "") 
				$sql .= " AND coalesce(X.est_res_rev, '') NOT IN ('R','A','X','') ";
			else{
				$inCod2 = substr($inCod2, 0, strlen($inCod2) - 1);
				$sql .= " AND coalesce(X.est_res_rev, '') IN (".$inCod2.") ";	
			}
		}
		if($CRM == "1" && $SRM == "1")	// todas las opciones marcadas evita el filtro
			$NoAplica = "";
		else{
			if($inCod3 == "") 
				$sql .= " AND coalesce(X.est_recibo_mercaderias, '') NOT IN ('R','') ";
			else{
				$inCod3 = substr($inCod3, 0, strlen($inCod3) - 1);
				$sql .= " AND coalesce(X.est_recibo_mercaderias, '') IN (".$inCod3.") ";	
			}
		}


/*		if($AAR == "1" && $RAR == "1" && $SAR == "1") $sql .= " AND coalesce(X.est_rec_doc, '') IN ('R','X','') ";
		if($AAR == "1" && $RAR == "1" && $SAR != "1") $sql .= " AND (X.est_rec_doc IN ('R','X') AND coalesce(X.est_rec_doc, '') != '') ";
		if($AAR == "1" && $RAR != "1" && $SAR != "1") $sql .= " AND (X.est_rec_doc = 'R' AND coalesce(X.est_rec_doc, '') NOT IN ('X','')) ";
		if($AAR != "1" && $RAR != "1" && $SAR != "1") $sql .= " AND coalesce(X.est_rec_doc, '') NOT IN ('R','X','') ";	*/

/*		if($AAC == "1" && $RAC == "1" && $SAC == "1") $sql .= " AND coalesce(X.est_res_rev, '') IN ('A','R','X','')  ";
		if($AAC == "1" && $RAC == "1" && $SAC != "1") $sql .= " AND coalesce(X.est_res_rev, '') IN ('A','R','X')  ";
		if($AAC == "1" && $RAC != "1" && $SAC != "1") $sql .= " AND coalesce(X.est_res_rev, '') IN ('A','R')  ";
		if($AAC != "1" && $RAC != "1" && $SAC != "1") $sql .= " AND coalesce(X.est_res_rev, '') NOT IN ('A','R','X','')  "; */


//		if($AAR == "1")	$sql .= " AND X.est_rec_doc = 'R'"; else $sql .= " AND X.est_rec_doc != 'R'";
//		if($RAR == "1")	$sql .= " AND X.est_rec_doc = 'X'"; else $sql .= " AND X.est_rec_doc != 'X'";
//		if($SAR == "1")	$sql .= " AND coalesce(X.est_rec_doc, '') = ''"; else $sql .= " AND coalesce(X.est_rec_doc, '') != ''";
/*
		if($AAC == "1")	$sql .= " AND X.est_res_rev IN ('A','R')"; else $sql .= " AND X.est_res_rev NOT IN ('A','R')";
		if($RAC == "1")	$sql .= " AND X.est_res_rev = 'X'"; else $sql .= " AND X.est_res_rev != 'X'";
		if($SAC == "1")	$sql .= " AND coalesce(X.est_res_rev, '') = ''"; else $sql .= " AND coalesce(X.est_res_rev, '') != ''";

		if($CRM == "1")	$sql .= " AND X.est_recibo_mercaderias = 'R'"; else $sql .= " AND X.est_recibo_mercaderias != 'R'";
		if($SRM == "1")	$sql .= " AND X.est_recibo_mercaderias = ''"; else $sql .= " AND X.est_recibo_mercaderias != ''";
*/
		// $sql .= " ORDER BY fec_carg DESC ";	// LIMIT 20 ";

		$campos = $campos . " " . $sql;	// . " ORDER BY fec_carg DESC ";
		$cont = $cont . " " . $sql;
	//			echo $cont . "<br><br>";

		//Limito la busqueda
		$TAMANO_PAGINA = 40;

		if (!$pagina) {
		   $inicio = 1;
		   $pagina = 1;
		   $desde = 0;
		}
		else {
		   $inicio = ($pagina - 1) * $TAMANO_PAGINA;
		   $desde = ($pagina - 1) * $TAMANO_PAGINA;
		}
		if($inicio == 0)
			$inicio = 1;
		
		$limiteCount = $TAMANO_PAGINA * 40;			// cantidad de registros por pagina por el doble de cantidad de paginas disponibles
		$cont = $cont . " LIMIT " . $limiteCount . " offset " . $desde;
//		echo $cont . "<br>";
		$resultCount = rCursor($conn, $cont);
//		if(!$resultCount->EOF) 
//			$totalFilas = trim($resultCount->fields["t"]); 
		$totalFilas = 0;
		while (!$resultCount->EOF) {
			$totalFilas++;
			$resultCount->MoveNext(); 
		}

		if($totalFilas > 0){

			//$result = $conn->selectLimit($campos, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);
			$campos .= " ORDER BY " . $orden . " " . $descAsc ." LIMIT " . $TAMANO_PAGINA . " offset " . $desde;
//			echo $campos;
			$result = rCursor($conn, $campos);
			$sClassRow = "alt";											 // clase de la hoja de estilo

			while (!$result->EOF) {
				$tipo_docu = trim($result->fields["tipo_docu"]);  
				$folio_dte = trim($result->fields["folio_dte"]);  
				$est_xdte = trim($result->fields["est_xdte"]);  
				$fec_emi_dte = trim($result->fields["fec_emi_dte"]);  
				$fec_carg = trim($result->fields["fec_carg"]);  
				$rut_rec_dte = trim($result->fields["rut_rec_dte"]) . "-" . trim($result->fields["dig_rec_dte"]) ;  
				$nom_rec_dte = trim($result->fields["nom_rec_dte"]);  
				$dir_rec_dte = trim($result->fields["dir_rec_dte"]);  
				$com_rec_dte = trim($result->fields["com_rec_dte"]);  
				$mntneto_dte = trim($result->fields["mntneto_dte"]);  
				$mnt_exen_dte = trim($result->fields["mnt_exen_dte"]);  
				$iva_dte = trim($result->fields["iva_dte"]);  
				$mont_tot_dte = trim($result->fields["mont_tot_dte"]);  
				$trackid_xed = trim($result->fields["trackid_xed"]);  
				$est_envio = trim($result->fields["est_envio"]);  
				$est_recibo_mercaderias = trim($result->fields["est_recibo_mercaderias"]);  
				$est_rec_doc = trim($result->fields["est_rec_doc"]);  
				$est_res_rev = trim($result->fields["est_res_rev"]);  
				$path_pdf = trim($result->fields["path_pdf"]);  
				$path_pdf_cedible = trim($result->fields["path_pdf_cedible"]);  
				$rutEmi = trim($result->fields["rut_emis_dte"]);  
				if($mnt_exen_dte == "")	$mnt_exen_dte = "0";
				if($mntneto_dte == "")	$mntneto_dte = "0";
				if($iva_dte == "")	$iva_dte = "0";
				if($mont_tot_dte == "")	$mont_tot_dte = "0";

                // Links con Bootstrap tooltips
                $codEmp = trim($_SESSION["_COD_EMP_USU_SESS"]);

                // Recibo Mercaderia
                $linkMerca = "<span class='text-secondary' title='No Recepcionado'><i class='bi bi-box-seam'></i></span>";
                if($est_recibo_mercaderias == "R") $linkMerca = "<a href='view_xml_resp.php?c=$codEmp&f=$folio_dte&t=$tipo_docu&o=RM' target='_blank' class='text-success' title='Recibo OK'><i class='bi bi-box-seam-fill'></i></a>";

                // Acuse de Recibo
                $linkAcuse = "<span class='text-secondary' title='No Recibido'><i class='bi bi-file-earmark-check'></i></span>";
                if($est_rec_doc == "R") $linkAcuse = "<a href='view_xml_resp.php?c=$codEmp&f=$folio_dte&t=$tipo_docu&o=AR' target='_blank' class='text-success' title='Acuse OK'><i class='bi bi-file-earmark-check-fill'></i></a>";
                if($est_rec_doc == "X") $linkAcuse = "<a href='view_xml_resp.php?c=$codEmp&f=$folio_dte&t=$tipo_docu&o=AR' target='_blank' class='text-danger' title='Rechazado'><i class='bi bi-file-earmark-x-fill'></i></a>";

                // Respuesta Comercial
                $linkComer = "<span class='text-secondary' title='Sin Respuesta'><i class='bi bi-shop'></i></span>";
                if($est_res_rev == "A") $linkComer = "<a href='view_xml_resp.php?c=$codEmp&f=$folio_dte&t=$tipo_docu&o=ARC' target='_blank' class='text-success' title='Aceptado'><i class='bi bi-shop'></i></a>";
                if($est_res_rev == "X") $linkComer = "<a href='view_xml_resp.php?c=$codEmp&f=$folio_dte&t=$tipo_docu&o=ARC' target='_blank' class='text-danger' title='Rechazado'><i class='bi bi-shop'></i></a>";
                if($est_res_rev == "R") $linkComer = "<a href='view_xml_resp.php?c=$codEmp&f=$folio_dte&t=$tipo_docu&o=ARC' target='_blank' class='text-warning' title='Con Discrepancias'><i class='bi bi-shop'></i></a>";

                // PDF y Cedible
                $linkPDF = "<a href='view_pdf.php?sUrlPdf=" . trim($path_pdf) . "' target='_blank' class='btn btn-outline-danger btn-action' title='Ver PDF'><i class='bi bi-file-pdf'></i></a>";
                $linkCedible = "<a href='view_pdf.php?sUrlPdf=" . trim($path_pdf_cedible) . "' target='_blank' class='btn btn-outline-secondary btn-action' title='PDF Cedible'><i class='bi bi-file-pdf-fill'></i></a>";

                // Reenviar
                $linkReenviar = "<a href='javascript:reenviarDTE(\"$folio_dte\",\"$tipo_docu\");' class='btn btn-outline-primary btn-action' title='Reenviar'><i class='bi bi-envelope'></i></a>";

                // Ceder
                if($est_xdte > 28 && $est_xdte != 77)
                    $linkCeder = "<a href='javascript:cederDocumento(\"$folio_dte\",\"$tipo_docu\",\"$mont_tot_dte\",\"$codEmp\");' class='btn btn-outline-info btn-action' title='Ceder DTE'><i class='bi bi-arrow-right-circle'></i></a>";
                else
                    $linkCeder = "<span class='btn btn-outline-secondary btn-action disabled' title='Requiere Aceptado SII'><i class='bi bi-arrow-right-circle'></i></span>";

                // XML
                $linkXML = "<a href='view_xml.php?nFolioDte=$folio_dte&nTipoDocu=$tipo_docu' target='_blank' class='btn btn-outline-warning btn-action' title='Ver XML'><i class='bi bi-code-slash'></i></a>";

                // No aplica para ciertos tipos
                if(in_array($tipo_docu, ["39","41","56","61","111","112"])){
                    $linkCedible = "<span class='text-muted' title='N/A'>-</span>";
                    $linkCeder = "<span class='text-muted' title='N/A'>-</span>";
                }
                if(in_array($tipo_docu, ["39","41","110","111","112"])){
                    $linkAcuse = "<span class='text-muted'>-</span>";
                    $linkMerca = "<span class='text-muted'>-</span>";
                    $linkComer = "<span class='text-muted'>-</span>";
                }

                // Badge de estado
                $badgeClass = "bg-secondary";
                if($est_xdte == 29) $badgeClass = "bg-success";
                elseif($est_xdte == 77 || $est_xdte == 1437) $badgeClass = "bg-danger";
                elseif($est_xdte == 45 || $est_xdte == 173) $badgeClass = "bg-warning text-dark";
                elseif($est_xdte == 157 || $est_xdte == 413) $badgeClass = "bg-info";
?>
                        <tr>
                            <td class="text-center">
                            <?php if($est_xdte < 5 || $est_xdte == 77): ?>
                                <input type="checkbox" class="form-check-input" name="del[]" value="<?php echo $folio_dte . "|" . $tipo_docu; ?>">
                            <?php endif; ?>
                            </td>
	                            <td class="actions-stack">
	                                <div class="icon-links">
                                    <?php echo $linkPDF . $linkCedible . $linkXML . $linkReenviar . $linkCeder; ?>
                                </div>
	                                <div class="status-links mt-2">
                                    <?php echo $linkAcuse . " " . $linkMerca . " " . $linkComer; ?>
                                </div>
                            </td>
	                            <td class="track-box">
	                            <?php if($trackid_xed != ""): ?>
	                                <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="trackDocumento('<?php echo h($folio_dte); ?>','<?php echo h($tipo_docu); ?>','<?php echo h($mont_tot_dte); ?>','<?php echo h($codEmp); ?>');">
	                                    <i class="bi bi-diagram-3 me-1"></i> Track
	                                </button>
	                                <small class="d-block text-muted mt-1"><?php echo h($trackid_xed); ?></small>
	                            <?php else: ?>
	                                <span class="badge text-bg-light border">Sin track</span>
	                            <?php endif; ?>
	                            </td>
	                            <td><small><?php echo h(poneTipo($tipo_docu)); ?></small></td>
                            <td class="text-end"><?php echo number_format($folio_dte,0,',','.'); ?></td>
	                            <td><span class="badge <?php echo $badgeClass; ?> badge-estado"><?php echo h(poneEstado($est_xdte)); ?></span></td>
	                            <td><?php echo h($fec_emi_dte); ?></td>
	                            <td><?php echo h(substr($fec_carg, 0, 10)); ?></td>
                            <td class="text-end"><?php echo number_format($mnt_exen_dte,0,',','.'); ?></td>
                            <td class="text-end"><?php echo number_format($mntneto_dte,0,',','.'); ?></td>
                            <td class="text-end"><?php echo number_format($iva_dte,0,',','.'); ?></td>
                            <td class="text-end fw-bold"><?php echo number_format($mont_tot_dte,0,',','.'); ?></td>
	                            <td><?php echo h($rut_rec_dte); ?></td>
	                            <td><small><?php echo h($nom_rec_dte); ?></small></td>
	                            <td><small><?php echo h($dir_rec_dte); ?></small></td>
	                            <td><small><?php echo h($com_rec_dte); ?></small></td>
                        </tr>
<?php
                    $result->MoveNext();
                }
            }
            else{
?>
                        <tr>
	                            <td colspan="16">
	                                <div class="empty-state">
	                                    <i class="bi bi-inbox text-muted"></i>
	                                    <h5 class="text-muted mt-3">No hay resultados para su b&uacute;squeda</h5>
	                                    <p class="mb-0">Pruebe ampliando el rango de fechas, el tipo de DTE o los estados seleccionados para localizar documentos emitidos.</p>
	                                </div>
                            </td>
                        </tr>
<?php
            }
?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <!-- Paginacion -->
	    <?php
	        if($totalFilas > 0):
	            $total_paginas = ceil($totalFilas / $TAMANO_PAGINA);
	            $paginasLista = min($total_paginas, 20);
	            $qrstring .= "&orden=" . $orden . "&orni=" . $descAsc;

	            $inicio = floor(($pagina - 1) / $paginasLista) * $paginasLista + 1;
	            $fin = min($inicio + $paginasLista - 1, $total_paginas);
	    ?>
	    <div class="card-footer bg-white">
	        <div class="pagination-wrap">
	        <p class="text-muted small mb-0">
	            Mostrando p&aacute;gina <?php echo $pagina; ?> de <?php echo $total_paginas; ?>
	            (<?php echo $totalFilas; ?> registros encontrados)
	        </p>
	        <nav aria-label="Paginacion">
	            <ul class="pagination pagination-sm mb-0">
<?php
    if ($paginasLista > 1) {
        // Anterior
        if ($pagina > 1) {
            echo '<li class="page-item"><a class="page-link" href="list_dte_v3.php?pagina='.($pagina-1).$qrstring.'">&laquo;</a></li>';
        }

        // Numeros de pagina
        for ($i = $inicio; $i <= $fin; $i++) {
            if ($pagina == $i) {
                echo '<li class="page-item active"><span class="page-link">'.$i.'</span></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="list_dte_v3.php?pagina='.$i.$qrstring.'">'.$i.'</a></li>';
            }
        }

        // Siguiente
        if ($pagina < $total_paginas) {
            echo '<li class="page-item"><a class="page-link" href="list_dte_v3.php?pagina='.($pagina+1).$qrstring.'">&raquo;</a></li>';
        }
    }
?>
            </ul>
        </nav>
	        </div>
    </div>
    <?php endif; ?>
</div>

<?php
    }
?>
	</div>

<!-- Bootstrap 5 JS y Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuracion comun de Flatpickr
    var fpConfig = {
        dateFormat: "Y-m-d",
        locale: "es",
        allowInput: true,
        disableMobile: true,
        clickOpens: true
    };

    // Inicializar cada campo individualmente para evitar duplicados
    if (document.getElementById('fecha1') && !document.getElementById('fecha1')._flatpickr) {
        flatpickr("#fecha1", fpConfig);
    }
    if (document.getElementById('fecha2') && !document.getElementById('fecha2')._flatpickr) {
        flatpickr("#fecha2", fpConfig);
    }
    if (document.getElementById('fechac1') && !document.getElementById('fechac1')._flatpickr) {
        flatpickr("#fechac1", fpConfig);
    }
    if (document.getElementById('fechac2') && !document.getElementById('fechac2')._flatpickr) {
        flatpickr("#fechac2", fpConfig);
    }

    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el);
    });
});
</script>

</body>
</html>
