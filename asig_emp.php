<?php
  include("include/config.php");
  include("include/frontend_config.php");
  session_start();
  include("include/db_lib.php");
  $conn = conn();

  if(trim($_SESSION["_COD_USU_SESS"]) == "" || trim($_SESSION["_COD_ROL_SESS"]) == "")
      header("location:" . getLoginUrl() . "?sMsgJs=Sesión expirada");

  $nCodEmp = trim($_POST["nCodEmp"]);
  $sNomEmp = trim($_POST["sNomEmp"]);    
  $sUriRetorno = trim($_POST["sUriRetorno"]);    

  if(trim($nCodEmp) == ""){
	header("location:" . getSelEmpUrl());
    exit;
  }

  $_SESSION["_COD_EMP_USU_SESS"] = $nCodEmp;    
  $_SESSION["_NOM_EMP_USU_SESS"] = $sNomEmp;        

  $sql  = "select rut_empr, dv_empr from empresa where codi_empr=".$nCodEmp;
    $result = rCursor($conn, $sql);

    if(!$result->EOF) {
        $_SESSION["_RUT_EMP_SESS"] = trim($result->fields["rut_empr"]) . "-" . trim($result->fields["dv_empr"]);
   }    

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
    $sql .="    E.codi_empr = " . $nCodEmp . " AND ";
    $sql .="    EU.codi_empr = E.codi_empr AND  ";
    $sql .="    EU.cod_usu = " . $_SESSION["_COD_USU_SESS"];
    $result = rCursor($conn, $sql);
    $nNumRow = $result->RecordCount();        // obtiene el numero de filas                   
    $_SESSION["_NUM_EMP_USU_SESS"] = $nNumRow;                            
    
   if($nNumRow == 0){
      $_SESSION = array();
      session_destroy();
      header("location:" . getLoginUrl() . "?sMsgJs=Usuario sin empresa");
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

        $_SESSION["_COD_EMP_USU_SESS"] 	= $sCodEmp;   // SESSION CON EL CODIGO DE EMPRESA
        $_SESSION["v_codi_empr"] 	= $sCodEmp;   // SESSION CON EL CODIGO DE EMPRESA

	$_SESSION["_IS_RECEP_ERP_"]=$isRecep;
	$_SESSION["_EMITE_WEB_"]=$nEmiteWeb;

        $_SESSION["RUT_EMP"]=$nRutEmp;
        $_SESSION["DV_EMP"]=$sDvEmp;
        $_SESSION["_RUT_EMP_SESS"]=$nRutEmp."-".$sDvEmp;

        $_SESSION["DIR_EMP"]=$sDirEmp;
	$_SESSION["TIENE_BOLETA"]=$bol;
        $_SESSION["_NOM_EMP_USU_SESS"] = $sNomEmp;
        header("location:" . getIndexUrl());
        exit;
      }
      else{
        header("location:" . getLoginUrl() . "?sMsgJs=Error de usuario");
        exit;
      }
    }
  }


  if(trim($sUriRetorno) == "")
	  $sUriRetorno = getIndexUrl();

?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	location.href = "<?php echo $sUriRetorno; ?>";
//-->
</SCRIPT>
