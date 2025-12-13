<?php
	 include("../include/config.php");  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Tipo de DTE y Motivo - <?php echo $_GET['s']; ?></TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
  <base href="<?php echo $_LINK_BASE; ?>" />

  <script language="javascript" type="text/javascript" src="javascript/funciones.js"></script>
  <script language="javascript" type="text/javascript" src="javascript/common.js"></script>
  <script language="javascript" type="text/javascript" src="javascript/msg.js"></script>  
  <link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/custom.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/layout.css">
  <link rel="stylesheet" type="text/nonsense" href="skins/<?php echo $_SKINS; ?>/css/misc.css">

<SCRIPT LANGUAGE="JavaScript">
<!--

	function enableDisable(obj,estado){
	      obj.disabled = estado; 
	}

	function checkea(obj){
		if(obj.checked == true){
			enableDisable(document._FFORM.sRecinto,false);
			enableDisable(document._FFORM.sFirma,false);
		}
		else{
			enableDisable(document._FFORM.sRecinto,true);
			enableDisable(document._FFORM.sFirma,true);
		}
	}

	function chkRespuesta(obj){
		if(obj.checked == true){
			enableDisable(document._FFORM.sRespuesta,false);
			enableDisable(document._FFORM.sGlosa,false);
		}
		else{
			enableDisable(document._FFORM.sRespuesta,true);
			enableDisable(document._FFORM.sGlosa,true);
		}
	}



	function validaAcuse(){
		var F = document._FFORM;

		if(vacio(F.sRecinto.value,_MSG_ING_RECINTO) == false){
			F.sRecinto.focus();
			return false;
		}

		if(rut(F.sFirma.value,_MSG_FIRMA_RECINTO) == false){
			F.sFirma.focus();
			return false;
		}

		if(confirm(_MSG_ACEPTA_ACUSE_RECIBO))
			return true;
		else
			return false;
	}

	function valida(){
		var F = document._FFORM;
	//		if(F.sTipFac.options[F.sTipFac.selectedIndex].value == "IVANORECUPER" && F.nMotIvaNoRec.value == "")
	//			alert("Seleccione el motivo de no recuperar el iva.")
	//		else{
				if(confirm(_MSG_ACEPTA_DTE)){
					entra = true;
<?php
if ($_GET['s']=='ACEPTADO'){
?>
					if(F.nGeneraAcuse.checked == true){	// genera acuse
						entra = validaAcuse();			// valida datos del acuse
						opener.document._FENV.sRecinto.value = F.sRecinto.value;
						opener.document._FENV.sFirma.value = F.sFirma.value;
						opener.document._FENV.nGeneraAcuse.value = 1;
					}
					else{
						opener.document._FENV.sRecinto.value = "";
						opener.document._FENV.sFirma.value = "";
						opener.document._FENV.nGeneraAcuse.value = "";					
					}
<?php
}
?>

					if(F.nAprobacion.checked == true){	
						if (F.sRespuesta.value == 1 || F.sRespuesta.value ==2){
							if (F.sGlosa.value==''){
								alert('Ingrese Motivo');
								F.sGlosa.focus();
								return;
							}
						}
						opener.document._FENV.sRespuesta.value = F.sRespuesta.value;
						opener.document._FENV.sGlosa.value = F.sGlosa.value;
						opener.document._FENV.nAprobacion.value = 1;
					}
					else{
						opener.document._FENV.sRespuesta.value = "";
						opener.document._FENV.sGlosa.value = "";
						opener.document._FENV.nAprobacion.value = "";
					}

					if(entra == true){
//						opener.document._FENV.sTipFac.value = F.sTipFac.options[F.sTipFac.selectedIndex].value;
//						if(F.sTipFac.options[F.sTipFac.selectedIndex].value == "IVANORECUPER")
//							opener.document._FENV.nMotIvaNoRec.value = F.nMotIvaNoRec.options[F.nMotIvaNoRec.selectedIndex].value;
//						else
//							opener.document._FENV.nMotIvaNoRec.value = "";

						opener.document._FENV.submit();
						window.close();
					}
				}
		//	}
		}

	function cerrar(){
//		opener.objTmpSelect.options[0].selected = true;
	}

	function cargar(){
		document._FFORM.nGeneraAcuse.checked = false;
		enableDisable(document._FFORM.sRecinto,true);
		enableDisable(document._FFORM.sFirma,true);
		enableDisable(document._FFORM.sRespuesta,true);
		enableDisable(document._FFORM.sGlosa,true);
	}
//-->
</SCRIPT>
</HEAD>

<BODY id="mainCP" onLoad="cargar();" onUnLoad="cerrar();" class="visibilityAdminMode">
  <div class="screenBody" id="">
	<div class="formArea">
		<fieldset>
			<legend>Categorizar DTE</legend>

  <FORM name="_FFORM" METHOD=POST ACTION="">
	<TABLE >


<?php
if ($_GET['s']=='ACEPTADO'){
?>
		<tr>
			<td colspan="2">
				<INPUT TYPE="checkbox" NAME="nGeneraAcuse" value="1" onClick="checkea(this);" >Genera Acuse de recibo de Mercader&iacute;a.
			</td>
		</TR>

		<tr>
			<td width="33%" nowrap> Recinto 
			</td>
			<td>
				<INPUT TYPE="text" NAME="sRecinto" maxlength="80" size="25">
			</td>
		</TR>			

		<tr>
			<td width="33%"> Rut Firma Recepci&oacute;n Mercader&iacute;a
			</td>
			<td>
				<INPUT TYPE="text" NAME="sFirma" maxlength="10" size="10">Ej: 11111111-1
			</td>
		</TR>			
		<tr>
			<td colspan="2">
			</td>
		</TR>			
<?php
}
?>

		<tr>
			<td colspan="2">
				<INPUT TYPE="checkbox" NAME="nAprobacion" value="1" onClick="chkRespuesta(this);" >Genera Respuesta Comercial
			</td>
		</TR>

		<TR>
			<td width="33%"><label for="fid-cname">Estado</label></td>
			<td width="33%" nowrap> 
				<SELECT NAME="sRespuesta">
<?php
if ($_GET['s']=='ACEPTADO'){
?>
					<option value="0">DTE ACEPTADO OK</option>
					<option value="1">DTE ACEPTADO con Discrepancia</option>
<?php
}
else {
?>

					<option value="2">DTE Rechazado</option>
<?php
}
?>
				</SELECT>
			</td>
		</TR>
		<tr>
			<td width="33%" nowrap> Glosa rechazo o Discrepancia
			</td>
			<td>
				<INPUT TYPE="text" NAME="sGlosa" maxlength="80" size="25">
			</td>
		</TR>			

		<Tr><td colspan=2 align="center"><br><INPUT TYPE="button" value="Enviar" onclick="valida();"></td></tr>
	</TABLE>
  </FORM>
  </fieldset>
	</div>
	</div>

</BODY>
</HTML>
