<?php 
 include("../include/config.php");  
  include("../include/ver_aut.php");
  include("../include/ver_aut_adm_super.php");
  include("../include/ver_emp_adm.php");

 include("../include/db_lib.php"); 
 include("../include/tables.php"); 

 function cambiarEstado($nCodCorrel, $nTipDte){
	echo "<select name='cambia' onChange='confirmCambio(" . $nCodCorrel . ", \"" . $nTipDte . "\", this);'>\n";
	echo "<option value=''></option>\n";
	echo "<option value='ACEPTADO'>Aceptar</option>\n";
	echo "<option value='RECHAZADO'>Rechazar</option>\n";
	echo "</select>\n";
 }



  $conn = conn();

  $sMsgJs = trim($_GET["sMsgJs"]);  
  $nTipDoc = trim($_GET["nTipDoc"]);

  $sLinkActual = "factura/list_rechazado.php?nTipDoc=" . $nTipDoc;

//  $sql = "SELECT desc_tipo_docu FROM dte_tipo WHERE tipo_docu = '" . $nTipDoc . "'";
//  $result = rCursor($conn, $sql);  

//  if(!$result->EOF) 
//	$sTipNomDocu = trim($result->fields["desc_tipo_docu"]);

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
    echo "alert(" . $sMsgJs . ");\n";
 
 ?>    
 
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
          if(confirm(_MSG_DEL_FACT))
          document._FDEL.submit();
      }
      else
        alert(_MSG_SEL_FACT_DEL);
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
	
	var objTmpSelect;


//-->
  </script>
 </head>
 <body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP">
 <a href="#" name="top" id="top"></a>
 <table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>
 <?php sTituloCabecera("DTE Recibidos"); ?>
 <div class="screenBody">
  <div class="listArea">
   <fieldset>
    <legend><?php echo $sTipNomDocu; ?></legend>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
     <tr>
      <td>
       <table width="100%" cellspacing="0" class="buttons">
        <td class="main">
         <div>
                    <form name="_FSEARCH" method="get" action="<?php echo $_LINK_BASE . $sLinkActual; ?>">
                    <input type="hidden" name="_COLUM_SEARCH" value="rut_rec_dte">
          <input type="text" name="_STRING_SEARCH" id="searchInput" value="<?php echo $_STRING_SEARCH; ?>" size="20" maxlength="8">Rut Emisor
          <div class="commonButton" id="bid-search" title="Buscar"  name="bid-search">
           <button name="bname_search">Buscar</button><span>Buscar</span>
          </div>
<!--          <div class="commonButton" id="bid-show-all" title="Mostrar todo" name="bid-show-all">
           <button name="bname_show_all">Mostrar todo</button><span>Mostrar todo</span>
          </div> -->
                    </form>
        </td>
        <td class="misc">
         <div>
          <div class="commonButton">&nbsp;</div>
<!--
          <div class="commonButton" id="bid-remove-selected" title="Eliminar seleccion"  name="bid-remove-selected">
             <button name="bname_remove_selected" onclick="chDelEmp();">Eliminar seleccion</button><span>Eliminar seleccion</span>
          </div> -->
         </div>
        </td>
       </table>
         
              
              <form name="_FDEL" method="post" action="factura/pro_noelecompra.php">
              <input type="hidden" name="sAccion" value="E">         
              <input type="hidden" name="nTipDoc" value="<?php echo $nTipDoc; ?>">         
<?php 
        $sql = " SELECT  "; 
        $sql .= "   correl_doc, ";
        $sql .= "   fact_ref, ";
        $sql .= "   fec_emi_doc, ";
        $sql .= "   per_desd_dte, ";
        $sql .= "   per_hast_dte, ";
        $sql .= "   rut_emis_dte, ";
        $sql .= "   digi_emis_dte, ";
        $sql .= "   nom_emis_dte, ";
        $sql .= "   dir_orig_dte, ";
        $sql .= "   rut_rec_dte, ";
        $sql .= "   dig_rec_dte, ";
        $sql .= "   nom_rec_dte, ";
        $sql .= "   mntneto_dte,  ";
        $sql .= "   mnt_exen_dte,  ";
        $sql .= "   tasa_iva_dte,  ";
        $sql .= "   iva_dte,  ";
        $sql .= "   mont_tot_dte, ";        
//        $sql .= "   (SELECT est_xdte FROM xmldte WHERE folio_txt = documentoscompras_temp.correl_doc) as est_xdte, ";        
        $sql .= "   tipo_docu, ";
        $sql .= "   (SELECT desc_tipo_docu FROM dte_tipo WHERE tipo_docu = documentoscompras_temp.tipo_docu) AS desc_docu, ";
		$sql .= "   fact_ref, ";
		$sql .= "   est_doc , path_pdf";
        $sql .= " FROM ";
        $sql .= "   documentoscompras_temp ";
        $sql .= " WHERE ";
//        $sql .= "   tipo_docu = '" . $nTipDoc . "'  AND ";
//        $sql .= "   tipo_prod_serv = 'PRODUCTO' AND ";        
//        $sql .= "   rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = '" . str_replace("'","''",$_SESSION["_COD_EMP_USU_SESS"]) . "') ";
        $sql .= "     trim(est_doc) = 'RECHAZADO' AND codi_empr = '" . str_replace("'","''",$_SESSION["_COD_EMP_USU_SESS"]) . "' ";
        
        
        if($_STRING_SEARCH != "")
          $sql .= " AND UPPER(" . $_COLUM_SEARCH  . ") LIKE UPPER('" . str_replace("'","''",$_STRING_SEARCH) . "%') ";
        
        if(trim($_ORDER_BY_COLUM) == "")
          $sql .= " ORDER BY fec_emi_doc DESC ";
        else
          $sql .= " ORDER BY " . $_ORDER_BY_COLUM . "  $_NIVEL_BY_ORDER ";          

        switch ($_ORDER_BY_COLUM) {
          case "fec_emi_doc":
                $sClassFecEmi = "class='sort'";
                $sImgFecEmi = "<img src='" . $_IMG_BY_ORDER . "'>";          
          break;          
          
          case "per_desd_dte":
                $sClassDesde = "class='sort'";
                $sImgDesde = "<img src='" . $_IMG_BY_ORDER . "'>";          
          break;          
        
          case "per_hast_dte":
                $sClassHasta = "class='sort'";
                $sImgHasta = "<img src='" . $_IMG_BY_ORDER . "'>";          
          break;                  

          case "rut_rec_dte":
                $sClassRutRec = "class='sort'";
                $sImgRutRec = "<img src='" . $_IMG_BY_ORDER . "'>";          
          break;                  

          case "nom_rec_dte":
                $sClassNomRec = "class='sort'";
                $sImgNomRec = "<img src='" . $_IMG_BY_ORDER . "'>";          
          break;             
                           
          case "mntneto_dte":
                $sClassNeto = "class='sort'";
                $sImgNeto = "<img src='" . $_IMG_BY_ORDER . "'>";          
          break;                  

          case "mnt_exen_dte":
                $sClassExen = "class='sort'";
                $sImgExen = "<img src='" . $_IMG_BY_ORDER . "'>";          
          break;                       
      
          case "iva_dte":
                $sClassIva = "class='sort'";
                $sImgIva = "<img src='" . $_IMG_BY_ORDER . "'>";          
          break;            
           
          case "mont_tot_dte":
                $sClassTot = "class='sort'";
                $sImgTot = "<img src='" . $_IMG_BY_ORDER . "'>";          
          break;            

          case "tipo_docu":
                $sClassTipoDoc = "class='sort'";
                $sImgTipoDoc = "<img src='" . $_IMG_BY_ORDER . "'>";          
          break;            		  

          default :       
                $sClassFecEmi = "class='sort'";
                $sImgFecEmi = "<img src='" . $_IMG_BY_ORDER . "'>";          
          break;           
        }
          
?>            
       <table width="100%" cellspacing="0" class="list">
        <tr>
           <th width="20%" <?php echo $sClassTipoDoc; ?>>
                  <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=tipo_docu&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">
                    Tipo Documento</a><?php echo $sImgTipoDoc; ?>
          </th>

           <th width="10%" <?php echo $sClassFecEmi; ?>>
                  <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=fec_emi_doc&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">
                    Fecha Emisi&oacute;n</a><?php echo $sImgFecEmi; ?>
          </th>
           <th width="10%" <?php echo $sClassFecEmi; ?>>
                    Folio DTE
          </th>
 <!--         
          <th width="5%" <?php echo $sClassDesde; ?>>
                  <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=per_desd_dte&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">
                    Per. Desde</a><?php echo $sImgDesde; ?>
          </th>        
          <th width="30%" <?php echo $sClassHasta; ?>>
                  <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=per_hast_dte&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">
                    Per. Hasta</a><?php echo $sImgHasta; ?>
          </th> -->
           <th width="5%" <?php echo $sClassRutRec; ?>>
                  <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=rut_rec_dte&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">
                    Rut Emisor</a><?php echo $sImgRutRec; ?>
          </th>                                             
                  
           <th width="35%" <?php echo $sClassNomRec; ?>>
                  <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=nom_rec_dte&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">
                    Nombre Emisor</a><?php echo $sImgNomRec; ?>
          </th>                                             
           
           <th width="10%" <?php echo $sClassNeto; ?>>
                  <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=mntneto_dte&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">
                    Mnt Neto</a><?php echo $sImgNeto; ?>
          </th>                                                       

           <th width="10%" <?php echo $sClassExen; ?>>
                  <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=mnt_exen_dte&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">
                    Mnt Exento</a><?php echo $sImgExen; ?>
          </th>         
          
           <th width="10%" <?php echo $sClassIva; ?>>
                  <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=iva_dte&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">
                    Mnt Iva</a><?php echo $sImgIva; ?>
          </th>         
          
           <th width="10%" <?php echo $sClassTot; ?>>
                  <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=mont_tot_dte&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">
                    Mnt Total</a><?php echo $sImgTot; ?>
          </th>     
		  <th>PDF</th>          
 <th>XML</th>
           <th width="10%" <?php echo $sClassTot; ?>>
                  <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=est_doc&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">
                    Estado</a><?php echo $sImgTot; ?>
          </th>     

        </tr>




<?php 

/********************** LISTA USUARIOS ****************************************/
        $result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);        
        $sPaginaResult = sPagina($conn, $sql, $sLinkActual);        // string de paginacion
        
        $sClassRow = "evenrowbg";               // clase de la hoja de estilo 
        
        while (!$result->EOF) {
          $nCodDoc = trim($result->fields["correl_doc"]);
          $nFolio  = trim($result->fields["fact_ref"]);
          $dFecEmi = trim($result->fields["fec_emi_doc"]);
          $dDesd = trim($result->fields["per_desd_dte"]);
          $dHasta = trim($result->fields["per_hast_dte"]);
          $nRutEmi = trim($result->fields["rut_emis_dte"]);
          $sDvEmi = trim($result->fields["digi_emis_dte"]);
          $sNomEmi = trim($result->fields["nom_emis_dte"]);
          $sDirOrig = trim($result->fields["dir_orig_dte"]);
          $nRutRec = trim($result->fields["rut_rec_dte"]);
          $nDigRec = trim($result->fields["dig_rec_dte"]);
          $sNomRec = trim($result->fields["nom_rec_dte"]);
          $nMntNeto = trim($result->fields["mntneto_dte"]);
          $nMntExen = trim($result->fields["mnt_exen_dte"]);
          $nTasaIva = trim($result->fields["tasa_iva_dte"]);
          $nIva = trim($result->fields["iva_dte"]);
          $nMntTotal = trim($result->fields["mont_tot_dte"]);                    
          $sEstDte = trim($result->fields["est_xdte"]);
          $nTipDoc = trim($result->fields["tipo_docu"]);
          $nFacRef = trim($result->fields["fact_ref"]);
          $sDescDocu = trim($result->fields["desc_docu"]);
          $sEstado = trim($result->fields["est_doc"]);
		
		  $strParamLink = "nCodDoc=" . urlencode($nCodDoc);
          $strParamLink .= "&nRutClie=" . urlencode($nRutRec);
          $strParamLink .= "&nTipDoc=" . urlencode($nTipDoc);		  
          $strParamLink .= "&sAccion=M";      

//		  $_file_pddf = $_PATH_TERCEROS_PDF . "dte-$nTipDoc-$nFacRef.pdf";
$_file_pddf = trim($result->fields["path_pdf"]);


		  $sTemp = "";
		  if(is_file($_file_pddf))
			  $sTemp = "<a href=\"" . $_LINK_BASE . "dte/view_pdf_file.php?sUrlPdf=" . urlencode($_file_pddf) . "\">Ver</a>";

?>                       
                  
        <tr class="<?php echo $sClassRow; ?>">         
        
<!--          <td><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>factura/form_noelecompra.php?<?php echo $strParamLink; ?>';"><?php echo $dFecEmi; ?></a></td> -->
          <td><?php echo $sDescDocu; ?></td>
		  <td><?php echo $dFecEmi; ?></td>
		  <td><?php echo $nFolio; ?></td>
<!--          <td><?php echo $dDesd; ?></td>          
          <td><?php echo $dHasta; ?></td> -->
          <td><?php echo $nRutRec . "-" . $nDigRec; ?></td>
          <td><?php echo $sNomRec; ?></td>
          <td><?php echo $nMntNeto; ?></td>
          <td><?php echo $nMntExen; ?></td>
          <td><?php echo $nIva; ?></td>
          <td><?php echo $nMntTotal; ?></td>
		  <td><?php echo $sTemp; ?></td>
<td><?php echo "<a href=\"" . $_LINK_BASE . "dte/view_xmlrecibido.php?nFolioDte=" . $nFolio . "&nTipoDocu=" . $nTipDoc . "\">xml</a>"; ?></td>
		  <td>
		  <?php 
		  
			if(trim($sEstado) == "")
				echo cambiarEstado($nCodDoc, $nTipDoc);
			else
				echo ucfirst(strtolower($sEstado));
		  
		  ?>		  
		  </td>
        </tr>
                
<?php
          if($sClassRow == "oddrowbg")
            $sClassRow = "evenrowbg";
          else
            $sClassRow = "oddrowbg";
            
          $result->MoveNext();
        } 
        
/*********************** FIN LISTA CONTRATOS ***********************************/
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
