<?php
//	header('Content-type: application/json');


	session_start();
        include("../include/config.php");
        include("../include/db_lib.php");

	/*	$estado
		REGISTRO: DTE registrados en el regitro de compra y venta.
		PENDIENTE: DTE pendientes de aceptación o rechazo en el registro de compra y venta.
		NO_INCLUIR: DTE que no deben ser incluidos en el registro de compra y venta.
		RECLAMADO: DTE que han sido reclamados	
	*/
	function updateEstado($conn, $rutEmpr, $dvEmpr, $anio, $mes, $estado){
			global $conn;
				$aniomes = $anio.$mes;

				$_WSDL_CONSULTA_SII = "http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/ConsultaRegistroSII";
				$soapClient=new SoapClient($_WSDL_CONSULTA_SII.'?wsdl');
				$soapClient->__setLocation($_WSDL_CONSULTA_SII);
				$parametros=array();
				$parametros["RutEmpresa"]=$rutEmpr . "-" . $dvEmpr;  
				$parametros["periodo"]=$aniomes;
				$parametros["estado"]=$estado;
				$parametros["apikey"]="";
				$sjson = $soapClient->descargaRegistro($parametros);

				$aTemp = explode("<Glosa>",$sjson->return);
				$aTemp = explode("</Glosa>",$aTemp[1]);
//echo $aTemp[0];
				$aJson = json_decode($aTemp[0], true);
			//	print_r($aJson["data"]);
				for($i=1; $i < sizeof($aJson["data"]); $i++){
					//echo $aJson["data"][$i] . "<br><br>";
					if(trim($aJson["data"][$i]) == "")
						continue;

					$aLinea = explode(";",$aJson["data"][$i]);
					$tipoDocu = $aLinea[1];
					$rutProv = $aLinea[3];
					$aRutProv = explode("-",$rutProv);
					$folio = $aLinea[5];
					$fechaRecep = $aLinea[7]; // 23/10/2020 02:05:09
					$fechaAcuse = $aLinea[8]; // 23/10/2020 02:05:09
					$razon_prov = $aLinea[4];
					$fecha_dte = $aLinea[6];
					$exento = $aLinea[9];
					$neto = $aLinea[10];
					$iva = $aLinea[11];
					$total = $aLinea[14];
					if($exento == "") $exento = "0";
					if($neto == "") $neto = "0";
					if($iva == "") $iva = "0";
					if($total == "") $total = "0";

					if($fechaAcuse == "0") 
						$fechaAcuse = "null";
					else
						$fechaAcuse = "TO_TIMESTAMP('$fechaAcuse','DD/MM/YYYY HH24:MI:SS')";


					$sql = "SELECT codi_empr FROM documentoscompras_temp WHERE 
							rut_rec_dte='" . $aRutProv[0] . "' AND tipo_docu = '$tipoDocu' AND 
							fact_ref = '$folio' AND codi_empr = " . $_SESSION["_COD_EMP_USU_SESS"];
					$result = rCursor($conn, $sql);
			//		echo $sql . "<br><br>";
					if(!$result->EOF) {
						$sql = "SELECT acuse_dte, to_char(fech_acuse_dte,'DD/MM/YYYY HH24:MI:SS') fech_acuse_dte, 
										merca_dte, to_char(fech_merca_dte,'DD/MM/YYYY HH24:MI:SS') fech_merca_dte ,
										to_char(fech_limite_sii,'DD/MM/YYYY HH24:MI:SS') fech_limite_sii
								FROM dte_sii_no_openb WHERE 
									rut_prov='$rutProv' AND tipo_docu = '$tipoDocu' AND 
									folio_dte = '$folio' AND codi_empr = " . $_SESSION["_COD_EMP_USU_SESS"];
						$result2 = rCursor($conn, $sql);
						$sqlAcuse = "";	

						if(!$result2->EOF) {
							$acuse_dte = trim($result2->fields["acuse_dte"]);
							$fech_acuse_dte = trim($result2->fields["fech_acuse_dte"]);
							$merca_dte = trim($result2->fields["merca_dte"]);
							$fech_merca_dte = trim($result2->fields["fech_merca_dte"]);
							$fech_limite_sii = trim($result2->fields["fech_limite_sii"]);
							if($acuse_dte != "") $sqlAcuse = " acuse_dte = '" . $acuse_dte . "' , ";
							if($fech_acuse_dte != "") $sqlAcuse .= " fech_acuse_dte = TO_TIMESTAMP('" . $fech_acuse_dte . "','DD/MM/YYYY HH24:MI:SS') , ";
							if($merca_dte != "") $sqlAcuse .= " merca_dte = '" . $merca_dte . "' , ";
							if($fech_merca_dte != "") $sqlAcuse .= " fech_merca_dte = TO_TIMESTAMP('" . $fech_merca_dte . "','DD/MM/YYYY HH24:MI:SS') , ";
							if($fech_limite_sii != "") $sqlAcuse .= " fech_limite_sii = TO_TIMESTAMP('" . $fech_limite_sii . "','DD/MM/YYYY HH24:MI:SS') , ";
							
						}


						
						// Para no perder historia se actualiza la fehca de aceptación o rechazo del DTE en la tabla documentoscompras_temp, previo a borrar el registro 
						$sql = "UPDATE documentoscompras_temp SET " . $sqlAcuse . "		
								fech_recep_sii	= TO_TIMESTAMP('$fechaRecep','DD/MM/YYYY HH24:MI:SS'),
								fech_limite_sii	= TO_TIMESTAMP('$fechaRecep','DD/MM/YYYY HH24:MI:SS') + CAST('8 days' AS INTERVAL),
								estado_sii = '$estado',
								fech_update_sii = now()
							WHERE 
								rut_rec_dte='" . $aRutProv[0] . "' AND tipo_docu = '$tipoDocu' AND fact_ref = '$folio' AND 
								codi_empr = " . $_SESSION["_COD_EMP_USU_SESS"];
						nrExecuta($conn, $sql);
						echo $sql . "<br><br>";
						// borrar el registro de faltantes
						$sql = "DELETE FROM dte_sii_no_openb WHERE 
								rut_prov='$rutProv' AND tipo_docu = '$tipoDocu' AND 
								folio_dte = '$folio' AND codi_empr = " . $_SESSION["_COD_EMP_USU_SESS"];
						nrExecuta($conn, $sql);
					//	echo $sql . "<br><br>";
					}
					else{	// no recibido en openb, se ingresa el registro.
						// Verificar si existe, si no grabar.
						$sql = "SELECT estado_sii, codi_empr FROM dte_sii_no_openb WHERE 
								rut_prov='$rutProv' AND tipo_docu = '$tipoDocu' AND 
								folio_dte = '$folio' AND codi_empr = " . $_SESSION["_COD_EMP_USU_SESS"];
						$result2 = rCursor($conn, $sql);

						if(!$result2->EOF) {
							$est_act = trim($result2->fields["estado_sii"]);
							if($est_act != $estado){	// Se actualiza solo si el estado actual cambia
								$sql = "UPDATE dte_sii_no_openb SET 
											estado_sii = '$estado' ,
											fech_update_sii = now()
										WHERE 
										codi_empr = " . $_SESSION["_COD_EMP_USU_SESS"] . " AND 
										tipo_docu = '" . $tipoDocu . "' AND 
										rut_prov = '" . $rutProv . "' AND
										folio_dte = '" . $folio . "'";
								nrExecuta($conn, $sql);	
						//		echo $sql . "<br><br>";
							}
						}
						else{ 	
							$sql = "INSERT INTO dte_sii_no_openb(codi_empr,tipo_docu,rut_prov,razon_prov, folio_dte, fecha_dte,fecha_recep,fecha_acuse,
									exento, neto, iva, total, estado_sii, fech_update_sii, fech_limite_sii) VALUES( " . $_SESSION["_COD_EMP_USU_SESS"] . ",
									'" . $tipoDocu . "','" . $rutProv . "', '" . str_replace("'","''",$razon_prov) . "', '" . $folio . "', 
									TO_TIMESTAMP('$fecha_dte','DD/MM/YYYY HH24:MI:SS'), TO_TIMESTAMP('$fechaRecep','DD/MM/YYYY HH24:MI:SS'), 
									" .  $fechaAcuse . ", '" . $exento . "', '" . $neto . "', '" . $iva . "', '" . $total . "', '" . $estado . "', now(), 
									TO_TIMESTAMP('$fechaRecep','DD/MM/YYYY HH24:MI:SS') + CAST('8 days' AS INTERVAL))";
								nrExecuta($conn, $sql);		
						//		echo $sql . "<br><br>";								
						}
					}

				}	
				return $aJson["data"];
	}




    if($_SESSION){
//		if($_POST){
			$conn = conn();
			$sql = "select rut_empr, dv_empr from empresa where codi_empr=" . $_SESSION["_COD_EMP_USU_SESS"];                             
			$resultCount = rCursor($conn, $sql);
			if(!$resultCount->EOF) {
				$rutEmpr = trim($resultCount->fields["rut_empr"]);
				$dvEmpr = trim($resultCount->fields["dv_empr"]);

				$anio = "2023"; // trim($_GET["sanio"]);
				$mes = "01"; // trim($_GET["smes"]);

				if($anio != "" && $mes != ""){
					$jPend = updateEstado($conn, $rutEmpr, $dvEmpr, $anio, $mes, "PENDIENTE");
					$jRegistro = updateEstado($conn, $rutEmpr, $dvEmpr, $anio, $mes, "REGISTRO");
					$jReclamo = updateEstado($conn, $rutEmpr, $dvEmpr, $anio, $mes, "RECLAMADO");
					$jNoIncl = updateEstado($conn, $rutEmpr, $dvEmpr, $anio, $mes, "NO_INCLUIR");

					$arrRespuesta = array(
						"Error" => "0",
						"msj" => "Registro Actualizado"
					);

					echo json_encode($arrRespuesta);
					exit;
				}
				else{
					$arrRespuesta = array(
						"Error" => "0",
						"msj" => "Anio y/o mes no recibido"
					);

					echo json_encode($arrRespuesta);
					exit;				
				}


	// for
			}	// if empresa
			else{
				$arrRespuesta = array(
					"Error" => "1",
					"msj" => "Empresa no encontrada"
				);
				echo json_encode($arrRespuesta);
				exit;
			}
	}
	else{
			$arrRespuesta = array(
				"Error" => "2",
				"msj" => "Session Expirada"
			);
			echo json_encode($arrRespuesta);
			exit;
//			echo "<script>alert('Session Expirada');";
//			echo "window.close();</script>";
	}
	?>
