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
	<?php sTituloCabecera("Estado de Boletas por Empresas"); ?>
<?php  
	$hoy = date("d-m-Y");
 
        $conn = conn();
        $sLinkActual = "mantencion/list_estado_boleta.php";
?>
	<div class="screenBody">
		<div class="listArea">

                        <fieldset>
                                <legend>Agrupado por Estado</legend>
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tr>
                                                <td>
<h2><font color=red>**** No incluye boletas emitidas el d&iacute;a de hoy <?php echo $hoy; ?><br>
**** Solo Boletas</font></h2>


            <form name="_FDEL" method="post" action="mantencion/pro_tc.php">
              <input type="hidden" name="sAccion" value="E">

              <table width="100%" cellspacing="0" class="list">
<?php

$sql = "SELECT COUNT(TIPO_DOCU) as cant,  
CASE 
  WHEN est_xdte = '0' THEN 'CARGADO'
  WHEN est_xdte = '1' THEN 'FIRMADO'
  WHEN est_xdte = '5' THEN 'EMPAQUETADO'
  WHEN est_xdte = '13' THEN 'ENVIADO SII'
  WHEN est_xdte = '29' THEN 'ACEPTADO SII'
  WHEN est_xdte = '77' THEN 'RECHAZADO SII'
  WHEN est_xdte = '45' THEN 'ACEPTADO CON REPARO SII'
ELSE
  'ESTADO ' || est_xdte || ' NO CONOCIDO'
END as estado
from xmldte where tipo_docu in (39,41) and est_xdte < 78 and fec_carg < CURRENT_DATE and ts >= to_date('2022-08-01','YYYY-MM-DD') group by est_xdte ORDER BY  est_xdte";

?>

                <tr>
                        <th width="20%" <?php echo $sClassCod; ?>>Cantidad</th>
                        <th width="20%" <?php echo $sClassCod; ?>>Estado</th>
                </tr>


<?php
/********************** LISTA TIPO DE DOCUMENTO ****************************************/

        $result = $conn->selectLimit($sql, 5000, 5000 * $_NUM_PAG_ACT);

        $sClassRow = "evenrowbg";               // clase de la hoja de estilo

        while (!$result->EOF) {
          $nCant = trim($result->fields["cant"]);
          $sEst = trim($result->fields["estado"]);

?>                                                                                                                                                                             
        <tr class="<?php echo $sClassRow; ?>">
                <td class="icon"><?php echo $nCant; ?></td>
                <td class="icon"><?php echo $sEst; ?></td>
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

                                                </td>
                                        </tr>
                                </table>
                        </fieldset>


			<fieldset>
				<legend>Agrupado por Estado y Empresa</legend>
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td>
<h2><font color=red>**** No incluye las boletas emitidos el d&iacute;a de hoy <?php echo $hoy; ?><br>
**** Incluye solo Boletas</font></h2>             
 
              
            <form name="_FDEL" method="post" action="mantencion/pro_tc.php">
              <input type="hidden" name="sAccion" value="E">							
              
              <table width="100%" cellspacing="0" class="list">
<?php

$sql = "SELECT COUNT(TIPO_DOCU) as cant,
CASE 
  WHEN est_xdte = '0' THEN 'CARGADO'
  WHEN est_xdte = '1' THEN 'FIRMADO'
  WHEN est_xdte = '5' THEN 'EMPAQUETADO'
  WHEN est_xdte = '13' THEN 'ENVIADO SII'
  WHEN est_xdte = '29' THEN 'ACEPTADO SII'
  WHEN est_xdte = '77' THEN 'RECHAZADO SII'
  WHEN est_xdte = '45' THEN 'ACEPTADO CON REPARO SII'
ELSE
  'ESTADO ' || est_xdte || ' NO CONOCIDO'
END as estado
, (SELECT rs_empr FROM empresa WHERE codi_empr = xmldte.codi_empr) AS rs
from xmldte where tipo_docu in (39,41) and est_xdte < 78 and fec_carg < CURRENT_DATE and ts >= to_date('2022-08-01','YYYY-MM-DD') group by rs, est_xdte ORDER BY rs, est_xdte"; 

//echo $sql;
?>								
                
                <tr>
			<th width="20%" <?php echo $sClassCod; ?>>Cantidad</th>
			<th width="20%" <?php echo $sClassCod; ?>>Estado</th>
			<th width="40%" <?php echo $sClassCod; ?>>Empresa</th>
		</tr>


<?php 
/********************** LISTA TIPO DE DOCUMENTO ****************************************/
        
        $result = $conn->selectLimit($sql, 5000, 5000 * $_NUM_PAG_ACT);        
        
        $sClassRow = "evenrowbg";               // clase de la hoja de estilo 
        
        while (!$result->EOF) {
          $nCant = trim($result->fields["cant"]);
          $sEst = trim($result->fields["estado"]);
          $sRs = trim($result->fields["rs"]);

?>																												
	<tr class="<?php echo $sClassRow; ?>">
		<td class="icon"><?php echo $nCant; ?></td>
                <td class="icon"><?php echo $sEst; ?></td>
                <td class="icon"><?php echo $sRs; ?></td>
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

						</td>
					</tr>
				</table>
			</fieldset>


		</div>
	</div>
 	
 </body>
</html>
