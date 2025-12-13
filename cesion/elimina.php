<?php
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm_super.php");        
  include("../include/db_lib.php"); 
  include ("../include/upload_class.php"); 

  $conn = conn();

  $ok = trim($_POST["ok"]);      
  $tipo_dte = trim($_POST["tipo_docu"]);      
  $folio_dte = trim($_POST["folio_dte"]);      
  $rut_cedente = trim($_POST["rut_cedente"]);      
  $razon_cedente = trim($_POST["razon_cedente"]);      
  $dir_cedente = trim($_POST["dir_cedente"]);      
  $email_cedente = trim($_POST["email_cedente"]);      
  $rut_cesionario = trim($_POST["rut_cesionario"]);      
  $razon_cesionario = trim($_POST["razon_cesionario"]);      
  $dir_cesionario = trim($_POST["dir_cesionario"]);      
  $email_cesionario = trim($_POST["email_cesionario"]);      
  $monto_cesion = trim($_POST["monto_cesion"]);      
  $fecha_venc = trim($_POST["fecha_venc"]);      
  $email_notificacion = trim($_POST["email_notificacion"]);      
  $otras_condic = trim($_POST["otras_condic"]);      
  $email_deudor = trim($_POST["email_deudor"]);      
  $rut_firma = trim($_POST["rut_firma"]);      
  $nombre_firma = trim($_POST["nombre_firma"]);      

  if($ok == "OK"){
	  try{
        $conn->StartTrans();	

		$sql = "SELECT 
					id 
				FROM 
					dte_ceder 
				WHERE 
					codi_empr='" . str_replace("'","''",$_SESSION["_COD_EMP_USU_SESS"]) . "' AND
					tipo_docu = '" . str_replace("'","''",$tipo_dte) . "' AND
					folio_dte = '" . str_replace("'","''",$folio_dte) . "'";
		$result = rCursor($conn, $sql);
		if(!$result->EOF){
			header("location:ceder.php?msg=2");
			exit;
		}

		$sql = "SELECT 
					mont_tot_dte 
				FROM 
					dte_enc
				WHERE 
					codi_empr='" . str_replace("'","''",$_SESSION["_COD_EMP_USU_SESS"]) . "' AND
					tipo_docu = '" . str_replace("'","''",$tipo_dte) . "' AND
					folio_dte = '" . str_replace("'","''",$folio_dte) . "'";
		$result = rCursor($conn, $sql);
		if(!$result->EOF){
			$mont_tot_dte = trim($result->fields["mont_tot_dte"]); 
			if($monto_cesion > $mont_tot_dte){
				header("location:ceder.php?msg=3");
				exit;
			}
		}

		$sql = "INSERT INTO dte_ceder(
			codi_empr,
			tipo_docu,
			folio_dte,
			estado,
			rut_cedente,
			razon_social_cedente,
			direccion_cedente,
			email_cedente,
			rut_cesionario,
			razon_social_cesionario,
			direccion_cesionario,
			email_cesionario,
			monto_cesion,
			fecha_ultimo_vencimiento,
			otras_condiciones,
			email_deudor,
			secuencia_cesion,
			email_notificacion_sii)
		VALUES(
			'" . str_replace("'","''",$_SESSION["_COD_EMP_USU_SESS"]) . "',
			'" . str_replace("'","''",$tipo_dte) . "', 
			'" . str_replace("'","''",$folio_dte) . "',1,
			'" . str_replace("'","''",$rut_cedente) . "',
			'" . str_replace("'","''",$razon_cedente) . "',
			'" . str_replace("'","''",$dir_cedente) . "',
			'" . str_replace("'","''",$email_cedente) . "',
			'" . str_replace("'","''",$rut_cesionario) . "',
			'" . str_replace("'","''",$razon_cesionario) . "',
			'" . str_replace("'","''",$dir_cesionario) . "',
			'" . str_replace("'","''",$email_cesionario) . "',
			'" . str_replace("'","''",$monto_cesion) . "',
			'" . str_replace("'","''",$fecha_venc) . "',
			'" . str_replace("'","''",$otras_condic) . "',
			'" . str_replace("'","''",$email_deudor) . "',1,
			'" . str_replace("'","''",$email_notificacion) . "')";
		nrExecuta($conn, $sql);

		$sql = "SELECT currval('dte_ceder_id_seq') ";
		$result = rCursor($conn, $sql);
		if(!$result->EOF){
			$id = trim($result->fields[0]); 
	
			$sql = "INSERT INTO dte_ceder_rut_autorizado(rut, nombre, dte_ceder) VALUES(
			'" . str_replace("'","''",$rut_firma) . "', 
			'" . str_replace("'","''",$nombre_firma) . "', 
			'" . str_replace("'","''",$id) . "')";
			nrExecuta($conn, $sql);
		} 

		if ($conn->HasFailedTrans() == true){ 
			$conn->FailTrans();
			$error=true;
		}
		$conn->CompleteTrans();

		if($error==true)
			header("location:fin_ceder.php?msg=0");
		else
			header("location:fin_ceder.php");
		exit;
	  }
	  catch(Exception $e){
		header("location:ceder.php?msg=0");
		exit;	  
	  }
  } 
    
?>

