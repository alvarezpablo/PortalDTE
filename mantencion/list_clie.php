<?php 
	include("../include/config.php");  
	include("../include/ver_aut.php");      
  include("../include/ver_emp_adm.php");

	include("../include/db_lib.php"); 
	include("../include/tables.php"); 
  
  $conn = conn();
  $sLinkActual = "mantencion/list_clie.php";
  
  
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
        if(confirm(_MSG_DEL_CLIE))
          document._FDEL.submit();
      }
      else
        alert(_MSG_SEL_CLIE_DEL);
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
	<?php sTituloCabecera("Clientes"); ?>
	<?php sAgregaHerramienta("screenClientList", "Herramientas", $aBotonCliempHerramienta); ?>

	<div class="screenBody">
		<div class="listArea">
			<fieldset>
				<legend>Clientes</legend>
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td>
							<table width="100%" cellspacing="0" class="buttons">
								<td class="main">
									<div>
                    <form name="_FSEARCH" method="get" action="<?php echo $_LINK_BASE . $sLinkActual; ?>">
                    <select name="_COLUM_SEARCH">
                      <option value="rut_cli">Rut Cliente</option>
                      <option value="rs_empr">Razón Social (Empresa)</option>
                      <option value="raz_social">Razón Social (Cliente)</option>                                         
                    </select>
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
              
              
            <form name="_FDEL" method="post" action="mantencion/pro_clie.php">
              <input type="hidden" name="sAccion" value="E">   

<?php 
                
        $sql = "SELECT  "; 
        $sql .= "   C.rut_cli,  "; 
        $sql .= "   E.rs_empr,  "; 
        $sql .= "   C.dv_cli,  "; 
        $sql .= "   C.emi_elec_cli,  "; 
        $sql .= "   C.acrec_email,  "; 
        $sql .= "   C.email_envio,  "; 
        $sql .= "   C.raz_social,  "; 
        $sql .= "   E.codi_empr, "; 
        $sql .= "   C.dir_clie,  ";
        $sql .= "   C.com_clie,  ";
        $sql .= "   C.giro_clie,  ";
        $sql .= "   C.ciud_cli,  ";
        $sql .= "   C.guia_clie,  ";
        $sql .= "   C.fono_clie, ";
        $sql .= "   C.nom_cont_tec, ";
        $sql .= "   C.fono_cont_tec, ";
        $sql .= "   C.mail_cont_tec, ";
        $sql .= "   C.nom_cont_adm, ";
        $sql .= "   C.fono_cont_adm, ";
        $sql .= "   C.mail_cont_adm     ";    
        $sql .= "  FROM clientes C, empresa E WHERE C.codi_empr = E.codi_empr ";         
	$sql .= " AND E.codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "' ";
        
		if(trim($_SESSION["_COD_ROL_SESS"]) != "1")
			$sql .= "  AND E.codi_empr IN(SELECT codi_empr FROM empr_usu WHERE cod_usu = '" . str_replace("'","''",$_SESSION["_COD_USU_SESS"]) . "')    ";    

        if($_STRING_SEARCH != "")
          $sql .= " AND UPPER(" . $_COLUM_SEARCH  . ") LIKE UPPER('" . str_replace("'","''",$_STRING_SEARCH) . "%') ";
        
        if(trim($_ORDER_BY_COLUM) == "")
          $sql .= " ORDER BY raz_social ";
        else
          $sql .= " ORDER BY " . $_ORDER_BY_COLUM . "  $_NIVEL_BY_ORDER ";          

        switch ($_ORDER_BY_COLUM){
          case "rut_cli": 
            $sClassRut = "class='sort'";
            $sImgRut = "<img src='" . $_IMG_BY_ORDER . "'>";          
            break;

          case "rs_empr": 
            $sClassRE = "class='sort'";
            $sImgRE = "<img src='" . $_IMG_BY_ORDER . "'>";          
            break;
        
          case "emi_elec_cli": 
            $sClassEE = "class='sort'";
            $sImgEE = "<img src='" . $_IMG_BY_ORDER . "'>";          
            break;
        
          case "acrec_email": 
            $sClassREE = "class='sort'";
            $sImgREE = "<img src='" . $_IMG_BY_ORDER . "'>";          
            break;
      
          case "raz_social": 
            $sClassRSC = "class='sort'";
            $sImgRSC = "<img src='" . $_IMG_BY_ORDER . "'>";          
            break;
                
          default:
            $sClassRut = "class='sort'";
            $sImgRut = "<img src='" . $_IMG_BY_ORDER . "'>";                     
            break;        
        }
                
?>	                            
              
							<table width="100%" cellspacing="0" class="list">
								<tr>
                
									<th width="10%" <?php echo $sClassRut; ?>>
                    <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=rut_cli&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Rut</a>
                    <?php echo $sImgRut; ?>
                  </th>
                  		                
									<th width="30%" <?php echo $sClassRSC; ?>>
                    <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=raz_social&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Raz&oacute;n Social (Cliente)</a>
                    <?php echo $sImgRSC; ?>
                  </th>		                 

									<th width="30%" <?php echo $sClassRE; ?>>
                    <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=rs_empr&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Raz&oacute;n Social (Empresa)</a>
                    <?php echo $sImgRE; ?>
                  </th>		
                                                    
									<th width="10%" <?php echo $sClassEE; ?>>
                    <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=emi_elec_cli&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Emisor Electr&oacute;nico</a>
                    <?php echo $sImgEE; ?>
                  </th>	
                  
									<th width="10%" <?php echo $sClassREE; ?>>
                    <a href="javascript:location.href='<?php echo $_LINK_BASE . $sLinkActual; ?>?_ORDER_BY_COLUM=acrec_email&_NIVEL_BY_ORDER=<?php echo $_NIVEL_BY_ORDER; ?>&_COLUM_SEARCH=<?php echo $_COLUM_SEARCH; ?>&_STRING_SEARCH=<?php echo $_STRING_SEARCH; ?>&_ORDER_CAMBIA=Y';">Receptor Electr&oacute;nico</a>
                    <?php echo $sImgREE; ?>
                  </th>	
                                                    
                
									<th width="10%" class="select"><input type="checkbox" class="checkbox" name="clientslistSelectAll" value="true" onClick="chDchALL();"></th>
								</tr>

                
<?php 
/********************** LISTA CLIENTES ****************************************/
        $result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $_NUM_ROW_LIST * $_NUM_PAG_ACT);        
        $sPaginaResult = sPagina($conn, $sql, $sLinkActual);        // string de paginacion
        
        $sClassRow = "evenrowbg";               // clase de la hoja de estilo 
        
        while (!$result->EOF) {
                
          $nRutCli = trim($result->fields["rut_cli"]);
          $sRazClie = trim($result->fields["raz_social"]);
          $sDvClie = trim($result->fields["dv_cli"]);
          $sEmiElecClie = trim($result->fields["emi_elec_cli"]);  // Emisor Electronico
          $sRecElecClie = trim($result->fields["acrec_email"]);     // Receptor Electronico
          $sEnvEmail = trim($result->fields["email_envio"]);       // Envio de Email
          $sRazEmp = trim($result->fields["rs_empr"]);             
          $sCodEmp = trim($result->fields["codi_empr"]);                                        

          $sFono = trim($result->fields["fono_clie"]);            
          $sGuiaClie = trim($result->fields["guia_clie"]);
          $sCiudClie = trim($result->fields["ciud_cli"]);
          $sGiroClie = trim($result->fields["giro_clie"]);
          $sComClie = trim($result->fields["com_clie"]);
          $sDirClie = trim($result->fields["dir_clie"]);          
          
          $sNomTec = trim($result->fields["nom_cont_tec"]);                    
          $sFonoTec = trim($result->fields["fono_cont_tec"]);          
          $sEmailTec = trim($result->fields["mail_cont_tec"]);          
          $sNomAdm = trim($result->fields["nom_cont_adm"]);          
          $sFonoAdm = trim($result->fields["fono_cont_adm"]);          
          $sEmailAdm = trim($result->fields["mail_cont_adm"]);          

          $strParamLink = "nRutCli=" . urlencode($nRutCli);
          $strParamLink .= "&sRazClie=" . urlencode($sRazClie);
          $strParamLink .= "&sDvClie=" . urlencode($sDvClie);          
          $strParamLink .= "&nRutCliNew=" . urlencode($nRutCli);
          $strParamLink .= "&sDvClieNew=" . urlencode($sDvClie);                    
          $strParamLink .= "&sEmiElecClie=" . urlencode($sEmiElecClie);          
          $strParamLink .= "&sRecElecClie=" . urlencode($sRecElecClie);                    
          $strParamLink .= "&sEnvEmail=" . urlencode($sEnvEmail);                              
          $strParamLink .= "&sRazEmp=" . urlencode(str_replace("#",'',$sRazEmp));                                        
          $strParamLink .= "&sCodEmp=" . urlencode($sCodEmp);                                                    
          $strParamLink .= "&sCodEmpNew=" . urlencode($sCodEmp); 
          $strParamLink .= "&sFono=" . urlencode($sFono);
          $strParamLink .= "&sGuiaClie=" . urlencode($sGuiaClie);
          $strParamLink .= "&sCiudClie=" . urlencode(str_replace("#",'',$sCiudClie));
          $strParamLink .= "&sGiroClie=" . urlencode(str_replace("#",'',$sGiroClie));
          $strParamLink .= "&sComClie=" . urlencode(str_replace("#",'',$sComClie));
          $strParamLink .= "&sDirClie=" . urlencode(str_replace("#",'',$sDirClie));
          $strParamLink .= "&sNomTec=" . urlencode($sNomTec);
          $strParamLink .= "&sFonoTec=" . urlencode($sFonoTec);
          $strParamLink .= "&sEmailTec=" . urlencode($sEmailTec);
          $strParamLink .= "&sNomAdm=" . urlencode($sNomAdm);
          $strParamLink .= "&sFonoAdm=" . urlencode($sFonoAdm);
          $strParamLink .= "&sEmailAdm=" . urlencode($sEmailAdm);
          $strParamLink .= "&sAccion=M";            
          
          if($sEmiElecClie == "S")
            $sImgEmiElecClie = "skins/" . $_SKINS . "/icons/ok.gif";
          else
            $sImgEmiElecClie = "skins/" . $_SKINS . "/icons/off.gif";
            
          if($sRecElecClie == "S")
            $sImgRecElecClie = "skins/" . $_SKINS . "/icons/ok.gif";
          else
            $sImgRecElecClie = "skins/" . $_SKINS . "/icons/off.gif";            
            
?>																							
								<tr class="<?php echo $sClassRow; ?>">
									<td class="icon"><?php echo $nRutCli . "-" . $sDvClie; ?></td>
									<td>
                    <a href="javascript:location.href='<?php echo $_LINK_BASE; ?>mantencion/form_clie.php?<?php echo $strParamLink; ?>';">
                      <?php echo $sRazClie; ?>
                    </a>
                  </td>
                  
									<td><?php echo $sRazEmp; ?></td>
									<td><img src="<?php echo $sImgEmiElecClie; ?>"></td>  
                  <td><img src="<?php echo $sImgRecElecClie; ?>"></td>                    
									<td class="select"><input type="checkbox" class="checkbox" name="del[]" id="del_2" value="<?php echo $nRutCli . ";" . $sCodEmp; ?>"></td>
								</tr>
                
<?php
          if($sClassRow == "oddrowbg")
            $sClassRow = "evenrowbg";
          else
            $sClassRow = "oddrowbg";
            
          $result->MoveNext();
        } 
        
/*********************** FIN LISTA CLIENTES ***********************************/
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
