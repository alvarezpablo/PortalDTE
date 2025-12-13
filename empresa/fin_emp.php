<?php 
    include("../include/config.php");  
    include("../include/ver_aut.php");      
    include("../include/ver_aut_adm.php");      
    include("../include/tables.php"); 
 
    $sRutEmp = trim($_GET["sRutEmp"]);
    $sDvEmp = trim($_GET["sDvEmp"]);        
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


<script type="text/javascript">
<!--


function _body_onload()
{
	loff();
	SetContext('cl_ed');
		
}

function _body_onunload()
{
	lon();
	
}

//-->
		</script>
	</head>

	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td id="screenWH">
	<div class="pathbar"><a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>empresa/listempre.php';">Empresas</a> &gt; </div>
	<div class="screenTitle">
		<table width="100%" cellspacing="0">
		<tr>
			<td>Informaci&oacute;n de empresa <?php echo $sRutEmp . "-" . $sDvEmp; ?>:</td>
			<td class="uplevel"><div class="commonButton" id="bid-up-level" title="Subir nivel"><button name="bname_up_level">Subir nivel</button><span>Subir nivel</span></div></td>
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
			<legend>Datos Ingresados </legend>
				<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
<br>	<br>	<br>
					<div class="commonButton" id="bid-ok" title="Aceptar" onClick="location.href='<?php echo $_LINK_BASE; ?>empresa/listempre.php';" onMouseOver="" onMouseOut=""><button name="bname_ok">Aceptar</button><span>Aceptar</span></div>
	<br>				<br>	<br>
				</td></tr></table>
		</fieldset>

	</div>
	
	<div class="formArea">
		<table width="100%" class="buttons" cellspacing="0" cellpadding="0"><tr>
			<td class="main" width="0"></td>
		</tr></table>


	</div>

</form>

	</div>
	</td></tr></table>
	</body>

	<script type="text/javascript">
		try {
			lsetup();
		} catch (e) {
		}
	</script>
</html>