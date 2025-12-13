<?php 
    session_start();

    if($_NIVEL_RAIZ != true)
      $_PATH_NIVEL_ATRAS = "../";
    
    /****** VALIDA QUE ESTE AUTENTIFICADO *****/
    if(trim($_SESSION["_COD_USU_SESS"]) == "" || trim($_SESSION["_COD_ROL_SESS"]) == ""){
      //header("location:" . $_PATH_NIVEL_ATRAS . "login.php?sMsgJs=_MSG_USER_EXPIRE");
	  header("location:" . $_PATH_NIVEL_ATRAS . "login.php");
      exit;
    }

    /****** VALIDA QUE SI NO ES ADMINISTRADOR TENGA SELECCIONADA UNA EMPRESA *******/
    if(trim($_SESSION["_COD_EMP_USU_SESS"]) == "" && trim($_SESSION["_COD_ROL_SESS"]) != "1"){
	  $sUrl = $_PATH_NIVEL_ATRAS . "sel_emp.php?sUriRetorno=" . urlencode($_SERVER["REQUEST_URI"]);
      header("location:" . $sUrl);
      exit;
    }

//    if(trim($_SESSION["MSG_CONTRATO"]) != "" && $_NO_MSG != true){
	if((trim($_SESSION["_COD_EMP_USU_SESS"]) == "303" || trim($_SESSION["_COD_EMP_USU_SESS"]) == "304" || trim($_SESSION["_COD_EMP_USU_SESS"]) == "301" || trim($_SESSION["_COD_EMP_USU_SESS"]) == "302" || trim($_SESSION["_COD_EMP_USU_SESS"]) == "282") && trim($_SESSION["_COD_ROL_SESS"]) != "1"){
#      	$_SESSION = array();
#        header("location: /logout.php");
#	header("location:/login.php?sMsgJs=" . trim($_SESSION["MSG_CONTRATO"]) );
//	echo "<script>alert('" . trim($_SESSION["MSG_CONTRATO"]) . "');</script>";
//	header("location:" . $_PATH_NIVEL_ATRAS . "login.php");
//	exit;
    }


?>
