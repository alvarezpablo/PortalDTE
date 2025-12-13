<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_emp_adm.php"); 
  include("../include/db_lib.php"); 
  include ("../include/upload_class.php"); 
  
  function uploadCaf(){
    global $_MAX_FILE_CAF, $_PATH_REAL_CAF, $_RUTA_BATCH;
	$_ARRAY_EXT_EXCEL = array(".xls",".xlsx");

    $max_size = 100000000;        // the max. size for uploading
    $my_upload = new file_upload;        
    $my_upload->upload_dir = $_PATH_REAL_CAF; //   RUTA DEL UPLOAD
    $my_upload->extensions = $_ARRAY_EXT_EXCEL;        // EXTENSION ACEPTADAS
    $my_upload->language = "es"; // 
    $my_upload->max_length_filename = 100;            //  LARGO NOMBRE MAX DEL ARCHIVO
    $my_upload->rename_file = true;                   // RENOMBRAR ARCHIVO
    $my_upload->replace = true;                       // SOBREESCRIBE ARCHIVO
    $my_upload->the_temp_file = $_FILES['sFileCaf']['tmp_name'];
    $my_upload->the_file = $_FILES['sFileCaf']['name'];
    $my_upload->http_error = $_FILES['sFileCaf']['error'];
    $my_upload->do_filename_check = "n";             // valida el nombre del archivo
    //$new_name = "caf_xml"; 

    if (!$my_upload->upload()){
      $arrayTmp = $my_upload->message;
      $sMsgJs = "";
      for($i=0; $i < sizeof($arrayTmp); $i++)
        $sMsgJs .= $arrayTmp[$i] . "\n";
      header("location:form_excel.php?sMsgJs=" . $sMsgJs);
      exit;        
    }
    else{
		$new_name = $my_upload->upload_dir . $my_upload->file_copy;		// $new_name . $my_upload->get_extension($my_upload->the_file);
		echo "Archivo subido. " . $new_name;
		header("location:generar.php?r=" . urlencode($new_name));
		exit;
    }
  }
  
  uploadCaf();
      
//  header("location:fin_caf.php");
  exit;    
?>
