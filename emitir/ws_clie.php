<?php
	include("../include/config.php");  
	include("../include/db_lib.php"); 
//	include("../include/tables.php"); 
	include("../include/ver_aut.php");      
    include("../include/ver_emp_adm.php");        

	$conn = conn();

	include("inc/funciones.php");
	header("Content-Type: application/json; charset=ISO-8859-1");
	ini_set("default_charset", "ISO-8859-1");

	// Verificar si la solicitud es POST
	if ($_SERVER['REQUEST_METHOD'] === 'POST' || 1==1) {
		// Leer el cuerpo de la solicitud
		$input = json_decode(file_get_contents('php://input'), true);

	    if (isset($input['rut_cliente'])) {
			$RUTRecep=explode("-",trim($input["rut_cliente"]));
//			$RUTRecep=explode("-",trim("11111111-1"));

			$sql = "SELECT cod_clie, raz_social, giro_clie, dir_clie, ciud_cli, com_clie FROM clientes 
					WHERE 
							rut_cli ='" . str_replace("'","''",trim($RUTRecep[0])) . "' AND 
							dv_cli ='" . strtoupper(str_replace("'","''",trim($RUTRecep[1]))) . "' AND 
							codi_empr = '". trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";
//echo $sql;
                                $response = [
                                        'status' => 'error',
                                        'message' => 'Cliente no Existe',
                                        'sql' => $sql
                                ];
			$result = rCursor($conn, $sql);
			if(!$result->EOF) {
				$cod_clie = trim($result->fields["cod_clie"]);	
				$raz_social = trim($result->fields["raz_social"]);	
				$giro_clie = trim($result->fields["giro_clie"]);	
				$dir_clie = trim($result->fields["dir_clie"]);	
				$ciud_cli = trim($result->fields["ciud_cli"]);	
				$com_clie = trim($result->fields["com_clie"]);	

//echo $dir_clie;
//exit;
				// Crear la respuesta

				$response = [
					'status' => 'success',
					'message' => 'Datos recibidos correctamente'
					,'data' => [
						'cod_clie' => $cod_clie,
						'raz_social' => $raz_social,
						'giro_clie' => $giro_clie,
						'dir_clie' => $dir_clie,
						'ciud_cli' => $ciud_cli,
						'com_clie' => $com_clie	
					] 
				];
					$response['data']['dir_clie'] = utf8_encode($response['data']['dir_clie']);

//				print_r($response);
//				echo json_encode($response, JSON_UNESCAPED_UNICODE);

			}
			else{
				
				// Crear la respuesta
				$response = [
					'status' => 'error',
					'message' => 'Cliente no Existe',
					'sql' => ''
				];

			}
		}
		else {
			// Respuesta en caso de datos faltantes
			$response = [
				'status' => 'error',
				'message' => 'Falta el RUT de Busqueda'
			];
		}
	}
	else {
		// Respuesta en caso de que no se utilice el método POST
		$response = [
			'status' => 'error',
			'message' => 'Método no permitido'
		];
	}

	// Enviar la respuesta en formato JSON
	echo json_encode($response);

?>
