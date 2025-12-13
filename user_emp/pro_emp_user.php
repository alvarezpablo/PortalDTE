<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");        
  include("../include/db_lib.php"); 

  $nCodEmp = trim($_POST["nCodEmp"]);
  $arEmpoUser = $_POST["IndexColumnList"];
  $op = trim($_POST["op"]); 
  $conn = conn();

  
  function dIngresar($conn){
      global $nCodEmp, $arEmpoUser;
  
      $sql = "DELETE FROM empr_usu ";        
      $sql .= "   WHERE ";
      $sql .= "   codi_empr = " . $nCodEmp;
      nrExecuta($conn, $sql);
      
      for($i=0; $i < sizeof($arEmpoUser); $i++){
        $sql = "INSERT INTO empr_usu (cod_usu, codi_empr) ";
        $sql .= " VALUES(" . $arEmpoUser[$i] . " , " . $nCodEmp . ")";
        nrExecuta($conn, $sql);
      }
  }
  
  dIngresar($conn);
  
  header("location:fin_emp_user.php?op=" . $op);
  exit;    
?>
