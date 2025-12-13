<?php 
        include("../include/config.php");
//        include("../include/ver_aut.php");
        include("../include/db_lib.php");  
	$conn = conn();	

     //   $sql = "UPDATE xmldte SET est_xdte=1, stqueue=0 WHERE est_xdte = 5 AND tipo_docu NOT IN (39, 41) AND ts >= CURRENT_DATE - INTERVAL '3 days' AND ts < CURRENT_DATE"; 
	$sql = "UPDATE xmldte SET est_xdte=1, stqueue=0 WHERE est_xdte = 5 AND tipo_docu NOT IN (39, 41) AND ts >= NOW() - INTERVAL '3 days' AND ts < NOW() - INTERVAL '1 hour'";
	nrExecuta($conn, $sql);

?>
