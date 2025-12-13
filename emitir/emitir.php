<?php

	include("../include/config.php");  
	include("../include/db_lib.php"); 
//	include("../include/tables.php"); 

	include("../include/ver_aut.php");      
    include("../include/ver_emp_adm.php");        

	$conn = conn();

	include("inc/funciones.php");
	ini_set("default_charset", "ISO-8859-1");


	$nTipo = trim($_GET["t"]);
	$accion = trim($_POST["accion"]);

	if($nTipo == ""){
		$nTipo = trim($_POST["t"]);		
		if($nTipo == ""){
	      		header("location:../main.php");
     	      		exit;
		}
	}

	function tipoDTE($nTipo){
		if($nTipo == "33") $nTipo = "Factura Electrónica";
		if($nTipo == "34") $nTipo = "Factura No Afecta o Exenta Electrónica";
		if($nTipo == "39") $nTipo = "Boleta Electrónica";
		if($nTipo == "41") $nTipo = "Boleta Exenta Electrónica";
		if($nTipo == "43") $nTipo = "Liquidación Factura Electrónica";
		if($nTipo == "46") $nTipo = "Factura de Compra Electrónica";
		if($nTipo == "52") $nTipo = "Guía de Despacho Electrónica";
		if($nTipo == "56") $nTipo = "Nota de Débito Electrónica";
		if($nTipo == "61") $nTipo = "Nota de Crédito Electrónica";

		return $nTipo;
//		if($nTipo == "110") $nTipo = "Factura Electrónica";
//		if($nTipo == "111") $nTipo = "Factura Electrónica";
//		if($nTipo == "112") $nTipo = "Factura Electrónica";
	}


	$sql = "select valor_config from config where cod_config ='RESPETA_FOLIO_TXT_XML_ENTRADA' and codi_empr='". trim($_SESSION["_COD_EMP_USU_SESS"]) . "'";
	$result = rCursor($conn, $sql);
	if(!$result->EOF) {
		$sRepetaFolio = trim($result->fields["valor_config"]);	// Reseta entrada de folio
	}



if ($accion == "grabar"){
	$nTipo = trim($_POST["t"]);


//genera XML

	$sql="SELECT emp.rut_empr,emp.dv_empr,emp.rs_empr,emp.giro_emp,emp.cod_act,emp.dir_empr,emp.com_emp FROM empresa emp where codi_empr='".trim($_SESSION["_COD_EMP_USU_SESS"])."'";
	$result = rCursor($conn, $sql);
	if(!$result->EOF) {
		$RUTSinDV=trim($result->fields["rut_empr"]);
		$RUTDV=trim(strtoupper($result->fields["dv_empr"]));

		$RUTEmisor="$RUTSinDV-$RUTDV" ; //trim($row["rut_empr"])."-".trim(strtoupper($row["dv_empr"]));
		$RznSoc=trim($result->fields["rs_empr"]);
		$GiroEmis=trim($result->fields["giro_emp"]);
		$Acteco=trim($result->fields["cod_act"]);
		$DirOrigen=trim($result->fields["dir_empr"]);
		$CmnaOrigen=substr(trim($result->fields["com_emp"]),0,20);
		$CiudadOrigen=trim($result->fields["ciudad"]);
	}
/*
		$RUTSinDV="99999999";
		$RUTDV="9";

		$RUTEmisor="$RUTSinDV-$RUTDV";
		$RznSoc="EMPRESA DE PRUEBAS";
		$GiroEmis="GIRO DE PRUEBAS";
		$Acteco="123456";
		$DirOrigen="CALLE 123";
		$CmnaOrigen="LAS CONDES";
		$CiudadOrigen="SANTIAGO";
*/

if($sRepetaFolio != "S")
	$doc_id = "1";
else
	$doc_id = trim($_POST["folio"]);	//"1503";

$fec_emi_doc=trim($_POST["fecha_factura"]);
$FchVenc=trim($_POST["fecha_vencimiento"]);
$MntNeto=trim($_POST["neto"]);
$MntExe=trim($_POST["exento"]);
$TasaIVA="19";//trim($_POST["fecha_factura"]);
$IVA=trim($_POST["iva"]);
$MntTotal=trim($_POST["total_t"]);
$RUTRecep=trim($_POST["rut_cliente"]);
$RznSocRecep=trim($_POST["razon_social"]);
$GiroRecep=substr((trim($_POST["giro"])),0,40);
$DirRecep=trim($_POST["direccion"]);
$CmnaRecep=trim($_POST["comuna"]);
$CiudadRecep=trim($_POST["ciudad"]);
$termpagoglosa=trim($_POST["termpagoglosa"]);
$fma_pago_dte=trim($_POST["fma_pago_dte"]);
$IndTraslado =trim($_POST["IndTraslado"]);


$aFech=explode('/',$fec_emi_doc);
$fec_emi_doc = $aFech[2] . "-" . $aFech[1] . "-" . $aFech[0];

if($FchVenc != ""){
	$aFech=explode('/',$FchVenc);
	$FchVenc = $aFech[2] . "-" . $aFech[1] . "-" . $aFech[0];
}

/** GRABA CLIENTE O MODIFICA SI EXISTE **/
$aRutClie = explode("-",$RUTRecep);
$codigo_cliente =trim($_POST["codigo_cliente"]);

if($codigo_cliente == ""){
	$sql = "INSERT INTO clientes(cod_clie, codi_empr, rut_cli, dv_cli, raz_social, giro_clie, dir_clie, ciud_cli, com_clie) ";
	$sql .= "VALUES(nextval('clientes_cod_clie_seq'),  ";
	$sql .= "'" . str_replace("'","''",trim($_SESSION["_COD_EMP_USU_SESS"])) . "', ";
	$sql .= "'" . str_replace("'","''",trim($aRutClie[0])) . "', ";
	$sql .= "'" . strtoupper(str_replace("'","''",trim($aRutClie[1]))) . "', ";
	$sql .= "'" . str_replace("'","''",trim($RznSocRecep)) . "', ";
	$sql .= "'" . str_replace("'","''",trim($GiroRecep)) . "', ";
	$sql .= "'" . str_replace("'","''",trim($DirRecep)) . "', ";
	$sql .= "'" . str_replace("'","''",trim($CiudadRecep)) . "', ";
	$sql .= "'" . str_replace("'","''",trim($CmnaRecep)) . "' ";
	$sql .= ")";
	nrExecuta($conn, $sql);
}
else{
	$sql = "UPDATE clientes SET ";
	$sql .= " raz_social = '" . str_replace("'","''",trim($RznSocRecep)) . "', ";
	$sql .= " giro_clie = '" . str_replace("'","''",trim($GiroRecep)) . "', ";
	$sql .= " dir_clie = '" . str_replace("'","''",trim($DirRecep)) . "', ";
	$sql .= " ciud_cli = '" . str_replace("'","''",trim($CiudadRecep)) . "', ";
	$sql .= " com_clie = '" . str_replace("'","''",trim($CmnaRecep)) . "' ";
	$sql .= " WHERE ";	
	$sql .= "	cod_clie = '" . str_replace("'","''",trim($CmnaRecep)) . "' AND ";
	$sql .= "	codi_empr = '" . str_replace("'","''",trim($_SESSION["_COD_EMP_USU_SESS"])) . "' ";
	nrExecuta($conn, $sql);
}

/** FIN GRABA CLIENTE O MODIFICA SI EXISTE **/

$xml="<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
<DTE version=\"1.0\">
<Documento ID=\"DTE-" . $nTipo . "-".$doc_id."-$numPrefact\">
<Encabezado>
<IdDoc>
<TipoDTE>" . $nTipo. "</TipoDTE>
<Folio>".$doc_id."</Folio>
<FchEmis>".$fec_emi_doc."</FchEmis>";

if($nTipo == "52")
	$xml.="<IndTraslado>" . $IndTraslado . "</IndTraslado>";



if($nTipo == "39" || $nTipo == "41")
	$xml.="<IndServicio>3</IndServicio>";
else{
	$xml.="<FmaPago>".$fma_pago_dte."</FmaPago>
	<TermPagoGlosa>".$termpagoglosa."</TermPagoGlosa>";
}

if($FchVenc != "") $xml.="<FchVenc>".$FchVenc."</FchVenc>";

$xml.="</IdDoc>";

if($nTipo == "39" || $nTipo == "41"){
//	<GiroEmisor>$GiroEmis</GiroEmisor>

$xml.="<Emisor>
	<RUTEmisor>".$RUTEmisor."</RUTEmisor>
	<RznSocEmisor><![CDATA[$RznSoc]]></RznSocEmisor>
	<DirOrigen><![CDATA[$DirOrigen]]></DirOrigen>
	<CmnaOrigen><![CDATA[$CmnaOrigen]]></CmnaOrigen>
	<CiudadOrigen><![CDATA[$CiudadOrigen]]></CiudadOrigen>
	</Emisor>";
	$xml.="<Receptor>
	<RUTRecep>$RUTRecep</RUTRecep>
	<RznSocRecep><![CDATA[$RznSocRecep]]></RznSocRecep>	
	<DirRecep><![CDATA[$DirRecep]]></DirRecep>
	<CmnaRecep><![CDATA[$CmnaRecep]]></CmnaRecep>
	<CiudadRecep><![CDATA[$CiudadRecep]]></CiudadRecep>
	</Receptor>";
}
else{
$xml.="<Emisor>
	<RUTEmisor>".$RUTEmisor."</RUTEmisor>
	<RznSoc><![CDATA[$RznSoc]]></RznSoc>
	<GiroEmis><![CDATA[$GiroEmis]]></GiroEmis>
	<Acteco>$Acteco</Acteco>
	<DirOrigen><![CDATA[$DirOrigen]]></DirOrigen>
	<CmnaOrigen><![CDATA[$CmnaOrigen]]></CmnaOrigen>
	<CiudadOrigen><![CDATA[$CiudadOrigen]]></CiudadOrigen>
	</Emisor>";
$xml.="<Receptor>
<RUTRecep>$RUTRecep</RUTRecep>
<RznSocRecep><![CDATA[$RznSocRecep]]></RznSocRecep>
<GiroRecep><![CDATA[$GiroRecep]]></GiroRecep>
<DirRecep><![CDATA[$DirRecep]]></DirRecep>
<CmnaRecep><![CDATA[$CmnaRecep]]></CmnaRecep>
<CiudadRecep><![CDATA[$CiudadRecep]]></CiudadRecep>
</Receptor>";
}



if($nTipo == "34" || $nTipo == "41"){
	$xml .="<Totales>
	<MntExe>".round($MntExe,0)."</MntExe>
	<MntTotal>".round($MntTotal,0)."</MntTotal>
	</Totales>";
}
else{
	if($nTipo == "39"){
		$xml .="<Totales>
		<MntNeto>".round($MntNeto,0)."</MntNeto>
		<MntExe>".round($MntExe,0)."</MntExe>
		<IVA>".round($IVA,0)."</IVA>
		<MntTotal>".round($MntTotal,0)."</MntTotal>
		</Totales>";	
	}
	else{
		$xml .="<Totales>
		<MntNeto>".round($MntNeto,0)."</MntNeto>
		<MntExe>".round($MntExe,0)."</MntExe>
		<TasaIVA>".round($TasaIVA,0)."</TasaIVA>
		<IVA>".round($IVA,0)."</IVA>
		<MntTotal>".round($MntTotal,0)."</MntTotal>
		</Totales>";			
	}
}

$xml .="</Encabezado>";

for ($a=1;$a<=15;++$a){
	if ($_POST["codigo_oculto_".$a]!="" || $_POST["desc_producto_".$a]!="" || $_POST["producto_".$a]!=""){
		$TpoCodigo=trim($_POST["tpocodigo_".$a]);
		$vlrcodigo=trim($_POST["vlrcodigo_".$a]);
		$NmbItem=trim($_POST["producto_".$a]);
		$NmbItem=trim($_POST["producto_".$a]);
		$QtyItem=trim($_POST["cantidad_".$a]);
		$PrcItem=trim($_POST["valor_unit_".$a]);
		$MontoItem=trim($_POST["total_".$a]);
		$desc_item=trim($_POST["desc_producto_".$a]);
		$iva_item =trim($_POST["iva_".$a]);


		$xml .="<Detalle>
			<NroLinDet>$a</NroLinDet>";

		if($TpoCodigo != "" && $vlrcodigo != ""){
			$xml .="<CdgItem>
			<TpoCodigo><![CDATA[$TpoCodigo]]></TpoCodigo>
			<VlrCodigo><![CDATA[$vlrcodigo]]></VlrCodigo>
			</CdgItem>";
		}

		if($iva_item == "2")
			$xml .="	<IndExe>1</IndExe>";			

		if($NmbItem != "")
			$xml .="	<NmbItem><![CDATA[".substr($NmbItem,0,80)."]]></NmbItem><DscItem><![CDATA[".$desc_item."]]></DscItem>";
		else
			$xml .="	<NmbItem>.</NmbItem><DscItem><![CDATA[".$desc_item."]]></DscItem>";


		$xml .="	<QtyItem>".$QtyItem."</QtyItem>";

		if($PrcItem != "0")
			$xml .="	<PrcItem>".$PrcItem."</PrcItem>";

			$xml .="<MontoItem>".round($MontoItem,0)."</MontoItem>
			</Detalle>";
	}	
}


if($nTipo == "61" || $nTipo == "56"){
	if ($_POST["docto_ref1"]!=""){
		$fecha_ref1 = trim($_POST["fecha_ref1"]);
		$docto_ref1 = trim($_POST["docto_ref1"]);
		$folio_ref1 = trim($_POST["folio_ref1"]);
		$tipodocto_ref1 = trim($_POST["tipodocto_ref1"]);
		
		$ref1 = trim($_POST["ref1"]);

		$fecha1=explode("/",$fecha_ref1);
		$fecha1=$fecha1[2].'-'.$fecha1[1].'-'.$fecha1[0];
		$xml.="<Referencia>
		<NroLinRef>1</NroLinRef>
		<TpoDocRef>".$docto_ref1."</TpoDocRef>
		<FolioRef>".$folio_ref1."</FolioRef>
		<FchRef>".$fecha1."</FchRef>
		<CodRef>".$tipodocto_ref1."</CodRef>		
		<RazonRef><![CDATA[".$ref1."]]></RazonRef>
		</Referencia>";
	}
}
else{
	if ($_POST["docto_ref1"]!=""){
		$fecha_ref1 = trim($_POST["fecha_ref1"]);
		$docto_ref1 = trim($_POST["docto_ref1"]);
		$folio_ref1 = trim($_POST["folio_ref1"]);
		$ref1 = trim($_POST["ref1"]);

		$fecha1=explode("/",$fecha_ref1);
		$fecha1=$fecha1[2].'-'.$fecha1[1].'-'.$fecha1[0];
		$xml.="<Referencia>
		<NroLinRef>1</NroLinRef>
		<TpoDocRef>".$docto_ref1."</TpoDocRef>
		<FolioRef>".$folio_ref1."</FolioRef>
		<FchRef>".$fecha1."</FchRef>
		<RazonRef><![CDATA[".$ref1."]]></RazonRef>
		</Referencia>";
	}

	if ($_POST["docto_ref2"]!=""){
		$fecha_ref2 = trim($_POST["fecha_ref2"]);
		$docto_ref2 = trim($_POST["docto_ref2"]);
		$folio_ref2 = trim($_POST["folio_ref2"]);
		$ref2 = trim($_POST["ref2"]);

		$fecha2=explode("/",$fecha_ref2);
		$fecha2=$fecha2[2].'-'.$fecha2[1].'-'.$fecha2[0];
		$xml.="<Referencia>
		<NroLinRef>2</NroLinRef>
		<TpoDocRef>".$docto_ref2."</TpoDocRef>
		<FolioRef>".$folio_ref2."</FolioRef>
		<FchRef>".$fecha2."</FchRef>
		<RazonRef><![CDATA[".$ref2."]]></RazonRef>
		</Referencia>";
	}

	if ($_POST["docto_ref3"]!=""){
		$fecha_ref3 = trim($_POST["fecha_ref3"]);
		$docto_ref3 = trim($_POST["docto_ref3"]);
		$folio_ref3 = trim($_POST["folio_ref3"]);
		$ref3 = trim($_POST["ref3"]);

		$fecha3=explode("/",$fecha_ref3);
		$fecha3=$fecha3[2].'-'.$fecha3[1].'-'.$fecha3[0];
		$xml.="<Referencia>
		<NroLinRef>3</NroLinRef>
		<TpoDocRef>".$docto_ref3."</TpoDocRef>
		<FolioRef>".$folio_ref3."</FolioRef>
		<FchRef>".$fecha3."</FchRef>
		<RazonRef><![CDATA[".$ref3."]]></RazonRef>
		</Referencia>";
	}

	if ($_POST["docto_ref4"]!=""){
		$fecha_ref4 = trim($_POST["fecha_ref4"]);
		$docto_ref4 = trim($_POST["docto_ref4"]);
		$folio_ref4 = trim($_POST["folio_ref4"]);
		$ref4 = trim($_POST["ref4"]);

		$fecha4=explode("/",$fecha_ref4);
		$fecha4=$fecha4[2].'-'.$fecha4[1].'-'.$fecha4[0];
		$xml.="<Referencia>
		<NroLinRef>4</NroLinRef>
		<TpoDocRef>".$docto_ref4."</TpoDocRef>
		<FolioRef>".$folio_ref4."</FolioRef>
		<FchRef>".$fecha4."</FchRef>
		<RazonRef><![CDATA[".$ref4."]]></RazonRef>
		</Referencia>";
	}

	if ($_POST["docto_ref5"]!=""){
		$fecha_ref5 = trim($_POST["fecha_ref5"]);
		$docto_ref5 = trim($_POST["docto_ref5"]);
		$folio_ref5 = trim($_POST["folio_ref5"]);
		$ref5 = trim($_POST["ref5"]);

		$fecha5=explode("/",$fecha_ref5);
		$fecha5=$fecha5[2].'-'.$fecha5[1].'-'.$fecha5[0];
		$xml.="<Referencia>
		<NroLinRef>5</NroLinRef>
		<TpoDocRef>".$docto_ref5."</TpoDocRef>
		<FolioRef>".$folio_ref5."</FolioRef>
		<FchRef>".$fecha5."</FchRef>
		<RazonRef><![CDATA[".$ref5."]]></RazonRef>
		</Referencia>";
	}

	if ($_POST["docto_ref6"]!=""){
		$fecha_ref6 = trim($_POST["fecha_ref6"]);
		$docto_ref6 = trim($_POST["docto_ref6"]);
		$folio_ref6 = trim($_POST["folio_ref6"]);
		$ref6 = trim($_POST["ref6"]);

		$fecha6=explode("/",$fecha_ref6);
		$fecha6=$fecha6[2].'-'.$fecha6[1].'-'.$fecha6[0];
		$xml.="<Referencia>
		<NroLinRef>6</NroLinRef>
		<TpoDocRef>".$docto_ref6."</TpoDocRef>
		<FolioRef>".$folio_ref6."</FolioRef>
		<FchRef>".$fecha6."</FchRef>
		<RazonRef><![CDATA[".$ref6."]]></RazonRef>
		</Referencia>";
	}

	$iRNum = 7;
	for($iR = 7;$iR < 20; $iR++){
		$fecha_refiR = trim($_POST["fecha_ref$iR"]);
		$docto_refiR = trim($_POST["docto_ref$iR"]);
		$folio_refiR = trim($_POST["folio_ref$iR"]);
		$refiR = trim($_POST["ref$iR"]);

		$fechaiR=explode("/",$fecha_refiR);
		$fechaiR=$fechaiR[2].'-'.$fechaiR[1].'-'.$fechaiR[0];

		if($docto_refiR != ""){
			$xml.="<Referencia>
			<NroLinRef>$iRNum</NroLinRef>
			<TpoDocRef>".$docto_refiR."</TpoDocRef>
			<FolioRef>".$folio_refiR."</FolioRef>
			<FchRef>".$fechaiR."</FchRef>
			<RazonRef><![CDATA[".$refiR."]]></RazonRef>
			</Referencia>";		
			$iRNum++;
		}
	}

}

$xml .="<TmstFirma>" . date("Y-m-d"). "T" . date("H:i:s") . "</TmstFirma>
</Documento>
</DTE>";

//fin de XML
//echo $xml;

	$respuesta=firmarDTE($xml,$RUTSinDV,$RUTDV,$nTipo);
	$glosaRespuesta=$respuesta->getResulGlosa();

echo "<html><head>
<style type=\"text/css\">
	
.fondo{
	background-color: #FBFCFC; 
	background-image: url(\"../skins/aqua/images/main_bg.gif\"); 
	background-repeat: repeat-y;
}
</style></head>
";
echo "<body class='fondo'><br><br><h2>$glosaRespuesta</h2><br><br>";

	if($respuesta->getEstado() != 1){
//		$e="Factura Presenta problemas : ".$glosaRespuesta;	
	
		$e="<h3><a href='emitir.php?t=$nTipo'>Volver</a></h3>";
		echo $e;
		
	}
	else{
		$pdf = $respuesta->getPDF();
 		$pdf=str_replace("http://","https://",$pdf); 
		$pdf_cedible=str_replace("/dte-","/cedible-dte-",$pdf);
	 	$pdf_cedible=str_replace("http://","https://",$pdf_cedible);

		$e="<h3><a href=$pdf target='_blank'>Descargar PDF</a><br><br>";

		if($nTipo == "33" || $nTipo == "34"  || $nTipo == "52"  || $nTipo == "43"  || $nTipo == "46")
			$e.="<a href=$pdf_cedible target='_blank'>Descargar Cedible PDF</a><br><br>";

		$e.="<a href='emitir.php?t=$nTipo'>Volver</a></h3>";

		echo $e;

	}
	echo "</body></html>";
	exit;
}
else{



?>

<html><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>OpenDTE</title>

<link rel="stylesheet" type="text/css" href="inc/estilo.css" media="all"/>
<script language="JavaScript" type="text/JavaScript" src="inc/funciones.js"></script>

<!--script de cada pagina-->
<script language="javascript">
function add_cliente(){consultar('add_cliente.php',1000,500);}
function busca_cliente(){consultar('busca_cliente.php',1000,350);}
function busca_producto(id,val){consultar('busca_producto.php?id='+id+'&val='+val,1000,350);}

function pone_total(id){
var form="document.formulario1.";
total=0;
if (eval(form+"cantidad_"+id+".value")!='' && parseFloat(eval(form+"cantidad_"+id+".value"))>0 && parseFloat(eval(form+"valor_unit_"+id+".value"))>=0){
total=eval(form+"cantidad_"+id+".value")*eval(form+"valor_unit_"+id+".value");
if (trim(eval(form+"descuento_"+id+".value"))!='' && Number.isNaN(eval(form+"descuento_"+id+".value"))==false){
valtmp=parseFloat((total*eval(form+"descuento_"+id+".value"))/100);
if (valtmp<0){valtmp=(valtmp*-1);}
eval(form+"valdor_"+id+".value="+valtmp.toFixed(2));
total+=(total*eval(form+"descuento_"+id+".value"))/100;
}else{
eval(form+"valdor_"+id+".value=0");
}
eval(form+"total_"+id+".value="+parseFloat(total).toFixed(2));
}
ttotal=0;
totExento=0;
for(a=1;a<=15;a++){
if (parseFloat(eval(form+"total_"+a+".value"))!='' && parseFloat(eval(form+"total_"+a+".value"))>0)
	if(eval("document.formulario1.iva_" + a + ".options[document.formulario1.iva_" + a + ".selectedIndex].value") == "1")
		ttotal +=parseFloat(eval(form+"total_"+a+".value"));
	else
		totExento +=parseFloat(eval(form+"total_"+a+".value"));

//	ttotal+=parseFloat(eval(form+"total_"+a+".value"));
}
document.formulario1.neto.value=parseFloat(ttotal).toFixed(0);
document.formulario1.exento.value=parseFloat(totExento).toFixed(0);
document.formulario1.iva.value=(parseFloat(document.formulario1.neto.value)*0.19).toFixed(0);
document.formulario1.total_t.value=(parseFloat(document.formulario1.neto.value)+parseFloat(document.formulario1.iva.value)+parseFloat(document.formulario1.exento.value)).toFixed(0);
}

function rut(campo,msg){
var suma = 0;
var contador = 0;
var caracteres = "1234567890-kK";
var rut = campo.substring(0,8);
var drut = campo.substring(9,10);
var dvr = '0';
var mul = 2;

if ( campo.length == 0 ){
   alert(msg);
    return false;
}

if ( campo.length == 9 ){
    rut = campo.substring(0,7);
 drut = campo.substring(8,9);
}

//  verifica que el campo tenga solo caracteres numericos - k

      for (var i=0; i < campo.length; i++){
            ubicacion = campo.substring(i, i + 1);
            if (caracteres.indexOf(ubicacion) != -1)
           contador ++;
      }

    if (contador != 10 && contador != 9){
       alert(msg);
       return false;}


   for (i= rut.length -1 ; i >= 0; i--)
    {
      suma = suma + rut.charAt(i) * mul
        if (mul == 7)
          mul = 2
        else    
          mul++
    }

  res = suma % 11
  if (res==1)
    dvr = 'k'
  else if (res==0)
    dvr = '0'
  else
    {
      dvi = 11-res
      dvr = dvi + ""
    }

  if ( dvr != drut.toLowerCase() )
    { alert(msg);
  return false; }
  else
    { return true; }
}



function valida(){
var form=document.formulario1;
var form2="document.formulario1";
<?php if (date("n",time())<10){$m=date("n",time());}else{$m=date("m",time());} 
$y=date("Y",time());
$d="20";
if (($m+1)<10){$f_fin=$y."0".($m+1)."05";}else{$f_fin=$y.($m+1)."05";}
if (($m-1)<10){$f_ini=$y."0".($m-1)."20";}else{$f_ini=$y.($m-1)."20";}
?>
var f_ini="<?php echo $f_ini; ?>";
var f_fin="<?php echo $f_fin; ?>";
if (form.fecha_factura.value==""){
	alert("Seleccione fecha para la factura...");
	form.fecha_factura.focus();
return false;
}
var ff=form.fecha_factura.value.split("/")
ff=ff[2]+""+ff[1]+""+ff[0];

//if (parseInt(ff)<parseInt(f_ini) || parseInt(ff)>parseInt(f_fin)){
//alert("La fecha de emision de la factura no puede ser menor al dia 20 del mes anterior, ni superior al dia 05 del mes siguiente...");
//return false;
//}
/*
if (form.codigo_cliente.value==""){
alert("Seleccione cliente para la factura...");
return false;
} */

<?php 	if($sRepetaFolio == "S"){	?>
	
	if (numerico(form.folio.value.trim(),"",1) == false){
		form.folio.focus();
		alert("Ingrese folio Númerico del DTE...");
		return false;
	}
<?php }	?>

if ((form.rut_cliente.value.trim()=="")){
	form.rut_cliente.focus();
	alert("Ingrese RUT de cliente para el DTE...");
	return false;
}
  
if(rut(form.rut_cliente.value.trim(),"Ingrese RUT Valido de cliente para el DTE...") == false){  
	form.rut_cliente.focus();
	return false;  
}

if ((form.razon_social.value.trim()=="")){
	form.razon_social.focus();
	alert("Ingrese nombre de cliente para la factura...");
	return false;
}
if ((form.giro.value.trim()=="")){
	form.giro.focus();
	alert("Ingrese giro de cliente para la factura...");
	return false;
}
if ((form.direccion.value.trim()=="")){
	form.direccion.focus();
	alert("Ingrese direccion de cliente para la factura...");
	return false;
}
if ((form.comuna.value.trim()=="")){
	form.comuna.focus();
	alert("Ingrese comuna de cliente para la factura...");
	return false;
}

err=0;
conta=0;
for (a=1;a<=15;a++){
if (eval("document.formulario1.codigo_oculto_"+a+".value")!="" || eval("document.formulario1.producto_"+a+".value")!=""  || eval("document.formulario1.desc_producto_"+a+".value")!=""){
conta++;
}
if ((eval("document.formulario1.codigo_oculto_"+a+".value")!="" || eval("document.formulario1.producto_"+a+".value")!=""  || eval("document.formulario1.desc_producto_"+a+".value")!="") && (parseFloat(eval("document.formulario1.cantidad_"+a+".value"))>0 && parseFloat(eval("document.formulario1.valor_unit_"+a+".value"))>=0)){
err++;
}
}

if (conta==0){
	alert("Debe incluir a lo menos un item...");
	return false;
}

if (conta>err){
alert("Recuerde indicar cantidades y precio para todos los items incluidos en la factura...");
return false;
}


for (a=1;a<=15;a++){
	if ((eval("document.formulario1.tpocodigo_"+a+".value") != "" && eval("document.formulario1.vlrcodigo_"+a+".value") == "" ) || (eval("document.formulario1.tpocodigo_"+a+".value") == "" && eval("document.formulario1.vlrcodigo_"+a+".value") != "" )){
		alert("Linea " + a + " debe incluir el tipo y codigo del producto o bien ambos deben estar sin valor");
		break;
	}
}



if (form.docto_ref1.value!='' && (form.fecha_ref1.value=='' || form.folio_ref1.value=='' || form.ref1.value=='')){
alert("debe completar los datos de la referencia 1...");
return false;
}


<?php if($nTipo == "61" || $nTipo == "56"){	?>

	if(form.docto_ref1.options[form.docto_ref1.selectedIndex].value == ""){
		alert("Debe Seleccionar el tipo de Documento de Referencia...");
		form.docto_ref1.focus();
		return false;		
	}

	if(form.tipodocto_ref1.options[form.tipodocto_ref1.selectedIndex].value == ""){
		alert("Debe Seleccionar el motivo del Documento...");
		form.tipodocto_ref1.focus();
		return false;		
	}

	if(form.tipodocto_ref1.options[form.tipodocto_ref1.selectedIndex].value == "2" && parseFloat(eval("form.total_t.value")) > 0){
		alert("Los Documentos de Corrección de Texto, administrativas, debe ser Emitida por un Monto Total de 0...");
		form.tipodocto_ref1.focus();
		return false;		
	}


<?php } else { ?>
	if (form.docto_ref2.value!='' && (form.fecha_ref2.value=='' || form.folio_ref2.value=='' || form.ref2.value=='')){
		alert("debe completar los datos de la referencia 2...");
		return false;
		}
		if (form.docto_ref3.value!='' && (form.fecha_ref3.value=='' || form.folio_ref3.value=='' || form.ref3.value=='')){
		alert("debe completar los datos de la referencia 3...");
		return false;
		}
		if (form.docto_ref4.value!='' && (form.fecha_ref4.value=='' || form.folio_ref4.value=='' || form.ref4.value=='')){
			alert("debe completar los datos de la referencia 4...");
			return false;
		}
		if (form.docto_ref5.value!='' && (form.fecha_ref5.value=='' || form.folio_ref5.value=='' || form.ref5.value=='')){
			alert("debe completar los datos de la referencia 5...");
			return false;
		}
		if (form.docto_ref6.value!='' && (form.fecha_ref6.value=='' || form.folio_ref6.value=='' || form.ref6.value=='')){
			alert("debe completar los datos de la referencia 6...");
			return false;
		}

		for(iR = 7;iR < 20; iR++){   
			if (eval("document.formulario1.docto_ref" + iR + ".value")!='' && (eval("document.formulario1.fecha_ref" + iR + ".value")=='' || eval("document.formulario1.folio_ref" + iR + ".value")=='' || eval("document.formulario1.ref" + iR + ".value")=='')){
				alert("debe completar los datos de la referencia " + iR + "...");
				return false;
			}
		}
		
<?php } ?>



return true;
}


function grabar(){
var form=document.formulario1;
if (valida()==true){
a=confirm("Esta seguro que desea generar el DTE con los datos de pantalla...?");
if (a==true){	
	form.accion.value="grabar";
        form.action ="emitir.php";
        form.target = "_self"; 
	form.submit();
}else{
return false;	
}
}
}

function preview(){
	var form=document.formulario1;
	form.action ="preview.php";
	form.target = "_blank";
	form.submit();
}


function quita_item(id){
var form="document.formulario1.";
if (trim(eval(form+"producto_"+id+".value"))==""){
eval(form+"codigo_oculto_"+id+".value=''");
eval(form+"valor_unit_"+id+".value=''");
//eval(form+"tpocodigo_"+id+".value=''");
//eval(form+"vlrcodigo_"+id+".value=''");
eval(form+"cantidad_"+id+".value=''");
eval(form+"descuento_"+id+".value=''");
eval(form+"valdor_"+id+".value=''");
eval(form+"total_"+id+".value=''");
}
}
function buscar(p1,p2){
var form=document.formulario1;
form.accion.value='buscar';
form.codigo.value=p1;
form.codigo2.value=p2;
form.submit();
}


function habdesitem(i){
  var nCodItem = document.getElementById('producto_' + String(i)).value.trim();
  var bEst = false;
  if(nCodItem == ""){
	bEst = true;

	document.getElementById('tpocodigo_' + String(i)).value = "";
	document.getElementById('vlrcodigo_' + String(i)).value = "";
	document.getElementById('desc_producto_' + String(i)).value = "";
	document.getElementById('cantidad_' + String(i)).value = "";
	document.getElementById('valor_unit_' + String(i)).value = "";
	document.getElementById('total_' + String(i)).value = "";

	pone_total(i);
  }
   
  document.getElementById('tpocodigo_' + String(i)).disabled = bEst;
  document.getElementById('vlrcodigo_' + String(i)).disabled = bEst;
  document.getElementById('desc_producto_' + String(i)).disabled = bEst;
  document.getElementById('cantidad_' + String(i)).disabled = bEst;
  document.getElementById('valor_unit_' + String(i)).disabled = bEst;
  document.getElementById('iva_' + String(i)).disabled = bEst;
  document.getElementById('total_' + String(i)).disabled = bEst;
}


function traeCliente(){
	var rutClie = document.getElementById('rut_cliente').value.trim();
	var rutClieOld = document.getElementById('rut_cliente_old').value.trim();

	if(rutClie != rutClieOld && rutClieOld != ""){
		document.getElementById('codigo_cliente').value = "";
		document.getElementById('razon_social').value = "";
		document.getElementById('giro').value = "";
		document.getElementById('direccion').value = "";
		document.getElementById('ciudad').value = "";
		document.getElementById('comuna').value = "";
	}

	if(rutClie != "" && rutClie != rutClieOld){
		if (rut(rutClie,"Formato de RUT Incorrecto, ingresar sin punto y con guión") == false){
	//		alert("Formato de RUT Incorrecto, ingresar sin punto y con guión.");
			document.getElementById('rut_cliente').value = "";
		}
		else{
			// Datos que quieres enviar al servicio web
			const data = {
				rut_cliente: rutClie
			};

			// Realizar la solicitud POST utilizando fetch
			fetch('<?php echo $_LINK_BASE; ?>emitir/ws_clie.php', {
				method: 'POST', // Método HTTP
				headers: {
					'Content-Type': 'application/json' // Indica que se envían datos JSON
				},
				body: JSON.stringify(data) // Convierte el objeto de datos a JSON
			})
			.then(response => response.json()) // Procesar la respuesta como JSON
			.then(data => {
				// Aquí accedes a los datos del JSON
				if (data.status === 'error') {
		//            console.error('Error:', data.message);
					if(rutClie != rutClieOld && rutClieOld != ""){
						document.getElementById('codigo_cliente').value = "";
						document.getElementById('razon_social').value = "";
						document.getElementById('giro').value = "";
						document.getElementById('direccion').value = "";
						document.getElementById('ciudad').value = "";
						document.getElementById('comuna').value = "";
					}
				} else {
		  //          console.log('Éxito:', data);
					  document.getElementById('codigo_cliente').value = data.data.cod_clie;
					  document.getElementById('razon_social').value = data.data.raz_social;
					  document.getElementById('giro').value = data.data.giro_clie;
					  document.getElementById('direccion').value = data.data.dir_clie;
					  document.getElementById('ciudad').value = data.data.ciud_cli;
					  document.getElementById('comuna').value = data.data.com_clie;
					  
				}
			})
			.catch((error) => {
		//		console.error('Error:', error);
				if(rutClie != rutClieOld && rutClieOld != ""){
					document.getElementById('codigo_cliente').value = "";
					document.getElementById('razon_social').value = "";
					document.getElementById('giro').value = "";
					document.getElementById('direccion').value = "";
					document.getElementById('ciudad').value = "";
					document.getElementById('comuna').value = "";
				}
			});

			document.getElementById('razon_social').disabled = false;
			document.getElementById('giro').disabled = false;
			document.getElementById('direccion').disabled = false;
			document.getElementById('ciudad').disabled = false;
			document.getElementById('comuna').disabled = false;
		}
	}
	else{
		if(rutClie == ""){
			document.getElementById('rut_cliente_old').value = "";
			document.getElementById('razon_social').disabled = true;
			document.getElementById('giro').disabled = true;
			document.getElementById('direccion').disabled = true;
			document.getElementById('ciudad').disabled = true;
			document.getElementById('comuna').disabled = true;		
		}
	}

}


</script>
<!---fin de script de cada pagina-->

<style type="text/css">
	
.fondo{
	background-color: #FBFCFC; 
	background-image: url("../skins/aqua/images/main_bg.gif"); 
	background-repeat: repeat-y;
}
</style>

<?php //require('dos_up.php');?>
<!--inicio cuerpo pagina-->

<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0">
<form name="formulario1" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="accion" value="" readonly>
<input type="hidden" name="t" value="<?php echo $nTipo; ?>" readonly>


<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
<tr height="100%"><td>

<!---cuerpo total con menu -->

<input type="hidden" name="codigo" value="" readonly>
<input type="hidden" name="codigo2" value="" readonly>
<?php 
if ($e!=''){
echo "<table width='90%' align='center' cellspacing='3'>
<tr>
<td colspan='2' class='campo_cal' align='center'>".$e."&nbsp;&nbsp;&nbsp;<a href=\"Javascript:principal('emision_factura_afecta.php','etr1');\">Crear Nueva factura</a></td>
</tr>
</table>";
exit();
}
?>

<table width="96%" align="center" cellspacing="3">
<tr>
<td align="left" class="ttitulo"><b><?php echo tipoDTE($nTipo); ?></b></td>
<tr>
</table>

<table width="96%" align="center" cellspacing="5">

<?php 	if($sRepetaFolio == "S"){	?>
<tr>
<td class='cab_campo' width="15%">&nbsp;Folio</td>
<td class="texto" width="85%">&nbsp;<input type="text" name="folio" class="campo" size="12" maxlength="18" value="">&nbsp;<label class="nota">*</label></td>
</tr>
<?php 	}	?>

<tr>
<td class='cab_campo' width="15%">&nbsp;Fecha Emision&nbsp;</td>
<td class="texto" width="85%">&nbsp;<input type="text" name="fecha_factura" class="campo" size="12" maxlength="10" value="" readonly>&nbsp;<img vspace="0" src="img/calendario.gif" border="0" onClick="Javascript:consultar('calendario.php?codigo=fecha_factura',300,176);" style="cursor:hand;"  alt="[Cargar fecha desde calendario]">&nbsp;<label class="nota">*</label></td>
</tr>

<tr>
<td class='cab_campo' width="15%">&nbsp;Fecha Vencimiento&nbsp;</td>
<td class="texto" width="85%">&nbsp;<input type="text" name="fecha_vencimiento" class="campo" size="12" maxlength="10" value="<?php echo date('d',time())."/".date('m',time())."/".date('Y',time()); ?>" readonly>&nbsp;<img vspace="0" src="img/calendario.gif" border="0" onClick="Javascript:consultar('calendario.php?codigo=fecha_vencimiento',300,176);" style="cursor:hand;"  alt="[Cargar fecha desde calendario]">&nbsp;<label class="nota">*</label></td>
</tr>
<tr>
<td class='cab_campo' width="15%">&nbsp;Forma de Pago&nbsp;</td>
<td class="texto" width="85%">&nbsp;
<select class="campo" name="fma_pago_dte">
<option value="2">Cr&eacute;dito</option>
<option value="1">Contado</option>
<option value="3">Entregas Gratuitas</option>
</select>
</td>
</tr>
<?php if($nTipo == "52") { ?>
<tr>
<td class='cab_campo' width="15%">&nbsp;Indicador de Traslado&nbsp;</td>
<td class="texto" width="85%">&nbsp;
<select class="campo" name="IndTraslado">
<option value="1">Operacion Constituye Venta</option>
<option value="2">Venta por Efectuar</option>
<option value="3">Consignacion</option>
<option value="4">Promocion  o Donacion (RUT Emisor = RUT Receptor)</option>
<option value="5">Traslado Interno</option>
<option value="6">Otros Traslados que no Constituyen Venta</option>
<option value="7">Guia de Devolucion</option>
</select>
</td>
</tr>
<?php } ?>

<tr>
<td class='cab_campo' width="15%">&nbsp;Condicion de venta&nbsp;</td>
<td class="texto" width="85%">&nbsp;<input type="text" name="termpagoglosa" class="campo" size="50" maxlength="100" value="" placeholder="Describa glosa de condiciones de venta ej: pago en 45 dias"></td>
</tr>
<!--inicio datos cliente-->
<tr>
<td colspan="2">
<table cellpadding="0" cellspacing="0" width="100%">
<tr><td class="cab_campo" height="26" align="left">&nbsp;<b>Datos del Cliente</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
</table>
</td>
</tr>
<tr>
<td class='cab_campo'>&nbsp;Rut Cliente (ej: 123456789-0)&nbsp;</td>
<td class="texto">&nbsp;<input type="text" id="rut_cliente" name="rut_cliente" class="campo" size="12" maxlength="10" value="<?php echo $rut_cliente; ?>" onblur="traeCliente();" onfocus="document.getElementById('rut_cliente_old').value=this.value;">
						<input type="hidden" id="rut_cliente_old" name="rut_cliente_old" size="10" value="">
</td>
</tr>
<tr>
<td class='cab_campo' height="20">&nbsp;Razon Social&nbsp;<input type="hidden" id="codigo_cliente" name="codigo_cliente" size="10" value="<?php echo $codigo_cliente; ?>"></td>
<td class="texto">&nbsp;<input type="text" id="razon_social" name="razon_social" value="<?php echo $razon_social; ?>" class="campo" size="50" disabled></td>
</tr>
<tr>
<td class='cab_campo' height="20">&nbsp;Giro&nbsp;</td>
<td class="texto">&nbsp;<input type="text" id="giro" name="giro" value="<?php echo $giro; ?>" class="campo" size="50" disabled></td>
</tr>
<tr>
<td class='cab_campo' height="20">&nbsp;Direccion - Comuna - Ciudad</td>
<td class="texto">&nbsp;<input type="text" id="direccion" name="direccion" value="<?php echo $direccion; ?>" class="campo" size="50" disabled>
<input type="texto" id="comuna" name="comuna" value="<?php echo $comuna; ?>" disabled>
<input type="texto" id="ciudad" name="ciudad" value="<?php echo $ciudad; ?>" disabled>
</td>
</tr>
<!--fin de datos del cliente-->




</table>

<!-- <div style="max-height: 600px; overflow-y: auto; width: 100%;"> -->

<table width="96%" align="center" cellspacing="5">
<tr>
<td class='cab_campo' height="20" align='left' colspan='7'><b>Factura Afecta - Datos de Items</b></td>
</tr>
<tr>
<td width="5%" align='center' class='cab_campo' valign="middle"><b>#</b></td>
<td width="15%" align='center' class='cab_campo' valign="middle"><b>Tipo - Código</b></td>
<td width="20%" align='center' class='cab_campo' valign="middle"><b>Item - Descripcion</b></td>
<td width="10%" align='center' class='cab_campo' valign="middle"><b>Cantidad</b></td>
<td width="10%" align='center' class='cab_campo' valign="middle"><b>Valor Unit.</b></td>
<!--<td width="10%" align='center' class='cab_campo' valign="middle"><b>% <br>Descuento<br>Recargo</b></td>
<td width="10%" align='center' class='cab_campo' valign="middle"><b>Valor<br>Descuento<br>Recargo</b></td> -->
<td width="10%" align='center' class='cab_campo' valign="middle"><b>IVA<br></td>
<td width="10%" align='center' class='cab_campo' valign="middle"><b>Total</b></td>
</tr>
<!--Rep x 10-->
<?php 
for ($a=1;$a<=15;++$a){ 
eval("unset(\$producto_".$a.");");
eval("unset(\$tpocodigo_".$a.");");
eval("unset(\$vlrcodigo_".$a.");");
eval("unset(\$codigo_oculto_".$a.");");
eval("unset(\$codigo_oculto2_".$a.");");
eval("unset(\$desc_producto_".$a.");");
eval("unset(\$cantidad_".$a.");");
eval("unset(\$valor_unit_".$a.");");
eval("unset(\$total_".$a.");");
eval("unset(\$descuento_".$a.");");
eval("unset(\$valdor_".$a.");");
eval("unset(\$total_".$a.");");
}

if ($accion=='buscar'){
$sqlx="select num_lin_ddoc,codi_empr,tipo_docu,correl_doc,ind_exen_det,
nom_item_det,cant_ref_det,prec_ref_det,cant_item_det,prec_item_det,prec_mon_det,
cod_mone_det,dcto_item_det,prod_correl,reca_item_det,desc_porc_det,codi_item,reca_porc_det,desc_item_det from detalle_doc where codi_empr='".$_SESSION["DEFAULT_EMP_CODI"]."' and tipo_docu='33' and correl_doc='".$codigo."' order by num_lin_ddoc asc";
$resultx=pg_query($cn,$sqlx);
$contadorx=1;
while ($rowx=pg_fetch_array($resultx)){
eval("\$producto_".$contadorx."='".$rowx["nom_item_det"]."';");
eval("\$codigo_oculto_".$contadorx."='".$rowx["prod_correl"]."';");
eval("\$codigo_oculto2_".$contadorx."='".$rowx["codi_item"]."';");
eval("\$desc_producto_".$contadorx."='".$rowx["desc_item_desc"]."';");
eval("\$cantidad_".$contadorx."='".round($rowx["cant_ref_det"],0)."';");
eval("\$valor_unit_".$contadorx."='".round($rowx["prec_ref_det"],0)."';");
eval("\$total_".$contadorx."='".round($rowx["prec_mon_det"],0)."';");
if ($rowx["desc_porc_det"]<0){ 
eval("\$descuento_".$contadorx."='".(round($rowx["desc_porc_det"]*-1))."';");
}else{
eval("\$descuento_".$contadorx."='".(round($rowx["desc_porc_det"]))."';");
}
eval("\$valdor_".$contadorx."='".$rowx["dcto_item_det"]."';");
++$contadorx;
}
}

for ($a=1;$a<=15;++$a){ 
if ($a%2==0)$bgcolor="#ffffff"; else $bgcolor='#f3f3f3'; 
//add DscItem ?>
<tr bgcolor="<?php echo $bgcolor; ?>">
<td class='texto' align='right'><?php echo $a; ?>&nbsp;</td>
<td class='texto' align='center'>
	<input type='text' id='tpocodigo_<?php echo $a;?>' name='tpocodigo_<?php echo $a;?>' value='' class='campo' size='7' maxlength='10' disabled>-<input type='text' id='vlrcodigo_<?php echo $a;?>' name='vlrcodigo_<?php echo $a;?>' value='' class='campo' size='10' maxlength='35' disabled></td>

<td class='texto' align='left' nowrap="nowrap">
	<input type='text' id='producto_<?php echo $a;?>' name='producto_<?php echo $a;?>' placeholder='C&oacute;digo del item <?php echo $a; ?>' onkeydown="Javascript:quita_item('<?php echo $a;?>');" onkeyup="Javascript:quita_item('<?php echo $a;?>');" value='<?php eval("echo \$producto_".$a.";");?>' class='campo' size='40' maxlength='35' onblur="habdesitem(<?php echo $a;?>);">&nbsp;
	
	<input type='hidden' name='codigo_oculto_<?php echo $a;?>' value='<?php eval("echo \$codigo_oculto_".$a.";");?>' readonly>
	
	<br><div style="font-size:2px">&nbsp;</div>
	
	<textarea id='desc_producto_<?php echo $a;?>' name='desc_producto_<?php echo $a;?>' value='' class='campo' rows="3" cols="40" maxlength='500' placeholder='Descripcion opcional <?php echo $a; ?>' disabled></textarea>
	<input type='hidden' name='codigo_oculto2_<?php echo $a;?>' value='<?php eval("echo \$codigo_oculto2_".$a.";");?>' readonly> 
</td>

<td class='texto' align='center'>
	<input type='text' id='cantidad_<?php echo $a;?>' name='cantidad_<?php echo $a;?>' onblur="Javascript:pone_total('<?php echo $a;?>');" value='<?php eval("echo \$cantidad_".$a.";");?>' class='campo' size='13' maxlength='11' disabled>
</td>

<td class='texto' align='center'>
	<input type='text' id='valor_unit_<?php echo $a;?>' name='valor_unit_<?php echo $a;?>' onblur="Javascript:pone_total('<?php echo $a;?>');" value='<?php eval("echo \$valor_unit_".$a.";");?>' class='campo' size='13' maxlength='11' disabled></td>

<!-- <td class='texto' align='center'><input type='text' id='descuento_<?php echo $a;?>' name='descuento_<?php echo $a;?>' value='<?php eval("echo \$descuento_".$a.";");?>' class='campo' size='13' maxlength='15' onblur="Javascript:pone_total('<?php echo $a;?>');"></td>
<td class='texto' align='center'><input type='text' id='valdor_<?php echo $a;?>' name='valdor_<?php echo $a;?>' value='<?php eval("echo \$valdor_".$a.";");?>'  class='campo' size='13' maxlength='15' readonly></td>
-->
<input type='hidden' id='descuento_<?php echo $a;?>' name='descuento_<?php echo $a;?>' value='<?php eval("echo \$descuento_".$a.";");?>' class='campo' size='13' maxlength='15' onblur="Javascript:pone_total('<?php echo $a;?>');">
<input type='hidden' id='valdor_<?php echo $a;?>' name='valdor_<?php echo $a;?>' value='<?php eval("echo \$valdor_".$a.";");?>'  class='campo' size='13' maxlength='15' readonly>


<td class='texto' align='center'>
<select id='iva_<?php echo $a;?>' name='iva_<?php echo $a;?>' onchange="javascript:pone_total('<?php echo $a;?>');" disabled>
<?php if($nTipo == "34" || $nTipo == "41"){	?>
	<option value="2">EXENTO DE IVA</option>
<?php } else{ ?>
	<option value="1" selected>AFECTO A IVA</option>
	<option value="2">EXENTO DE IVA</option>
<?php } ?>
</select>

</td>

<td class='texto' align='center'><input type='text' id='total_<?php echo $a;?>' name='total_<?php echo $a;?>' dir="rtl" value='' class='campo' size='18' maxlength='15' disabled></td>
</tr>
<?php } ?>
</table>
<!-- </div> -->
<table width="96%" align="center" cellspacing="5">
<tr height="40px">
<td align="right" class="ttitulo">Neto&nbsp;</td>
<td align="left"><input type='text' name='neto' value='0' dir="rtl" class='campo' size='18'  maxlength='15' readonly></td>
<td>&nbsp;</td>
<td align="right" class="ttitulo">Exento&nbsp;</td>
<td align="left"><input type='text' name='exento' value='0' dir="rtl" class='campo' size='18'  maxlength='15' readonly></td>
<td>&nbsp;</td>
<td align="right" class="ttitulo">I.V.A.&nbsp;</td>
<td align="left"><input type='text' name='iva' value='0' class='campo' dir="rtl" size='18'  maxlength='15' readonly></td>
<td>&nbsp;</td>
<td align="right" class="ttitulo">Total&nbsp;</td>
<td align="left"><input type='text' name='total_t' value='0'  class='campo' dir="rtl" size='18' maxlength='15' readonly></td>
</tr>
</table>

<div style="width: 100%;border-collapse: collapse;height: 200px;overflow-y: auto;">

<table width="96%" align="center" cellspacing="5">
<tr><td height="20" colspan="6" class="cab_campo">Referencia</td></tr>
<tr>
<td height="20" class="cab_campo" align="center">Nº.</td>
<td height="20" class="cab_campo" align="center">Tipo Docto.</td>

<?php if($nTipo == "61" || $nTipo == "56"){	?>
	<td height="20" class="cab_campo" align="center">Motivo.</td>
<?php } ?>

<td height="20" class="cab_campo" align="center">Fecha</td>
<td height="20" class="cab_campo" align="center">Folio</td>
<td height="20" class="cab_campo" align="center">Razon Referencia</td>
</tr>

<?php if($nTipo == "61" || $nTipo == "56"){	?>
<tr> <td>1</td>
<td valign="top" align="center"><select class="campo" name="docto_ref1"><option value="">::Seleccione::</option>


<?php if($nTipo == "61"){	?>
	<option value="33">Factura Afecta Electrónica</option>
	<option value="34">Factura Exenta Electrónica</option>
	<option value="39">Boleta Afecta Electrónica</option>
	<option value="41">Boleta Exenta Electrónica</option>
	<option value="56">Nota de Débito Electrónica</option>
<?php } ?>
<?php if($nTipo == "56"){	?>
	<option value="33">Factura Afecta Electrónica</option>
	<option value="34">Factura Exenta Electrónica</option>
	<option value="39">Boleta Afecta Electrónica</option>
	<option value="41">Boleta Exenta Electrónica</option>
	<option value="61">Nota Crédito Electrónica</option>
<?php } ?>

</select></td>

<td valign="top" align="center"><select class="campo" name="tipodocto_ref1">
	<option value="1">Anula Documento de Referencia</option>
	<option value="2">Corrigue Texto</option>
	<option value="3">Corrigue Montos</option>
</select></td>



<td valign="top" align="center"><input type="text" name="fecha_ref1" class="campo" size="12" maxlength="10" value=""  readonly>&nbsp;<img vspace="0" src="img/calendario.gif" border="0" onClick="Javascript:consultar('calendario.php?codigo=fecha_ref1',300,176);" style="cursor:hand;"  alt="[Cargar fecha desde calendario]"></td>
<td valign="top" align="center"><input type="text" name="folio_ref1" value="" class="campo" size="35" maxlength="18"></td>
<td valign="top" align="center"><input type="text" name="ref1" value="" class="campo" size="35" maxlength="30"></td>
</tr>
<?php }
else{
?>

<tr><td>1</td>
<td valign="top" align="center"><select class="campo" name="docto_ref1"><option value="">::Seleccione::</option>

<?php if($nTipo != "52"){	?>
<option value="52">Guia de Despacho Electronica</option>
<?php } ?>
<option value="801">Orden de Compra</option>
<option value="802">Nota de pedido</option>
<option value="803">Contrato</option>
<option value="804">Resolucion</option>
<option value="805">Proceso ChileCompra</option>
<option value="806">Ficha ChileCompra</option>
<option value="807">DUS</option>
<option value="808">B/L (Conocimiento de embarque)</option>
<option value="809">AWB (Air Will Bill)</option>
<option value="810">MIC/DTA</option>
<option value="811">Carta de Porte</option>
<option value="812">Resolucion del SNA donde califica Servicios de Exp.</option>
<option value="813">Pasaporte</option>
<option value="814">Certificado de Deposito Bolsa Prod. Chile</option>
<option value="815">Vale de Prenda Bolsa Prod. Chile</option>
<option value="HES">HES</option>
<option value="HEM">HEM</option>
<option value="WE">WE</option>
<option value="OV">OV</option>
</select></td>


<td valign="top" align="center"><input type="text" name="fecha_ref1" class="campo" size="12" maxlength="10" value=""  readonly>&nbsp;<img vspace="0" src="img/calendario.gif" border="0" onClick="Javascript:consultar('calendario.php?codigo=fecha_ref1',300,176);" style="cursor:hand;"  alt="[Cargar fecha desde calendario]"></td>
<td valign="top" align="center"><input type="text" name="folio_ref1" value="" class="campo" size="35" maxlength="18"></td>
<td valign="top" align="center"><input type="text" name="ref1" value="" class="campo" size="35" maxlength="30"></td>
</tr>

<tr><td>2</td>
<td valign="top" align="center"><select class="campo" name="docto_ref2"><option value="">::Seleccione::</option>

<?php if($nTipo != "52"){	?>
<option value="52">Guia de Despacho Electronica</option>
<?php } ?>
<option value="801">Orden de Compra</option>
<option value="802">Nota de pedido</option>
<option value="803">Contrato</option>
<option value="804">Resolucion</option>
<option value="805">Proceso ChileCompra</option>
<option value="806">Ficha ChileCompra</option>
<option value="807">DUS</option>
<option value="808">B/L (Conocimiento de embarque)</option>
<option value="809">AWB (Air Will Bill)</option>
<option value="810">MIC/DTA</option>
<option value="811">Carta de Porte</option>
<option value="812">Resolucion del SNA donde califica Servicios de Exp.</option>
<option value="813">Pasaporte</option>
<option value="814">Certificado de Deposito Bolsa Prod. Chile</option>
<option value="815">Vale de Prenda Bolsa Prod. Chile</option>
<option value="HES">HES</option>
<option value="HEM">HEM</option>
<option value="WE">WE</option>
<option value="OV">OV</option>
</select></td>
<td valign="top" align="center"><input type="text" name="fecha_ref2" class="campo" size="12" maxlength="10" value="" readonly>&nbsp;<img vspace="0" src="img/calendario.gif" border="0" onClick="Javascript:consultar('calendario.php?codigo=fecha_ref2',300,176);" style="cursor:hand;"  alt="[Cargar fecha desde calendario]"></td>
<td valign="top" align="center"><input type="text" name="folio_ref2" value="" class="campo" size="35" maxlength="18"></td>
<td valign="top" align="center"><input type="text" name="ref2" value="" class="campo" size="35" maxlength="30"></td>
</tr>

<tr><td>3</td>
<td valign="top" align="center"><select class="campo" name="docto_ref3"><option value="">::Seleccione::</option>
<?php if($nTipo != "52"){	?>
<option value="52">Guia de Despacho Electronica</option>
<?php } ?>
<option value="801">Orden de Compra</option>
<option value="802">Nota de pedido</option>
<option value="803">Contrato</option>
<option value="804">Resolucion</option>
<option value="805">Proceso ChileCompra</option>
<option value="806">Ficha ChileCompra</option>
<option value="807">DUS</option>
<option value="808">B/L (Conocimiento de embarque)</option>
<option value="809">AWB (Air Will Bill)</option>
<option value="810">MIC/DTA</option>
<option value="811">Carta de Porte</option>
<option value="812">Resolucion del SNA donde califica Servicios de Exp.</option>
<option value="813">Pasaporte</option>
<option value="814">Certificado de Deposito Bolsa Prod. Chile</option>
<option value="815">Vale de Prenda Bolsa Prod. Chile</option>
<option value="HES">HES</option>
<option value="HEM">HEM</option>
<option value="WE">WE</option>
<option value="OV">OV</option>
</select></td>
<td valign="top" align="center"><input type="text" name="fecha_ref3" class="campo" size="12" maxlength="10" value="" readonly>&nbsp;<img vspace="0" src="img/calendario.gif" border="0" onClick="Javascript:consultar('calendario.php?codigo=fecha_ref3',300,176);" style="cursor:hand;"  alt="[Cargar fecha desde calendario]"></td>
<td valign="top" align="center"><input type="text" name="folio_ref3" value="" class="campo" size="35" maxlength="18"></td>
<td valign="top" align="center"><input type="text" name="ref3" value="" class="campo" size="35" maxlength="30"></td>
</tr>

<tr><td>4</td>
<td valign="top" align="center"><select class="campo" name="docto_ref4"><option value="">::Seleccione::</option>
<?php if($nTipo != "52"){	?>
<option value="52">Guia de Despacho Electronica</option>
<?php } ?>
<option value="801">Orden de Compra</option>
<option value="802">Nota de pedido</option>
<option value="803">Contrato</option>
<option value="804">Resolucion</option>
<option value="805">Proceso ChileCompra</option>
<option value="806">Ficha ChileCompra</option>
<option value="807">DUS</option>
<option value="808">B/L (Conocimiento de embarque)</option>
<option value="809">AWB (Air Will Bill)</option>
<option value="810">MIC/DTA</option>
<option value="811">Carta de Porte</option>
<option value="812">Resolucion del SNA donde califica Servicios de Exp.</option>
<option value="813">Pasaporte</option>
<option value="814">Certificado de Deposito Bolsa Prod. Chile</option>
<option value="815">Vale de Prenda Bolsa Prod. Chile</option>
<option value="HES">HES</option>
<option value="HEM">HEM</option>
<option value="WE">WE</option>
<option value="OV">OV</option>
</select></td>
<td valign="top" align="center"><input type="text" name="fecha_ref4" class="campo" size="12" maxlength="10" value="" readonly>&nbsp;<img vspace="0" src="img/calendario.gif" border="0" onClick="Javascript:consultar('calendario.php?codigo=fecha_ref4',300,176);" style="cursor:hand;"  alt="[Cargar fecha desde calendario]"></td>
<td valign="top" align="center"><input type="text" name="folio_ref4" value="" class="campo" size="35" maxlength="18"></td>
<td valign="top" align="center"><input type="text" name="ref4" value="" class="campo" size="35" maxlength="30"></td>
</tr>

<tr><td>5</td>
<td valign="top" align="center"><select class="campo" name="docto_ref5"><option value="">::Seleccione::</option>
<?php if($nTipo != "52"){	?>
<option value="52">Guia de Despacho Electronica</option>
<?php } ?>
<option value="801">Orden de Compra</option>
<option value="802">Nota de pedido</option>
<option value="803">Contrato</option>
<option value="804">Resolucion</option>
<option value="805">Proceso ChileCompra</option>
<option value="806">Ficha ChileCompra</option>
<option value="807">DUS</option>
<option value="808">B/L (Conocimiento de embarque)</option>
<option value="809">AWB (Air Will Bill)</option>
<option value="810">MIC/DTA</option>
<option value="811">Carta de Porte</option>
<option value="812">Resolucion del SNA donde califica Servicios de Exp.</option>
<option value="813">Pasaporte</option>
<option value="814">Certificado de Deposito Bolsa Prod. Chile</option>
<option value="815">Vale de Prenda Bolsa Prod. Chile</option>
<option value="HES">HES</option>
<option value="HEM">HEM</option>
<option value="WE">WE</option>
<option value="OV">OV</option>
</select></td>
<td valign="top" align="center"><input type="text" name="fecha_ref5" class="campo" size="12" maxlength="10" value="" readonly>&nbsp;<img vspace="0" src="img/calendario.gif" border="0" onClick="Javascript:consultar('calendario.php?codigo=fecha_ref5',300,176);" style="cursor:hand;"  alt="[Cargar fecha desde calendario]"></td>
<td valign="top" align="center"><input type="text" name="folio_ref5" value="" class="campo" size="35" maxlength="18"></td>
<td valign="top" align="center"><input type="text" name="ref5" value="" class="campo" size="35" maxlength="30"></td>
</tr>


<tr><td>6</td>
<td valign="top" align="center"><select class="campo" name="docto_ref6"><option value="">::Seleccione::</option>
<?php if($nTipo != "52"){	?>
<option value="52">Guia de Despacho Electronica</option>
<?php } ?>
<option value="801">Orden de Compra</option>
<option value="802">Nota de pedido</option>
<option value="803">Contrato</option>
<option value="804">Resolucion</option>
<option value="805">Proceso ChileCompra</option>
<option value="806">Ficha ChileCompra</option>
<option value="807">DUS</option>
<option value="808">B/L (Conocimiento de embarque)</option>
<option value="809">AWB (Air Will Bill)</option>
<option value="810">MIC/DTA</option>
<option value="811">Carta de Porte</option>
<option value="812">Resolucion del SNA donde califica Servicios de Exp.</option>
<option value="813">Pasaporte</option>
<option value="814">Certificado de Deposito Bolsa Prod. Chile</option>
<option value="815">Vale de Prenda Bolsa Prod. Chile</option>
<option value="HES">HES</option>
<option value="HEM">HEM</option>
<option value="WE">WE</option>
<option value="OV">OV</option>
</select></td>
<td valign="top" align="center"><input type="text" name="fecha_ref6" class="campo" size="12" maxlength="10" value="" readonly>&nbsp;<img vspace="0" src="img/calendario.gif" border="0" onClick="Javascript:consultar('calendario.php?codigo=fecha_ref6',300,176);" style="cursor:hand;"  alt="[Cargar fecha desde calendario]"></td>
<td valign="top" align="center"><input type="text" name="folio_ref6" value="" class="campo" size="35" maxlength="18"></td>
<td valign="top" align="center"><input type="text" name="ref6" value="" class="campo" size="35" maxlength="30"></td>
</tr>


<?php 
	for($iR = 7;$iR < 20; $iR++){   	
?>
	<tr><td><?php echo $iR; ?></td>
	<td valign="top" align="center"><select class="campo" name="docto_ref<?php echo $iR; ?>"><option value="">::Seleccione::</option>
	<?php if($nTipo != "52"){	?>
	<option value="52">Guia de Despacho Electronica</option>
	<?php } ?>
	<option value="801">Orden de Compra</option>
	<option value="802">Nota de pedido</option>
	<option value="803">Contrato</option>
	<option value="804">Resolucion</option>
	<option value="805">Proceso ChileCompra</option>
	<option value="806">Ficha ChileCompra</option>
	<option value="807">DUS</option>
	<option value="808">B/L (Conocimiento de embarque)</option>
	<option value="809">AWB (Air Will Bill)</option>
	<option value="810">MIC/DTA</option>
	<option value="811">Carta de Porte</option>
	<option value="812">Resolucion del SNA donde califica Servicios de Exp.</option>
	<option value="813">Pasaporte</option>
	<option value="814">Certificado de Deposito Bolsa Prod. Chile</option>
	<option value="815">Vale de Prenda Bolsa Prod. Chile</option>
	<option value="HES">HES</option>
<option value="HEM">HEM</option>
<option value="WE">WE</option>
<option value="OV">OV</option>
	</select></td>
	<td valign="top" align="center"><input type="text" name="fecha_ref<?php echo $iR; ?>" class="campo" size="12" maxlength="10" value="" readonly>&nbsp;<img vspace="0" src="img/calendario.gif" border="0" onClick="Javascript:consultar('calendario.php?codigo=fecha_ref<?php echo $iR; ?>',300,176);" style="cursor:hand;"  alt="[Cargar fecha desde calendario]"></td>
	<td valign="top" align="center"><input type="text" name="folio_ref<?php echo $iR; ?>" value="" class="campo" size="35" maxlength="18"></td>
	<td valign="top" align="center"><input type="text" name="ref<?php echo $iR; ?>" value="" class="campo" size="35" maxlength="30"></td>
	</tr>
 <?php } ?>


<?php } ?>

</table>
</div> <!-- referencia -->
<table width="96%" align="center" cellspacing="5">
<tr>
<td colspan='6' align="right">
<input type="button" name="save" class="boton" value="Generar DTE" onClick="javascript:grabar();">&nbsp;&nbsp;
<input type="button" name="save" class="boton" value="Preview" onClick="javascript:preview();">&nbsp;&nbsp;
</td>
</tr>
</table>

</div></td>
</tr>
</table>
<!---fin del cuerpo total-->
<br><br></td></tr>
</table>
</form>


</body>
</html>


<?php //require('tres.php'); 
if ($accion=='buscar'){
for ($a=1;$a<=($contadorx-1);++$a){ 
echo "<script language='javascript'>pone_total(".$a.")</script>";
}
}
//echo "<script language='javascript'>document.getElementById('".$trs."').className='current';</script>";?>

<?php } ?>
