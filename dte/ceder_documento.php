<?php


	include("../include/ver_aut.php");      
    include("../include/ver_emp_adm.php");
    
//var_dump($_GET);
$nFolio	= $_GET["nFolio"];
$nTipoDTE	= $_GET["nTipoDTE"];
$nMontTot = $_GET["nMontTot"];
$nCodEmp = $_SESSION["_COD_EMP_USU_SESS"];

 //require('inc/funciones.php');
//TODO SACAR LOS VALORES DE BASES DE DATOS
$conn = pg_connect("host=10.30.1.194 port=5432 dbname=opendte user=opendte password=root8831"); 
//$conn = pg_connect("host=127.0.0.1 port=5432 dbname=opendte user=opendte password=root8831");
//pg_query($conn,"SET NAMES 'LATIN1'");
pg_set_client_encoding($conn, "LATIN1");

$mostrar_form = true;

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

				$rut_cedente = $salida["rut_empr"];
				$dv_empresa = $salida["dv_empr"];
				$razon_social_cedente=$salida["rs_empr"];
				$dir_cedente = $salida["dir_empr"];


				$sql="select count(1) as contador from dte_ceder where codi_empr='".$nCodEmp."' and tipo_docu='".$nTipoDTE."' and folio_dte='".$nFolio."'";
	
				$result=pg_query($conn,$sql);
				while ($row=pg_fetch_array($result)){
					$contadorx=$row[0];
				}

				if ($contadorx==0){
					//nombre autorizado
					$sql = "select valor_config FROM config WHERE cod_config = 'NOMBRE_CESION' and codi_empr=".$nCodEmp." and valor_config <> '' ";
					$result=pg_query($conn,$sql);
					while ($row=pg_fetch_array($result)){
						$nombre_cedente=$row[0];
					}
					//rut
					$sql = "select valor_config FROM config WHERE cod_config = 'RUT_CESION' and codi_empr=".$nCodEmp." and valor_config <> ''";
					$result=pg_query($conn,$sql);
					while ($row=pg_fetch_array($result)){
						$rut_firma=$row[0];
					}
					//insertamos en dte_ceder
					$sql="insert into dte_ceder 
					(codi_empr,tipo_docu,folio_dte,estado,cesion,rut_cedente,razon_social_cedente,direccion_cedente,email_cedente,rut_cesionario,razon_social_cesionario,direccion_cesionario,email_cesionario,monto_cesion,fecha_ultimo_vencimiento,otras_condiciones,email_deudor,secuencia_cesion,email_notificacion_sii)
				 		values
					('".$nCodEmp."','".$nTipoDTE."','".$nFolio."',1,1,'".$rut_cedente."-".$dv_empresa."','".$razon_social_cedente."','".$dir_cedente."','".$correo."','".$rut."','".$razon_social."','".$direccion."','".$correo0."','".$monto_cesion."','".$fecha_vencimiento."','".$otras_condiciones."','".$correo."',1,'".$correo2."')";
					
$result=pg_query($conn,$sql);
					
					if (!$result) {
					  echo "A ocurrido un error inesperado, contactar a soporte";
					  /*
					  echo pg_last_error($conn);
					  */
					  exit;

					}

					//dte_ceder_pkey
					$sql ="SELECT currval('dte_ceder_id_seq')";
					$result=pg_query($conn,$sql);
					
					while ($row=pg_fetch_array($result)){
						$dte_ceder_id_seq=$row[0];
					}


					//insertamos en dte_ceder_rut_autorizado
					$sql ="insert into dte_ceder_rut_autorizado(rut, nombre, dte_ceder) values('".$rut_firma."','".$nombre_cedente."',".$dte_ceder_id_seq.")";
					$result=pg_query($conn,$sql);

					if (!$result) {
					  echo "A ocurrido un error inesperado, contactar a soporte";
					  /*
					  echo $sql."\n";
					  echo "An error occurred dte_ceder_rut_autorizado.\n";
					  echo pg_last_error($conn);
					  */
					  exit;
					}

					//insert into dte_ceder_rut_autorizado(rut, nombre, dte_ceder) values('9181879-5','Mario Lara Essedin',20);

					echo "<script language=\"Javascript\">alert('Cesion creada con exito...');window.close();</script>";
				}else{
					echo "<script language=\"Javascript\">alert('El documento ya presenta una cesion no lo puede volver a ceder...');window.close();</script>";
				}
			break;
		}
	}else{

		$mostrar_form = false;

		//verificamos si el cliente tiene autorizacion para ceder facturas
		$esta_autorizado = false;
		$rut_cesion_autorizado = false;
		$nombre_cesion_autorizado  = false;

		//validamos si habilitar cesion esta en si
		$sql = "select count(1) FROM config WHERE cod_config = 'HABILITAR_CESION' and codi_empr=".$nCodEmp." and valor_config='S'";
		$result = pg_query($conn,$sql);

		
		while ($row=pg_fetch_array($result)){
			$autoriza_ceder=$row[0];
		}


		if($autoriza_ceder > 0){
			$esta_autorizado = true;
		}else{
			$esta_autorizado = false;
		}

		//validamos si rut cesion existe;
		$sql = "select count(1) FROM config WHERE cod_config = 'RUT_CESION' and codi_empr=".$nCodEmp." and valor_config <> ''";

		
		$result = pg_query($conn,$sql);

		while ($row=pg_fetch_array($result)){
			$existe_ceder_rut=$row[0];
		}
		

		if($existe_ceder_rut > 0){
			$rut_cesion_autorizado = true;
		}else{
			$rut_cesion_autorizado = false;
		}

		//validamos si nombre cesion esta
		$sql = "select count(1) FROM config WHERE cod_config = 'NOMBRE_CESION' and codi_empr=".$nCodEmp." and valor_config <> '' ";
		$result = pg_query($conn,$sql);

		while ($row=pg_fetch_array($result)){
			$existe_ceder_nombre=$row[0];
		}

		if($existe_ceder_nombre > 0){
			$nombre_cesion_autorizado = true;
		}else{
			$nombre_cesion_autorizado = false;
		}
		//validamos que haya pasado todas las query anteriores

		if($esta_autorizado && $rut_cesion_autorizado && $nombre_cesion_autorizado){


			$sql="select id from dte_ceder where codi_empr='".$nCodEmp."' and tipo_docu='".$nTipoDTE."' and folio_dte='".$nFolio."'";

			$result=pg_query($conn,$sql);

			while ($row=pg_fetch_array($result)){
				$salida_dte_ceder=$row[0];
			}
			//TODO validar cuando no vengan datos
			$salida_dte_ceder= (int)$salida_dte_ceder;
			
			if ($salida_dte_ceder>0){		
			

				//si el documento ya fue cedido, validamos si esta en la tabla cesion
				$sql ="select count(1) from cesion where dte_ceder='".$salida_dte_ceder."'";

				$result = pg_query($conn,$sql);
				while ($row=pg_fetch_array($result)){
					$salida_ceder=$row[0];
				}

				$salida_ceder=(int)$salida_ceder;


				//si ceder es mayor que cero

				if ($salida_ceder > 0){
					echo "<a href='view_cesion.php?nFolioDte=".$nFolio."&nTipoDocu=".$nTipoDTE."'>Descargar XML cesi�n</a>";
					//echo "<a href=\"dte/view_xml.php?nFolioDte="+$nFolio+"&nTipoDocu="+$nTipoDTE+"\">Ver XML</a>";
				}else{
					echo "En proceso..";
				}

				//echo "<script language=\"Javascript\">alert('El documento ya presenta una cesion no lo puede volver a ceder...');window.close();</script>";
			}else{
				//habilitamos despliegue de formulario
				$mostrar_form = true;
			}
		}else{
			//si no esta autorizado, mostrar mensaje para comunicar a soporte
			echo "Comunicarse con su ejecutivo comercial";
		}

		
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="shortcut icon" href="/favicon.ico">
		<title>OpenB</title>
	<script language="JavaScript" type="text/JavaScript" src="https://portaldte.opendte.cl/javascript/funciones_jean.js"></script>
	<script language="javascript">

	function validarut2(rut2){
		var rut=trim(rut2);
		var aRut=rut.split("-");
		if (aRut.length > 2) return false;
		if (rut.length<9)return(false);
		i1=rut.indexOf("-");
		dv=rut.substr(i1+1);
		dv=dv.toUpperCase();
		nu=rut.substr(0,i1);
		cnt=0;
		suma=0;
		for (i=nu.length-1; i>=0; --i){
			dig=nu.substr(i,1);
			fc=cnt+2;
			suma+= parseInt(dig)*fc;
			cnt=(cnt+1)%6;
		}
		dvok=11-(suma%11);
		if (dvok==11) dvokstr="0";
		if (dvok==10) dvokstr="K";
		if ((dvok!=11) && (dvok!=10)) 
			dvokstr=""+dvok;
		if (dvokstr==dv)return(true);
		 else return(false);
	}
	
		function valida(){
			var form=document.formulario1;
			if(validarut2(form.rut.value)==false){
				alert("Rut ingresado no es valido");
				sele('rut');
				foco('rut');
				return false;
			}
			if(alfanumerico(form.razon_social.value,'',1)==false){
					sele('razon_social');
					foco('razon_social');
					return false;
			}
			if(alfanumerico(form.direccion.value,'',1)==false){
					sele('direccion');
					foco('direccion');
					return false;
			}
			if(numerico(form.monto_cesion.value,'',1)==false){
					sele('monto_cesion');
					foco('monto_cesion');
					return false;
			}
			//se paso a parse double por el monto alto de los documentos
			if (parseFloat(form.monto_cesion.value)>parseFloat(form.mnt.value)){
				alert("El monto de la cesion no puede ser mayor al monto del documento...");	
				sele('monto_cesion');
				foco('monto_cesion');
				return false;
			}
			if (form.fecha_vencimiento.value==""){
				alert("Seleccione fecha ultimo vencimiento...");
				return false;
			}
                        if (validateDate(form.fecha_vencimiento.value)==false){
                                alert("Formato de Fecha de vencimiento es incorrecta, debe ser a\u00F1o-mes-dia ej: 2020-05-12");
				selec('fecha_vencimiento');
				foco('fecha_vencimiento');
                                return false;
                        }

			if(alfanumerico(form.otras_condiciones.value,'',1)==false){
				sele('otras_condiciones');foco('otras_condiciones');
				return false;
			}
			if (alfanumerico(form.correo0.value,'@',1)==false){
				sele('correo0');foco('correo0');
				return false;
			}else{
				if (trim(form.correo0.value).length>0)
					{
						if (email(form.correo0.value)==false){
							sele('correo0');
							foco('correo0');
							return false;
						}
					}
				}	
			if (alfanumerico(form.correo.value,'@',1)==false){
				sele('correo');
				foco('correo');
				return false;
			}else{
				if (trim(form.correo.value).length>0){
					if (email(form.correo.value)==false){
						sele('correo');
						foco('correo');
						return false;
					}
				}
			}	
			if (alfanumerico(form.correo2.value,'@',1)==false){
				sele('correo2');
				foco('correo2');
				return false;
			}else{
				if (trim(form.correo2.value).length>0){
					if (email(form.correo2.value)==false){
						sele('correo2');
						foco('correo2');
						return false;
					}
				}
			}	
			return true;
		}
		
		function graba(){
			var form=document.formulario1;
			
			if (valida()==true){
				form.accion.value='grabar';
				form.submit();
			}
			
		}

function validateDate(dateInput) {
    var regex = /^\d{4}-\d{2}-\d{2}$/;

    if (regex.test(dateInput)) {
        // Dividir la fecha en componentes
        var parts = dateInput.split("-");
        var year = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10);
        var day = parseInt(parts[2], 10);

        // Crear una fecha con los componentes
        var date = new Date(year, month - 1, day);

        // Verificar que la fecha sea válida
        if (date.getFullYear() === year && (date.getMonth() + 1) === month && date.getDate() === day) {
            return true; // Fecha válida
        } else {
  //          alert("La fecha ingresada no es válida.");
            return false;
        }
    } else {
//        alert("El formato de la fecha es incorrecto. Debe ser yyyy-mm-dd.");
        return false;
    }
}

</script>
</head>
<body topmargin="5" leftmargin="0" rightmargin="0" bottommargin="0" bgcolor="#f3f3f3">
	<?php if ($mostrar_form){?>
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
<td width="85%"><input type="text" name="fecha_vencimiento" placeholder="AAAA-MM-DD" class="campo" size="12" maxlength="10" value="">&nbsp;
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
<?php } ?>
</body>
</html>
