<?php include("../include/config.php");  ?>
<?php include("../include/tables.php");  ?>
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
	<div class="screenTitle">
		<table width="100%" cellspacing="0">
		<tr>
			<td>Carga Caf:</td>
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
		
	<div class="listArea">
		<fieldset>

		<legend>Tipo Documento</legend>

		<table cellspacing="0" width="100%" class="list">
			<tr>
				<th>Documento&nbsp;</td>
			</tr>

			<tr>
				<td class="oddrowbg"><label for="fid-cname"><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>caf/form_caf.php';">Factura Electr&oacute;nica</a></label>&nbsp;</td>
			</tr>

			<tr>
				<td class="evenrowbg"><label for="fid-cname"><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>caf/form_caf.php';">Nota de Credito Electr&oacute;nica</a></label>&nbsp;</td>
			</tr>

			<tr>
				<td class="oddrowbg"><label for="fid-cname"><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>caf/form_caf.php';">Nota de Debito Electr&oacute;nica</a></label>&nbsp;</td>
			</tr>

		</table>

		<input type="hidden" name="start" value="">

		</fieldset>

	</div>
	
	<div class="formArea">
		<table width="100%" class="buttons" cellspacing="0" cellpadding="0"><tr>
			<td class="main" width="0"></td>
			<td class="footnote">&nbsp;</td>
			<td class="misc" width="0">

			</td>
		</tr></table>

		<input type="hidden" name="cmd" value="update">
		<input type="hidden" name="lock" value="false">
		<input type="hidden" name="previous_page" value="cl_ed">

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