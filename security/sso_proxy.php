<?php

include("../include/config.php");  
include("../include/db_lib.php");   
$conn = conn();

session_start();


//rut_ic=<rut iconstruye>&p=<clave>&mod=<módulo opendte>&rut=<rut empresa>


$rut_p = $_GET['rut_ic'];
$clave = base64_decode($_GET['p']);
$mod   = $_GET['mod'];
$rut_e = $_GET['rut'];

if (strcmp($rut_e,"")==0){
	$rut_e="111111111";
}

$_rut_e = substr($rut_e, 0, 8);
$_dv_e  = substr($rut_e, -1);


//echo "Parametros " . $rut_p . " --- " . $clave . " ---- " . $mod . " ---- " . $rut_e;


$config = parse_ini_file('security.ini', 1);

$pass=$config[$rut_p]['clave'];

if (strcmp($rut_p,"")==0){
	$rut_p="19";
	$clave="********";
}

if (strcmp($clave, $pass)!=0) {
	echo "<br>Error: Clave Erronea para RUT [$rut_p]<br>";
	echo "clave ingresada:**" . $clave."**<br>";
	echo "clave config   :**" . $pass."**<br>";
	echo "clave config   :**" . base64_encode($pass)."**<br>";
	exit;
}

$path=$config['mods'][$mod];


//echo "ejecutando... " . $path;


$sql = "  SELECT ";
$sql .="    EU.codi_empr,  ";
$sql .="    E.rs_empr ";
$sql .="  FROM ";
$sql .="    empr_usu EU, ";
$sql .="    empresa E ";  
$sql .="  WHERE "; 
$sql .="    EU.codi_empr = E.codi_empr AND  ";
$sql .="    E.rut_empr='$_rut_e' and dv_empr='$_dv_e'";


//echo "<br>";
//echo $sql;

$result = rCursor($conn, $sql);

$nNumRow = $result->RecordCount();        // obtiene el numero de filas                   
  
$_SESSION["_NUM_EMP_USU_SESS"] = $nNumRow;                            
      
if($nNumRow == 0){
      $_SESSION = array();
      session_destroy();
	echo "<br>No existe Empresa";
//      header("location:login.php?sMsgJs=_MSG_USER_SIN_EMP");
      exit;
}
elseif($nNumRow == 1){
      if(!$result->EOF) {
        $sCodEmp = trim($result->fields["codi_empr"]);
        $sNomEmp = trim($result->fields["rs_empr"]);      
        $_SESSION["_COD_EMP_USU_SESS"] = $sCodEmp;   // SESSION CON EL CODIGO DE EMPRESA    
        $_SESSION["_NOM_EMP_USU_SESS"] = $sNomEmp;              


	$sql_cod = "select EU.cod_usu, U.cod_rol, U.id_usu from empr_usu EU, usuario U where EU.codi_empr=$sCodEmp and EU.cod_usu=U.cod_usu";
	$rs = rCursor($conn, $sql_cod);
	if (!$rs->EOF) {
		$sCodUsu = trim($rs->fields["cod_usu"]);
		$sCodRol = trim($rs->fields["cod_rol"]);
		$sUser = trim($rs->fields["id_usu"]);
	        $_SESSION["_COD_USU_SESS"] = $sCodUsu;   // SESSION CON EL CODIGO DE USUARIO
       	  	$_SESSION["_COD_ROL_SESS"] = $sCodRol;   // SESSION CON EL CODIGO DEL ROL
         	$_SESSION["_ALIAS_USU_SESS"] = $sUser;   // NOMBRE DE USUARIO

	}
	else {
		echo "<br>Error: Configurar Usuario para empresa [$sNomEmp]<br>";
		exit;
	}

        header("location:".$path);
        exit;
      }
      else{
		echo "<br>Error: para Obtener Parametros de empresa [$sNomEmp]<br>";
        	exit;
      }

}






?>
