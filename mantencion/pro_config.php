<?php 
	include("../include/config.php");  
	include("../include/ver_aut.php");      
	include("../include/ver_aut_adm.php");        
	include("../include/db_lib.php"); 
  
	$conn = conn();

	$aCodConfig = $_POST["aCodConfig"];  
	$aDescConfig = $_POST["aDescConfig"];
	$nTotalRadio = $_POST["nTotalRadio"];  
	$nCodEmp = $_POST["nCodEmp"];  

	$sql = "SELECT 
			cod_config, 
			map_config, 
			valor_config, 
			tipo_campo, 
			val_perm, 
			valor_default , 
			valor_sin_selec, 
			orden
		FROM 
			config 
		WHERE 
			codi_empr = 0
		ORDER BY orden";        
	$result = rCursor($conn, $sql);
	
	while(!$result->EOF) {
		$map_config[trim($result->fields["cod_config"])] = trim($result->fields["map_config"]);
		$tipo_campo[trim($result->fields["cod_config"])] = trim($result->fields["tipo_campo"]);
		$val_perm[trim($result->fields["cod_config"])] = trim($result->fields["val_perm"]);
		$valor_default[trim($result->fields["cod_config"])] = trim($result->fields["valor_default"]);
		$valor_sin_selec[trim($result->fields["cod_config"])] = trim($result->fields["valor_sin_selec"]);
		$orden[trim($result->fields["cod_config"])] = trim($result->fields["orden"]);
		$result->MoveNext();
	} 


  $sql = "DELETE FROM config WHERE codi_empr = '" . trim($nCodEmp) . "'";      
  nrExecuta($conn, $sql);

  for($i=0; $i < sizeof($aCodConfig); $i++){   	  
	  $sql = "INSERT INTO config 
				(cod_config, map_config, valor_config, codi_empr, tipo_campo, val_perm, valor_default, valor_sin_selec, orden) 
			  VALUES(
				  '" . str_replace("'","''",trim($aCodConfig[$i])) . "',
				  '" . str_replace("'","''",trim($map_config[trim($aCodConfig[$i])])) . "',
				  '" . str_replace("'","''",trim($aDescConfig[$i])) . "',
				  '" . trim($nCodEmp) . "',
				  '" . str_replace("'","''",trim($tipo_campo[trim($aCodConfig[$i])])) . "',
				  '" . str_replace("'","''",trim($val_perm[trim($aCodConfig[$i])])) . "',
				  '" . str_replace("'","''",trim($valor_default[trim($aCodConfig[$i])])) . "',
				  '" . str_replace("'","''",trim($valor_sin_selec[trim($aCodConfig[$i])])) . "',
				  '" . str_replace("'","''",trim($orden[trim($aCodConfig[$i])])) . "')
			";      

	  nrExecuta($conn, $sql);
  }

  for($i=0; $i < $nTotalRadio; $i++){      
	  $aCodConfigR = trim($_POST["aCodConfig" . $i]);
	  $aDescConfigR = trim($_POST["aDescConfig" . $i]);

	  $sql = "INSERT INTO config 
				(cod_config, map_config, valor_config, codi_empr, tipo_campo, val_perm, valor_default, valor_sin_selec, orden) 
			  VALUES(
				  '" . str_replace("'","''",trim($aCodConfigR)) . "',
				  '" . str_replace("'","''",trim($map_config[trim($aCodConfigR)])) . "',
				  '" . str_replace("'","''",trim($aDescConfigR)) . "',
				  '" . trim($nCodEmp) . "',
				  '" . str_replace("'","''",trim($tipo_campo[$aCodConfigR])) . "',
				  '" . str_replace("'","''",trim($val_perm[$aCodConfigR])) . "',
				  '" . str_replace("'","''",trim($valor_default[$aCodConfigR])) . "',
				  '" . str_replace("'","''",trim($valor_sin_selec[$aCodConfigR])) . "',
				  '" . str_replace("'","''",trim($orden[$aCodConfigR])) . "')
			";      
	  nrExecuta($conn, $sql);
  }

  generarProperties($conn);

  function generarProperties($conn){
	  global $nCodEmp;

        $sql = "SELECT 
					cod_config, 
					map_config, 
					valor_config, 
					tipo_campo, 
					val_perm, 
					valor_default 
				FROM 
					config 
				WHERE 
					codi_empr = '" . trim($nCodEmp) . "'
				ORDER BY orden";        

		$result = rCursor($conn, $sql);  
		
		$strProperties = propertiesTemplate();

		while(!$result->EOF) {
			$valor_config = trim($result->fields["valor_config"]);
			$valor_default = trim($result->fields["valor_default"]);
			
			if($valor_config == "")
				$valor_config = $valor_default;

			$strProperties = str_replace("{" . trim($result->fields["cod_config"]) . "}",$valor_config, $strProperties);
			$result->MoveNext();
		} 

		$sql = "SELECT  
			rut_empr,
			dv_empr,
			fec_resolucion,
			num_resolucion,
			path_licencia
		FROM 
			empresa
		WHERE 
			codi_empr = '" . trim($nCodEmp) . "'";
		$result = rCursor($conn, $sql);
		if(!$result->EOF) {
			$aLicencia = explode("/",trim($result->fields["path_licencia"]));
			$sNomLic = $aLicencia[sizeof($aLicencia) - 1];

			$strProperties = str_replace("{RUT_EMPRESA_EMISORA_SINDV}",trim($result->fields["rut_empr"]), $strProperties);
			$strProperties = str_replace("{DV_EMPRESA_EMISORA}",trim($result->fields["dv_empr"]), $strProperties);
			$strProperties = str_replace("{FECHA_RESOLUCION}",trim($result->fields["fec_resolucion"]), $strProperties);
			$strProperties = str_replace("{NUMERO_RESOLUCION}",trim($result->fields["num_resolucion"]), $strProperties);
			$strProperties = str_replace("{LICENCIA_EMPRESA}",trim($sNomLic), $strProperties);
			

			$sql = "UPDATE 
						empresa 
					SET 
						propiedades = '" . $strProperties . "' 
					WHERE 
						codi_empr = '" . trim($nCodEmp) . "'";
			nrExecuta($conn, $sql);
		}

  }

  function propertiesTemplate(){

$strProperties = "#DEBUG
HABILITAR_DEBUG={HABILITAR_DEBUG}

#ALERTAS
HABILITAR_ALERTAS={HABILITAR_ALERTAS}
ALERTAS_MAIL_TO={ALERTAS_MAIL_TO}
ALERTAS_MAIL_FROM=alertas@opendte.cl
ALERTAS_MAIL_REPLYTO=alertas@opendte.cl


#VARIABLES DEL SISTEMA
RUT_EMPRESA_EMISORA={RUT_EMPRESA_EMISORA_SINDV}-{DV_EMPRESA_EMISORA}
RUT_EMPRESA_EMISORA_SINDV={RUT_EMPRESA_EMISORA_SINDV}
DV_EMPRESA_EMISORA={DV_EMPRESA_EMISORA}
NUMERO_RESOLUCION={NUMERO_RESOLUCION}
FECHA_RESOLUCION={FECHA_RESOLUCION}
RUT_SII=60803000-K
OFICINA_SII={OFICINA_SII}

#Configuracion Sistema
PATH_DIRECTORIO_BASE=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}

PATH_DIRECTORIO_ENTRADA_LIBRO_GUIA=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/EntradaLibrosGuia/
PATH_DIRECTORIO_SALIDA_LIBRO_GUIA=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/Salida/
PATH_DIRECTORIO_ENTRADA_LIBRO=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/EntradaLibros/
PATH_DIRECTORIO_SALIDA_LIBRO=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/SalidaLibros/
PATH_DIRECTORIO_ENTRADA=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/Entrada/
PATH_DIRECTORIO_SALIDA=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/Salida/
PATH_DIRECTORIO_ENVIOXML_TERCEROS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/envioTerceros/
PATH_TMP_DIR=/tmp/
IMAGENES_APLICACION=/opt/opendte/Imagenes/
DIRECTORIO_ARCHIVOS_PDF=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/PDF/
MEMBRETE=N
TIPOMOV=0
PATH_SELLO_AGUA=/opt/opendte/Imagenes/selloAguaOpenb.jpg
PATH_SELLO_AGUA_COMPRA=/opt/opendte/Imagenes/selloAguaOpenb.jpg
PATH_ARCHIVO_LICENCIA=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/Licencia/{LICENCIA_EMPRESA}
PATH_REAL_CERT_DIGITAL=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/Certificado/
PATH_REAL_LIC_DIGITAL=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/Licencia/
PATH_REAL_CAF=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/CAF/


PATH_DIRECTORIO_ENTRADA_BOLETA=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/EntradaBoleta/
PATH_DIRECTORIO_SALIDA_BOLETA=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/SalidaBoleta/

PATH_DIRECTORIO_ENTRADA_BOLETA_XML=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/EntradaBoletaXML/
PATH_DIRECTORIO_SALIDA_BOLETA_XML=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/SalidaBoletaXML/
PATH_DIRECTORIO_ERRORES_PROCESATXT_BOLETA_XML=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/SalidaErroresBoletaXML/

#PDF Terceros
DIRECTORIO_ARCHIVOS_PDF_RECIBIDO=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/Pdf_Tercero/
PATH_DIRECTORIO_RECEPXML_TERCEROS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/xml_recibo/

#Variables email de contacto SII
SERVIDOR_SMTP_RESPUESTAS_SII={SII_MAIL_HOST}
PROTOCOLO_SERVIDOR_SMTP_RESPUESTAS_SII={SII_MAIL_PROTOCOL}
SERVIDOR_SMTP_RESPUESTAS_SII_USER={SII_MAIL_USER}
SERVIDOR_SMTP_RESPUESTAS_SII_PASS={SII_MAIL_PASS}

MAIL_RESPUESTAS_SII={MAIL_DTE_TERCEROS}

SERVIDOR_SMTP_RESPUESTAS_SII_REQUIERE_AUTH={SERVIDOR_SMTP_AUTH}
PATH_RESPALDO_EMAILS_SII=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/RespaldoEmailSII/SII
PATH_MSG_REPARO_ENVIO_SII=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/Info/mensajeReparoError.txt
#Direccion de email que recibe avisos de reparos o error del SII
DIRECCION_EMAIL_AVISO_REPAROS_ERROR_SII={DIRECCION_EMAIL_AVISO_REPAROS_ERROR_SII}

MAXIMO_EMAILS_PROCESAR={MAXIMO_EMAILS_PROCESAR}

MAIL_PATH_RESPALDO_SII=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/CorreosSII/
MAIL_PATH_RESPALDO_SII_ACEPTADOS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/CorreosSII/Aceptados/
MAIL_PATH_RESPALDO_SII_ACEPTADOS_REPAROS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/CorreosSII/Reparos/
MAIL_PATH_RESPALDO_SII_RECHAZOS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/CorreosSII/Rechazos/

SII_MAIL_HOST={SII_MAIL_HOST}
SII_MAIL_PROTOCOL={SII_MAIL_PROTOCOL}
SII_MAIL_FOLDER={SII_MAIL_FOLDER}
SII_MAIL_PORT={SII_MAIL_PORT}
SII_MAIL_USER={SII_MAIL_USER}
SII_MAIL_PASS={SII_MAIL_PASS}

#Variables email desde Otros Contribuyentes Electronicos
MAIL_DTE_TERCEROS={MAIL_DTE_TERCEROS}
SERVIDOR_SMTP_DTE_TERCEROS={OTROS_MAIL_HOST}
PROTOCOLO_SERVIDOR_SMTP_DTE_TERCEROS={OTROS_MAIL_PROTOCOL}
SERVIDOR_SMTP_DTE_TERCEROS_USER={OTROS_MAIL_USER}
SERVIDOR_SMTP_DTE_TERCEROS_PASS={OTROS_MAIL_PASS}

SERVIDOR_SMTP_DTE_TERCEROS_REQUIERE_AUTH={SERVIDOR_SMTP_AUTH}

PATH_RESPALDO_EMAILS_TERCEROS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/RespaldoEmailSII/DTE

OTROS_MAIL_HOST={OTROS_MAIL_HOST}
OTROS_MAIL_PROTOCOL={OTROS_MAIL_PROTOCOL}
OTROS_MAIL_FOLDER={OTROS_MAIL_FOLDER}
OTROS_MAIL_PORT={OTROS_MAIL_PORT}
OTROS_MAIL_USER={OTROS_MAIL_USER}
OTROS_MAIL_PASS={OTROS_MAIL_PASS}

MAIL_PATH_RESPALDO_OTROS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/CorreosOtros/Otros/
MAIL_PATH_RESPALDO_OTROS_ENVIOS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/CorreosOtros/Envios/
MAIL_PATH_RESPALDO_OTROS_RESPUESTAS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/CorreosOtros/Respuestas/
MAIL_PATH_RESPALDO_OTROS_RESPUESTAS_MERCADERIAS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/CorreosOtros/Recibo/


# Variables envio mails a terceros
MAIL_FROM_ENVIOXML_TERCEROS={MAIL_DTE_TERCEROS}
MAIL_CC_ENVIOXML_TERCEROS=N
MAIL_BCC_ENVIOXML_TERCEROS=N
MAIL_REPLYTO_ENVIOXML_TERCEROS={MAIL_DTE_TERCEROS}

SERVIDOR_SMTP={SERVIDOR_SMTP}
SERVIDOR_SMTP_USER={SERVIDOR_SMTP_USER}
SERVIDOR_SMTP_PASS={SERVIDOR_SMTP_PASS}
SERVIDOR_SMTP_AUTH={SERVIDOR_SMTP_AUTH}
SERVIDOR_SMTP_REQUIERE_AUTH={SERVIDOR_SMTP_AUTH}
SERVIDOR_SMTP_DTE_TERCEROS_REQUIERE_AUTH={SERVIDOR_SMTP_AUTH}

PATH_MSG_ENVIOXML_TERCEROS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/Info/MSG_ENVIOXML_TERCEROS.txt
PATH_MSG_RESPUESTA_XML_TERCEROS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/Info/MSG_RESPUESTA_XML_TERCEROS.txt
SUBJECT_ENVIOXML_TERCEROS={SUBJECT_ENVIOXML_TERCEROS}
SUBJECT_ENVIO_PDF_TERCEROS={SUBJECT_ENVIO_PDF_TERCEROS}
SUBJECT_RESPUESTA_XML_TERCEROS={SUBJECT_RESPUESTA_XML_TERCEROS}
PATH_MSG_ENVIO_PDF_TERCEROS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/Info/MSG_ENVIO_PDF_TERCEROS.txt

# Comandos de Impresion
HABILITAR_IMPRESION=N
IMPRESORA_POSTSCRIPT=N

HABILITAR_CESION={HABILITAR_CESION}
RUT_CESION={RUT_CESION}
NOMBRE_CESION={NOMBRE_CESION}

#Procesamiento de CAF
ARCHIVO_TEMPORAL_LLAVE_CAF_PRIV=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/CAF/Temp/llave.priv
ARCHIVO_TEMPORAL_LLAVE_CAF_PEM=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/CAF/Temp/llave.pem
COMANDO_OPENSSL_CAF=/usr/bin/openssl pkcs8 -in /opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/CAF/Temp/llave.priv -topk8 -nocrypt -out /opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/CAF/Temp/llave.pem

PATH_MSG_RECIBO_MERCADERIAS=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/Info/MSG_RECIBO_MERCADERIAS.TXT
SUBJECT_RECIBO_MERCADERIAS=Recibo Mercaderias
NOMBRE_CONTACTO_EMPRESA=
FONO_CONTACTO_EMPRESA=
MAIL_CONTACTO_EMPRESA=

URL_ARCHIVO_PDF_WS=http://{URL_ARCHIVO_PDF_WS}/OpenDTEWS/
PATH_DIRECTORIO_ENTRADA_XML=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/EntradaXML/
PATH_DIRECTORIO_SALIDA_XML=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/SalidaXML/

PATH_DIRECTORIO_ENTRADA_XML_EXPORTACION=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/EntradaExportacion/
PATH_DIRECTORIO_SALIDA_XML_EXPORTACION=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/SalidaExportacion/
PATH_DIRECTORIO_ENTRADA_XML_LIQUIDACION=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/EntradaLiquidacion/
PATH_DIRECTORIO_SALIDA_XML_LIQUIDACION=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/SalidaLiquidacion/


#DIRECTORIO CON SALIDA DE ERRORES DE PROCESAMIENTO TXT
PATH_DIRECTORIO_ERRORES_PROCESATXT=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/salidaErrores/
PATH_DIRECTORIO_ERRORES_PROCESATXT_BOLETA=/opt/opendte/Archivos/{RUT_EMPRESA_EMISORA_SINDV}/salidaBoletaErrores/

#Usa Plantillas
PDF_USA_PLANTILLAS=S
PDF_POS_X=4
PDF_POS_Y=60

#Respeta folios
RESPETA_FOLIO_TXT_XML_ENTRADA={RESPETA_FOLIO_TXT_XML_ENTRADA}


#Modulo Compras
HABILITAR_ENVIO_ERP={HABILITAR_ENVIO_ERP}

#Datos para consulta a SII
RUT_AUTENTICACION_SII={RUT_AUTENTICACION_SII}
DV_AUTENTICACION_SII={DV_AUTENTICACION_SII}
CLAVE_AUTENTICACION_SII={CLAVE_AUTENTICACION_SII}

";

	return $strProperties;
  }

  header("location:fin_config.php");
  exit;    
?>
