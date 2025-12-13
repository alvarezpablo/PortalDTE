<?php 
	include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");        
	include("../include/db_lib.php"); 
	include("../include/tables.php"); 
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
	SetContext('clients');
	setActiveButtonByName('clients');
	loff();
	
}

function _body_onunload()
{
	lon();
	
}

function open_help()
{
	try {
		window.open('/help.php?context=' + GetHelpPrefix() + GetContext() + (GetHelpModule() ? '&module=' + GetHelpModule() : ''),
				'help',
				'toolbar=no,width=500,height=400,innerHeight=400,innerWidth=500,scrollbars=yes,resizable=yes');
	} catch (e) {
		return false;
	}
	return true;
}

var opt_no_frames = false;
var opt_integrated_mode = false;
setActiveButtonByName("clients");


    function chListBoxSearch(){
      var F = document._FSEARCH;
      for(i=0; i < F._COLUM_SEARCH.length; i++){
        if(F._COLUM_SEARCH.options[i].value == "<?php echo $_COLUM_SEARCH; ?>")
          F._COLUM_SEARCH.options[i].selected = true;
      }
    }
    
    function chSelDelEmp(){
      var F = document._FDEL;
    
      for(i=0; i < F.elements.length; i++){
        if(F.elements[i].name == "del[]"){
            if(F.elements[i].checked == true)
              return true;
          
        }
      }
      return false;
    }
    
    function chDelEmp(){
      if(chSelDelEmp() == true){
        if(confirm(_MSG_DEL_USER))
          document._FDEL.submit();
      }
      else
        alert(_MSG_SEL_USER_DEL);
    }
    
    function chDchALL(){
      var F = document._FDEL;
      var obj = F.clientslistSelectAll;
      
      if(obj.checked == true){
        for(i=0; i < F.elements.length; i++){
           if(F.elements[i].name == "del[]")
              F.elements[i].checked = true;                                 
        }
      }
      else{
        for(i=0; i < F.elements.length; i++){
           if(F.elements[i].name == "del[]")
              F.elements[i].checked = false;                                 
        }
      }
    }
  

//-->
		</script>
	</head>
	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP">

	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>
	<?php sTituloCabecera("Usuarios"); ?>
	<?php sAgregaHerramienta("screenClientList", "Herramientas", $aBotonUserHerramienta); ?>
<?php 
        $conn = conn();
        $sLinkActual = "usuario/list_user.php";
?>
	<div class="screenBody">
		<div class="listArea">
			<fieldset>
				<legend>Usuarios</legend>
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td>
							<table width="100%" cellspacing="0" class="buttons">
								<td class="main">
									<div>
                    <form name="_FSEARCH" method="get" action="<?php echo $_LINK_BASE . $sLinkActual; ?>">
                    <input type="hidden" name="_COLUM_SEARCH" value="id_usu">
										<input type="text" name="_STRING_SEARCH" id="searchInput" value="<?php echo $_STRING_SEARCH; ?>" size="20" maxlength="245">
										<div class="commonButton" id="bid-search" title="Buscar"  name="bid-search">
											<button name="bname_search" onclick="document._FSEARCH.submit();">Buscar</button><span>Buscar</span>
										</div>
<!--										<div class="commonButton" id="bid-show-all" title="Mostrar todo" name="bid-show-all">
											<button name="bname_show_all">Mostrar todo</button><span>Mostrar todo</span>
										</div> -->
                    </form>
								</td>
								<td class="misc">
									<div>
										<div class="commonButton">&nbsp;</div>

										<div class="commonButton" id="bid-remove-selected" title="Eliminar seleccion"  name="bid-remove-selected">
													<button name="bname_remove_selected" onclick="chDelEmp();">Eliminar seleccion</button><span>Eliminar seleccion</span>
										</div>
									</div>
								</td>
							</table>
         
              
            <form name="_FDEL" method="post" action="usuario/pro_usu.php">
              <input type="hidden" name="sAccion" value="E">         

<?php 
                
        $sql = "SELECT cod_usu, id_usu, cert_usu, est_usu, U.cod_rol, R.desc_rol FROM usuario U, rol R WHERE CAST(R.cod_rol as varchar) = U.cod_rol "; 
        
        
        if($_STRING_SEARCH != "")
          $sql .= " AND UPPER(" . $_COLUM_SEARCH  . ") LIKE UPPER('" . str_replace("'","''",$_STRING_SEARCH) . "%') ";
        
        if(trim($_ORDER_BY_COLUM) == "")
          $sql .= " ORDER BY id_usu ";
        else
          $sql .= " ORDER BY " . $_ORDER_BY_COLUM . "  $_NIVEL_BY_ORDER ";          

        
        if($_ORDER_BY_COLUM == "id_usu"){
           $sClassUsu = "class='sort'";
           $sImgUsu = "<img src='" . $_IMG_BY_ORDER . "'>";          
        }
        else{
          if($_ORDER_BY_COLUM == "R.desc_rol"){
            $sClassRol = "class='sort'";
            $sImgRol = "<img src='" . $_IMG_BY_ORDER . "'>";          
          }
          else{
            if($_ORDER_BY_COLUM == "est_usu"){
              $sClassEst = "class='sort'";
              $sImgEst = "<img src='" . $_IMG_BY_ORDER . "'>";          
            }
            else{
              $sClassUsu = "class='sort'";
              $sImgUsu = "<img src='" . $_IMG_BY_ORDER . "'>";                     
            }
          }
        }
                
?>			         
                  
							<table width="100%" cellspacing="0" class="list">
								<tr>
									<th width="0" <?php echo $sClassEst; ?>>
                    <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=est_usu&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Estado</a>
                    <?php echo $sImgEst; ?>
                  </th>								
                  
									<th width="40%" <?php echo $sClassUsu; ?>>
                    <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=id_usu&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">User</a>
                    <?php echo $sImgUsu; ?>
                  </th>
              <!--    
									<th width="30%">
                      Certificado Digital
                  </th> -->
                  
									<th width="30%" <?php echo $sClassRol; ?>>
                  <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=R.desc_rol&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Rol</a>
                  <?php echo $sImgRol; ?>
                  </th>								
									
                  <th width="0" <?php echo $sClassRut; ?>>                    
                    <input type="checkbox" class="checkbox" name="clientslistSelectAll" value="true" onClick="chDchALL();">
                  </th>
								</tr>




<?php 
/********************** LISTA USUARIOS ****************************************/
        
        $result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);        
        $sPaginaResult = sPagina($conn, $sql, $sLinkActual);        // string de paginacion
        
        $sClassRow = "evenrowbg";               // clase de la hoja de estilo 
        
        while (!$result->EOF) {
        
          $nCodUsu = trim($result->fields["cod_usu"]);
          $sIdUsu = trim($result->fields["id_usu"]);
          $sPathCert = trim($result->fields["cert_usu"]);
          $sEstUsu = trim($result->fields["est_usu"]);
          $sCodRolUsu = trim($result->fields["cod_rol"]);          
          $sDescRol = trim($result->fields["desc_rol"]);          
          
          $strParamLink = "nCodUsu=" . urlencode($nCodUsu);
          $strParamLink .= "&sIdUsu=" . urlencode($sIdUsu);
          $strParamLink .= "&sIdUsuNew=" . urlencode($sIdUsu);          
          $strParamLink .= "&sPathCert=" . urlencode($sPathCert);          
          $strParamLink .= "&sEstUsu=" . urlencode($sEstUsu);                    
          $strParamLink .= "&sCodRolUsu=" . urlencode($sCodRolUsu);                              
          $strParamLink .= "&sDescRol=" . urlencode($sDescRol);                                        
          $strParamLink .= "&sAccion=M";            
          
          if($sEstUsu == "1")
            $sImgEstado = "skins/" . $_SKINS . "/icons/on.gif";
          else
            $sImgEstado = "skins/" . $_SKINS . "/icons/off.gif";

?>																							
								<tr class="<?php echo $sClassRow; ?>">
									<td class="icon"><img src='<?php echo $sImgEstado; ?>'></td>
									<td>
                    <a href="javascript:location.href='<?php echo $_LINK_BASE; ?>usuario/form_user.php?<?php echo $strParamLink; ?>';">
                      <?php echo $sIdUsu; ?>
                    </a>
                  </td>
                  
					<!--				<td><?php echo $sPathCert; ?></td> -->
									<td><?php echo $sDescRol; ?></td>  
									<td class="select" width=1><input type="checkbox" class="checkbox" name="del[]" id="del_2" value="<?php echo $nCodUsu; ?>"></td>
								</tr>
                
<?php
          if($sClassRow == "oddrowbg")
            $sClassRow = "evenrowbg";
          else
            $sClassRow = "oddrowbg";
            
          $result->MoveNext();
        } 
        
/*********************** FIN LISTA USUARIOS ***********************************/
?>								           
                
                
							</table>
							<div class="paging"><?php echo $sPaginaResult; ?></div>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</div>
 	
 </body>
</html>
