<?php
require("inc/funciones.php");
if ($nuevo_mes=='' && $nuevo_ano=='' && $dia==''){$mes=date("n",time());$ano=date("Y",time());$dia=date("d",time());}else{$mes=$nuevo_mes;$ano=$nuevo_ano;$dia=$dia;}
$fecha=$ano."-".$mes."-".$dia;
if ($accion=='cargar'){
if (strlen($dia)<2)$dia='0'.$dia;
if (strlen($nuevo_mes)<2)$nuevo_mes='0'.$nuevo_mes;
$fecha_comp=$dia."/".$nuevo_mes."/".$nuevo_ano;
if ($codigo!='')echo "<script language=\"javascript\">var form=opener.document.formulario1;var obj=eval(\"opener.document.formulario1.$codigo\");obj.value='$fecha_comp';opener.focus();window.close();</script>";}
function mostrar_calendario($dia,$mes,$ano,$codigo){
$mesz=$mes;
if ($mesz<10) $mesz='0'.$mesz; 
$mes_hoy=date("m");
$ano_hoy=date("Y");
if (($mes_hoy<>$mes)||($ano_hoy<>$ano))$hoy=0;else $hoy=date("d");
$nombre_mes=dame_nombre_mes($mes);
echo "<table width=300 height=150 class=texto cellspacing=2 cellpadding=2><tr><td colspan=7 align=center>";
$mes_anterior=$mes-1;$ano_anterior=$ano;
if ($mes_anterior==0){--$ano_anterior;$mes_anterior=12;}
$mes_siguiente=$mes+1;
$ano_siguiente=$ano;
if ($mes_siguiente==13){++$ano_siguiente;$mes_siguiente=1;}
echo "<select name='mesx' class='campo_cal' onChange=\"javascript:cambio(this.value,$ano);\"><option value=''>Mes</option>
<option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option>
<option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option>
<option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select>
<select name='anox' class='campo_cal' onChange=\"javascript:cambio($mes,this.value);\"><option value=''>Año</option>";
$anioFinal = date("Y") + 5;
for ($a=2014;$a<$anioFinal;++$a){echo "<option value='$a'>$a</option>";}echo "</select>
</td></tr>
<tr><td width=14% align=center class=altnx>Lu</td><td width=14% align=center class=altnx>Ma</td><td width=14% align=center class=altnx>Mi</td><td width=14% align=center class=altnx>Ju</td><td width=14% align=center class=altnx>Vi</td><td width=14% align=center class=altnx>Sa</td><td width=14% align=center class=altnx>Do</td></tr>";
$dia_actual=1;
$numero_dia=calcula_numero_dia_semana(1,$mes,$ano);
$ultimo_dia=ultimoDia($mes,$ano);
echo "<tr>";
for ($i=0;$i<7;++$i){
if ($i<$numero_dia)echo "<td class=campo_cal></td>";else{
if (($i==5) || ($i==6)){
if ($dia_actual==$hoy)echo "<td class=da><a href=calendario.php?dia=$dia_actual&nuevo_mes=$mes&nuevo_ano=$ano&codigo=$codigo&accion=cargar>$dia_actual</a></td>";else echo "<td class=fs><a href=calendario.php?dia=$dia_actual&nuevo_mes=$mes&nuevo_ano=$ano&codigo=$codigo&accion=cargar>$dia_actual</a></td>";
}else{			
if ($dia_actual==$hoy)echo "<td class=da><a href=calendario.php?dia=$dia_actual&nuevo_mes=$mes&nuevo_ano=$ano&codigo=$codigo&accion=cargar>$dia_actual</a></td>";else echo "<td align=center class=campo_cal><a href=calendario.php?dia=$dia_actual&nuevo_mes=$mes&nuevo_ano=$ano&codigo=$codigo&accion=cargar>$dia_actual</a></td>";
}
++$dia_actual;
}
}
echo "</tr>";
$numero_dia=0;
while ($dia_actual<=$ultimo_dia){
if ($numero_dia==0)
echo "<tr>";
if (($numero_dia==5)||($numero_dia==6)){
if ($dia_actual==$hoy)echo "<td class=da><a href=calendario.php?dia=$dia_actual&nuevo_mes=$mes&nuevo_ano=$ano&codigo=$codigo&accion=cargar>$dia_actual</a></td>";else echo "<td class=fs ><a href=calendario.php?dia=$dia_actual&nuevo_mes=$mes&nuevo_ano=$ano&codigo=$codigo&accion=cargar>$dia_actual</a></td>";
}else{		
if ($dia_actual==$hoy) echo "<td class=da><a href=calendario.php?dia=$dia_actual&nuevo_mes=$mes&nuevo_ano=$ano&codigo=$codigo&accion=cargar>$dia_actual</a></td>";else echo "<td align=center class=campo_cal><a href=calendario.php?dia=$dia_actual&nuevo_mes=$mes&nuevo_ano=$ano&codigo=$codigo&accion=cargar>$dia_actual</a></td>";
}
++$dia_actual;
++$numero_dia;
if ($numero_dia==7){
$numero_dia=0;
echo "</tr>";
}
}
for ($i=$numero_dia;$i<7;++$i){
echo "<td></td>";
}
echo "</tr></table>";
}
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="inc/estilo.css"/>
<script language="javascript">
function cambio(mes,ano){
location.href="calendario.php?dia=1&nuevo_mes="+mes+"&nuevo_ano="+ano+"&codigo=<?php echo $codigo;?>";
}
</script>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<form name="formulario1" action="" method="post">
<?php mostrar_calendario($dia,$mes,$ano,$codigo); ?>
</form>
</body>
</html>
<?php 
if ($nuevo_mes=='') $nuevo_mes=$mes;
if ($nuevo_ano=='') $nuevo_ano=$ano;
echo "<script language='javascript'>document.formulario1.mesx.value='$nuevo_mes';
document.formulario1.anox.value='$nuevo_ano';</script>";?>
