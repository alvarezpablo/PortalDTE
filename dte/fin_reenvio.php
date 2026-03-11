<?php
include("../include/config.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }

$rawMessage = isset($_REQUEST["msj"]) ? trim((string)$_REQUEST["msj"]) : "";
$normalized = str_ireplace(array("<br />", "<br/>", "<br>"), "\n", $rawMessage);
$normalized = strip_tags($normalized);
$normalized = str_replace("\r", "", $normalized);
$messageLines = array();

foreach(explode("\n", $normalized) as $line){
	$line = trim($line);
	if($line !== ""){
		$messageLines[] = $line;
	}
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Reenvio de DTE</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<base href="<?php echo h($_LINK_BASE); ?>" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
		.popup-shell{max-width:720px;margin:0 auto;padding:1rem}.popup-card{border:0;border-radius:18px;box-shadow:0 18px 40px rgba(15,23,42,.12);overflow:hidden}
		.popup-body{padding:1.75rem 1.25rem;background:#fff}.icon-wrap{width:68px;height:68px;border-radius:20px;background:linear-gradient(135deg,#0d6efd 0%,#20c997 100%);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.85rem}
	</style>
	<script type="text/javascript">
	function cerrarVentana(){
		window.close();
		return false;
	}
	</script>
</head>
<body>
	<div class="popup-shell">
		<div class="popup-card card mt-4">
			<div class="popup-body text-center">
				<div class="icon-wrap mx-auto mb-3"><i class="bi bi-envelope-check"></i></div>
				<h1 class="h4 mb-2">Reenvio de DTE</h1>
				<p class="text-secondary mb-4">Pantalla final segura para el flujo de reenvio por correo.</p>
				<div class="alert alert-info text-start border-0 shadow-sm mx-auto" style="max-width:560px;">
					<?php if(sizeof($messageLines) > 0){ ?>
					<ul class="mb-0 ps-3">
						<?php foreach($messageLines as $line){ ?>
						<li><?php echo h($line); ?></li>
						<?php } ?>
					</ul>
					<?php } else { ?>
					<strong>No se recibio informacion para mostrar.</strong>
					<?php } ?>
				</div>
				<div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
					<button type="button" class="btn btn-primary" onclick="cerrarVentana();"><i class="bi bi-x-circle me-2"></i>Cerrar ventana</button>
					<a href="main.php" class="btn btn-outline-secondary"><i class="bi bi-house-door me-2"></i>Ir al inicio</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
