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
	<div class="pathbar"><a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE; ?>main.php';">Caf</a> &gt;</div>
	<div class="screenTitle">
		<table width="100%" cellspacing="0">
		<tr>
			<td>Caf Disponibles:</td>
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

		<legend>Caf Vigentes</legend>

		<table cellspacing="0" width="100%" class="list">
			<tr>
				<th>&nbsp;</td>
				<th colspan="2" class="sort"><center>Correlativo&nbsp;</center></td>
			</tr>

			<tr>
				<th width="40%">Documento&nbsp;</td>
				<th width="15%">Desde:&nbsp;</td>
				<th width="15%">Hasta:&nbsp;</td>
<!--				<th width="15%">Folio Actual:&nbsp;</td>
				<th width="15%">Total Disponible:&nbsp;</td>                
-->			</tr>
<?php 
      $sql = "SELECT 
                C.ini_num_caf, 
                C.ter_num_caf, 
                C.fol_disp_caf, 
                D.desc_tipo_docu, 
                (C.ter_num_caf - C.fol_disp_caf) as disp ,
		C.estado
              FROM 
                caf C, 
                dte_tipo D 
              WHERE 
                D.tipo_docu = C.tipo_docu AND estado = 1 AND
                C.codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";
        $result = rCursor($conn, $sql);        
        $sPaginaResult = sPagina($conn, $sql, $sLinkActual);        // string de paginacion
        
        $sClassRow = "evenrowbg";               // clase de la hoja de estilo 
        
        while (!$result->EOF) {
          $nNumIni = trim($result->fields["ini_num_caf"]);        
          $nNumFin = trim($result->fields["ter_num_caf"]);        
          $nNumAct = trim($result->fields["fol_disp_caf"]);        
          $sTipDoc = trim($result->fields["desc_tipo_docu"]);        
          $nNumDisp = trim($result->fields["disp"]);        
	  $estado = trim($result->fields["estado"]);

	  $color = "style=\"background-color:red\"";
	  if($estado == "1")
		$color = "";

?>
			<tr>
				<td <?php echo $color; ?> class="<?php echo $sClassRow; ?>"><label for="fid-cname"><?php echo $sTipDoc; ?></label>&nbsp;</td>
				<td <?php echo $color; ?> class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $nNumIni; ?></label>&nbsp;</td>
				<td <?php echo $color; ?> class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $nNumFin; ?></label>&nbsp;</td>
<!--				<td <?php echo $color; ?> class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $nNumAct; ?></label>&nbsp;</td>
				<td <?php echo $color; ?> class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $nNumDisp; ?></label>&nbsp;</td>                
-->			</tr>

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


        <div class="listArea">
                <fieldset>

                <legend>Caf Vencidos</legend>

                <table cellspacing="0" width="100%" class="list">
                        <tr>
                                <th>&nbsp;</td>
                                <th colspan="2" class="sort"><center>Correlativo&nbsp;</center></td>
                        </tr>

                        <tr>
                                <th width="40%">Documento&nbsp;</td>
                                <th width="15%">Desde:&nbsp;</td>
                                <th width="15%">Hasta:&nbsp;</td>
<!--                            <th width="15%">Folio Actual:&nbsp;</td>
                                <th width="15%">Total Disponible:&nbsp;</td>
-->                     </tr>
<?php
      $sql = "SELECT
                C.ini_num_caf,
                C.ter_num_caf,
                C.fol_disp_caf,
                D.desc_tipo_docu,
                (C.ter_num_caf - C.fol_disp_caf) as disp ,
                C.estado
              FROM
                caf C,
                dte_tipo D                                    
              WHERE
                D.tipo_docu = C.tipo_docu AND estado = 2 AND
                C.codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";
        $result = rCursor($conn, $sql);
        $sPaginaResult = sPagina($conn, $sql, $sLinkActual);        // string de paginacion

        $sClassRow = "evenrowbg";               // clase de la hoja de estilo

        while (!$result->EOF) {
          $nNumIni = trim($result->fields["ini_num_caf"]);
          $nNumFin = trim($result->fields["ter_num_caf"]);
          $nNumAct = trim($result->fields["fol_disp_caf"]);
          $sTipDoc = trim($result->fields["desc_tipo_docu"]);
          $nNumDisp = trim($result->fields["disp"]);
          $estado = trim($result->fields["estado"]);

          $color = "style=\"background-color:red\"";
          if($estado == "1")
                $color = "";

?>
                        <tr>
                                <td <?php echo $color; ?> class="<?php echo $sClassRow; ?>"><label for="fid-cname"><?php echo $sTipDoc; ?></label>&nbsp;</td>
                                <td <?php echo $color; ?> class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $nNumIni; ?></label>&nbsp;</td>
                                <td <?php echo $color; ?> class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $nNumFin; ?></label>&nbsp;</td>
<!--                            <td <?php echo $color; ?> class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $nNumAct; ?></label>&nbsp;</td>
                                <td <?php echo $color; ?> class="<?php echo $sClassRow; ?>" align="right"><label for="fid-cname"><?php echo $nNumDisp; ?></label>&nbsp;</td>
-->                     </tr>

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
