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
        if(confirm(_MSG_DEL_TDOC))
          document._FDEL.submit();
      }
      else
        alert(_MSG_SEL_TDOC_DEL);
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
	<?php sTituloCabecera("Tipo Documento"); ?>
	<?php sAgregaHerramienta("screenClientList", "Herramientas", $aBotonTipDocHerramienta); ?>
<?php   
        $conn = conn();
        $sLinkActual = "mantencion/list_tip_doc.php";
?>
	<div class="screenBody">
		<div class="listArea">
			<fieldset>
				<legend>Tipo Documento</legend>
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td>
              
							<table width="100%" cellspacing="0" class="buttons">
								<td class="main">&nbsp;</td>
								<td class="misc">
									<div>
										<div class="commonButton">&nbsp;</div>

										<div class="commonButton" id="bid-remove-selected" title="Eliminar seleccion"  name="bid-remove-selected">
											<button name="bname_remove_selected" onclick="chDelEmp();">Eliminar seleccion</button><span>Eliminar seleccion</span>
										</div>
									</div>
								</td>
							</table>
              
            <form name="_FDEL" method="post" action="mantencion/pro_tc.php">
              <input type="hidden" name="sAccion" value="E">							
              
              <table width="100%" cellspacing="0" class="list">
<?php 
                
        $sql = "SELECT tipo_docu, desc_tipo_docu FROM dte_tipo WHERE 1=1 ";
        
        
        if($_STRING_SEARCH != "")
          $sql .= " AND UPPER(" . $_COLUM_SEARCH  . ") LIKE UPPER('" . str_replace("'","''",$_STRING_SEARCH) . "%') ";
        
        if(trim($_ORDER_BY_COLUM) == "")
          $sql .= " ORDER BY desc_tipo_docu ";
        else
          $sql .= " ORDER BY " . $_ORDER_BY_COLUM . "  $_NIVEL_BY_ORDER ";          

        
        if($_ORDER_BY_COLUM == "desc_tipo_docu"){
           $sClassDoc = "class='sort'";
           $sImgDoc = "<img src='" . $_IMG_BY_ORDER . "'>";          
        }
        else{
            if($_ORDER_BY_COLUM == "tipo_docu"){
              $sClassCod = "class='sort'";
              $sImgCod = "<img src='" . $_IMG_BY_ORDER . "'>";          
            }
            else{
              $sClassDoc = "class='sort'";
              $sImgDoc = "<img src='" . $_IMG_BY_ORDER . "'>";                     
           }
        }
        
        
?>								
                
                <tr>
									<th width="20%" <?php echo $sClassCod; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=tipo_docu&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Codigo Documento</a><?php echo $sImgCod; ?></th>						                
									<th width="60%" <?php echo $sClassDoc; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=desc_tipo_docu&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Tipo Documento</a><?php echo $sImgDoc; ?></th>									
                  <th width="0" class="select"><input type="checkbox" class="checkbox" name="clientslistSelectAll" value="true" onClick="chDchALL();"></th>
								</tr>


<?php 
/********************** LISTA TIPO DE DOCUMENTO ****************************************/
        
        $result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);        
        $sPaginaResult = sPagina($conn, $sql, $sLinkActual);        // string de paginacion
        
        $sClassRow = "evenrowbg";               // clase de la hoja de estilo 
        
        while (!$result->EOF) {
          $nCodDoc = trim($result->fields["tipo_docu"]);
          $sDescDoc = trim($result->fields["desc_tipo_docu"]);
          
          $strParamLink = "nCodDoc=" . urlencode($nCodDoc);
          $strParamLink .= "&nCodDocNew=" . urlencode($nCodDoc);                    
          $strParamLink .= "&sDescDoc=" . urlencode($sDescDoc);
          $strParamLink .= "&sAccion=M";                                        

?>																												
								<tr class="<?php echo $sClassRow; ?>">
									<td class="icon"><?php echo $nCodDoc; ?></td>
									<td><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>mantencion/form_tc.php?<?php echo $strParamLink; ?>';"><?php echo $sDescDoc; ?></a></td>
                  <td class="select"><input type="checkbox" class="checkbox" name="del[]" value="<?php echo $nCodDoc; ?>"></td>
								</tr>

<?php
          if($sClassRow == "oddrowbg")
            $sClassRow = "evenrowbg";
          else
            $sClassRow = "oddrowbg";
            
          $result->MoveNext();
        } 
        
/*********************** FIN LISTA TIPO DE DOCUMENTO ***********************************/
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
