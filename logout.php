<?php 
      session_start();
      $_SESSION = array();
      session_destroy();
      header("location:login.php?sMsgJs=_MSG_USER_LOGOUT");
      exit;
?>