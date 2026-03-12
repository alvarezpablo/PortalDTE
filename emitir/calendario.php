<?php
require("inc/funciones.php");

if (!function_exists('h')) {
	function h($value){
		return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
	}
}

function buildCalendarUrl($dia, $mes, $ano, $codigo){
	return "calendario.php?dia=" . urlencode($dia) . "&nuevo_mes=" . urlencode($mes) . "&nuevo_ano=" . urlencode($ano) . "&codigo=" . urlencode($codigo);
}

$nuevo_mes = isset($nuevo_mes) ? trim((string)$nuevo_mes) : "";
$nuevo_ano = isset($nuevo_ano) ? trim((string)$nuevo_ano) : "";
$dia = isset($dia) ? trim((string)$dia) : "";
$codigo = isset($codigo) ? trim((string)$codigo) : "";
$accion = isset($accion) ? trim((string)$accion) : "";

if ($nuevo_mes == '' && $nuevo_ano == '' && $dia == '') {
	$mes = (int) date("n");
	$ano = (int) date("Y");
	$dia = (int) date("d");
} else {
	$mes = ($nuevo_mes != '') ? (int) $nuevo_mes : (int) date("n");
	$ano = ($nuevo_ano != '') ? (int) $nuevo_ano : (int) date("Y");
	$dia = ($dia != '') ? (int) $dia : 1;
}

if ($mes < 1 || $mes > 12) $mes = (int) date("n");
if ($ano < 2014) $ano = 2014;
if ($dia < 1) $dia = 1;

$scriptCarga = '';
if ($accion == 'cargar') {
	$dia_comp = str_pad((string) $dia, 2, '0', STR_PAD_LEFT);
	$mes_comp = str_pad((string) $mes, 2, '0', STR_PAD_LEFT);
	$fecha_comp = $dia_comp . "/" . $mes_comp . "/" . $ano;
	if ($codigo != '') {
		$scriptCarga = "<script language=\"javascript\">var form=opener.document.formulario1;var obj=eval(\"opener.document.formulario1." . addslashes($codigo) . "\");obj.value='" . addslashes($fecha_comp) . "';opener.focus();window.close();</script>";
	}
}

$mes_hoy = (int) date("m");
$ano_hoy = (int) date("Y");
$hoy = (($mes_hoy == $mes) && ($ano_hoy == $ano)) ? (int) date("d") : 0;
$numero_dia = calcula_numero_dia_semana(1, $mes, $ano);
$ultimo_dia = ultimoDia($mes, $ano);
$anioFinal = (int) date("Y") + 5;
$meses = array(
	1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
	5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
	9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
);
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<style type="text/css">
		body{margin:0;background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
		.page-shell{max-width:340px;margin:0 auto;padding:.55rem}
		.topbar{display:flex;align-items:center;justify-content:space-between;gap:.5rem;background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;border-radius:14px;padding:.55rem .7rem;box-shadow:0 10px 24px rgba(15,23,42,.16);margin-bottom:.55rem}
		.topbar-title{font-size:.92rem;font-weight:700;line-height:1.1}
		.topbar-meta{font-size:.72rem;opacity:.85}
		.topbar .btn{white-space:nowrap}
		.panel{background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:14px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden}
		.panel-body{padding:.65rem}
		.control-grid{display:grid;grid-template-columns:1fr 1fr;gap:.45rem;margin-bottom:.55rem}
		.form-select{font-size:.78rem;padding:.3rem 1.75rem .3rem .6rem;min-height:34px}
		.calendar-table{width:100%;border-collapse:separate;border-spacing:4px}
		.calendar-table th{font-size:.68rem;font-weight:700;color:#64748b;text-align:center;padding:0 0 .15rem}
		.calendar-table td{width:14.28%;height:28px;text-align:center;vertical-align:middle}
		.day-empty{border-radius:9px;background:#f8fafc}
		.day-link{display:flex;align-items:center;justify-content:center;width:100%;height:100%;border-radius:9px;background:#f8fafc;border:1px solid #dbe7f3;color:#0f172a;text-decoration:none;font-size:.78rem;font-weight:600}
		.day-link:hover{background:#eff6ff;border-color:#93c5fd;color:#0b5ed7}
		.day-link.weekend{background:#fff7ed;border-color:#fed7aa;color:#9a3412}
		.day-link.today{background:#0b5ed7;border-color:#0b5ed7;color:#fff}
		.day-link.selected{box-shadow:inset 0 0 0 2px #001f3f}
		.panel-footer{display:flex;justify-content:space-between;align-items:center;gap:.5rem;padding:.55rem .65rem;border-top:1px solid #e2e8f0;background:#f8fafc}
		.helper-text{font-size:.7rem;color:#64748b;line-height:1.2}
	</style>
	<script language="javascript">
	function cambio(){
		var mes = document.getElementById('mesx').value;
		var ano = document.getElementById('anox').value;
		if(mes === '' || ano === '') return;
		location.href = 'calendario.php?dia=1&nuevo_mes=' + encodeURIComponent(mes) + '&nuevo_ano=' + encodeURIComponent(ano) + '&codigo=<?php echo rawurlencode($codigo); ?>';
	}
	</script>
</head>
<body>
	<?php echo $scriptCarga; ?>
	<form name="formulario1" action="" method="post">
	<div class="page-shell">
		<div class="topbar">
			<div>
				<div class="topbar-title">Seleccionar fecha</div>
				<div class="topbar-meta"><?php echo h(dame_nombre_mes($mes) . ' ' . $ano); ?></div>
			</div>
			<button type="button" class="btn btn-light btn-sm" onclick="window.close();">Cerrar</button>
		</div>

		<div class="panel">
			<div class="panel-body">
				<div class="control-grid">
					<select name="mesx" id="mesx" class="form-select form-select-sm" onchange="cambio();">
						<?php foreach($meses as $mesNumero => $mesNombre): ?>
							<option value="<?php echo h($mesNumero); ?>"<?php if($mes == $mesNumero) echo ' selected'; ?>><?php echo h($mesNombre); ?></option>
						<?php endforeach; ?>
					</select>
					<select name="anox" id="anox" class="form-select form-select-sm" onchange="cambio();">
						<?php for($a = 2014; $a <= $anioFinal; ++$a): ?>
							<option value="<?php echo h($a); ?>"<?php if($ano == $a) echo ' selected'; ?>><?php echo h($a); ?></option>
						<?php endfor; ?>
					</select>
				</div>

				<table class="calendar-table">
					<thead>
						<tr>
							<th>Lu</th>
							<th>Ma</th>
							<th>Mi</th>
							<th>Ju</th>
							<th>Vi</th>
							<th>Sa</th>
							<th>Do</th>
						</tr>
					</thead>
					<tbody>
						<tr>
						<?php for($i = 0; $i < 7; ++$i): ?>
							<?php if($i < $numero_dia): ?>
								<td><div class="day-empty"></div></td>
							<?php else: ?>
								<?php
								$dayNumber = $i - $numero_dia + 1;
								$classes = 'day-link';
								if($i >= 5) $classes .= ' weekend';
								if($dayNumber == $hoy) $classes .= ' today';
								if($dayNumber == (int)$dia) $classes .= ' selected';
								?>
								<td><a class="<?php echo h($classes); ?>" href="<?php echo h(buildCalendarUrl($dayNumber, $mes, $ano, $codigo) . '&accion=cargar'); ?>"><?php echo h($dayNumber); ?></a></td>
							<?php endif; ?>
						<?php endfor; ?>
						</tr>
						<?php
						$dia_actual = 8 - $numero_dia;
						while($dia_actual <= $ultimo_dia):
						?>
							<tr>
							<?php for($columna = 0; $columna < 7; ++$columna): ?>
								<?php if($dia_actual <= $ultimo_dia): ?>
									<?php
									$classes = 'day-link';
									if($columna >= 5) $classes .= ' weekend';
									if($dia_actual == $hoy) $classes .= ' today';
									if($dia_actual == (int)$dia) $classes .= ' selected';
									?>
									<td><a class="<?php echo h($classes); ?>" href="<?php echo h(buildCalendarUrl($dia_actual, $mes, $ano, $codigo) . '&accion=cargar'); ?>"><?php echo h($dia_actual); ?></a></td>
									<?php $dia_actual++; ?>
								<?php else: ?>
									<td><div class="day-empty"></div></td>
								<?php endif; ?>
							<?php endfor; ?>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>

			<div class="panel-footer">
				<div class="helper-text">La fecha se escribe directamente en el formulario emisor.</div>
				<div class="badge text-bg-light border">Campo: <?php echo h($codigo != '' ? $codigo : 'sin codigo'); ?></div>
			</div>
		</div>
	</div>
	</form>
</body>
</html>
