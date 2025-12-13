<?php 
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	include("../include/ver_aut.php");
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Excel Softland</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#fecIni" ).datepicker({
		dateFormat: "yy-mm-dd"
    });
    $( "#fecFin" ).datepicker({
		dateFormat: "yy-mm-dd"
    });
  } );

  function generaExel(){
	var F = document._FORM_;
	var fini = F.fecIni.value;
	var ffin = F.fecFin.value;
	var ok = true;

	if(fini.trim() == ""){
		alert("Debe seleccionar la fecha desde que se genera el excel");
		ok = false;
	}
	if(ffin.trim() == ""){
		alert("Debe seleccionar la fecha hasta que se generara el excel");
		ok = false;
	}

	if(ok == true){
		F.submit();
	}

  }
  </script>
</head>

 <style type="text/css">
body {
    padding: 0 0 0 6px;
    margin: 0px;
    margin-top: -1px;
}
body {
    background-color: #F9F9F9;
    background-image: url(../../images/left_bg.gif);
    background-position: bottom;
    background-repeat: no-repeat;
}
body {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 11px;
    font-weight: normal;
    color: #000000;

}
body {
    display: block;

}

 </style>

<body>
<form name="_FORM_" id="_FORM_" method="get" action="vgm_excel2.php" target="_blank">
<input type="hidden" name="accion" value="OK">

<h2>Excel de Ventas para Softland</h2>
<p>&nbsp;</p>

<p>Empresa: &nbsp; <select name="emp">
	<option value="77648628" selected>SOCIEDAD DE PROFESIONALES VGM LIMITADA</option>
	<option value="77648624">SOCIEDAD DE PROFESIONALES VGM OUTSOURCING LIMITADA</option>
	<option value="77239803">VGM AUDITORES LIMITADA</option>

</select>&nbsp;&nbsp;&nbsp;	

<p>&nbsp;</p>

<p>Desde: &nbsp; <input type="text" id="fecIni" name="fecIni" value="<?php echo $fini; ?>" readonly> &nbsp;&nbsp;&nbsp;

	Hasta: &nbsp; <input type="text" id="fecFin" name="fecFin" value="<?php echo $ffin; ?>" readonly> &nbsp;&nbsp;&nbsp;

	<input type="button" value="Generar Excel" onclick="generaExel();">
</p>
 
 </form>
</body>
</html>