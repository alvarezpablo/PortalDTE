<?php 
  ini_set('post_max_size', '800M');
  ini_set('upload_max_filesize', '800M');
  ini_set('memory_limit', '1024M');
  ini_set('max_execution_time', '36000');
  ini_set('max_input_time', '36000');

  include("../include/config.php");  
  include("../include/ver_aut.php");      
//  include("../include/ver_aut_adm.php");        
  include("../include/db_lib.php"); 
  include("../include/tables.php");  
  $sMsgJs = trim($_GET["sMsgJs"]);  

  $sLinkActual = "mantencion/form_cont_elec.php";  
  $_NUM_ROW_LIST = 200;
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
    echo "alert('" . $sMsgJs . "');\n";
 
 ?> 
   
	SetContext('cl_ed');
		
}

   function chListBoxSearch(){
      var F = document._FSEARCH;
      for(i=0; i < F._COLUM_SEARCH.length; i++){
        if(F._COLUM_SEARCH.options[i].value == "<?php echo $_COLUM_SEARCH; ?>")
          F._COLUM_SEARCH.options[i].selected = true;
      }
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
	<div class="screenTitle">
		<table width="100%" cellspacing="0">
		<tr>
			<td>Carga de Contribuyentes Electr&oacute;nicos:</td>
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

  <form name="_FFORM" enctype="multipart/form-data" action="mantencion/pro_clie_elec_v2.php" method="post">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $_MAX_FILE_CLIE_ELEC; ?>"> 
 
  		
	<div class="formArea">
		<fieldset>
<legend>Formulario de Contribuyentes Electr&oacute;nico </legend>
<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td>

<table class="formFields" cellspacing="0" width="100%">
	<tr>
		<td class="name"><label for="fid-cname">Archivo :</label>&nbsp;*</td>
		<td><input type="file" name="sFileClieElec" id="fid-cname" value="" size="25" maxlength="1000"></td>
	</tr>
</table>


</td></tr></table></fieldset>

	</div>
	
	<div class="formArea">
		<table width="100%" class="buttons" cellspacing="0" cellpadding="0"><tr>
			<td class="main" width="0"></td>
			<td class="footnote"><span class="required">*</span> Campos requeridos.</td>
			<td class="misc" width="0">
				<div class="commonButton" id="bid-ok" title="Aceptar" onClick="document._FFORM.submit();" onMouseOver="" onMouseOut=""><button name="bname_ok">Aceptar</button><span>Aceptar</span></div></form>
				<div class="commonButton" id="bid-cancel" title="Cancelar" onClick="location.href='<?php echo $_LINK_BASE; ?>main.php';" onMouseOver="" onMouseOut=""><button name="bname_cancel">Cancelar</button><span>Cancelar</span></div>
			</td>
		</tr></table>
<br><br>

	</div>

<!-- ************************ -->


							<table width="100%" cellspacing="0" class="buttons">
								<td class="main">
									<div>
                    <form name="_FSEARCH" method="get" action="<?php echo $_LINK_BASE . $sLinkActual; ?>">
					<select name="_COLUM_SEARCH">
                      <option value="rut_contr">Rut</option>
                      <option value="rs_contr">Raz&oacute;n Social</option>                      
                      <option value="email_contr">Email</option>                    
                    </select>
                    <script> chListBoxSearch(); </script>
                    
					<input type="text" name="_STRING_SEARCH" id="searchInput" value="<?php echo $_STRING_SEARCH; ?>" size="20" maxlength="245">
					<div class="commonButton" id="bid-search" title="Buscar"  name="bid-search">
						<button name="bname_search" onclick="document._FSEARCH.submit();">Buscar</button><span>Buscar</span>
					</div>
<!--										<div class="commonButton" id="bid-show-all" title="Mostrar todo" name="bid-show-all">
						<button name="bname_show_all">Mostrar todo</button><span>Mostrar todo</span>
					</div> -->
                    </form>
									</div>
								</td>

                <td class="misc">

								</td>
							</table>

<!-- *********************** -->
            <form name="_FDEL" method="post" action="dte/pro_dte.php">
              <input type="hidden" name="sAccion" value="E">
							<table width="100%" cellspacing="0" class="list">
<?php 
                
  $sql = "  SELECT  
				rut_contr, 
				rs_contr, 
				nrores_contr, 
				fecres_contr, 
				email_contr 
			FROM 
				contrib_elec 
            WHERE  
                1 = 1 ";

		if($nTipoDocu != "")
			$sql .= " AND D.tipo_docu = $nTipoDocu ";
        

        if($_STRING_SEARCH != "")
          $sql .= " AND UPPER(cast(" . $_COLUM_SEARCH  . " as varchar)) LIKE UPPER('" . str_replace("'","''",$_STRING_SEARCH) . "%') ";
       
        if(trim($_ORDER_BY_COLUM) == "")
          $sql .= " ORDER BY rs_contr ";
        else
          $sql .= " ORDER BY " . $_ORDER_BY_COLUM . "  $_NIVEL_BY_ORDER ";          
        
		
        if($_ORDER_BY_COLUM == "rut_contr"){
           $sClassRut = "class='sort'";
           $sImgRut = "<img src='" . $_IMG_BY_ORDER . "'>";          
        }
        else{
          if($_ORDER_BY_COLUM == "rs_contr"){
            $sClassRs = "class='sort'";
            $sImgRs = "<img src='" . $_IMG_BY_ORDER . "'>";          
          }
          else{
            if($_ORDER_BY_COLUM == "fecres_contr"){
              $sClassFe = "class='sort'";
              $sImgFe = "<img src='" . $_IMG_BY_ORDER . "'>";          
            }
            else{
              if($_ORDER_BY_COLUM == "email_contr"){
                $sClassEm = "class='sort'";
                $sImgEm = "<img src='" . $_IMG_BY_ORDER . "'>";          
              }
			  else{
				   $sClassRut = "class='sort'";
				   $sImgRut = "<img src='" . $_IMG_BY_ORDER . "'>";          
			  }
            }
          }
        }
        
?>			              
		<tr>
		  <th <?php echo $sClassRut; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=rut_contr&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Rut</a><?php echo $sImgRut; ?></th>
		  <th <?php echo $sClassRs; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=rs_contr&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Raz&oacute;n Social</a><?php echo $sImgRs; ?></th>
		  <th <?php echo $sClassFe; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=fecres_contr&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Fecha Creaci&oacute;n</a><?php echo $sImgFe; ?></th>
		  <th <?php echo $sClassEm; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=email_contr&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Email</a><?php echo $sImgEm; ?></th>
		</tr>
<?php 
/***********************************************************/
        
        $result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);        
        $sPaginaResult = sPagina($conn, $sql, $sLinkActual, 100);        // string de paginacion
        
        $sClassRow = "evenrowbg";                                   // clase de la hoja de estilo 
        
        while (!$result->EOF) {
          $sRut = trim($result->fields["rut_contr"]);
          $sRs = trim($result->fields["rs_contr"]);
          $nFolioDte = trim($result->fields["nrores_contr"]);
          $dFec = trim($result->fields["fecres_contr"]);
          $sEm = trim($result->fields["email_contr"]);
?>																												
			<tr class="<?php echo $sClassRow; ?>">
				<td><?php echo $sRut; ?></td>
				<td><?php echo $sRs; ?></td>
				<td><?php echo $dFec; ?></td>
				<td><?php echo $sEm; ?></td>
			</tr>

<?php
          if($sClassRow == "oddrowbg")
            $sClassRow = "evenrowbg";
          else
            $sClassRow = "oddrowbg";
            
          $result->MoveNext();
        } 
        
/**********************************************************/
?>		                
                              
                
 							</table>
							<div class="paging"><?php echo $sPaginaResult; ?></div>

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
