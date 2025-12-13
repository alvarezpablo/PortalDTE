<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");        
  include("../include/db_lib.php"); 

  $nCodUser = trim($_POST["nCodUser"]);
  $arEmpoUser = $_POST["IndexColumnList"];
  $op = trim($_POST["op"]); 
  $conn = conn();

  
  function dIngresar($conn){
      global $nCodUser, $arEmpoUser;
  
      $sql = "DELETE FROM empr_usu ";        
      $sql .= "   WHERE ";
      $sql .= "   cod_usu = " . $nCodUser;
      nrExecuta($conn, $sql);
      
      for($i=0; $i < sizeof($arEmpoUser); $i++){
        $sql = "INSERT INTO empr_usu (cod_usu, codi_empr) ";
        $sql .= " VALUES( " . $nCodUser . "," . $arEmpoUser[$i] . ")";
        nrExecuta($conn, $sql);
      }
  }
  
  dIngresar($conn);
  
  header("location:fin_user_emp.php?op=" . $op);
  exit;    
?>
