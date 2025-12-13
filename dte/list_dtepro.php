<?php 
	include("../include/config.php");  
	include("../include/db_lib.php"); 
	include("../include/tables.php"); 
 
  $conn = conn();
  $sBuscar = $_GET["_Buscar"];
 session_start();
  if($sBuscar == "OK"){
	  $_SESSION["_EST_DTES"] = trim($_GET["_EST_DTE"]);  
	  $_SESSION["nTipoDocuS"] = trim($_GET["nTipoDocu"]);   
	  $_SESSION["nAnioS"] = trim($_GET["nAnio"]);
	  $_SESSION["_COLUM_SEARCHS"] = trim($_GET["_COLUM_SEARCH"]);
	  $_SESSION["_STRING_SEARCHS"] = trim($_GET["_STRING_SEARCH"]);         // STRING SEARCH
	  $_SESSION["_STRING_SEARCH0S"] = trim($_GET["_STRING_SEARCH0"]);         // STRING SEARCH
	  $_SESSION["_STRING_SEARCH2S"] = trim($_GET["_STRING_SEARCH2"]);         // STRING SEARCH

	  $_EST_DTE = $_SESSION["_EST_DTES"];  
	  $nTipoDocu = $_SESSION["nTipoDocuS"];   
	  $nAnio = $_SESSION["nAnioS"];
	  $_COLUM_SEARCH = $_SESSION["_COLUM_SEARCHS"];
	  $_STRING_SEARCH = $_SESSION["_STRING_SEARCHS"];         // STRING SEARCH
	  $_STRING_SEARCH0 = $_SESSION["_STRING_SEARCH0S"];         // STRING SEARCH
	  $_STRING_SEARCH2 = $_SESSION["_STRING_SEARCH2S"];         // STRING SEARCH
  }
  else{
	  $_EST_DTE = $_SESSION["_EST_DTES"];  
	  $nTipoDocu = $_SESSION["nTipoDocuS"];   
	  $nAnio = $_SESSION["nAnioS"];
	  $_COLUM_SEARCH = $_SESSION["_COLUM_SEARCHS"];
	  $_STRING_SEARCH = $_SESSION["_STRING_SEARCHS"];         // STRING SEARCH
	  $_STRING_SEARCH0 = $_SESSION["_STRING_SEARCH0S"];         // STRING SEARCH
	  $_STRING_SEARCH2 = $_SESSION["_STRING_SEARCH2S"];         // STRING SEARCH
  }
  
  $sLinkActual = "dte/list_dtepro.php?_EST_DTE=" . $_EST_DTE . "&nTipoDocu=" . $nTipoDocu . "&nAnio=" . $nAnio . "&";  
  include("include/phpgrid.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>OpenB</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>


		<script language="javascript" type="text/javascript" src="<?php echo $_LINK_BASE; ?>javascript/common.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $_LINK_BASE; ?>javascript/msg.js"></script>

		<link rel="stylesheet" type="text/css" href="<?php echo $_LINK_BASE; ?>skins/<?php echo $_SKINS; ?>/css/general.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $_LINK_BASE; ?>skins/<?php echo $_SKINS; ?>/css/main/custom.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $_LINK_BASE; ?>skins/<?php echo $_SKINS; ?>/css/main/layout.css">
		<link rel="stylesheet" type="text/nonsense" href="<?php echo $_LINK_BASE; ?>skins/<?php echo $_SKINS; ?>/css/misc.css">

		  <!-- calendar  -->
		  <link rel="stylesheet" type="text/css" media="all" href="<?php echo $_LINK_BASE; ?>css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
		  <script type="text/javascript" src="<?php echo $_LINK_BASE; ?>javascript/calendar.js"></script>
		  <script type="text/javascript" src="<?php echo $_LINK_BASE; ?>javascript/lang/calendar-es.js"></script>
		  <script type="text/javascript" src="<?php echo $_LINK_BASE; ?>javascript/calendar-setup.js"></script>
		  <!-- calendar fin -->

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
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="<?php echo $_LINK_BASE; ?>skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

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
						<INPUT type="hidden" name="_Buscar" value="OK">
           
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
					$sql = "SELECT MIN(TO_CHAR(TO_DATE(fec_emi_dte, 'YYYY-MM-DD'), 'YYYY')) AS anio_menor, MAX(TO_CHAR(TO_DATE(fec_emi_dte, 'YYYY-MM-DD'), 'YYYY'))  AS anio_mayor FROM dte_enc ";
					$result = rCursor($conn, $sql);
					if(!$result->EOF){
						$nAnioMenor = trim($result->fields["anio_menor"]);
						$nAnioMayor = trim($result->fields["anio_mayor"]);
					}
					else{
						$nAnioMenor = date(Y);
						$nAnioMayor = date(Y);
					}


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
                      <option value="413">DTE Aceptados por Clientes</option>
                    </select>
 				<script> chListBoxEstado(); </script>

                    
                    <select name="_COLUM_SEARCH" onChange="muestraDivCampos();">
                      <option value="D.folio_dte">Folio Dte</option>
                      <option value="D.fec_emi_dte">Fecha Emisi&oacute;n</option>                      
                      <option value="D.fec_venc_dte">Fecha Vencimiento</option>                    
                      <option value="D.nom_rec_dte">Razon Social Receptor</option>                    
                      <option value="D.giro_rec_dte">Giro Receptor</option>                    
                    </select>
                    <script> chListBoxSearch(); </script>       
			<div id="noFechaEmisionFin" style="display:none">                    
					<input type="text" name="_STRING_SEARCH" id="searchInput" value="<?php echo $_STRING_SEARCH; ?>" size="20" maxlength="245">
					<img src="<?php echo $_LINK_BASE; ?>skins/aqua/images/btn_search_bg.gif" border="0" onclick="document._FSEARCH.submit();" alignth="right">					
			</div>

	<div id="fechaEmisionFin" style="display:none">
		<input type="text" id="_STRING_SEARCH0" name="_STRING_SEARCH0" id="searchInput" onFocus="this.blur();" value="<?php echo $_STRING_SEARCH0; ?>" size="20" maxlength="245">
		<img src="<?php echo $_LINK_BASE; ?>img.gif" id="f_trigger_ini" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" / >		
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
		<img src="<?php echo $_LINK_BASE; ?>img.gif" id="f_trigger_ter" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" / >		
		<script type="text/javascript">
			Calendar.setup({
				inputField     :    "_STRING_SEARCH2",     // id of the input field
				ifFormat       :    "%Y/%m/%d",      // format of the input field
				button         :    "f_trigger_ter",  // trigger for the calendar (button ID)
				align          :    "Tl",           // alignment (defaults to "Bl")
				singleClick    :    true
			});
		</script>
		<img src="<?php echo $_LINK_BASE; ?>skins/aqua/images/btn_search_bg.gif" border="0" onclick="document._FSEARCH.submit();" alignth="right">
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
								</td>
							  </tr>
							</table>
              
            <form name="_FDEL" method="post" action="dte/pro_dte.php">
              <input type="hidden" name="sAccion" value="E">
							<table width="100%" cellspacing="0" class="list">
<?php 
                


	$hostName = $_SERVER_DB;
	$userName = $_USER_DB;
	$password = $_PASS_DB;
	$dbName	  = $_DATABASE;
	$dbType = "postgres";
		
		$sql = "SELECT 
			to_char(to_date(DE.fec_emi_dte, 'YYYY-MM-DD'),'YYYY') AS anio, 
			mes_nombre(to_char(to_date(DE.fec_emi_dte, 'YYYY-MM-DD'),'MM')) AS periodo,
			DE.tipo_docu AS tipo,
			TD.desc_tipo_docu AS desc_tipo,
			DE.folio_dte AS folio,
			to_char(to_date(DE.fec_emi_dte, 'YYYY-MM-DD'),'DD-MM-YYYY') AS fecha_doc,
			dte_estado(XD.est_xdte) AS estado,	
			DE.rut_rec_dte || '-' || DE.dig_rec_dte AS rut_rece,
			DE.nom_rec_dte AS nom_rece,
			DE.mntneto_dte AS neto,
			DE.mnt_exen_dte AS exento,
			DE.iva_dte AS iva, 
			DE.mont_tot_dte AS total,

			CASE 
				WHEN CE.rut_contr IS NOT NULL THEN 'Si'
			ELSE
				'No'
			END AS rece_elec
		FROM 
			dte_enc DE LEFT JOIN contrib_elec CE ON CE.rut_contr = DE.rut_rec_dte, 
			dte_tipo TD, 
			xmldte XD 
		WHERE 
			DE.tipo_docu = TD.tipo_docu AND 
			XD.tipo_docu = DE.tipo_docu AND 
			XD.folio_dte = DE.folio_dte AND 
			XD.codi_empr = DE.codi_empr ";

		if($_EST_DTE != "")
			$sql .= " AND XD.est_xdte = $_EST_DTE ";
  
  		if($nTipoDocu != "")
			$sql .= " AND DE.tipo_docu = $nTipoDocu ";
        
		if($nAnio != "")
			$sql .= " AND to_char(to_date(DE.fec_emi_dte, 'YYYY-MM-DD'), 'YYYY') = '" . $nAnio . "' ";
		
        if($_COLUM_SEARCH == "DE.fec_emi_dte"){
        	if($_STRING_SEARCH0 != "" && $_STRING_SEARCH2 != "")
				$sql .= " AND TO_DATE(DE.fec_emi_dte,'YYYY/MM/DD') BETWEEN ('" . str_replace("'","''",$_STRING_SEARCH0) . "') AND ('" . str_replace("'","''",$_STRING_SEARCH2) . "') ";
        }
		else
		  if($_STRING_SEARCH != "")
        	  $sql .= " AND UPPER(" . $_COLUM_SEARCH  . ") LIKE UPPER('" . str_replace("'","''",$_STRING_SEARCH) . "%') ";
 
		$dg = new C_DataGrid($hostName, $userName, $password, $dbName, $dbType);
		 
		$dg -> set_gridpath     ($_LINK_BASE . "dte/include/");
		$dg -> set_sql          ($sql);
		//$dg -> set_sql_table    ("xmldte");
		//$dg -> set_sql_key      ("folio_dte");
		$dg -> set_page_size(15);
		$dg -> set_allow_export(true);
		$dg -> set_col_title("anio", "Año");
		$dg -> set_col_title("periodo", "Periodo");
		$dg -> set_col_title("tipo", "Tipo");
		$dg -> set_col_title("folio", "Folio");
		$dg -> set_col_title("estado", "Estado");
		$dg -> set_col_title("neto", "Neto");
		$dg -> set_col_title("exento", "Exento");
		$dg -> set_col_title("iva", "Iva");
		$dg -> set_col_title("total", "Total");
		$dg -> set_col_title("desc_tipo", "Descripción de Tipo");
		$dg -> set_col_title("fecha_doc", "Fecha Doc.");
		$dg -> set_col_title("rut_rece", "Rut Receptor");
		$dg -> set_col_title("nom_rece", "Nombre Receptor");
		$dg -> set_col_title("rece_elec", "Receptor<br>Electrónico");
		$dg -> set_theme("sweet");
		$dg -> set_ok_showcredit(false);
		$dg -> display();
?>					              
 							</table>							
						</td>
					</tr>
				</table>
                   </form>
			</fieldset>
		</div>
	</div>
 	
 </body>
</html>
