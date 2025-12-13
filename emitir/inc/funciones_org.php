<?php
set_time_limit(0);session_start();header("Expires:Mon,22 Jul 2019 05:00:00 GMT");header("Last-Modified:".gmdate("D,d M Y H:i:s")." GMT");header("Cache-Control:no-store,no-cache,must-revalidate");header("Cache-Control:post-check=0,pre-check=0",false);header("Pragma:no-cache");

$cn=pg_connect("host=10.0.0.180 user=opendte password='root8831' dbname=godte port=5432 options='--client_encoding=LATIN1'");
//$cn=pg_connect("host=localhost user=postgres password='123' dbname=godte port=5432 options='--client_encoding=LATIN1'");

foreach($_REQUEST as $k=>$v){$$k=htmlspecialchars(str_replace("'","",trim($v)));}
//foreach($_REQUEST as $k=>$v){$$k=$v;}
function texto_val($texto){return htmlspecialchars(str_replace("'","",trim($texto)));}
function dame_nombre_mes($mes){switch ($mes){case 1:$nombre_mes="Enero"; break;case 2:$nombre_mes="Febrero";break;case 3:$nombre_mes="Marzo";break;case 4:$nombre_mes="Abril";break;case 5:$nombre_mes="Mayo";break;case 6:$nombre_mes="Junio";break;case 7:$nombre_mes="Julio";break;case 8:$nombre_mes="Agosto";break;case 9:$nombre_mes="Septiembre";break;case 10:$nombre_mes="Octubre";break;case 11:$nombre_mes="Noviembre";break;case 12:$nombre_mes="Diciembre";break;}return $nombre_mes;}
function ffecha($lsFecha){return substr($lsFecha,6,4).substr($lsFecha,3,2).substr($lsFecha,0,2);}
function entero($num){return number_format($num,0,".",",");}

function seteaMultiEmpresa($cn){
global $_NUMERO_RESOLUCION,$_FECHA_RESOLUCION,$_PATH_REAL_DTE,$_PATH_REAL_DTE_LIBROS,$_PATH_ENTRADA_LIBRO_GUIA,$_PATH_REAL_DTE_BOLETA,$_PATH_REAL_CAF,$_PATH_TERCEROS_PDF,$_RUT_CONTRIBUYENTE_ENVIADOR,$_PATH_REAL_CERT_DIGITAL,$_PATH_REAL_LIC_DIGITAL,$_OFICINA_SII;
if(trim($_SESSION["DEFAULT_EMP_CODI"])!=""){
$result=pg_query($cn,"SELECT propiedades,rut_enviador FROM empresa WHERE codi_empr='".trim($_SESSION["DEFAULT_EMP_CODI"])."'");
$nNumMon=0;
while ($row=pg_fetch_array($result)){
$propiedades=trim($row["propiedades"]);
$_RUT_CONTRIBUYENTE_ENVIADOR=trim($row["rut_enviador"]);
++$nNumMon;	
}    
if ($nNumMon>0){ 
$aPropiedad=explode("\n",$propiedades);
for($i=0;$i<sizeof($aPropiedad);$i++){
$tmp=explode("=",$aPropiedad[$i]);
if(trim(strtoupper($tmp[0]))=="NUMERO_RESOLUCION"){
$tmp=explode("#",$tmp[1]);
$_NUMERO_RESOLUCION=trim($tmp[0]);
continue;
}
if(trim(strtoupper($tmp[0]))=="OFICINA_SII"){
$tmp=explode("#",$tmp[1]);
$_OFICINA_SII=trim($tmp[0]);
continue;
}
if(trim(strtoupper($tmp[0]))=="FECHA_RESOLUCION"){
$tmp=explode("#",$tmp[1]);
$_FECHA_RESOLUCION=trim($tmp[0]);
continue;
}
if(trim(strtoupper($tmp[0]))=="PATH_DIRECTORIO_ENTRADA"){
$tmp=explode("#",$tmp[1]);
$_PATH_REAL_DTE=trim($tmp[0]);
continue;
}
if(trim(strtoupper($tmp[0]))=="PATH_DIRECTORIO_ENTRADA_LIBRO"){
$tmp=explode("#",$tmp[1]);
$_PATH_REAL_DTE_LIBROS=trim($tmp[0]);
continue;
}
if(trim(strtoupper($tmp[0]))=="PATH_DIRECTORIO_ENTRADA_LIBRO_GUIA"){
$tmp=explode("#",$tmp[1]);
$_PATH_ENTRADA_LIBRO_GUIA=trim($tmp[0]);
continue;
}
if(trim(strtoupper($tmp[0]))=="PATH_DIRECTORIO_ENTRADA_BOLETA"){
$tmp=explode("#",$tmp[1]);
$_PATH_REAL_DTE_BOLETA=trim($tmp[0]);
continue;
}
if(trim(strtoupper($tmp[0]))=="DIRECTORIO_ARCHIVOS_PDF_RECIBIDO"){
$tmp=explode("#",$tmp[1]);
$_PATH_TERCEROS_PDF=trim($tmp[0]);
continue;
}
if(trim(strtoupper($tmp[0]))=="PATH_REAL_CERT_DIGITAL"){
$tmp=explode("#",$tmp[1]);
$_PATH_REAL_CERT_DIGITAL=trim($tmp[0]);
continue;
}
if(trim(strtoupper($tmp[0]))=="PATH_REAL_LIC_DIGITAL"){
$tmp=explode("#",$tmp[1]);
$_PATH_REAL_LIC_DIGITAL=trim($tmp[0]);
continue;
}
if(trim(strtoupper($tmp[0]))=="PATH_REAL_CAF"){
$tmp=explode("#",$tmp[1]);
$_PATH_REAL_CAF=trim($tmp[0]);
continue;
}		
}
}
}
}

function timestamp2(){return date("Y",time()).date("m",time()).date("d",time()).date("H",time()).date("i",time()).date("s",time());}
function calcula_numero_dia_semana($dia,$mes,$ano){$numerodiasemana=date('w',mktime(0,0,0,$mes,$dia,$ano));if ($numerodiasemana==0)$numerodiasemana=6;else --$numerodiasemana;return $numerodiasemana;}
function ultimoDia($mes,$ano){$ultimo_dia=28;while (checkdate($mes,$ultimo_dia+1,$ano)){++$ultimo_dia;}return $ultimo_dia;} 

function send_mail($html,$from,$sub,$to){
$mail=new PHPMailer();
$mail->Mailer="smtp";
$mail->Host="smtp.mailgun.org"; 
$mail->From="postmaster@mg.opendte.cl"; 
$mail->SMTPAuth=true;
$mail->Username="postmaster@mg.opendte.cl"; 
$mail->Password="8zhou6ut9hq4";
$mail->FromName="SAC OpenDTE GO";
$mail->AddAddress($to,""); 
$mail->WordWrap=100;
$mail->IsHTML(true);
$mail->Subject=$sub;
$htm="<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<link rel='stylesheet' type='text/css' href='http://godte.opendte.cl/inc/estilo.css'/>
</head>
<body topmargin='0' rightmargin='0' bottommargin='0' leftmargin='0'>
<table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0'>
<tr height='100px'><td>
<table width='100%' height='100%' cellpadding='0' cellspacing='0'><tr><td width='70%' valign='middle'>&nbsp;<img src='http://godte.opendte.cl/img/img_modulo_go3.png'>&nbsp;&nbsp;&nbsp;<img src='http://godte.opendte.cl/img/img_logo2.png'/></td></tr>
<tr bgcolor='#FF9900'><td colspan='2' style='font-size:5px'>&nbsp;</td></tr>
</table>
</td></tr>
<tr height='100%'><td>
<table border='0' cellpadding='10' cellspacing='0' width='100%' height='100%'>
<tr>
<td width='90%' valign='top'><br><div style='overflow:auto;height:100%' class='texto'>
".$html."
</div></td>
</tr>
</table>
</td></tr>
<tr height='15px'><td class='nota2' align='right'>Desarrollado por OpenB 2014-2020 <a href='http://www.openb.cl' target='_blank' title='Visitar OpenB'>www.openb.cl</a>&nbsp;&nbsp;</td></tr>
</table>
</body>
</html>";
$mail->Body=$htm;
$mail->AltBody="";
$mail->Send();
}


function send_mail2($html,$from,$sub,$to,$adjunto_path,$adjunto_nom){
$mail=new PHPMailer();
$mail->Mailer="smtp";
$mail->Host="smtp.mailgun.org"; 
$mail->From="postmaster@mg.opendte.cl"; 
$mail->SMTPAuth=true;
$mail->Username="postmaster@mg.opendte.cl"; 
$mail->Password="8zhou6ut9hq4";
$mail->FromName="SAC OpenDTE GO";
$mail->AddAddress($to,""); 
$mail->WordWrap=100;
$mail->AddAttachment($adjunto_path,$adjunto_nom);
$mail->IsHTML(true);
$mail->Subject=$sub;
$htm="<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<link rel='stylesheet' type='text/css' href='http://godte.opendte.cl/inc/estilo.css'/>
</head>
<body topmargin='0' rightmargin='0' bottommargin='0' leftmargin='0'>
<table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0'>
<tr height='100px'><td>
<table width='100%' height='100%' cellpadding='0' cellspacing='0'><tr><td width='70%' valign='middle'>&nbsp;<img src='http://godte.opendte.cl/img/img_modulo_go3.png'>&nbsp;&nbsp;&nbsp;<img src='http://godte.opendte.cl/img/img_logo2.png'/></td></tr>
<tr bgcolor='#FF9900'><td colspan='2' style='font-size:5px'>&nbsp;</td></tr>
</table>
</td></tr>
<tr height='100%'><td>
<table border='0' cellpadding='10' cellspacing='0' width='100%' height='100%'>
<tr>
<td width='90%' valign='top'><br><div style='overflow:auto;height:100%' class='texto'>
".$html."
</div></td>
</tr>
</table>
</td></tr>
<tr height='15px'><td class='nota2' align='right'>Desarrollado por OpenB 2014-2020 <a href='http://www.openb.cl' target='_blank' title='Visitar OpenB'>www.openb.cl</a>&nbsp;&nbsp;</td></tr>
</table>
</body>
</html>";
$mail->Body=$htm;
$mail->AltBody="";
$mail->Send();
}

class respuestaFirma{
private $urlPDF="";
private $Estado="";
private $ResultadoGlosa="";
function setValor($pdf,$estado,$resulGlosa){
$this->urlPDF=$pdf;
$this->Estado=$estado;	
$this->ResultadoGlosa=$resulGlosa;	
}
function getPDF(){return $this->urlPDF;}
function getResulGlosa(){return $this->ResultadoGlosa;}
function getEstado(){return $this->Estado;}
}

function firmarDTE($xml, $rutEmisor, $dvEmisor, $tipoDTE=""){
$WSDLFirmaDTE="http://10.0.0.96:8080/OpenDTEWS/services/FirmaDTE";
if ($tipoDTE== "39" || $tipoDTE== "41")
$WSDLFirmaDTE="http://10.0.0.96:8080/OpenDTEWS/services/FirmaBoleta";
if ($tipoDTE== "110" || $tipoDTE== "111" || $tipoDTE== "112")
$WSDLFirmaDTE="http://10.0.0.96:8080/OpenDTEWS/services/FirmaDTEExportacion";
$respuesta=new respuestaFirma();
try {
$soapClient=new SoapClient($WSDLFirmaDTE.'?wsdl',array('encoding'=>'ISO-8859-1'));
$soapClient->__setLocation($WSDLFirmaDTE);
$parametros=array();
$parametros["RUTEmisor"]=$rutEmisor;
$parametros["DVEmisor"]=$dvEmisor;
$parametros["tipoArchivo"]="XML";
$parametros["archivo"]=$xml;
if($tipoDTE == "110" || $tipoDTE== "111" || $tipoDTE== "112"){
	$r=$soapClient->firmaDTEExportacion($parametros);
}
else{
	$parametros["apikey"]="";
	$r=$soapClient->firmaDTE($parametros);
}
$xmlResult=$r;		
foreach ($r as $valor){
$resXML=new SimpleXMLElement("<?xml version='1.0' encoding='ISO-8859-1'?>" . $valor);
$estado=$resXML->Estado;
if($estado== "1"){$pdf=$resXML->URLpdf;}
$resulGlosa=$resXML->Glosa;		
$respuesta->setValor($pdf,$estado,$resulGlosa);
break;
}
} 
catch (Exception $e) {
$respuesta->setValor("","0",$e->getMessage());  
}
return $respuesta;
}

function addConfPrint($cn,$nCodEmpr,$sRutEmp){
// PDF VENTA
addParamPrint($cn,33,$nCodEmpr,$sRutEmp,"");
addParamPrint($cn,34,$nCodEmpr,$sRutEmp,"");
addParamPrint($cn,39,$nCodEmpr,$sRutEmp,""); 
addParamPrint($cn,41,$nCodEmpr,$sRutEmp,"");
addParamPrint($cn,46,$nCodEmpr,$sRutEmp,"");
addParamPrint($cn,52,$nCodEmpr,$sRutEmp,"");
addParamPrint($cn,56,$nCodEmpr,$sRutEmp,"");
addParamPrint($cn,61,$nCodEmpr,$sRutEmp,"");
addParamPrint($cn,110,$nCodEmpr,$sRutEmp,"");
addParamPrint($cn,111,$nCodEmpr,$sRutEmp,"");
addParamPrint($cn,112,$nCodEmpr,$sRutEmp,"");
// PDF COMPRA
addParamPrint($cn,33,$nCodEmpr,$sRutEmp,"compra_");
addParamPrint($cn,34,$nCodEmpr,$sRutEmp,"compra_");
addParamPrint($cn,39,$nCodEmpr,$sRutEmp,"compra_");
addParamPrint($cn,41,$nCodEmpr,$sRutEmp,"compra_");
addParamPrint($cn,46,$nCodEmpr,$sRutEmp,"compra_");
addParamPrint($cn,52,$nCodEmpr,$sRutEmp,"compra_");
addParamPrint($cn,56,$nCodEmpr,$sRutEmp,"compra_");
addParamPrint($cn,61,$nCodEmpr,$sRutEmp,"compra_");
addParamPrint($cn,110,$nCodEmpr,$sRutEmp,"compra_");
addParamPrint($cn,111,$nCodEmpr,$sRutEmp,"compra_");
addParamPrint($cn,112,$nCodEmpr,$sRutEmp,"compra_");
}

function addParamPrint($cn,$tipoDTE,$nCodEmpr,$sRutEmp,$sCompra){
if($sCompra=="compra_") $nTipMov=2; else $nTipMov=1;
$sql="INSERT INTO parametros_impresion (tipo_dt,tipo_movimiento,ruta_pdf_papel,ruta_propiedades,codi_empr) VALUES ('".$tipoDTE."','".$nTipMov."','/opt/opendte/Archivos/".$sRutEmp."/Plantillas/dte_".$sCompra.$tipoDTE.".pdf','/opt/opendte/Archivos/".$sRutEmp."/Plantillas/dte_".$sCompra.$tipoDTE.".properties','".$nCodEmpr."')";
pg_query($cn,$sql);
}

function crearDirectorio($sRutEmp){
shell_exec("LANG=es_CL.ISO-8859-1;cp -Rp /opt/opendte/Archivos/RUT_TEMPLATE/ /opt/opendte/Archivos/".$sRutEmp."/");	
}

function estados_dte($cod){
switch ($cod){
case 1: return "Firmado"; break;
case 3: return "Error"; break;
case 5: return "Empaquetado"; break;
case 13: return "Enviado a SII"; break;
case 29: return "Aceptado SII"; break;
case 45: return "Aceptado SII"; break;
case 77: return "Rechazado SII"; break;
case 157: return "Enviado a Clientes Aceptado SII"; break;
case 173: return "Enviado a Clientes Aceptado SII"; break;
case 413: return "Aceptado Cliente"; break;
case 429: return "Aceptado Cliente aceptado SII"; break;
case 512: return "Aprobado Cliente"; break;
case 1181: return "Rechazado Cliente Aceptado SII"; break;
case 1197: return "Rechazado Cliente Aceptado SII"; break;
case 1437: return "Rechazado Comercial Aceptado SII"; break;
case 1453: return "Rechazado Comercial Aceptado SII"; break;
}
}

function tipos_dte($cod){
switch ($cod){
case 35: return "Boleta"; break;
case 39: return "Boleta electronica"; break;
case 38: return "Boleta exenta"; break;
case 41: return "Boleta exenta Electronica"; break;
case 914: return "Declaracion de ingreso"; break;
case 30: return "Factura"; break;
case 45: return "Factura compra"; break;
case 46: return "Factura de compra electronica"; break;
case 101: return "Factura de exportacion"; break;
case 110: return "Factura de exportacion electronica"; break;
case 33: return "Factura electronica"; break;
case 32: return "Factura No afecta"; break;
case 34: return "Factura No afecta o Exenta electronica"; break;
case 50: return "Guia de despacho"; break;
case 52: return "Guia de despacho electronica"; break;
case 40: return "Liquidacion factura"; break;
case 43: return "Liquidacion factura electronica"; break;
case 60: return "Nota de credito"; break;
case 106: return "Nota de credito de exportacion"; break;
case 112: return "Nota de credito de exportacion electronica"; break;
case 61: return "Nota de credito electronica"; break;
case 55: return "Nota de debito"; break;
case 104: return "Nota de debito de exportacion"; break;
case 111: return "Nota de debito de exportacion electronica"; break;
case 56: return "Nota de debito electronica"; break;
}}

function CreaVariables($cn,$consulta)

	{

				$result=pg_query($cn, $consulta);
				$row=pg_fetch_all($result);
				$count_campos= pg_num_fields($result);
				$count_row= pg_num_rows($result);
		for ($a=0;$a<$count_campos;)
		{	

			$extraeCampos= pg_field_name($result, $a);
			$campo=$extraeCampos;
			$VariableCampo[$campo][0]=$campo;
			for($b=0;$b<$count_row;){
			$VariableCampo[$campo][$b+1]=$row[$b][$campo];
			++$b;
			}
			++$a;
				}
				pg_free_result($result);
				unset($consulta,$result,$cn);
	return $VariableCampo;
}

function query_result($query,$cn){
	$result=pg_query($cn,$query);
	if (pg_connection_status($cn)) return 0;
	else return $result; 
	unset($query,$result,$cn);
} 

function ValidezCertifiacado($rutaCertificado,$certifiacdo){
$p12cert=array();
$file=$rutaCertificado;
$pass=$certifiacdo;
$fd=fopen($file, 'r');
$p12buf=fread($fd, filesize($file));
fclose($fd);
( openssl_pkcs12_read($p12buf, $p12cert, $pass) );
$certificado=$p12cert["cert"];
$data=openssl_x509_parse($p12cert['cert']);
//$valido['desde']= $data['validFrom_time_t'];
$valido['hasta']=$data['validTo_time_t'];
return $valido;
}



function diferenciatiempo($tiempo_final){
		 $ini_time=mktime();
			
$tiempo=$tiempo_final  - $ini_time;
$segundos=1;
$minutos=60 * $segundos;
$horas=60 * $minutos;
$dias=$horas * 24;
$dias_real=($tiempo - (fmod($tiempo , $dias)))/$dias;
$tiempo=fmod($tiempo , $dias);
$horas_real=($tiempo - (fmod($tiempo , $horas)))/$horas;
$tiempo=fmod($tiempo , $horas);
$minutos_real=($tiempo - (fmod($tiempo , $minutos)))/$minutos;
$tiempo=fmod($tiempo , $minutos);
$segundos_real=($tiempo / $segundos) - fmod($tiempo , $segundos);

$salida=$dias_real." dias, ";
$salida.= $horas_real." horas, ";
$salida.= $minutos_real." minutos, ";
$salida.= $segundos_real." segundos ";
return $salida;
}
?>
