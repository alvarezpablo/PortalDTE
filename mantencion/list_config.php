<?php 
	include("../include/config.php");  
    include("../include/ver_aut.php");      
    include("../include/ver_aut_adm.php"); 
//	include("../include/ver_emp_adm.php");         
	include("../include/db_lib.php"); 
	include("../include/tables.php");  


	$nCodEmp = $_GET["nCodEmp"];
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
	<?php sTituloCabecera("Configuraci&oacute;n Properties"); ?>
<?php   
        $conn = conn();
        $sLinkActual = "mantencion/list_config.php";
?>
	<div class="screenBody">
		<div class="listArea">
			<fieldset>
				<legend>Configuraci&oacute;n Properties</legend>
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td>
              
            <form name="_FDEL" method="post" action="mantencion/pro_config.php">
              <input type="hidden" name="sAccion" value="E">		
              <input type="hidden" name="nCodEmp" value="<?php echo $nCodEmp; ?>">		
			  
              
              <table width="100%" cellspacing="0" class="list">
               <tr>
				<th width="30%" <?php echo $sClassCod; ?>>Codigo</th>						                
				<th width="40%" <?php echo $sClassCod; ?>>Valor</th>						                
			  </tr>


<?php 
/********************** LISTA TIPO DE DOCUMENTO ****************************************/
        $sql = "SELECT 
					cod_config, 
					map_config, 
					valor_config, 
					tipo_campo, 
					val_perm, 
					valor_default 
				FROM 
					config 
				WHERE 
					codi_empr = '" . trim($nCodEmp) . "'
				ORDER BY orden";        

	$sql = "SELECT cod_config, map_config, valor_config, tipo_campo, val_perm, valor_default, orden
		FROM config
		WHERE codi_empr='" . trim($nCodEmp) . "'
		UNION
		SELECT cod_config, map_config, valor_config, tipo_campo, val_perm, valor_default, orden
		FROM config a
		WHERE a.codi_empr=0 and a.cod_config not in (select cod_config from config where codi_empr='" . trim($nCodEmp) . "')
		ORDER BY 7";
		$result = rCursor($conn, $sql);
		$total = $result->RecordCount();
		
		if($total == 0){
	        $sql = "SELECT 
					cod_config, 
					map_config, 
					valor_config, 
					tipo_campo, 
					val_perm, 
					valor_default 
				FROM 
					config 
				WHERE 
					codi_empr = 0
				ORDER BY orden";        
			$result = rCursor($conn, $sql);
		}

        
        $sClassRow = "evenrowbg";               // clase de la hoja de estilo 
		$j=0;
					
        while (!$result->EOF) {
          $nCodConfig  = trim($result->fields["cod_config"]);
          $sMapConfig = trim($result->fields["map_config"]);
          $sDescConfig = trim($result->fields["valor_config"]);
          $sTipoCampo = trim($result->fields["tipo_campo"]);			// tipo campo
          $sValorPerm = trim($result->fields["val_perm"]);				// valor permitido separado por coma
          $sValorDefault = trim($result->fields["valor_default"]);		// valor default

?>																												
			<tr class="<?php echo $sClassRow; ?>">
				<td class="icon"><?php echo $sMapConfig; ?> :</td>
				<td>
					
<?php 
				if($sTipoCampo == "text" || $sTipoCampo == "password") {
?>
					<INPUT TYPE="hidden" NAME="aCodConfig[]" value="<?php echo $nCodConfig; ?>">
					<INPUT TYPE="text" NAME="aDescConfig[]" size="80" value="<?php echo $sDescConfig; ?>">
<?php 				
				}
				else{	//	radio
					if($sDescConfig == "")
						$sDescConfig = $sValorDefault;

					$aValorPosible = explode(",",$sValorPerm);
?> 				
					<INPUT TYPE="hidden" NAME="aCodConfig<?php echo $j; ?>" value="<?php echo $nCodConfig; ?>">
<?php
					for($i=0; $i < sizeof($aValorPosible); $i++){
						if($aValorPosible[$i] == $sDescConfig)
							$check = "checked";
						else
							$check = "";
?>
						<INPUT TYPE="radio" NAME="aDescConfig<?php echo $j; ?>" value="<?php echo $aValorPosible[$i]; ?>" <?php echo $check; ?> ><?php echo $aValorPosible[$i]; ?>
<?php				
					}
					$j++;
				}
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
        
/*********************** FIN LISTA TIPO DE DOCUMENTO ***********************************/
?>								           
								<INPUT TYPE="hidden" NAME="nTotalRadio" value="<?php echo $j; ?>">

							</table>
							<br><center><INPUT TYPE="submit" value="Grabar"></center>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</div>
 	
 </body>
</html>
