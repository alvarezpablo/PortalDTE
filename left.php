<?php 
  include("include/config.php");  
  $_NIVEL_RAIZ = true;  
  include("include/ver_aut.php");    
  include("include/categoria.php");  
  
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>OpenB</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

		<base href="<?php echo $_LINK_BASE; ?>" />

		<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/leftframe.js"></script>

		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/left/custom.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/left/layout.css">
		<link rel="stylesheet" type="text/nonsense" href="skins/<?php echo $_SKINS; ?>/css/misc.css">


<script language="javascript">
<!--
function _body_onload()
{
	if (navigator.appName == 'Microsoft Internet Explorer' && document.documentElement && navigator.userAgent.indexOf ('Opera') == -1) setScrollInIE(); SetConHelp();
	loff();
	
}

function _body_onunload()
{
	lon();
	
}

function open_help()
{
	try {
		window.open('/help.php?context=' + GetHelpPrefix() + GetContext() + (GetHelpModule() ? '&module=' + GetHelpModule() : ''),
				'help',
				'toolbar=no,width=500,height=400,innerHeight=400,innerWidth=500,scrollbars=yes,resizable=yes');
	} catch (e) {
		return false;
	}
	return true;
}


var opt_no_frames = false;
var opt_integrated_mode = false;

var activeItem = "clients";

function my_logout()
{
	if (confirm("Seguro que quiere desconectarse?"))
		go_to_top("/logout.php3");
	return false;
}
		
//-->
		</script>
	</head>

	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="leftCP">

	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerH">&nbsp;</td></tr></table>
	<div class="screenBody" id="">
		<form action="/left.php3" method="post" enctype="multipart/form-data" >

		<table id="navArea" cellspacing="0" cellpadding="0" width="100%" border="0" summary="Navigation Items Area"><tr><td>
			<div id="navLayout">
	
	<!-- ************************************************************************************************************* -->			

<?php	aMenu($_SKINS);	?>

	<!-- ************************************************************************************************************* -->


			</div>
		</td></tr></table>

								
	</form>
	</div>

	</body>

</html>

