<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm_super.php");        
  include("../include/ver_emp_adm.php");         
  include("../include/db_lib.php"); 
  include ("../include/upload_class.php"); 

  $nCodEmp = trim($_POST["nCodEmp"]);
  $sClaveCert = trim($_POST["sClaveCert"]);
  
  $conn = conn();

  function uploadCertificado(){
    global $_MAX_FILE_CERT, $_PATH_REAL_CERT_DIGITAL, $_ARRAY_EXT_CERT, $sAccion, $sClaveCert, $nCodEmp, $conn, $_RUTA_BASE;    


    $sql = "SELECT rut_empr, dv_empr FROM empresa WHERE codi_empr = '" . str_replace("'","''",$nCodEmp) . "'";
    $result = rCursor($conn, $sql);
    if(!$result->EOF){

	  $sRutEmp = trim($result->fields[0]); 
      $sDvEmp = trim($result->fields[1]); 


	if($_PATH_REAL_CERT_DIGITAL == "")
		$_PATH_REAL_CERT_DIGITAL = $_RUTA_BASE . "Archivos/" . $sRutEmp . "/Certificado/";

	  
	  $sql = "SELECT rut_empresa FROM certificado WHERE rut_empresa = '" . str_replace("'","''",$sRutEmp) . "'";
	  $result = rCursor($conn, $sql);
	  if(!$result->EOF)
		$sAccion = "M";
	  else
	    $sAccion = "I";
			
	 //   if($_FILES['sPathCert']['tmp_name'] == ""){

	 //   }
	//    else{

		$max_size = $_MAX_FILE_CERT;        // the max. size for uploading
		$my_upload = new file_upload;        
		$my_upload->upload_dir = $_PATH_REAL_CERT_DIGITAL; //   RUTA DEL UPLOAD    
		$my_upload->extensions = $_ARRAY_EXT_CERT;        // EXTENSION ACEPTADAS
		$my_upload->language = "es"; // 
		$my_upload->max_length_filename = 100;            //  LARGO NOMBRE MAX DEL ARCHIVO
		$my_upload->rename_file = false;                   // RENOMBRAR ARCHIVO
		$my_upload->replace = true;                       // SOBREESCRIBE ARCHIVO
		$my_upload->the_temp_file = $_FILES['sPathCert']['tmp_name'];
		$my_upload->the_file = $_FILES['sPathCert']['name'];
		$my_upload->http_error = $_FILES['sPathCert']['error'];
		$my_upload->do_filename_check = "n";             // valida el nombre del archivo
			 
		if ($my_upload->upload()) { 
		  $new_name = $my_upload->upload_dir.$my_upload->the_file;
	  //    $extFile = $my_upload->get_extension($my_upload->the_file);

			if($sAccion == "M"){
				  $sql = "UPDATE certificado SET ";
				  $sql .= "   path_certificado = '" . str_replace("'","''",$new_name) . "' ";
				  $sql .= " WHERE ";
				  $sql .= "   rut_empresa = '" . str_replace("'","''",$sRutEmp) . "'";        
				  nrExecuta($conn, $sql);
			}
			else{
				  $sql = "INSERT INTO certificado(rut_empresa, dv_empresa, path_certificado, clave_certificado) VALUES( ";
				  $sql .= "   '" . str_replace("'","''",$sRutEmp) . "',";
				  $sql .= "   '" . str_replace("'","''",$sDvEmp) . "',";
				  $sql .= "   '" . str_replace("'","''",$new_name) . "', ";	      	      
				  $sql .= "   '" . str_replace("'","''",$sClaveCert) . "') ";	      
				  nrExecuta($conn, $sql);    	
			}

			// $sBatch = "/opt/opendte/binCert/binCertifi/bin/CargaCertificadoBatch.sh";
			$sBatch = "/opt/opendte/bin/CargaCertificadoBatch.sh";
			$p=exec($sBatch,$a,$b);
		}
		 else{
			   header("location:certificado.php?sMsgJs=_MSG_CERTIFICADO_FALLA");
			   exit;
		 }

		if(trim($sClaveCert) != ""){
			$sql = "UPDATE certificado SET ";
			$sql .= "   clave_certificado = '". str_replace("'","''",$sClaveCert) ."'";  
			$sql .= " WHERE ";
			$sql .= "   rut_empresa = '" . str_replace("'","''",$sRutEmp) . "'";      
			nrExecuta($conn, $sql);
		}
    }  	
  } 
  
  uploadCertificado();

  header("location:fin_cert.php");
  exit;    
?>
