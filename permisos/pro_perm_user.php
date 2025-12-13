<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");        
  include("../include/db_lib.php"); 

  $nCodEmp = trim($_POST["nCodEmp"]);
  
  $cod_usu = $_POST["TableColumnList"];

  $cod_rol  = $_POST["IndexColumnList"];
  $op = trim($_POST["op"]); 
  $conn = conn();

error_reporting(E_ALL);
ini_set('display_errors', 1);
 /**
 * Actualizamos el rol de un usuario
 * @param object $conn 
 * @return mixed 
 */
 function updateUserCodRol($conn,$cod_rol,$cod_usu){
    $sql = "UPDATE usuario SET cod_rol=".$cod_rol." WHERE cod_usu=".$cod_usu;
    $result = $conn->Execute($sql);
    if($result){
      return $result;
    }else{
      return $conn->ErrorMsg(); 
    }
 }
  updateUserCodRol($conn,$cod_rol,$cod_usu);
  header("location:fin_emp_user.php?op=" . $op);
  exit;    
  
?>
