<?php 
	include("../include/config.php");  
    include("../include/ver_aut.php");      
    include("../include/ver_aut_adm.php"); 
//	include("../include/ver_emp_adm.php");         
	include("../include/db_lib.php"); 
	include("../include/tables.php");  
$conn = conn();
$sql = "select rut_contr as  rut_contr2, SUBSTR(cast(rut_contr as varchar), 1, LENGTH(cast(rut_contr as varchar)) - 1) as rut_contr, nrores_contr , fecres_contr from contrib_elec where
rut_contr in (85865800,76931350,76107004,76172618,76253184,76552132,9059938,86514300,89630400,76043752,89876100,83162400,96717640,76057558,96809480,76170844,85208700,96938990,99565090,76396710,76268991,76232837,76629071,78969740,92177000,76411568,76232841,76207854,76207834,76366530,99508830,76297154,76087464,76109145,76103648,76010909,92698000,76090996,76242018,76028461,76023726,76037864,76037869,76037858,76037872,86018500,99546600,59101150,79509220,59059340,88819000,76177760,76425390,78196790,76552176,78013210,76068622,78777490,76035624,76041431,76093454,94599000,86379600,76344873,59160180,7369876,79773780,76380242,76016236,92405000,76082113,99555660,76030627,59090500,76884880,76306130,99584440,76035224,76243167,76724620,78979690,78075630,79581120,76095582,76245966,76362654,76238193,77235800,76076256,99574500,76782470,61608700,60806000,77888340,76804710,76381427,76456800,76078057,76262889,76365349,59090630,76815270,78708780,79640960,59108780,77506190,76519999,76499727,96919150,76006012,85904700,76366733,78837140,85075900,76538330,84756600,76098302,76679760,76980730,93754000,76566180,84710400,77634300,65000241,96923620,78376780,76408750,78136410,77970090,78837550,77471910,7220319,99563730,78988990,76197308,77788390,76444370,79727360,77100990,77658970,76027066,76273829,9749617,12275869,78458390,76257278,77094420,76124499,76809210,76181211,76259308,5435891,96996290,76193647,76075337,59112080,78995170,76341240,76226356,76243601,77190820,96896990,76842420,99524470,99568520,79764840,99524450,86099700,76400332,78125830,76584106,76415214,76384578,76391317,76754790,76075441,87532700,76606190,76890810,91614000,76458391,77305910,96539040,99557380,96937710,76472207,76131631,76598547,99535510,78381520,76020575,76020478,78782450,79665080,87845200,71602500,12064861,6032761,96819010,76165127,76101664,78088970,9212239,76430414,12006733,76499801,78933990,96637250,76686205,76155922,76016396,82728500,10659678,76408917,76200411,77031030,76379451,76022382,78461630,76635912,76251096,76738636,96545040,79518230,96843010,76414823,76409952,76075832,99568400,99999999,77274880,81496800,70039200,96665690,76876772,96769130,76820257,76001496,87049000,78317880,76941424,76798858,76805771,94433000,77006073,65030892,96717910,96721040,61214000,96975090,99589040,53226150,72421000,72323600,75181000,77329620,76609990,76255142,76391088,76507451,76526662,76190286,76357009,76409458,76926444,76891330,84078800,84284500,77031793,76016406,78304140,14599330,76031548,77076859,76432471,77107585)";	
$result = rCursor($conn, $sql);
        while (!$result->EOF) {
          $rut_contr  = trim($result->fields["rut_contr2"]);
          $nrores_contr  = trim($result->fields["nrores_contr"]);
          $fecres_contr  = trim($result->fields["fecres_contr"]);

	  $sql2 = "update empresa set fec_resolucion='$fecres_contr', num_resolucion='$nrores_contr' where rut_empr=$rut_contr";
	  nrExecuta($conn, $sql2);
echo $sql2."<br>";
          $result->MoveNext();
        } 
exit;
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
