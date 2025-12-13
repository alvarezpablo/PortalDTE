<?php

  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm_super.php");        
  include("../include/db_lib.php"); 
  include ("../include/upload_class.php"); 
  $msg = $_GET["msg"];
  $conn = conn();

  if($msg == "0") $msg = "Se produjo un error, por favor int\u00e9ntelo de nuevo m\u00e1s tarde";
  if($msg == "2") $msg = "Se produjo un error, el DTE a sido cesido anteriormente";
  if($msg == "3") $msg = "Se produjo un error, el monto de cesido es mayor al monto del DTE";

  ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> Ceder DTE </title>
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">

		<link rel="shortcut icon" href="/favicon.ico">
		<title>OpenB</title>
		<base href="<?php echo $_LINK_BASE; ?>" />
		
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/custom.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/layout.css">
		<link rel="stylesheet" type="text/nonsense" href="skins/<?php echo $_SKINS; ?>/css/misc.css">



<script type="text/javascript">
<!--

<?php 
	if($msg != ""){
		echo "alert(\"".$msg."\");";
	}
?>

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

	function ceder(){
		var F = document.F;
		var folio = F.folio_dte.value;
		var tipo_docu = F.tipo_docu.options[F.tipo_docu.selectedIndex].value;

		if(tipo_docu == ""){
			alert("Seleccionar Tipo DTE");
			return false;
		}

		if(folio == ""){
			alert("Ingresar Folio de DTE");
			return false;
		}

		if(isNumber(folio) == false){
			alert("Ingresar Folio Num\u00e9rico");
			return false;
		}		

/*
rut_cedente
razon_cedente
dir_cedente
email_cedente
rut_cesionario
razon_cesionario
dir_cesionario
email_cesionario
monto_cesion
fecha_venc
email_notificacion
otras_condic
email_deudor
*/
		if(confirm("Ceder DTE folio " + folio+ "?") == true)
			return true;
		else
			return false;
	}
//-->
</script>
 </head>

 <body>
    <form name="F" method="post" action="cesion/pro_ceder.php" onsubmit="return ceder();">
	<input type="hidden" name="ok" value="OK">

<table>
<tr>
	<td>Tipo DTE (*)</td>
	<td><select name="tipo_docu">
		<option value="">Seleccionar Tipo</option>
		<option value="33">Factura Electr&oacute;nica</option>
		<option value="34">Factura no Afecta o Exenta Electr&oacute;nica</option>
		<option value="46">Factura de Compra Electr&oacute;nica</option>
		<option value="43">Liquidaci&oacute;n Factura Electr&oacute;nica</option>	
	</select>
</td>
</tr>
<tr>
	<td>Folio (*)</td>
	<td><input type="text" name="folio_dte" maxlength="10" size="15"></td>
</tr>
<tr>
	<td colspan="2">Datos Cedente</td>
</tr>
<?php 
$sql = "SELECT 
         rut_empr, 
		 dv_empr,
         rs_empr, 
         dir_empr
        FROM 
          empresa
        WHERE 
          codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";
$result = rCursor($conn, $sql);        
$rut_ced= trim($result->fields["rut_empr"]);
$dv_ced= trim($result->fields["dv_empr"]);
$rs_ced= trim($result->fields["rs_empr"]);
$dir_ced= trim($result->fields["dir_empr"]);

?>
<tr>
	<td>Rut Cedente (*)</td>
	<td><label><?php echo $rut_ced."-".$dv_ced;?></label></td>
	<td><input type="hidden" name="rut_cedente" maxlength="10" size="15" value="<?php echo $rut_ced."-".$dv_ced;?>"></td>-->
	
</tr>
<tr>
	<td>Razón Social Cedente (*)</td>
	<td><label><?php echo $rs_ced;?></label></td>
	<td><input type="hidden" name="razon_cedente" maxlength="100" size="40" value="<?php echo $rs_ced;?>"></td>
</tr>
<tr>
	<td>Dirección Cedente (*)</td>
	<td><label><?php echo $dir_ced;?></label></td>
	<td><input type="hidden" name="dir_cedente" maxlength="60" size="40" value="<?php echo $dir_ced;?>"></td>
</tr>
<tr>
	<td>Email Cedente (*)</td>
	<td><input type="text" name="email_cedente" maxlength="40" size="40"></td>
</tr>
<tr>
	<td colspan="2">Datos Cesionario</td>
</tr>
<tr>
	<td>Rut Cesionario (*)</td>
	<td><input type="text" name="rut_cesionario" maxlength="40" size="40"></td>
</tr>
<tr>
	<td>Razón Social Cesionario (*)</td>
	<td><input type="text" name="razon_cesionario" maxlength="100" size="40"></td>
</tr>
<tr>
	<td>Dirección Cesionario (*)</td>
	<td><input type="text" name="dir_cesionario" maxlength="60" size="40"></td>
</tr>
<tr>
	<td>Email Cesionario (*)</td>
	<td><input type="text" name="email_cesionario" maxlength="40" size="40"></td>
</tr>
<tr>
	<td>Monto Cesión (*)</td>
	<td><input type="text" name="monto_cesion" maxlength="18" size="40"></td>
</tr>
<tr>
	<td>Fecha de último vencimiento (AAAA-MM-DD)(*)</td>
	<td><input type="text" name="fecha_venc" maxlength="40" size="40"></td>
</tr>
<tr>
	<td colspan="2">Datos de quien firma digitalmente</td>
</tr>

<tr>
	<td>Rut</td>
	<td><input type="text" name="rut_firma" maxlength="10" size="20"></td>
</tr>
<tr>
	<td>Nombre</td>
	<td><input type="text" name="nombre_firma" maxlength="60" size="40"></td>
</tr>

<tr>
	<td colspan="2">Otros Datos</td>
</tr>

<tr>
	<td>Email Notificación SII</td>
	<td><input type="text" name="email_notificacion" maxlength="40" size="40"></td>
</tr>
<tr>
	<td>Otras condiciones</td>
	<td><input type="text" name="otras_condic" maxlength="500" size="80"></td>
</tr>
<tr>
	<td>Email Deudor</td>
	<td><input type="text" name="email_deudor" maxlength="40" size="80"></td>
</tr>
<tr>
	<td colspan="2">(*) Obligatorios</td>
</tr>
<tr>
	<td colspan="2">	<input type="submit" value="Ceder DTE"></td>
</tr>
</table>
  </form>
 </body>
</html>

