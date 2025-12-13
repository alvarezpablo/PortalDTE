<?php 
// vuelve a firmar los dte que estan rechazaso, esto es temporal mientras se resuelve el rechazo por error de firma 

	include("../include/config.php");
	include("../include/db_lib.php");
	$conn = conn();

	$sql = "SELECT codi_empr, tipo_docu, folio_dte, COALESCE(repara_bole,0) as repara FROM xmldte WHERE 
					 est_xdte=77 and tipo_docu not in (39,41) AND ts >= to_date('2025-03-22','YYYY-MM-DD') 
 and COALESCE(repara_bole,0) < 10 
					
					";

//					and COALESCE(xml_temp,'') = '' 
// 					and folio_dte=522200

	$result = rCursor($conn, $sql);

	while (!$result->EOF) {
		$codi_empr = trim($result->fields["codi_empr"]);
		$tipo = trim($result->fields["tipo_docu"]);
		$folio = trim($result->fields["folio_dte"]);
		$repara = intval(trim($result->fields["repara"])) + 1;

		$sql = "UPDATE xmldte SET 
								repara_bole=$repara,
								stqueue=0,
								est_xdte=0 
				WHERE	folio_dte = '" . str_replace("'","''",$folio) . "' AND  
						tipo_docu = '" . str_replace("'","''",$tipo) . "' AND  
						codi_empr = $codi_empr";
		nrExecuta($conn, $sql);

		echo "Reparando empresa : $codi_empr , tipo : $tipo, folio : $folio por $repara veces <br>";
//		echo $sql . "<br>";


		$result->MoveNext();
	}  

        $sql = "SELECT codi_empr, tipo_docu, folio_dte, COALESCE(repara_bole,0) as repara FROM xmldte WHERE
                                         est_xdte=77 and tipo_docu in (39,41) AND ts >= to_date('2025-03-22','YYYY-MM-DD') and
					codi_empr not in (70,73,93,167,193,334,7,231,259,69,335,70,71,229,337) 
 and COALESCE(repara_bole,0) < 10 

                                        ";

//                                      and COALESCE(xml_temp,'') = ''
//                                      and folio_dte=522200

        $result = rCursor($conn, $sql);

        while (!$result->EOF) {
                $codi_empr = trim($result->fields["codi_empr"]);
                $tipo = trim($result->fields["tipo_docu"]);
                $folio = trim($result->fields["folio_dte"]);
                $repara = intval(trim($result->fields["repara"])) + 1;

                $sql = "UPDATE xmldte SET
                                                                repara_bole=$repara,
                                                                stqueue=0,
                                                                est_xdte=0
                                WHERE   folio_dte = '" . str_replace("'","''",$folio) . "' AND
                                                tipo_docu = '" . str_replace("'","''",$tipo) . "' AND
                                                codi_empr = $codi_empr";
                nrExecuta($conn, $sql);

                echo "Reparando empresa : $codi_empr , tipo : $tipo, folio : $folio por $repara veces <br>";
//              echo $sql . "<br>";


                $result->MoveNext();
        }



//	echo $xml;

	echo "FIN !!!<br>";
?>
