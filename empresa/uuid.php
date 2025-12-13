<?php 
  include("../include/config.php"); 
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm_super.php");        
  include("../include/ver_emp_adm.php");         
  include("../include/tables.php");  
  include("../include/db_lib.php"); 

  $conn = conn();

  $nCodEmp = trim($_SESSION["_COD_EMP_USU_SESS"]);
  $sAccion = "M";
  $sMsgJs = trim($_GET["sMsgJs"]);  

  $sql = "SELECT 
  			E.uuid
  		FROM 
  			empresa E
  		WHERE 
  			E.codi_empr = " . $nCodEmp; 

  $result = rCursor($conn, $sql);

  if(!$result->EOF) {
  	$sUuid = trim($result->fields["uuid"]);
  }

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>Portal OpenDTE</title>
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

//-->
		</script>
	</head>

<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	
  <form name="_FFORM" enctype="multipart/form-data" action="empresa/pro_uuid.php" method="post" onSubmit="return valida();">
    <input type="hidden" name="nCodEmp" value="<?php echo $nCodEmp; ?>">
    <input type="hidden" name="sAccion" value="<?php echo $sAccion; ?>">
 
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

<legend>Formulario Api Key</legend>
<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td>

<table class="formFields" cellspacing="0" width="100%">

	<tr>
		<td class="name"><label for="fid-cname">
        <?php 
              echo "Api Key:";
        ?>
         
          </label>&nbsp;</td>
		<td><input type="text" name="uuid" id="fid-cname" value="<?php echo $sUuid; ?>" size="35"> </td>
	</tr>

	
</table>

<br>

Esta clave se debe solicitar para ser utilizada con los webservices OpenDTE. El valor que se asigna automaticamente debe ir en el parametro apikey en el metodo a utilizar.<br>
Si desea modificar el valor, solo debe presionar el boton "Actualizar".

<input type="hidden" name="start" value="">

</td></tr></table></fieldset>

	</div>
	
	<div class="formArea" align=left>
		<table width="100" class="buttons" cellspacing="0" cellpadding="0"><tr>
			<td class="footnote" align="left"><span class="required"></span></td>
			<td class="misc" width="0" align="left">
				<div class="commonButton" id="bid-ok" title="Actualizar" onMouseOver="" onMouseOut="" align="left"><button name="bname_ok" onClick="return valida();" >Actualizar</button><span>Actualizar</span></div>
				<a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>empresa/listempre.php';">
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
