<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");        
  include("../include/db_lib.php"); 

  $nCodEmp = trim($_POST["nCodEmp"]);
  
  $aRecursos = $_POST["TableColumnList"];

  $rol  = $_POST["IndexColumnList"];
  $op = trim($_POST["op"]); 
  $conn = conn();

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  //echo "<pre>";var_dump($_POST);echo "</pre>";die();

 /**
 * agregamos recursos a roles de usuario(permisos) 
 * @param object $conn 
 * @param array $aRecursos
 * @param integer $rol
 * @return bool 
 */
function agregarPermiso($conn,$aRecursos,$rol){

  try{
    //borramos los permisos anteriormente asignados
    $sql = "DELETE FROM permiso ";        
    $sql .= "   WHERE ";
    $sql .= "   rol_cod_rol = " . $rol;
    $result = $conn->Execute($sql);
    if(!$result){
      throw new Exception($conn->ErrorMsg());
    }
    //asignamos los nuevos recorsos al rol de usuario
    for($i=0; $i < sizeof($aRecursos); $i++){
      $sql = "INSERT INTO permiso (rol_cod_rol, recurso_cod_recurso) ";
      $sql .= " VALUES(" . $rol . " , " . $aRecursos[$i] . ")";
      $result = $conn->Execute($sql);
      if(!$result){
        throw new Exception($conn->ErrorMsg());
        break;
      }
    }
    return true;
  }catch(Exception $e){
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
  }
 }
  agregarPermiso($conn,$aRecursos,$rol);
  
  header("location:fin_emp_user.php?op=" . $op);
  exit;    
  
?>
