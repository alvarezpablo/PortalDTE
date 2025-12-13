<?php 
  ini_set('post_max_size', '800M');
  ini_set('upload_max_filesize', '800M');
  ini_set('memory_limit', '1024M');
  ini_set('max_execution_time', '36000');
  ini_set('max_input_time', '36000');

  include("../include/config.php");  
  include("../include/ver_aut.php");      

  include("../include/db_lib.php"); 
  include ("../include/upload_class.php"); 
  
  $conn = conn();

  function uploadCaf(){
    global $_MAX_FILE_CLIE_ELEC, $_PATH_REAL_CLIE_ELEC, $_ARRAY_EXT_CLIE_ELEC, $conn;
    $max_size = $_MAX_FILE_CLIE_ELEC;        // the max. size for uploading
    $my_upload = new file_upload;        
    $my_upload->upload_dir = $_PATH_REAL_CLIE_ELEC; //   RUTA DEL UPLOAD
    $my_upload->extensions = $_ARRAY_EXT_CLIE_ELEC;        // EXTENSION ACEPTADAS
    $my_upload->language = "es"; // 
    $my_upload->max_length_filename = 100;            //  LARGO NOMBRE MAX DEL ARCHIVO
    $my_upload->rename_file = true;                   // RENOMBRAR ARCHIVO
    $my_upload->replace = true;                       // SOBREESCRIBE ARCHIVO
    $my_upload->the_temp_file = $_FILES['sFileClieElec']['tmp_name'];
    $my_upload->the_file = $_FILES['sFileClieElec']['name'];
    $my_upload->http_error = $_FILES['sFileClieElec']['error'];
    $my_upload->do_filename_check = "n";             // valida el nombre del archivo
    $new_name = "cont_elec_csv"; 
    
    if (!$my_upload->upload($new_name)){
      $arrayTmp = $my_upload->message;
      $sMsgJs = "";
      for($i=0; $i < sizeof($arrayTmp); $i++)
        $sMsgJs .= $arrayTmp[$i] . "\n";
      header("location:form_cont_elec.php?sMsgJs=" . $sMsgJs);
      exit;        
    }
    else{
		$path_csv = $_PATH_REAL_CLIE_ELEC . $new_name . $my_upload->get_extension($my_upload->the_file); // path del csv
		$gestor = file_get_contents($path_csv);
		
		if($gestor != false){
//			$sql = "DELETE FROM contrib_elec";
//			nrExecuta($conn, $sql);
$rutaSql = $_PATH_REAL_CLIE_ELEC.$new_name. ".sql";
//echo $rutaSql;
//$file = fopen($rutaSql, "w");
			
			$aFilas = explode("\n",$gestor);
			$contFila = 0;
			for($i=1; $i < sizeof($aFilas); $i++){
				$contFila++;
				
/*				if($contFila > 1000){
					// Desconectar de la base de datos
					$conn->Close();
					$conn = conn();
					$contFila = 0;
				}
*/
				$aCol = explode(";",$aFilas[$i]);
				$dRut = explode("-",$aCol[0]);
				$aCol[3] = str_replace("/","-",$aCol[3]);
				$dFec = explode("-",$aCol[3]);

	$sql = "SELECT rut_contr, email_contr FROM contrib_elec WHERE CAST(rut_contr as VARCHAR) = '" . str_replace("'","''",$dRut[0]) . "'";
        $result = rCursor($conn, $sql);

        if(!$result->EOF){
		//echo "entro update "  . trim($aCol[4]) . " - " . trim($result->fields["email_contr"]) . "<br>";
		if(trim($aCol[4]) == trim($result->fields["email_contr"]))
			continue;

		$sql = "UPDATE contrib_elec SET 
				rs_contr = '" . trim(str_replace("'","''",$aCol[1])) . "' ,
				nrores_contr = '" . trim(str_replace("'","''",$aCol[2])) . "' ,
				fecres_contr = '" . trim($dFec[2]) . "-" . trim($dFec[1]) . "-" . trim($dFec[0]) . "',
				email_contr='" . trim(str_replace("'","''",$aCol[4])) . "' 
			WHERE 
				rut_contr = '" . str_replace("'","''",$dRut[0]) . "'";
//fwrite($file, str_replace("\n","",$sql) .";\n" . PHP_EOL);
		nrExecuta($conn, $sql);
	}
	else{
//print_r($aCol);
		if($aCol[0] != ""){
			echo "entro insert"  . trim($aCol[4]) . " - " . trim($result->fields["email_contr"]) . "<br>";
			$sql = "INSERT INTO contrib_elec( ";
			$sql .= "       rut_contr,  ";
			$sql .= "       rs_contr,  ";
			$sql .= "       nrores_contr, ";
			$sql .= "       fecres_contr,  ";
			$sql .= "       email_contr ) ";
			$sql .= " values( ";
			$sql .= "       '" . str_replace("'","''",$dRut[0]) . "', ";
			$sql .= "       '" . str_replace("'","''",$aCol[1]) . "', ";
			$sql .= "       '" . str_replace("'","''",$aCol[2]) . "', ";
			$sql .= "       '" . trim($dFec[2]) . "-" . trim($dFec[1]) . "-" . trim($dFec[0]) . "', ";
			$sql .= "       '" . str_replace("'","''",$aCol[4]) . "') ";
			//fwrite($file,  str_replace("\n","",$sql). ";\n" . PHP_EOL);
			//echo $sql . "<br>"; 
			nrExecuta($conn, $sql); 	
		}
	}

	$sql = "UPDATE clientes SET emi_elec_cli = 'S',
		acrec_email = 'S' WHERE rut_cli = '" . str_replace("'","''",$dRut[0]) . "'";
//echo $sql . "<br>";
	nrExecuta($conn, $sql);
//fwrite($file,  str_replace("\n","",$sql). ";\n" . PHP_EOL);

			}
//fclose($file);

		}
	}
      
  }      
  
  uploadCaf();

      
  header("location:fin_cont_elec.php");
  exit;    
?>
