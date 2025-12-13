<?php 
include("../include/ver_aut.php");      
include("../include/ver_emp_adm.php");

$nFolio	= $_GET["nFolio"];
$nTipoDTE	= $_GET["nTipoDTE"];
$nMontTot = $_GET["nMontTot"];
$nCodEmp = $_SESSION["_COD_EMP_USU_SESS"];
if (isset($_POST['accion'])){
	$correo=$_POST['correo'];
	$rut=$_POST['rut'];
	$razon_social=$_POST['razon_social'];
	$direccion=$_POST['direccion'];
	$correo0=$_POST['correo0'];
	$monto_cesion=$_POST['monto_cesion'];
	$fecha_vencimiento=$_POST['fecha_vencimiento'];
	$otras_condiciones=$_POST['otras_condiciones'];
	$correo=$_POST['correo'];
	$correo2=$_POST['correo2'];

	switch ($_POST['accion']){
		case 'grabar':
			$conn = pg_connect("host=10.30.1.194 port=5432 dbname=opendte user=opendte password=root8831");
			$sql = "  SELECT ";
			$sql .="    E.codi_empr,  ";
			$sql .="    E.rs_empr, ";
			$sql .="    E.rut_empr, ";
			$sql .="    E.dv_empr, ";
			$sql .="    E.dir_empr ";
			$sql .="  FROM ";
			$sql .="    empresa E ";
			$sql .="  WHERE ";
			$sql .="     E.codi_empr = " . $_SESSION["_COD_EMP_USU_SESS"];

			$result=pg_query($conn,$sql);
			while ($row=pg_fetch_array($result)){
				$salida=$row;
			}
			$rut_cedente=$salida["rut_empr"];
			$dv_empresa=$salida["dv_empr"];
			$razon_social_cedente=$salida["rs_empr"];
			$dir_cedente = $salida["dir_empr"];
			var_dump($dir_cedente);
		break;
	}
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>OpenDTE GO!</title>
		<script language="javascript">
		function graba(){
			var form=document.formulario1;
			form.accion.value='grabar';
			form.submit();
			
			/*if (valida()==true){
				form.accion.value='grabar';form.submit();
			}
			*/
		}
		</script>
	</head>
	<body>
		<form name="formulario1" action="" method="post">
<input type="hidden" name="accion" value="" readonly>
<input type="hidden" name="codigo" value="" readonly>
<input type="hidden" name="xml_id" value="<?php echo $xml_id;?>" readonly>
<input type="hidden" name="tipo_docu" value="<?php echo $tipo_docu;?>" readonly>
<table width="96%" align="center" cellspacing="2">
<tr>
<td colspan="2" class="ttitulo">Datos de la Cesion para el docto folio <?php echo $nFolio;?></td>
</tr>

<tr>
<td class="cab_campo" width="15%">Rut Cesionario</td>
<td width="85%"><input type="text" name="rut" value="" class="campo" size="20" maxlength="10"><label class="nota">*</label></td>
</tr>
<tr> 
<td class="cab_campo" width="15%">Razon Social Cesionario</td>
<td width="85%"><input type="text" name="razon_social" value="" class="campo" size="64" maxlength="100"><label class="nota">*</label></td>
</tr>
<tr>
<td class="cab_campo" width="15%">Direccion Cesionario</td>
<td width="85%"><input type="text" name="direccion" value="" size="64" maxlength="60" class="campo"><label class="nota">*</label></td>
</tr>

<tr>
<td class="cab_campo" width="15%">Monto Cesion</td>
<td width="85%" class="texto"><input type="text" name="monto_cesion" value="" class="campo" size="20" maxlength="10">&nbsp;&nbsp;&nbsp;Monto del documento: $ <?php echo number_format($nMontTot,0,',','.');?><label class="nota">*</label><input type="hidden" name="mnt" value="<?php echo $nMontTot?>" readonly></td>
</tr>
<tr>
<td class="cab_campo" width="15%">Fecha Ult. Vencimineto</td>
<td width="85%"><input type="text" name="fecha_vencimiento" class="campo" size="12" maxlength="10" value="">&nbsp;
	<!--<img vspace="0" src="img/calendario.gif" border="0" onClick="Javascript:/*consultar('calendario.php?codigo=fecha_vencimiento',300,176);" style="cursor:hand;"  alt="[Cargar fecha desde calendario]">-->
	&nbsp;<label class="nota">*</label></td>
</tr>
<tr>
<td class="cab_campo" width="15%">Otras Condiciones</td>
<td width="85%"><textarea name="otras_condiciones" id="otras_condiciones" class="campo" rows="5" cols="43" onKeyDown="cuenta(this,document.formulario1.numero,250);Textarea_Sin_Enter(event.keyCode,this.id);" onKeyUp="cuenta(this,document.formulario1.numero,250);Textarea_Sin_Enter(event.keyCode,this.id);"></textarea>&nbsp;<label class="texto">Caract. Disp.</label>&nbsp;<input type="text" class="texto" value="250" readonly name="numero" size="3" style="border:0;"><label class="nota">*</label></td>
</tr>
<tr>
<td class="cab_campo" width="15%">Correo Cesionario</td>
<td width="85%"><input type="text" name="correo0" value="" class="campo" size="64" maxlength="80"><label class="nota">*</label></td>
</tr>
<tr>
<td class="cab_campo" width="15%">Correo deudor</td>
<td width="85%"><input type="text" name="correo" value="" class="campo" size="64" maxlength="80"><label class="nota">*</label></td>
</tr>
<tr>
<td class="cab_campo" width="15%">Correo notificacion SII</td>
<td width="85%"><input type="text" name="correo2" value="" class="campo" size="64" maxlength="80"><label class="nota">*</label></td>
</tr>
</table>
<br>
<table cellpadding="0" cellspacing="0" border="0" width="96%" align="center">
<tr><td class="nota">Los datos marcados con * son de caracter obligatorios</td></tr>
<tr>
<td align="right">
<input type="button" name="save" class="boton" value="Grabar" onClick="Javascript:graba();">&nbsp;&nbsp;
</td>
</tr>
</table>
</form>
	</body>
</html>
