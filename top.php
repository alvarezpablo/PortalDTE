<?php 
  include("include/config.php");  
  $_NIVEL_RAIZ = true;
  include("include/ver_aut.php");   

  $sNomUser = $_SESSION["_ALIAS_USU_SESS"];
  $sNomEmp = $_SESSION["_NOM_EMP_USU_SESS"]; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Portal OpenDTExpress - Plan Full</title>
<style type="text/css">
<!--
body {
	margin:0px;
	padding:0px;
}
#header {
	background-image: url(imag/header-opendte.jpg);
	background-repeat: no-repeat;
	background-position: left top;
	height:60px;
	padding:0 0 0 24px;
}
-->
</style>
<base href="<?php echo $_LINK_BASE; ?>" />
<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
</head>
<body>
<div id="header"> 
<div align="center" style="color: white;"><TABLE>
<TR>
	<TD height="23" valign="BOTTOM"><b>Usuario: <?php echo $sNomUser; ?></b></TD>
</TR>
<TR>
	<TD><b>Empresa: <?php echo $sNomEmp; ?> </b></TD>
</TR>
</TABLE></div>
</div>
</body>
</html>
