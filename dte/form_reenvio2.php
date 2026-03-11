<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/ver_emp_adm.php");
include("../include/db_lib.php");

if (!function_exists('h')) {
	function h($value) {
		return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
	}
}

$nFolio = isset($_GET["nFolio"]) ? trim($_GET["nFolio"]) : "";
$nTipoDTE = isset($_GET["nTipoDTE"]) ? trim($_GET["nTipoDTE"]) : "";
$emailenvio = "";
$emailcontribuyente = "";

if (trim($nFolio) == "" || $nTipoDTE == "") {
	echo "<script>alert('Faltan folio o tipo de documento');window.close();</script>";
}

$conn = conn();

$sql = "SELECT email_envio FROM clientes WHERE rut_cli IN (SELECT rut_rec_dte FROM dte_enc WHERE folio_dte=$nFolio AND tipo_docu=$nTipoDTE AND codi_empr=" . $_SESSION["_COD_EMP_USU_SESS"] . ")";
$result = rCursor($conn, $sql);
if (!$result->EOF) {
	$emailenvio = trim($result->fields["email_envio"]);
}

$sql = "SELECT email_contr FROM contrib_elec WHERE rut_contr IN (SELECT rut_rec_dte FROM dte_enc WHERE folio_dte=$nFolio AND tipo_docu=$nTipoDTE AND codi_empr=" . $_SESSION["_COD_EMP_USU_SESS"] . ")";
$result = rCursor($conn, $sql);
if (!$result->EOF) {
	$emailcontribuyente = trim($result->fields["email_contr"]);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Reenviar DTE</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<base href="<?php echo h($_LINK_BASE); ?>" />
	<script type="text/javascript" src="javascript/funciones.js"></script>
	<script type="text/javascript" src="javascript/common.js"></script>
	<script type="text/javascript" src="javascript/msg.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
	<style>
		body { background: #eef2f7; font-family: Arial, Helvetica, sans-serif; color: #1f2937; }
		.popup-shell { max-width: 760px; margin: 0 auto; padding: 1rem; }
		.hero { background: linear-gradient(135deg, #0f3d6e 0%, #0b5ed7 100%); color: #fff; border-radius: 20px; padding: 1.5rem; box-shadow: 0 18px 40px rgba(15,23,42,.18); margin-bottom: 1.25rem; }
		.hero-badge { display: inline-flex; align-items: center; gap: .45rem; background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.18); border-radius: 999px; padding: .4rem .75rem; font-size: .85rem; }
		.card-shell { border: 0; border-radius: 20px; box-shadow: 0 16px 36px rgba(15,23,42,.08); }
		.option-card { border: 1px solid #dbeafe; border-radius: 16px; padding: 1rem; background: #f8fbff; height: 100%; }
		.small-muted { color: #64748b; font-size: .92rem; }
	</style>
	<script type="text/javascript">
	function valida(){
		if(confirm("Confirma el Reenvio del documento ??")){
			if(email(document._F.sDestinatario.value,"Ingrese un Email Valido") == false){
				document._F.sDestinatario.focus();
				document._F.sDestinatario.select();
				return false;
			}
			return true;
		}
		return false;
	}

	function IngresarEmail(opcion){
		if(opcion==1)
			document._F.sDestinatario.value=document._F.emailcliente.value;

		if(opcion==2)
			document._F.sDestinatario.value=document._F.emailcontribuyente.value;
	}
	</script>
</head>
<body>
	<div class="popup-shell">
		<div class="hero">
			<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
				<div>
					<h1 class="h4 mb-2">Reenviar DTE</h1>
					<p class="mb-0 opacity-75">Mantiene el envio al procesador legado <strong>dte/pro_resend.php</strong> con los mismos parametros del popup original.</p>
				</div>
				<span class="hero-badge"><i class="bi bi-send"></i>Folio <?php echo h($nFolio); ?> &middot; Tipo <?php echo h($nTipoDTE); ?></span>
			</div>
		</div>

		<form name="_F" method="post" action="<?php echo h($_LINK_BASE); ?>dte/pro_resend.php" onsubmit="return valida();" class="card card-shell">
			<input type="hidden" name="nFolio" value="<?php echo h($nFolio); ?>" />
			<input type="hidden" name="nTipoDTE" value="<?php echo h($nTipoDTE); ?>" />
			<input type="hidden" name="emailcliente" value="<?php echo h($emailenvio); ?>" />
			<input type="hidden" name="emailcontribuyente" value="<?php echo h($emailcontribuyente); ?>" />
			<div class="card-body p-4">
				<div class="row g-3 mb-4">
					<div class="col-md-6">
						<label class="option-card d-block">
							<input type="radio" name="nTipoEnvio" value="PDF" checked onclick="IngresarEmail(1)" />
							<span class="fw-semibold ms-2">Enviar PDF</span>
							<div class="small-muted mt-2">Usa el email sugerido del cliente cuando exista.</div>
						</label>
					</div>
					<div class="col-md-6">
						<label class="option-card d-block">
							<input type="radio" name="nTipoEnvio" value="XML" onclick="IngresarEmail(2)" />
							<span class="fw-semibold ms-2">Enviar XML</span>
							<div class="small-muted mt-2">Usa el email sugerido del contribuyente electronico cuando exista.</div>
						</label>
					</div>
				</div>

				<div class="mb-3">
					<label for="sDestinatario" class="form-label fw-semibold">Email destino</label>
					<input type="text" name="sDestinatario" id="sDestinatario" value="<?php echo h($emailenvio); ?>" maxlength="200" class="form-control" />
					<div class="form-text">Puedes ajustar manualmente el destinatario antes de reenviar.</div>
				</div>

				<div class="d-flex flex-wrap gap-2 justify-content-end">
					<button type="submit" class="btn btn-primary"><i class="bi bi-send-check me-2"></i>Re-Enviar</button>
					<button type="button" class="btn btn-outline-secondary" onclick="window.close();"><i class="bi bi-x-circle me-2"></i>Cerrar</button>
				</div>
			</div>
		</form>
	</div>
</body>
</html>