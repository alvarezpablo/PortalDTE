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

function uploadLicencia(){
    global $_MAX_FILE_LIC, $_PATH_REAL_LIC_DIGITAL, $_ARRAY_EXT_LIC, $sRutEmp, $conn, $nCodEmp, $_RUTA_BASE;

    $sql = "SELECT rut_empr FROM empresa WHERE codi_empr = '" . str_replace("'","''",$nCodEmp) . "'";
    $result = rCursor($conn, $sql);
    if(!$result->EOF){

	  $sRutEmp = trim($result->fields[0]); 

	if($_PATH_REAL_LIC_DIGITAL == "")
		$_PATH_REAL_LIC_DIGITAL = $_RUTA_BASE . "Archivos/" . $sRutEmp . "/Licencia/";

		$max_size = $_MAX_FILE_LIC;        // the max. size for uploading
		$my_upload = new file_upload;        
		$my_upload->upload_dir = $_PATH_REAL_LIC_DIGITAL; //   RUTA DEL UPLOAD    
		$my_upload->extensions = $_ARRAY_EXT_LIC;        // EXTENSION ACEPTADAS
		$my_upload->language = "es"; // 
		$my_upload->max_length_filename = 100;            //  LARGO NOMBRE MAX DEL ARCHIVO
		$my_upload->rename_file = true;                   // RENOMBRAR ARCHIVO
		$my_upload->replace = true;                       // SOBREESCRIBE ARCHIVO
		$my_upload->the_temp_file = $_FILES['sPathLicencia']['tmp_name'];
		$my_upload->the_file = $_FILES['sPathLicencia']['name'];
		$my_upload->http_error = $_FILES['sPathLicencia']['error'];
		$my_upload->do_filename_check = "n";             // valida el nombre del archivo
			 
		if ($my_upload->upload("licencia")) { 
		  $new_name = $my_upload->upload_dir.$my_upload->the_file;
	  //    $extFile = $my_upload->get_extension($my_upload->the_file);

		  $sql = "UPDATE empresa SET ";
		  $sql .= "   path_licencia = '" . str_replace("'","''",$new_name) . "' ";
		  $sql .= " WHERE ";
		  $sql .= "   rut_empr = '" . str_replace("'","''",$sRutEmp) . "'";        
		  nrExecuta($conn, $sql);
		 }
		 else{
			   header("location:licencia.php?sMsgJs=_MSG_LICENCIA_FALLA");
			   exit;
		 }
	}
  }   

  uploadLicencia();

  header("location:fin_licencia.php");
  exit;    
?>
