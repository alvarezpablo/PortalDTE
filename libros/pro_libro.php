<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm_super.php");        
  include("../include/db_lib.php"); 
  include ("../include/upload_class.php"); 


  function rutEmpresa(){
	$conn = conn();

	$sql = "select (rut_empr || '-' || dv_empr) as rut from empresa where codi_empr=".$_SESSION["_COD_EMP_USU_SESS"];
	$result = rCursor($conn, $sql);

	if(!$result->EOF) 
		return trim($result->fields["rut"]);
	else
		return false;
	
  }
  
  function validaRespuesta($a, $fileLib){

//	print_r($a);
//	echo "<br>OAOAOAOAO<br>";
//	echo $a[sizeof($a)-1];
//print_r($a);
//exit;
	if(trim($a[sizeof($a)-1]) != "==> OK"){
		$msj="";
		for($i=0; $i<sizeof($a);$i++){
			$msj.= $a[$i]."\n";
		}
	//echo "OAOAOA";
	//exit;
	@unlink($fileLib); // borra libro

	echo "<html>\n";
	echo "<head>\n";
	echo "<title></title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<form name=\"myform\" method=\"post\" action=\"fin_libro.php\">\n";
	echo "<input type=\"hidden\" name=\"sMsgJs\" value=\"" .  str_replace('"',"",$msj) . "\">\n";
	echo "<script language=\"javascript\">document.myform.submit();</script>\n";
	echo "</form>\n";
	echo "</body>\n";
	echo "</html>\n";

//		header("location:fin_libro.php?sMsgJs=".urlencode($msj));
      		exit;
	}
  }

  function uploadLibro(){
    global $_MAX_FILE_LIBRO, $_PATH_REAL_DTE_LIBROS, $_ARRAY_EXT_LIBRO, $_RUTA_BATCH;

    if(($rut_empr = rutEmpresa()) == false){
      header("location:form_libro.php?sMsgJs=Debe+seleccionar+rut");
      exit;
    }
    
    $max_size = $_MAX_FILE_LIBRO;        // the max. size for uploading
    $my_upload = new file_upload;        
    $my_upload->upload_dir = $_PATH_REAL_DTE_LIBROS; //   RUTA DEL UPLOAD
    $my_upload->extensions = $_ARRAY_EXT_LIBRO;        // EXTENSION ACEPTADAS
    $my_upload->language = "es"; // 
    $my_upload->max_length_filename = 100;            //  LARGO NOMBRE MAX DEL ARCHIVO
    $my_upload->rename_file = true;                   // RENOMBRAR ARCHIVO
    $my_upload->replace = false;                       // SOBREESCRIBE ARCHIVO
    $my_upload->the_temp_file = $_FILES['sFileCaf']['tmp_name'];
    $my_upload->the_file = $_FILES['sFileCaf']['name'];
    $my_upload->http_error = $_FILES['sFileCaf']['error'];
    $my_upload->do_filename_check = "n";             // valida el nombre del archivo
    $new_name = "libro_xml"; 

    if (!$my_upload->upload()){
      $arrayTmp = $my_upload->message;
      $sMsgJs = "";
      for($i=0; $i < sizeof($arrayTmp); $i++)
        $sMsgJs .= $arrayTmp[$i] . "\n";
      header("location:form_libro.php?sMsgJs=" . $sMsgJs);
      exit;        
    }
    else{
//		$new_name = $new_name . $my_upload->get_extension($my_upload->the_file);

	error_reporting(E_ALL|E_STRICT);

		if(substr(php_uname(), 0, 7) == "Windows"){
			$sBatch = $_RUTA_BATCH . "ejecutaProcesaTXTLibro.bat " . $rut_empr;
			$p = exec($sBatch,$a,$b);
//			exec('start /B "window_name" "' . $sBatch . '"');
		}
		else{
			$sBatch = $_RUTA_BATCH . "ejecutaProcesaTXTLibro.sh " . $rut_empr;
		        $p=exec($sBatch,$a,$b);
		}
		
	$fileFull = $my_upload->upload_dir . $my_upload->the_file;		
		validaRespuesta($a,$fileFull);
      // llamar a funcion java que carga el xml
    }    
  }

function deleteDir($dir) {
   $iterator = new RecursiveDirectoryIterator($dir);
   foreach (new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file) 
   {
      if ($file->isDir()) {
         ;
      } else {
           unlink($file->getPathname());
      }
   }
//   rmdir($dir);
}


  deleteDir($_PATH_REAL_DTE_LIBROS);
  
  uploadLibro();

      
  header("location:fin_libro.php");
  exit;    
?>
