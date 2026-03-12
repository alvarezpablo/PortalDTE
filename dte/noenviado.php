<?php 
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");	

	include("../include/config.php");
	include("../include/db_lib.php");  
	include("../include/ver_aut.php");
    include("../include/ver_emp_adm.php"); 

		function requestValue($key){
			return isset($_GET[$key]) ? trim($_GET[$key]) : "";
		}

		function h($value){
			return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
		}

		function selectedAttr($value, $current){
			return ((string)$value === (string)$current) ? ' selected="selected"' : '';
		}

		$orden = requestValue("orden");	// orden campo
		$descAsc = requestValue("orni"); // orden nivel

	if($descAsc == "") $descAsc = "1";
	if($orden == "") $orden = "X.fec_carg";	
	if($orden == "1") $orden = "D.folio_dte";
	if($orden == "2") $orden = "D.fec_emi_dte";
	if($orden == "3") $orden = "D.rut_rec_dte";
	if($orden == "4") $orden = "D.mont_tot_dte";

	if($descAsc == "1") $descAsc = "DESC";
	if($descAsc == "2") $descAsc = "ASC";

		$tipo = requestValue("tipo");
		$folio = requestValue("folio");
		$fecha1 = requestValue("fecha1");
		$fecha2 = requestValue("fecha2");
		$fechac1 = requestValue("fechac1");
		$fechac2 = requestValue("fechac2");
		$estado = requestValue("estado");
		$rutInput = requestValue("rut");
		$rut = $rutInput;
		$pagina = requestValue("pagina");
		$nCodEmprSel = requestValue("nCodEmprSel");

	if($rut != ""){
		$aRut = explode("-",$rut);
		$rut = $aRut[0];
	}

		$AAR = requestValue("AAR");		// Acuse de recibo ok
		$RAR = requestValue("RAR");		// acuse de recubi rechazado
		$SAR = requestValue("SAR");		// sin acuse de recibo
		$AAC = requestValue("AAC");		// acptado comercialmente
		$RAC = requestValue("RAC");		// rechazado comercialmente
		$SAC = requestValue("SAC");		// sin respuesta comercial
		$CRM = requestValue("CRM");		// con recibo de mercaderia
		$SRM = requestValue("SRM");		// sin recibo de mercaderia
		$hasSearch = !empty($_GET);
		$tipoSeleccionado = $hasSearch ? $tipo : "33";

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
	$qrstring .= "&nCodEmprSel=" . $nCodEmprSel;	

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
                        case 1197:
                        $sEstadoDte = "Rechazado Comercialmente";
                        break; 
		}
		return $sEstadoDte;
	}

	function poneTipo($tipo_docu){
			$sEstadoDte = "";

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
?>
<!doctype html>
	<html lang="es">
 <head>
  <meta charset="latin1">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="Generator" content="EditPlus�">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
	  <title>DTE no enviados</title>
	  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <style>
	 :root{
		--primary-dark:#001f3f;
		--primary-color:#0b5ed7;
		--border-color:#dbe5f0;
		--soft-bg:#f8fbff;
		--text-muted:#64748b;
	 }
	 body{background:#eef3f8;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
	 .page-shell{max-width:1600px;margin:0 auto}
	 .topbar{display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1rem}
	 .eyebrow{font-size:.78rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--primary-color);margin-bottom:.2rem}
	 .summary-chip{display:inline-flex;align-items:center;gap:.45rem;padding:.5rem .85rem;border-radius:999px;background:#fff;border:1px solid var(--border-color);color:#334155;font-size:.82rem;box-shadow:0 6px 18px rgba(15,23,42,.05)}
	 .panel{background:#fff;border:1px solid rgba(15,23,42,.06);border-radius:18px;box-shadow:0 14px 32px rgba(15,23,42,.08);overflow:hidden}
	 .panel + .panel{margin-top:1rem}
	 .panel-header{background:linear-gradient(135deg,var(--primary-dark) 0%,var(--primary-color) 100%);color:#fff;padding:.95rem 1.15rem}
	 .panel-header-title{display:flex;align-items:center;gap:.55rem;font-weight:600}
	 .panel-header-note{font-size:.82rem;color:rgba(255,255,255,.82);margin-top:.2rem}
	 .panel-body{padding:1rem 1.15rem}
	 .form-label{font-size:.82rem;font-weight:700;color:#0f172a;margin-bottom:.35rem}
	 .form-control,.form-select{font-size:.85rem;border-color:#cbd5e1}
	 .form-control:focus,.form-select:focus{border-color:#86b7fe;box-shadow:0 0 0 .2rem rgba(13,110,253,.12)}
	 .date-range{display:flex;align-items:center;gap:.45rem;flex-wrap:wrap}
	 .date-field{display:flex;align-items:center;gap:.45rem;flex:1 1 180px;min-width:180px}
	 .range-separator{font-size:.82rem;color:var(--text-muted);font-weight:600}
	 .calendar-trigger{cursor:pointer;border:1px solid #93c5fd;border-radius:.5rem;background:#eff6ff;padding:.2rem}
	 .note-card{background:var(--soft-bg);border:1px solid var(--border-color);border-radius:14px;padding:.85rem .95rem;color:#334155;font-size:.83rem}
	 .note-card strong{color:#0f172a}
	 .action-bar{display:flex;justify-content:space-between;align-items:center;gap:.75rem;flex-wrap:wrap;margin-top:1rem}
	 .table-responsive{max-height:70vh;overflow:auto}
	 .table thead th{background:var(--primary-dark);color:#fff;font-size:.78rem;font-weight:700;white-space:nowrap;vertical-align:middle;position:sticky;top:0;z-index:1}
	 .table tbody td{font-size:.78rem;vertical-align:middle}
	 .table tbody tr.alt td{background:#f8fbff}
	 .table tbody tr:hover td{background:#eef6ff}
	 .sort-link{display:inline-flex;align-items:center;gap:.35rem;color:#fff;text-decoration:none}
	 .sort-link:hover{color:#dbeafe}
	 .action-links{display:flex;align-items:center;gap:.45rem;flex-wrap:wrap;min-width:120px}
	 .action-links img{display:block}
	 .status-pill{display:inline-flex;align-items:center;padding:.3rem .65rem;border-radius:999px;background:#e8f1ff;color:#0b5ed7;font-weight:700;font-size:.74rem}
	 .results-toolbar{display:flex;justify-content:space-between;align-items:center;gap:.75rem;flex-wrap:wrap;padding:.85rem 1.15rem;border-bottom:1px solid #e5edf5;background:#f8fbff}
	 .results-footer{display:flex;justify-content:space-between;align-items:center;gap:.9rem;flex-wrap:wrap;padding:.9rem 1.15rem;border-top:1px solid #e5edf5;background:#fff}
	 .paging{display:flex;align-items:center;gap:.45rem;flex-wrap:wrap}
	 .paging a,.paging span{display:inline-flex;align-items:center;justify-content:center;min-width:2rem;height:2rem;border-radius:999px;border:1px solid #d0d7e2;background:#fff;color:#0f172a;text-decoration:none;padding:0 .75rem;font-size:.82rem}
	 .paging a:hover{background:#eff6ff;border-color:#93c5fd}
	 .paging .current{background:var(--primary-dark);border-color:var(--primary-dark);color:#fff}
	 .empty-state{padding:3.5rem 1rem;text-align:center;color:#64748b}
	 .empty-state i{font-size:2.5rem;display:block;margin-bottom:.75rem;color:#94a3b8}
	 #tooltip{position:absolute;visibility:hidden;z-index:2000;max-width:260px;padding:.45rem .65rem;border-radius:.6rem;background:rgba(15,23,42,.92);color:#fff;font-size:.75rem;box-shadow:0 10px 24px rgba(15,23,42,.2)}
	 div.dhtmlx_window_active,div.dhx_modal_cover_dv{position:fixed !important}
	 @media (max-width: 767.98px){
		body{padding:1rem !important}
		.panel-header,.panel-body,.results-toolbar,.results-footer{padding:.9rem}
		.table-responsive{max-height:none}
	 }

  </style>
<script type="text/javascript">
<!--
	function ceder_documento(nFolioDte,nTipDoc,nMontTot,nCodEmp) {
   //var sUrl = "http://portaldte.opendte.cl/dte/form_reenvio.php?nFolio=" + nFolioDte + "&nTipoDTE=" + nTipDoc;
   var sUrl = "ceder_documento.php?nFolio=" + nFolioDte + "&nTipoDTE=" + nTipDoc + "&nMontTot="+nMontTot+"&nCodEmp="+nCodEmp;
    wTipoMot=window.open(sUrl, "reenviar","dependent=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,width=1000,height=800");
        var centroAncho = (screen.width/2)  - (1000);
        var centroAlto  = (screen.height/2) - (400);
        wTipoMot.moveTo(centroAlto,centroAncho);
}
	
        function track_documento(nFolioDte,nTipDoc,nMontTot,nCodEmp) {
   //var sUrl = "http://portaldte.opendte.cl/dte/form_reenvio.php?nFolio=" + nFolioDte + "&nTipoDTE=" + nTipDoc;
   var sUrl = "view_track.php?nFolioDte=" + nFolioDte + "&nTipoDocu=" + nTipDoc + "&nMontTot="+nMontTot+"&nCodEmp="+nCodEmp;
    wTipoMot=window.open(sUrl, "reenviar","dependent=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,width=600,height=600");
        var centroAncho = (screen.width/2)  - (800);
        var centroAlto  = (screen.height/2) - (200);
        wTipoMot.moveTo(centroAlto,centroAncho);
}

function Reenviar(nFolioDte,nTipDoc) {
   var sUrl = "form_reenvio.php?nFolio=" + nFolioDte + "&nTipoDTE=" + nTipDoc;
    wTipoMot=window.open(sUrl, "reenviar","dependent=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,width=400,height=400");
        var centroAncho = (screen.width/2)  - (400);
        var centroAlto  = (screen.height/2) - (200);
        wTipoMot.moveTo(centroAlto,centroAncho);
}

    function chSelDelEmp(){
      var F = document._FDEL;
    
      for(i=0; i < F.elements.length; i++){
        if(F.elements[i].name == "del[]"){
            if(F.elements[i].checked == true)
              return true;
          
        }
      }
      return false;
    }
    
    function chDelEmp(){
      if(chSelDelEmp() == true){
        if(confirm("Confirma la eliminaci�n de los DTE ? "))
          document._FDEL.submit();
      }
      else
        alert("Seleccione el DTE a Eliminar");
    }
    
    function chDchALL(){
      var F = document._FDEL;
      var obj = F.clientslistSelectAll;
      
      if(obj.checked == true){
        for(i=0; i < F.elements.length; i++){
           if(F.elements[i].name == "del[]")
              F.elements[i].checked = true;                                 
        }
      }
      else{
        for(i=0; i < F.elements.length; i++){
           if(F.elements[i].name == "del[]")
              F.elements[i].checked = false;                                 
        }
      }
    }

    function chListBoxSelect(obj, valor){
		obj.options[0].selected = true;

        for(i=0; i < obj.length; i++){
          if(obj.options[i].value == valor){
            obj.options[i].selected = true;
			break;
		  }
        }
      }

    function chCheckSele(obj, valor){
		obj.checked = true;
		if(valor == ""){
			obj.checked = false;
		}
	}

	function valida(){
		var F = document._BUSCA;
/*
	$fecha1 = trim($_GET["fecha1"]);
	$fecha2 = trim($_GET["fecha2"]);
	$fechac1 = trim($_GET["fechac1"]);
	$fechac2 = trim($_GET["fechac2"]);
	$rut = trim($_GET["rut"]);

		*/

		return true;
	}

	function bajarExcel(){
		var F = document._BUSCA;

		if(confirm("Bajar a Excel el resultado de la busqueda?. Se descargan un m�ximo de 10.000 registros.")){
			document._BUSCA.action = "excel_dte_v2.php";
			document._BUSCA.target = "_blank";
			document._BUSCA.submit();
		}
	}
	function listar(){
		if(valida() == true){
			document._BUSCA.action = "noenviado.php";
			document._BUSCA.target = "_self";
			document._BUSCA.submit();
		}
	}

	function nm_mostra_hint(nm_obj, nm_evt, nm_mens) {
		if (nm_mens == "") {
			return;
		}
		tem_hint = true;
		if (document.layers) {
			theString = "<DIV CLASS='ttip'>" + nm_mens + "</DIV>";
			document.tooltip.document.write(theString);
			document.tooltip.document.close();
			document.tooltip.left = nm_evt.pageX + 14;
			document.tooltip.top = nm_evt.pageY + 2;
			document.tooltip.visibility = "show";
		}
		else {
			if (document.getElementById) {
				nmdg_nav = navigator.appName;
				elm = document.getElementById("tooltip");
				elml = nm_obj;
				elm.innerHTML = nm_mens;
				if (nmdg_nav == "Netscape") {
					elm.style.height = elml.style.height;
					elm.style.top = nm_evt.pageY + 2 + 'px';
					elm.style.left = nm_evt.pageX + 14 + 'px';
				}
				else {
					elm.style.top = nm_evt.y + document.body.scrollTop + 10 + 'px';
					elm.style.left = nm_evt.x + document.body.scrollLeft + 10 + 'px';
				}
				elm.style.visibility = "visible";
			}
		}
	}
	function nm_apaga_hint() {
		if (!tem_hint) {
			return;
		}
		tem_hint = false;
		if (document.layers) {
			document.tooltip.visibility = "hidden";
		}
		else {
			if (document.getElementById) {
				elm.style.visibility = "hidden";
			}
		}
	}

//-->
</script>
		  <!-- calendar  -->
		  <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
		  <script type="text/javascript" src="../javascript/calendar.js"></script>
		  <script type="text/javascript" src="../javascript/lang/calendar-es.js"></script>
		  <script type="text/javascript" src="../javascript/calendar-setup.js"></script>
		  <!-- calendar fin -->

 </head>
	 <body class="p-3">
	<div class="page-shell">
		<div id="tooltip"></div>
		<div class="topbar">
			<div>
				<div class="eyebrow">DTE Emitidos</div>
				<h1 class="h4 mb-0">DTE no enviados al SII</h1>
				<div class="text-secondary small mt-1">Listado operativo con filtros, ordenamiento, paginacion y acciones legacy preservadas.</div>
			</div>
			<div class="summary-chip"><i class="bi bi-shield-check"></i> Estructura visual compacta compatible con la operatoria actual</div>
		</div>

		<form name="_BUSCA" method="get" action="">
			<div class="panel">
				<div class="panel-header">
					<div class="panel-header-title"><i class="bi bi-funnel"></i><span>Filtros de busqueda</span></div>
					<div class="panel-header-note">Puede combinar tipo, folio, rangos de fecha, RUT receptor y empresa sin alterar el contrato GET del modulo.</div>
				</div>
				<div class="panel-body">
					<div class="row g-3">
						<div class="col-xl-3 col-md-6">
							<label class="form-label" for="tipo">Tipo DTE</label>
							<select name="tipo" id="tipo" class="form-select form-select-sm">
								<option value="33"<?php echo selectedAttr("33", $tipoSeleccionado); ?>>Factura Electr&oacute;nica</option>
								<option value="34"<?php echo selectedAttr("34", $tipoSeleccionado); ?>>Factura No Afecta o Exenta Electr&oacute;nica</option>
								<option value="39"<?php echo selectedAttr("39", $tipoSeleccionado); ?>>Boleta Electr&oacute;nica</option>
								<option value="41"<?php echo selectedAttr("41", $tipoSeleccionado); ?>>Boleta Exenta Electr&oacute;nica</option>
								<option value="43"<?php echo selectedAttr("43", $tipoSeleccionado); ?>>Liquidaci&oacute;n Factura Electr&oacute;nica</option>
								<option value="46"<?php echo selectedAttr("46", $tipoSeleccionado); ?>>Factura de Compra Electr&oacute;nica</option>
								<option value="52"<?php echo selectedAttr("52", $tipoSeleccionado); ?>>Gu&iacute;a de Despacho Electr&oacute;nica</option>
								<option value="56"<?php echo selectedAttr("56", $tipoSeleccionado); ?>>Nota de D&eacute;bito Electr&oacute;nica</option>
								<option value="61"<?php echo selectedAttr("61", $tipoSeleccionado); ?>>Nota de Cr&eacute;dito Electr&oacute;nica</option>
								<option value="110"<?php echo selectedAttr("110", $tipoSeleccionado); ?>>Factura de Exportaci&oacute;n Electr&oacute;nica</option>
								<option value="111"<?php echo selectedAttr("111", $tipoSeleccionado); ?>>Nota de D&eacute;bito de Exportaci&oacute;n Electr&oacute;nica</option>
								<option value="112"<?php echo selectedAttr("112", $tipoSeleccionado); ?>>Nota de Cr&eacute;dito de Exportaci&oacute;n Electr&oacute;nica</option>
								<option value=""<?php echo selectedAttr("", $tipoSeleccionado); ?>>Todos</option>
							</select>
						</div>
						<div class="col-xl-2 col-md-6">
							<label class="form-label" for="folio">Folio DTE</label>
							<input type="text" name="folio" id="folio" maxlength="18" value="<?php echo h($folio); ?>" class="form-control form-control-sm">
						</div>
						<div class="col-xl-3 col-md-6">
							<label class="form-label" for="rut">RUT Receptor</label>
							<input type="text" name="rut" id="rut" maxlength="10" value="<?php echo h($rutInput); ?>" class="form-control form-control-sm">
						</div>
						<div class="col-xl-4 col-md-6">
							<label class="form-label" for="nCodEmprSel">Ver Empresa</label>
							<select name="nCodEmprSel" id="nCodEmprSel" class="form-select form-select-sm">
								<option value=""<?php echo selectedAttr("", $nCodEmprSel); ?>>Todas las Empresas</option>
								<option value="72"<?php echo selectedAttr("72", $nCodEmprSel); ?>>AGRICOLA NACIONAL SAC E I</option>
								<option value="73"<?php echo selectedAttr("73", $nCodEmprSel); ?>>ANASAC AMBIENTAL SA</option>
								<option value="70"<?php echo selectedAttr("70", $nCodEmprSel); ?>>ANASAC CHILE S.A.</option>
								<option value="74"<?php echo selectedAttr("74", $nCodEmprSel); ?>>ANASAC COMERCIAL SPA</option>
								<option value="151"<?php echo selectedAttr("151", $nCodEmprSel); ?>>ANASAC COMERCIAL DOS SPA</option>
								<option value="318"<?php echo selectedAttr("318", $nCodEmprSel); ?>>DFM PHARMA SPA</option>
								<option value="71"<?php echo selectedAttr("71", $nCodEmprSel); ?>>DIFEM LABORATORIOS S A</option>
							</select>
						</div>
						<div class="col-xl-6">
							<label class="form-label">Fecha Emision</label>
							<div class="date-range">
								<div class="date-field">
									<input type="text" name="fecha1" id="fecha1" maxlength="10" value="<?php echo h($fecha1); ?>" class="form-control form-control-sm">
									<img src="../img.gif" id="f_trigger_ini" class="calendar-trigger" title="Date selector" onmouseover="this.style.background='#bfdbfe';" onmouseout="this.style.background=''" alt="Calendario">
									<script type="text/javascript">Calendar.setup({inputField:"fecha1",ifFormat:"%Y-%m-%d",button:"f_trigger_ini",align:"Tl",singleClick:true});</script>
								</div>
								<span class="range-separator">a</span>
								<div class="date-field">
									<input type="text" name="fecha2" id="fecha2" maxlength="10" value="<?php echo h($fecha2); ?>" class="form-control form-control-sm">
									<img src="../img.gif" id="f_trigger_fin" class="calendar-trigger" title="Date selector" onmouseover="this.style.background='#bfdbfe';" onmouseout="this.style.background=''" alt="Calendario">
									<script type="text/javascript">Calendar.setup({inputField:"fecha2",ifFormat:"%Y-%m-%d",button:"f_trigger_fin",align:"Tl",singleClick:true});</script>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<label class="form-label">Fecha Carga</label>
							<div class="date-range">
								<div class="date-field">
									<input type="text" name="fechac1" id="fechac1" maxlength="10" value="<?php echo h($fechac1); ?>" class="form-control form-control-sm">
									<img src="../img.gif" id="f_trigger_c_ini" class="calendar-trigger" title="Date selector" onmouseover="this.style.background='#bfdbfe';" onmouseout="this.style.background=''" alt="Calendario">
									<script type="text/javascript">Calendar.setup({inputField:"fechac1",ifFormat:"%Y-%m-%d",button:"f_trigger_c_ini",align:"Tl",singleClick:true});</script>
								</div>
								<span class="range-separator">a</span>
								<div class="date-field">
									<input type="text" name="fechac2" id="fechac2" maxlength="10" value="<?php echo h($fechac2); ?>" class="form-control form-control-sm">
									<img src="../img.gif" id="f_trigger_c_fin" class="calendar-trigger" title="Date selector" onmouseover="this.style.background='#bfdbfe';" onmouseout="this.style.background=''" alt="Calendario">
									<script type="text/javascript">Calendar.setup({inputField:"fechac2",ifFormat:"%Y-%m-%d",button:"f_trigger_c_fin",align:"Tl",singleClick:true});</script>
								</div>
							</div>
						</div>
					</div>
					<div class="note-card mt-3">
						<strong>Nota operativa:</strong> se conserva el mismo envio GET hacia <code>noenviado.php</code>, asi como la exportacion Excel disponible en el JavaScript legacy aunque el boton permanezca oculto tal como estaba en la vista original.
					</div>
					<div class="action-bar">
						<div class="small text-secondary">Use rangos de fechas para acotar el listado de documentos pendientes de envio.</div>
						<div class="d-flex gap-2 flex-wrap">
							<button type="button" class="btn btn-primary btn-sm" onclick="listar();"><i class="bi bi-search me-1"></i>Listar</button>
							<button type="button" class="btn btn-outline-secondary btn-sm" onclick="location.href='noenviado.php';"><i class="bi bi-x-circle me-1"></i>Limpiar</button>
						</div>
					</div>
				</div>
			</div>
		</form>
		<?php
			if($hasSearch){
		?>
		<div class="panel">
			<div class="panel-header">
				<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
					<div>
						<div class="panel-header-title"><i class="bi bi-table"></i><span>Resultados de la busqueda</span></div>
						<div class="panel-header-note">Se mantienen el borrado selectivo, el orden por columnas y los accesos a PDF, XML y track del documento.</div>
					</div>
					<span class="badge rounded-pill text-bg-light text-primary-emphasis">40 registros por pagina</span>
				</div>
			</div>
			<form name="_FDEL" method="post" action="pro_dte.php">
			<!-- <form name="_FDEL" method="post" action="dte/pro_dte.php"> -->
				<input type="hidden" name="sAccion" value="E">
				<div class="results-toolbar">
					<div class="small text-secondary">Seleccione documentos solo cuando corresponda el flujo de eliminacion legacy.</div>
					<button type="button" class="btn btn-outline-danger btn-sm" name="bname_remove_selected" onclick="chDelEmp();"><i class="bi bi-trash me-1"></i>Eliminar seleccionados</button>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm align-middle mb-0">
						<thead>
							<tr>
								<th class="text-center"><input type="checkbox" class="form-check-input" name="clientslistSelectAll" value="true" onclick="chDchALL();"></th>
								<th>Operaciones</th>
								<th>Empresa</th>
								<th>Tipo</th>
								<th><a class="sort-link" href="noenviado.php?a=1<?php echo $qrsFolio; ?>">Folio <?php echo $fleFolio; ?></a></th>
								<th>Estado</th>
								<th><a class="sort-link" href="noenviado.php?a=1<?php echo $qrsFech; ?>">F.Emision <?php echo $fleFech; ?></a></th>
								<th><a class="sort-link" href="noenviado.php?a=1<?php echo $qrsCarga; ?>">F.Carga <?php echo $fleCarga; ?></a></th>
								<th class="text-end">Exento</th>
								<th class="text-end">Neto</th>
								<th class="text-end">IVA</th>
								<th class="text-end"><a class="sort-link justify-content-end" href="noenviado.php?a=1<?php echo $qrsTotal; ?>">Total <?php echo $fleTotal; ?></a></th>
								<th><a class="sort-link" href="noenviado.php?a=1<?php echo $qrsRut; ?>">Rut <?php echo $fleRut; ?></a></th>
								<th>Receptor</th>
								<th>Direccion</th>
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
					X.est_envio, X.est_recibo_mercaderias, X.est_rec_doc, 
					X.est_res_rev, X.path_pdf, X.path_pdf_cedible, D.rut_emis_dte, (SELECT rs_empr FROM empresa WHERE codi_empr = D.codi_empr) rs_empr ";
		$sql = " FROM 
			xmldte X, 
			dte_enc D
		WHERE
			D.tipo_docu = X.tipo_docu AND
			D.folio_dte = X.folio_dte AND
			D.codi_empr = X.codi_empr "; //AND
//			D.codi_empr = '". trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";

		if($tipo != "")	$sql .= " AND D.tipo_docu = '" . str_replace("'","''",$tipo) . "'";
		if($folio != "")	$sql .= " AND CAST(D.folio_dte as varchar)= '" . str_replace("'","''",$folio) . "'";
//		if($estado != "")	$sql .= " AND X.est_xdte in(" . str_replace("'","",$estado) . ")";
		$sql .= " AND X.est_xdte < 13 "; 

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

		if($nCodEmprSel == "")
			$sql .= " AND D.codi_empr IN (71,73,72,74,318,70,151) ";
		else
			$sql .= " AND D.codi_empr IN ($nCodEmprSel) ";


/*
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
*/

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
				$rs_empr = trim($result->fields["rs_empr"]);  
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

	//			number_format($nMntNeto,0,',','.');
				$linkMerca = "<a href=\"javascript:alert('Recibo de Mercader�a No Recepcionado');\" onMouseover=\"nm_mostra_hint(this, event, 'Recibo de Mercader�a No Recepcionado')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/rm_no.png' alt='Recibo No Recepcionado'></a>";
				if($est_recibo_mercaderias == "R")	$linkMerca = "<a href=\"view_xml_resp.php?c=" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "&f=" . $folio_dte . "&t=" . $tipo_docu . "&o=RM\" target='_blank' onMouseover=\"nm_mostra_hint(this, event, 'Recibo de Mercader�a OK')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/rm_ok.png' alt='Recibo Mercader�a OK'></a>";

				$linkAcuse = "<a href=\"javascript:alert('Acuse de Recibo No Recepcionado');\" onMouseover=\"nm_mostra_hint(this, event, 'Acuse de Recibo No Recepcionado')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ar_no.png' alt='Acuse de Recibo No Recepcionado'></a>";
				if($est_rec_doc == "R")	
					$linkAcuse = "<a href=\"view_xml_resp.php?c=" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "&f=" . $folio_dte . "&t=" . $tipo_docu . "&o=AR\" target='_blank' onMouseover=\"nm_mostra_hint(this, event, 'Acuse de Recibo OK')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ar_ok.png' alt='Acuse de Recibo OK'\"></a>";
				if($est_rec_doc == "X")	$linkAcuse = "<a href=\"view_xml_resp.php?c=" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "&f=" . $folio_dte . "&t=" . $tipo_docu . "&o=AR\" target='_blank' onMouseover=\"nm_mostra_hint(this, event, 'Acuse de Recibo Rechazado')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ar_nook.png' alt='Acuse de Recibo Rechazado'></a>";

				$linkComer = "<a href=\"javascript:alert('Respuesta Comercial no Recibida');\" onMouseover=\"nm_mostra_hint(this, event, 'Respuesta Comercial No Recibida')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_no.png' alt='Respuesta Comercial no Recibida'></a>";
				if($est_res_rev == "A")	$linkComer = "<a href=\"view_xml_resp.php?c=" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "&f=" . $folio_dte . "&t=" . $tipo_docu . "&o=ARC\" target='_blank' onMouseover=\"nm_mostra_hint(this, event, 'Aceptado Comercialmente')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_ok.png' alt='Aceptado Comercialmente'></a>";
				if($est_res_rev == "X")	$linkComer = "<a href=\"view_xml_resp.php?c=" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "&f=" . $folio_dte . "&t=" . $tipo_docu . "&o=ARC\" target='_blank' onMouseover=\"nm_mostra_hint(this, event, 'Rechazado Comercialmente')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_nook.png' alt='Rechazado Comercialmente'></a>";
				if($est_res_rev == "R")	$linkComer = "<a href=\"view_xml_resp.php?c=" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "&f=" . $folio_dte . "&t=" . $tipo_docu . "&o=ARC\" target='_blank' onMouseover=\"nm_mostra_hint(this, event, 'Aceptado Con Discrepancias')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_warn.png' alt='Aceptado Con Discrepancias'></a>";

				//$linkCedible = "<a href=\"view_pdf_rem.php?c=" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "&f=" . $folio_dte . "&t=" . $tipo_docu . "&cd=true\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'PDF Cedible')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/grp__NM__nm_icon_pdf_cede.gif' alt='PDF Cedible'></a>";
				$linkCedible = "<a href=\"view_pdf.php?sUrlPdf=" . trim($path_pdf_cedible) . "\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'PDF Cedible')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/grp__NM__nm_icon_pdf_cede.gif' alt='PDF Cedible'></a>";					

//				$linkPDF = "<a href=\"view_pdf_rem.php?c=" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "&f=" . $folio_dte . "&t=" . $tipo_docu . "\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'PDF')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/grp__NM__nm_icon_pdf_cede.gif' alt='PDF'></a>";
				$linkPDF = "<a href=\"view_pdf.php?sUrlPdf=" . trim($path_pdf) . "\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'PDF')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/grp__NM__nm_icon_pdf_cede.gif' alt='PDF'></a>";



//				$linkPDF = "<a href=\"pdf.php?r=" . trim($path_pdf) . "&f=O&t=" . $tipo_docu ."\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'PDF')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/grp__NM__nm_icon_pdf_cede.gif' alt='PDF'></a>";

			//	$linkCedible = "<a href=\"pdf.php?r=" . trim($path_pdf) . "&f=C&t=" . $tipo_docu ."\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'PDF Cedible')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/grp__NM__nm_icon_pdf_cede.gif' alt='PDF Cedible'></a>";

				$linkReenviar = "<a href=\"javascript:Reenviar('" . $folio_dte . "','" . $tipo_docu . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Reenviar DTE')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/email.png' height=\"17\" alt='Reenviar'></a>";

				if($est_xdte > 28 && $est_xdte != 77)
					$linkCeder = "<a href=\"javascript:ceder_documento('" . $folio_dte . "','" . $tipo_docu . "','" . $mont_tot_dte . "','" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Ceder DTE')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/factor.png' height='17' alt='Ceder'></a>";
				else
					$linkCeder = "<a href=\"javascript:alert('Para Ceder el DTE debe estar Aceptado por SII ');\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'Para Ceder el DTE debe estar Aceptado por SII')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/factor.png' height='17' alt='Ceder'></a>";


				if(file_exists($path_pdf_cedible) == false){
//					$linkCedible = "<a href=\"pdf.php?r=" . trim($path_pdf) . "&f=R&t=" . $tipo_docu ."&e=".$rutEmi ."&n=".$folio_dte."\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'PDF Cedible')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/grp__NM__nm_icon_pdf_cede.gif' alt='PDF Cedible'></a>";
					$linkCedible = "<a href=\"view_pdf.php?sUrlPdf=" . trim($path_pdf_cedible) . "\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'PDF Cedible')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/grp__NM__nm_icon_pdf_cede.gif' alt='PDF Cedible'></a>";					
				}


				if($tipo_docu == "39" || $tipo_docu == "41" || $tipo_docu == "56" || $tipo_docu == "61" || $tipo_docu == "111" || $tipo_docu == "112"){
					$linkCedible = "<img src='../img/na.png' height=\"27\" alt='No Aplica'>";
					$linkCeder = ""; //"<img src='../img/na.png' height=\"17\" alt='No Aplica'>";
				}
				if($tipo_docu == "39" || $tipo_docu == "41" || $tipo_docu == "110" || $tipo_docu == "111" || $tipo_docu == "112"){
					$linkAcuse = ""; //"<img src='../img/na.png' height=\"17\" alt='No Aplica'>";
					$linkMerca = ""; //"<img src='../img/na.png' height=\"17\" alt='No Aplica'>";
					$linkComer = ""; //"<img src='../img/na.png' height=\"17\" alt='No Aplica'>";
				}

                                        $linkTrack = "<a href=\"javascript:track_documento('" . $folio_dte . "','" . $tipo_docu . "','" . $mont_tot_dte . "','" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Track DTE')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/track.png' height='17' alt='Track'></a>"; 

		?>

						<tr class="<?php echo $sClassRow; ?>">
						<?php 
							if(($est_xdte < 5 || $est_xdte == 77))
								echo "<td class=\"text-center\"><input type=\"checkbox\" class=\"form-check-input\" name=\"del[]\" value=\"" . $folio_dte . "|" . $tipo_docu . "\"></td>";
							else
								echo "<td class=\"text-center\">&nbsp;</td>";
						?>
							<td>
								<div class="action-links">
									<?php echo $linkPDF; ?>
									<?php echo $linkCedible; ?>
									<a href="view_xml.php?nFolioDte=<?php echo $folio_dte; ?>&nTipoDocu=<?php echo $tipo_docu; ?>" target="_blank"><img src="../img/sys__NM__xml.jpeg" alt="XML"></a>
									<?php echo $linkTrack; ?>
								</div>
							</td>
							<td><?php echo h($rs_empr); ?></td>
							<td><?php echo h(poneTipo($tipo_docu)); ?></td>
							<td class="text-end"><?php echo number_format($folio_dte,0,',','.'); ?></td>
							<td><span class="status-pill"><?php echo h(poneEstado($est_xdte)); ?></span></td>
							<td><?php echo h($fec_emi_dte); ?></td>
							<td><?php echo h($fec_carg); ?></td>
							<td class="text-end"><?php echo number_format($mnt_exen_dte,0,',','.'); ?></td>
							<td class="text-end"><?php echo number_format($mntneto_dte,0,',','.'); ?></td>
							<td class="text-end"><?php echo number_format($iva_dte,0,',','.'); ?></td>
							<td class="text-end"><?php echo number_format($mont_tot_dte,0,',','.'); ?></td>
							<td><?php echo h($rut_rec_dte); ?></td>
							<td><?php echo h($nom_rec_dte); ?></td>
							<td><?php echo h($dir_rec_dte); ?></td>
							<td><?php echo h($com_rec_dte); ?></td>
						</tr>
		<?php

				if($sClassRow == "alt")
					$sClassRow = "";
				else
					$sClassRow = "alt";
				$result->MoveNext(); 
			}
		}
		else{
	?>
						<tr>
							<td colspan="16">
								<div class="empty-state">
									<i class="bi bi-inbox"></i>
									<strong>No hay resultados para su busqueda</strong>
									<div class="mt-2">Ajuste los filtros y vuelva a listar.</div>
								</div>
							</td>
						</tr>
	<?php
		}
	?>
						</tbody>
					</table>
				</div>

				<div class="results-footer">
					<div class="text-muted small">
	<?php if($totalFilas > 0){ ?>
		Pagina <?php echo (int)$pagina; ?> de <?php echo (int)ceil($totalFilas / $TAMANO_PAGINA); ?> &middot; <?php echo (int)$totalFilas; ?> registros encontrados.
	<?php }else{ ?>
		Sin registros para los filtros actuales.
	<?php } ?>
					</div>
					<div class="paging">
	<?php 
		//calculo el total de p�ginas
		$total_paginas = ceil($totalFilas / $TAMANO_PAGINA);

		$paginasLista = $total_paginas;
	if($paginasLista > 20)
		$paginasLista = 20;

		$qrstring .= "&orden=" . $orden;
		$qrstring .= "&orni=" . $descAsc;

	if ($paginasLista > 0) {
		$inicio =  floor($pagina / $paginasLista);
		if(floor($pagina / $paginasLista) == ($pagina / $paginasLista))
			$inicio = $inicio * $paginasLista - $paginasLista + 1;
		else
			$inicio = $inicio * $paginasLista + 1;
	}

	if ($paginasLista > 1) { 
	   if ($pagina > 20)
	      echo '<a href="noenviado.php?pagina='.($inicio-1). $qrstring.'">Previous</a>';
	      for ($i=$inicio;$i<=($paginasLista + $inicio - 1);$i++) {
	         if ($pagina == $i)
	            echo '<span class="current">'.$pagina.'</span>';
	         else
	            echo '<a href="noenviado.php?pagina='.$i.$qrstring.'">'.$i.'</a>';
	      }
	      if ($total_paginas > $paginasLista)
	         echo '<a href="noenviado.php?pagina='.($i).$qrstring.'">Next</a>';
	}
	?>
					</div>
				</div>

			</form>
		</div>
		<br><br>
	<?php
		}
	?>

	</div>
	 </body>
	</html>
