<?php 
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");	

	include("../include/config.php");
	include("../include/db_lib.php");  
	include("../include/ver_aut.php");
    include("../include/ver_emp_adm.php"); 
	function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }

	$orden = trim($_GET["orden"]);	// orden campo
	$descAsc = trim($_GET["orni"]); // orden nivel

	if($descAsc == "") $descAsc = "1";
	if($orden == "") $orden = "fecha_recep";	
	if($orden == "1") $orden = "fact_ref";
	if($orden == "2") $orden = "fec_emi_doc";
	if($orden == "3") $orden = "rut_rec_dte";
	if($orden == "4") $orden = "mont_tot_dte";

	if($descAsc == "1") $descAsc = "DESC";
	if($descAsc == "2") $descAsc = "ASC";

	$tipo = trim($_GET["tipo"]);
	$folio = trim($_GET["folio"]);
	$fecha1 = trim($_GET["fecha1"]);
	$fecha2 = trim($_GET["fecha2"]);
	$fechac1 = trim($_GET["fechac1"]);
	$fechac2 = trim($_GET["fechac2"]);
	$rut = trim($_GET["rut"]);
	$pagina = trim($_GET["pagina"]);

	if($rut != ""){
		$aRut = explode("-",$rut);
		$rut = $aRut[0];
	}

	$AAR = trim($_GET["AAR"]);		// Acuse de recibo ok
	$SAR = trim($_GET["SAR"]);		// sin acuse de recibo
	$AAC = trim($_GET["AAC"]);		// acptado comercialmente
	$RAC = trim($_GET["RAC"]);		// rechazado comercialmente
	$SAC = trim($_GET["SAC"]);		// sin respuesta comercial
	$CRM = trim($_GET["CRM"]);		// con recibo de mercaderia ACEPTADO
	$RRM = trim($_GET["RRM"]);		// con recibo de mercaderia RECHAZADO
	
	$SRM = trim($_GET["SRM"]);		// sin recibo de mercaderia

	$qrstring = "&tipo=" . $tipo;
	$qrstring .= "&folio=" . $folio;
	$qrstring .= "&fecha1=" . $fecha1;
	$qrstring .= "&fecha2=" . $fecha2;
	$qrstring .= "&fechac1=" . $fechac1;
	$qrstring .= "&fechac2=" . $fechac2;
	$qrstring .= "&rut=" . $rut;
	$qrstring .= "&AAR=" . $AAR;
	$qrstring .= "&SAR=" . $SAR;
	$qrstring .= "&AAC=" . $AAC;
	$qrstring .= "&RAC=" . $RAC;
	$qrstring .= "&SAC=" . $SAC;
	$qrstring .= "&CRM=" . $CRM;
	$qrstring .= "&RRM=" . $RRM;	
	$qrstring .= "&SRM=" . $SRM;	
	$url = "list_dte_recep_v3.php";

	$fleCarga = "";
	$fleFolio = "";
	$fleFech = "";
	$fleRut = "";
	$fleTotal = "";


	$descAscCarga = "1";
	if($orden == "fecha_recep"){
		$fleCarga = "<img src='../img/arriba.png' width='17'>";
		if($descAsc == "DESC") {
			$descAscCarga = "2";
			$fleCarga = "<img src='../img/abajo.png' width='17'>";
		}
	}
	$descAscFolio = "1";
	if($orden == "fact_ref"){
		$fleFolio = "<img src='../img/arriba.png' width='17'>";
		if($descAsc == "DESC") {
			$descAscFolio = "2";
			$fleFolio = "<img src='../img/abajo.png' width='17'>";
		}
	}
	$descAscFech = "1";
	if($orden == "fec_emi_doc"){
		$fleFech = "<img src='../img/arriba.png' width='17'>";
		if($descAsc == "DESC"){
			$descAscFech = "2";
			$fleFech = "<img src='../img/abajo.png' width='17'>";
		}
	}
	$descAscRut = "1";
	if($orden == "rut_rec_dte"){
		$fleRut = "<img src='../img/arriba.png' width='17'>";
		if($descAsc == "DESC"){
			$descAscRut = "2";
			$fleRut = "<img src='../img/abajo.png' width='17'>";
		}
	}
	$descAscTotal = "1";
	if($orden == "mont_tot_dte"){
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

	 function cambiarEstado($nCodCorrel, $nTipDte){
		 	
			echo "<select name='cambia' onChange='confirmCambio(" . $nCodCorrel . ",
	 \"" . $nTipDte . "\", this);'>\n";
			echo "<option value=''></option>\n";
			echo "<option value='ACEPTADO'>Aceptar</option>\n";
			echo "<option value='RECHAZADO'>Rechazar</option>\n";
			echo "</select>\n";
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
<?php 
		$conn = conn();
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
			$fech_update_sii4 = trim($result2->fields["fech_update_sii2"]);
			if($fech_update_sii4 == "") $fech_update_sii4 = 0;
		}
/*
echo $fech_update_sii . "<br>";
echo $fech_update_sii1 . "<br>";
echo $fech_update_sii2 . "<br>";
echo $fech_update_sii3 . "<br>";
echo $fech_update_sii4 . "<br>";
*/
		$fech_update_sii = $fech_update_sii1;
		if($fech_update_sii1 == "") $fech_update_sii = $fech_update_sii2;

		if($fech_update_sii2 > $fech_update_sii4) $fech_update_sii = $fech_update_sii1;
		if($fech_update_sii2 < $fech_update_sii4) $fech_update_sii = $fech_update_sii3;
		if($fech_update_sii == "") 
			$fech_update_sii = "nunca actualizado";
	//	else
	//		$fech_update_sii = substr($fech_update_sii,0,4) . "-" . substr($fech_update_sii,4,2) . "-" . substr($fech_update_sii,6,2); // 2022 10 21 1001


?>
<!doctype html>
<html lang="es">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>DTE Recibidos V3 - Portal DTE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <style>
body.fondo{margin:0;background:linear-gradient(180deg,#eef4fb 0%,#f8fafc 100%);color:#1f2937;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif}
.page-shell{max-width:1680px;margin:0 auto;padding:1.25rem}
.page-hero{background:linear-gradient(135deg,#0f3f75 0%,#2563eb 100%);color:#fff;border-radius:22px;padding:1.5rem;box-shadow:0 18px 40px rgba(37,99,235,.22);margin-bottom:1.25rem}
.hero-icon{width:62px;height:62px;border-radius:18px;background:rgba(255,255,255,.16);display:flex;align-items:center;justify-content:center;font-size:1.55rem}
.hero-pills{display:flex;flex-wrap:wrap;gap:.65rem;justify-content:flex-end}.hero-pill{display:inline-flex;align-items:center;gap:.4rem;border-radius:999px;font-size:.82rem;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.18);padding:.45rem .8rem}
.content-card{background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:20px;box-shadow:0 12px 28px rgba(15,23,42,.08);overflow:hidden;margin-bottom:1.2rem}.content-card-header{padding:1rem 1.15rem;background:linear-gradient(135deg,#eff6ff 0%,#dbeafe 100%);border-bottom:1px solid #dbeafe}.content-card-header h2{margin:0;font-size:1.18rem;font-weight:700;color:#0f172a}.content-card-header p{margin:.35rem 0 0;color:#475569;font-size:.88rem}.content-card-body{padding:1.15rem}
table.datagrid,table.container{width:100%;border-collapse:separate;border-spacing:0;background:#fff;border:1px solid #d7e3f4;border-radius:18px;overflow:hidden}table.datagrid th,table.datagrid td,table.container th,table.container td{padding:.75rem .9rem;border-bottom:1px solid #e2e8f0;vertical-align:top;font-size:.84rem;color:#1f2937}table.datagrid tr:last-child td,table.container tbody tr:last-child td{border-bottom:none}
.filter-card>.content-card-body>form>table.datagrid{border:none;box-shadow:none}.filter-card>.content-card-body>form>table.datagrid>tbody>tr>th{width:230px;background:#eff6ff;color:#1e3a8a;font-weight:700;border-bottom:1px solid #dbeafe}.filter-card>.content-card-body>form>table.datagrid>tbody>tr>td{background:#fff}.datagrid-inner{border:none !important;box-shadow:none !important}
table.datagrid table,table.container table{width:auto;border-collapse:collapse;border:none;background:transparent}table.datagrid table th,table.datagrid table td,table.container table th,table.container table td{padding:.25rem .35rem;border:none;background:transparent;color:inherit;font-size:inherit}
table.datagrid input[type="text"],table.datagrid select,#_FORMAJAX select,#_FENVACT select{min-height:40px;border:1px solid #cbd5e1;border-radius:12px;padding:.45rem .75rem;background:#fff;color:#1f2937;max-width:100%}table.datagrid input[type="checkbox"]{margin-right:.35rem}
.myButton{display:inline-flex;align-items:center;justify-content:center;gap:.45rem;border-radius:999px;padding:.7rem 1.1rem;border:1px solid transparent;background:#2563eb;color:#fff;text-decoration:none;font-weight:600;line-height:1;box-shadow:0 8px 18px rgba(37,99,235,.2);cursor:pointer}.myButton:hover{background:#1d4ed8;color:#fff}.myButton:active{position:relative;top:1px}.myButton-success{background:#16a34a;box-shadow:0 8px 18px rgba(22,163,74,.22)}.myButton-success:hover{background:#15803d}.myButton-muted{background:#64748b;box-shadow:0 8px 18px rgba(100,116,139,.2)}.myButton-muted:hover{background:#475569}
#divLoading{display:none;position:fixed;inset:0;background:rgba(15,23,42,.38);z-index:30001}#divLoading .loading-card{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:18px;padding:1.1rem 1.35rem;box-shadow:0 18px 40px rgba(15,23,42,.24);text-align:center;color:#0f172a}#divLoading .loading-card img{display:block;margin:.6rem auto 0}
#tooltip{position:absolute;visibility:hidden;z-index:30003;background:#0f172a;color:#fff;padding:.45rem .65rem;border-radius:10px;font-size:.78rem;box-shadow:0 10px 24px rgba(15,23,42,.2)}
#norecibido.modal,#formActualiza.modal,#_FENVACT.modal{max-width:760px;width:92%;padding:1.2rem 1.25rem;border-radius:20px;box-shadow:0 20px 44px rgba(15,23,42,.24)}#norecibido.modal{max-width:1100px}#formActualiza h2,#_FENVACT h2{font-size:1.25rem;font-weight:700;color:#0f172a;margin-bottom:.75rem}#formActualiza h3{font-size:.95rem;color:#b91c1c;margin-bottom:1rem}#_FENVACT table{width:100%}.jquery-modal.blocker{z-index:30002}a.close-modal{display:none !important}
.summary-chips{display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:1rem}.summary-chip{display:inline-flex;align-items:center;gap:.35rem;background:#eff6ff;border:1px solid #bfdbfe;border-radius:999px;padding:.42rem .8rem;font-size:.8rem;color:#1d4ed8}.modal-actions{display:flex;justify-content:flex-end;margin-bottom:.8rem}.modal-actions .myButton{min-width:44px}.filter-actions{padding-top:1rem !important;text-align:center !important}.filter-actions .myButton{margin:.2rem .35rem}.manual-link{color:#1d4ed8;text-decoration:none;font-weight:700}.manual-link:hover{color:#1e40af;text-decoration:underline}
.results-toolbar{display:flex;flex-wrap:wrap;gap:.75rem;align-items:center;justify-content:space-between;padding:1rem 1.15rem;border-bottom:1px solid #e2e8f0;background:#fff}.results-toolbar h2{margin:0;font-size:1.08rem;font-weight:700;color:#0f172a}.results-toolbar p{margin:.2rem 0 0;font-size:.84rem;color:#64748b}.table-responsive-shell{overflow-x:auto;padding:0 1rem 1rem}table.container.results-table{min-width:1480px;border:none;border-radius:0;box-shadow:none}table.container.results-table thead th{background:#0f3f75;color:#fff;font-size:.82rem;white-space:nowrap}table.container.results-table thead th a,.alink{color:#fff;text-decoration:none;font-weight:700}table.container.results-table thead th a:hover,.alink:hover{color:#dbeafe}table.container.results-table tbody tr.alt td{background:#f8fbff}table.container.results-table tbody td{font-size:.82rem;vertical-align:middle}.results-table table.datagrid,.op-grid{width:auto;border:none !important;box-shadow:none !important}.results-table table.datagrid td,.op-grid td{padding:.22rem .28rem;border:none;background:transparent}
.no-results{padding:3.5rem 1rem !important;text-align:center !important;font-size:1rem !important;color:#64748b !important}.no-results i{display:block;font-size:2.6rem;margin-bottom:.6rem;color:#94a3b8}
#paging{padding:.95rem 1rem 0}#paging ul{display:flex;flex-wrap:wrap;gap:.5rem;align-items:center;list-style:none;padding:0;margin:0}#paging li{display:inline-flex}#paging a,#paging span{display:inline-flex;align-items:center;justify-content:center;min-width:2.2rem;height:2.2rem;padding:0 .9rem;border:1px solid #cbd5e1;border-radius:999px;background:#fff;color:#0f172a;text-decoration:none;font-size:.84rem}#paging a:hover{background:#eff6ff;border-color:#93c5fd;color:#1d4ed8}.page-current{background:#2563eb !important;border-color:#2563eb !important;color:#fff !important;font-weight:700}
div.dhtmlx_window_active,div.dhx_modal_cover_dv{position:fixed !important}
@media (max-width:991.98px){.page-shell{padding:.85rem}.page-hero{padding:1.15rem}.hero-pills{justify-content:flex-start}.filter-card>.content-card-body>form>table.datagrid>tbody>tr>th,.filter-card>.content-card-body>form>table.datagrid>tbody>tr>td{display:block;width:100%}.results-toolbar{align-items:flex-start}table.container.results-table{min-width:1280px}}

  </style>

<script src="../javascript/jquery-3.5.1.min.js"></script>
<script src="../javascript/jquery.modal.min.js"></script>
<link rel="stylesheet" href="../javascript/jquery.modal.min.css" />

<script type="text/javascript">
<!--

function Reenviar(nFolioDte,nTipDoc) {
   var sUrl = "form_reenvio.php?nFolio=" + nFolioDte + "&nTipoDTE=" + nTipDoc;
    wTipoMot=window.open(sUrl, "reenviar","dependent=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,width=400,height=400");
        var centroAncho = (screen.width/2)  - (400);
        var centroAlto  = (screen.height/2) - (200);
        wTipoMot.moveTo(centroAlto,centroAncho);
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
		if(F.AAR.checked == false && F.SAR.checked == false){
			alert("Se debe seleccionar a lo menos un estado del Acuse de Recibo");
			return false;
		}
		// Acuse comercialmente
		if(F.AAC.checked == false && F.RAC.checked == false && F.SAC.checked == false){
			alert("Se debe seleccionar a lo menos un estado de la Respuesta Comercial");
			return false;
		}
		// recibo de mercaderia		
		if(F.CRM.checked == false && F.RRM.checked == false && F.SRM.checked == false){
			alert("Se debe seleccionar a lo menos un estado del Recibo de Mercader�a");
			return false;
		}
		return true;
	}

	function bajarExcel(){
		var F = document._BUSCA;

		if(confirm("Bajar a Excel el resultado de la busqueda?. Se descargan un m�ximo de 10.000 registros.")){
			document._BUSCA.action = "excel_dte_recep_v3.php";
			document._BUSCA.target = "_blank";
			document._BUSCA.submit();
		}
	}
	function listar(){
		if(valida() == true){
			document._BUSCA.action = "list_dte_recep_v3.php";
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

        var objTmpSelect;
        function confirmCambio(nCodDoc, nTipoDte, obj){
                var F = document._FENV;
                objTmpSelect = obj;

                if(confirm(_MSG_ACEPTA_RECHAZA_DTE)){
                        F.nCodCorr.value = nCodDoc;
                        F.sEstado.value = obj.options[obj.selectedIndex].value;
                        F.sTipFac.value = "";
                        F.nMotIvaNoRec.value = "";

                        if(nTipoDte == "33" || nTipoDte == "34" || nTipoDte == "30" || nTipoDte == "45" || nTipoDte == "46")
                                enviaEstResp("form_newmotivo2.php?s="+obj.value);
                        else
                                F.submit();
                }
                else
                        obj.options[0].selected = true;
        } 

	function enviaEstResp(URL) {
	    wTipoMot=window.open(URL, "tipoMotivo","dependent=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,width=500,height=400");
	//    ResEst=window.open(URL, "respuestaEst");
	//      if (wTipoMot.opener == null) { wTipoMot.opener = window; }
	//    wTipoMot.opener.name = 'opener';
		var centroAncho = (screen.width/2)  - (400);
		var centroAlto  = (screen.height/2) - (200);
		wTipoMot.moveTo(centroAlto,centroAncho);
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


	function actualizarSII(){
//		window.open("actualizaSII.php", "formSII", "toolbar=no,scrollbars=no,resizable=no,top=200,left=500,width=400,height=400");	
		alert("Fecha \u00FAltima actualizaci\u00F3n: <?php echo $fech_update_sii; ?>");
		$("#formActualiza").show();		
	}


        function actualizaRegistro(){
				var F = document._FORMAJAX;

				$("#divLoading").show();

				var form = new FormData(document.getElementById("_FORMAJAX"));

					$.ajax({
                        type: "GET",
                        url: "actualizaSII.php",
                        //                        data: "{ nCodClie: 'juan', age: 80}",
///                        data:  "{ sanio: '" + $("#sanio").val() + "', smes: '" + $("#smes").val() + "'}",  // $('_FORMAJAX').serialize(), //
                        //data:  "sanio=2020&smes=01",  // $('_FORMAJAX').serialize(), //
						data: $("#_FORMAJAX").serialize() , //"{ sanio: '2020', smes: '01'}",
						processData: false, 
enctype: 'multipart/form-data',
                //async: false,
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        async: true,
                        success: function (obj) {
//                            var obj = $.parseJSON(r.d);

                            if (obj.Error == "0") {
                                alert(obj.msj);
								location.reload();
                            }
                            if (obj.Error == "1") {
                                alert(obj.msj);
								$("#formActualiza").hide();		
								$("#divLoading").hide();
                            }
                            if (obj.Error == "2") {     // Redireccion a login
                                alert(obj.msj);
                                window.location.href = '../login.php';
                            }
                        },
                        error: function (r) {
                            alert(r.responseText);
							$("#formActualiza").hide();
							$("#divLoading").hide();
                        },
                        failure: function (r) {
                            alert(r.responseText);
							$("#formActualiza").hide();
							$("#divLoading").hide();
						}
                    });
//                    return false;
        }

		function responderSII2(folio_dte, tipo_docu, rut_rec_dte, dig_rec_dte, merca_dte, fech_merca_dte, acuse_dte, fech_acuse_dte){
				$("#norecibido").hide();			
				responderSII(folio_dte, tipo_docu, rut_rec_dte, dig_rec_dte, merca_dte, fech_merca_dte, acuse_dte, fech_acuse_dte);
		}
		function responderSII(folio_dte, tipo_docu, rut_rec_dte, dig_rec_dte, merca_dte, fech_merca_dte, acuse_dte, fech_acuse_dte){
				$("#folio_dte").val(folio_dte);			
				$("#tipo_docu").val(tipo_docu);			
				$("#rut_rec_dte").val(rut_rec_dte);			
				$("#dig_rec_dte").val(dig_rec_dte);			
				$("#botonEnvio").show();

				if(merca_dte == ""){
					$("#sRespuestaMerca1").show();			
					$("#sRespuestaMerca2").hide();			
					$("#sRespuestaMerca3").hide();								
				}
				else{
					$("#sRespuestaMerca1").hide();			
					$("#sRespuestaMerca2").show();			
					$("#sRespuestaMerca3").show();		
					
					if(merca_dte == "ERM") $("#sRespuestaMerca3").html("Otorgado Recibo de Mercader�as el " + fech_merca_dte);
					if(merca_dte == "RFP") $("#sRespuestaMerca3").html("Reclamado por Falta Parcial de Mercader�as el " + fech_merca_dte);
					if(merca_dte == "RFT") $("#sRespuestaMerca3").html("Reclamodo por Falta Total de Mercader�as el " + fech_merca_dte);
				}


				if(acuse_dte == ""){
					$("#sRespuesta1").show();			
					$("#sRespuesta2").hide();			
					$("#sRespuesta3").hide();								
				}
				else{
					$("#sRespuesta1").hide();			
					$("#sRespuesta2").show();			
					$("#sRespuesta3").show();		
					
					if(acuse_dte == "ACD") $("#sRespuesta3").html("Aceptado Contenido del Documento el " + fech_acuse_dte);
					if(acuse_dte == "RCD") $("#sRespuesta3").html("Reclamado Contenido del Documento el " + fech_acuse_dte);
				}

				if(acuse_dte != "" && merca_dte != "")
					$("#botonEnvio").hide();


		}
 
 		function enviarRespSII(){

			if($("#sRespuesta option:selected").val() == "" && $("#sRespuestaMerca option:selected").val() == "")
				alert("Debe seleccionar acuse de documento y/o recibo de mercader�a para el DTE");

			else{

				$("#divLoading").show();

				var form = new FormData(document.getElementById("_FORMAJAX"));

					$.ajax({
                        type: "GET",
                        url: "pro_newcambiar2.php",
                        //                        data: "{ nCodClie: 'juan', age: 80}",
///                        data:  "{ sanio: '" + $("#sanio").val() + "', smes: '" + $("#smes").val() + "'}",  // $('_FORMAJAX').serialize(), //
                        //data:  "sanio=2020&smes=01",  // $('_FORMAJAX').serialize(), //
						data: $("#_FENVACT").serialize() , //"{ sanio: '2020', smes: '01'}",
						processData: false, 
						enctype: 'multipart/form-data',
                //async: false,
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        async: true,
                        success: function (obj) {
//                            var obj = $.parseJSON(r.d);
//							console.log(obj);
                            if (obj.Error == "0") {
//                                alert(obj.msj);
								$("#divLoading").hide();

								if($("#sRespuesta option:selected").val() != "")	// Genera acuse de documento
									alert("Resultado de Respuesta a Contenido del Documento: " + obj.glosaAcuse);
								if($("#sRespuestaMerca option:selected").val() != "")	// Genera acuse de documento
									alert("Resultado de Respuesta a Recibo de Mercader�as: " + obj.glosaMerca);

//								location.reload();
                            }
                            if (obj.Error == "1") {
                                alert(obj.msj);
								$("#_FENVACT").hide();		
								$("#divLoading").hide();
                            }
                            if (obj.Error == "2") {     // Redireccion a login
                                alert(obj.msj);
                                window.location.href = '../login.php';
                            }
                        },
                        error: function (r) {
                            alert(r.responseText);
							$("#_FENVACT").hide();
							$("#divLoading").hide();
                        },
                        failure: function (r) {
                            alert(r.responseText);
							$("#_FENVACT").hide();
							$("#divLoading").hide();
						}
                    });
			}
		}

	var noRecActivo = false;

	function norecibido(){
				
		
				$("#divLoading").show();

				var form = new FormData(document.getElementById("_FORMAJAX"));

					$.ajax({
                        type: "GET",
                        url: "norecibido.php",
                        //                        data: "{ nCodClie: 'juan', age: 80}",
///                        data:  "{ sanio: '" + $("#sanio").val() + "', smes: '" + $("#smes").val() + "'}",  // $('_FORMAJAX').serialize(), //
                        //data:  "sanio=2020&smes=01",  // $('_FORMAJAX').serialize(), //
//						data: $("#_FENVACT").serialize() , //"{ sanio: '2020', smes: '01'}",
						processData: false, 
						enctype: 'multipart/form-data',
                //async: false,
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        async: true,
                        success: function (obj) {
//                            var obj = $.parseJSON(r.d);
//							console.log(obj);
                            if (obj.Error == "0") {
//                                alert(obj.msj);
								$("#norecibidohtml").html(obj.html);
								$("#norecibido").show();
								noRecActivo = true;
								$("#recibido").hide();
								$("#divLoading").hide();
                            }
                            if (obj.Error == "1") {
                                alert(obj.msj);
								$("#norecibido").hide();		
								$("#divLoading").hide();
                            }
                            if (obj.Error == "2") {     // Redireccion a login
                                alert(obj.msj);
                                window.location.href = '../login.php';
                            }
                        },
                        error: function (r) {
                            alert(r.responseText);
							$("#norecibido").hide();
							$("#divLoading").hide();
                        },
                        failure: function (r) {
                            alert(r.responseText);
							$("#norecibido").hide();
							$("#divLoading").hide();
						}
                    });
	}

	function recibido(){
								$("#norecibido").hide();
								$("#recibido").show();		
								noRecActivo = false;
	}

	function recibidox(){
		if(noRecActivo == true)
			$("#norecibido").show();
	}

/*
$(document).ready(function(){
  $('a[href="#close-modal"]').on('click', function(){
    alert(1)
  }) 
});
*/
$(document).on('click', 'a[href="#close-modal"]', function(){
	if(noRecActivo == true)
		$("#norecibido").show();

});

/*
$(document).ready(function() {
  $("#_FENVACT").click(function(event) {
    alert(event.target.id);
  });
});

$(document).on(function() {
  $("#_FENVACT").click(function(event) {
    alert(event.target.id);
  });
});
*/
//$("#cuerpo").click(function() { var contentPanelId = $(this).attr("id"); alert(contentPanelId); });
/*
$(document).ready(function() {
  $("#cuerpo").click(function(event) {
    console.log(event);
	    alert(event);
  });
});

  $(function() {

    function log_modal_event(event, modal) {
      if(typeof console != 'undefined' && console.log) console.log("[event] " + event.type);
    };

    $('a[href="#_FENVACT"]').click(function(event) {
      event.preventDefault();
      $(this).modal({
        closeExisting: false
      });
    });
  });
*/


  
  $(function() {

    $('a[href="#_FENVACT"]').click(function(event) {
      event.preventDefault();
      $(this).modal({
        escapeClose: false,
        clickClose: false,
        showClose: false
      });
    }); 
  });

	$(document).on('click', 'a[href="#_FENVACT"]', function(){
      event.preventDefault();
      $(this).modal({
        escapeClose: false,
        clickClose: false,
        showClose: false
      });
	});


//-->
</script>
		  <script language="javascript" type="text/javascript" src="../javascript/msg.js"></script> 

		  <!-- calendar  -->
		  <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
		  <script type="text/javascript" src="../javascript/calendar.js"></script>
		  <script type="text/javascript" src="../javascript/lang/calendar-es.js"></script>
		  <script type="text/javascript" src="../javascript/calendar-setup.js"></script>
		  <!-- calendar fin -->



 </head>
 <body class="fondo" id="cuerpo">

<div id="divLoading">
	<div class="loading-card">
		<strong>Actualizando, por favor espere...</strong>
		<img src="../skins/aqua/icons/loading.gif" alt="Cargando">
	</div>
</div>
<div id="tooltip"></div>

<div class="page-shell">
	<div class="page-hero">
		<div class="row g-3 align-items-center">
			<div class="col-lg-8">
				<div class="d-flex align-items-start gap-3">
					<div class="hero-icon"><i class="bi bi-receipt-cutoff"></i></div>
					<div>
						<h1 class="h3 mb-2">DTE recibidos desde compras</h1>
						<p class="mb-0 opacity-75">Se conserva la b&uacute;squeda legacy, la respuesta al SII, la exportaci&oacute;n a Excel y los accesos PDF/XML del m&oacute;dulo original.</p>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="hero-pills">
					<span class="hero-pill"><i class="bi bi-funnel"></i>Filtros por tipo, folio, fecha y RUT</span>
					<span class="hero-pill"><i class="bi bi-cloud-arrow-down"></i>Actualizaci&oacute;n SII</span>
					<span class="hero-pill"><i class="bi bi-file-earmark-spreadsheet"></i>Excel, XML y PDF</span>
				</div>
			</div>
		</div>
	</div>

  <div id="norecibido" class="login_form modal" style="display:none">
		<div class="modal-actions"><input type="button" class="myButton myButton-muted" text="X" Value="X" onclick="recibido();"></div>
		<div id="norecibidohtml"></div>
  </div>

<div id="recibido">

	  <div id="formActualiza" class="login_form modal" style="display:none">
		<h2>Actualizar Registro de Compra SII</h2>
		<h3>Fecha &uacute;ltima actualizaci&oacute;n: <?php echo h($fech_update_sii); ?></h3>

	  <form method="post" name="_FORMAJAX" id="_FORMAJAX" action="actualizaSII.php">
			<?php 
				$anio = date("Y");
				$mes = date("m");		
			?>

				<div class="fw-semibold mb-3">Periodo a Actualizar (A&ntilde;o-Mes):</div>
				<div class="d-flex flex-wrap align-items-center gap-2">
			<select name="sanio" id="sanio">
				<option value="<?php echo $anio; ?>" selected><?php echo $anio; ?></option>
			<?php 
				for($i=$anio-1; $i > $anio-20; $i--){
					echo "<option value='" . $i . "'>" . $i . "</option>\n";
				}
			?>			
			</select> 

			<select name="smes" id="smes">
			<?php 
				for($i=1; $i < 13; $i++){
					$sel = "";
					if($i == $mes)	$sel = "selected";

					$m = $i;
					if(strlen($m) == 1) 
						$m = "0" . $m;					

					echo "<option value='" . $m . "' " . $sel . ">" . $m . "</option>\n";
				}
			?>			
			</select>
				</div>

		<div class="mt-4 d-flex flex-wrap gap-2">
			<input type="button" class="myButton myButton-success" text="Actualizar SII" Value="Actualizar SII" onclick="actualizaRegistro();">
		</div>
  </form>

  </div>

  <form name="_FENVACT" id="_FENVACT" method="post" action="pro_newcambiar2.php" class="login_form modal" style="display:none">
			<div class="modal-actions"><a href="#" class="myButton myButton-muted" rel="modal:close" onclick="recibidox();">X</a></div>

                <INPUT TYPE="hidden" name="folio_dte" id="folio_dte" value="">
                <INPUT TYPE="hidden" name="tipo_docu" id="tipo_docu" value="">
                <INPUT TYPE="hidden" name="rut_rec_dte" id="rut_rec_dte" value="">
                <INPUT TYPE="hidden" name="dig_rec_dte" id="dig_rec_dte" value="">

	<h2>Responder DTE</h2>

	<TABLE >


		<TR id="sRespuestaMerca1">
			<td width="33%"><label for="fid-cname">Respuesta a Recibo de Mercader�as</label></td>
			<td width="33%" nowrap> 
				<SELECT NAME="sRespuestaMerca" id="sRespuestaMerca">
					<option value="">Seleccione Respuesta de Mercader�as</option>
					<option value="ERM">Otorga Recibo de Mercader�as o Servicios</option>
					<option value="RFP">Reclamo por Falta Parcial de Mercader�as</option>
					<option value="RFT">Reclamo por Falta Total de Mercader�as</option>
				</SELECT>
			</td>
		</TR>

		<TR id="sRespuestaMerca2">
			<td width="66%" colspan="2"><label for="fid-cname" id="sRespuestaMerca3"></label></td>
		</TR>
		

		<TR id="sRespuesta1">
			<td width="33%"><label for="fid-cname">Respuesta a Contenido del Documento</label></td>
			<td width="33%" nowrap> 
				<SELECT NAME="sRespuesta" id="sRespuesta">
					<option value="">Seleccione Respuesta al Contenido</option>
					<option value="ACD">Acepta Contenido del Documento</option>
					<option value="RCD">Reclamo al Contenido del Documento</option>
				</SELECT>
			</td>
		</TR>

		<TR id="sRespuesta2">
			<td width="66%" colspan="2"><label for="fid-cname" id="sRespuesta3"></label></td>
		</TR>

		<Tr><td colspan=2 align="center"><br><INPUT TYPE="button" value="Enviar" id="botonEnvio" class="myButton myButton-success" onclick="enviarRespSII();"> </td></tr>
	</TABLE>	
  
  </form> 


	<div class="content-card filter-card">
		<div class="content-card-header">
			<h2><i class="bi bi-search me-2"></i>B&uacute;squeda y filtros</h2>
			<p>Se conservan los mismos campos GET, calendarios legacy y acciones del listado original.</p>
		</div>
		<div class="content-card-body">
			<div class="summary-chips">
				<span class="summary-chip"><i class="bi bi-calendar-range"></i>Rango por emisi&oacute;n y recepci&oacute;n</span>
				<span class="summary-chip"><i class="bi bi-patch-check"></i>Estados de acuse, respuesta y mercader&iacute;a</span>
				<span class="summary-chip"><i class="bi bi-building"></i>Filtro por RUT emisor</span>
			</div>
<form name="_BUSCA" method="get" action="">
<table class="datagrid filter-table" align="center">
<tr>
	<td>
	<table class="datagrid datagrid-inner">
	<tr>
		<th>Tipo DTE</th>
		<td><select name="tipo">
			<option value="33" selected>Factura Electr�nica</option>
			<option value="34">Factura No Afecta o Exenta Electr�nica</option>
			<option value="39">Boleta Electr�nica</option>
			<option value="41">Boleta Exenta Electr�nica</option>
			<option value="43">Liquidaci�n Factura Electr�nica</option>
			<option value="46">Factura de Compra Electr�nica</option>
			<option value="52">Gu�a de Despacho Electr�nica</option>
			<option value="56">Nota de D�bito Electr�nica</option>
			<option value="61">Nota de Cr�dito Electr�nica</option>
			<option value="110">Factura de Exportaci�n Electr�nica</option>
			<option value="111">Nota de D�bito de Exportaci�n Electr�nica</option>
			<option value="112">Nota de Cr�dito de Exportaci�n Electr�nica</option>
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
		<th>Fecha Emisi�n</th>
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
		<th>Fecha Recepci�n</th>
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
		<th>Rut Emisor</th>
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
				<th>Recibo de Mercader�a</th>
			</tr>
			</table>
		</th>
		<td>
			<table>
				<tr>
					<td><input type="checkbox" name="AAR" value="1" checked>Generado</td>
					<td><input type="checkbox" name="SAR" value="1" checked>No Generado</td>
				</tr>
				<tr>
					<td><input type="checkbox" name="AAC" value="1" checked>Aceptado</td>
					<td><input type="checkbox" name="RAC" value="1" checked>Rechazado</td>
					<td><input type="checkbox" name="SAC" value="1" checked>No Generado</td>
				</tr>
				<tr>
					<td><input type="checkbox" name="CRM" value="1" checked>Aceptado</td>
					<td><input type="checkbox" name="RRM" value="1" checked>Rechazado</td>					
					<td><input type="checkbox" name="SRM" value="1" checked>No Generado</td>
				</tr>
			</table>	
		</td>
	</tr>
	<script>
	<?php 	if($_GET){ ?>
				chCheckSele(document._BUSCA.AAR, "<?php echo $AAR; ?>");
				chCheckSele(document._BUSCA.SAR, "<?php echo $SAR; ?>");
				chCheckSele(document._BUSCA.AAC, "<?php echo $AAC; ?>");
				chCheckSele(document._BUSCA.RAC, "<?php echo $RAC; ?>");
				chCheckSele(document._BUSCA.SAC, "<?php echo $SAC; ?>");
				chCheckSele(document._BUSCA.CRM, "<?php echo $CRM; ?>");
				chCheckSele(document._BUSCA.RRM, "<?php echo $RRM; ?>");
				chCheckSele(document._BUSCA.SRM, "<?php echo $SRM; ?>");
	<?php } ?>
	</script>

	<tr>
		<td align="center" colspan="2" class="filter-actions">
			<div class="d-flex flex-wrap justify-content-center gap-2">
				<input type="button" class="myButton" text="Listar" Value="Listar" onclick="listar();">
				<input type="button" class="myButton" text="Excel" Value="Excel" onclick="bajarExcel();">
				<input type="button" onclick="location.href='list_dte_recep_v3.php';" class="myButton myButton-muted" text="Limpiar" Value="Limpiar">
				<a class="myButton myButton-success" href="#formActualiza" rel="modal:open">Actualizar Registro de Compra desde el SII</a>
				<input type="button" class="myButton myButton-success" text="Ver los no recibidos en OpenB" Value="Ver los no recibidos en OpenB" onclick="norecibido();">
			</div>
		</td>
	</tr>

<tr>
		<td align="center" colspan="2"><b><a class="manual-link" href="manual_reg_compra.pdf" target="_blank">Manual de Uso</a></b></td>
</tr>

</table>
</td>
</tr>
</table>
</form>
		</div>
	</div>
<br><br>
<?php
	if($_GET){
?>
<div class="content-card">
<form name="_FDEL" method="post" action="pro_dte.php">
<!-- <form name="_FDEL" method="post" action="dte/pro_dte.php"> -->
	<input type="hidden" name="sAccion" value="E">

	<div class="results-toolbar">
		<div>
			<h2><i class="bi bi-table me-2"></i>Resultados de DTE recibidos</h2>
			<p>Se mantiene la grilla original con respuestas SII, enlaces PDF/XML y ordenamiento legacy.</p>
		</div>
		<div class="d-flex flex-wrap align-items-center gap-2">
			<span class="summary-chip"><i class="bi bi-list-ol"></i>Paginaci&oacute;n manual preservada</span>
			<input type="button" class="myButton myButton-muted" name="bname_remove_selected" onclick="chDelEmp();" value="X">
		</div>
	</div>

		<div class="table-responsive-shell">
		<table class="container results-table">
		<thead>
			<tr>
				<th>Operaciones</th>
				<th>Tipo</th>
				<th><table border="0" style="border-collapse: collapse;"><tr style="padding: 0px;line-height:0"><td style="padding: 0px;line-height:0"><a class="alink" href="list_dte_recep_v3.php?a=1<?php echo $qrsFolio; ?>">Folio</a></td><td style="padding: 0px;line-height:0"><?php echo $fleFolio; ?></td></tr></table></th>
				<th><table border="0" style="border-collapse: collapse;"><tr style="padding: 0px;line-height:0"><td style="padding: 0px;line-height:0"><a class="alink" href="list_dte_recep_v3.php?a=1<?php echo $qrsFech; ?>">F.Emisi�n</a></td><td style="padding: 0px;line-height:0"><?php echo $fleFech; ?></td></tr></table></th>
				<th><table border="0" style="border-collapse: collapse;"><tr style="padding: 0px;line-height:0"><td style="padding: 0px;line-height:0"><a class="alink" href="list_dte_recep_v3.php?a=1<?php echo $qrsCarga; ?>">F.Recepci&oacute;n Openb</a></td><td style="padding: 0px;line-height:0"><?php echo $fleCarga; ?></td></tr></table></th>
				<th>F.Recepci&oacute;n SII</th>
				<th>F.Limite Acepta/Rechazo SII</th>
				<th>Exento</th>
				<th>Neto</th>
				<th>IVA</th>
				<th><table border="0" style="border-collapse: collapse;"><tr style="padding: 0px;line-height:0"><td style="padding: 0px;line-height:0"><a class="alink" href="list_dte_recep_v3.php?a=1<?php echo $qrsTotal; ?>">Total</a></td><td style="padding: 0px;line-height:0"><?php echo $fleTotal; ?></td></tr></table></th>
				<th><table border="0" style="border-collapse: collapse;"><tr style="padding: 0px;line-height:0"><td style="padding: 0px;line-height:0"><a class="alink" href="list_dte_recep_v3.php?a=1<?php echo $qrsRut; ?>">Rut.Emisor</a></td><td style="padding: 0px;line-height:0"><?php echo $fleRut; ?></td></tr></table></th>
				<th>Emisor</th>
				<th>Direcci�n</th>
				<th>Comuna</th>
			</tr>
		</thead>

		<tbody>
<?php
//		print_r($_POST);


//		$cont = " SELECT COUNT(D.folio_dte) t ";
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


		if($AAR == "1" && $SAR == "1")	// todas las opciones marcadas evita el filtro
			$NoAplica = "";
		else{
			if($AAR == "1") 
				$sql .= " AND coalesce(xml_respuesta, '') != '' ";
			if($SAR == "1") 
				$sql .= " AND coalesce(xml_respuesta, '') = '' ";
		}


		if($CRM == "1" && $RRM == "1" && $SRM == "1")
			$NoAplica = "";
		if($CRM == "1" && $RRM == "1" && $SRM == "")	
			$sql .= " AND trim(coalesce(merca_dte, '')) IN ('ERM','RFP','RFT') ";
		if($CRM == "1" && $RRM == "" && $SRM == "1")	
			$sql .= " AND trim(coalesce(merca_dte, '')) IN ('ERM','') ";
		if($CRM == "" && $RRM == "1" && $SRM == "1")	
			$sql .= " AND trim(coalesce(merca_dte, '')) IN ('RFP','RFT','') ";	
		if($CRM == "" && $RRM == "" && $SRM == "1")	
			$sql .= " AND trim(coalesce(merca_dte, '')) IN ('') ";			
		if($CRM == "" && $RRM == "1" && $SRM == "")	
			$sql .= " AND trim(coalesce(merca_dte, '')) IN ('RFP','RFT') ";	
		if($CRM == "1" && $RRM == "" && $SRM == "")	
			$sql .= " AND trim(coalesce(merca_dte, '')) IN ('ERM') ";

/*

		if($CRM == "1" && $SRM == "1")	// todas las opciones marcadas evita el filtro
			$NoAplica = "";
		else{
			if($CRM == "1") 
				$sql .= " AND coalesce(xml_recibo_mercaderia, '') != '' ";
			if($SRM == "1") 
				$sql .= " AND coalesce(xml_recibo_mercaderia, '') = '' ";	
		}
*/
// $SAC	respuesta comercial no generada
// $RAC respuesta comercial rechazada
// $AAC respuesta comercial aceptada
		if($AAC == "1" && $RAC == "1" && $SAC == "1")
			$NoAplica = "";
		if($AAC == "1" && $RAC == "1" && $SAC == "")	
			$sql .= " AND trim(coalesce(acuse_dte, '')) IN ('ACD','RCD') ";
		if($AAC == "1" && $RAC == "" && $SAC == "1")	
			$sql .= " AND trim(coalesce(acuse_dte, '')) IN ('ACD','') ";
		if($AAC == "" && $RAC == "1" && $SAC == "1")	
			$sql .= " AND trim(coalesce(acuse_dte, '')) IN ('RCD','') ";	
		if($AAC == "" && $RAC == "" && $SAC == "1")	
			$sql .= " AND trim(coalesce(acuse_dte, '')) IN ('') ";			
		if($AAC == "" && $RAC == "1" && $SAC == "")	
			$sql .= " AND trim(coalesce(acuse_dte, '')) IN ('RCD') ";	
		if($AAC == "1" && $RAC == "" && $SAC == "")	
			$sql .= " AND trim(coalesce(acuse_dte, '')) IN ('ACD') ";	
						
/*		if($AAC == "1" && $RAC == "1" && $SAC == "1")
			$NoAplica = "";
		if($AAC == "1" && $RAC == "1" && $SAC == "")	
			$sql .= " AND trim(coalesce(est_doc, '')) IN ('ACEPTADO','RECHAZADO') ";
		if($AAC == "1" && $RAC == "" && $SAC == "1")	
			$sql .= " AND trim(coalesce(est_doc, '')) IN ('ACEPTADO','') ";
		if($AAC == "" && $RAC == "1" && $SAC == "1")	
			$sql .= " AND trim(coalesce(est_doc, '')) IN ('RECHAZADO','') ";	
		if($AAC == "" && $RAC == "" && $SAC == "1")	
			$sql .= " AND trim(coalesce(est_doc, '')) IN ('') ";			
		if($AAC == "" && $RAC == "1" && $SAC == "")	
			$sql .= " AND trim(coalesce(est_doc, '')) IN ('RECHAZADO') ";	
		if($AAC == "1" && $RAC == "" && $SAC == "")	
			$sql .= " AND trim(coalesce(est_doc, '')) IN ('ACEPTADO') ";	
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
		if(!$resultCount->EOF) 
			$totalFilas = trim($resultCount->fields["t"]); 
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
				$dir_rec_dte = trim($result->fields["dir_rec_dte"]);
				$com_rec_dte = trim($result->fields["com_rec_dte"]);
				$mntneto_dte = trim($result->fields["mntneto_dte"]);
				$mnt_exen_dte = trim($result->fields["mnt_exen_dte"]);
				$tasa_iva_dte = trim($result->fields["tasa_iva_dte"]);
				$iva_dte = trim($result->fields["iva_dte"]);
				$mont_tot_dte = trim($result->fields["mont_tot_dte"]);
				$tipo_docu = trim($result->fields["tipo_docu"]);
				$sEstado = trim($result->fields["est_doc"]); 
				$sAcuseRecibo = trim($result->fields["xml_respuesta"]); 
				$sReciboMerca = trim($result->fields["xml_recibo_mercaderia"]); 
				$sAcuseComer = trim($result->fields["xml_est_res_rev"]); 
				$estado_sii = trim($result->fields["estado_sii"]); 
				
				if($mnt_exen_dte == "")	$mnt_exen_dte = "0";
				if($mntneto_dte == "")	$mntneto_dte = "0";
				if($iva_dte == "")	$iva_dte = "0";
				if($mont_tot_dte == "")	$mont_tot_dte = "0";


				if($fech_limite_sii2 == "") 
					$fech_limite_sii2 = $fech_ahora; // se deja con la misma fecha, para que pueda ser aprobado o rechazado. Solo los que no tienen estado se permiten aceptar o rechazar.
				else
					$fech_limite_sii2 = floatval($fech_limite_sii2);


				$urlPdf = "../dte/view_pdf_compras.php?c=" . $_SESSION["_COD_EMP_USU_SESS"] . "&f=" . $folio_dte . "&t=" . $tipo_docu . "&r=" . $rut_rec_dte . "-" . $dig_rec_dte;
				$urlXML = "../dte/view_xmlrecibido.php?rutEmi=" . $rut_rec_dte . "&nFolioDte=" . $folio_dte . "&nTipoDocu=" . $tipo_docu ;
				$urlSET = "../dte/view_setxmlrecibido.php?rutEmi=" . $rut_rec_dte . "&nFolioDte=" . $folio_dte . "&nTipoDocu=" . $tipo_docu ;


	//			number_format($nMntNeto,0,',','.');
				$linkMerca = "<a href=\"javascript:alert('Recibo de Mercader�a No Recepcionado');\" onMouseover=\"nm_mostra_hint(this, event, 'Recibo de Mercader�a No Generado')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/rm_no.png' alt='Recibo No Recepcionado'></a>";
				if($merca_dte == "ERM")	
					$linkMerca = "<a href=\"javascript:alert('Otorgado Recibo de Mercader�as el " . $fech_merca_dte . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Otorgado Recibo de Mercader�as el " . $fech_merca_dte . "')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/rm_ok.png' alt='Otorgado Recibo de Mercader�as el " . $fech_merca_dte . "'></a>";					
				if($merca_dte == "RFP")	
					$linkMerca = "<a href=\"javascript:alert('Reclamado por Falta Parcial de Mercader�as el " . $fech_merca_dte . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Reclamado por Falta Parcial de Mercader�as el " . $fech_merca_dte . "')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_nook.png' alt='Reclamado por Falta Parcial de Mercader�as el " . $fech_merca_dte . "'></a>";
				if($merca_dte == "RFT")	
					$linkMerca = "<a href=\"javascript:alert('Reclamodo por Falta Total de Mercader�as el " . $fech_merca_dte . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Reclamodo por Falta Total de Mercader�as el " . $fech_merca_dte . "')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_nook.png' alt='Reclamodo por Falta Total de Mercader�as el " . $fech_merca_dte . "'></a>";

				$linkAcuse = "<a href=\"javascript:alert('Acuse de Recibo No Recepcionado');\" onMouseover=\"nm_mostra_hint(this, event, 'Acuse de Recibo No Generado')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ar_no.png' alt='Acuse de Recibo No Recepcionado'></a>";
				if($sAcuseRecibo != "")	
					$linkAcuse = "<a href=\"../dte/view_xml_compras.php?c=" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "&f=" . $folio_dte . "&t=" . $tipo_docu . "&r=".$rut_rec_dte."-".$dig_rec_dte."&x=AR\" target='_blank' onMouseover=\"nm_mostra_hint(this, event, 'Acuse de Recibo Generado')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ar_ok.png' alt='Acuse de Recibo OK'\"></a>";


				$linkComer = "<a href=\"javascript:alert('Respuesta Comercial no Generada');\" onMouseover=\"nm_mostra_hint(this, event, 'Respuesta Comercial No Generada')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_no.png' alt='Respuesta Comercial no Generada'></a>";
				if($acuse_dte != ""){
					if(trim($acuse_dte) == "ACD")
						$linkComer = "<a href=\"javascript:alert('Aceptado el Contenido del Documento el " . $fech_acuse_dte . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Aceptado el Contenido del Documento el " . $fech_acuse_dte . "')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_ok.png' alt='Aceptado el Contenido del Documento el " . $fech_acuse_dte . "'></a>";						
					if(trim($acuse_dte) == "RCD")
						$linkComer = "<a href=\"javascript:alert('Reclamado el Contenido del Documento el " . $fech_acuse_dte . "');\" onMouseover=\"nm_mostra_hint(this, event, 'Reclamado el Contenido del Documento el " . $fech_acuse_dte . "')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/ac_nook.png' alt='Reclamado el Contenido del Documento el " . $fech_acuse_dte . "'></a>";						
				}

				$linkPDF = "<a href=\"" . $urlPdf . "\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'PDF')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/grp__NM__nm_icon_pdf_cede.gif' alt='PDF'></a>";
				$linkXML = "<a href=\"" . $urlXML . "\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'XML')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/sys__NM__xml.jpeg' alt='XML'></a>";
				$linkXMLSET = "<a href=\"" . $urlSET . "\" target=\"_blank\" onMouseover=\"nm_mostra_hint(this, event, 'XML')\" onMouseOut=\"nm_apaga_hint()\"><img src='../img/sys__NM__xml_set.jpg' alt='SET XML'></a>";


				if($tipo_docu == "39" || $tipo_docu == "41" || $tipo_docu == "110" || $tipo_docu == "111" || $tipo_docu == "112"){
					$linkAcuse = "<img src='../img/na.png' height=\"17\" alt='No Aplica'>";
					$linkMerca = "<img src='../img/na.png' height=\"17\" alt='No Aplica'>";
					$linkComer = "<img src='../img/na.png' height=\"17\" alt='No Aplica'>";
				}



	?>

				<tr class="<?php echo $sClassRow; ?>">
					<td>
					<table class="op-grid">
							<tr>
								<td><?php echo $linkPDF; ?></td>
								<td><?php echo $linkXML; ?></td>
								<td><?php echo $linkXMLSET; ?></td>
								<td>
                  <?php
//                        if(trim($sEstado) == "" && $fech_ahora <= $fech_limite_sii2){ 
                        if($fech_ahora <= $fech_limite_sii2){ 
							if(trim($merca_dte) == "" || trim($acuse_dte) == "")	{
							?>
								<a href="#_FENVACT" onclick="responderSII('<?php echo $folio_dte; ?>', '<?php echo $tipo_docu; ?>', '<?php echo $rut_rec_dte; ?>', '<?php echo $dig_rec_dte; ?>', '<?php echo $merca_dte; ?>','<?php echo $fech_merca_dte; ?>','<?php echo $acuse_dte; ?>','<?php echo $fech_acuse_dte; ?>');" >Responder DTE</a>	
							
					<?php
							}
						}
					//	else
                      //          echo "<b>" . ucfirst(strtolower($sEstado)) . "</b>";

                  ?> 
								</td>
							</tr>
							<tr>
								<td><?php echo $linkAcuse; ?></td>
								<td><?php echo $linkMerca; ?></td>
								<td><?php echo $linkComer; ?></td>
							</tr>
						</table>				
					</td>
					<td><?php echo poneTipo($tipo_docu); ?></td>
					<td align="right"><?php echo number_format($folio_dte,0,',','.'); ?></td>
					<td><?php echo $fec_emi_doc; ?></td>
					<td><?php echo $fec_rece_doc; ?></td>
					<td><?php echo $fec_rece_sii; ?></td>
					<td><?php echo $fech_limite_sii; ?></td>					
					<td align="right"><?php echo number_format($mnt_exen_dte,0,',','.'); ?></td>
					<td align="right"><?php echo number_format($mntneto_dte,0,',','.'); ?></td>
					<td align="right"><?php echo number_format($iva_dte,0,',','.'); ?></td>
					<td align="right"><?php echo number_format($mont_tot_dte,0,',','.'); ?></td>
					<td><?php echo $rut_rec_dte . "-" . $dig_rec_dte; ?></td>
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
			<tr><td colspan="16" class="no-results"><i class="bi bi-inbox"></i><strong>No hay resultados para su busqueda</strong><br>Pruebe ajustando los filtros o limpie la consulta para volver a listar.</td></tr>
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
	//calculo el total de p�ginas
	$total_paginas = ceil($totalFilas / $TAMANO_PAGINA);

	$paginasLista = $total_paginas;
	if($paginasLista < 1)
		$paginasLista = 1;
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
	      echo '<li><a href="'.$url.'?pagina='.($inicio-1). $qrstring.'">Previous</a></li>';
      for ($i=$inicio;$i<=($paginasLista + $inicio - 1);$i++) {
         if ($pagina == $i)
            //si muestro el �ndice de la p�gina actual, no coloco enlace
	            echo '<li><span class="page-current">'.$pagina.'</span></li>';
         else
            //si el �ndice no corresponde con la p�gina mostrada actualmente,
            //coloco el enlace para ir a esa p�gina
	            echo '<li><a href="'.$url.'?pagina='.$i.$qrstring.'">'.$i.'</a></li>';
      }
      if ($total_paginas > $paginasLista)
	         echo '<li><a href="'.$url.'?pagina='.($i).$qrstring.'">Next</a></li>';
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
		</div>

</form>
	</div>
<br><br><br>
<?php
	}
?>

</div>

</div>

 </body>
</html>
