<?php include("../include/config.php");  ?>
<?php include("../include/tables.php");  ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> OpenB </TITLE>
<link rel="shortcut icon" href="/favicon.ico">
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">

<base href="<?php echo $_LINK_BASE; ?>" />
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/custom.css">
<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/layout.css">
<link rel="stylesheet" type="text/nonsense" href="skins/<?php echo $_SKINS; ?>/css/misc.css">

</HEAD>

	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	
	<a href="#" name="top" id="top"></a>

	<?php sTituloCabecera("Error"); ?>

	<div class="screenBody">
		<div class="listArea">
			<fieldset>
				<legend>Mensaje de Rechazo SII</legend>
				<br>
				Su factura electr&oacute;nica se rechazo por que el Nº dte no es valido.
			</fieldset>
		</div>
	</div>
			
</BODY>
</HTML>
