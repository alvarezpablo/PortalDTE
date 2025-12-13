<?php 
 	session_start();
	if ($_SESSION["_COD_USU_SESS"] == ''){
			  header("location: login.php");
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="shortcut icon" href="/favicon.ico">
<title>Portal OpenDTExpress - Plan Full</title>

<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript">
<!--
    if (window != window.top)
      top.location.href = location.href;
var opt_no_frames = false;
var opt_integrated_mode = false;var _help_prefix = '';

var _help_module = "";
var _context = "";

//-->
</script>

</head>

	<frameset rows="62,*" border=0 framespacing=0 frameborder=0>
		<frame src="newtop.php"	name="topFrame"	frameborder=0 border=0 framespacing=0 marginheight=0 marginwidth=0 scrolling="No" noresize>
		<frameset cols="210,*" border="0" frameborder="0" framespacing="0">
			<frame src="left.php" name="lFrame" frameborder="0" border="0" noresize>
			<frame src="main.php" name="workFrame" frameborder="0" border="0" framespacing="0" marginheight="7" marginwidth="7" noresize scrolling="yes">
		</frameset>
	</frameset><noframes></noframes>
</html>
