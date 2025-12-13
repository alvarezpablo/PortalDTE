<?php

 function aMenu($_SKINS){
 
  $i=0;
  $j=0;

  $_ARRAY_MENU["RAIZ"]["ID"][$i] = "Recibir_Documentos";
  $_ARRAY_MENU["RAIZ"]["TITULO"][$i] = "Recepcion DTE";
  
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "dte_recep";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "factura/list_dte_recep_v2.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "DTE Recibidos";      
  $j++;   // inclementa j  

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "dte_recep_sii";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "factura/list_dte_recep_v3.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "DTE Recibidos V3(Beta)";
  $j++;   // inclementa j

/*
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "dte_recep_sii";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "informes/grid_cuadratura_sii/";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Cuadratura DTE SII";      
  $j++;   // inclementa j  

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "dte_sii";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "informes/grid_dte_recep_sii/";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "DTE Recibidos por SII";      
  $j++;   // inclementa j  
*/

/*
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "aprodte.";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "factura/list_recielec.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Aprobar DTE";      
  $j++;   // inclementa j  

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "acepdte.";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "factura/list_aceptado.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "DTE Aceptados";
  $j++;   // inclementa j 

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "rechadte.";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "factura/list_rechazado.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "DTE Rechazados";
  $j++;   // inclementa j 
*/
  if(trim($_SESSION["_COD_ROL_SESS"]) == "1" ||  trim($_SESSION["_COD_ROL_SESS"]) == "3" ){ 
	  $i++;
	  $j=0;

	  $_ARRAY_MENU["RAIZ"]["ID"][$i] = "Seguridad";
	  $_ARRAY_MENU["RAIZ"]["TITULO"][$i] = "Seguridad";
		
	  if(trim($_SESSION["_COD_ROL_SESS"]) == "1"){  
			
		  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "Empresas";
		  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
		  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
		  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "empresa/listempre.php";
		  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
		  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Empresa";
		  $j++;   // inclementa j
		  
		  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "Usuario";
		  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
		  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_user-new_bg.gif";
		  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "usuario/list_user.php";
		  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
		  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Usuarios";
		  $j++;    

                  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "Reenvio";
                  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
                  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_user-new_bg.gif";
                  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "reenvio/reenvio.php";
                  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
                  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Reenv&iacute;o Masivo";
                  $j++;  


		/*  $_ARRAY_MENU["RAIZ"]["NODO"][0]["ID"][2] = "Usuario_Empresa";
		  $_ARRAY_MENU["RAIZ"]["NODO"][0]["CLASS"][2] = "node";
		  $_ARRAY_MENU["RAIZ"]["NODO"][0]["ICONO"][2] = "skins/" . $_SKINS . "/images/btn_web-users_bg.gif";
		  $_ARRAY_MENU["RAIZ"]["NODO"][0]["LINK"][2] = "user_emp/list_user_emp.php";
		  $_ARRAY_MENU["RAIZ"]["NODO"][0]["TARGET"][2] = "workFrame";
		  $_ARRAY_MENU["RAIZ"]["NODO"][0]["TEXT_LINK"][2] = "Usuario Empresa"; */

	  }
        

	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "CertificadoEmpresa";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_user-new_bg.gif";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "empresa/certificado.php";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Certificado Empresa";
	  $j++;

	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "LicenciaEmpresa";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_user-new_bg.gif";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "empresa/licencia.php";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Licencia Empresa";
	$j++;

	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "apikey";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_user-new_bg.gif";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "empresa/uuid.php";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
	  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Api Key";


  }

  $i++;
  $j=0;   // j = 0

if(trim($_SESSION["_COD_EMP_USU_SESS"]) == "85"){
      $j=0; 

      $_ARRAY_MENU["RAIZ"]["ID"][$i] = "Consorcio1";
      $_ARRAY_MENU["RAIZ"]["TITULO"][$i] = "Consorcio";

      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "Consorcio";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_client-templates_bg.gif";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "consorcio/form_excel.php";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Carga Boletas";
      $j=0;   // inclementa j  
      $i++;
}



    $_ARRAY_MENU["RAIZ"]["ID"][$i] = "Caf";
    $_ARRAY_MENU["RAIZ"]["TITULO"][$i] = "Caf";
  
    if(trim($_SESSION["_COD_ROL_SESS"]) == "1" ||  trim($_SESSION["_COD_ROL_SESS"]) == "3" ){ 
		$_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "carga";
		$_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
		$_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_add-services_bg.gif";
		$_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "caf/form_caf.php";
		$_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
		$_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Carga Caf";
	    $j++;   // inclementa j		
	}

    $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "consultacaf";
    $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
    $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_show_bg.gif";
    $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "caf/disp_caf.php";
    $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
    $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Consulta Caf";
  
    $i++;   // inclementa i
  $j=0;   // j = 0


// CATEGORIA
if($_SESSION["_GPUERTO_"] == "1" || trim($_SESSION["_COD_ROL_SESS"]) == "1"){
  $_ARRAY_MENU["RAIZ"]["ID"][$i] = "grupo_puerto";
  $_ARRAY_MENU["RAIZ"]["TITULO"][$i] = "Carga DTE";

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "cargadte";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "laudus/gpuerto.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Carga Excel DTE";
  $j++;   // inclementa j

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "reenviardte";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "laudus/gpuerto_resend.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Reenviar DTE";
  $j++;   // inclementa j


  $i++;
  $j=0;
}  

if($_SESSION["RUT_EMP"] == "77648628" || $_SESSION["RUT_EMP"] == "77648624" || $_SESSION["RUT_EMP"] == "77239803" || trim($_SESSION["_COD_ROL_SESS"]) == "1"){
  $_ARRAY_MENU["RAIZ"]["ID"][$i] = "VGMEmite";
  $_ARRAY_MENU["RAIZ"]["TITULO"][$i] = "VGM Emite DTE";

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "cargadte";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "vgm/vgm.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Carga Excel DTE";
  $j++;   // inclementa j

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "bajarexcel";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "vgm/vgm_excel.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Excel Softland";
  $j++;   // inclementa j
 
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "reenviarmail";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "vgm/vgm_reenviar.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Reenviar Email";
  $j++;   // inclementa j  
 
  
  $i++;
  $j=0;
}


// CATEGORIA
if(trim($_SESSION["_EMITE_WEB_"]) == "1" || trim($_SESSION["_COD_ROL_SESS"]) == "1"){
  $_ARRAY_MENU["RAIZ"]["ID"][$i] = "EMITIR_WEB";
  $_ARRAY_MENU["RAIZ"]["TITULO"][$i] = "Emitir DTE";

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "dte33";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "emitir/emitir.php?t=33";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Factura Electrónica";
  $j++;   // inclementa j

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "dte34";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "emitir/emitir.php?t=34";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Factura No Afecta o Exenta Electrónica";
  $j++;   // inclementa j

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "dte39";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "emitir/emitir.php?t=39";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Boleta Electrónica";
  $j++;   // inclementa j

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "dte41";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "emitir/emitir.php?t=41";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Boleta Exenta Electrónica";
  $j++;   // inclementa j

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "dte56";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "emitir/emitir.php?t=56";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Nota de Débito Electrónica";
  $j++;   // inclementa j

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "dte61";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "emitir/emitir.php?t=61";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Nota de Crédito Electrónica";
  $j++;   // inclementa j

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "dte5-2";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_manage_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "emitir/emitir.php?t=52";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Guía de Despacho Electrónica";
  $j++;   // inclementa j

  $i++;
  $j=0;
}  

  
  $_ARRAY_MENU["RAIZ"]["ID"][$i] = "DTE";
  $_ARRAY_MENU["RAIZ"]["TITULO"][$i] = "DTE Emitidos";
 

  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "cargado2";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_mpc_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "dte/list_dte_v2.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "DTE Listado ";
  $j++;   // inclementa j 

if(trim($_SESSION["_COD_ROL_SESS"]) == "1" ||  trim($_SESSION["_COD_ROL_SESS"]) == "3" ){
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "descarga-xml-historicos";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_tts-tickets-all_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "exportXML/consulta_xml_exportado.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Descarga XML (new)";
  $j++;   // inclementa j
}



$nCA = trim($_SESSION["_COD_EMP_USU_SESS"]);

if($nCA == "72" || $nCA == "73" || $nCA == "70" || $nCA == "74" || $nCA == "151" || $nCA == "318" || $nCA == "71"){
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "cargado3";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_mpc_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "dte/noenviado.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "DTE No Env&iacute;ado a SII ";
  $j++;   // inclementa j
}



 /*
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "cargado2";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_mpc_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "dte/list_dtetmp.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "DTE Listado ";
  $j++;   // inclementa j   
*/
//  if(trim($_SESSION["_COD_ROL_SESS"]) == "1"){
/*
 $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "dte_emitidos";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_mpc_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "informes/grid_public_v_dte/grid_public_v_dte.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "DTE Emitidos";
  $j++;   // inclementa j

 $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "resp_no_recep";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_mpc_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "informes/grid_public_resp_no_recep/grid_public_resp_no_recep.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Respuestas con Error";
  $j++;   // inclementa j
*/

/*
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "dte_cargados";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_mpc_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "informes/rep_est_dte/rep_est_dte.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Estado DTE Cargados";
  $j++;   // inclementa j
*/

//}


  /*
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "cargado22";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_mpc_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "dte/list_dtepro.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "DTE Listado Plus";
  $j++;   // inclementa j  
  */
  $i++;   // inclementa i
  $j=0;   // j = 0

  $_ARRAY_MENU["RAIZ"]["ID"][$i] = "Libros";
  $_ARRAY_MENU["RAIZ"]["TITULO"][$i] = "Libros";
/*
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "doc_recep";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_mail-resp-files_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "consulta/listdte.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Doc. Recepcionados";

  $j++;   // inclementa j    */
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "librosCompra";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_tts-tickets-all_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "libros/list_libro.php?sTipo=COMPRA";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Libros Compras";

  $j++;   // inclementa j    
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "librosVenta";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_tts-tickets-all_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "libros/list_libro.php?sTipo=VENTA";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Libros Venta";

  $j++;   // inclementa j    
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "librosGuia";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_tts-tickets-all_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "libros/list_libro.php?sTipo=GUIA";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Libros Gu&iacute;a";
  $j++;   // inclementa j    


if(trim($_SESSION["_COD_ROL_SESS"]) == "1" ||  trim($_SESSION["_COD_ROL_SESS"]) == "3" ){
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "cargaLibro";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_tts-tickets-all_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "libros/form_libro.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Carga Libros";
  $j++;   // inclementa j
}

/*
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "log";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_action-log_bg.gif";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "consulta/listdte.php";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
  $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Logs Sistema";
*/
  $i++;   // inclementa i
  $j=0;   // j = 0


      $_ARRAY_MENU["RAIZ"]["ID"][$i] = "mantencion";
      $_ARRAY_MENU["RAIZ"]["TITULO"][$i] = "Mantenci&oacute;n";

      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "clientes";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_client-templates_bg.gif";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "mantencion/list_clie.php";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Clientes";
      $j++;   // inclementa j       

if(trim($_SESSION["_COD_ROL_SESS"]) == "1"){	
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "contribuyente";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_mpc_bg.gif";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "mantencion/form_cont_elec.php";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Contribuyentes Electr&oacute;nicos";    
      $j++;   // inclementa j        
            
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "tipodoc";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_edit_bg.gif";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "mantencion/list_tip_doc.php";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Tipo Documentos";
      $j++;   // inclementa j 
 
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "consoli";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_edit_bg.gif";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "mantencion/list_estado.php";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Estado Documentos";
      $j++;   // inclementa j 


      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "consolibol";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_edit_bg.gif";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "mantencion/list_estado_boleta.php";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Estado Boletas";
      $j++;   // inclementa j
  }
  
 // if(trim($_SESSION["_COD_ROL_SESS"]) != "2")
//     $j = 0;   // inclementa j     

 // if($_SESSION["_NUM_EMP_USU_SESS"] > 1 || trim($_SESSION["_COD_ROL_SESS"]) == "1"){
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "sel_emp";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_edit_bg.gif";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "sel_emp.php";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Cambiar de Empresa";
      $j++;
 // }

      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "contacto_sii";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_edit_bg.gif";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "mantencion/form_user_sii.php";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "workFrame";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Act. Contacto SII";
      $j++;
          
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j] = "salir";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j] = "node";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j] = "skins/" . $_SKINS . "/images/btn_edit_bg.gif";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j] = "logout.php";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j] = "_top";
      $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j] = "Salir";

  $i++;
  $j=0;
      
  $strReturn = "";
  for($i=0; $i < sizeof($_ARRAY_MENU["RAIZ"]["ID"]); $i++){
   $id = $_ARRAY_MENU["RAIZ"]["ID"][$i];
   $nomCat = $_ARRAY_MENU["RAIZ"]["TITULO"][$i];
   $strSubCat = "";
   $strCat = strCategoria($id, $nomCat, $_SKINS);

   for($j=0; $j < sizeof($_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"]); $j++){
    $id = $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ID"][$j];
    $stylo = $_ARRAY_MENU["RAIZ"]["NODO"][$i]["CLASS"][$j];
    $path_icon = $_ARRAY_MENU["RAIZ"]["NODO"][$i]["ICONO"][$j];
    $link_cat = $_ARRAY_MENU["RAIZ"]["NODO"][$i]["LINK"][$j];
    $target = $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TARGET"][$j];
    $text_link = $_ARRAY_MENU["RAIZ"]["NODO"][$i]["TEXT_LINK"][$j];
    $strSubCat .= strSubCategoria($id, $stylo, $path_icon, $link_cat, $target, $text_link);

   }
   
   $strCat = str_replace("@@_SUB_CATEGORIA_@@", $strSubCat, $strCat);
   $strReturn .= $strCat;
  }
  echo $strReturn;
 }

 function strSubCategoria($id, $stylo, $path_icon, $link_cat, $target, $text_link){

  $strLink = '<table border="0" cellspacing="0" cellpadding="0" width="100%" id="' . $id . '" class="' . $stylo . '">
   <tr>
    <td class="nodeImage">
     <a href="' . $link_cat . '" title="' . $text_link . '" target="' . $target . '">
      <img src="' . $path_icon . '" width="16" height="16" border="0" alt="' . $text_link . '">
     </a>
    </td>
    <td width="100%">
     <span class="name">
      <a href="' . $link_cat . '" onClick="return irOpcion(\'' . $id . '\', \'' . $link_cat . '\');" target="' . $target . '" title="' . $text_link . '" onMouseOver="mouse_move(\'b_' . $id . '\')" onMouseOut="mouse_move()">
       ' . $text_link . '
      </a>
     </span>
    </td>
   </tr>
  </table>' . "\n";
  return $strLink;
 }


 function strCategoria($id, $nomCat, $_SKINS){
  $strCategoria = ' <table border="0" cellspacing="0" cellpadding="0" width="100%" class="navClosed" id="' . $id . '">
    <tr>

     <td>
      <table border="0" cellspacing="0" cellpadding="0" width="100%" class="navTitle" onMouseOut="mout(this);" onClick="return opentree (\'' . $id . '\');" onMouseOver="mover(this);" onmouseout="mout(this);">
      <tr>
       <td class="titleLeft"><img src="skins/' . $_SKINS . '/images/topleft.gif" border="0" alt=""/></td>
       <td class="titleText" width="100%">' . $nomCat . '</td>
       <td class="titleHandle"><img src="skins/' . $_SKINS . '/images/1x1.gif" width="20" height="1" border="0" alt=""/></td>
       <td class="titleRight"><img src="skins/' . $_SKINS . '/images/topright.gif" border="0" alt=""/></td>
      </tr>

      </table>
     </td>
    </tr>

    
    <tr>
     <td>
      <div class="tree">
       <table border="0" cellspacing="0" cellpadding="0" width="100%">
       <tr>
        <td>
         @@_SUB_CATEGORIA_@@
        </td>
       </tr>
       </table>
      </div>
     </td>

    </tr>
    </table>';
  return $strCategoria;
 }

?>
