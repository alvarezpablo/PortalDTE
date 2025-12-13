<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");        
	include("../include/db_lib.php"); 

   
  $nCodDoc = trim($_POST["nCodDoc"]);
  $nCodDocNew = trim($_POST["nCodDocNew"]);    
  $sDescDoc = trim($_POST["sDescDoc"]);
  $sAccion = trim($_POST["sAccion"]);

  $conn = conn();

  function dIngresar($conn){
       global $nCodDoc, $sDescDoc, $nCodDocNew, $sAccion;
  
      if(bExisTipDoc($conn, $nCodDoc, $nCodDocNew, $sAccion) == false)
         bReturnMsg("_MSG_TDOC_ING_EXIS");
                
      $sql = "INSERT INTO dte_tipo (";        
      $sql .= "   tipo_docu, ";
      $sql .= "   desc_tipo_docu) ";
      $sql .= "   VALUES(";      
      $sql .= " '" . str_replace("'","''",$nCodDocNew) . "',";  
      $sql .= " '" . str_replace("'","''",$sDescDoc) . "')";  
      nrExecuta($conn, $sql);
  }
  
  function bExisTipDoc($conn, $nCodDoc, $nCodDocNew, $sAccion){

      $sql = "SELECT tipo_docu FROM dte_tipo WHERE tipo_docu = '" . str_replace("'","''",$nCodDocNew) . "'";   
      if($sAccion == "M")
        $sql .= " AND tipo_docu != " . $nCodDoc;

      $result = rCursor($conn, $sql);      
      $nNumRow = $result->RecordCount();        // obtiene el numero de filas 

      if($nNumRow > 0)
        return false;
      else
        return true;
  }
      
  function bReturnMsg($sMsgJs){
      global $nCodDoc, $sDescDoc, $nCodDocNew, $sAccion;
       
      $strParamLink = "nCodDoc=" . urlencode($nCodDoc);
      $strParamLink .= "&nCodDocNew=" . urlencode($nCodDocNew);
      $strParamLink .= "&sDescDoc=" . urlencode($sDescDoc);          
      $strParamLink .= "&sAccion=" . urlencode($sAccion);                              
      $strParamLink .= "&sMsgJs=" . urlencode($sMsgJs);                              
            
      header("location:form_tc.php?" . $strParamLink);
      exit;
  }
  
  function dModificar($conn){
     global $nCodDoc, $sDescDoc, $nCodDocNew, $sAccion;

    if(bExisTipDoc($conn, $nCodDoc, $nCodDocNew, $sAccion) == false)
      bReturnMsg("_MSG_TDOC_ING_EXIS");
    
    $sql = "UPDATE dte_tipo SET ";
    $sql .= "   tipo_docu = '" . str_replace("'","''",$nCodDocNew) . "',";  
    $sql .= "   desc_tipo_docu = '" . str_replace("'","''",$sDescDoc) . "'";  
    $sql .= " WHERE ";
    $sql .= "   tipo_docu = " . $nCodDoc;        
    nrExecuta($conn, $sql);
    
  }
  
  function dBorraRegistro($conn, $nCodDoc){
     $sql = "DELETE FROM dte_tipo WHERE tipo_docu = " . $nCodDoc;        
     nrExecuta($conn, $sql);
  
  }
  
  function dEliminar($conn){
    $aTDDel = $_POST["del"];  
    
    for($i=0; $i < sizeof($aTDDel); $i++){      
      if(trim($aTDDel[$i]) != "")
        dBorraRegistro($conn, $aTDDel[$i]);      
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
  header("location:fin_tip_doc.php");
  exit;    
?>