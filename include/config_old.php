<?php 

	/*****************************************************************************************************
	Configuracion sistema administracion facturación electrónica
	Autor: Mauricio Escobar <a href="mailto:diosdelviento@gmail.com">diosdelviento@gmail.com</a>	
	********************************************************************************************************/

   /* VARIABLES DEL SISTEMA */
  $_RUT_CONTRIBUYENTE_ENVIADOR = "13981637-4";	// "11628942-3";		// rut de quien envia los libro a sii
  $_NUMERO_RESOLUCION = "0";							// NUKMERO DE RESOLUCION SII
  $_FECHA_RESOLUCION = "2008-10-01";					// FECHA RESOLUCION
  $_EN_CERTIFICACION = false;

  /****** RUTA DEL LOS BAT *******/
  $_RUTA_BATCH = "/opt/opendte/bin/";

  /**** RUTA DTE ***/
  $_PATH_REAL_DTE = "/opt/opendte/Archivos/Entrada/";    // PATH FISICO DE LOS DTE

  /**** RUTA LIBROS VENTA COMPRA ***/
  $_PATH_REAL_DTE_LIBROS = "/opt/opendte/Archivos/EntradaLibros/";    // PATH FISICO DE LOS DTE DE LIBROS

  /**** RUTA LIBRO GUIA ***/
  $_PATH_ENTRADA_LIBRO_GUIA = "/opt/opendte/Archivos/EntradaLibrosGuia/";

  /**** RUTA PDF RECIBIDO DE TERCEROS ******/
  $_PATH_TERCEROS_PDF = "/opt/opendte/Archivos/Pdf_Tercero/";

  $_PATH_REAL_RAIZ = "/opt/opendte/httpdocs/";    // PATH FISICO RAIZ
    
  /* FACTURACION */
  $_IVA_ = 19;    // IVA EN Nº ENTERO

  /* MONEDA POR DEFAULT */
  $_NCOD_MON_DEF_ = "CPL";    // MONEDA POR DEFAULT
  

  $_SKINS = "aqua";         // SKING A USAR
  $_LINK_BASE = "http://192.168.76.129/factura/";         // RUTA BASE DEL SITIO

   /** CERTIFICADO **/  
//  $_PATH_REAL_CERT_DIGITAL = "/opt/opendte/httpdocs/certificado/";    // PATH FISICO DE LOS CERTIFICADOS
  $_PATH_REAL_CERT_DIGITAL = "/opt/opendte/Certificado/";
  $_ARRAY_EXT_CERT = array(".pfx");  // EXTENSION ACEPTADAS PARA EL CERTIFCADO
  $_MAX_FILE_CERT = 1024*20;         // TAMAÑO MAXIMO DE BYTES DEL CERTIFICADO  
  /***/

   /** LICENCIA **/  
//  $_PATH_REAL_CERT_DIGITAL = "/opt/opendte/httpdocs/certificado/";    // PATH FISICO DE LOS CERTIFICADOS
  $_PATH_REAL_LIC_DIGITAL = "/opt/opendte/Licencia/";
  $_ARRAY_EXT_LIC = array(".lic");  // EXTENSION ACEPTADAS PARA EL CERTIFCADO
  $_MAX_FILE_LIC = 1024*20;         // TAMAÑO MAXIMO DE BYTES DEL CERTIFICADO  
  /***/  
  
  /** CAF **/
  $_PATH_REAL_CAF = "/opt/opendte/Archivos/CAF/";    // PATH FISICO DE LOS ARCHIVOS CAF
  $_ARRAY_EXT_CAF = array(".xml");  // EXTENSION ACEPTADAS PARA EL CERTIFCADO
  $_MAX_FILE_CAF = 1024*100;         // TAMAÑO MAXIMO DE BYTES DEL ARCHIVO CAF 
  
  /****/
  
  /** CLIENTE ELECTRONICO **/
  $_PATH_REAL_CLIE_ELEC = "/opt/opendte/httpdocs/clie_elec/";    // PATH FISICO DE LOS ARCHIVOS DE CLEITNE ELECTRONICO
  $_ARRAY_EXT_CLIE_ELEC = array(".csv");  // EXTENSION ACEPTADAS PARA EL ARCHIVO
  $_MAX_FILE_CLIE_ELEC = 1024*1024;         // TAMAÑO MAXIMO DE BYTES DEL ARCHIVO CAF 
  

  $_SERVER_DB = "127.0.0.1";						# IP DE POSTGRES
  $_USER_DB = "factura";							# USUARIO POSTGRES
  $_PASS_DB = "factura";						# PASSWORD DE USUARIO POSTGRES
  $_DATABASE = "factura";						# NOMBRE DATABASE   

  
  
  /***********************************************************/
  /**** FECHAS ***/
  $_FORMAT_FECHA_DEFAULT = "Ymd";   // AÑOMESDIA 20051031
  $_FORMAT_FECHA_ESP = "d-m-Y";   // formato en español  
  
  $_PATH_VITUAL_CERT_DIGITAL = "certificado\\";      // PATH FISICO DE LOS CERTIFICADOS  
 
  $_NUM_ROW_LIST = 25;                               // NUMERO DE registros por listado de PAGINAS
  $_NUM_PAG_ACT = $_GET["_NUM_PAG_ACT"];  
  $_ORDER_BY_COLUM = $_GET["_ORDER_BY_COLUM"];      // ORDEN DE COLUMNAS
  $_NIVEL_BY_ORDER = $_GET["_NIVEL_BY_ORDER"];      // NIVEL DE PORDEN ASC O DESC
  $_COLUM_SEARCH = trim($_GET["_COLUM_SEARCH"]);         // columna seachr
  $_STRING_SEARCH = trim($_GET["_STRING_SEARCH"]);         // STRING SEARCH  
  $_STRING_SEARCH2 = trim($_GET["_STRING_SEARCH2"]);         // STRING SEARCH  
  $_STRING_SEARCH0 = trim($_GET["_STRING_SEARCH0"]);         // STRING SEARCH  
  $_ORDER_CAMBIA = trim($_GET["_ORDER_CAMBIA"]);    // cambia el orden de datos
   
  if(trim($_NIVEL_BY_ORDER) == ""){
    $_NIVEL_BY_ORDER = "DESC";
    $_IMG_BY_ORDER = $_LINK_BASE . "skins/" . $_SKINS . "/icons/arrow_down.gif";        
  }
  else{
    if($_ORDER_CAMBIA == "Y"){
      if(trim($_NIVEL_BY_ORDER) == "ASC"){
        $_NIVEL_BY_ORDER = "DESC";
        $_IMG_BY_ORDER = $_LINK_BASE . "skins/" . $_SKINS . "/icons/arrow_up.gif";      
      }
      else{
        $_NIVEL_BY_ORDER = "ASC";
        $_IMG_BY_ORDER = $_LINK_BASE . "skins/" . $_SKINS . "/icons/arrow_down.gif";
      }
    }
    else{
       if(trim($_NIVEL_BY_ORDER) == "ASC")
          $_IMG_BY_ORDER = $_LINK_BASE . "skins/" . $_SKINS . "/icons/arrow_down.gif";      
       else
          $_IMG_BY_ORDER = $_LINK_BASE . "skins/" . $_SKINS . "/icons/arrow_up.gif";       
    }
  }

  
  if(trim($_NUM_PAG_ACT) == "")
    $_NUM_PAG_ACT = 0;
  
  //define("_NUM_PAG_LIST",1); // Nº de paginas por listado
  
/*************** MOTIVO NOTA DE CREDITO Y DEBITO **************/
 $_AMOTIVO_NC_ND[1] = "Anula Documento de Referencia";
 $_AMOTIVO_NC_ND[2] = "Corrige Texto del Documento de Referencia";
 $_AMOTIVO_NC_ND[3] = "Corrige Montos";


 /******************** EMPRESA **********************************************/

 $aBotonEmpHerramienta["ID"][0] = "bid-white-list-add"; 
 $aBotonEmpHerramienta["ONMOUSEOVER"][0] =  "";
 $aBotonEmpHerramienta["ONMOUSEOUT"][0] =  "";
 $aBotonEmpHerramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "empresa/form_emp.php?sAccion=I';";
 $aBotonEmpHerramienta["SETIQUETA"][0] =  "Nueva Empresa";

 $aBotonEmpHerramienta["ID"][1] = "bid-new-client"; 
 $aBotonEmpHerramienta["ONMOUSEOVER"][1] =  "";
 $aBotonEmpHerramienta["ONMOUSEOUT"][1] =  "";
 $aBotonEmpHerramienta["ONCLICK"][1] =  "location.href='" . $_LINK_BASE . "usuario/form_user.php?sAccion=I';";
 $aBotonEmpHerramienta["SETIQUETA"][1] =  "Nuevo Usuario";

 $aBotonEmpHerramienta["ID"][2] = "bid-client-templates"; 
 $aBotonEmpHerramienta["ONMOUSEOVER"][2] =  "";
 $aBotonEmpHerramienta["ONMOUSEOUT"][2] =  "";
 $aBotonEmpHerramienta["ONCLICK"][2] =  "location.href='" . $_LINK_BASE . "user_emp/add_user_emp.php?op=0';";
 $aBotonEmpHerramienta["SETIQUETA"][2] =  "Usuarios Empresa";

 $aBotonEmpHerramienta["ID"][3] = "bid-domain-user"; 
 $aBotonEmpHerramienta["ONMOUSEOVER"][3] =  "";
 $aBotonEmpHerramienta["ONMOUSEOUT"][3] =  "";
 $aBotonEmpHerramienta["ONCLICK"][3] =  "location.href='" . $_LINK_BASE . "user_emp/add_emp_user.php?op=0';";
 $aBotonEmpHerramienta["SETIQUETA"][3] =  "Empresa Usuarios";


/******************** USUARIO **********************************************/

 $aBotonUserHerramienta["ID"][0] = "bid-new-client"; 
 $aBotonUserHerramienta["ONMOUSEOVER"][0] =  "";
 $aBotonUserHerramienta["ONMOUSEOUT"][0] =  "";
 $aBotonUserHerramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "usuario/form_user.php?sAccion=I';";
 $aBotonUserHerramienta["SETIQUETA"][0] =  "Nuevo Usuario";

 $aBotonUserHerramienta["ID"][1] = "bid-client-templates"; 
 $aBotonUserHerramienta["ONMOUSEOVER"][1] =  "";
 $aBotonUserHerramienta["ONMOUSEOUT"][1] =  "";
 $aBotonUserHerramienta["ONCLICK"][1] =  "location.href='" . $_LINK_BASE . "user_emp/add_user_emp.php?op=1';";
 $aBotonUserHerramienta["SETIQUETA"][1] =  "Usuarios Empresa";

 $aBotonUserHerramienta["ID"][2] = "bid-domain-user"; 
 $aBotonUserHerramienta["ONMOUSEOVER"][2] =  "";
 $aBotonUserHerramienta["ONMOUSEOUT"][2] =  "";
 $aBotonUserHerramienta["ONCLICK"][2] =  "location.href='" . $_LINK_BASE . "user_emp/add_emp_user.php?op=1';";
 $aBotonUserHerramienta["SETIQUETA"][2] =  "Empresa Usuarios";

/******************** USUARIO EMPRESA **********************************************/
/*
 $aBotonUserEmpHerramienta["ID"][0] = "bid-new-client"; 
 $aBotonUserEmpHerramienta["ONMOUSEOVER"][0] =  "";
 $aBotonUserEmpHerramienta["ONMOUSEOUT"][0] =  "";
 $aBotonUserEmpHerramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "user_emp/add_user_emp.php';";
 $aBotonUserEmpHerramienta["SETIQUETA"][0] =  "Agregar Usuario a Empresa";

 $aBotonUserEmpHerramienta["ID"][1] = "bid-white-list-add"; 
 $aBotonUserEmpHerramienta["ONMOUSEOVER"][1] =  "";
 $aBotonUserEmpHerramienta["ONMOUSEOUT"][1] =  "";
 $aBotonUserEmpHerramienta["ONCLICK"][1] =  "location.href='" . $_LINK_BASE . "user_emp/add_emp_user.php';";
 $aBotonUserEmpHerramienta["SETIQUETA"][1] =  "Agregar Empresa a Usuario"; 
*/

/******************** CLIENTE **********************************************/

 $aBotonCliempHerramienta["ID"][0] = "bid-new-client"; 
 $aBotonCliempHerramienta["ONMOUSEOVER"][0] =  "";
 $aBotonCliempHerramienta["ONMOUSEOUT"][0] =  "";
 $aBotonCliempHerramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "mantencion/form_clie.php?sAccion=I';";
 $aBotonCliempHerramienta["SETIQUETA"][0] =  "Nuevo Cliente";

/******************** TIPO DOCUMENTO ****************************************/

 $aBotonTipDocHerramienta["ID"][0] = "bid-add-new-ticket"; 
 $aBotonTipDocHerramienta["ONMOUSEOVER"][0] =  "";
 $aBotonTipDocHerramienta["ONMOUSEOUT"][0] =  "";
 $aBotonTipDocHerramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "mantencion/form_tc.php?sAccion=I';";
 $aBotonTipDocHerramienta["SETIQUETA"][0] =  "Nuevo Tipo de Documento";


/******************** CATEGORIAS ****************************************/

 $aBotonCatHerramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonCatHerramienta["ONMOUSEOVER"][0] =  "";
 $aBotonCatHerramienta["ONMOUSEOUT"][0] =  "";
 $aBotonCatHerramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_cat.php?sAccion=I';";
 $aBotonCatHerramienta["SETIQUETA"][0] =  "Nueva Categor&iacute;a";

/******************** MONEDAS ****************************************/

 $aBotonMonHerramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonMonHerramienta["ONMOUSEOVER"][0] =  "";
 $aBotonMonHerramienta["ONMOUSEOUT"][0] =  "";
 $aBotonMonHerramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_mon.php?sAccion=I';";
 $aBotonMonHerramienta["SETIQUETA"][0] =  "Nueva Moneda";

/******************** productos ****************************************/

 $aBotonProdHerramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonProdHerramienta["ONMOUSEOVER"][0] =  "";
 $aBotonProdHerramienta["ONMOUSEOUT"][0] =  "";
 $aBotonProdHerramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_prod.php?sAccion=I';";
 $aBotonProdHerramienta["SETIQUETA"][0] =  "Nuevo Producto";

/******************** SERVICIOS ****************************************/

 $aBotonServHerramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonServHerramienta["ONMOUSEOVER"][0] =  "";
 $aBotonServHerramienta["ONMOUSEOUT"][0] =  "";
 $aBotonServHerramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_serv.php?sAccion=I';";
 $aBotonServHerramienta["SETIQUETA"][0] =  "Nuevo Servicio";

/******************** PLANES ****************************************/

 $aBotonPlanHerramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonPlanHerramienta["ONMOUSEOVER"][0] =  "";
 $aBotonPlanHerramienta["ONMOUSEOUT"][0] =  "";
 $aBotonPlanHerramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_plan.php?sAccion=I';";
 $aBotonPlanHerramienta["SETIQUETA"][0] =  "Nuevo Plan";

/******************** CONTRATOS ****************************************/

 $aBotonContHerramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonContHerramienta["ONMOUSEOVER"][0] =  "";
 $aBotonContHerramienta["ONMOUSEOUT"][0] =  "";
 $aBotonContHerramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_cont.php?sAccion=I';";
 $aBotonContHerramienta["SETIQUETA"][0] =  "Nuevo Contrato";

/******************** FACTURACION ****************************************/

 $aBotonFactHerramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonFactHerramienta["ONMOUSEOVER"][0] =  "";
 $aBotonFactHerramienta["ONMOUSEOUT"][0] =  "";
 $aBotonFactHerramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_fact.php?sAccion=I';";
 $aBotonFactHerramienta["SETIQUETA"][0] =  "Nueva Factura";

/******************** NOTA CREDITO ****************************************/

 $aBotonDoc61Herramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonDoc61Herramienta["ONMOUSEOVER"][0] =  "";
 $aBotonDoc61Herramienta["ONMOUSEOUT"][0] =  "";
 $aBotonDoc61Herramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_nc_nd.php?sAccion=I&nTipDoc=61';";
 $aBotonDoc61Herramienta["SETIQUETA"][0] =  "Nueva Nota de Crédito";

/******************** NOTA DEBITO ****************************************/

 $aBotonDoc56Herramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonDoc56Herramienta["ONMOUSEOVER"][0] =  "";
 $aBotonDoc56Herramienta["ONMOUSEOUT"][0] =  "";
 $aBotonDoc56Herramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_nc_nd.php?sAccion=I&nTipDoc=56';";
 $aBotonDoc56Herramienta["SETIQUETA"][0] =  "Nueva Nota de Débito";

/******************** GUIA DE DESPACHO ****************************************/

 $aBotonDoc52Herramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonDoc52Herramienta["ONMOUSEOVER"][0] =  "";
 $aBotonDoc52Herramienta["ONMOUSEOUT"][0] =  "";
 $aBotonDoc52Herramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_guia.php?sAccion=I&nTipDoc=52';";
 $aBotonDoc52Herramienta["SETIQUETA"][0] =  "Nueva Gu&iacute;a de Despacho";


/******************** FACTURA ****************************************/

 $aBotonDoc30Herramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonDoc30Herramienta["ONMOUSEOVER"][0] =  "";
 $aBotonDoc30Herramienta["ONMOUSEOUT"][0] =  "";
 $aBotonDoc30Herramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_noelec.php?sAccion=I&nTipDoc=30';";
 $aBotonDoc30Herramienta["SETIQUETA"][0] =  "Nueva Factura";

/******************** FACTURA NO AFECTA ****************************************/

 $aBotonDoc32Herramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonDoc32Herramienta["ONMOUSEOVER"][0] =  "";
 $aBotonDoc32Herramienta["ONMOUSEOUT"][0] =  "";
 $aBotonDoc32Herramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_noelec.php?sAccion=I&nTipDoc=32';";
 $aBotonDoc32Herramienta["SETIQUETA"][0] =  "Nueva Factura no Afecta";

/******************** FACTURA NO AFECTA ****************************************/

 $aBotonDoc45Herramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonDoc45Herramienta["ONMOUSEOVER"][0] =  "";
 $aBotonDoc45Herramienta["ONMOUSEOUT"][0] =  "";
 $aBotonDoc45Herramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_noelec.php?sAccion=I&nTipDoc=45';";
 $aBotonDoc45Herramienta["SETIQUETA"][0] =  "Nueva Factura de Compra";

/******************** FACTURA NO AFECTA ****************************************/

 $aBotonDoc101Herramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonDoc101Herramienta["ONMOUSEOVER"][0] =  "";
 $aBotonDoc101Herramienta["ONMOUSEOUT"][0] =  "";
 $aBotonDoc101Herramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_noelec.php?sAccion=I&nTipDoc=101';";
 $aBotonDoc101Herramienta["SETIQUETA"][0] =  "Nueva Factura de Exportaci&oacute;n";

/******************** NOTA DE CREDUTO ****************************************/

 $aBotonDoc60Herramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonDoc60Herramienta["ONMOUSEOVER"][0] =  "";
 $aBotonDoc60Herramienta["ONMOUSEOUT"][0] =  "";
 $aBotonDoc60Herramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_noelec.php?sAccion=I&nTipDoc=60';";
 $aBotonDoc60Herramienta["SETIQUETA"][0] =  "Nueva Nota de Crédito";

/******************** NOTA DE DEBITO ****************************************/

 $aBotonDoc55Herramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonDoc55Herramienta["ONMOUSEOVER"][0] =  "";
 $aBotonDoc55Herramienta["ONMOUSEOUT"][0] =  "";
 $aBotonDoc55Herramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "factura/form_noelec.php?sAccion=I&nTipDoc=55';";
 $aBotonDoc55Herramienta["SETIQUETA"][0] =  "Nueva Nota de Débito";

/******************** GENERAR LIBROS ****************************************/

 $aBotonGeneraLibroHerramienta["ID"][0] = "bid-site-app-pkg-new"; 
 $aBotonGeneraLibroHerramienta["ONMOUSEOVER"][0] =  "";
 $aBotonGeneraLibroHerramienta["ONMOUSEOUT"][0] =  "";
 $aBotonGeneraLibroHerramienta["ONCLICK"][0] =  "location.href='" . $_LINK_BASE . "libros/list_genera.php?sTipo=VENTA';";
 $aBotonGeneraLibroHerramienta["SETIQUETA"][0] =  "Libro de Venta";

 $aBotonGeneraLibroHerramienta["ID"][1] = "bid-site-app-pkg-new"; 
 $aBotonGeneraLibroHerramienta["ONMOUSEOVER"][1] =  "";
 $aBotonGeneraLibroHerramienta["ONMOUSEOUT"][1] =  "";
 $aBotonGeneraLibroHerramienta["ONCLICK"][1] =  "location.href='" . $_LINK_BASE . "libros/list_genera.php?sTipo=COMPRA';";
 $aBotonGeneraLibroHerramienta["SETIQUETA"][1] =  "Libro de Compra";

 $aBotonGeneraLibroHerramienta["ID"][2] = "bid-site-app-pkg-new"; 
 $aBotonGeneraLibroHerramienta["ONMOUSEOVER"][2] =  "";
 $aBotonGeneraLibroHerramienta["ONMOUSEOUT"][2] =  "";
 $aBotonGeneraLibroHerramienta["ONCLICK"][2] =  "location.href='" . $_LINK_BASE . "libros/list_genera.php?sTipo=GUIA';";
 $aBotonGeneraLibroHerramienta["SETIQUETA"][2] =  "Libro de Guia";

?>
