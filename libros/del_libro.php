<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
//  include("../include/ver_aut_adm.php");        
  include("../include/ver_emp_adm.php");

  include("../include/db_lib.php"); 
  include("../include/genera_dte.php"); 
  include("../include/tables.php");  

  function enviarAviso($sMsgJs){
	header("location:fin_genera.php?sMsgJs=" . $sMsgJs);
	exit;
  }
  
  $conn = conn();       // conecion a base de datos
  $nCodEmp = $_SESSION["_COD_EMP_USU_SESS"];
  $sTipo = trim($_GET["sTipo"]); 
  $nCodClcv = trim($_GET["nCodClcv"]); 
  $nEstLcv = 8;

  $sql = "  SELECT 
				LX.est_lcx
			FROM
				lcv L,
				lcvxml LX
			WHERE
				L.clcv_tip_oper = '" . str_replace("'","''",$sTipo) . "' AND
				L.codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "' AND
				L.clcv_correl = LX.clcv_correl AND
				L.clcv_correl = '" . str_replace("'","''",$nCodClcv) . "' AND
				LX.est_lcx in ( '" . $nEstLcv . " , '0','13','5') '
			ORDER BY L.clcv_per_trib DESC ";  
  $result = rCursor($conn, $sql);
  
  if(!$result->EOF) {    
	$sql = "DELETE FROM lcvxml WHERE clcv_correl = '" . str_replace("'","''",$nCodClcv) . "' AND est_lcx in ( '" . $nEstLcv . "', '0','13','5')";
	nrExecuta($conn, $sql);
  	
  	$sql = "DELETE FROM lcv_res_seg WHERE clcv_correl = '" . str_replace("'","''",$nCodClcv) . "'";
	nrExecuta($conn, $sql);
	
	$sql = "DELETE FROM lcv WHERE clcv_correl = '" . str_replace("'","''",$nCodClcv) . "'";
	nrExecuta($conn, $sql);
	
  }
  
  header("Location:fin_delete.php?sTipo=" . $sTipo);
?>
