<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");        
  include("../include/db_lib.php"); 
  include ("../include/upload_class.php"); 
  
  $nCodUsu = trim($_POST["nCodUsu"]);
  $sIdUsu = trim($_POST["sIdUsu"]);
  $sIdUsuNew = trim($_POST["sIdUsuNew"]);  
  $sPathCert = trim($_POST["sPathCert"]);  
  $sEstUsu = trim($_POST["sEstUsu"]);
  $sCodRolUsu = trim($_POST["sCodRolUsu"]);
  $sAccion = trim($_POST["sAccion"]);
  $sClaveUsu = trim($_POST["sClaveUsu"]);  
  $sClaveCert = trim($_POST["sClaveCert"]);     

  $conn = conn();

  
  function dIngresar($conn){
      global $nCodUsu, $sIdUsu, $sIdUsuNew, $sPathCert, $sEstUsu, $sCodRolUsu, $sAccion, $sClaveUsu, $sClaveCert;
  
      if(bExisUser($conn, $nCodUsu, $sIdUsuNew, $sAccion) == false)
        bReturnMsg("_MSG_USER_ING_EXIS");
                
      $sql = "INSERT INTO usuario (";        
      $sql .= "   cod_usu, ";
      $sql .= "   id_usu, ";
      $sql .= "   pass_usu, ";
      $sql .= "   kcert_usu, ";
      $sql .= "   cod_rol, ";        
      $sql .= "   est_usu) VALUES(";
      $sql .= "   nextval('usuario_cod_usu_serial'),";          
      $sql .= " '" . str_replace("'","''",$sIdUsuNew) . "',";  
      $sql .= " '" . str_replace("'","''",$sClaveUsu) . "',";  
      $sql .= " '" . str_replace("'","''",$sClaveCert) . "',";  
      $sql .= " '" . str_replace("'","''",$sCodRolUsu) . "', "; 
      $sql .= " '" . str_replace("'","''",$sEstUsu) . "')";         
      nrExecuta($conn, $sql);
      $sql = "SELECT currval('usuario_cod_usu_serial') ";
      $result = rCursor($conn, $sql);            
      if(!$result->EOF) 
        $nCodUsu = trim($result->fields[0]);

  //    uploadCertificado($nCodUsu, $conn);         
  }
  
  function bExisUser($conn, $nCodUsu, $sIdUsuNew, $sAccion){
  
      $sql = "SELECT cod_usu FROM usuario WHERE id_usu = '" . str_replace("'","''",$sIdUsuNew) . "'";  
      if($sAccion == "M")
        $sql .= " AND cod_usu != " . $nCodUsu;

      $result = rCursor($conn, $sql);      
      $nNumRow = $result->RecordCount();        // obtiene el numero de filas 
      
      if($nNumRow > 0)  
        return false;
      else
        return true;
  }
      
  function bReturnMsg($sMsgJs){
      global $nCodUsu, $sIdUsu, $sIdUsuNew, $sPathCert, $sEstUsu, $sCodRolUsu, $sAccion, $sClaveUsu, $sClaveCert, $sMsgJs2;

      $strParamLink = "nCodUsu=" . urlencode($nCodUsu);
      $strParamLink .= "&sIdUsu=" . urlencode($sIdUsu);
      $strParamLink .= "&sIdUsuNew=" . urlencode($sIdUsuNew);          
      $strParamLink .= "&sPathCert=" . urlencode($sPathCert);                    
      $strParamLink .= "&sEstUsu=" . urlencode($sEstUsu);        
      $strParamLink .= "&sCodRolUsu=" . urlencode($sCodRolUsu);                              
      $strParamLink .= "&sAccion=" . urlencode($sAccion);                              
      $strParamLink .= "&sMsgJs=" . urlencode($sMsgJs);                              
      $strParamLink .= "&sMsgJs2=" . urlencode($sMsgJs2);                                    
            
      header("location:form_user.php?" . $strParamLink);
      exit;
  }
  
  function dModificar($conn){
    global $nCodUsu, $sIdUsu, $sIdUsuNew, $sPathCert, $sEstUsu, $sCodRolUsu, $sAccion, $sClaveUsu, $sClaveCert;
  
    if(bExisUser($conn, $nCodUsu, $sIdUsuNew, $sAccion) == false)
      bReturnMsg("_MSG_USER_ING_EXIS");
    
//    $sql .= "   cert_usu = '" . str_replace("'","''",$sRzSclEmp) . "',";  
        
    $sql = "UPDATE usuario SET ";
    $sql .= "   id_usu = '" . str_replace("'","''",$sIdUsuNew) . "',";  
    
    if($sClaveUsu <> "")
      $sql .= "   pass_usu = '" . str_replace("'","''",$sClaveUsu) . "',";  
    
    if($sClaveCert <> "")  
      $sql .= "   kcert_usu = '" . str_replace("'","''",$sClaveCert) . "', ";  
        
    $sql .= "   est_usu = '" . str_replace("'","''",$sEstUsu) . "', ";  
    $sql .= "   cod_rol = '" . str_replace("'","''",$sCodRolUsu) . "' ";          
    $sql .= " WHERE ";
    $sql .= "   cod_usu = " . $nCodUsu;        
    nrExecuta($conn, $sql);

  //  uploadCertificado($nCodUsu, $conn);        
  }

  function dBorraCertificado($conn, $nCodUsu){
     global  $_PATH_REAL_CERT_DIGITAL;

     $sql = "SELECT cert_usu FROM usuario WHERE cod_usu = " . $nCodUsu; 
     $result = rCursor($conn, $sql);            
     if(!$result->EOF){
        $sNomCert = trim($result->fields["cert_usu"]);          
        $file = $_PATH_REAL_CERT_DIGITAL . $sNomCert;

        if (@file_exists($file)) { 
          $delete = @chmod ($file, 0775); 
				  $delete = @unlink($file); 
		  	}    
        
     }  
  }
    
  function dBorraRegistro($conn, $nCodUsu){
 //   dBorraCertificado($conn, $nCodUsu);

     $sql = "DELETE FROM empr_usu WHERE cod_usu = " . $nCodUsu;        
     nrExecuta($conn, $sql);
     
     $sql = "DELETE FROM usuario WHERE cod_usu = " . $nCodUsu;        
     nrExecuta($conn, $sql);
  
  }
  
  function dEliminar($conn){
    $aEmpDel = $_POST["del"];  
    
    for($i=0; $i < sizeof($aEmpDel); $i++){      
      if(trim($aEmpDel[$i]) != "")
        dBorraRegistro($conn, $aEmpDel[$i]);      
    }
  }

  function uploadCertificado($nCodUsu2, $conn){
    global $_MAX_FILE_CERT, $_PATH_REAL_CERT_DIGITAL, $_ARRAY_EXT_CERT, $sAccion;
    
    if($_FILES['sPathCert']['tmp_name'] == "" && $sAccion == "M"){
    
    }
    else{
    
    $max_size = $_MAX_FILE_CERT;        // the max. size for uploading
    $my_upload = new file_upload;        
    $my_upload->upload_dir = $_PATH_REAL_CERT_DIGITAL; //   RUTA DEL UPLOAD
    
    $my_upload->extensions = $_ARRAY_EXT_CERT;        // EXTENSION ACEPTADAS
    $my_upload->language = "es"; // 
    $my_upload->max_length_filename = 100;            //  LARGO NOMBRE MAX DEL ARCHIVO
    $my_upload->rename_file = true;                   // RENOMBRAR ARCHIVO
    $my_upload->replace = true;                       // SOBREESCRIBE ARCHIVO
    $my_upload->the_temp_file = $_FILES['sPathCert']['tmp_name'];
    $my_upload->the_file = $_FILES['sPathCert']['name'];
    $my_upload->http_error = $_FILES['sPathCert']['error'];
    $my_upload->do_filename_check = "n";             // valida el nombre del archivo
        
    $new_name = $nCodUsu2 . "_certificado"; 

    if ($my_upload->upload($new_name)) { 
      $full_path = $my_upload->upload_dir.$my_upload->file_copy;

      $extFile = $my_upload->get_extension($my_upload->the_file);
      $sql = "UPDATE usuario SET ";
      $sql .= "   cert_usu = '" . str_replace("'","''",$new_name . $extFile) . "'";  
      $sql .= " WHERE ";
      $sql .= "   cod_usu = " . $nCodUsu2;        
      nrExecuta($conn, $sql);      
    }
    else{
      global $sMsgJs2;
      $arrayTmp = $my_upload->message;
      $sMsgJs2 = "";
      for($i=0; $i < sizeof($arrayTmp); $i++)
        $sMsgJs2 .= $arrayTmp[$i] . "\n";
      
      global $nCodUsu;  
      $nCodUsu = $nCodUsu2;
      $sAccion = "M";
      bReturnMsg("");      
//      header("location:form_usu.php?sMsgJs2=" . $sMsgJs2);
      exit; 
    }
    }
  }  
    
  switch ($sAccion) {
    case "I": 
        dIngresar($conn);
        break;
    
    case "M": 
        dModificar($conn);
        break;

    case "E": 
        dEliminar($conn);
        break;  
  }
  header("location:fin_user.php?sIdUsuNew=" . urlencode($sIdUsuNew));
  exit;    
?>
