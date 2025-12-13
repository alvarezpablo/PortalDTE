<?php 
    /* ARCHIVO A INCLUIR DESPUES DE VER_AUT PARA LAS APLICAICONES QUE SON PERFIL ADMINISTRACION Y NECESITAN UNA EMPRESA ASOCIADA */
   
    /****** VALIDA QUE SI ES ROL ADM TENGA ASOCIADA UNA EMPRESA *******/
    if(trim($_SESSION["_COD_ROL_SESS"]) == "1" && trim($_SESSION["_COD_EMP_USU_SESS"]) == ""){
      header("location:" . $_PATH_NIVEL_ATRAS . "sel_emp.php?sMsgJs=_MSG_EMP_ADM&sUriRetorno=" . urlencode($_SERVER["REQUEST_URI"]));
      exit;
    }
?>