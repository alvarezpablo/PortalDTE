<?php 

  $sUriRetorno = $_GET["sUriRetorno"];
 
  include("include/config.php");  
  include("include/db_lib.php");   
  $conn = conn();
  $sMsgJs = $_GET["sMsgJs"];

  session_start();
  if(trim($_SESSION["_COD_USU_SESS"]) == "" || trim($_SESSION["_COD_ROL_SESS"]) == "")
      header("location:login.php?sMsgJs=_MSG_USER_EXPIRE");
    
  if(trim($_SESSION["_COD_ROL_SESS"]) != "1"){
      
    $sql = "  SELECT ";
    $sql .="    EU.codi_empr,  ";
    $sql .="    E.rs_empr, ";
    $sql .="    E.rut_empr, ";
    $sql .="    E.dv_empr, ";
    $sql .="    E.dir_empr ";
//    $sql .="    , DATE_PART('days', COALESCE(fec_ter_contrato,NOW()+ interval '3 days') - NOW()) AS ndays ";
    $sql .=" , (SELECT count(codi_empr) FROM caf WHERE tipo_docu in(39,41) and codi_empr=E.codi_empr) as bol, E.is_recep_erp, E.emite_web ";
    $sql .="  FROM ";
    $sql .="    empr_usu EU, ";
    $sql .="    empresa E ";  
    $sql .="  WHERE "; 
    $sql .="    EU.codi_empr = E.codi_empr AND  ";
    $sql .="    EU.cod_usu = " . $_SESSION["_COD_USU_SESS"];
    $result = rCursor($conn, $sql);
    $nNumRow = $result->RecordCount();        // obtiene el numero de filas                   
    $_SESSION["_NUM_EMP_USU_SESS"] = $nNumRow;                            
    
   if($nNumRow == 0){
      $_SESSION = array();
      session_destroy();
      header("location:login.php?sMsgJs=_MSG_USER_SIN_EMP");
      exit;
    }
    elseif($nNumRow == 1){
      if(!$result->EOF) {
        $sCodEmp = trim($result->fields["codi_empr"]);
        $sNomEmp = trim($result->fields["rs_empr"]);   
	$bol = trim($result->fields["bol"]);
        $nRutEmp = trim($result->fields["rut_empr"]);
        $sDvEmp = trim($result->fields["dv_empr"]);
        $sDirEmp = trim($result->fields["dir_empr"]);
	$isRecep = trim($result->fields["is_recep_erp"]);
	$nEmiteWeb = trim($result->fields["emite_web"]);

/*
        $nDays = trim($result->fields["ndays"]);
	if ($sCodEmp==17){
		$nDays=0;
		$msgContrato="Advertencia: El contrato asociado a su empresa ha expirado. Debe contactarse con su ejecutivo comercial.";
        	$_SESSION["MSG_CONTRATO"]=$msgContrato;
	}

        $_SESSION["NDAYS_CONTRATO"]=$nDays;
*/
        $_SESSION["_COD_EMP_USU_SESS"] 	= $sCodEmp;   // SESSION CON EL CODIGO DE EMPRESA  
        $_SESSION["v_codi_empr"] 	= $sCodEmp;   // SESSION CON EL CODIGO DE EMPRESA    

	$_SESSION["_IS_RECEP_ERP_"]=$isRecep;

	$_SESSION["_EMITE_WEB_"]=$nEmiteWeb;



        $_SESSION["RUT_EMP"]=$nRutEmp;
        $_SESSION["DV_EMP"]=$sDvEmp;
        $_SESSION["_RUT_EMP_SESS"]=$nRutEmp."-".$sDvEmp;

        $_SESSION["DIR_EMP"]=$sDirEmp;
	$_SESSION["TIENE_BOLETA"]=$bol;
//echo $_SESSION["TIENE_BOLETA"];
//exit;
        $_SESSION["_NOM_EMP_USU_SESS"] = $sNomEmp;              
        header("location:index.php");
        exit;
      }
      else{
        header("location:login.php?sMsgJs=_MSG_USER_ERR");
        exit;
      }
    }
  }
  else{
    $sql = "  SELECT ";
    $sql .="    E.codi_empr,  ";
    $sql .="    E.rs_empr, ";
    $sql .="    E.rut_empr, ";
    $sql .="    E.dv_empr ";
    $sql .="  FROM ";
    $sql .="    empresa E ";  
    $sql .="  ORDER BY "; 
    $sql .="    E.rs_empr";    
    $result = rCursor($conn, $sql);
    $nNumRow = $result->RecordCount();        // obtiene el numero de filas                   
    $nRutEmp = trim($result->fields["rut_empr"]);
    $sDvEmp = trim($result->fields["dv_empr"]);

  
    $_SESSION["_RUT_EMP_SESS"]=$nRutEmp."-".$sDvEmp;
    $_SESSION["_NUM_EMP_USU_SESS"] = $nNumRow; 

  }

?>
<html>
	
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>OpenB</title>

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
      
      SetContext('cl_ed');
        
    }
    
    function _body_onunload()
    {
      lon();
      
    }
  
  function asigNom(){
    var F = document._FFORM;
    
    if(F.nCodEmp.options[F.nCodEmp.selectedIndex].value == ""){
      alert(_MSG_SEL_EMP_ING);
      return false;
    }   
    else{
      F.sNomEmp.value = F.nCodEmp.options[F.nCodEmp.selectedIndex].text;
	  F.submit();
      return true;
    }
	
  }
  //-->
		</script>    
    
    
	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>
  
  
  <div class="body">
    <img src='skins/<?php echo $_SKINS; ?>/images/def_open_logo.gif' name='logo' height='50' border='0'>
  </div>  
  <br>
  
  
	<div class="formArea"  align="center">
		<table width="500" class="buttons" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td class="main" width="0">&nbsp;</td>
        <td class="footnote">&nbsp;</td>
        <td class="misc" width="0">&nbsp;</td>
      </tr>
    </table>
	</div>    
  
  
  <form name="_FFORM" action="asig_emp.php" method="post" onSubmit="return asigNom();">    
    <input type="hidden" name="sNomEmp" value="">
    <input type="hidden" name="sUriRetorno" value="<?php echo $sUriRetorno; ?>">

    <table class="formFields" cellspacing="0" width="500"  align="center">
      <tr>
        <td class="name"><label for="fid-cname">Seleccione Empresa:</label>&nbsp;*</td>
        <td>
        
         <SELECT NAME="nCodEmp" size="1">
  <?php 
          if($_SESSION["_COD_EMP_USU_SESS"] <> "")
            echo '<option value="">Selecione Empresa</option>		\n	';
          else
            echo '<option value="" selected>Selecione Empresa</option>		\n	';           
          
          $result->MoveFirst();            
          while (!$result->EOF) {
          
            $nCodEmpt = trim($result->fields["codi_empr"]);          
            $sRsEmpt = trim($result->fields["rs_empr"]);    

            if($nCodEmpt == $_SESSION["_COD_EMP_USU_SESS"])	                            
              echo '<option value="' . $nCodEmpt . '" selected>' . $sRsEmpt . '</option> \n';
            else
              echo '<option value="' . $nCodEmpt . '">' . $sRsEmpt . '</option> \n';
  
            $result->MoveNext();
          } 
  ?>        
              </SELECT>    
        
        </td>
      </tr>
      
    </table>
  
<br>
  
	<div class="formArea"  align="center">
		<table width="500" class="buttons" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td class="main" width="0"></td>
        <td class="footnote"><span class="required">*</span> Campos requeridos.</td>
        <td class="misc" width="0">
		  <div class="commonButton" id="bid-ok" title="Aceptar"  onClick="return asigNom();" onMouseOver="" onMouseOut="">
            <button name="bname_ok">Aceptar</button>
            <span>Aceptar</span>
          </div>
        </td>
      </tr>
    </table>
	</div>  
 </form>  
  
</html>    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
