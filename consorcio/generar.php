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

/** PhpSpreadsheet (reemplaza PHPExcel obsoleto) */
require_once dirname(__DIR__) . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if (!function_exists('h')) {
	function h($value){
		return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
	}
}

function js_sq($value){
	$value = str_replace(array("\\", "'", "\r", "\n"), array("\\\\", "\\'", "\\r", "\\n"), (string)$value);
	return "'" . $value . "'";
}

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
	$sMsgJs = isset($_GET["sMsgJs"]) ? trim((string)$_GET["sMsgJs"]) : "";

if($rutaExcel == ""){
      header("location:form_excel.php?sMsgJs=Error al subir excel");
      exit; 	
}

function validaBoleta($rutaExcel){
	global $RUTEmisorSinDV,$DVRutEmisor, $WSDL, $msjGlobal;
//	$rutaExcel = "/opt/opendte/httpdocs/consorcio/Boleta2015.xls";
	$XLFileType = IOFactory::identify($rutaExcel);
	$objReader = IOFactory::createReader($XLFileType);
	$objReader->setReadDataOnly(true);
	$objPHPExcel = $objReader->load($rutaExcel);
	//$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
	$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
	//$objWorksheet = $objPHPExcel->getActiveSheet();

	// encabezado excel
	//Cuenta;Fecha Facturaci�n; Rut Completo ; RUT ; DV ;Nombre;Neto;IVA;Total;Direcci�n;Comuna;Ciudad;Periodo;Monto en palabras

	$i=0;
	$error=0;

		echo '<div class="table-responsive mb-3">' . "\n";
		echo '<table width="100%" cellspacing="0" class="list table table-sm align-middle mb-0">' . "\n";
		echo '<thead><tr><th class="sort">Validaci&oacute;n del archivo</th></tr></thead><tbody>' . "\n";
	$sClassRow = "evenrowbg";

	foreach ($objWorksheet->getRowIterator() as $row)
	{
	  // la primera linea de encabezado no se usa
	  if($i == 0){
		$i++;
		continue;
	  }
		  echo '<tr class="' . $sClassRow . '">' . "\n";
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
			  echo '<td><span class="result-badge result-badge-danger">Error</span> L&iacute;nea ' . (int)$i . ': ' . h($msjGlobal) . '</td>' . "\n";
		  $error++;
		  }
		  else{
			  echo '<td><span class="result-badge result-badge-ok">OK</span> L&iacute;nea ' . (int)$i . ' validada correctamente.</td>' . "\n";
	  }

	  $i++;	
  	  if($sClassRow == "oddrowbg")
		$sClassRow = "evenrowbg";
	  else
		$sClassRow = "oddrowbg"; 
	  echo '</tr>' . "\n";
	}

	if($error > 0){
			  echo '<tr class="summary-row"><td><div class="alert alert-danger mb-0 py-2 px-3">El archivo tiene errores y no se procesa.</div></td></tr>' . "\n";
			  echo '</tbody></table></div>' . "\n";
			  return false;
	}

		echo '</tbody></table></div>' . "\n";

		return true;


}

function generaBoleta($rutaExcel){
		global $RUTEmisorSinDV,$DVRutEmisor, $WSDL, $msjGlobal;
//	$rutaExcel = "/opt/opendte/httpdocs/consorcio/Boleta2015.xls";
	$XLFileType = IOFactory::identify($rutaExcel);
	$objReader = IOFactory::createReader($XLFileType);
	$objReader->setReadDataOnly(true);
	$objPHPExcel = $objReader->load($rutaExcel);
	//$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
	$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
	//$objWorksheet = $objPHPExcel->getActiveSheet();

	// encabezado excel
	//Cuenta;Fecha Facturaci�n; Rut Completo ; RUT ; DV ;Nombre;Neto;IVA;Total;Direcci�n;Comuna;Ciudad;Periodo;Monto en palabras

		if(!validaBoleta($rutaExcel))
			return;		// valida que no tenga errores.

	$i=0;

		echo '<div class="table-responsive">' . "\n";
		echo '<table width="100%" cellspacing="0" class="list table table-sm align-middle mb-0">' . "\n";
		echo '<thead><tr><th class="sort">Resultado de la generaci&oacute;n</th></tr></thead><tbody>' . "\n";
	$sClassRow = "evenrowbg";

	foreach ($objWorksheet->getRowIterator() as $row)
	{
	  // la primera linea de encabezado no se usa
	  if($i == 0){
		$i++;
		continue;
	  }
		  echo '<tr class="' . $sClassRow . '">' . "\n";
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
			  echo '<td><span class="result-badge result-badge-danger">Error</span> L&iacute;nea ' . (int)$i . ': ' . h($msjGlobal) . '</td>' . "\n";
	  }
	  else{
		echo '<td>';  

		  $respuesta=firmarDTE($xmlBoleta,$RUTEmisorSinDV,$DVRutEmisor, $WSDL);
		  $glosaRespuesta=$respuesta->getResulGlosa();

		  if($respuesta->getEstado() != 1){ 
				echo '<span class="result-badge result-badge-danger">Error</span> L&iacute;nea ' . (int)$i . ': ' . h($glosaRespuesta); 
		  }
		  else{
			$pdf = $respuesta->getPDF();
			$pdf_cedible=str_replace("/dte-","/cedible-dte-",$pdf);
				echo '<span class="result-badge result-badge-ok">OK</span> L&iacute;nea ' . (int)$i . ' procesada correctamente. <a href="' . h($pdf) . '" target="_blank" class="result-link">Descargar PDF</a>';
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

		echo '</tbody></table></div>' . "\n";
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
	$msjGlobal = "Error: Direcci�n Cliente en Blanco"; 
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
 $glosa = "Comisiones Tarjeta de Cr�dito " . $aFecha[1] . "-" . $aFecha[2];
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

<!DOCTYPE html>

<html lang="es">
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>Carga de Boletas - Resultado</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<base href="<?php echo h($_LINK_BASE); ?>" />
		<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/msg.js"></script>
		<link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/general.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/custom.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/layout.css">
		<link rel="stylesheet" type="text/nonsense" href="skins/<?php echo h($_SKINS); ?>/css/misc.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
		<style>
			body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937;}
			.page-shell{max-width:1100px;margin:0 auto;padding:16px;}
			.topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:16px;}
			.topbar-title{margin:0;font-size:1.55rem;font-weight:700;color:#001f3f;}
			.topbar-meta{font-size:.85rem;color:#64748b;margin-bottom:2px;}
			.topbar-chip{display:inline-flex;align-items:center;gap:8px;padding:6px 12px;border-radius:999px;background:#e8f1ff;color:#0b5ed7;font-weight:600;font-size:.85rem;}
			.panel{background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:18px;box-shadow:0 14px 32px rgba(15,23,42,.08);overflow:hidden;}
			.panel-header{padding:16px 20px;background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;}
			.panel-header h2{margin:0;font-size:1.05rem;font-weight:700;}
			.panel-header p{margin:6px 0 0;font-size:.9rem;opacity:.9;}
			.panel-body{padding:20px;}
			.panel-note{background:#f8fbff;border:1px solid #d7e3f0;border-radius:14px;padding:12px 14px;margin-bottom:16px;color:#475569;}
			.list.table{border-color:#d7e3f0;}
			.list.table thead th,.list.table tr:first-child th{background:#001f3f;color:#fff;border-color:#17385c;font-size:.84rem;letter-spacing:.01em;}
			.list.table tbody td{vertical-align:middle;}
			.list.table tr.evenrowbg td{background:#f8fbff;}
			.list.table tr.oddrowbg td{background:#fff;}
			.result-badge{display:inline-flex;align-items:center;border-radius:999px;padding:3px 10px;font-size:.76rem;font-weight:700;margin-right:8px;}
			.result-badge-ok{background:#e8fff1;color:#157347;}
			.result-badge-danger{background:#fff1f2;color:#b42318;}
			.result-link{color:#0b5ed7;font-weight:600;text-decoration:none;}
			.result-link:hover{text-decoration:underline;}
			.summary-row td{background:#fff !important;}
			#loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050;}
			#loaderContainerWH{text-align:center;vertical-align:middle;}
			#loader{display:inline-block;background:#fff;border-radius:14px;padding:16px 20px;box-shadow:0 12px 28px rgba(15,23,42,.18);}
		</style>

		<script type="text/javascript">
		<!--

		function _body_onload()
		{
			loff();

		 <?php
		  if($sMsgJs != "")
		    echo "alert(" . js_sq($sMsgJs) . ");\n";
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
		<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" style="margin-right:10px;vertical-align:middle;"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>

		<div class="page-shell">
			<div class="topbar">
				<div>
					<div class="topbar-meta">Consorcio &gt; Carga de Boletas</div>
					<h1 class="topbar-title">Resultado de la carga</h1>
				</div>
				<div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
					<span class="topbar-chip"><i class="bi bi-file-earmark-spreadsheet"></i> Flujo activo</span>
					<a href="consorcio/form_excel.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Volver</a>
				</div>
			</div>

			<div class="panel">
				<div class="panel-header">
					<h2><i class="bi bi-check2-square me-2"></i>Validaci&oacute;n y firma de boletas</h2>
					<p>Se mantiene intacta la lectura del Excel, la validaci&oacute;n por l&iacute;nea y la firma SOAP del flujo legacy.</p>
				</div>
				<div class="panel-body">
					<div class="panel-note small">
						<strong style="color:#0f172a;">Nota:</strong> esta pantalla muestra primero la revisi&oacute;n del archivo y luego el resultado de la generaci&oacute;n de cada boleta procesada.
					</div>
	<?php
		generaBoleta($rutaExcel);
		unlink($rutaExcel);
	?>
				</div>
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


