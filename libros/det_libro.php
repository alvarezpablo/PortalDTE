<?php 
	include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_emp_adm.php");        
	include("../include/db_lib.php"); 
	include("../include/tables.php"); 
  
  $conn = conn();
  $nCodClcv = $_GET["nCodClcv"];  
  $sTipo = $_GET["sTipo"];  

  $sLinkActual = "libros/det_libro.php?nCodClcv=" . $nCodClcv . "&sTipo=" . $sTipo;   
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
	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

	<a href="#" name="top" id="top"></a>

	<?php sTituloCabecera("Libro de $sTipo"); ?>
	<?php // sAgregaHerramienta("screenClientList", "Herramientas", $aBotonEmpHerramienta); ?>

	<div class="screenBody">
		<div class="listArea">
			<fieldset>
				<legend>Libro de <?php echo $sTipo; ?></legend>
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td>
							<table width="100%" cellspacing="0" class="buttons">
								<td class="main">
									<div>
<!--                    <form name="_FSEARCH" method="get" action="<?php echo $_LINK_BASE . $sLinkActual; ?>">
                    <INPUT type="hidden" name="_EST_DTE" value="<?php echo $_EST_DTE; ?>">
                    <select name="_COLUM_SEARCH">
                      <option value="D.folio_dte">Folio Dte</option>
                      <option value="D.fec_emi_dte">Fecha Emisi&oacute;n</option>                      
                      <option value="D.fec_venc_dte">Fecha Vencimiento</option>                    
                      <option value="D.nom_rec_dte">Razon Social Receptor</option>                    
                      <option value="D.giro_rec_dte">Giro Receptor</option>                    
                    </select>
                    <script> chListBoxSearch(); </script>
                    
										<input type="text" name="_STRING_SEARCH" id="searchInput" value="<?php echo $_STRING_SEARCH; ?>" size="20" maxlength="245">
										<div class="commonButton" id="bid-search" title="Buscar"  name="bid-search">
											<button name="bname_search" onclick="document._FSEARCH.submit();">Buscar</button><span>Buscar</span>
										</div>
-->
<!--										<div class="commonButton" id="bid-show-all" title="Mostrar todo" name="bid-show-all">
											<button name="bname_show_all">Mostrar todo</button><span>Mostrar todo</span>
										</div> -->
<!--                    </form> -->
									</div>
								</td>

                <td class="misc">

				<?php	if($_EST_DTE == "0" || $_EST_DTE == "1" || $_EST_DTE == "3") { ?>
				<!--					<div>
										<div class="commonButton">&nbsp;</div>

										<div class="commonButton" id="bid-remove-selected" title="Eliminar seleccion"  name="bid-remove-selected">
											<button name="bname_remove_selected" onclick="chDelEmp();">Eliminar seleccion</button><span>Eliminar seleccion</span>
										</div>
									</div> -->
				<?php  } ?>
								</td>
							</table>
              
            <form name="_FDEL" method="post" action="empresa/pro_emp.php">
              <input type="hidden" name="sAccion" value="E">
							<table width="100%" cellspacing="0" class="list">
<?php                 
  $sql = "  SELECT 
				rlcv_tip_doc,
				(SELECT desc_tipo_docu from dte_tipo WHERE tipo_docu = lcv_res_seg.rlcv_tip_doc) as nom_tip_doc,
				rlcv_can_doc,
				rlcv_tot_exc,
				rlcv_tot_mto,
				rlcv_tot_iva
			FROM
				lcv_res_seg
			WHERE
				clcv_correl = '" . str_replace("'","''",$nCodClcv) . "' 
			ORDER BY rlcv_tip_doc ";
       
      
        if($_ORDER_BY_COLUM == "D.folio_dte"){
           $sClassFol = "class='sort'";
           $sImgFol = "<img src='" . $_IMG_BY_ORDER . "'>";          
        }
        else{
          if($_ORDER_BY_COLUM == "D.fec_emi_dte"){
            $sClassFED = "class='sort'";
            $sImgFED = "<img src='" . $_IMG_BY_ORDER . "'>";          
          }
          else{
            if($_ORDER_BY_COLUM == "D.fec_venc_dte"){
              $sClassFVC = "class='sort'";
              $sImgFVC = "<img src='" . $_IMG_BY_ORDER . "'>";          
            }
            else{
              if($_ORDER_BY_COLUM == "D.giro_rec_dte"){
                $sClassGR = "class='sort'";
                $sImgGR = "<img src='" . $_IMG_BY_ORDER . "'>";          
              }
              else{
                if($_ORDER_BY_COLUM == "DT.desc_tipo_docu"){
                  $sClassTD = "class='sort'";
                  $sImgTD = "<img src='" . $_IMG_BY_ORDER . "'>";          
                }
                else{
                  $sClassFol = "class='sort'";
                  $sImgFol = "<img src='" . $_IMG_BY_ORDER . "'>";                     
                }
              }
            }
          }
        }
        
?>			              
                <tr>
                  <th>Documento</th>									
				  <th>Cantida Documento</th>									
				  <th>Total Exento</th>									
				  <th>Total</th>				
				  <th>Total Iva</th>				
				</tr>
                
<?php 
/***********************************************************/
        
        $result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);        
        $sPaginaResult = sPagina($conn, $sql, $sLinkActual);        // string de paginacion
        
        $sClassRow = "evenrowbg";                                   // clase de la hoja de estilo 
        
        while (!$result->EOF) {
          $nCodClcv = trim($result->fields["rlcv_tip_doc"]);
		  $sTipDoc = trim($result->fields["nom_tip_doc"]);
          $nCanDoc = trim($result->fields["rlcv_can_doc"]);
          $nTotExc = trim($result->fields["rlcv_tot_exc"]);
          $nTotNeto = trim($result->fields["rlcv_tot_mto"]);
          $nTotIva = trim($result->fields["rlcv_tot_iva"]);
?>																												
			<tr class="<?php echo $sClassRow; ?>">
				<td><?php echo $sTipDoc; ?></td>
				<td align="right"><?php echo number_format($nCanDoc,0,",","."); ?></td>
				<td align="right"><?php echo number_format($nTotExc,0,",","."); ?></td>
				<td align="right"><?php echo number_format($nTotNeto,0,",","."); ?></td>
				<td align="right"><?php echo number_format($nTotIva,0,",","."); ?></td>
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
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</div>
 	<center><INPUT TYPE="button" value="Volver" onClick="location.href='libros/list_libro.php?sTipo=<?php echo $sTipo; ?>'"></center>
 </body>
</html>
