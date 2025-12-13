<?php 
	include("../include/config.php");  
	include("../include/ver_aut.php"); 
	include("../include/ver_emp_adm.php"); 
	include("../include/db_lib.php"); 
	include("../include/tables.php"); 

  $conn = conn();
  $sAccion = trim($_POST["sAccion"]);       
  
  function dBorraRegistro($conn, $nFolioDte, $nTipDocu){

    $sql = "SELECT folio_dte FROM xmldte WHERE tipo_docu = '" . $nTipDocu . "' AND folio_dte = '" . str_replace("'","''",$nFolioDte) . "' AND est_xdte IN (0,1,3,77) AND codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";
    $result = rCursor($conn, $sql);
    if(!$result->EOF){

      $sql = "DELETE FROM xmldte WHERE tipo_docu = '" . $nTipDocu . "' AND folio_dte = '" . str_replace("'","''",$nFolioDte) . "' AND codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";      
      nrExecuta($conn, $sql); 

      $sql = "DELETE FROM dte_det WHERE tipo_docu = '" . $nTipDocu . "' AND folio_dte = '" . str_replace("'","''",$nFolioDte) . "'  AND codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";      
      nrExecuta($conn, $sql); 

      $sql = "DELETE FROM dte_enc WHERE tipo_docu = '" . $nTipDocu . "' AND folio_dte = '" . str_replace("'","''",$nFolioDte) . "'  AND codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";      
      nrExecuta($conn, $sql); 

$sql = "delete from gpuerto_ref  where folio_erp='" . str_replace("'","''",$nFolioDte) . "' and tipo_docu='" . $nTipDocu . "' AND rut_emisor in (SELECT cast(rut_empr as varchar) FROM empresa WHERE codi_empr='" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "')";
nrExecuta($conn, $sql);  
$sql = "delete from gpuerto_det  where folio_erp='" . str_replace("'","''",$nFolioDte) . "' and tipo_docu='" . $nTipDocu . "' AND rut_emisor in (SELECT cast(rut_empr as varchar) FROM empresa WHERE codi_empr='" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "')";
nrExecuta($conn, $sql);  
$sql = "delete from gpuerto_enc  where folio_erp='" . str_replace("'","''",$nFolioDte) . "' and tipo_docu='" . $nTipDocu . "' AND rut_emisor in (SELECT cast(rut_empr as varchar) FROM empresa WHERE codi_empr='" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "')";
nrExecuta($conn, $sql);  

	return true;

   }
   else
      return false;
  }
  
  function dEliminar($conn){
    $aEmpDel = $_POST["del"];
    $noElim = 0;
    for($i=0; $i < sizeof($aEmpDel); $i++){
		if(trim($aEmpDel[$i]) != ""){
			$aTemp = explode("|",$aEmpDel[$i]);
 			if(dBorraRegistro($conn, $aTemp[0], $aTemp[1]) == false)
                                $msjErr=" Error al borrar, Folio:" . $aTemp[0] . " Tipo:" . $aTemp[1] . "<br>";
		}
    }
 return $msjErr;
  }


   
  switch ($sAccion) {
    case "E": 
        $msj=dEliminar($conn);
        break;  
  }
  header("location:fin_dte.php?msj=".urlencode($msj));
  exit;    
?>
