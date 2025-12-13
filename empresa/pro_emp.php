<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");        
  include("../include/db_lib.php"); 
  include ("../include/upload_class.php"); 

  $nCodEmp = trim($_POST["nCodEmp"]);
  $sRutEmp = trim($_POST["sRutEmp"]);
  $sDvEmp = trim($_POST["sDvEmp"]);  
  $sRzSclEmp = trim($_POST["sRzSclEmp"]);
  $sDirEmp = trim($_POST["sDirEmp"]);
  $dFecRes = trim($_POST["dFecRes"]);  
  $nResSii = trim($_POST["nResSii"]);  
  $sClaveCert = trim($_POST["sClaveCert"]);
  $nCodAct = trim($_POST["nCodAct"]);
  $sGiroEmp = trim($_POST["sGiroEmp"]);
  $sComEmp = trim($_POST["sComEmp"]);
  $sAccion = trim($_POST["sAccion"]);
  $sPropiedades = trim($_POST["sPropiedades"]);
  $nEmiteWeb = trim($_POST["nEmiteWeb"]);

  $conn = conn();

  function dIngresar($conn){
      global $sComEmp, $nCodEmp, $dFecRes, $nResSii, $sRutEmp, $sDvEmp, $sRzSclEmp, $nCodAct, $sGiroEmp, $sDirEmp, $sAccion, $sPropiedades, $nEmiteWeb ;
  
      if(bExisRut($conn, $nCodEmp, $sRutEmp, $sAccion) == false)
        bReturnMsg("_MSG_RUT_ING_EXIS");
                
      $sql = "INSERT INTO empresa (";        
      $sql .= "   codi_empr, ";
      $sql .= "   rut_empr, ";
      $sql .= "   dv_empr, ";
      $sql .= "   rs_empr, ";
      $sql .= "   cod_act, ";
      $sql .= "   giro_emp, ";
      $sql .= "   com_emp, ";
      $sql .= "   fec_resolucion, ";
      $sql .= "   num_resolucion, ";
      $sql .= "   propiedades, ";
      $sql .= "   emite_web, ";
	  $sql .= "   dir_empr) VALUES(";
      $sql .= "   nextval('empresa_codi_empr_serial'),";          
      $sql .= " '" . str_replace("'","''",$sRutEmp) . "',";  
      $sql .= " '" . str_replace("'","''",$sDvEmp) . "',";  
      $sql .= " '" . str_replace("'","''",$sRzSclEmp) . "',";  
      $sql .= " '" . str_replace("'","''",$nCodAct) . "',";  
      $sql .= " '" . str_replace("'","''",$sGiroEmp) . "',";  
      $sql .= " '" . str_replace("'","''",$sComEmp) . "',";  	  
      $sql .= " '" . str_replace("'","''",$dFecRes) . "',";  	  
      $sql .= " '" . str_replace("'","''",$nResSii) . "',";  	  
      $sql .= " '" . str_replace("'","''",$sPropiedades) . "',";  	  
      $sql .= " '" . str_replace("'","''",$nEmiteWeb) . "',";  	  
      $sql .= " '" . str_replace("'","''",$sDirEmp) . "') ";  
      nrExecuta($conn, $sql);

		$sql = "SELECT currval('empresa_codi_empr_serial') ";
		$result = rCursor($conn, $sql);
		if(!$result->EOF){
		  $nCodEmp = trim($result->fields[0]); 
		  addConfPrint($conn, $nCodEmp, $sRutEmp);
		  global $nCodEmp;
		}  

	  crearDirectorio($sRutEmp);
	  uploadCertificado();   
	  uploadLicencia();
  }
  
  function crearDirectorio($sRutEmp){
	shell_exec("LANG=es_CL.ISO-8859-1;cp -Rp /opt/opendte/Archivos/RUT_TEMPLATE/ /opt/opendte/Archivos/" . $sRutEmp . "/");	
  }

  function eliminarDirectorio($sRutEmp){
	  if(trim($sRutEmp) != "")
		shell_exec("LANG=es_CL.ISO-8859-1;rm -rf /opt/opendte/Archivos/" . $sRutEmp . "/");	
  }

  function bExisRut($conn, $nCodEmp, $sRutEmp, $sAccion){
  
      $sql = "SELECT codi_empr FROM empresa WHERE rut_empr = '" . str_replace("'","''",$sRutEmp) . "'";  
      if($sAccion == "M")
        $sql .= " AND codi_empr != " . $nCodEmp;
      
      $result = rCursor($conn, $sql);      
      $nNumRow = $result->RecordCount();        // obtiene el numero de filas 
      
      if($nNumRow > 0)
        return false;
      else
        return true;
  }
      
  function bReturnMsg($sMsgJs){
      global $nCodAct, $sGiroEmp, $dFecRes, $nResSii, $sComEmp, $nCodEmp, $sRutEmp, $sDvEmp, $sRzSclEmp, $sDirEmp, $sAccion;
      $strParamLink = "nCodEmp=" . urlencode($nCodEmp);
      $strParamLink .= "&sRutEmp=" . urlencode($sRutEmp);
      $strParamLink .= "&sDvEmp=" . urlencode($sDvEmp);          
      $strParamLink .= "&sRzSclEmp=" . urlencode($sRzSclEmp);                    
      $strParamLink .= "&sDirEmp=" . urlencode($sDirEmp);                              
      $strParamLink .= "&sAccion=" . urlencode($sAccion);                              
      $strParamLink .= "&sMsgJs=" . urlencode($sMsgJs);                              

	  $strParamLink .= "&nCodAct=" . urlencode($nCodAct);                              
	  $strParamLink .= "&sGiroEmp=" . urlencode($sGiroEmp);         
	  $strParamLink .= "&sComEmp=" . urlencode($sComEmp);         		  

      $strParamLink .= "&dFecRes=" . urlencode($dFecRes);
      $strParamLink .= "&nResSii=" . urlencode($nResSii);

      header("location:form_emp.php?" . $strParamLink);
      exit;
  }
  
  function dModificar($conn){
    global $sComEmp, $nCodEmp, $dFecRes, $nResSii, $sRutEmp, $sDvEmp, $sRzSclEmp, $nCodAct, $sGiroEmp, $sDirEmp, $sAccion,$sPropiedades, $nEmiteWeb;
  
    if(bExisRut($conn, $nCodEmp, $sRutEmp, $sAccion) == false)
      bReturnMsg("_MSG_RUT_ING_EXIS");
    
    $sql = "UPDATE empresa SET ";
    $sql .= "   com_emp = '" . str_replace("'","''",$sComEmp) . "',";  
    $sql .= "   rut_empr = '" . str_replace("'","''",$sRutEmp) . "',";  
    $sql .= "   dv_empr = '" . str_replace("'","''",$sDvEmp) . "',";  
    $sql .= "   rs_empr = '" . str_replace("'","''",$sRzSclEmp) . "',";  
	$sql .= "   cod_act = '" . str_replace("'","''",$nCodAct) . "',";
	$sql .= "   giro_emp = '" . str_replace("'","''",$sGiroEmp) . "',"; 	
	$sql .= "   fec_resolucion = '" . str_replace("'","''",$dFecRes) . "',"; 	
	$sql .= "   num_resolucion = '" . str_replace("'","''",$nResSii) . "',"; 	
	$sql .= "   emite_web = '" . str_replace("'","''",$nEmiteWeb) . "',"; 	
//	$sql .= "   propiedades = '" . str_replace("'","''",$sPropiedades) . "',"; 	
	$sql .= "   dir_empr = '" . str_replace("'","''",$sDirEmp) . "' ";  
    $sql .= " WHERE ";
    $sql .= "   codi_empr = " . $nCodEmp;        
    nrExecuta($conn, $sql);

    uploadCertificado();
	uploadLicencia();  

  }
  
  function addConfPrint($conn, $nCodEmpr, $sRutEmp){
	  // PDF VENTA
	  addParamPrint($conn, 33, $nCodEmpr, $sRutEmp,"");	// factura electronica
	  addParamPrint($conn, 34, $nCodEmpr, $sRutEmp,"");	// factura electronica
	  addParamPrint($conn, 39, $nCodEmpr, $sRutEmp,"");	// factura electronica
	  addParamPrint($conn, 41, $nCodEmpr, $sRutEmp,"");	// factura electronica
	  addParamPrint($conn, 46, $nCodEmpr, $sRutEmp,"");	// factura electronica
	  addParamPrint($conn, 52, $nCodEmpr, $sRutEmp,"");	// factura electronica
	  addParamPrint($conn, 56, $nCodEmpr, $sRutEmp,"");	// factura electronica
	  addParamPrint($conn, 61, $nCodEmpr, $sRutEmp,"");	// factura electronica
	  addParamPrint($conn, 110, $nCodEmpr, $sRutEmp,"");	// factura electronica
	  addParamPrint($conn, 111, $nCodEmpr, $sRutEmp,"");	// factura electronica
	  addParamPrint($conn, 112, $nCodEmpr, $sRutEmp,"");	// factura electronica

	  // PDF COMPRA
	  addParamPrint($conn, 33, $nCodEmpr, $sRutEmp,"compra_");	// factura electronica
	  addParamPrint($conn, 34, $nCodEmpr, $sRutEmp,"compra_");	// factura electronica
	  addParamPrint($conn, 39, $nCodEmpr, $sRutEmp,"compra_");	// factura electronica
	  addParamPrint($conn, 41, $nCodEmpr, $sRutEmp,"compra_");	// factura electronica
	  addParamPrint($conn, 46, $nCodEmpr, $sRutEmp,"compra_");	// factura electronica
	  addParamPrint($conn, 52, $nCodEmpr, $sRutEmp,"compra_");	// factura electronica
	  addParamPrint($conn, 56, $nCodEmpr, $sRutEmp,"compra_");	// factura electronica
	  addParamPrint($conn, 61, $nCodEmpr, $sRutEmp,"compra_");	// factura electronica
	  addParamPrint($conn, 110, $nCodEmpr, $sRutEmp,"compra_");	// factura electronica
	  addParamPrint($conn, 111, $nCodEmpr, $sRutEmp,"compra_");	// factura electronica
	  addParamPrint($conn, 112, $nCodEmpr, $sRutEmp,"compra_");	// factura electronica
  
  }

  function addParamPrint($conn, $tipoDTE, $nCodEmpr, $sRutEmp, $sCompra){

		if($sCompra == "compra_")
			$nTipMov = 2;
		else
			$nTipMov = 1;

		$sql = "INSERT INTO parametros_impresion ( 
				tipo_dt,
				tipo_movimiento,
				ruta_pdf_papel,
				ruta_propiedades,
				codi_empr ) 
			VALUES (
				'" . $tipoDTE . "',
				'" . $nTipMov . "',
				'/opt/opendte/Archivos/" . $sRutEmp . "/Plantillas/dte_" . $sCompra . $tipoDTE . ".pdf',
				'/opt/opendte/Archivos/" . $sRutEmp . "/Plantillas/dte_" . $sCompra . $tipoDTE . ".properties',
				'" . $nCodEmpr . "'	) ";
		nrExecuta($conn, $sql);
	
  }

  function dBorraRegistro($conn, $nCodEmp){

    $sql = "SELECT E.rut_empr, C.path_certificado, E.path_licencia FROM empresa E, certificado C WHERE 
			E.rut_empr = C.rut_empresa AND 
			E.codi_empr = '" . str_replace("'","''",$nCodEmp) . "'";
	$result = rCursor($conn, $sql);

	if(!$result->EOF) {
		$sRutEmp = trim($result->fields["rut_empr"]);
	    $sPathCert = trim($result->fields["path_certificado"]);
	    $sPathLic = trim($result->fields["path_licencia"]);
	}  	 

     $sql = "DELETE FROM empr_usu WHERE codi_empr = " . $nCodEmp;        
     nrExecuta($conn, $sql);
  
     $sql = "DELETE FROM certificado WHERE rut_empresa = '" . str_replace("'","''",$sRutEmp). "'";        
     nrExecuta($conn, $sql);

     $sql = "DELETE FROM parametros_impresion WHERE codi_empr = '" . str_replace("'","''",$nCodEmp). "'";        
     nrExecuta($conn, $sql);	 
     
     $sql = "DELETE FROM empresa WHERE codi_empr = " . $nCodEmp;        
     nrExecuta($conn, $sql);

     if (@file_exists($sPathLic)) { 
		$delete = @chmod ($sPathLic, 0775); 
		$delete = @unlink($sPathLic); 
	}
	  
	if (@file_exists($sPathCert)) { 
		$delete = @chmod ($sPathCert, 0775); 
		$delete = @unlink($sPathCert); 
	}     

	eliminarDirectorio($sRutEmp);		// elimina directorios
  }
  
  function dEliminar($conn){
    $aEmpDel = $_POST["del"];  
    
    for($i=0; $i < sizeof($aEmpDel); $i++){      
      if(trim($aEmpDel[$i]) != "")
        dBorraRegistro($conn, $aEmpDel[$i]);      
    }
  }

function uploadCertificado(){
    global $_MAX_FILE_CERT, $_PATH_REAL_CERT_DIGITAL, $_ARRAY_EXT_CERT, $sDvEmp, $sAccion, $sClaveCert, $sRutEmp, $conn, $_RUTA_BASE;

	
	if($_PATH_REAL_CERT_DIGITAL == "")
		$_PATH_REAL_CERT_DIGITAL = $_RUTA_BASE . "Archivos/" . $sRutEmp . "/Certificado/";

    if(trim($sClaveCert) != ""){
	    $sql = "UPDATE certificado SET ";
	    $sql .= "   clave_certificado = '". str_replace("'","''",$sClaveCert) ."'";  
	    $sql .= " WHERE ";
	    $sql .= "   rut_empresa = '" . str_replace("'","''",$sRutEmp) . "'";      
	    nrExecuta($conn, $sql);
    }
            
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
    }
  /*  else{
      $arrayTmp = $my_upload->message;
      for($i=0; $i < sizeof($arrayTmp); $i++)
        echo $arrayTmp[$i] . "<br>\n";
    }  
    */
//    }

  } 

function uploadLicencia(){
    global $_MAX_FILE_LIC, $_PATH_REAL_LIC_DIGITAL, $_ARRAY_EXT_LIC, $sRutEmp, $conn, $_RUTA_BASE;
  
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
//      $new_name = "licencia.lic";
  //    $extFile = $my_upload->get_extension($my_upload->the_file);

      $sql = "UPDATE empresa SET ";
      $sql .= "   path_licencia = '" . str_replace("'","''",$new_name) . "' ";
      $sql .= " WHERE ";
      $sql .= "   rut_empr = '" . str_replace("'","''",$sRutEmp) . "'";        
      nrExecuta($conn, $sql);
     }
  /*  else{
      $arrayTmp = $my_upload->message;
      for($i=0; $i < sizeof($arrayTmp); $i++)
        echo $arrayTmp[$i] . "<br>\n";
    }  
    */
//    }

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

  if($sAccion == "E")
	  header("location:fin_emp.php?nCodEmp=" . urlencode($nCodEmp) . "&sRutEmp=" . urlencode($sRutEmp) . "&sDvEmp=" . urlencode($sDvEmp));
  else
	  header("location:../mantencion/list_config.php?nCodEmp=" . urlencode($nCodEmp) . "&sRutEmp=" . urlencode($sRutEmp) . "&sDvEmp=" . urlencode($sDvEmp));
  exit;    
?>
