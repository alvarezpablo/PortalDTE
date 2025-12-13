<?php

  include("../include/config.php");
$_NO_MSG=true;
  include("../include/db_lib.php");
  include("../include/tables.php");

        include("../include/ver_aut.php");
    include("../include/ver_emp_adm.php");

?>

<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>TrackDTE</title>
 <style>
 .datagrid table { border-collapse: collapse; text-align: left; width: 100%;} 
 .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #006699; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }
 .datagrid table td, .datagrid table th { padding: 3px 10px; }
 .datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #0070A8; } 
 .datagrid table thead th:first-child { border: none; }
 .datagrid table tbody td { color: #00557F; border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal; }
 .datagrid table tbody .alt td { background: #E1EEf4; color: #00557F; }
 .datagrid table tbody td:first-child { border-left: none; }
 .datagrid table tbody tr:last-child td { border-bottom: none; }
 .datagrid table tfoot td div { border-top: 1px solid #006699;background: #E1EEf4;} 
 .datagrid table tfoot td { padding: 0; font-size: 12px } 
 .datagrid table tfoot td div{ padding: 2px; }
 .datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: left; }
 .datagrid table tfoot  li { display: inline; }
 .datagrid table tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #FFFFFF;border: 1px solid #006699;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; }
 .datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #00557F; color: #FFFFFF; background: none; background-color:#006699;}div.dhtmlx_window_active, div.dhx_modal_cover_dv { position: fixed !important; } 

 .container {
	width: 30em;
	overflow-x: auto;
	white-space: nowrap;
	border-collapse: collapse; text-align: left; width: 100%;
	font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #006699; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; 
}
 .container td, .container th { padding: 3px 10px; }
 .container thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; color:#FFFFFF; font-size: 12px; font-weight: bold; border-left: 1px solid #0070A8; } 
 .container thead th:first-child { border: none; }
 .container tbody td { color: #00557F; border-left: 1px solid #E1EEF4;font-size: 10px;font-weight: normal; }
 .alink {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; color:#FFFFFF; font-size: 12px; font-weight: bold; border-left: 1px solid #0070A8; } 
 .container tbody .alt td { background: #E1EEf4; color: #00557F; }
 .container tbody td:first-child { border-left: none; }
 .container tbody tr:last-child td { border-bottom: none; }
 .container tfoot td div { border-top: 1px solid #006699;background: #E1EEf4;} 
 .container tfoot td { padding: 0; font-size: 12px } 
 .container tfoot td div{ padding: 2px; }
 .container tfoot td ul { margin: 0; padding:0; list-style: none; text-align: left; }
 .container tfoot  li { display: inline; }
 .container tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #FFFFFF;border: 1px solid #006699;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; }
 .container tfoot ul.active, .container tfoot ul a:hover { text-decoration: none;border-color: #00557F; color: #FFFFFF; background: none; background-color:#006699;}div.dhtmlx_window_active, div.dhx_modal_cover_dv { position: fixed !important; } 


.myButton {
	-moz-box-shadow:inset 0px 1px 0px 0px #dcecfb;
	-webkit-box-shadow:inset 0px 1px 0px 0px #dcecfb;
	box-shadow:inset 0px 1px 0px 0px #dcecfb;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #bddbfa), color-stop(1, #80b5ea));
	background:-moz-linear-gradient(top, #bddbfa 5%, #80b5ea 100%);
	background:-webkit-linear-gradient(top, #bddbfa 5%, #80b5ea 100%);
	background:-o-linear-gradient(top, #bddbfa 5%, #80b5ea 100%);
	background:-ms-linear-gradient(top, #bddbfa 5%, #80b5ea 100%);
	background:linear-gradient(to bottom, #bddbfa 5%, #80b5ea 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#bddbfa', endColorstr='#80b5ea',GradientType=0);
	background-color:#bddbfa;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #84bbf3;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #528ecc;
}
.myButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #80b5ea), color-stop(1, #bddbfa));
	background:-moz-linear-gradient(top, #80b5ea 5%, #bddbfa 100%);
	background:-webkit-linear-gradient(top, #80b5ea 5%, #bddbfa 100%);
	background:-o-linear-gradient(top, #80b5ea 5%, #bddbfa 100%);
	background:-ms-linear-gradient(top, #80b5ea 5%, #bddbfa 100%);
	background:linear-gradient(to bottom, #80b5ea 5%, #bddbfa 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#80b5ea', endColorstr='#bddbfa',GradientType=0);
	background-color:#80b5ea;
}
.myButton:active {
	position:relative;
	top:1px;
}

.fondo{
	background-color: #FBFCFC; 
	background-image: url("../skins/aqua/images/main_bg.gif"); 
	background-repeat: repeat-y;
}

  </style>
 </head>
 <body class="fondo">
<?php


  $conn = conn();
  $nFolioDte = $_GET["nFolioDte"];
  $nTipoDocu = $_GET["nTipoDocu"];

  // Enviaremos un xml

	$sql = "SELECT tipo_docu, folio_dte, to_char(ts,'dd-mm-yyyy hh:MI') as ts2 ,est_dte, msg_track_dte FROM trackdte WHERE tipo_docu = '" . str_replace("'","''",$nTipoDocu) . "' AND folio_dte = '" . str_replace("'","''",$nFolioDte) . "' and codi_empr = '". trim($_SESSION["_COD_EMP_USU_SESS"]) . "' order by ts asc";
	$result = rCursor($conn, $sql);
        $clase = "alt";
	$entra = false;
	echo "<h2><center>Movimientos de Tipo DTE: $nTipoDocu. Folio: $nFolioDte</center></h2><br>";
	
	echo "<table class='container'><thead><tr><th>Fecha</th><th>Glosa</th></tr>\n  </thead> <tbody>";
	while (!$result->EOF) {
		$entra = true;
		$tipo_docu = trim($result->fields["tipo_docu"]);
		$folio_dte =  trim($result->fields["folio_dte"]);
		$est_dte =  trim($result->fields["est_dte"]);
		$ts =  trim($result->fields["ts2"]);
		$msg_track_dte =  trim($result->fields["msg_track_dte"]);

		if(($tipo_docu == "39" || $tipo_docu == "41") and ($est_dte == "64" || $est_dte == "77")){
//if(($tipo_docu == "39" || $tipo_docu == "41") ){
			$sql = "SELECT msg_xdte, num_envioboleta FROM xmldte WHERE tipo_docu = '" . str_replace("'","''",$nTipoDocu) . "' AND folio_dte = '" . str_replace("'","''",$nFolioDte) . "' and codi_empr = '". trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";
			$result2 = rCursor($conn, $sql);
			if(!$result2->EOF) {
                		$descextra = str_replace("\n","<br>",trim($result2->fields["msg_xdte"]));
				$numboleta = str_replace("\n","<br>",trim($result2->fields["num_envioboleta"]));  
			}
			if(trim(str_replace("<br>","",$descextra)) == "" && $numboleta != ""){
				$sql = "SELECT msg_sii FROM xmlenvioboleta WHERE num_xed = '" . $numboleta. "'";
				$result3 = rCursor($conn, $sql); 
                        	if(!$result3->EOF) {
                	                $msgsii = trim($result3->fields["msg_sii"]);
			//		echo $msgsii;
	                        }
			}

			echo "<tr class='$alt'><td>$ts</td><td>$msg_track_dte<br>$descextra</td></tr>\n";
		}
		else	
			echo "<tr class='$alt'><td>$ts</td><td>$msg_track_dte</td></tr>\n";

		if($alt == "")
			$alt = "alt";
		else
			$alt = "";

		$result->MoveNext();
	}

	if($entra == false){
		$tipo_docu=$nTipoDocu;
                if(($tipo_docu == "39" || $tipo_docu == "41") ){
                        $sql = "SELECT msg_xdte, num_envioboleta FROM xmldte WHERE tipo_docu = '" . str_replace("'","''",$nTipoDocu) . "' AND folio_dte = '" . str_replace("'","''",$nFolioDte) . "' and codi_empr = '". trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";
                        $result2 = rCursor($conn, $sql);
                        if(!$result2->EOF) {
                                $descextra = str_replace("\n","<br>",trim($result2->fields["msg_xdte"]));
                                $numboleta = str_replace("\n","<br>",trim($result2->fields["num_envioboleta"]));
                        }
                        if(trim($numboleta) != ""){
                                $sql = "SELECT msg_sii FROM xmlenvioboleta WHERE num_xed = '" . $numboleta. "'";
                                $result3 = rCursor($conn, $sql);
                                if(!$result3->EOF) {
                                        $msgsii = trim($result3->fields["msg_sii"]);
					$data = json_decode($msgsii, true);  // true convierte el JSON a un array asociativo
					$estado = $data['estado'];
					
					if($estado == "RSC"){
						$msg_track_dte = $data['detalle_rep_rech'][0]["descripcion"];
						$descextra =  $data['detalle_rep_rech'][0]['error'][0]['descripcion'];
					}
					if($estado == "EPR"){
						
					}						

                        //              echo $msgsii;
                                }
                        }

                        echo "<tr class='$alt'><td>$ts</td><td>$msg_track_dte<br>$descextra</td></tr>\n";
                }   
	}


	echo "</tbody></table>";
?>


 </body>
</html>
