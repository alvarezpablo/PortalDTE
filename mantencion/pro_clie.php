<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/db_lib.php"); 

   
  $nRutCli = trim($_POST["nRutCli"]);
  $nRutCliNew = trim($_POST["nRutCliNew"]);  
  $sRazClie = trim($_POST["sRazClie"]);
  $sDvClie = trim($_POST["sDvClie"]);       
  $sDvClieNew = trim($_POST["sDvClieNew"]);         
  $sEmiElecClie = trim($_POST["sEmiElecClie"]);
  $sRecElecClie = trim($_POST["sRecElecClie"]);               
  $sEnvEmail = trim($_POST["sEnvEmail"]);                           
  $sRazEmp = trim($_POST["sRazEmp"]);             
  
  $sFono = trim($_POST["sFono"]);            
  $sGuiaClie = trim($_POST["sGuiaClie"]);
  $sCiudClie = trim($_POST["sCiudClie"]);
  $sGiroClie = trim($_POST["sGiroClie"]);
  $sComClie = trim($_POST["sComClie"]);
  $sDirClie = trim($_POST["sDirClie"]);   

  $sNomTec = trim($_POST["sNomTec"]);  
  $sFonoTec = trim($_POST["sFonoTec"]);  
  $sEmailTec = trim($_POST["sEmailTec"]);  
  $sNomAdm = trim($_POST["sNomAdm"]);  
  $sFonoAdm = trim($_POST["sFonoAdm"]);  
  $sEmailAdm = trim($_POST["sEmailAdm"]);    
                         
  $sAccion = trim($_POST["sAccion"]); 
  $sCodEmp = trim($_POST["sCodEmp"]); 
  $sCodEmpNew = trim($_POST["sCodEmpNew"]);   
  $conn = conn();


        

	if(trim($_SESSION["_COD_ROL_SESS"]) != "1"){
		$sql = "  SELECT codi_empr FROM empr_usu WHERE cod_usu = '" . str_replace("'","''",$_SESSION["_COD_USU_SESS"]) . "' ";    
        $result = rCursor($conn, $sql);
                
        if(!$result->EOF){

		}
		else{
			header("location:list_clie.php");
			exit;
		}          
	}

  function dIngresar($conn){
    global $nRutCli, $nRutCliNew, $sRazClie, $sDvClie, $sDvClieNew, $sEmiElecClie, $sRecElecClie, $sEnvEmail, $sRazEmp, $sAccion, $sCodEmp, $sCodEmpNew, $sFono, $sGuiaClie, $sCiudClie, $sGiroClie, $sComClie, $sDirClie, $sNomTec, $sFonoTec, $sEmailTec, $sNomAdm, $sFonoAdm, $sEmailAdm;
  
    if(bExisClie($conn, $nRutCli, $nRutCliNew, $sAccion, $sCodEmp, $sCodEmpNew) == false)
      bReturnMsg("_MSG_CLIE_ING_EXIS");
                
      $sql = "INSERT INTO clientes (";        
      $sql .= "   cod_clie,rut_cli, ";
      $sql .= "   codi_empr, ";
      $sql .= "   dv_cli, ";
//      $sql .= "   emi_elec_cli, ";
      $sql .= "   acrec_email, ";
      $sql .= "   email_envio, ";                                        
      $sql .= "   raz_social,  ";
      $sql .= "   dir_clie,  ";
      $sql .= "   com_clie,  ";
      $sql .= "   giro_clie,  ";
      $sql .= "   ciud_cli,  ";
      $sql .= "   fono_clie, ";            
      $sql .= "   nom_cont_tec, ";
      $sql .= "   fono_cont_tec, ";
      $sql .= "   mail_cont_tec, ";
      $sql .= "   nom_cont_adm, ";
      $sql .= "   fono_cont_adm, ";
      $sql .= "   mail_cont_adm)     ";       
      $sql .= " VALUES(";   
	  $sql .= "		nextval('clientes_cod_clie_seq'), ";
      $sql .= " '" . str_replace("'","''",$nRutCliNew) . "',";
      $sql .= " '" . str_replace("'","''",$sCodEmpNew) . "',";
      $sql .= " '" . str_replace("'","''",$sDvClieNew) . "',";
//      $sql .= " '" . str_replace("'","''",$sEmiElecClie) . "',";
      $sql .= " '" . str_replace("'","''",$sRecElecClie) . "',";
      $sql .= " '" . str_replace("'","''",$sEnvEmail) . "',";
      $sql .= " '" . str_replace("'","''",$sRazClie) . "',";
      $sql .= " '" . str_replace("'","''",$sDirClie) . "',";
      $sql .= " '" . str_replace("'","''",$sComClie) . "',";
      $sql .= " '" . str_replace("'","''",$sGiroClie) . "',";
      $sql .= " '" . str_replace("'","''",$sCiudClie) . "',";
      $sql .= " '" . str_replace("'","''",$sFono) . "',";
      $sql .= " '" . str_replace("'","''",$sNomTec) . "',";
      $sql .= " '" . str_replace("'","''",$sFonoTec) . "',";
      $sql .= " '" . str_replace("'","''",$sEmailTec) . "',";
      $sql .= " '" . str_replace("'","''",$sNomAdm) . "',";                  
      $sql .= " '" . str_replace("'","''",$sFonoAdm) . "',";
      $sql .= " '" . str_replace("'","''",$sEmailAdm) . "')";      
      nrExecuta($conn, $sql);
  
  }
  
  function bExisClie($conn, $nRutCli, $nRutCliNew, $sAccion, $sCodEmp, $sCodEmpNew){

      $sql = "SELECT rut_cli FROM clientes WHERE rut_cli = '" . str_replace("'","''",$nRutCliNew) . "' AND codi_empr = '" . str_replace("'","''",$sCodEmpNew) . "' ";    
      if($sAccion == "M"){
        if($nRutCli != $nRutCliNew && $sCodEmp != $sCodEmpNew)
          $sql .= " AND rut_cli != '" . str_replace("'","''",$nRutCli) . "' AND codi_empr != '" . str_replace("'","''",$sCodEmp) . "'";  
        else
          if($nRutCli != $nRutCliNew && $sCodEmp == $sCodEmpNew)
            $sql .= " AND rut_cli != '" . str_replace("'","''",$nRutCli) . "'"; 
          else
            if($nRutCli == $nRutCliNew && $sCodEmp != $sCodEmpNew)
                $sql .= " AND codi_empr != '" . str_replace("'","''",$sCodEmp) . "'";  
            else
              if($nRutCli == $nRutCliNew && $sCodEmp == $sCodEmpNew)
                $sql .= " AND codi_empr != '" . str_replace("'","''",$sCodEmp) . "' AND rut_cli != '" . str_replace("'","''",$nRutCli) . "'";  

      }  
                
      $result = rCursor($conn, $sql);      
      $nNumRow = $result->RecordCount();        // obtiene el numero de filas 

      if($nNumRow > 0)
        return false;
      else
        return true;
  }
      
  function bReturnMsg($sMsgJs){
    global $nRutCli, $nRutCliNew, $sRazClie, $sDvClie, $sDvClieNew, $sEmiElecClie, $sRecElecClie, $sEnvEmail, $sRazEmp, $sAccion, $sCodEmp, $sCodEmpNew, $sFono, $sGuiaClie, $sCiudClie, $sGiroClie, $sComClie, $sDirClie,$sNomTec, $sFonoTec, $sEmailTec, $sNomAdm, $sFonoAdm, $sEmailAdm;

      $strParamLink = "nRutCli=" . urlencode($nRutCli);
      $strParamLink .= "&nRutCliNew=" . urlencode($nRutCliNew);
      $strParamLink .= "&sRazClie=" . urlencode($sRazClie);    
      $strParamLink .= "&sDvClie=" . urlencode($sDvClie);
      $strParamLink .= "&sDvClieNew=" . urlencode($sDvClieNew);      
      $strParamLink .= "&sEmiElecClie=" . urlencode($sEmiElecClie);
      $strParamLink .= "&sRecElecClie=" . urlencode($sRecElecClie);       
      $strParamLink .= "&sEnvEmail=" . urlencode($sEnvEmail);
      $strParamLink .= "&sRazEmp=" . urlencode($sRazEmp);             
      $strParamLink .= "&sAccion=" . urlencode($sAccion);  
      $strParamLink .= "&sCodEmp=" . urlencode($sCodEmpNew);             
      $strParamLink .= "&sCodEmpNew=" . urlencode($sCodEmpNew);         
      $strParamLink .= "&sFono=" . urlencode($sFono);
      $strParamLink .= "&sGuiaClie=" . urlencode($sGuiaClie);
      $strParamLink .= "&sCiudClie=" . urlencode($sCiudClie);
      $strParamLink .= "&sGiroClie=" . urlencode($sGiroClie);
      $strParamLink .= "&sComClie=" . urlencode($sComClie);
      $strParamLink .= "&sDirClie=" . urlencode($sDirClie);                                              
      $strParamLink .= "&sMsgJs=" . urlencode($sMsgJs);                              
      $strParamLink .= "&sNomTec=" . urlencode($sNomTec);
      $strParamLink .= "&sFonoTec=" . urlencode($sFonoTec);
      $strParamLink .= "&sEmailTec=" . urlencode($sEmailTec);
      $strParamLink .= "&sNomAdm=" . urlencode($sNomAdm);
      $strParamLink .= "&sFonoAdm=" . urlencode($sFonoAdm);
      $strParamLink .= "&sEmailAdm=" . urlencode($sEmailAdm);                              
                        
      header("location:form_clie.php?" . $strParamLink);
      exit;
  }
  
  function dModificar($conn){
    global $nRutCli, $nRutCliNew, $sRazClie, $sDvClie, $sDvClieNew, $sEmiElecClie, $sRecElecClie, $sEnvEmail, $sRazEmp, $sAccion, $sCodEmp, $sCodEmpNew, $sFono, $sGuiaClie, $sCiudClie, $sGiroClie, $sComClie, $sDirClie,$sNomTec, $sFonoTec, $sEmailTec, $sNomAdm, $sFonoAdm, $sEmailAdm;

    if(bExisClie($conn, $nRutCli, $nRutCliNew, $sAccion, $sCodEmp, $sCodEmpNew) == false)
      bReturnMsg("_MSG_CLIE_ING_EXIS");
    
    $sql = "UPDATE clientes SET ";
    $sql .= "   rut_cli = '" . str_replace("'","''",$nRutCliNew) . "',";  
    $sql .= "   codi_empr = '" . str_replace("'","''",$sCodEmpNew) . "',";  
    $sql .= "   dv_cli = '" . str_replace("'","''",$sDvClieNew) . "',";  
//    $sql .= "   emi_elec_cli = '" . str_replace("'","''",$sEmiElecClie) . "',";  
    $sql .= "   acrec_email = '" . str_replace("'","''",$sRecElecClie) . "',";  
    $sql .= "   email_envio = '" . str_replace("'","''",$sEnvEmail) . "',";  
    $sql .= "   raz_social = '" . str_replace("'","''",$sRazClie) . "',";  
    $sql .= "   dir_clie = '" . str_replace("'","''",$sDirClie) . "',";  
    $sql .= "   com_clie = '" . str_replace("'","''",$sComClie) . "',";  
    $sql .= "   giro_clie = '" . str_replace("'","''",$sGiroClie) . "',";  
    $sql .= "   ciud_cli = '" . str_replace("'","''",$sCiudClie) . "',";  
    $sql .= "   fono_clie = '" . str_replace("'","''",$sFono) . "',";            
    $sql .= "   nom_cont_tec = '" . str_replace("'","''",$sNomTec) . "',";
    $sql .= "   fono_cont_tec = '" . str_replace("'","''",$sFonoTec) . "',";
    $sql .= "   mail_cont_tec = '" . str_replace("'","''",$sEmailTec) . "',";
    $sql .= "   nom_cont_adm = '" . str_replace("'","''",$sNomAdm) . "',";  
    $sql .= "   fono_cont_adm = '" . str_replace("'","''",$sFonoAdm) . "',";
    $sql .= "   mail_cont_adm = '" . str_replace("'","''",$sEmailAdm) . "'";     
    $sql .= " WHERE ";
    $sql .= "   rut_cli = '" . str_replace("'","''",$nRutCli) . "' AND ";   
    $sql .= "   codi_empr = '" . str_replace("'","''",$sCodEmp) . "' ";       
    nrExecuta($conn, $sql);
    
  }
  
  function dBorraRegistro($conn, $nRutCli, $sCodEmp){
     $sql = "DELETE FROM clientes WHERE rut_cli = " . $nRutCli . " AND codi_empr = '" . str_replace("'","''",$sCodEmp) . "'";      
     nrExecuta($conn, $sql);
  
  }
  
  function dEliminar($conn){
    $aTDDel = $_POST["del"];  
    
    for($i=0; $i < sizeof($aTDDel); $i++){      
      if(trim($aTDDel[$i]) != ""){
         $arrTmp = explode(";",$aTDDel[$i]);
         dBorraRegistro($conn, $arrTmp[0], $arrTmp[1]);      
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
  header("location:fin_clie.php");
  exit;    
?>
