<?php 
	include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_emp_adm.php");        
	include("../include/db_lib.php"); 

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
		
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/custom.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/layout.css">
		<link rel="stylesheet" type="text/nonsense" href="skins/<?php echo $_SKINS; ?>/css/misc.css">


<script type="text/javascript">
<!--


function _body_onload()
{
	loff();
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
	
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td id="screenWH">
	<div class="pathbar"><a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>main.php';">CEDIBLES</a> &gt;</div>
	<div class="screenTitle">
		<table width="100%" cellspacing="0">
		<tr>
			<td>DTEs Cedidos:</td>
			<td class="uplevel"><div class="commonButton" id="bid-up-level" title="Subir nivel"><button name="bname_up_level">Subir nivel</button><span>Subir nivel</span></div></td>
		</tr>
		</table>
	</div>
	<div id="screenSubTitle"></div>
	<div id="screenTabs">
		<div id="tabs">
			
		</div>

	</div>
	<div class="screenBody" id="">
		
	<div class="listArea">
		<fieldset>

		<legend>DTEs Cedidos</legend>

		<table cellspacing="0" width="100%" class="list">
			
			<tr>
				<th width="20%">Documento&nbsp;</td>
				<th width="10%">Folio:&nbsp;</td>
				<th width="10%">Rut Cedente:&nbsp;</td>
				<th width="20%">RS Cedente:&nbsp;</td>
				<th width="10%">Rut Cesionario:&nbsp;</td>
				<th width="20%">RS Cesionario:&nbsp;</td>
				<th width="10%">Fecha:&nbsp;</td>
			</tr>
<?php 
      $sql = "SELECT 
				C.folio_dte,
				C.rut_cedente,
				C.razon_social_cedente,
				C.rut_cesionario,
				C.razon_social_cesionario,
				C.fecha_ultimo_vencimiento,
				D.desc_tipo_docu
              FROM 
                dte_ceder C,
				dte_tipo D 
              WHERE 
                D.tipo_docu = C.tipo_docu AND
                codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";
        $result = rCursor($conn, $sql);        
        $sPaginaResult = sPagina($conn, $sql, $sLinkActual);        // string de paginacion
        
        $sClassRow = "evenrowbg";               // clase de la hoja de estilo 
        
        while (!$result->EOF) {
          $sTipDoc = trim($result->fields["desc_tipo_docu"]);        
          $sFolDoc = trim($result->fields["folio_dte"]);        
          $sRutCed = trim($result->fields["rut_cedente"]);        
          $sRSCed = trim($result->fields["razon_social_cedente"]);        
          $sRutCDario = trim($result->fields["rut_cesionario"]);        
          $sRSCDario = trim($result->fields["razon_social_cesionario"]);        
          $dFecVenc = trim($result->fields["fecha_ultimo_vencimiento"]);        

?>
			<tr>
				<td class="<?php echo $sClassRow; ?>"><label for="fid-cname"><?php echo $sTipDoc; ?></label>&nbsp;</td>
				<td class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $sFolDoc; ?></label>&nbsp;</td>
				<td class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $sRutCed; ?></label>&nbsp;</td>
				<td class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $sRSCed; ?></label>&nbsp;</td>
				<td class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $sRutCDario; ?></label>&nbsp;</td> 
				<td class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $sRSCDario; ?></label>&nbsp;</td>
				<td class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $dFecVenc; ?></label>&nbsp;</td> 
			</tr>

<?php
          if($sClassRow == "oddrowbg")
            $sClassRow = "evenrowbg";
          else
            $sClassRow = "oddrowbg";
            
          $result->MoveNext();
        } 
        
?>	
		</table>

		<input type="hidden" name="start" value="">

		</fieldset>

	</div>
	
	<div class="formArea">
		<table width="100%" class="buttons" cellspacing="0" cellpadding="0"><tr>
			<td class="main" width="0"></td>
			<td class="footnote">&nbsp;</td>
			<td class="misc" width="0">
				<div class="commonButton" id="bid-ok" title="Aceptar" onClick="location.href='<?php echo $_LINK_BASE; ?>main.php';" onMouseOver="" onMouseOut=""><button name="bname_ok" onClick="location.href='<?php echo $_LINK_BASE; ?>main.php';">Aceptar</button><span>Aceptar</span></div>
			</td>
		</tr></table>

		<input type="hidden" name="cmd" value="update">
		<input type="hidden" name="lock" value="false">
		<input type="hidden" name="previous_page" value="cl_ed">

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