<?php 
	include("../include/config.php");  
  include("../include/ver_aut.php");      
	include("../include/db_lib.php"); 
	include("../include/tables.php"); 
  
  $nRutCli = trim($_GET["nRutCli"]);
  $nRutCliNew = trim($_GET["nRutCliNew"]);  
  $sRazClie = trim($_GET["sRazClie"]);
  $sDvClie = trim($_GET["sDvClie"]);       
  $sDvClieNew = trim($_GET["sDvClieNew"]);         
  $sEmiElecClie = trim($_GET["sEmiElecClie"]);
  $sRecElecClie = trim($_GET["sRecElecClie"]);               
  $sEnvEmail = trim($_GET["sEnvEmail"]);                           
  $sRazEmp = trim($_GET["sRazEmp"]);                                
  $sCodEmp = trim($_GET["sCodEmp"]);                                  
  $sCodEmpNew = trim($_GET["sCodEmpNew"]);                                    
  
  $sFono = trim($_GET["sFono"]);            
  $sGuiaClie = trim($_GET["sGuiaClie"]);
  $sCiudClie = trim($_GET["sCiudClie"]);
  $sGiroClie = trim($_GET["sGiroClie"]);
  $sComClie = trim($_GET["sComClie"]);
  $sDirClie = trim($_GET["sDirClie"]);  
  
  $sNomTec = trim($_GET["sNomTec"]);  
  $sFonoTec = trim($_GET["sFonoTec"]);  
  $sEmailTec = trim($_GET["sEmailTec"]);  
  $sNomAdm = trim($_GET["sNomAdm"]);  
  $sFonoAdm = trim($_GET["sFonoAdm"]);  
  $sEmailAdm = trim($_GET["sEmailAdm"]);      
    
  $sAccion = trim($_GET["sAccion"]); 
  $sMsgJs = trim($_GET["sMsgJs"]);    
  $conn = conn();
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
  var rutaux = Trim(F.nRutCliNew.value) + "-" + Trim(F.sDvClieNew.value);
  
  if(rut(rutaux,_MSG_RUT) == false){  
    F.nRutCliNew.select();
    return false;  
  }
  
  if(vacio(F.sRazClie.value,_MSG_RAZON_SOCIAL) == false){
    F.sRazClie.select();
    return false;
  }

  if(vacio(F.sDirClie.value,_MSG_DIR) == false){
    F.sDirClie.select();
    return false;
  }
  
  if(vacio(F.sComClie.value,_MSG_COMUNA) == false){
    F.sComClie.select();
    return false;
  }
  
  if(vacio(F.sGiroClie.value,_MSG_GIRO) == false){
    F.sGiroClie.select();
    return false;
  }
  
  if(vacio(F.sCiudClie.value,_MSG_CIUDAD) == false){
    F.sCiudClie.select();
    return false;
  }
  
  if(vacio(F.sFono.value,_MSG_FONO) == false){
    F.sFono.select();
    return false;
  }        

  if(Trim(F.sEnvEmail.value) != ""){
    if(email(F.sEnvEmail.value, _MSG_EMAIL) == false){
      F.sEnvEmail.select();
      return false;
    } 
  }    

  if(vacio_sm(F.sNomTec.value) == false && vacio_sm(F.sNomAdm.value) == false){
    alert(_MSG_TEC_ADM);
    return false;
  }     
 
  if(Trim(F.sEmailTec.value) != ""){
    if(email(F.sEmailTec.value, _MSG_EMAIL) == false){
      F.sEmailTec.select();
      return false;
    } 
  }       
  
  if(Trim(F.sEmailAdm.value) != ""){
    if(email(F.sEmailAdm.value, _MSG_EMAIL) == false){
      F.sEmailAdm.select();
      return false;
    } 
  }       
  
  F.submit();
}

//-->
		</script>
	</head>

	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td id="screenWH">
	<div class="pathbar"><a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>mantencion/list_clie.php';">Clientes</a> &gt;</div>
	<div class="screenTitle">
		<table width="100%" cellspacing="0">
		<tr>
			<td>Editar informaci&oacute;n de cliente <?php echo $nRutCli . "-" . $sDvClie ?>:</td>
			<td class="uplevel"><div class="commonButton" id="bid-up-level" title="Subir nivel" onClick="location.href='<?php echo $_LINK_BASE; ?>mantencion/list_clie.php';"><button name="bname_up_level" onClick="location.href='<?php echo $_LINK_BASE; ?>mantencion/list_clie.php';">Subir nivel</button><span>Subir nivel</span></div></td>
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

<legend>Formulario de Cliente </legend>
<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td>

<form name="_FFORM" action="mantencion/pro_clie.php" method="post" onSubmit="return valida();">
  <input type="hidden" name="nRutCli" value="<?php echo $nRutCli; ?>">
  <input type="hidden" name="sDvClie" value="<?php echo $sDvClie; ?>">  
  <input type="hidden" name="sAccion" value="<?php echo $sAccion; ?>">
  <input type="hidden" name="sCodEmp" value="<?php echo $sCodEmp; ?>">
  

<table class="formFields" cellspacing="0" width="100%">
	<tr>
		<td class="name"><label for="fid-cname">Rut de Cliente:</label>&nbsp;*</td>
		<td><input type="text" name="nRutCliNew" id="fid-cname" value="<?php echo $nRutCliNew; ?>" size="10" maxlength="10"><input type="text" name="sDvClieNew" id="fid-cname" value="<?php echo $sDvClieNew; ?>" size="2" maxlength="1"></td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">Raz&oacute;n Social:</label>&nbsp;*</td>
		<td><input type="text" name="sRazClie" id="fid-cname" value="<?php echo $sRazClie; ?>" size="50" maxlength="80"></td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">Direcci&oacute;n:</label>&nbsp;*</td>
		<td><input type="text" name="sDirClie" id="fid-cname" value="<?php echo $sDirClie; ?>" size="40" maxlength="60"></td>
	</tr>
  
	<tr>
		<td class="name"><label for="fid-cname">Comuna:</label>&nbsp;*</td>
		<td><input type="text" name="sComClie" id="fid-cname" value="<?php echo $sComClie; ?>" size="40" maxlength="60"></td>
	</tr>
  
	<tr>
		<td class="name"><label for="fid-cname">Giro:</label>&nbsp;*</td>
		<td><input type="text" name="sGiroClie" id="fid-cname" value="<?php echo $sGiroClie; ?>" size="40" maxlength="60"></td>
	</tr>      
  
	<tr>
		<td class="name"><label for="fid-cname">Ciudad:</label>&nbsp;*</td>
		<td><input type="text" name="sCiudClie" id="fid-cname" value="<?php echo $sCiudClie; ?>" size="40" maxlength="60"></td>
	</tr>  
  
	<tr>
		<td class="name"><label for="fid-cname">Fono:</label>&nbsp;*</td>
		<td><input type="text" name="sFono" id="fid-cname" value="<?php echo $sFono; ?>" size="20" maxlength="20"></td>
	</tr>    
<!--    
	<tr>
		<td class="name"><label for="fid-cname2">Emisor Electr&oacute;nico:</label>&nbsp;*</td>
		<td>
    <?php       
/*      if($sEmiElecClie == "S")
        $chSi = "checked";
      else
        $chNo = "checked"; */
    ?>
			<input type="radio" name="sEmiElecClie" id="fid-cname2" value="S" <?php echo  $chSi; ?>> Si
			<input type="radio" name="sEmiElecClie" id="fid-cname2" value="N" <?php echo  $chNo; ?>> no
		</td>
	</tr>
-->
	<tr>
		<td class="name"><label for="fid-cname3">Acepta Recepci&oacute;n de Email:</label>&nbsp;*</td>
		<td>
    <?php       
      if($sRecElecClie == "S")
        $chSis = "checked";
      else
        $chNos = "checked";

    ?>
			<input type="radio" name="sRecElecClie" id="fid-cname3" value="S" <?php echo $chSis; ?>> Si
			<input type="radio" name="sRecElecClie" id="fid-cname3" value="N" <?php echo $chNos; ?>> no
		</td>
	</tr>

  <tr>
		<td class="name"><label for="fid-cname">Empresa:</label>&nbsp;*</td>
		<td>
        <select name="sCodEmpNew">
<?php 
        $sql = "SELECT codi_empr, rs_empr FROM empresa ";

if(trim($_SESSION["_COD_ROL_SESS"]) != "1")
		$sql .= "  WHERE codi_empr IN(SELECT codi_empr FROM empr_usu WHERE cod_usu = '" . str_replace("'","''",$_SESSION["_COD_USU_SESS"]) . "')    ";    

		$sql .= " ORDER BY rs_empr";

        $result = rCursor($conn, $sql);
                
        while (!$result->EOF) {
          $sCodEmpt = trim($result->fields["codi_empr"]);
          $sRsEmpt = trim($result->fields["rs_empr"]);          
          
          if($sCodEmpt == $sCodEmpNew)
            echo "<option value='" . $sCodEmpt . "' selected>" . $sRsEmpt . "</option>";
          else
            echo "<option value='" . $sCodEmpt . "'>" . $sRsEmpt . "</option>";
          
          $result->MoveNext();
        } 
?>        
        </select>
    </td>
	</tr>  
  
  <tr>
		<td class="name"><label for="fid-cname">Email Envio:</label>&nbsp;</td>
		<td><input type="text" name="sEnvEmail" id="fid-cname" value="<?php echo $sEnvEmail; ?>" size="25" maxlength="100"></td>
	</tr>
  
  <tr>
		<td colspan="2"><b><label for="fid-cname" align="left">Contacto Técnico</label>&nbsp;</b></td>
	</tr>  
  
  
	<tr>
		<td class="name"><label for="fid-cname">Nombre:</label>&nbsp;***</td>
		<td><input type="text" name="sNomTec" id="fid-cname" value="<?php echo $sNomTec; ?>" size="40" maxlength="100"></td>
	</tr>    

	<tr>
		<td class="name"><label for="fid-cname">Fono:</label>&nbsp;</td>
		<td><input type="text" name="sFonoTec" id="fid-cname" value="<?php echo $sFonoTec; ?>" size="20" maxlength="20"></td>
	</tr>    

	<tr>
		<td class="name"><label for="fid-cname">Email:</label>&nbsp;</td>
		<td><input type="text" name="sEmailTec" id="fid-cname" value="<?php echo $sEmailTec; ?>" size="40" maxlength="100"></td>
	</tr>    

  <tr>
		<td colspan="2"><b><label for="fid-cname" align="left">Contacto Administrativo</label>&nbsp;</b></td>
	</tr>  
  
  
	<tr>
		<td class="name"><label for="fid-cname">Nombre:</label>&nbsp;***</td>
		<td><input type="text" name="sNomAdm" id="fid-cname" value="<?php echo $sNomAdm; ?>" size="40" maxlength="100"></td>
	</tr>    
  
	<tr>
		<td class="name"><label for="fid-cname">Fono:</label>&nbsp;</td>
		<td><input type="text" name="sFonoAdm" id="fid-cname" value="<?php echo $sFonoAdm; ?>" size="20" maxlength="20"></td>
	</tr>    

	<tr>
		<td class="name"><label for="fid-cname">Email:</label>&nbsp;</td>
		<td><input type="text" name="sEmailAdm" id="fid-cname" value="<?php echo $sEmailAdm; ?>" size="40" maxlength="100"></td>
	</tr>    
  
  <tr>
		<td colspan="2"><label for="fid-cname" align="left">*** Contacto Técnico y/o Administrativo</label>&nbsp;</td>
	</tr>    

</table>


</td></tr></table></fieldset>

	</div>
	
	<div class="formArea">
		<table width="100%" class="buttons" cellspacing="0" cellpadding="0"><tr>
			<td class="main" width="0"></td>
			<td class="footnote"><span class="required">*</span> Campos requeridos.</td>
			<td class="misc" width="0">
				<div class="commonButton" id="bid-ok" title="Aceptar" onClick="return valida();" onMouseOver="" onMouseOut="">
          <button name="bname_ok">  
             Aceptar 
          </button>
          <span>Aceptar</span></div>
				<a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>mantencion/list_clie.php';">
        <div class="commonButton" id="bid-cancel" title="Cancelar" onClick="location.href='<?php echo $_LINK_BASE; ?>mantencion/list_clie.php';" onMouseOver="" onMouseOut="">
          <button name="bname_cancel">Cancelar</button>
          <span>Cancelar</span>
        </div></a>
			</td>
		</tr></table>

	</div>

</form>

	</div>
	</td></tr></table>
	</body>

	<script type="text/javascript">
		try {
			lsetup();
		} catch (e) {
		}
	</script>
</html>