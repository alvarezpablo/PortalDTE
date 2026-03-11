<?php
include("../include/config.php");
include("../include/tables.php");

if (!function_exists('h')) {
	function h($value) {
		return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
	}
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Error - Portal DTE</title>
	<link rel="shortcut icon" href="/favicon.ico" />
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<base href="<?php echo h($_LINK_BASE); ?>" />
	<script type="text/javascript" src="javascript/common.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
	<style>
		body { background: #eef2f7; font-family: Arial, Helvetica, sans-serif; color: #1f2937; }
		.page-shell { max-width: 860px; margin: 0 auto; padding: 1rem; }
		.card-shell { border: 0; border-radius: 22px; box-shadow: 0 18px 40px rgba(15,23,42,.1); }
	</style>
</head>

<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	<a href="#" name="top" id="top"></a>

	<div class="page-shell">
		<div class="card card-shell">
			<div class="card-body p-4 p-lg-5">
				<div class="d-flex align-items-start gap-3">
					<div class="text-danger fs-1"><i class="bi bi-exclamation-triangle-fill"></i></div>
					<div>
						<h1 class="h3 mb-3">Mensaje de rechazo SII</h1>
						<p class="mb-0">Su factura electr&oacute;nica se rechazo porque el N&deg; de DTE no es valido.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>