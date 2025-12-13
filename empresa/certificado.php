<?php 
  include("../include/config.php"); 
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm_super.php");        
  include("../include/ver_emp_adm.php");         
  include("../include/tables.php");  
  include("../include/db_lib.php"); 

  $conn = conn();
  include("../include/rubros.php"); 

  $nCodEmp = trim($_SESSION["_COD_EMP_USU_SESS"]);
  $sAccion = "M";
  $sMsgJs = trim($_GET["sMsgJs"]);  

  $sql = "SELECT 
  			C.path_certificado, 
  			C.clave_certificado ,
  			E.path_licencia, E.propiedades
  		FROM 
  			certificado C,
  			empresa E
  		WHERE 
  			C.rut_empresa = E.rut_empr AND
  			C.rut_empresa = '" . str_replace("'","''",$sRutEmp) . "'";
  $result = rCursor($conn, $sql);

  if(!$result->EOF) {
  	$sPathCert = trim($result->fields["path_certificado"]);
    $sClaveCert = trim($result->fields["clave_certificado"]);
    $sPathLicencia = trim($result->fields["path_licencia"]);
    $sPropiedades = trim($result->fields["propiedades"]);
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
/*	var rutaux = Trim(F.sRutEmp.value) + "-" + Trim(F.sDvEmp.value);
  
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
	*/
	F.submit();
  }
  

//-->
		</script>
	</head>

<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	
  <form name="_FFORM" enctype="multipart/form-data" action="empresa/pro_cert.php" method="post" onSubmit="return valida();">
    <input type="hidden" name="nCodEmp" value="<?php echo $nCodEmp; ?>">
    <input type="hidden" name="sAccion" value="<?php echo $sAccion; ?>">
     <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $_MAX_FILE_CERT; ?>">
 
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td id="screenWH">
	<div class="pathbar"><a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>../main.php';">Home</a> &gt;</div>

  <div class="screenTitle">
		<table width="100%" cellspacing="0">
		<tr>
			<td>Editar informaci&oacute;n de empresa <?php echo $_SESSION["_NOM_EMP_USU_SESS"]; ?>:</td>
			<td class="uplevel"><a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>../main.php';"><div class="commonButton" id="bid-up-level" title="Subir nivel"><button name="bname_up_level" >Subir nivel</button><span>Subir nivel</span></div></a></td>
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
