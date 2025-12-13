<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_emp_adm.php");        
  include("../include/ver_aut_adm_super.php");        
  include("../include/db_lib.php"); 
  include ("../include/upload_class.php"); 




  
  function uploadCaf(){
    global $_MAX_FILE_CAF, $_PATH_REAL_CAF, $_ARRAY_EXT_CAF, $_RUTA_BATCH;
    $max_size = $_MAX_FILE_CAF;        // the max. size for uploading
    $my_upload = new file_upload;        
    $my_upload->upload_dir = $_PATH_REAL_CAF; //   RUTA DEL UPLOAD
    $my_upload->extensions = $_ARRAY_EXT_CAF;        // EXTENSION ACEPTADAS
    $my_upload->language = "es"; // 
    $my_upload->max_length_filename = 100;            //  LARGO NOMBRE MAX DEL ARCHIVO
    $my_upload->rename_file = true;                   // RENOMBRAR ARCHIVO
    $my_upload->replace = true;                       // SOBREESCRIBE ARCHIVO
    $my_upload->the_temp_file = $_FILES['sFileCaf']['tmp_name'];
    $my_upload->the_file = $_FILES['sFileCaf']['name'];
    $my_upload->http_error = $_FILES['sFileCaf']['error'];
    $my_upload->do_filename_check = "n";             // valida el nombre del archivo
    $new_name = "caf_xml"; 


    if (!$my_upload->upload($new_name)){

      $arrayTmp = $my_upload->message;
      $sMsgJs = "";
      for($i=0; $i < sizeof($arrayTmp); $i++)
        $sMsgJs .= $arrayTmp[$i] . "\n";
      header("location:form_caf.php?sMsgJs=" . $sMsgJs);
      exit;        
    }
    else{
		$new_name = $new_name . $my_upload->get_extension($my_upload->the_file);

	error_reporting(E_ALL|E_STRICT);

                if(trim($_SESSION["_RUT_EMP_SESS"]) == "96928010-8")
                        convertirAUTF8($_PATH_REAL_CAF . $new_name );

		if(substr(php_uname(), 0, 7) == "Windows"){
			$sBatch = $_RUTA_BATCH . "ejecutaProcesaCAFBD.bat " . $_PATH_REAL_CAF . $new_name;
			exec($sBatch);
//			exec('start /B "window_name" "' . $sBatch . '"');
		}
		else{
			$sBatch = $_RUTA_BATCH . "ejecutaProcesaCAFBD.sh " . $_PATH_REAL_CAF . $new_name;
		$p=exec($sBatch,$a,$b);
		}
      // llamar a funcion java que carga el xml
    }
  }

function convertirAUTF8($archivo) {
    // Leer el contenido del archivo
    $contenido = file_get_contents($archivo);

    // Detectar la codificación del archivo
    $encoding = mb_detect_encoding($contenido, ['UTF-8', 'ISO-8859-1', 'ASCII', 'WINDOWS-1252'], true);

    // Si no es UTF-8, convertirlo a UTF-8
    if ($encoding !== 'UTF-8') {
        // Convertir a UTF-8
        $contenidoUTF8 = mb_convert_encoding($contenido, 'UTF-8', $encoding);
        
        // Guardar el archivo con el mismo nombre, pero en UTF-8
        file_put_contents($archivo, $contenidoUTF8);
    } 
}

  
  uploadCaf();
      
  header("location:fin_caf.php");
  exit;    
?>
