<?php
	/*****************************************************************************************************
	Configuracion sistema administracion facturación electrónica
	Autor: Mauricio Escobar <a href="mailto:diosdelviento@gmail.com">diosdelviento@gmail.com</a>	
	********************************************************************************************************/

$_NUMERO_RESOLUCION = "";
$_FECHA_RESOLUCION = "";
$_PATH_REAL_DTE = "";
$_PATH_REAL_DTE_LIBROS = "";
$_PATH_ENTRADA_LIBRO_GUIA = "";
$_PATH_REAL_DTE_BOLETA = "";
$_PATH_TERCEROS_PDF = "";
$_RUT_CONTRIBUYENTE_ENVIADOR = "";

 include( $_PATH_REAL_RAIZ . "include/adodb/adodb.inc.php");
seteaMultiEmpresa();
 

function conn(){
  global $_SERVER_DB, $_USER_DB, $_PASS_DB, $_DATABASE;						 	
 	
  $dbdriver = "postgres";
/*  $server = "192.168.76.129";
  $user = "factura";
  $password = "factura";
  $database = "factura"; */
 
  $conn = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
  $conn->debug = false;
//  $conn->charSet = 'ISO-8859-1';
  $conn->Connect($_SERVER_DB, $_USER_DB, $_PASS_DB, $_DATABASE);
  return $conn;
 }

function seteaMultiEmpresa(){
	global $_NUMERO_RESOLUCION, $_FECHA_RESOLUCION, $_PATH_REAL_DTE, $_PATH_REAL_DTE_LIBROS, $_PATH_ENTRADA_LIBRO_GUIA, $_PATH_REAL_DTE_BOLETA, $_PATH_REAL_CAF,$_PATH_TERCEROS_PDF, $_RUT_CONTRIBUYENTE_ENVIADOR,$_PATH_REAL_CERT_DIGITAL,$_PATH_REAL_LIC_DIGITAL;

if(trim($_SESSION["_COD_EMP_USU_SESS"]) != ""){
    $conn=conn();
    $sql = "SELECT propiedades,rut_enviador, DATE_PART('days', COALESCE(fec_ter_contrato,NOW()+ interval '3 days') - NOW()) AS ndays FROM empresa WHERE codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";
    $result = rCursor($conn, $sql);
    $nNumMon = 0;
    if (!$result->EOF) {
	$propiedades = trim($result->fields["propiedades"]);
	$nDays = trim($result->fields["ndays"]);

	$_RUT_CONTRIBUYENTE_ENVIADOR = trim($result->fields["rut_enviador"]);

        $_SESSION["NDAYS_CONTRATO"]=100;
        $_SESSION["MSG_CONTRATO"]=""; 

        if ($nDays <= 0 ){
                $msgContrato="Advertencia: El contrato asociado a su empresa ha expirado. Debe contactarse con su ejecutivo comercial.";
	        $_SESSION["NDAYS_CONTRATO"]=$nDays; 
                $_SESSION["MSG_CONTRATO"]=$msgContrato;
        }

	if(trim($_SESSION["_COD_ROL_SESS"]) == "1"){
	        $_SESSION["NDAYS_CONTRATO"]=100;
	        $_SESSION["MSG_CONTRATO"]="";
	}


	$aPropiedad = explode("\n",$propiedades);
	for($i=0;$i < sizeof($aPropiedad); $i++){
	    $tmp=explode("=",$aPropiedad[$i]);
	    if(trim(strtoupper($tmp[0])) == "NUMERO_RESOLUCION"){
		$tmp=explode("#",$tmp[1]);
		$_NUMERO_RESOLUCION = trim($tmp[0]);                              //numero resolución sii
		continue;
	    }
	    if(trim(strtoupper($tmp[0])) == "FECHA_RESOLUCION"){
		$tmp=explode("#",$tmp[1]);
		$_FECHA_RESOLUCION = trim($tmp[0]); 		                 //fecha resolución sii
		continue;
	    }
	    if(trim(strtoupper($tmp[0])) == "PATH_DIRECTORIO_ENTRADA"){
		$tmp=explode("#",$tmp[1]);
		$_PATH_REAL_DTE = trim($tmp[0]);             			//ruta entrada txt decarga (DTE)
		continue;
	    }
	    if(trim(strtoupper($tmp[0])) == "PATH_DIRECTORIO_ENTRADA_LIBRO"){
		$tmp=explode("#",$tmp[1]);
		$_PATH_REAL_DTE_LIBROS = trim($tmp[0]);                //ruta entrada txt de libros (DTE de libros venta)
		continue;
	    }
	    if(trim(strtoupper($tmp[0])) == "PATH_DIRECTORIO_ENTRADA_LIBRO_GUIA"){
		$tmp=explode("#",$tmp[1]);
		$_PATH_ENTRADA_LIBRO_GUIA = trim($tmp[0]);         //ruta entrada txt de libros de guia(DTE de libros guia)
		continue;
	    }
	    if(trim(strtoupper($tmp[0])) == "PATH_DIRECTORIO_ENTRADA_BOLETA"){
		$tmp=explode("#",$tmp[1]);
		$_PATH_REAL_DTE_BOLETA = trim($tmp[0]);                //ruta entrada txt de libros (DTE de libros venta)
		continue;
	    }
	    if(trim(strtoupper($tmp[0])) == "DIRECTORIO_ARCHIVOS_PDF_RECIBIDO"){
		$tmp=explode("#",$tmp[1]);
		$_PATH_TERCEROS_PDF = trim($tmp[0]);             //ruta de almacenamiento de pdf de terceros recibidos.
		continue;
	    }

	    if(trim(strtoupper($tmp[0])) == "PATH_REAL_CERT_DIGITAL"){
		$tmp=explode("#",$tmp[1]);
		$_PATH_REAL_CERT_DIGITAL = trim($tmp[0]);             //ruta de almacenamiento de pdf de terceros recibidos.
		continue;
	    }
	    if(trim(strtoupper($tmp[0])) == "PATH_REAL_LIC_DIGITAL"){
		$tmp=explode("#",$tmp[1]);
		$_PATH_REAL_LIC_DIGITAL = trim($tmp[0]);             //ruta de almacenamiento de pdf de terceros recibidos.
		continue;
	    }

	    if(trim(strtoupper($tmp[0])) == "PATH_REAL_CAF"){
		$tmp=explode("#",$tmp[1]);
		$_PATH_REAL_CAF = trim($tmp[0]);             //ruta de almacenamiento de pdf de terceros recibidos.
		continue;
	    }		
	}
    }
}


}

  /* retorna un cursor */
  function rCursor($conn, $sql, $bCtrErr = false){

    $result = $conn->Execute($sql);
    
    if ($result === false && $bCtrErr == true) 
      die("<h2>Error de Sql. Corregir por favor </h2> $sql");  
    
    return $result;

  }
     
  function nrExecuta($conn, $sql, $bCtrErr = false){
    
    $result = $conn->Execute($sql);
    
    if ($result === false && $bCtrErr == true) 
       die("<h2>Error de Sql. Corregir por favor </h2>");  
  }
  
  /* PAGINACION */
  function sPagina($conn, $sql, $link, $numPagMostrar = 0){
    global $_NUM_ROW_LIST, $_NUM_PAG_ACT, $_ORDER_BY_COLUM, $_NIVEL_BY_ORDER, $_COLUM_SEARCH, $_STRING_SEARCH, $_STRING_SEARCH0, $_STRING_SEARCH1,$_STRING_SEARCH2;
    
    $result = rCursor($conn, $sql);      
    $nNumRow = $result->RecordCount();        // obtiene el numero de filas              
    
    $nNumPagAct = $_NUM_PAG_ACT;
    $nNumPag = $_NUM_ROW_LIST;          
    
    if(trim($nNumPagAct) == "")
      $nNumPagAct = 0;        
    
    $nTotPag = ceil($nNumRow / $nNumPag);        // total de paginas
    
    $strPag = "";
    $aLink = explode("?",$link);

    if(sizeof($aLink) == 1)
      $link = $link . "?a=a";
    
    if($nTotPag > 1){
	if($numPagMostrar > 0) $nTotPag = $numPagMostrar;

      for($i=0; $i < $nTotPag; $i++) 
        if($i == $nNumPagAct)
          $strPag .= ($i + 1) . " ";
        else
          $strPag .= " <a href='" . $link . "&_NUM_PAG_ACT=" . $i . "&_ORDER_BY_COLUM=" . $_ORDER_BY_COLUM . "&_NIVEL_BY_ORDER=" . $_NIVEL_BY_ORDER . "&_COLUM_SEARCH=" . $_COLUM_SEARCH . "&_STRING_SEARCH=" . $_STRING_SEARCH . "&_STRING_SEARCH0=" . $_STRING_SEARCH0 . "&_STRING_SEARCH2=" . $_STRING_SEARCH2 . "'>" . ($i + 1) . "</a> ";        
    }  
    return $strPag;
  }        
  
?>
