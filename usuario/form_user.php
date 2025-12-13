<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");        
  include("../include/db_lib.php"); 
  include("../include/tables.php");  

  $nCodUsu = trim($_GET["nCodUsu"]);
  $sIdUsu = trim($_GET["sIdUsu"]);
  $sIdUsuNew = trim($_GET["sIdUsuNew"]);  
  $sPathCert = trim($_GET["sPathCert"]);  
  $sEstUsu = trim($_GET["sEstUsu"]);
  $sCodRolUsu = trim($_GET["sCodRolUsu"]);
  $sDescRol = trim($_GET["sDescRol"]);
  $sMsgJs = trim($_GET["sMsgJs"]);  
  $sAccion = trim($_GET["sAccion"]);   
  $sMsgJs2 = trim($_GET["sMsgJs2"]);    
  $conn = conn();       // conecion a base de datos
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
 
  if($sMsgJs2 != "")
    echo "alert('" . $sMsgJs2 . "');\n";
 
 ?>    
  
	SetContext('cl_ed');
		
}

function _body_onunload()
{
	lon();
	
}

    function chListBoxSearch(sCodRolUsu){
      var F = document._FSEARCH;
      for(i=0; i < F._COLUM_SEARCH.length; i++){
        if(F._COLUM_SEARCH.options[i].value == sCodRolUsu)
          F._COLUM_SEARCH.options[i].selected = true;
      }
    }

	function valida(){
	  var F = document._FFORM;
	  
	  if(vacio(F.sIdUsuNew.value,_MSG_USU) == false){
		F.sIdUsuNew.select();
		return false;
	  }

	  if(F.sAccion.value == "I"){
		  if(vacio(F.sClaveUsu.value,_MSG_CLAVE_USU) == false){
			F.sClaveUsu.select();
			return false;
		  }
/*
		  if(vacio(F.sClaveCert.value,_MSG_CLAVE_CERT) == false){
			F.sClaveCert.select();
			return false;
		  }	*/
	  }
	  F.submit();
	}
//-->
		</script>
	</head>

	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	
  <form name="_FFORM" enctype="multipart/form-data" action="usuario/pro_usu.php" method="post" onSubmit="return valida();">
    <input type="hidden" name="nCodUsu" value="<?php echo $nCodUsu; ?>">
    <input type="hidden" name="sIdUsu" value="<?php echo $sIdUsu; ?>">  
    <input type="hidden" name="sAccion" value="<?php echo $sAccion; ?>">      
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $_MAX_FILE_CERT; ?>">
      
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td id="screenWH">
	<div class="pathbar"><a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>usuario/list_user.php';">Usuarios</a> &gt;</div>
	<div class="screenTitle">
		<table width="100%" cellspacing="0">
		<tr>
			<td>Editar informaci&oacute;n de usuario <?php echo $sIdUsu; ?>:</td>
			<td class="uplevel"><a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>usuario/list_user.php';"><div class="commonButton" id="bid-up-level" title="Subir nivel"><button name="bname_up_level">Subir nivel</button><span>Subir nivel</span></div></a></td>
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

<legend>Formulario de Usuarios </legend>
<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td>



<table class="formFields" cellspacing="0" width="100%">
	<tr>
		<td class="name"><label for="fid-cname">Identificaci&oacute;n de Usuario:</label>&nbsp;*</td>
		<td><input type="text" name="sIdUsuNew" id="fid-cname" value="<?php echo $sIdUsuNew; ?>" size="25" maxlength="100"></td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">Clave:</label>&nbsp;*</td>
		<td><input type="password" name="sClaveUsu" id="fid-cname" value="" size="25" maxlength="20"> (Ingrese solo si desea cambiarla)</td>
	</tr>
<!--
	<tr>
		<td class="name"><label for="fid-cname">
        <?php 
            if($sPathCert == "")
              echo "Certificado Digital:";
            else
              echo "<a href='javascript:void(0);' onclick='javascript:window.open(\"" . $_PATH_VITUAL_CERT_DIGITAL . $sPathCert . "\");' >Certificado Digital:</a>";
        ?>
        
        
          
          </label>&nbsp;* </td>
		<td><input type="file" name="sPathCert" id="fid-cname" value="<?php echo $sPathCert; ?>" size="25"> </td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">Clave Certificado:</label>&nbsp;* </td>
		<td><input type="password" name="sClaveCert" id="fid-cname" value="" size="25" maxlength="20"> (Ingrese solo si desea cambiarla)</td>
	</tr>
 -->
	<tr>
		<td class="name"><label for="fid-cname">Estado:</label>&nbsp;*</td>
		<td>
    <?php 
      if($sEstUsu == "0"){
    ?>
        <input type="radio" name="sEstUsu" id="fid-cname" value="1"> Activo
        <input type="radio" name="sEstUsu" id="fid-cname" value="0" checked> Desactivo
    <?php
     }
     else{     
     ?>
        <input type="radio" name="sEstUsu" id="fid-cname" value="1" checked> Activo
        <input type="radio" name="sEstUsu" id="fid-cname" value="0"> Desactivo
     <?php
     } 
     ?>
		</td>
	</tr>

	<tr>
		<td class="name"><label for="fid-cname">Rol:</label>&nbsp;*</td>
		<td>
			<SELECT NAME="sCodRolUsu">
<?php 
        $sql = "SELECT cod_rol, desc_rol FROM rol ORDER BY desc_rol";
        $result = rCursor($conn, $sql);
        
        while (!$result->EOF) {
          $nCodRolT = trim($result->fields["cod_rol"]);
          $sDescRolT = trim($result->fields["desc_rol"]);
          
          if($nCodRolT == $sCodRolUsu)
            echo "<option value='" . $nCodRolT . "' selected>" . $sDescRolT . "</option> \n";
          else
            echo "<option value='" . $nCodRolT . "'>" . $sDescRolT . "</option> \n";
          
          $result->MoveNext();
        }

?>      
			</SELECT>
      
		</td>
	</tr>


</table>

<input type="hidden" name="start" value="">

</td></tr></table></fieldset>

	</div>
	
	<div class="formArea">
		<table width="100%" class="buttons" cellspacing="0" cellpadding="0"><tr>
			<td class="main" width="0"></td>
			<td class="footnote"><span class="required">*</span> Campos requeridos.</td>
			<td class="misc" width="0">
				<div class="commonButton" id="bid-ok" title="Aceptar" onMouseOver="" onMouseOut=""><button name="bname_ok" onClick="return valida();">Aceptar</button><span>Aceptar</span></div>
<a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>usuario/list_user.php';">        
				<div class="commonButton" id="bid-cancel" title="Cancelar" onMouseOver="" onMouseOut=""><button name="bname_cancel">Cancelar</button><span>Cancelar</span></div></a>
			</td>
		</tr></table>

	</div>

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
