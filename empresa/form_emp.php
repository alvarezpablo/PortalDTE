<?php 
  include("../include/config.php"); 
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");         
  include("../include/tables.php");  
  include("../include/db_lib.php"); 

  $conn = conn();
  include("../include/rubros.php"); 

  $nCodEmp = trim($_GET["nCodEmp"]);
  $sRutEmp = trim($_GET["sRutEmp"]);
  $sDvEmp = trim($_GET["sDvEmp"]);  
  $sRzSclEmp = trim($_GET["sRzSclEmp"]);
  $sDirEmp = trim($_GET["sDirEmp"]);
  $sAccion = trim($_GET["sAccion"]);
  $sMsgJs = trim($_GET["sMsgJs"]);  

  $nCodAct = trim($_GET["nCodAct"]);
  $sGiroEmp = trim($_GET["sGiroEmp"]);  
  $sComEmp = trim($_GET["sComEmp"]);  
  $dFecRes = trim($_GET["dFecRes"]);  
  $nResSii = trim($_GET["nResSii"]);  

  $sql = "SELECT 
  			C.path_certificado, 
  			C.clave_certificado ,
  			E.path_licencia, E.propiedades, E.emite_web as emite_web
  		FROM 
  			certificado C,
  			empresa E
  		WHERE 
  			cast(C.rut_empresa as varchar) = cast(E.rut_empr as varchar) AND
  			C.rut_empresa = '" . str_replace("'","''",$sRutEmp) . "'";
  $result = rCursor($conn, $sql);

  if(!$result->EOF) {

  	$sPathCert = trim($result->fields["path_certificado"]);
    $sClaveCert = trim($result->fields["clave_certificado"]);
    $sPathLicencia = trim($result->fields["path_licencia"]);
    $sPropiedades = trim($result->fields["propiedades"]);
    $nEmiteWeb = trim($result->fields["emite_web"]);

  }

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>OpenB</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<base href="<?php echo $_LINK_BASE; ?>" />
		<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/msg.js"></script>    
		<script language="javascript" type="text/javascript" src="javascript/funciones.js"></script>        
		
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/custom.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/layout.css">
		<link rel="stylesheet" type="text/nonsense" href="skins/<?php echo $_SKINS; ?>/css/misc.css">

		  <!-- calendar  -->
		  <link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
		  <script type="text/javascript" src="javascript/calendar.js"></script>
		  <script type="text/javascript" src="javascript/lang/calendar-es.js"></script>
		  <script type="text/javascript" src="javascript/calendar-setup.js"></script>
		  <!-- calendar fin -->

<script type="text/javascript">
<!--

  
  function _body_onload()
  {
    loff();
 <?php 
  if($sMsgJs != "")
    echo "alert(" . $sMsgJs . ");\n";
 
 ?>      
     SetContext('cl_ed');
  }
  
  function _body_onunload()
  {
    lon();
    
  }

  function valida(){
	var F = document._FFORM;
	var rutaux = Trim(F.sRutEmp.value) + "-" + Trim(F.sDvEmp.value);
  
	if(rut(rutaux,_MSG_RUT) == false){  
		F.sRutEmp.select();
		return false;  
	}



  if(vacio(F.sRzSclEmp.value,_MSG_RAZON_SOCIAL) == false){
    F.sRzSclEmp.select();
    return false;
  }

  if(vacio(F.sDirEmp.value,_MSG_DIR) == false){
    F.sDirEmp.select();
    return false;
  }

  if(vacio(F.sComEmp.value,_MSG_COMUNA) == false){
    F.sComEmp.select();
    return false;
  }

  if(vacio(F.sGiroEmp.value,_MSG_GIRO_EMP) == false){
    F.sGiroEmp.select();
    return false;
  }

  if(F.nCodAct.options[F.nCodAct.selectedIndex].value == ""){
	alert(_MSG_ACT_EMP);
	F.nCodAct.focus();
	return false;
  }

  if(F.dFecRes.value == ""){
	alert(_MSG_FECRESOL_EMP);
	F.dFecRes.focus();
	return false;
  }

  if(vacio(F.nResSii.value,_MSG_NUMRESOL_EMP) == false){
	F.nResSii.focus();
	return false;
  }
	
	F.submit();
  }
  

//-->
		</script>
	</head>

<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	
  <form name="_FFORM" enctype="multipart/form-data" action="empresa/pro_emp.php" method="post" onSubmit="return valida();">
    <input type="hidden" name="nCodEmp" value="<?php echo $nCodEmp; ?>">
    <input type="hidden" name="sAccion" value="<?php echo $sAccion; ?>">
     <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $_MAX_FILE_CERT; ?>">
 
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td id="screenWH">
	<div class="pathbar"><a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>empresa/listempre.php';">Empresas</a> &gt;</div>

  <div class="screenTitle">
		<table width="100%" cellspacing="0">
		<tr>
			<td>Editar informaci&oacute;n de empresa <?php echo $sRutEmp . "-" . $sDvEmp; ?>:</td>
			<td class="uplevel"><a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>empresa/listempre.php';"><div class="commonButton" id="bid-up-level" title="Subir nivel"><button name="bname_up_level" >Subir nivel</button><span>Subir nivel</span></div></a></td>
		</tr>
		</table>
	</div>
   
  <div id="screenSubTitle"></div>
	<div id="screenTabs">
		<div id="tabs">
			
		</div>

	</div>
	<div class="screenBody" id="">
		
	<div class="formArea">
		<fieldset>

<legend>Formulario de Empresa </legend>
<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td>

<table class="formFields" cellspacing="0" width="100%">
	<tr>
		<td class="name"><label for="fid-cname">Rut de Empresa:</label>&nbsp;*</td>
		<td><input type="text" name="sRutEmp" value="<?php echo $sRutEmp; ?>" size="10" maxlength="8"><input type="text" name="sDvEmp" value="<?php echo $sDvEmp; ?>" size="2" maxlength="1"></td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">Razon Social:</label>&nbsp;*</td>
		<td><input type="text" name="sRzSclEmp" value="<?php echo $sRzSclEmp; ?>" size="25" maxlength="100"></td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">
        <?php 
            if($sPathCert == "")
              echo "Certificado Digital:";
            else
              echo "Certificado Digital (<font color=red>$sPathCert</font>):";
        ?>
         
          </label>&nbsp;* </td>
		<td><input type="file" name="sPathCert" id="fid-cname" value="<?php echo $sPathCert; ?>" size="25"> </td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">Clave Certificado:</label>&nbsp;* </td>
		<td><input type="password" name="sClaveCert" id="fid-cname" value="" size="25" maxlength="20"> (Ingrese solo si desea cambiarla)</td>
	</tr>
	
	<tr>
		<td class="name"><label for="fid-cname">
        <?php 
            if($sPathCert == "")
              echo "Licencia OpenB:";
            else
              echo "Licencia OpenB (<font color=red>$sPathLicencia</font>):";
        ?>
         
          </label>&nbsp;* </td>
		<td><input type="file" name="sPathLicencia" id="fid-cname" value=""> </td>
	</tr>	
	
	<tr>
		<td class="name"><label for="fid-cname">Direcci&oacute;n:</label>&nbsp;*</td>
		<td><input type="text" name="sDirEmp" value="<?php echo $sDirEmp; ?>" size="25" maxlength="60"></td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">Comuna:</label>&nbsp;*</td>
		<td><input type="text" name="sComEmp" value="<?php echo $sComEmp; ?>" size="25" maxlength="20"></td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">Giro:</label>&nbsp;*</td>
		<td><input type="text" name="sGiroEmp" value="<?php echo $sGiroEmp; ?>" size="25" maxlength="80"></td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">Emite DTE WEB</label>&nbsp;*</td>
		<td>
			<select name="nEmiteWeb">
			<?php 
				if($nEmiteWeb == "")
					echo "<option value='' selected>No Autorizado</option><option value='1'>Autorizado</option>";
				else 
					echo "<option value=''>No Autorizado</option><option value='1' selected>Autorizado</option>";
			?>
				
			</select>
		</td>
	</tr>


	<tr>
		<td class="name"><label for="fid-cname">Actividad Econ&oacute;mica:</label>&nbsp;*</td>
		<td>
			  <select name="nCodAct" style="this.style.textTransform='capitalize'">		
				<SCRIPT LANGUAGE="JavaScript">
				<!--
					llenaRubros("<?php echo $nCodAct; ?>");
				//-->
				</SCRIPT>		
			  </select>
		</td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">Fecha Resoluci&oacute;n:</label>&nbsp;*</td>
		<td><input type="text" name="dFecRes" id="f_date_ter" onFocus="this.blur();" value="<?php echo $dFecRes; ?>" size="15" maxlength="10">
<img src="img.gif" id="f_trigger_ter" style="cursor: pointer; border: 1px solid red;" title="Date selector"
      onmouseover="this.style.background='red';" onmouseout="this.style.background=''" / >
		
	<script type="text/javascript">
		Calendar.setup({
			inputField     :    "f_date_ter",     // id of the input field
			ifFormat       :    _FORMAT_FECHA_FORM,      // format of the input field
			button         :    "f_trigger_ter",  // trigger for the calendar (button ID)
			align          :    "Tl",           // alignment (defaults to "Bl")
			singleClick    :    true
		});
	</script>
		
		</td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">N� Resoluci&oacute;n:</label>&nbsp;*</td>
		<td><input type="text" name="nResSii" value="<?php echo $nResSii; ?>" size="25" maxlength="20"></td>
	</tr>
<!--
	<tr>
		<td class="name"><label for="fid-cname">Propiedades:</label>&nbsp;*</td>
		<td
			<textarea name=sPropiedades cols=20 rows=20><?php echo $sPropiedades; ?></textarea>
		</td>
	</tr>
-->
</table>

<input type="hidden" name="start" value="">

</td></tr></table></fieldset>

	</div>
	
	<div class="formArea">
		<table width="50%" class="buttons" cellspacing="0" cellpadding="0"><tr>
			<td class="footnote" align="left"><span class="required">*</span> Campos requeridos.</td>
			<td class="misc" width="0" align="left">
				<div class="commonButton" id="bid-ok" title="Aceptar" onMouseOver="" onMouseOut=""><button name="bname_ok" onClick="return valida();" >Aceptar</button><span>Aceptar</span></div>
				<a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>empresa/listempre.php';">
        <div class="commonButton" id="bid-cancel" title="Cancelar" onMouseOver="" onMouseOut=""><button name="bname_cancel">Cancelar</button><span>Cancelar</span></div></a>
			</td>
		</tr></table>

		<input type="hidden" name="cmd" value="update">
		<input type="hidden" name="lock" value="false">
		<input type="hidden" name="previous_page" value="cl_ed">

	</div>

</form>

	</div>
	</td></tr></table>
  </form>
	</body>

	<script type="text/javascript">
		try {
			lsetup();
		} catch (e) {
		}
	</script>
</html>
