<?php
include("../include/ver_aut.php");
include("../include/ver_emp_adm.php");

if (!function_exists('h')) {
	function h($value) {
		return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
	}
}

$nFolio = isset($_GET["nFolio"]) ? trim((string)$_GET["nFolio"]) : "";
$nTipoDTE = isset($_GET["nTipoDTE"]) ? trim((string)$_GET["nTipoDTE"]) : "";
$nMontTot = isset($_GET["nMontTot"]) ? trim((string)$_GET["nMontTot"]) : "";
$nCodEmp = isset($_SESSION["_COD_EMP_USU_SESS"]) ? $_SESSION["_COD_EMP_USU_SESS"] : "";
$xml_id = isset($_POST["xml_id"]) ? trim((string)$_POST["xml_id"]) : "";
$tipo_docu = isset($_POST["tipo_docu"]) ? trim((string)$_POST["tipo_docu"]) : "";

$conn = pg_connect("host=10.30.1.194 port=5432 dbname=opendte user=opendte password=root8831");
pg_set_client_encoding($conn, "LATIN1");

$mostrar_form = true;
$statusVariant = "info";
$statusTitle = "";
$statusText = "";
$statusLinkHref = "";
$statusLinkLabel = "";

if (isset($_POST['accion'])) {
	$correo = isset($_POST['correo']) ? $_POST['correo'] : "";
	$rut = isset($_POST['rut']) ? $_POST['rut'] : "";
	$razon_social = isset($_POST['razon_social']) ? $_POST['razon_social'] : "";
	$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : "";
	$correo0 = isset($_POST['correo0']) ? $_POST['correo0'] : "";
	$monto_cesion = isset($_POST['monto_cesion']) ? $_POST['monto_cesion'] : "";
	$fecha_vencimiento = isset($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : "";
	$otras_condiciones = isset($_POST['otras_condiciones']) ? $_POST['otras_condiciones'] : "";
	$correo2 = isset($_POST['correo2']) ? $_POST['correo2'] : "";

	switch ($_POST['accion']) {
		case 'grabar':
			$sql = "  SELECT ";
			$sql .= "    E.codi_empr,  ";
			$sql .= "    E.rs_empr, ";
			$sql .= "    E.rut_empr, ";
			$sql .= "    E.dv_empr, ";
			$sql .= "    E.dir_empr ";
			$sql .= "  FROM ";
			$sql .= "    empresa E ";
			$sql .= "  WHERE ";
			$sql .= "     E.codi_empr = " . $_SESSION["_COD_EMP_USU_SESS"];

			$result = pg_query($conn, $sql);
			while ($row = pg_fetch_array($result)) {
				$salida = $row;
			}

			$rut_cedente = $salida["rut_empr"];
			$dv_empresa = $salida["dv_empr"];
			$razon_social_cedente = $salida["rs_empr"];
			$dir_cedente = $salida["dir_empr"];

			$sql = "select count(1) as contador from dte_ceder where codi_empr='" . $nCodEmp . "' and tipo_docu='" . $nTipoDTE . "' and folio_dte='" . $nFolio . "'";
			$result = pg_query($conn, $sql);
			while ($row = pg_fetch_array($result)) {
				$contadorx = $row[0];
			}

			if ($contadorx == 0) {
				$sql = "select valor_config FROM config WHERE cod_config = 'NOMBRE_CESION' and codi_empr=" . $nCodEmp . " and valor_config <> '' ";
				$result = pg_query($conn, $sql);
				while ($row = pg_fetch_array($result)) {
					$nombre_cedente = $row[0];
				}

				$sql = "select valor_config FROM config WHERE cod_config = 'RUT_CESION' and codi_empr=" . $nCodEmp . " and valor_config <> ''";
				$result = pg_query($conn, $sql);
				while ($row = pg_fetch_array($result)) {
					$rut_firma = $row[0];
				}

				$sql = "insert into dte_ceder 
				(codi_empr,tipo_docu,folio_dte,estado,cesion,rut_cedente,razon_social_cedente,direccion_cedente,email_cedente,rut_cesionario,razon_social_cesionario,direccion_cesionario,email_cesionario,monto_cesion,fecha_ultimo_vencimiento,otras_condiciones,email_deudor,secuencia_cesion,email_notificacion_sii)
				 		values
				('" . $nCodEmp . "','" . $nTipoDTE . "','" . $nFolio . "',1,1,'" . $rut_cedente . "-" . $dv_empresa . "','" . $razon_social_cedente . "','" . $dir_cedente . "','" . $correo . "','" . $rut . "','" . $razon_social . "','" . $direccion . "','" . $correo0 . "','" . $monto_cesion . "','" . $fecha_vencimiento . "','" . $otras_condiciones . "','" . $correo . "',1,'" . $correo2 . "')";

				$result = pg_query($conn, $sql);
				if (!$result) {
					echo "A ocurrido un error inesperado, contactar a soporte";
					exit;
				}

				$sql = "SELECT currval('dte_ceder_id_seq')";
				$result = pg_query($conn, $sql);
				while ($row = pg_fetch_array($result)) {
					$dte_ceder_id_seq = $row[0];
				}

				$sql = "insert into dte_ceder_rut_autorizado(rut, nombre, dte_ceder) values('" . $rut_firma . "','" . $nombre_cedente . "'," . $dte_ceder_id_seq . ")";
				$result = pg_query($conn, $sql);

				if (!$result) {
					echo "A ocurrido un error inesperado, contactar a soporte";
					exit;
				}

				echo "<script language=\"Javascript\">alert('Cesion creada con exito...');window.close();</script>";
			} else {
				echo "<script language=\"Javascript\">alert('El documento ya presenta una cesion no lo puede volver a ceder...');window.close();</script>";
			}
		break;
	}
} else {
	$mostrar_form = false;
	$esta_autorizado = false;
	$rut_cesion_autorizado = false;
	$nombre_cesion_autorizado = false;
	$autoriza_ceder = 0;
	$existe_ceder_rut = 0;
	$existe_ceder_nombre = 0;
	$salida_dte_ceder = 0;
	$salida_ceder = 0;

	$sql = "select count(1) FROM config WHERE cod_config = 'HABILITAR_CESION' and codi_empr=" . $nCodEmp . " and valor_config='S'";
	$result = pg_query($conn, $sql);
	while ($row = pg_fetch_array($result)) {
		$autoriza_ceder = $row[0];
	}

	if ($autoriza_ceder > 0) {
		$esta_autorizado = true;
	}

	$sql = "select count(1) FROM config WHERE cod_config = 'RUT_CESION' and codi_empr=" . $nCodEmp . " and valor_config <> ''";
	$result = pg_query($conn, $sql);
	while ($row = pg_fetch_array($result)) {
		$existe_ceder_rut = $row[0];
	}

	if ($existe_ceder_rut > 0) {
		$rut_cesion_autorizado = true;
	}

	$sql = "select count(1) FROM config WHERE cod_config = 'NOMBRE_CESION' and codi_empr=" . $nCodEmp . " and valor_config <> '' ";
	$result = pg_query($conn, $sql);
	while ($row = pg_fetch_array($result)) {
		$existe_ceder_nombre = $row[0];
	}

	if ($existe_ceder_nombre > 0) {
		$nombre_cesion_autorizado = true;
	}

	if ($esta_autorizado && $rut_cesion_autorizado && $nombre_cesion_autorizado) {
		$sql = "select id from dte_ceder where codi_empr='" . $nCodEmp . "' and tipo_docu='" . $nTipoDTE . "' and folio_dte='" . $nFolio . "'";
		$result = pg_query($conn, $sql);
		while ($row = pg_fetch_array($result)) {
			$salida_dte_ceder = $row[0];
		}

		$salida_dte_ceder = (int)$salida_dte_ceder;
		if ($salida_dte_ceder > 0) {
			$sql = "select count(1) from cesion where dte_ceder='" . $salida_dte_ceder . "'";
			$result = pg_query($conn, $sql);
			while ($row = pg_fetch_array($result)) {
				$salida_ceder = $row[0];
			}

			$salida_ceder = (int)$salida_ceder;
			if ($salida_ceder > 0) {
				$statusVariant = "success";
				$statusTitle = "Cesion disponible";
				$statusText = "El documento ya cuenta con cesion generada y puede descargar el XML asociado.";
				$statusLinkHref = "view_cesion.php?nFolioDte=" . urlencode($nFolio) . "&nTipoDocu=" . urlencode($nTipoDTE);
				$statusLinkLabel = "Descargar XML cesion";
			} else {
				$statusVariant = "warning";
				$statusTitle = "Cesion en proceso";
				$statusText = "En proceso..";
			}
		} else {
			$mostrar_form = true;
		}
	} else {
		$statusVariant = "info";
		$statusTitle = "Cesion no habilitada";
		$statusText = "Comunicarse con su ejecutivo comercial";
	}
}

$montoDocumento = is_numeric($nMontTot) ? number_format((float)$nMontTot, 0, ',', '.') : $nMontTot;
$stateClass = "state-info";
$stateIcon = "bi-info-circle";

if ($statusVariant == "success") {
	$stateClass = "state-success";
	$stateIcon = "bi-check2-circle";
} elseif ($statusVariant == "warning") {
	$stateClass = "state-warning";
	$stateIcon = "bi-hourglass-split";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<title>Cesion DTE</title>
	<script language="JavaScript" type="text/JavaScript" src="https://portaldte.opendte.cl/javascript/funciones_jean.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
	<style>
		body { background: #eef2f7; font-family: Arial, Helvetica, sans-serif; color: #1f2937; }
		.popup-shell { max-width: 980px; margin: 0 auto; padding: 1rem; }
		.hero { background: linear-gradient(135deg, #001f3f 0%, #0b5ed7 100%); color: #fff; border-radius: 20px; padding: 1.4rem 1.5rem; box-shadow: 0 18px 40px rgba(15,23,42,.18); margin-bottom: 1.25rem; }
		.hero-badge { display: inline-flex; align-items: center; gap: .45rem; background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.18); border-radius: 999px; padding: .4rem .75rem; font-size: .85rem; margin-bottom: .75rem; }
		.hero-title { margin: 0; font-size: 1.45rem; font-weight: 700; }
		.hero-meta { display: flex; flex-wrap: wrap; gap: .7rem; margin-top: .95rem; }
		.hero-chip { display: inline-flex; align-items: center; gap: .45rem; padding: .45rem .85rem; border-radius: 999px; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.18); font-size: .9rem; }
		.card-shell { border: 0; border-radius: 20px; box-shadow: 0 16px 36px rgba(15,23,42,.08); overflow: hidden; }
		.card-head { padding: 1.35rem 1.5rem .8rem; border-bottom: 1px solid #e5e7eb; }
		.card-title { margin: 0; font-size: 1.18rem; font-weight: 700; color: #0f172a; }
		.card-text { margin: .35rem 0 0; color: #64748b; font-size: .94rem; }
		.card-body { padding: 1.5rem; }
		.form-label { font-weight: 600; color: #0f172a; }
		.req { color: #dc3545; margin-left: .15rem; }
		.form-control, textarea { border-radius: 12px; border-color: #cbd5e1; padding: .72rem .9rem; }
		.form-control:focus, textarea:focus { border-color: #0b5ed7; box-shadow: 0 0 0 .2rem rgba(11,94,215,.15); }
		.amount-pill { display: inline-flex; align-items: center; gap: .45rem; padding: .65rem .9rem; border-radius: 14px; background: #eff6ff; color: #1d4ed8; font-weight: 600; }
		.field-note, .form-note { color: #64748b; font-size: .9rem; }
		.form-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: flex-end; margin-top: 1.4rem; }
		.state-card { border-radius: 18px; padding: 2rem 1.5rem; text-align: center; }
		.state-info { background: linear-gradient(180deg, #eff6ff 0%, #ffffff 100%); }
		.state-success { background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%); }
		.state-warning { background: linear-gradient(180deg, #fff7ed 0%, #ffffff 100%); }
		.state-icon { width: 76px; height: 76px; margin: 0 auto 1rem; border-radius: 22px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 2rem; background: linear-gradient(135deg, #001f3f 0%, #0b5ed7 100%); }
		.state-title { margin-bottom: .6rem; font-size: 1.35rem; font-weight: 700; color: #0f172a; }
		.state-text { max-width: 680px; margin: 0 auto 1.2rem; color: #64748b; }
		.state-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; }
	</style>
	<script language="javascript">
	function validarut2(rut2){
		var rut=trim(rut2);
		var aRut=rut.split("-");
		if (aRut.length > 2) return false;
		if (rut.length<9)return(false);
		i1=rut.indexOf("-");
		dv=rut.substr(i1+1);
		dv=dv.toUpperCase();
		nu=rut.substr(0,i1);
		cnt=0;
		suma=0;
		for (i=nu.length-1; i>=0; --i){
			dig=nu.substr(i,1);
			fc=cnt+2;
			suma+= parseInt(dig)*fc;
			cnt=(cnt+1)%6;
		}
		dvok=11-(suma%11);
		if (dvok==11) dvokstr="0";
		if (dvok==10) dvokstr="K";
		if ((dvok!=11) && (dvok!=10))
			dvokstr=""+dvok;
		if (dvokstr==dv)return(true);
		 else return(false);
	}

	function valida(){
		var form=document.formulario1;
		if(validarut2(form.rut.value)==false){
			alert("Rut ingresado no es valido");
			sele('rut');
			foco('rut');
			return false;
		}
		if(alfanumerico(form.razon_social.value,'',1)==false){
			sele('razon_social');
			foco('razon_social');
			return false;
		}
		if(alfanumerico(form.direccion.value,'',1)==false){
			sele('direccion');
			foco('direccion');
			return false;
		}
		if(numerico(form.monto_cesion.value,'',1)==false){
			sele('monto_cesion');
			foco('monto_cesion');
			return false;
		}
		if (parseFloat(form.monto_cesion.value)>parseFloat(form.mnt.value)){
			alert("El monto de la cesion no puede ser mayor al monto del documento...");
			sele('monto_cesion');
			foco('monto_cesion');
			return false;
		}
		if (form.fecha_vencimiento.value==""){
			alert("Seleccione fecha ultimo vencimiento...");
			return false;
		}
		if (validateDate(form.fecha_vencimiento.value)==false){
			alert("Formato de Fecha de vencimiento es incorrecta, debe ser anio-mes-dia ej: 2020-05-12");
			selec('fecha_vencimiento');
			foco('fecha_vencimiento');
			return false;
		}
		if(alfanumerico(form.otras_condiciones.value,'',1)==false){
			sele('otras_condiciones');
			foco('otras_condiciones');
			return false;
		}
		if (alfanumerico(form.correo0.value,'@',1)==false){
			sele('correo0');
			foco('correo0');
			return false;
		}else{
			if (trim(form.correo0.value).length>0){
				if (email(form.correo0.value)==false){
					sele('correo0');
					foco('correo0');
					return false;
				}
			}
		}
		if (alfanumerico(form.correo.value,'@',1)==false){
			sele('correo');
			foco('correo');
			return false;
		}else{
			if (trim(form.correo.value).length>0){
				if (email(form.correo.value)==false){
					sele('correo');
					foco('correo');
					return false;
				}
			}
		}
		if (alfanumerico(form.correo2.value,'@',1)==false){
			sele('correo2');
			foco('correo2');
			return false;
		}else{
			if (trim(form.correo2.value).length>0){
				if (email(form.correo2.value)==false){
					sele('correo2');
					foco('correo2');
					return false;
				}
			}
		}
		return true;
	}

	function graba(){
		var form=document.formulario1;
		if (valida()==true){
			form.accion.value='grabar';
			form.submit();
		}
	}

	function validateDate(dateInput) {
	    var regex = /^\d{4}-\d{2}-\d{2}$/;
	    if (regex.test(dateInput)) {
	        var parts = dateInput.split("-");
	        var year = parseInt(parts[0], 10);
	        var month = parseInt(parts[1], 10);
	        var day = parseInt(parts[2], 10);
	        var date = new Date(year, month - 1, day);
	        if (date.getFullYear() === year && (date.getMonth() + 1) === month && date.getDate() === day) {
	            return true;
	        } else {
	            return false;
	        }
	    } else {
	        return false;
	    }
	}
	</script>
</head>
<body>
	<div class="popup-shell">
		<div class="hero">
			<div class="hero-badge"><i class="bi bi-arrow-left-right"></i> Popup activo de cesion</div>
			<h1 class="hero-title">Cesion de DTE</h1>
			<div class="hero-meta">
				<span class="hero-chip"><i class="bi bi-receipt"></i> Folio <?php echo h($nFolio); ?></span>
				<span class="hero-chip"><i class="bi bi-file-earmark-text"></i> Tipo <?php echo h($nTipoDTE); ?></span>
				<span class="hero-chip"><i class="bi bi-cash-stack"></i> Monto $ <?php echo h($montoDocumento); ?></span>
			</div>
		</div>

		<div class="card card-shell">
			<?php if ($mostrar_form) { ?>
			<div class="card-head bg-white">
				<h2 class="card-title">Datos de la cesion para el docto folio <?php echo h($nFolio); ?></h2>
				<p class="card-text">Se mantiene el mismo submit, nombres de campos, validaciones JavaScript y flujo del popup legacy.</p>
			</div>
			<div class="card-body bg-white">
				<form name="formulario1" action="" method="post">
					<input type="hidden" name="accion" value="" readonly />
					<input type="hidden" name="codigo" value="" readonly />
					<input type="hidden" name="xml_id" value="<?php echo h($xml_id); ?>" readonly />
					<input type="hidden" name="tipo_docu" value="<?php echo h($tipo_docu); ?>" readonly />

					<div class="row g-3">
						<div class="col-md-4">
							<label class="form-label cab_campo">Rut Cesionario<span class="req">*</span></label>
							<input type="text" name="rut" value="" class="campo form-control" maxlength="10" />
						</div>
						<div class="col-md-8">
							<label class="form-label cab_campo">Razon Social Cesionario<span class="req">*</span></label>
							<input type="text" name="razon_social" value="" class="campo form-control" maxlength="100" />
						</div>

						<div class="col-12">
							<label class="form-label cab_campo">Direccion Cesionario<span class="req">*</span></label>
							<input type="text" name="direccion" value="" maxlength="60" class="campo form-control" />
						</div>

						<div class="col-md-6">
							<label class="form-label cab_campo">Monto Cesion<span class="req">*</span></label>
							<input type="text" name="monto_cesion" value="" class="campo form-control" maxlength="10" />
							<input type="hidden" name="mnt" value="<?php echo h($nMontTot); ?>" readonly />
						</div>
						<div class="col-md-6 d-flex align-items-end">
							<div class="amount-pill"><i class="bi bi-currency-dollar"></i> Monto del documento: $ <?php echo h($montoDocumento); ?></div>
						</div>

						<div class="col-md-4">
							<label class="form-label cab_campo">Fecha Ult. Vencimiento<span class="req">*</span></label>
							<input type="text" name="fecha_vencimiento" placeholder="AAAA-MM-DD" class="campo form-control" maxlength="10" value="" />
							<div class="field-note mt-2">Formato obligatorio del flujo original.</div>
						</div>

						<div class="col-12">
							<label class="form-label cab_campo">Otras Condiciones<span class="req">*</span></label>
							<textarea name="otras_condiciones" id="otras_condiciones" class="campo form-control" rows="5" onKeyDown="cuenta(this,document.formulario1.numero,250);Textarea_Sin_Enter(event.keyCode,this.id);" onKeyUp="cuenta(this,document.formulario1.numero,250);Textarea_Sin_Enter(event.keyCode,this.id);"></textarea>
							<div class="d-flex flex-wrap justify-content-between align-items-center mt-2 gap-2">
								<span class="field-note">Contador legacy preservado.</span>
								<span class="field-note">Caract. Disp. <input type="text" class="texto form-control form-control-sm d-inline-block ms-1" value="250" readonly name="numero" size="3" style="width:70px;" /></span>
							</div>
						</div>

						<div class="col-md-4">
							<label class="form-label cab_campo">Correo Cesionario<span class="req">*</span></label>
							<input type="text" name="correo0" value="" class="campo form-control" maxlength="80" />
						</div>
						<div class="col-md-4">
							<label class="form-label cab_campo">Correo deudor<span class="req">*</span></label>
							<input type="text" name="correo" value="" class="campo form-control" maxlength="80" />
						</div>
						<div class="col-md-4">
							<label class="form-label cab_campo">Correo notificacion SII<span class="req">*</span></label>
							<input type="text" name="correo2" value="" class="campo form-control" maxlength="80" />
						</div>
					</div>

					<div class="form-note mt-3">Los datos marcados con * son de caracter obligatorios.</div>
					<div class="form-actions">
						<button type="button" name="save" class="boton btn btn-primary" onClick="Javascript:graba();"><i class="bi bi-save me-2"></i>Grabar</button>
						<button type="button" class="btn btn-outline-secondary" onclick="window.close();"><i class="bi bi-x-circle me-2"></i>Cerrar</button>
					</div>
				</form>
			</div>
			<?php } else { ?>
			<div class="card-body bg-white">
				<div class="state-card <?php echo h($stateClass); ?>">
					<div class="state-icon"><i class="bi <?php echo h($stateIcon); ?>"></i></div>
					<h2 class="state-title"><?php echo h($statusTitle); ?></h2>
					<p class="state-text"><?php echo h($statusText); ?></p>
					<div class="state-actions">
						<?php if ($statusLinkHref != "") { ?>
						<a href="<?php echo h($statusLinkHref); ?>" class="btn btn-primary"><i class="bi bi-download me-2"></i><?php echo h($statusLinkLabel); ?></a>
						<?php } ?>
						<button type="button" class="btn btn-outline-secondary" onclick="window.close();"><i class="bi bi-x-circle me-2"></i>Cerrar</button>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</body>
</html>