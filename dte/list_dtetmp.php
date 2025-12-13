<?php 
	include("../include/config.php");  
	include("../include/db_lib.php"); 
	include("../include/tables.php"); 

	include("../include/ver_aut.php");      
    include("../include/ver_emp_adm.php");        

  $conn = conn();
  $_EST_DTE = $_GET["_EST_DTE"];  
  $nTipoDocu = $_GET["nTipoDocu"];   
  $nAnio = $_GET["nAnio"];   
  $_OK = $_GET["OK"];
  $sLinkActual = "dte/list_dtetmp.php?_EST_DTE=" . $_EST_DTE . "&nTipoDocu=" . $nTipoDocu . "&nAnio=" . $nAnio . "&OK=" . $_OK . "&";  
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

		  <!-- calendar  -->
		  <link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
		  <script type="text/javascript" src="javascript/calendar.js"></script>
		  <script type="text/javascript" src="javascript/lang/calendar-es.js"></script>
		  <script type="text/javascript" src="javascript/calendar-setup.js"></script>
		  <!-- calendar fin -->

		<script type="text/javascript">
<!--
//lenvanta popup para la cesion de facturas
function ceder_documento(nFolioDte,nTipDoc,nMontTot,nCodEmp) {
   //var sUrl = "<?php echo $_LINK_BASE; ?>dte/form_reenvio.php?nFolio=" + nFolioDte + "&nTipoDTE=" + nTipDoc;
   var sUrl = "<?php echo $_LINK_BASE; ?>dte/ceder_documento.php?nFolio=" + nFolioDte + "&nTipoDTE=" + nTipDoc + "&nMontTot="+nMontTot+"&nCodEmp="+nCodEmp;
    wTipoMot=window.open(sUrl, "reenviar","dependent=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,width=1000,height=800");
        var centroAncho = (screen.width/2)  - (1000);
        var centroAlto  = (screen.height/2) - (400);
        wTipoMot.moveTo(centroAlto,centroAncho);
}

function Reenviar(nFolioDte,nTipDoc) {
   var sUrl = "<?php echo $_LINK_BASE; ?>dte/form_reenvio.php?nFolio=" + nFolioDte + "&nTipoDTE=" + nTipDoc;
    wTipoMot=window.open(sUrl, "reenviar","dependent=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,width=400,height=400");
        var centroAncho = (screen.width/2)  - (400);
        var centroAlto  = (screen.height/2) - (200);
        wTipoMot.moveTo(centroAlto,centroAncho);
}




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
        if(confirm(_MSG_DEL_CONFIR))
          document._FDEL.submit();
      }
      else
        alert(_MSG_DTE_DEL);
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

    function chListBoxEstado(){
        var F = document._FSEARCH;
        for(i=0; i < F._EST_DTE.length; i++){
          if(F._EST_DTE.options[i].value == "<?php echo $_EST_DTE; ?>")
            F._EST_DTE.options[i].selected = true;
        }
      }

	function muestraDivCampos(){ 
		var F = document._FSEARCH;
		var opt = F._COLUM_SEARCH.options[F._COLUM_SEARCH.selectedIndex].value;

		var div1=document.getElementById('fechaEmisionFin');
		var div2=document.getElementById('noFechaEmisionFin');
//		noFechaEmisionFin
		
		if (opt=="D.fec_emi_dte"){
			div1.style.display = 'block';
			div2.style.display = 'none';			
		}
		else{
			div1.style.display = 'none';
			div2.style.display = 'block';		
		}
	
	}
    
//-->
		</script>
	</head>
	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

	<a href="#" name="top" id="top"></a>

	<?php sTituloCabecera("Empresa"); ?>
	<?php // sAgregaHerramienta("screenClientList", "Herramientas", $aBotonEmpHerramienta); ?>

	<div class="screenBody">
		<div class="listArea">
			<fieldset>
				<legend>DTE</legend>
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td width="250" nowrap>
							<table width="100%" cellspacing="0" class="buttons"><tr>
								<td class="main" >
                    <form name="_FSEARCH" method="get" action="<?php echo $_LINK_BASE . $sLinkActual; ?>">
                   <!-- <INPUT type="hidden" name="_EST_DTE" value="<?php echo $_EST_DTE; ?>"> -->
<INPUT type="hidden" name="OK" value="OK">           
					<select name="nTipoDocu">
                      <option value="">Tipo Documento (Todos)</option>
<?php 
					$sql = "SELECT tipo_docu, desc_tipo_docu FROM dte_tipo ORDER BY desc_tipo_docu";
					$result = rCursor($conn, $sql);
					while (!$result->EOF) {
						$nTipoDocuTmp = trim($result->fields["tipo_docu"]);
						$sDescTipoDocu = trim($result->fields["desc_tipo_docu"]);

						if(trim($nTipoDocuTmp) == trim($nTipoDocu))
							echo "<option value='" . $nTipoDocuTmp . "' selected>" . $sDescTipoDocu . "</option> \n";
						else
							echo "<option value='" . $nTipoDocuTmp . "'>" . $sDescTipoDocu . "</option> \n";

						$result->MoveNext();
					} 
?>
                    </select>
                    <select name="nAnio">
						<option value="">A&ntilde;o (Todos)</option>
<?php 
/*					$sql = "SELECT MIN(TO_CHAR(TO_DATE(fec_emi_dte, 'YYYY-MM-DD'), 'YYYY')) AS anio_menor, MAX(TO_CHAR(TO_DATE(fec_emi_dte, 'YYYY-MM-DD'), 'YYYY'))  AS anio_mayor FROM dte_enc ";
					$result = rCursor($conn, $sql);
					if(!$result->EOF){
						$nAnioMenor = trim($result->fields["anio_menor"]);
						$nAnioMayor = trim($result->fields["anio_mayor"]);
					}
					else{
						$nAnioMenor = date(Y);
						$nAnioMayor = date(Y);
					}
*/
                                                $nAnioMenor = 2005;
                                                $nAnioMayor = date(Y);  

					for($i=$nAnioMayor; $i >= $nAnioMenor; $i--){
						if(trim($i) == trim($nAnio))
							echo "<option value='" . $i . "' selected>" . $i . "</option> \n";
						else
							echo "<option value='" . $i . "'>" . $i . "</option> \n";
					
					}

?>
					</select>
                    <select name="_EST_DTE">
					  <option value="">Estado Todos</option>                      
                      <option value="0">DTE Cargados</option>
                      <option value="1">DTE Firmados</option>                      
                      <option value="3">DTE Con ERROR</option>                    
                      <option value="5">DTE Empaquetados</option>                    
                      <option value="13">DTE Enviados SII</option>
                      <option value="29">DTE Aceptados SII</option>
                      <option value="45">DTE Con Reparos SII</option>
                      <option value="77">DTE Rechazados SII</option>
                      <option value="157">DTE Enviados a Clientes</option>
                      <option value="173">DTE Con Reparos Enviados a Clientes</option>
                      <option value="413">DTE Aceptados por Clientes</option>
                      <option value="429">DTE Con Reparos Aceptados por Clientes</option>
                      <option value="1024">Rechazo Comercial (por cliente)</option> 
                    </select>
 				<script> chListBoxEstado(); </script>
  
                    
                    <select name="_COLUM_SEARCH" onChange="muestraDivCampos();">
                      <option value="D.folio_dte">Folio Dte</option>
                      <option value="D.fec_emi_dte">Fecha Emisi&oacute;n</option>                      
                    <!--  <option value="D.fec_venc_dte">Fecha Vencimiento</option>                    -->
                      <option value="D.nom_rec_dte">Razon Social Receptor</option>                   <!-- 
                      <option value="D.giro_rec_dte">Giro Receptor</option>                    -->
                    </select>
                    <script> chListBoxSearch(); </script>       
			<div id="noFechaEmisionFin" style="display:none">                    
					<input type="text" name="_STRING_SEARCH" id="searchInput" value="<?php echo $_STRING_SEARCH; ?>" size="20" maxlength="245">
					<img src="skins/aqua/images/btn_search_bg.gif" border="0" onclick="document._FSEARCH.submit();" alignth="right">					
			</div>

	<div id="fechaEmisionFin" style="display:none">
		<input type="text" id="_STRING_SEARCH0" name="_STRING_SEARCH0" id="searchInput" onFocus="this.blur();" value="<?php echo $_STRING_SEARCH0; ?>" size="20" maxlength="245">
		<img src="img.gif" id="f_trigger_ini" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" / >		
		<script type="text/javascript">
			Calendar.setup({
				inputField     :    "_STRING_SEARCH0",     // id of the input field
				ifFormat       :    "%Y/%m/%d",      // format of the input field
				button         :    "f_trigger_ini",  // trigger for the calendar (button ID)
				align          :    "Tl",           // alignment (defaults to "Bl")
				singleClick    :    true
			});
		</script>
		<input type="text" id="_STRING_SEARCH2" name="_STRING_SEARCH2" id="searchInput" onFocus="this.blur();" value="<?php echo $_STRING_SEARCH2; ?>" size="20" maxlength="245">
		<img src="img.gif" id="f_trigger_ter" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" / >		
		<script type="text/javascript">
			Calendar.setup({
				inputField     :    "_STRING_SEARCH2",     // id of the input field
				ifFormat       :    "%Y/%m/%d",      // format of the input field
				button         :    "f_trigger_ter",  // trigger for the calendar (button ID)
				align          :    "Tl",           // alignment (defaults to "Bl")
				singleClick    :    true
			});
		</script>
		<img src="skins/aqua/images/btn_search_bg.gif" border="0" onclick="document._FSEARCH.submit();" alignth="right">
	</div>										
	<script>muestraDivCampos();</script>

<!-- 
										<div class="commonButton" id="bid-search" title="Buscar"  name="bid-search">
											<button name="bname_search" onclick="document._FSEARCH.submit();">Buscar</button><span>Buscar</span>
										</div> 
<br>  -->
<!--										<div class="commonButton" id="bid-show-all" title="Mostrar todo" name="bid-show-all">
											<button name="bname_show_all">Mostrar todo</button><span>Mostrar todo</span>
										</div> -->
                    </form>
					
								</td>

                <td class="misc">

				<?php //	if($_EST_DTE == "0" || $_EST_DTE == "1" || $_EST_DTE == "3") { ?>
									<div>
										<div class="commonButton">&nbsp;</div>

										<div class="commonButton" id="bid-remove-selected" title="Eliminar seleccion"  name="bid-remove-selected">
											<button name="bname_remove_selected" onclick="chDelEmp();">Eliminar seleccion</button><span>Eliminar seleccion</span>
										</div>
									</div> 
				<?php // } ?>
								</td>
							  </tr>
							</table>
<?php

if ($_OK != "OK"){
	echo "<h2>Debe seleccionar un filtro</h2>";
	exit;
}
else{
	if(trim($_STRING_SEARCH) == "" && trim($_EST_DTE) == "" && trim($nTipoDocu) == "" && trim($nAnio) == "" && trim($_STRING_SEARCH0) == "" && trim($_STRING_SEARCH2) == ""){
	        echo "<h2>Debe seleccionar un filtro</h2>";
        	exit; 
	}
}
?>
            <form name="_FDEL" method="post" action="dte/pro_dte.php">
              <input type="hidden" name="sAccion" value="E">
							<table width="100%" cellspacing="0" class="list">
<?php 
                
  $sql = "  SELECT  
                D.codi_empr,
                D.tipo_docu,
                D.folio_dte,
                D.fec_emi_dte,
                D.fec_venc_dte,
                D.rut_emis_dte,
                D.digi_emis_dte,
                D.nom_emis_dte,
                D.giro_emis_dte,
                D.dir_orig_dte,
                D.com_orig_dte,
                D.ciud_orig_dte,
                D.rut_rec_dte,
                D.dig_rec_dte,
                D.nom_rec_dte,
                D.giro_rec_dte,
                D.dir_rec_dte,
                D.com_rec_dte,
                D.ciud_rec_dte,
                D.mntneto_dte,
                D.tasa_iva_dte,
                D.iva_dte,
                D.mont_tot_dte,
                D.valo_pag_dte,
                D.fech_carg,
                DT.desc_tipo_docu ,
                X.path_pdf,
                X.path_pdf_cedible,
                X.est_xdte,
                '<a href=\"dte/view_pdf.php?sUrlPdf=' || X.path_pdf || '\">PDF</a>' as pdf,
                '<a href=\"dte/view_pdf.php?sUrlPdf=' || X.path_pdf_cedible || '\">PDF Cedible</a>' as pdf_cedible,
                '<a href=\"dte/view_xml.php?nFolioDte=' || D.folio_dte || '&nTipoDocu=' || D.tipo_docu || '\">Ver</a>' as xml,
				(SELECT trackid_xed FROM xmlenviodte WHERE codi_empr = X.codi_empr AND num_xed = X.num_xed) AS track_id
            FROM 
                dte_enc D,
                dte_tipo DT,
                xmldte X 
            WHERE  
                D.codi_empr = X.codi_empr AND
                DT.tipo_docu = D.tipo_docu AND
                D.folio_dte = X.folio_dte AND 
                D.tipo_docu = X.tipo_docu AND 
				X.codi_empr = '". trim($_SESSION["_COD_EMP_USU_SESS"]) . "'
				";
					// ";	//(est_xdte&" . $_EST_DTE . ") = " . $_EST_DTE . ")";
					// (SELECT path_pdf FROM xmldte WHERE folio_dte = D.folio_dte AND tipo_docu = D.tipo_docu) as path_pdf
					// IN (SELECT folio_dte FROM xmldte WHERE est_xdte = " . $_EST_DTE . " AND xmldte.tipo_docu = D.tipo_docu) 
					
		if($_EST_DTE != "")
			$sql .= " AND X.est_xdte = $_EST_DTE ";
  
  		if($nTipoDocu != "")
			$sql .= " AND D.tipo_docu = $nTipoDocu ";
        
		if($nAnio != "")
			$sql .= " AND to_char(to_date(D.fec_emi_dte, 'YYYY-MM-DD'), 'YYYY') = '" . $nAnio . "' ";
		
        if($_COLUM_SEARCH == "D.fec_emi_dte"){
        	if($_STRING_SEARCH0 != "" && $_STRING_SEARCH2 != "")
				$sql .= " AND TO_DATE(D.fec_emi_dte,'YYYY/MM/DD') BETWEEN ('" . str_replace("'","''",$_STRING_SEARCH0) . "') AND ('" . str_replace("'","''",$_STRING_SEARCH2) . "') ";
        }
	if($_COLUM_SEARCH == "D.folio_dte" && trim($_STRING_SEARCH) != "")
		$sql .= " AND " . $_COLUM_SEARCH  . " = '" . str_replace("'","''",trim($_STRING_SEARCH)) . "' ";

        if($_COLUM_SEARCH == "D.fec_venc_dte"){
                if($_STRING_SEARCH0 != "" && $_STRING_SEARCH2 != "")
                                $sql .= " AND TO_DATE(D.fec_emi_dte,'YYYY/MM/DD') BETWEEN ('" . str_replace("'","''",$_STRING_SEARCH0) . "') AND ('" . str_replace("'","''",$_STRING_SEARCH2) . "') ";
        } 

if(($_COLUM_SEARCH == "D.nom_rec_dte" || $_COLUM_SEARCH == "D.giro_rec_dte") && trim($_STRING_SEARCH) != "")
		$sql .= " AND UPPER(" . $_COLUM_SEARCH  . ") LIKE UPPER('" . str_replace("'","''",$_STRING_SEARCH) . "%') ";

/*		else
		  if($_STRING_SEARCH != "")
        	  $sql .= " AND UPPER(" . $_COLUM_SEARCH  . ") LIKE UPPER('" . str_replace("'","''",$_STRING_SEARCH) . "%') ";
  */
          
          
        if(trim($_ORDER_BY_COLUM) == "")
          $sql .= " ORDER BY D.fec_emi_dte DESC, D.folio_dte DESC ";
        else			
          $sql .= " ORDER BY " . $_ORDER_BY_COLUM . "  $_NIVEL_BY_ORDER ";          
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
				  <th <?php echo $sClassFol; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=D.folio_dte&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH0=<?php echo $_STRING_SEARCH0; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_STRING_SEARCH2=<?php echo $_STRING_SEARCH2; ?>&_ORDER_CAMBIA=Y&_EST_DTE=<?php echo $_EST_DTE; ?>';">Folio</a><?php echo $sImgFol; ?></th>
				  <th>TrackID.</th>
				  <th>Estado.</th>
                  <th <?php echo $sClassTD; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=DT.desc_tipo_docu&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH0=<?php echo $_STRING_SEARCH0; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_STRING_SEARCH2=<?php echo $_STRING_SEARCH2; ?>&_ORDER_CAMBIA=Y&_EST_DTE=<?php echo $_EST_DTE; ?>';">Tipo de Doc.</a><?php echo $sImgTD; ?></th>									
                  <th <?php echo $sClassFED; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=D.fec_emi_dte&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH0=<?php echo $_STRING_SEARCH0; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_STRING_SEARCH2=<?php echo $_STRING_SEARCH2; ?>&_ORDER_CAMBIA=Y&_EST_DTE=<?php echo $_EST_DTE; ?>';">Fecha Emis.</a><?php echo $sImgFED; ?></th>
	<!--								<th <?php echo $sClassFVC; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=D.fec_venc_dte&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH0=<?php echo $_STRING_SEARCH0; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_STRING_SEARCH2=<?php echo $_STRING_SEARCH2; ?>&_ORDER_CAMBIA=Y&_EST_DTE=<?php echo $_EST_DTE; ?>';">Fecha Venc.</a><?php echo $sImgFVC; ?></th>								
									<th>Rut Emis.</th>								
									<th>Nom Emis.</th>								
									<th>Giro Emis.</th>								
									<th>Dir Origen</th>								
									<th>Comuna Origen</th>								                                                                        
									<th>Ciudad Origen</th>								                                                                                          
	-->								<th>Rut Receptor</th>								                                                                                          									
									<th>Nombre Receptor</th>		
	<!--								<th <?php echo $sClassGR; ?>><a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>&_ORDER_BY_COLUM=D.giro_rec_dte&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH0=<?php echo $_STRING_SEARCH0; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_STRING_SEARCH2=<?php echo $_STRING_SEARCH2; ?>&_ORDER_CAMBIA=Y&_EST_DTE=<?php echo $_EST_DTE; ?>';">Giro Receptor</a><?php echo $sImgGR; ?></th>								
								<th>Dir. Receptor</th>											
									<th>Comuna Receptor</th>								                                                                                          									                  
									<th>Ciudad Receptor</th>								                                                                                          									
	-->								<th>Neto</th>								                                                                                          									
									<th>Iva</th>								                                                                                          									                  
									<th>Total</th>				
									<th>PDF</th>
									<th>PDF Cedible</th>
                  <th>Ceder DTE</th>
									<th>XML</th>
									<th>Re-Enviar</th>


<!--  
				<?php	if($_EST_DTE == "29" || $_EST_DTE == "1"  || $_EST_DTE == "13" || $_EST_DTE == "45" || $_EST_DTE == "157") { ?>						
									<th>Ver</th>			
									 <th>XML</th>  	
				<?php   } ?>

				<?php	if($_EST_DTE == "0" || $_EST_DTE == "1" || $_EST_DTE == "3" || $_EST_DTE == "77" ) { ?>						
									<th width="0" class="select"><input type="checkbox" class="checkbox" name="clientslistSelectAll" value="true" onClick="chDchALL();"></th> 
				<?php   } ?> -->
									<th width="0" class="select"><input type="checkbox" class="checkbox" name="clientslistSelectAll" value="true" onClick="chDchALL();"></th> 
	
								</tr>
                
  
                
<?php 
/***********************************************************/
        $result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);        
        $sPaginaResult = sPagina($conn, $sql, $sLinkActual);        // string de paginacion
        
        $sClassRow = "evenrowbg";                                   // clase de la hoja de estilo 
        
        while (!$result->EOF) {


          $nCodEmp = trim($result->fields["codi_empr"]);
          $nTipDoc = trim($result->fields["tipo_docu"]);
          $nTrackID = trim($result->fields["track_id"]);
		  $nFolioDte = trim($result->fields["folio_dte"]);
          $dFecEmiDte = trim($result->fields["fec_emi_dte"]);
          $dFecVenDte = trim($result->fields["fec_venc_dte"]);
          $nRutEmis = trim($result->fields["rut_emis_dte"]);
          $sDigEmis = trim($result->fields["digi_emis_dte"]);
          $sNomEmis = trim($result->fields["nom_emis_dte"]);
          $sGiroEmis = trim($result->fields["giro_emis_dte"]);
          $sDirOrig = trim($result->fields["dir_orig_dte"]);
          $sComOrig = trim($result->fields["com_orig_dte"]);
          $sCiudOrig = trim($result->fields["ciud_orig_dte"]);                                                                                                              
          $nRutRec = trim($result->fields["rut_rec_dte"]);                                                                                                              
          $sDigRec = trim($result->fields["dig_rec_dte"]);                                                                                                              
          $sNomRec = trim($result->fields["nom_rec_dte"]);                                                                                                              
          $sGiroRec = trim($result->fields["giro_rec_dte"]);                                                                                                              
          $sDirRec = trim($result->fields["dir_rec_dte"]);                                                                                                              
          $sComRec = trim($result->fields["com_rec_dte"]);                                                                                                              
          $sCiudRec = trim($result->fields["ciud_rec_dte"]);                                                                                                              
          $nMntNeto = trim($result->fields["mntneto_dte"]);                                                                                                                                                                                              
          $nIvaDte = trim($result->fields["iva_dte"]);                                                                                                                                                                                              
          $nMontTot = trim($result->fields["mont_tot_dte"]);                                                                                                                                                                                              
          $nValorPag = trim($result->fields["valo_pag_dte"]);                                                                                                                                                                                                                                      
          $dFecCarg = trim($result->fields["fech_carg"]);                                                                                                                                                                                                                                      
          $sDescTipoDoc = trim($result->fields["desc_tipo_docu"]);                                                                                                                                                                                                                                                          
          $sUrlPdf = trim($result->fields["path_pdf"]);     
          $sUrlPdfCedible = trim($result->fields["path_pdf_cedible"]);     
		  
$link_pdf_b=$_LINK_BASE . "dte/view_pdf_rem.php?c=".$nCodEmp."&f=".$nFolioDte."&t=".$nTipDoc;
                     

          $nEstadoDte = trim($result->fields["est_xdte"]);
		$linkXml = trim($result->fields["xml"]);
		$linkPDF = trim($result->fields["pdf"]);
		$linkPDFCedible = trim($result->fields["pdf_cedible"]);

        switch ($nEstadoDte) {
		    case 0:
		        $sEstadoDte = "Cargado";
		        break;
		    case 1:
		        $sEstadoDte = "Firmado";
		        break;
		    case 3:
		        $sEstadoDte = "Con ERROR";
		        break;
		    case 5:
		        $sEstadoDte = "Empaquetado";
		        break;
		    case 13:
		        $sEstadoDte = "Enviado SII";
		        break;		
		    case 29:
		        $sEstadoDte = "Aceptado SII";
		        break;
		    case 45:
		        $sEstadoDte = "Con Reparo SII";
		        break;
		    case 77:
		        $sEstadoDte = "Rechazado SII";
		        break; 
		    case 157:
		        $sEstadoDte = "Enviado a Cliente";
		        break;
		    case 173:
		        $sEstadoDte = "Con Reparo Enviado a Cliente";
		        break;
			case 413:
		        $sEstadoDte = "Aceptado por Cliente";
		        break;
                        case 429:
                        $sEstadoDte = "Con Reparo Aceptado por Cliente";
                        break;
                        case 1024:
                        $sEstadoDte = "Rechazo Comercial (por Cliente)";
                        break;

		}                      
?>																												
								<tr class="<?php echo $sClassRow; ?>">
									<td><?php echo $nFolioDte; ?></td>
									<td><?php echo $nTrackID; ?></td>
									<td><?php echo $sEstadoDte; ?></td>	
									<td><?php echo $sDescTipoDoc; ?></td>
									<td><?php echo $dFecEmiDte; ?></td>
		<!--							<td><?php echo $dFecVenDte; ?></td>
									<td><?php echo $nRutEmis . "-" . $sDigEmis; ?></td>
									<td><?php echo $sNomEmis; ?></td>
									<td><?php echo $sGiroEmis; ?></td>
									<td><?php echo $sDirOrig; ?></td>                                                                                                                              
									<td><?php echo $sComOrig; ?></td>                                                                                                                              
									<td><?php echo $sCiudOrig; ?></td>                                                                                                                              
		-->							<td><?php echo $nRutRec . "-" . $sDigRec; ?></td>                                                                                                                              
									<td><?php echo $sNomRec; ?></td>                                                                                                                              
		<!--							<td><?php echo $sGiroRec; ?></td>                                                                                                                              
									<td><?php echo $sDirRec; ?></td>                                                                                                                              
									<td><?php echo $sComRec; ?></td>                                                                                                                                                
									<td><?php echo $sCiudRec; ?></td>     -->
									<td>$<?php echo number_format($nMntNeto,0,',','.'); ?></td>                                                                                                                              
									<td>$<?php echo number_format($nIvaDte,0,',','.'); ?></td>                                                                                                                              
									<td>$<?php echo number_format($nMontTot,0,',','.'); ?></td>                                                                                                                              


				<?php	
					//if($nEstadoDte == "29" || $nEstadoDte == "1"  || $nEstadoDte == "13" || $nEstadoDte == "45" || $nEstadoDte == "157") { 
				//			if(is_file($sUrlPdf)){
				?>				
<td><a href="<?php echo $link_pdf_b; ?>" target="_blank">PDF</a></td> 

<!-- <td><a href="<?php echo $_LINK_BASE; ?>dte/view_pdf.php?sUrlPdf=<?php echo urlencode($sUrlPdf); ?>">PDF</a></td> -->	
								<?php	

								if($nTipDoc == "33" || $nTipDoc == "34" || $nTipDoc == "46" || $nTipDoc == "52" || $nTipDoc == "110") { ?>
			<td><a href="<?php echo $link_pdf_b; ?>&cd=true" target="_blank">PDF Cedible</a></td>

<!--								<td><a href="<?php echo $_LINK_BASE; ?>dte/view_pdf.php?sUrlPdf=<?php echo urlencode($sUrlPdfCedible); ?>">PDF Cedible</a></td>-->
								<?php	} 
										else 
											echo "<td>No Aplica</td>";
								?>
					
                  <td>
                  <?php
                  //verificamos el estado del documento
                  if($nEstadoDte >= 29 && $nEstadoDTE != 77)
                    //llamamos al formulario de cesion 
                    echo "<a href=\"javascript:ceder_documento('" . $nFolioDte . "','" . $nTipDoc ."','" . $nMontTot . "','" . $nCodEmp . "');\">Ceder DTE</a>";
                  else
                    echo "&nbsp;";
                  ?>
                </td>
<!--<td><a href="<?php echo $_LINK_BASE; ?>dte/view_xml.php?nFolioDte=<?php echo urlencode($nFolioDte); ?>&nTipoDocu=<?php echo urlencode($nTipDoc); ?>">XML</a></td>-->

				<?php		//} 
						//	else{
				?>
						<!--			<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>																																	  -->
				<?php	//	} 
					//	}
				?>                  
                  		<td><?php echo $linkXml; ?></td>
						
								<?php	if($nEstadoDte == "0" || $nEstadoDte == "1" || $nEstadoDte == "3" || $nEstadoDte == "77" ) { ?>
									<td>&nbsp;</td><td class="select"><input type="checkbox" class="checkbox" name="del[]" value="<?php echo $nFolioDte . "|" . $nTipDoc; ?>"></td> 
								<?php	}
if($nEstadoDte > 28)
echo "<td><a href=\"javascript:Reenviar('" . $nFolioDte . "','" . $nTipDoc ."');\">Re-Enviar</a></td>";
else
	echo "<td>&nbsp;</td>";




	?>
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
                   </form>
			</fieldset>
		</div>
	</div>
 	
 </body>
</html>
