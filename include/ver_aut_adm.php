<?php 
    /* ARCHIVO A INCLUIR DESPUES DE VER_AUT PARA LAS APLICAICONES QUE SON PERFIL ADMINISTRACION */
   
    /****** VALIDA QUE SI TENGA ROL ADM *******/
    if(trim($_SESSION["_COD_ROL_SESS"]) != "1"){
      header("location:" . $_PATH_NIVEL_ATRAS . "main.php");
      exit;
    }
?>