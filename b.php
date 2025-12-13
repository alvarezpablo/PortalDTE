<?php

  include("include/config.php");  
//  include("../include/ver_aut.php");      
//  include("../include/ver_emp_adm.php");        
  include("include/db_lib.php"); 
  include("include/tables.php"); 
  
  $_sAccion = $_GET["sAccion"];
  $_sRutEmp = $_GET["sRutEmp"];
  $_nFolioBol = $_GET["nFolioBoleta"];
  $_nTipoDocu = $_GET["nTipoDocu"];
  $_dFecha = $_GET["dFecha"];
  $_nTotal = $_GET["nTotal"];

  if($_sAccion == "V"){
      $_nFolioBol = $_nFolioBol * 1;

      $conn = conn();
      $sTipo = $_GET["sTipo"];  

//      $sql = "select path_pdf from xmldte where folio_dte='" . str_replace("'","''",$_nFolioBol) . "' and tipo_docu='" . str_replace("'","''",$_nTipoDocu) . "' and codi_empr in (select codi_empr from empresa where rut_empr='" . str_replace("'","''",$_sRutEmp) . "')";

  $sql = "select x.path_pdf from xmldte x, dte_enc d where x.folio_dte='" . str_replace("'","''",$_nFolioBol) . "' and x.tipo_docu='" . str_replace("'","''",$_nTipoDocu) . "'
and x.codi_empr in (select codi_empr from empresa where rut_empr='" . str_replace("'","''",$_sRutEmp) . "') AND 
	x.codi_empr = d.codi_empr and 	x.folio_dte = d.folio_dte and x.tipo_docu = d.tipo_docu and 
	fec_emi_dte='" . str_replace("'","''",$_dFecha) . "' and mont_tot_dte='" . str_replace("'","''",$_nTotal) . "'";
      $result = rCursor($conn, $sql);

    if (!$result->EOF) {
      $linkPDF = trim($result->fields["path_pdf"]);
      echo "<h1><font color=blue><center>Boleta Valida</center></font></h1><br><br>";
      echo "<a href='dte/view_pdf.php?sUrlPdf=" . $linkPDF . "'>Descarga PDF</a><br><br>";    

    }
    else{
      echo "<br><br><table border=1 bordercolor=red align=center><tr><td><h1><font color=red><center>Boleta no registrada</center></font></h1></td></tr></table><br><br>";
    }
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>OpenB</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

		<base href="<?php echo $_LINK_BASE; ?>" />

		<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/custom.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/layout.css">
		<link rel="stylesheet" type="text/nonsense" href="skins/<?php echo $_SKINS; ?>/css/misc.css">
<script language="javascript" type="text/javascript" src="javascript/funciones.js"></script> 

		<script type="text/javascript">
function fechaYMD(fecha) {
	patron = /^\d{4}\-\d{2}\-\d{2}$/

	if (!patron.test(fecha))
		return false;
	else
		return true;
}

function valida(){
  var F = document._FFORM;
  var rutaux = Trim(F.sRutEmp.value) + "-" + Trim(F.sDvRut.value);
  
  if(rut(rutaux,"Rut empresa no valido") == false){  
    F.sRutEmp.select();
    return false;  
  }

  if(numerico(F.nFolioBoleta.value,"",1,"El folio debe ser numerico") == false){
    F.nFolioBoleta.select();
    return false;
  }

  if(fechaYMD(F.dFecha.value) == false){
    alert("Fecha debe ser en formato YYYY-MM-DD");
    F.dFecha.select();
    return false;
  } 

  if(numerico(F.nTotal.value,"",1,"El monto debe ser un entero") == false){
    F.nTotal.select();
    return false;
  } 

  return true;
}
 
function _body_onload()
{
	SetContext('clients');
	setActiveButtonByName('clients');
	loff();
	
}
function _body_onunload()
{
	lon();
	
}


var opt_no_frames = false;
var opt_integrated_mode = false;
setActiveButtonByName("clients");

		</script>
	</head>

	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	<form method="get" action="b.php" name="_FFORM" onsubmit="return valida();">
<table border=0 align=center><tr><td colspan=2 align=center>
	  <input type="hidden" name="sAccion" value="V">
	  <h2>Verificaci&oacute;n Boletas Electr&oacute;nicas</h2><br></td></tr>
<tr><td>
		Rut Empresa Emisora :</td><td> <input type="text" name="sRutEmp" maxlength="8"> - <input type="text" name="sDvRut" maxlength="1" size=2><br><br></td></tr>
<tr><td>		Tipo DTE :</td><td> <select name="nTipoDocu">
	<option value="39" selected>Boleta Electr&oacute;nica</option>
	<option value="41">Boleta no Afecta o Exenta Electr&oacute;nica</option>
</select><br><br></td></tr>
<tr><td>		Folio Boleta : </td><td><input type="text" name="nFolioBoleta" maxlength="18"><br><br>		</td></tr>
<tr><td>                Fecha Emisi&oacute;n (yyyy-mm-dd) : </td><td><input type="text" name="dFecha" maxlength="10"><br><br>          </td></tr> 
<tr><td>                Total Boleta : </td><td><input type="text" name="nTotal" maxlength="10"><br><br>          </td></tr> 
<tr><td colspan=2 align=center>		<input type="submit" value="Verificar"></td></tr>
</td></tr></table>
	</form>
	</body>
	</html>

