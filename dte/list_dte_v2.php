<?php 
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");	

	include("../include/config.php");
	include("../include/db_lib.php");  
	include("../include/ver_aut.php");
    include("../include/ver_emp_adm.php"); 

	$orden = trim($_GET["orden"]);	// orden campo
	$descAsc = trim($_GET["orni"]); // orden nivel

	if($descAsc == "") $descAsc = "1";
	if($orden == "") $orden = "X.fec_carg";	
	if($orden == "1") $orden = "D.folio_dte";
	if($orden == "2") $orden = "D.fec_emi_dte";
	if($orden == "3") $orden = "D.rut_rec_dte";
	if($orden == "4") $orden = "D.mont_tot_dte";

	if($descAsc == "1") $descAsc = "DESC";
	if($descAsc == "2") $descAsc = "ASC";

	$tipo = trim($_GET["tipo"]);
	$folio = trim($_GET["folio"]);
	$fecha1 = trim($_GET["fecha1"]);
	$fecha2 = trim($_GET["fecha2"]);
	$fechac1 = trim($_GET["fechac1"]);
	$fechac2 = trim($_GET["fechac2"]);
	$estado = trim($_GET["estado"]);
	$rut = trim($_GET["rut"]);
	$pagina = trim($_GET["pagina"]);

	if($rut != ""){
		$aRut = explode("-",$rut);
		$rut = $aRut[0];
	}

	$AAR = trim($_GET["AAR"]);		// Acuse de recibo ok
	$RAR = trim($_GET["RAR"]);		// acuse de recubi rechazado
	$SAR = trim($_GET["SAR"]);		// sin acuse de recibo
	$AAC = trim($_GET["AAC"]);		// acptado comercialmente
	$RAC = trim($_GET["RAC"]);		// rechazado comercialmente
	$SAC = trim($_GET["SAC"]);		// sin respuesta comercial
	$CRM = trim($_GET["CRM"]);		// con recibo de mercaderia
	$SRM = trim($_GET["SRM"]);		// sin recibo de mercaderia

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
			case 173:
			$sEstadoDte = "Con Reparo Enviado a Cliente";
			break;
			case 285:
			$sEstadoDte = "Con Reparo Recibido por Cliente";
			break;
			case 301:
			$sEstadoDte = "Con Reparo Recibido por Cliente";
			break;
			case 413:
			$sEstadoDte = "Aceptado por Cliente";
			break;
			case 429:
			$sEstadoDte = "Con Reparo Aceptado por Cliente";
			break; 
			case 1181:
			$sEstadoDte = "Rechazado Automáticamente";
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
<html lang="en">
 <head>
  <meta charset="latin1">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Busqueda</title>

  <style>
 .datagrid table { border-collapse: collapse; text-align: left; width: 100%;} 
 .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #006699; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }
 .datagrid table td, .datagrid table th { padding: 3px 10px; }
 .datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #0070A8; } 
 .datagrid table thead th:first-child { border: none; }
 .datagrid table tbody td { color: #00557F; border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal; }
 .datagrid table tbody .alt td { background: #E1EEf4; color: #00557F; }
 .datagrid table tbody td:first-child { border-left: none; }
 .datagrid table tbody tr:last-child td { border-bottom: none; }
 .datagrid table tfoot td div { border-top: 1px solid #006699;background: #E1EEf4;} 
 .datagrid table tfoot td { padding: 0; font-size: 12px } 
 .datagrid table tfoot td div{ padding: 2px; }
 .datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: left; }
 .datagrid table tfoot  li { display: inline; }
 .datagrid table tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #FFFFFF;border: 1px solid #006699;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; }
 .datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #00557F; color: #FFFFFF; background: none; background-color:#006699;}div.dhtmlx_window_active, div.dhx_modal_cover_dv { position: fixed !important; } 

 .container {
	width: 30em;
	overflow-x: auto;
	white-space: nowrap;
	border-collapse: collapse; text-align: left; width: 100%;
	font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #006699; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; 
}
 .container td, .container th { padding: 3px 10px; }
 .container thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; color:#FFFFFF; font-size: 12px; font-weight: bold; border-left: 1px solid #0070A8; } 
 .container thead th:first-child { border: none; }
 .container tbody td { color: #00557F; border-left: 1px solid #E1EEF4;font-size: 10px;font-weight: normal; }
 .alink {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; color:#FFFFFF; font-size: 12px; font-weight: bold; border-left: 1px solid #0070A8; } 
 .container tbody .alt td { background: #E1EEf4; color: #00557F; }
 .container tbody td:first-child { border-left: none; }
 .container tbody tr:last-child td { border-bottom: none; }
 .container tfoot td div { border-top: 1px solid #006699;background: #E1EEf4;} 
 .container tfoot td { padding: 0; font-size: 12px } 
 .container tfoot td div{ padding: 2px; }
 .container tfoot td ul { margin: 0; padding:0; list-style: none; text-align: left; }
 .container tfoot  li { display: inline; }
 .container tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #FFFFFF;border: 1px solid #006699;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; }
 .container tfoot ul.active, .container tfoot ul a:hover { text-decoration: none;border-color: #00557F; color: #FFFFFF; background: none; background-color:#006699;}div.dhtmlx_window_active, div.dhx_modal_cover_dv { position: fixed !important; } 


.myButton {
	-moz-box-shadow:inset 0px 1px 0px 0px #dcecfb;
	-webkit-box-shadow:inset 0px 1px 0px 0px #dcecfb;
	box-shadow:inset 0px 1px 0px 0px #dcecfb;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #bddbfa), color-stop(1, #80b5ea));
	background:-moz-linear-gradient(top, #bddbfa 5%, #80b5ea 100%);
	background:-webkit-linear-gradient(top, #bddbfa 5%, #80b5ea 100%);
	background:-o-linear-gradient(top, #bddbfa 5%, #80b5ea 100%);
	background:-ms-linear-gradient(top, #bddbfa 5%, #80b5ea 100%);
	background:linear-gradient(to bottom, #bddbfa 5%, #80b5ea 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#bddbfa', endColorstr='#80b5ea',GradientType=0);
	background-color:#bddbfa;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #84bbf3;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #528ecc;
}
.myButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #80b5ea), color-stop(1, #bddbfa));
	background:-moz-linear-gradient(top, #80b5ea 5%, #bddbfa 100%);
	background:-webkit-linear-gradient(top, #80b5ea 5%, #bddbfa 100%);
	background:-o-linear-gradient(top, #80b5ea 5%, #bddbfa 100%);
	background:-ms-linear-gradient(top, #80b5ea 5%, #bddbfa 100%);
	background:linear-gradient(to bottom, #80b5ea 5%, #bddbfa 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#80b5ea', endColorstr='#bddbfa',GradientType=0);
	background-color:#80b5ea;
}
.myButton:active {
	position:relative;
	top:1px;
}

.fondo{
	background-color: #FBFCFC; 
	background-image: url("../skins/aqua/images/main_bg.gif"); 
	background-repeat: repeat-y;
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
        if(confirm("Confirma la eliminación de los DTE ? "))
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

		// Acuse de recibo
		if(F.AAR.checked == false && F.RAR.checked == false && F.SAR.checked == false){
			alert("Se debe seleccionar a lo menos un estado del Acuse de Recibo");
			return false;
		}
		// Acuse comercialmente
		if(F.AAC.checked == false && F.RAC.checked == false && F.SAC.checked == false){
			alert("Se debe seleccionar a lo menos un estado de la Respuesta Comercial");
			return false;
		}
		// recibo de mercaderia
		if(F.CRM.checked == false && F.SRM.checked == false){
			alert("Se debe seleccionar a lo menos un estado del Recibo de Mercadería");
			return false;
		}
		return true;
	}

	function bajarExcel(){
		var F = document._BUSCA;

		if(confirm("Bajar a Excel el resultado de la busqueda?. Se descargan un máximo de100.000 registros.")){
			document._BUSCA.action = "excel_dte_v2.php";
			document._BUSCA.target = "_blank";
			document._BUSCA.submit();
		}
	}
	function listar(){
		if(valida() == true){
			document._BUSCA.action = "list_dte_v2.php";
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
 <body class="fondo">

<form name="_BUSCA" method="get" action="">
<table class="datagrid" align="center">
<tr>
	<td>
	<table class="datagrid">
	<tr>
		<th>Tipo DTE</th>
		<td><select name="tipo">
			<option value="33" selected>Factura Electrónica</option>
			<option value="34">Factura No Afecta o Exenta Electrónica</option>
			<option value="39">Boleta Electrónica</option>
			<option value="41">Boleta Exenta Electrónica</option>
			<option value="43">Liquidación Factura Electrónica</option>
			<option value="46">Factura de Compra Electrónica</option>
			<option value="52">Guía de Despacho Electrónica</option>
			<option value="56">Nota de Débito Electrónica</option>
			<option value="61">Nota de Crédito Electrónica</option>
			<option value="110">Factura de Exportación Electrónica</option>
			<option value="111">Nota de Débito de Exportación Electrónica</option>
			<option value="112">Nota de Crédito de Exportación Electrónica</option>
			<option value="">Todos</option>
		</select>
		<?php 	if($_GET){ ?>
		<script> chListBoxSelect(document._BUSCA.tipo, "<?php echo $tipo; ?>"); </script>
		<?php 	} ?>
		</td>
	</tr>
	<tr>
		<th>Folio DTE</th>
		<td><input type="text" name="folio" maxlength="18" value="<?php echo $folio; ?>"></td>
	</tr>
	<tr>
		<th>Fecha Emisión</th>
		<td>
			<table>
				<tr>
					<td><input type="text" name="fecha1" id="fecha1" maxlength="10" value="<?php echo $fecha1; ?>">
		<img src="../img.gif" id="f_trigger_ini" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" / >		
		<script type="text/javascript">
			Calendar.setup({
				inputField     :    "fecha1",     // id of the input field
				ifFormat       :    "%Y-%m-%d",      // format of the input field
				button         :    "f_trigger_ini",  // trigger for the calendar (button ID)
				align          :    "Tl",           // alignment (defaults to "Bl")
				singleClick    :    true
			});
		</script>					
					</td>
					<td>a</td>
					<td><input type="text" name="fecha2" id="fecha2" maxlength="10" value="<?php echo $fecha2; ?>">
		<img src="../img.gif" id="f_trigger_fin" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" / >		
		<script type="text/javascript">
			Calendar.setup({
				inputField     :    "fecha2",     // id of the input field
				ifFormat       :    "%Y-%m-%d",      // format of the input field
				button         :    "f_trigger_fin",  // trigger for the calendar (button ID)
				align          :    "Tl",           // alignment (defaults to "Bl")
				singleClick    :    true
			});
		</script>					
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<th>Fecha Carga</th>
		<td>
			<table border="0" width="100%">
				<tr>
					<td><input type="text" name="fechac1" id="fechac1" maxlength="10" value="<?php echo $fechac1; ?>">
		<img src="../img.gif" id="f_trigger_c_ini" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" / >		
		<script type="text/javascript">
			Calendar.setup({
				inputField     :    "fechac1",     // id of the input field
				ifFormat       :    "%Y-%m-%d",      // format of the input field
				button         :    "f_trigger_c_ini",  // trigger for the calendar (button ID)
				align          :    "Tl",           // alignment (defaults to "Bl")
				singleClick    :    true
			});
		</script>					</td>
					<td>a</td>
					<td><input type="text" name="fechac2"  id="fechac2" maxlength="10" value="<?php echo $fechac2; ?>">
		<img src="../img.gif" id="f_trigger_c_fin" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" / >		
		<script type="text/javascript">
			Calendar.setup({
				inputField     :    "fechac2",     // id of the input field
				ifFormat       :    "%Y-%m-%d",      // format of the input field
				button         :    "f_trigger_c_fin",  // trigger for the calendar (button ID)
				align          :    "Tl",           // alignment (defaults to "Bl")
				singleClick    :    true
			});
		</script>							</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<th>Estado DTE</th>
		<td>
			<select name="estado" size="1">
				<option value="1" selected>Firmado</option>
				<option value="3">Error</option>
				<option value="5">Empaquetado </option>
				<option value="13">Enviado a SII</option>
				<option value="29">Aceptado SII</option>
				<option value="45">Aceptado Con Reparos SII</option>
				<option value="77">Rechazado SII</option>
				<option value="157">Enviado a Clientes </option>
				<option value="173">Con Reparo Enviado a Cliente</option>
				<option value="285 ,301">Con Reparo Recibido por Cliente</option>
				<option value="413">Aceptado Cliente</option>
				<option value="429">Con Reparo Aceptado por Cliente</option>
				<option value="285">Aceptado Comercialmente</option>
				<option value="1181">Rechazado Automaticamente</option>
				<option value="1197,1437">Rechazado Comercialmente</option>
				<option value="">Todos</option>
			</select>	
		<?php 	if($_GET){ ?>
			<script> chListBoxSelect(document._BUSCA.estado, "<?php echo $estado; ?>"); </script>
		<?php 	} ?>
		</td>
	</tr>
	<tr>
		<th>Rut Receptor</th>
		<td><input type="text" name="rut" maxlength="10" value="<?php echo trim($_GET["rut"]); ?>"></td>
	</tr>

	<tr>
		<th>
			<table>
			<tr>
				<th>Acuse de Recibo</th>
			</tr>
			<tr>
				<th>Respuesta Comercial</th>
			</tr>
			<tr>
				<th>Recibo de Mercadería</th>
			</tr>
			</table>
		</th>
		<td>
			<table>
				<tr>
					<td><input type="checkbox" name="AAR" value="1" checked>Recibido</td>
					<td><input type="checkbox" name="RAR" value="1" checked>Rechazado</td>
					<td><input type="checkbox" name="SAR" value="1" checked>No Recibido</td>
				</tr>
				<tr>
					<td><input type="checkbox" name="AAC" value="1" checked>Aceptado</td>
					<td><input type="checkbox" name="RAC" value="1" checked>Rechazado</td>
					<td><input type="checkbox" name="SAC" value="1" checked>No Recibida</td>
				</tr>
				<tr>
					<td><input type="checkbox" name="CRM" value="1" checked>Recibido</td>
					<td><input type="checkbox" name="SRM" value="1" checked>No Recibido</td>
				</tr>
			</table>	
		</td>
	</tr>
	<script>
	<?php 	if($_GET){ ?>
				chCheckSele(document._BUSCA.AAR, "<?php echo $AAR; ?>");
				chCheckSele(document._BUSCA.RAR, "<?php echo $RAR; ?>");
				chCheckSele(document._BUSCA.SAR, "<?php echo $SAR; ?>");
				chCheckSele(document._BUSCA.AAC, "<?php echo $AAC; ?>");
				chCheckSele(document._BUSCA.RAC, "<?php echo $RAC; ?>");
				chCheckSele(document._BUSCA.SAC, "<?php echo $SAC; ?>");
				chCheckSele(document._BUSCA.CRM, "<?php echo $CRM; ?>");
				chCheckSele(document._BUSCA.SRM, "<?php echo $SRM; ?>");
	<?php } ?>
	</script>

<tr>
	<td align="center" colspan="2">
		<input type="button" class="myButton" text="Listar" Value="Listar" onclick="listar();"> &nbsp; &nbsp; &nbsp; <input type="button" class="myButton" text="Excel" Value="Excel" onclick="bajarExcel();">
		&nbsp; &nbsp; &nbsp; <input type="button" onclick="location.href='list_dte_v2.php';" class="myButton" text="Limpiar" Value="Limpiar">
	</td>
</tr>
</table>
</td>
</tr>
</table>
</form>
<br><br>
<?php
	if($_GET){
?>
<form name="_FDEL" method="post" action="pro_dte.php">
<!-- <form name="_FDEL" method="post" action="dte/pro_dte.php"> -->
	<input type="hidden" name="sAccion" value="E">

<input type="button" class="myButton" name="bname_remove_selected" onclick="chDelEmp();" value="X">

	<table class="container">
		<thead>
			<tr>
				<th><input type="checkbox" class="checkbox" name="clientslistSelectAll" value="true" onclick="chDchALL();"></th>
				<th>Operaciones</th>
				<th>TrackDTE</th>
				<th>Tipo</th>
				<th><table border="0" style="border-collapse: collapse;"><tr style="padding: 0px;line-height:0"><td style="padding: 0px;line-height:0"><a class="alink" href="list_dte_v2.php?a=1<?php echo $qrsFolio; ?>">Folio</a></td><td style="padding: 0px;line-height:0"><?php echo $fleFolio; ?></td></tr></table></th>
				<th>Estado</th>
				<th><table border="0" style="border-collapse: collapse;"><tr style="padding: 0px;line-height:0"><td style="padding: 0px;line-height:0"><a class="alink" href="list_dte_v2.php?a=1<?php echo $qrsFech; ?>">F.Emisión</a></td><td style="padding: 0px;line-height:0"><?php echo $fleFech; ?></td></tr></table></th>
				<th><table border="0" style="border-collapse: collapse;"><tr style="padding: 0px;line-height:0"><td style="padding: 0px;line-height:0"><a class="alink" href="list_dte_v2.php?a=1<?php echo $qrsCarga; ?>">F.Carga</a></td><td style="padding: 0px;line-height:0"><?php echo $fleCarga; ?></td></tr></table></th>
				
				<th>Exento</th>
				<th>Neto</th>
				<th>IVA</th>
				<th><table border="0" style="border-collapse: collapse;"><tr style="padding: 0px;line-height:0"><td style="padding: 0px;line-height:0"><a class="alink" href="list_dte_v2.php?a=1<?php echo $qrsTotal; ?>">Total</a></td><td style="padding: 0px;line-height:0"><?php echo $fleTotal; ?></td></tr></table></th>
				<th><table border="0" style="border-collapse: collapse;"><tr style="padding: 0px;line-height:0"><td style="padding: 0px;line-height:0"><a class="alink" href="list_dte_v2.php?a=1<?php echo $qrsRut; ?>">Rut</a></td><td style="padding: 0px;line-height:0"><?php echo $fleRut; ?></td></tr></table></th>
				<th>Receptor</th>
				<th>Dirección</th>
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

	CASE
		WHEN D.tipo_docu = 39 OR D.tipo_docu = 41 THEN
			COALESCE(CAST(X.trackid AS VARCHAR),(SELECT trackid_xed FROM xmlenvioboleta WHERE codi_empr = X.codi_empr AND num_xed= X.num_envioboleta))
		ELSE
			COALESCE(CAST(X.trackid AS VARCHAR),(SELECT CAST(trackid_xed as VARCHAR) FROM xmlenviodte WHERE codi_empr = X.codi_empr AND num_xed = X.num_xed)) 
	END AS trackid_xed,

--COALESCE(X.trackid,(SELECT trackid_xed FROM xmlenviodte WHERE codi_empr = X.codi_empr AND num_xed = X.num_xed)) as trackid_xed,

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
		if($estado != "")	$sql .= " AND X.est_xdte in(" . str_replace("'","",$estado) . ")";
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

	//			number_format($nMntNeto,0,',','.');
				$linkMerca = "<a href=\"javascript:alert('Recibo de Mercadería No Recepcionado');\" onMouseover=\"nm_mostra_hint(this, event, 'Recibo de Mercadería No Recepcionado')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/rm_no.png' alt='Recibo No Recepcionado'></a>";
				if($est_recibo_mercaderias == "R")	$linkMerca = "<a href=\"view_xml_resp.php?c=" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "&f=" . $folio_dte . "&t=" . $tipo_docu . "&o=RM\" target='_blank' onMouseover=\"nm_mostra_hint(this, event, 'Recibo de Mercadería OK')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/rm_ok.png' alt='Recibo Mercadería OK'></a>";

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
					$linkCeder = "<a href=\"javascript:alert('Para Ceder el DTE debe estar Aceptado por SII ');\" ><img src='../img/factor.png' height='17' alt='Ceder'></a>";


				if(file_exists($path_pdf_cedible) == false){
//					$linkCedible = "<a href=\"pdf.php?r=" . trim($path_pdf) . "&f=R&t=" . $tipo_docu ."&e=".$rutEmi ."&n=".$folio_dte."\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'PDF Cedible')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/grp__NM__nm_icon_pdf_cede.gif' alt='PDF Cedible'></a>";
					$linkCedible = "<a href=\"view_pdf.php?sUrlPdf=" . trim($path_pdf_cedible) . "\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'PDF Cedible')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/grp__NM__nm_icon_pdf_cede.gif' alt='PDF Cedible'></a>";					
				}


				if($tipo_docu == "39" || $tipo_docu == "41" || $tipo_docu == "56" || $tipo_docu == "61" || $tipo_docu == "111" || $tipo_docu == "112"){
					$linkCedible = "<img src='../img/na.png' height=\"27\" alt='No Aplica'>";
					$linkCeder = "<img src='../img/na.png' height=\"17\" alt='No Aplica'>";
				}
				if($tipo_docu == "39" || $tipo_docu == "41" || $tipo_docu == "110" || $tipo_docu == "111" || $tipo_docu == "112"){
					$linkAcuse = "<img src='../img/na.png' height=\"17\" alt='No Aplica'>";
					$linkMerca = "<img src='../img/na.png' height=\"17\" alt='No Aplica'>";
					$linkComer = "<img src='../img/na.png' height=\"17\" alt='No Aplica'>";
				}

                                        $linkTrack = "<a href=\"javascript:track_documento('" . $folio_dte . "','" . $tipo_docu . "','" . $mont_tot_dte . "','" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Track DTE')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/track.png' height='17' alt='Track'></a>"; 

	?>

				<tr class="<?php echo $sClassRow; ?>">
				<?php 
					if(($est_xdte < 5 || $est_xdte == 77)) // and $tipo_docu != 39 && $tipo_docu != 41)
						echo "<td><input type=\"checkbox\" name=\"del[]\" value=\"" . $folio_dte . "|" . $tipo_docu . "\"></td>";
					else
						echo "<td>&nbsp;</td>";
				?>
					<td>
						<table class="datagrid">
							<tr>
								<td><?php echo $linkPDF; ?></td>
								<td><?php echo $linkCedible; ?></td>
								<td><?php echo $linkCeder; ?></td>
								<td><?php echo $linkReenviar; ?></td>
								<td><?php echo $linkTrack; ?></td>
							</tr>
							<tr>
								<td><a href="view_xml.php?nFolioDte=<?php echo $folio_dte; ?>&nTipoDocu=<?php echo $tipo_docu; ?>" target="_blank"><img src='../img/sys__NM__xml.jpeg' alt='XML'></a></td>
								<td><?php echo $linkAcuse; ?></td>
								<td><?php echo $linkMerca; ?></td>
								<td><?php echo $linkComer; ?></td>
								<td>&nbsp;</td>
							</tr>
						</table>				
					</td>
					<td><?php echo $trackid_xed; ?></td>
					<td><?php echo poneTipo($tipo_docu); ?></td>
					<td align="right"><?php echo number_format($folio_dte,0,',','.'); ?></td>
					<td><?php echo poneEstado($est_xdte); ?></td>
					<td><?php echo $fec_emi_dte; ?></td>
					<td><?php echo $fec_carg; ?></td>
					<td align="right"><?php echo number_format($mnt_exen_dte,0,',','.'); ?></td>
					<td align="right"><?php echo number_format($mntneto_dte,0,',','.'); ?></td>
					<td align="right"><?php echo number_format($iva_dte,0,',','.'); ?></td>
					<td align="right"><?php echo number_format($mont_tot_dte,0,',','.'); ?></td>
					<td><?php echo $rut_rec_dte; ?></td>
					<td><?php echo $nom_rec_dte; ?></td>
					<td><?php echo $dir_rec_dte; ?></td>
					<td><?php echo $com_rec_dte; ?></td>
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
			<tr><td colspan="16"><h2>No hay resultados para su busqueda</h2></td></tr>
<?php
		}
?>
		</tbody>

		<tfoot>
			<tr>
				<td colspan="16">
					<div id="paging">
						<ul>
<?php 
	//calculo el total de páginas
	$total_paginas = ceil($totalFilas / $TAMANO_PAGINA);

	$paginasLista = $total_paginas;
if($paginasLista > 20)
	$paginasLista = 20;

	$qrstring .= "&orden=" . $orden;
	$qrstring .= "&orni=" . $descAsc;


	$inicio =  floor($pagina / $paginasLista);
	if(floor($pagina / $paginasLista) == ($pagina / $paginasLista))
		$inicio = $inicio * $paginasLista - $paginasLista + 1;
	else
		$inicio = $inicio * $paginasLista + 1;

	

if ($paginasLista > 1) { 
   if ($pagina > 20)
      echo '<li><span><a href="'.$url.'?pagina='.($inicio-1). $qrstring.'">Previous</a></span></a>';
      for ($i=$inicio;$i<=($paginasLista + $inicio - 1);$i++) {
         if ($pagina == $i)
            //si muestro el índice de la página actual, no coloco enlace
            echo $pagina;
         else
            //si el índice no corresponde con la página mostrada actualmente,
            //coloco el enlace para ir a esa página
            echo '  <li><span><a href="'.$url.'?pagina='.$i.$qrstring.'">'.$i.'</a></span></a>  ';
      }
      if ($total_paginas > $paginasLista)
         echo '<li><span><a href="'.$url.'?pagina='.($i).$qrstring.'">Next</span></a></li>';
}
?>

<!--							<li><a href="#"><span>Previous</span></a></li>
							<li><a href="#" class="active"><span>1</span></a></li>
							<li><a href="#"><span>2</span></a></li>
							<li><a href="#"><span>3</span></a></li>
							<li><a href="#"><span>4</span></a></li>
							<li><a href="#"><span>5</span></a></li>
							<li><a href="#"><span>6</span></a></li>
							<li><a href="#"><span>7</span></a></li>
							<li><a href="#"><span>8</span></a></li>
							<li><a href="#"><span>9</span></a></li>
							<li><a href="#"><span>10</span></a></li>
							<li><a href="#"><span>Next</span></a></li> -->
						</ul>
					</div>
				</td>
			</tr>
		</tfoot>

	</table>

</form>
<br><br><br>
<?php
	}
?>



 </body>
</html>
