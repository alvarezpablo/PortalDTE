<?php 
/** Error reporting */
//error_reporting(E_ALL);
//ini_set('display_errors', false);
//ini_set('display_startup_errors', false);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

if( ! ini_get('date.timezone') )
{
    date_default_timezone_set('GMT');
}

include("../include/config.php");  
include("../include/ver_aut.php");      
include("../include/ver_emp_adm.php"); 
include("../include/tables.php");  

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
$msjGlobal = "";
$RUTEmisor = "99555660-K";
$RUTEmisorSinDV="99555660";
$DVRutEmisor="K";
$RznSocEmisor = "CONSORCIO TARJETAS DE CREDITO SA.";
$DirOrigen = "AV. EL BOSQUE SUR 180 PISO 3";
$CmnaOrigen = "LAS CONDES";
$CiudadOrigen = "SANTIAGO";
$WSDL = $_LINK_BASE_WS . "OpenDTEWS/services/FirmaBoleta?wsdl";		// test
//$WSDL = "http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/FirmaBoleta?wsdl";	// produccion
//$rutaExcel = "/opt/opendte/httpdocs/consorcio/Boleta2015.xls";

$rutaExcel = trim($_GET["r"]);

if($rutaExcel == ""){
      header("location:form_excel.php?sMsgJs=Error al subir excel");
      exit; 	
}

function validaBoleta($rutaExcel){
	global $RUTEmisorSinDV,$DVRutEmisor, $WSDL, $msjGlobal;
//	$rutaExcel = "/opt/opendte/httpdocs/consorcio/Boleta2015.xls";
	$XLFileType = PHPExcel_IOFactory::identify($rutaExcel);
	$objReader = PHPExcel_IOFactory::createReader($XLFileType);
	$objReader->setReadDataOnly(true);
	$objPHPExcel = $objReader->load($rutaExcel);
	//$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
	$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
	//$objWorksheet = $objPHPExcel->getActiveSheet();

	// encabezado excel
	//Cuenta;Fecha Facturación; Rut Completo ; RUT ; DV ;Nombre;Neto;IVA;Total;Dirección;Comuna;Ciudad;Periodo;Monto en palabras

	$i=0;
	$error=0;

	echo '<table width="100%" cellspacing="0" class="list">' . "\n";
	echo "<tr><th class='sort'>Resultado Revisi&oacute;n</tr>";
	$sClassRow = "evenrowbg";

	foreach ($objWorksheet->getRowIterator() as $row)
	{
	  // la primera linea de encabezado no se usa
	  if($i == 0){
		$i++;
		continue;
	  }
	  echo '<tr class=' . $sClassRow . '>' . "\n";
	  $cellIterator = $row->getCellIterator();
	  $cellIterator->setIterateOnlyExistingCells(false);

	  $j=1;
	$fechaFactura = "";
	$rutCliente = "";
	$dvCliente = "";
	$nombreCliente = "";
	$neto = "";
	$iva = "";
	$total = "";
	$dirCliente = "";
	$comunaCliente = "";
	$ciudadCliente = "";
	$montoEscrito = "";

	  foreach ($cellIterator as $cell)
	  {
		$valorCelda = trim($cell->getValue());

		if($j == 2) $fechaFactura = $valorCelda;
			if($j == 4) $rutCliente = $valorCelda;
			if($j == 5) $dvCliente = $valorCelda;
			if($j == 6) $nombreCliente = $valorCelda;
			if($j == 7) $neto = $valorCelda;
			if($j == 8) $iva = $valorCelda;
			if($j == 9) $total = $valorCelda;
			if($j == 10) $dirCliente = $valorCelda;
			if($j == 11) $comunaCliente = $valorCelda;
			if($j == 12) $ciudadCliente = $valorCelda;
			if($j == 14) $montoEscrito = $valorCelda;

	//    echo '<td>' . $cell->getValue() . '</td>' . "\n";

		if($j == 14)
			break;
		$j++;
	  }

	  $xmlBoleta=armaXML($i, $fechaFactura,$rutCliente,$dvCliente,$nombreCliente,$neto,$iva,$total,$dirCliente,$comunaCliente,$ciudadCliente,$montoEscrito);

	  if($xmlBoleta == ""){
		  echo '<td>Error en linea ' . $i . ' ' . $msjGlobal. '</td>' . "\n";
		  $error++;
	  }

	  $i++;	
  	  if($sClassRow == "oddrowbg")
		$sClassRow = "evenrowbg";
	  else
		$sClassRow = "oddrowbg"; 
	  echo '</tr>' . "\n";
	}

	if($error > 0){
		  echo '<tr><td><br><br><h2>El archivo tiene errores, no se procesa.</h2></td></tr>' . "\n";
		  exit;
	}

	echo '</table>' . "\n";


}

function generaBoleta($rutaExcel){
	global $RUTEmisorSinDV,$DVRutEmisor, $WSDL;
//	$rutaExcel = "/opt/opendte/httpdocs/consorcio/Boleta2015.xls";
	$XLFileType = PHPExcel_IOFactory::identify($rutaExcel);
	$objReader = PHPExcel_IOFactory::createReader($XLFileType);
	$objReader->setReadDataOnly(true);
	$objPHPExcel = $objReader->load($rutaExcel);
	//$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
	$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
	//$objWorksheet = $objPHPExcel->getActiveSheet();

	// encabezado excel
	//Cuenta;Fecha Facturación; Rut Completo ; RUT ; DV ;Nombre;Neto;IVA;Total;Dirección;Comuna;Ciudad;Periodo;Monto en palabras

	validaBoleta($rutaExcel);		// valida que no tenga errores.

	$i=0;

	echo '<table width="100%" cellspacing="0" class="list">' . "\n";
	echo "<tr><th class='sort'>Resultado Revisi&oacute;n</tr>";
	$sClassRow = "evenrowbg";

	foreach ($objWorksheet->getRowIterator() as $row)
	{
	  // la primera linea de encabezado no se usa
	  if($i == 0){
		$i++;
		continue;
	  }
	  echo '<tr class=' . $sClassRow . '>' . "\n";
	  $cellIterator = $row->getCellIterator();
	  $cellIterator->setIterateOnlyExistingCells(false);

	  $j=1;
	$fechaFactura = "";
	$rutCliente = "";
	$dvCliente = "";
	$nombreCliente = "";
	$neto = "";
	$iva = "";
	$total = "";
	$dirCliente = "";
	$comunaCliente = "";
	$ciudadCliente = "";
	$montoEscrito = "";

	  foreach ($cellIterator as $cell)
	  {
		$valorCelda = trim($cell->getValue());

		if($j == 2) $fechaFactura = $valorCelda;
			if($j == 4) $rutCliente = $valorCelda;
			if($j == 5) $dvCliente = $valorCelda;
			if($j == 6) $nombreCliente = $valorCelda;
			if($j == 7) $neto = $valorCelda;
			if($j == 8) $iva = $valorCelda;
			if($j == 9) $total = $valorCelda;
			if($j == 10) $dirCliente = $valorCelda;
			if($j == 11) $comunaCliente = $valorCelda;
			if($j == 12) $ciudadCliente = $valorCelda;
			if($j == 14) $montoEscrito = $valorCelda;

	//    echo '<td>' . $cell->getValue() . '</td>' . "\n";

		if($j == 14)
			break;
		$j++;
	  }

	  $xmlBoleta=armaXML($i, $fechaFactura,$rutCliente,$dvCliente,$nombreCliente,$neto,$iva,$total,$dirCliente,$comunaCliente,$ciudadCliente,$montoEscrito);

	  if($xmlBoleta == ""){
		  echo '<td>Error en linea ' . $i . ' ' . $msjGlobal. '</td>' . "\n";
	  }
	  else{
		echo '<td>';  

		  $respuesta=firmarDTE($xmlBoleta,$RUTEmisorSinDV,$DVRutEmisor, $WSDL);
		  $glosaRespuesta=$respuesta->getResulGlosa();

		  if($respuesta->getEstado() != 1){ 
			echo "Error en Linea $i ." . $glosaRespuesta; 
		  }
		  else{
			$pdf = $respuesta->getPDF();
			$pdf_cedible=str_replace("/dte-","/cedible-dte-",$pdf);
			echo "OK Linea $i <a href=$pdf target='_blank'>Descargar PDF</a>";
//			$e.="<a href=$pdf_cedible target='_blank'>Descargar Cedible PDF</a>";  
		  }
		  
		echo "</td>";
	  }

	  $i++;
  	  if($sClassRow == "oddrowbg")
		$sClassRow = "evenrowbg";
	  else
		$sClassRow = "oddrowbg"; 
	  echo '</tr>' . "\n";
	}

	echo '</table>' . "\n";
}

function armaXML($i, $fechaFactura,$rutCliente,$dvCliente,$nombreCliente,$neto,$iva,$total,$dirCliente,$comunaCliente,$ciudadCliente,$montoEscrito){
  global $msjGlobal, $RUTEmisor, $RznSocEmisor, $DirOrigen, $CmnaOrigen, $CiudadOrigen;

  //formateo y valido fecha
  $fechaFactura = str_replace("-","/",$fechaFactura);
  if(validateDate($fechaFactura,"d/m/Y") == false){
	$msjGlobal = "Error en fecha, no debe ser en blanco y debe ser formato dd/mm/yyyy"; 
	return "";
  }
  $aFecha = explode("/",$fechaFactura);
  $fechaFactura = $aFecha[2] . "-" . $aFecha[1] . "-" . $aFecha[0];

  if(valida_rut($rutCliente."-".$dvCliente) == false){
	$msjGlobal = "Error en rut"; 
	return "";
  }

  if(vacio_sm($nombreCliente) == false){
	$msjGlobal = "Error: Nombre Cliente en Blanco"; 
	return "";
  }

  if(vacio_sm($dirCliente) == false){
	$msjGlobal = "Error: Dirección Cliente en Blanco"; 
	return "";
  }

  if(vacio_sm($comunaCliente) == false){
	$msjGlobal = "Error: Comuna Cliente en Blanco"; 
	return "";
  }

  if(vacio_sm($ciudadCliente) == false){
	$msjGlobal = "Error: Ciudad Cliente en Blanco"; 
	return "";
  }

  if(isInteger($neto) == false){
	$msjGlobal = "Error en Monto Neto, debe ser un entero"; 
	return "";
  }

  if(isInteger($iva) == false){
	$msjGlobal = "Error en Monto Iva, debe ser un entero"; 
	return "";
  }

  if(isInteger($total) == false){
	$msjGlobal = "Error en Monto Total, debe ser un entero"; 
	return "";
  } 
 
 $tasaIva = 19;
 $glosa = "Comisiones Tarjeta de Crédito " . $aFecha[1] . "-" . $aFecha[2];
 $TmstFirma = date("Y-m-d") . "T" . date("H:i:s");

$xmlFull = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
<DTE version=\"1.0\">
<Documento ID=\"DTE-39-$i\">
<Encabezado>
<IdDoc>
<TipoDTE>39</TipoDTE>
<Folio>$i</Folio>
<FchEmis>$fechaFactura</FchEmis>
<IndServicio>1</IndServicio>
</IdDoc>
<Emisor>
<RUTEmisor>$RUTEmisor</RUTEmisor>
<RznSocEmisor>$RznSocEmisor</RznSocEmisor>
<DirOrigen>$DirOrigen</DirOrigen>
<CmnaOrigen>$CmnaOrigen</CmnaOrigen>
<CiudadOrigen>$CiudadOrigen</CiudadOrigen>
</Emisor>
<Receptor>
<RUTRecep>$rutCliente-$dvCliente</RUTRecep>
<RznSocRecep><![CDATA[" . substr($nombreCliente,0,40) . "]]></RznSocRecep>
<GiroRecep>PARTICULAR</GiroRecep>
<DirRecep><![CDATA[" . substr($dirCliente,0,70) . "]]></DirRecep>
<CmnaRecep><![CDATA[" . substr($comunaCliente,0,20) . "]]></CmnaRecep>
<CiudadRecep><![CDATA[" . substr($ciudadCliente,0,20) . "]]></CiudadRecep>
</Receptor>
<Totales>
<MntNeto>$neto</MntNeto>
<MntExe>0</MntExe>
<TasaIVA>$tasaIva</TasaIVA>
<IVA>$iva</IVA>
<MntTotal>$total</MntTotal>
</Totales>
</Encabezado>
<Detalle>
<NroLinDet>1</NroLinDet>
<NmbItem>$glosa</NmbItem>
<DscItem>$glosa

$montoEscrito
</DscItem>
<QtyItem>1.0</QtyItem>
<PrcItem>$total</PrcItem>
<MontoItem>$total</MontoItem>
</Detalle>
<TmstFirma>$TmstFirma</TmstFirma>
</Documento>
</DTE> ";

return $xmlFull;

}

function firmarDTE($xml, $rutEmisor, $dvEmisor, $wsdl){
$WSDLFirmaDTE=$wsdl;	// "http://10.0.0.180:9000/OpenDTEWS/services/FirmaDTE?WSDL";
$respuesta=new respuestaFirma();
try {
$soapClient=new SoapClient($WSDLFirmaDTE,array('encoding'=>'ISO-8859-1'));
$parametros=array();
$parametros["RUTEmisor"]=$rutEmisor;
$parametros["DVEmisor"]=$dvEmisor;
$parametros["tipoArchivo"]="XML";
$parametros["archivo"]=$xml;

$r=$soapClient->firmaDTE($parametros);
$xmlResult=$r;
foreach ($r as $valor){
$resXML=new SimpleXMLElement("<?xml version='1.0' encoding='ISO-8859-1'?>" . $valor);
$estado=$resXML->Estado;
$pdf=$resXML->URLpdf;
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

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function vacio_sm($valor){
	if(trim($valor) == "")
		return false;
	else
		return true;
}

function isInteger($input){
    return(ctype_digit(strval($input)));
}

function valida_rut($rut) 
{ 
if(strlen($rut) > 10) 
{ 
return false; 
} 

if(strstr($rut, '-') == false) 
{ 
return false; 
} 

$array_rut_sin_guion = explode('-',$rut); // separamos el la cadena del digito verificador. 
$rut_sin_guion = $array_rut_sin_guion[0]; // la primera cadena 
$digito_verificador = $array_rut_sin_guion[1];// el digito despues del guion. 


if(is_numeric($rut_sin_guion)== false) 
{ 
return false; 
} 
if ($digito_verificador != 'k' and $digito_verificador != 'K') 
{ 
    if(is_numeric($digito_verificador)== false)  
      { 
      return false; 
      } 
} 
$cantidad = strlen($rut_sin_guion); //8 o 7 elementos 
for ( $i = 0; $i < $cantidad; $i++)//pasamos el rut sin guion a un vector 
    { 
    $rut_array[$i] = $rut_sin_guion{$i}; 
    }   


$i = ($cantidad-1); 
$x=$i; 
for ($ib = 0; $ib < $cantidad; $ib++)// ingresamos los elementos del ventor rut_array en otro vector pero al reves. 
    { 
    $rut_reverse[$ib]= $rut_array[$i]; 
     
     $rut_reverse[$ib]; 
    $i=$i-1; 
    } 
     
$i=2; 
$ib=0; 
$acum=0;  
   do 
    { 
    if( $i > 7 ) 
      { 
      $i=2; 
      } 
      $acum = $acum + ($rut_reverse[$ib]*$i); 
     $i++; 
     $ib++; 
   }while ( $ib <= $x); 

$resto = $acum%11; 
$resultado = 11-$resto; 
if ($resultado == 11) { $resultado=0; } 
if ($resultado == 10) { $resultado='k'; } 
if ($digito_verificador == 'k' or $digito_verificador =='K') { $digito_verificador='k';} 

if ($resultado == $digito_verificador) 
    { 
    return true; 
    } 
    else 
    { 
    return false; 
    } 
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

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>OpenB</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<base href="<?php echo $_LINK_BASE; ?>" />
		<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/msg.js"></script>		
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/custom.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/layout.css">
		<link rel="stylesheet" type="text/nonsense" href="skins/<?php echo $_SKINS; ?>/css/misc.css">


<script type="text/javascript">
<!--


function _body_onload()
{
	loff();
  
 <?php 
  if($sMsgJs != "")
    echo "alert('" . $sMsgJs . "');\n";
 
 ?>   
   
	SetContext('cl_ed');
		
}

function _body_onunload()
{
	lon();
	
}

//-->
		</script>
	</head>

	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

	<div class="screenBody">
		<div class="listArea">
			<fieldset>
				<legend>Boletas</legend>
<?php 
	generaBoleta($rutaExcel);
	unlink($rutaExcel);
?>

			</fieldset>
		</div>
	</div>

	</body>

	<script type="text/javascript">
		try {
			lsetup();
		} catch (e) {
		}
	</script>
</html>


