<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");        
  include("../include/tables.php");  

  $nCodDoc = trim($_GET["nCodDoc"]);
  $nCodDocNew = trim($_GET["nCodDocNew"]);  
  $sDescDoc = trim($_GET["sDescDoc"]);
  $sAccion = trim($_GET["sAccion"]);  
  $sMsgJs = trim($_GET["sMsgJs"]);  
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>OpenB</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<base href="<?php echo $_LINK_BASE; ?>" />
		<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/msg.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/funciones.js"></script>		
		

		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/custom.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/layout.css">
		<link rel="stylesheet" type="text/nonsense" href="skins/<?php echo $_SKINS; ?>/css/misc.css">


<script type="text/javascript">
<!--


function _body_onload()
{
	loff();
 <?php 
  if($sMsgJs != "")
    echo "alert(" . $sMsgJs . ");\n";
 
 ?>      
  
	SetContext('cl_ed');
		
}

function _body_onunload()
{
	lon();
	
}


	function valida(){
	  var F = document._FFORM;

		  if(vacio(F.nCodDocNew.value,_MSG_COD_DOC) == false){
			F.nCodDocNew.select();
			return false;
		  }

		  if(vacio(F.sDescDoc.value,_MSG_DESC_DOC) == false){
			F.sDescDoc.select();
			return false;
		  }	

	  F.submit();
	}
//-->
		</script>
	</head>

	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">

    <form name="_FFORM" action="mantencion/pro_tc.php" method="post" onsubmit="return valida();">
    <input type="hidden" name="nCodDoc" value="<?php echo $nCodDoc; ?>">
    <input type="hidden" name="sAccion" value="<?php echo $sAccion; ?>">

  	
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td id="screenWH">
	<div class="pathbar"><a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>mantencion/list_tip_doc.php';">Tipo Documento</a> &gt;</div>
	<div class="screenTitle">
		<table width="100%" cellspacing="0">
		<tr>
			<td>Tipo Documento (Factura Electr&oacute;nica):</td>
			<td class="uplevel">   <a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>mantencion/list_tip_doc.php';">
      <div class="commonButton" id="bid-up-level" title="Subir nivel">       
        <button name="bname_up_level" >Subir nivel</button><span>Subir nivel</span>
        </a>
        </div></td>
		</tr>
		</table>
	</div>
	<div id="screenSubTitle"></div>
	<div id="screenTabs">
		<div id="tabs">
			
		</div>

	</div>
	<div class="screenBody" id="">
		
	<div class="formArea">
		<fieldset>

<legend>Formulario de Tipo de Documento </legend>
<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td>



<table class="formFields" cellspacing="0" width="100%">
	<tr>
		<td class="name"><label for="fid-cname">Codigo Documento:</label>&nbsp;*</td>
		<td><input type="text" name="nCodDocNew" id="fid-cname" value="<?php echo $nCodDocNew; ?>" size="3" maxlength="3"></td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">Documento:</label>&nbsp;*</td>
		<td><input type="text" name="sDescDoc" id="fid-cname" value="<?php echo $sDescDoc; ?>" size="50" maxlength="100"></td>
	</tr>

</table>

<input type="hidden" name="start" value="">

</td></tr></table></fieldset>

	</div>
	
	<div class="formArea">
		<table width="100%" class="buttons" cellspacing="0" cellpadding="0"><tr>
			<td class="main" width="0"></td>
			<td class="footnote"><span class="required">*</span> Campos requeridos.</td>
			<td class="misc" width="0">
				<div class="commonButton" id="bid-ok" title="Aceptar" onMouseOver="" onMouseOut="">
				<button name="bname_ok" onClick="return valida();">Aceptar</button><span>Aceptar</span></div>
        <a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>mantencion/list_tip_doc.php';">
				<div class="commonButton" id="bid-cancel" title="Cancelar" onClick="location.href='<?php echo $_LINK_BASE; ?>mantencion/list_tip_doc.php';" onMouseOver="" onMouseOut=""><button name="bname_cancel">Cancelar</button><span>Cancelar</span></div></a>
			</td>
		</tr></table>

		<input type="hidden" name="cmd" value="update">
		<input type="hidden" name="lock" value="false">
		<input type="hidden" name="previous_page" value="cl_ed">

	</div>

</form>

	</div>
	</td></tr></table>
  </form>
	</body>

	<script type="text/javascript">
		try {
			lsetup();
		} catch (e) {
		}
	</script>
</html>