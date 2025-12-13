<?php 
	include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_emp_adm.php");        
	include("../include/db_lib.php"); 
	include("../include/tables.php"); 
  
  $conn = conn();
  $sTipo = $_GET["sTipo"];  
  $sLinkActual = "libros/list_libro.php?sTipo=" . $sTipo;  
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

    function delLibro(sTipo, nCodClcv){
		if(confirm("Eliminar Libro"))
    		location.href="libros/del_libro.php?sTipo=" + sTipo + "&nCodClcv=" + nCodClcv;		
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
/* Correcion sobre eliminacion de libros segun SII
 * 17-01-2009 
  $sql = "  SELECT 
				clcv_correl, 
				clcv_rut_emisor,
				clcv_dv_emisor,
				clcv_per_trib,
				clcv_tip_lib,
				clcv_tip_env
			FROM
				lcv
			WHERE
				clcv_tip_oper = '" . str_replace("'","''",$sTipo) . "' AND
				codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'
			ORDER BY clcv_per_trib DESC ";
        */
  $sql = "  SELECT 
				L.clcv_correl, 
				L.clcv_rut_emisor,
				L.clcv_dv_emisor,
				L.clcv_per_trib,
				L.clcv_tip_lib,
				L.clcv_tip_env,
				LX.est_lcx,
				'<a href=\"libros/view_xml.php?nClcvcorrel=' || LX.clcv_correl || '\">Ver</a>' as xml,	
				LX.trackid
			FROM
				lcv L,
				lcvxml LX
			WHERE
				L.clcv_tip_oper = '" . str_replace("'","''",$sTipo) . "' AND
				L.codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "' AND
				L.clcv_correl = LX.clcv_correl
			ORDER BY L.clcv_per_trib DESC ";
        
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
			<th>Estado</th>
			<th>Trackid</th>
		                  <th>Periodo</th>									
				  <th>Tipo Libro</th>									
				  <th>Tipo Envio</th>									
				  <th>Detalle</th>				
			<th>XML</th>
				  <th>X</th>
				</tr>
                
  
                
<?php 
/***********************************************************/
        
        $result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);        
        $sPaginaResult = sPagina($conn, $sql, $sLinkActual);        // string de paginacion
        
        $sClassRow = "evenrowbg";                                   // clase de la hoja de estilo 
        
        while (!$result->EOF) {
          $nCodClcv = trim($result->fields["clcv_correl"]);
		  $sPerTrib = trim($result->fields["clcv_per_trib"]);
          $sTipLib = trim($result->fields["clcv_tip_lib"]);
          $sTipEnv = trim($result->fields["clcv_tip_env"]);
          $nEstLcx = trim($result->fields["est_lcx"]);                

	$xml = trim($result->fields["xml"]);
	$trackid = trim($result->fields["trackid"]);

	if($nEstLcx == "0")
		$estLibro = "Cargado";
        if($nEstLcx == "1") 
                $estLibro = "Enviado a SII";
        if($nEstLcx == "3") 
                $estLibro = "Aceptado SII";
        if($nEstLcx == "5")
                $estLibro = "Rechazado SII";
        if($nEstLcx == "8") 
                $estLibro = "Rechazado SII";
        if($nEstLcx == "13")
                $estLibro = "Aceptado con Reparos";

?>																												
			<tr class="<?php echo $sClassRow; ?>">
				<td><?php echo $estLibro; ?></td>
				<td><?php echo $trackid; ?></td>
				<td><?php echo $sPerTrib; ?></td>
				<td><?php echo $sTipLib; ?></td>
				<td><?php echo $sTipEnv; ?></td>
				<td><a href="libros/det_libro.php?sTipo=<?php echo $sTipo; ?>&nCodClcv=<?php echo $nCodClcv; ?>">Ver</a></td>
<td><?php echo $xml; ?></td>

<?php	if($nEstLcx == 8 || $nEstLcx == 0 || $nEstLcx == 13 || $nEstLcx == 5){		?>
				<td><a href="javascript:delLibro('<?php echo $sTipo; ?>',<?php echo $nCodClcv; ?>);">Eliminar</a></td>
				
<?php 	}else{ ?>
				<td>&nbsp;</td>
<?php   } ?>
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
					<tr><td><b>Nota: Solo se pueden borrar los libros con error</b></td></tr>
				</table>
			</fieldset>
		</div>
	</div>
 	
 </body>
</html>
