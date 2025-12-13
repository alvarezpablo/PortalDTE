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
        if(confirm(_MSG_DEL_EMP))
          document._FDEL.submit();
      }
      else
        alert(_MSG_SEL_EMP_DEL);
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
	<?php sTituloCabecera("Empresa"); ?>
	<?php sAgregaHerramienta("screenClientList", "Herramientas", $aBotonEmpHerramienta); ?>
<?php 
        $conn = conn();
        $sLinkActual = "empresa/listempre.php";
?>
	<div class="screenBody">
		<div class="listArea">
			<fieldset>
				<legend>Empresas</legend>
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td>
							<table width="100%" cellspacing="0" class="buttons">
								<td class="main">
									<div>
                    <form name="_FSEARCH" method="get" action="<?php echo $_LINK_BASE . $sLinkActual; ?>">
                    <select name="_COLUM_SEARCH">
                      <option value="rut_empr">Rut (Sin DV))</option>
                      <option value="rs_empr">Raz&oacute;n Social</option>                      
                      <option value="dir_empr">Direcci&oacute;n</option>                    
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
									<div>
										<div class="commonButton">&nbsp;</div>

										<div class="commonButton" id="bid-remove-selected" title="Eliminar seleccion"  name="bid-remove-selected">
											<button name="bname_remove_selected" onclick="chDelEmp();">Eliminar seleccion</button><span>Eliminar seleccion</span>
										</div>
									</div>
								</td>
							</table>
              
            <form name="_FDEL" method="post" action="empresa/pro_emp.php">
              <input type="hidden" name="sAccion" value="E">
							<table width="100%" cellspacing="0" class="list">
<?php 
                
        $sql = "SELECT codi_empr, rut_empr, dv_empr, rs_empr, dir_empr, cod_act, giro_emp, com_emp, fec_resolucion, num_resolucion FROM empresa WHERE 1=1 ";
        
        if($_STRING_SEARCH != "")
          $sql .= " AND UPPER(CAST(" . $_COLUM_SEARCH  . " AS VARCHAR)) LIKE UPPER('" . str_replace("'","''",$_STRING_SEARCH) . "%') ";
        
        if(trim($_ORDER_BY_COLUM) == "")
          $sql .= " ORDER BY rs_empr ";
        else
          $sql .= " ORDER BY " . $_ORDER_BY_COLUM . "  $_NIVEL_BY_ORDER ";          
        
        if($_ORDER_BY_COLUM == "rut_empr"){
           $sClassRut = "class='sort'";
           $sImgRut = "<img src='" . $_IMG_BY_ORDER . "'>";          
        }
        else{
          if($_ORDER_BY_COLUM == "rs_empr"){
            $sClassRSocial = "class='sort'";
            $sImgRSocial = "<img src='" . $_IMG_BY_ORDER . "'>";          
          }
          else{
            if($_ORDER_BY_COLUM == "dir_empr"){
              $sClassDirec = "class='sort'";
              $sImgRDirec = "<img src='" . $_IMG_BY_ORDER . "'>";          
            }
            else{
              $sClassRSocial = "class='sort'";
              $sImgRSocial = "<img src='" . $_IMG_BY_ORDER . "'>";                     
            }
          }
        }
        
        
?>								
                
                <tr>
									<th width="20%" <?php echo $sClassRut; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=rut_empr&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Rut</a><?php echo $sImgRut; ?></th>
									<th width="30%" <?php echo $sClassRSocial; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=rs_empr&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Raz&oacute;n Social</a><?php echo $sImgRSocial; ?></th>
									<th width="30%" <?php echo $sClassDirec; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=dir_empr&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Dirección</a><?php echo $sImgRDirec; ?></th>								
									<th width="20%" <?php echo $sClassDirec; ?>>Properties</th>								
									<th width="0" class="select"><input type="checkbox" class="checkbox" name="clientslistSelectAll" value="true" onClick="chDchALL();"></th>
								</tr>


<?php 
/********************** LISTA EMPRESAS ****************************************/
        
        $result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);        
        $sPaginaResult = sPagina($conn, $sql, $sLinkActual);        // string de paginacion
        
        $sClassRow = "evenrowbg";               // clase de la hoja de estilo 
        
        while (!$result->EOF) {
          $nCodEmp = trim($result->fields["codi_empr"]);
          $sRutEmp = trim($result->fields["rut_empr"]) . "-" . trim($result->fields["dv_empr"]);
          $sRzSclEmp = trim($result->fields["rs_empr"]);
          $sDirEmp = trim($result->fields["dir_empr"]);

		  $nCodAct = trim($result->fields["cod_act"]);
		  $sGiroEmp = trim($result->fields["giro_emp"]);
		  $sComEmp = trim($result->fields["com_emp"]);		  
		  $dFecRes = trim($result->fields["fec_resolucion"]);		  
		  $nResSii = trim($result->fields["num_resolucion"]);		  

		  $strParamLink = "nCodEmp=" . urlencode($nCodEmp);
          $strParamLink .= "&sRutEmp=" . urlencode(trim($result->fields["rut_empr"]));
          $strParamLink .= "&sDvEmp=" . urlencode(trim($result->fields["dv_empr"]));          
          $strParamLink .= "&sRzSclEmp=" . urlencode($sRzSclEmp);                    
          $strParamLink .= "&sDirEmp=" . urlencode($sDirEmp);                              
          $strParamLink .= "&nCodAct=" . urlencode($nCodAct);                              
          $strParamLink .= "&sGiroEmp=" . urlencode($sGiroEmp);         
          $strParamLink .= "&sComEmp=" . urlencode($sComEmp);         		  
          $strParamLink .= "&dFecRes=" . urlencode($dFecRes);                    
          $strParamLink .= "&nResSii=" . urlencode($nResSii);                    		  
		  $strParamLink .= "&sAccion=M";

?>																												
								<tr class="<?php echo $sClassRow; ?>">
									<td class="icon"><?php echo $sRutEmp; ?></td>
									<td><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>empresa/form_emp.php?<?php echo $strParamLink; ?>';"><?php echo $sRzSclEmp; ?></a></td>
									<td><?php echo $sDirEmp; ?></td>
									<td><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>mantencion/list_config.php?nCodEmp=<?php echo urlencode($nCodEmp); ?>';">Editar</a></td>
									<td class="select"><input type="checkbox" class="checkbox" name="del[]" value="<?php echo $nCodEmp; ?>"></td>
								</tr>

<?php
          if($sClassRow == "oddrowbg")
            $sClassRow = "evenrowbg";
          else
            $sClassRow = "oddrowbg";
            
          $result->MoveNext();
        } 
        
/*********************** FIN LISTA EMPRESAS ***********************************/
?>								           

							</table>
              </form>
							<div class="paging"><?php echo $sPaginaResult; ?></div>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</div>
 	
 </body>
</html>
