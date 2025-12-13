<?php

  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm_super.php");        
  include("../include/ver_emp_adm.php");         
  include("../include/db_lib.php"); 
  include ("../include/upload_class.php"); 

  $nCodEmp = trim($_SESSION["_COD_EMP_USU_SESS"]);

  $conn = conn();


function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}


$uuid =  gen_uuid();


$sql = "UPDATE empresa set uuid = '" . $uuid . "' WHERE codi_empr= " . $nCodEmp;

nrExecuta($conn, $sql);

header("Location: uuid.php");




?>
