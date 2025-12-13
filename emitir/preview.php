<?php
	include("../include/config.php");  
	include("../include/db_lib.php"); 
	require('fpdf186/fpdf.php');
	include("../include/ver_aut.php");      
    include("../include/ver_emp_adm.php");        

	$conn = conn();

	$sql = "SELECT rut_empr, dv_empr, rs_empr, dir_empr, giro_emp, com_emp FROM empresa WHERE codi_empr = '". trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";
	$result = rCursor($conn, $sql);
	if(!$result->EOF) {
		$sNomEmpr = trim($result->fields["rs_empr"]);
		$sGiroEmpr = trim($result->fields["giro_emp"]);
		$sDirEmpr = trim($result->fields["dir_empr"]);
		$sComCiudEmpr = trim($result->fields["com_emp"]);
		$sRutEmpr = trim($result->fields["rut_empr"]) . "-" . trim($result->fields["dv_empr"]);

	}

$sTipoDTE = trim($_POST["t"]);
$sFolioDTE = "###.###.###";

$descripcionT = [
    "33"  => "Factura Electrónica",
    "34"  => "Factura No Afecta o Exenta Electrónica",
    "39"  => "Boleta Electrónica",
    "41"  => "Boleta Exenta Electrónica",
    "43"  => "Liquidación Factura Electrónica",
    "46"  => "Factura de Compra Electrónica",
    "52"  => "Guía de Despacho Electrónica",
    "56"  => "Nota de Débito Electrónica",
    "61"  => "Nota de Crédito Electrónica",
    "110" => "Factura de Exportación Electrónica",
    "111" => "Nota de Débito de Exportación Electrónica",
    "112" => "Nota de Crédito de Exportación Electrónica",
	"801" => "Orden de Compra",
	"802" => "Nota de pedido",
	"803" => "Contrato",
	"804" => "Resolucion",
	"805" => "Proceso ChileCompra",
	"806" => "Ficha ChileCompra",
	"807" => "DUS",
	"808" => "B/L (Conocimiento de embarque)",
	"809" => "AWB (Air Will Bill)",
	"810" => "MIC/DTA",
	"811" => "Carta de Porte",
	"812" => "Resolucion del SNA donde califica Servicios de Exp.",
	"813" => "Pasaporte",
	"814" => "Certificado de Deposito Bolsa Prod. Chile",
	"815" => "Vale de Prenda Bolsa Prod. Chile",
	"HES" => "HES",
];

$sTipoNomDTE = $descripcionT[$sTipoDTE] ?? "";
// 
// termpagoglosa

$sNomClie = trim($_POST["razon_social"]);
$sRutClie = trim($_POST["rut_cliente"]);
$sFechDTE = trim($_POST["fecha_factura"]);
$sGiroClie = trim($_POST["giro"]);
$sDirClie = trim($_POST["direccion"]);
$sCiudClie = trim($_POST["ciudad"]);
$sComClie = trim($_POST["comuna"]);
$sTrasDTE = trim($_POST["IndTraslado"]);
$sPagoDTE = trim($_POST["fma_pago_dte"]);
$sPagoDTE = ($sPagoDTE == 1) ? "Contado" : (($sPagoDTE == 2) ? "Crédito" : "Entrega Gratuita");
$sFechVenc = trim($_POST["fecha_vencimiento"]);
$sNeto = trim($_POST["neto"]);
$sIva = trim($_POST["iva"]);
$sExento = trim($_POST["exento"]);
$sTotal = trim($_POST["total_t"]);
$termpagoglosa = trim($_POST["termpagoglosa"]);


$descripcionIndTraslado = [
    "1" => "Operacion Constituye Venta",
    "2" => "Venta por Efectuar",
    "3" => "Consignacion",
    "4" => "Promocion o Donacion (RUT Emisor = RUT Receptor)",
    "5" => "Traslado Interno",
    "6" => "Otros Traslados que no Constituyen Venta",
    "7" => "Guia de Devolucion"
];
$sTrasDTE = $descripcionIndTraslado[$sTrasDTE] ?? "";

$pdf = new FPDF();
$pdf->AddPage();

// Cargar la imagen del PDF base
$pdf->Image('preview/dte_1.jpg', 0, 0, 210, 297); // Asumiendo que el PDF es tamaño A4

// Configurar la fuente
$pdf->SetFont('Arial', 'B', 12);
//$pdf->SetTextColor(194,8,8);
$pdf->SetXY(20, 15);  
$pdf->Write(0, $sNomEmpr);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetXY(20, 25);  
//$pdf->Write(0, $sGiroEmpr);
$pdf->Multicell(110, 4, $sGiroEmpr, 0, 'L', false);
$pdf->SetXY(20, 40);  
//$pdf->Write(0, $sDirEmpr);
$pdf->Multicell(110, 4, $sDirEmpr, 0, 'L', false);
$pdf->SetXY(20, 55);  
$pdf->Write(0, $sComCiudEmpr);

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(194,8,8);
$pdf->SetXY(160, 19);  
$pdf->Write(0, $sRutEmpr);
$pdf->SetXY(130, 26);  
//$pdf->Write(0, $sTipoNomDTE);
//$pdf->Cell(90, 0,$sTipoNomDTE,0,0,'C');
$pdf->Multicell(70, 4, $sTipoNomDTE, 0, 'C', false);
$pdf->SetXY(160, 38);  
$pdf->Write(0, $sFolioDTE);


$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(43, 70);  
$pdf->Write(0, $sNomClie);
$pdf->SetXY(43, 76);  
$pdf->Write(0, $sRutClie);
$pdf->SetXY(43, 82);  
$pdf->Write(0, $sFechDTE);
$pdf->SetXY(43, 87.5);  
$pdf->Write(0, $sGiroClie);
$pdf->SetXY(43, 93);  
$pdf->Write(0, $sDirClie);
$pdf->SetXY(43, 99);  
$pdf->Write(0, $sCiudClie);
$pdf->SetXY(43, 105);  
$pdf->Write(0, $sComClie);
$pdf->SetXY(105, 105);  
$pdf->Write(0, $sTrasDTE);
$pdf->SetXY(160, 76);  
$pdf->Write(0, $sPagoDTE);
$pdf->SetXY(160, 81.5);  
$pdf->Write(0, $sFechVenc);

$pdf->SetXY(133, 87);
$pdf->Write(0, "Cond. de Venta:");
$pdf->SetXY(160, 87);
$pdf->Write(0, $termpagoglosa);


// TOTALES 
$pdf->SetXY(170, 220);  
//$pdf->Write(0, $sNeto);
$pdf->Cell(20, 0,number_format($sNeto, 0, ',', '.'),0,0,'R');
$pdf->SetXY(170, 226);  
//$pdf->Write(0, $sIva);
$pdf->Cell(20, 0,number_format($sIva, 0, ',', '.'),0,0,'R');
$pdf->SetXY(170, 232);  
//$pdf->Write(0, $sExento);
$pdf->Cell(20, 0,number_format($sExento, 0, ',', '.'),0,0,'R');
$pdf->SetXY(170, 237.5);  
//$pdf->Write(0, $sTotal);
$pdf->Cell(20, 0,number_format($sTotal, 0, ',', '.'),0,0,'R');
// FIN TOTALES 

// DETALLES 
	$pdf->SetFont('Arial', '', 9);
	$xIniDet = 132;
	for($i=0; $i < 30; $i++){
		$sTpoCod = trim($_POST["tpocodigo_$i"]);
		$sVlrCod = trim($_POST["vlrcodigo_$i"]);
		$sCodProd = trim($_POST["producto_$i"]);
		$sDescProd = trim($_POST["desc_producto_$i"]);

		if($sCodProd != ""){

			$sItem = $sCodProd . " - " . $sDescProd;

			$sCant = trim($_POST["cantidad_$i"]);
			$sPrecio = trim($_POST["valor_unit_$i"]);
			$sPrecioTotal = trim($_POST["total_$i"]);

			$sCant = (is_numeric($sCant) && ctype_digit($sCant)) ? number_format((float)$sCant, 0, ',', '.') : $sCant;
			$sPrecio = (is_numeric($sPrecio) && ctype_digit($sPrecio)) ? number_format((float)$sPrecio, 0, ',', '.') : $sPrecio;
			$sPrecioTotal = (is_numeric($sPrecioTotal) && ctype_digit($sPrecioTotal)) ? number_format((float)$sPrecioTotal, 0, ',', '.') : $sPrecioTotal;
			
			$pdf->SetXY(13, $xIniDet);  
			$pdf->Cell(20, 0,$sCant,0,0,'R');
			$pdf->SetXY(13, $xIniDet);  
			$pdf->Cell(155, 0,$sPrecio,0,0,'R');
			$pdf->SetXY(13, $xIniDet);  
			$pdf->Cell(182, 0,$sPrecioTotal,0,0,'R');
			$pdf->SetXY(40, $xIniDet-2);  
//			$pdf->Write(0, $sItem);
			$lineCount = countMultiCellLines($pdf, $sItem, 110);
			$pdf->Multicell(110, 4, $sItem, 0, 'L', false);

			$xIniDet = $xIniDet + (4 * $lineCount);	
		}
	}

// FIN DETALLES

// REFERENCIAS 
	$pdf->SetFont('Arial', '', 9);
	$xIniRef = 228;
	for($i=0; $i < 20; $i++){
		$sFolioRef = trim($_POST["folio_ref$i"]);
		$sFechFRef = trim($_POST["fecha_ref$i"]);
		$nTipoDTERef = trim($_POST["docto_ref$i"]);
		if($nTipoDTERef != ""){
			$sTipoDTERef = $descripcionT[$nTipoDTERef] ?? "";

			$sFolioRefFormatted = (is_numeric($sFolioRef) && ctype_digit($sFolioRef)) ? number_format((float)$sFolioRef, 0, ',', '.') : $sFolioRef;
			
			$pdf->SetXY(13, $xIniRef);  
			$pdf->Write(0, $sFolioRefFormatted);		
			$pdf->SetXY(55, $xIniRef);  
			$pdf->Write(0, $sFechFRef);
			$pdf->SetXY(75, $xIniRef);  
			$pdf->Write(0, $sTipoDTERef);
			$xIniRef = $xIniRef + 3.9;	
		}
	}

// FIN REFERENCIAS 


// Salvar el archivo
$pdf->Output('factura_completa.pdf', 'I'); // 'I' para mostrar en el navegador

function countMultiCellLines($pdf, $text, $width) {
    // Divide el texto en líneas si hay saltos de línea manuales
    $lines = explode("\n", $text);
    $lineCount = 0;

    foreach ($lines as $line) {
        // Divide la línea en palabras
        $words = explode(' ', $line);
        $currentLineWidth = 0;
        
        foreach ($words as $word) {
            $wordWidth = $pdf->GetStringWidth($word . ' ');

            // Si la palabra no cabe en la línea actual, cuenta la línea y empieza una nueva
            if ($currentLineWidth + $wordWidth > $width) {
                $lineCount++;
                $currentLineWidth = $wordWidth;  // Nueva línea comienza con esta palabra
            } else {
                $currentLineWidth += $wordWidth;
            }
        }

        // Contar la última línea si no está vacía
        $lineCount++;
    }

    return $lineCount;
}
?>
