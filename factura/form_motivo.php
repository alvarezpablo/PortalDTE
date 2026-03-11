<?php
  include("../include/config.php");

  function h($value){
    return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
  }

  function rq($key){
    return isset($_GET[$key]) ? trim((string)$_GET[$key]) : "";
  }

  function selectedAttr($value, $current){
    return ((string)$value === (string)$current) ? ' selected="selected"' : "";
  }

  $sEstado = rq("s");
  $sTipFac = rq("sTipFac");
  $nMotIvaNoRec = rq("nMotIvaNoRec");
  $bAceptado = ($sEstado === "ACEPTADO");
  $pageTitle = ($sEstado !== "") ? "Tipo de DTE y Motivo - " . $sEstado : "Tipo de DTE y Motivo";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title><?php echo h($pageTitle); ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <base href="<?php echo h($_LINK_BASE); ?>" />
  <script type="text/javascript" src="javascript/funciones.js"></script>
  <script type="text/javascript" src="javascript/common.js"></script>
  <script type="text/javascript" src="javascript/msg.js"></script>
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/general.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/custom.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/layout.css">
  <link rel="stylesheet" type="text/nonsense" href="skins/<?php echo h($_SKINS); ?>/css/misc.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body{background:#e9eef5;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
    .popup-shell{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.25rem}
    .popup-card{width:100%;max-width:760px;border:0;border-radius:18px;overflow:hidden;box-shadow:0 18px 40px rgba(15,23,42,.16)}
    .popup-head{background:linear-gradient(135deg,#0f172a 0%,#1d4ed8 100%);color:#fff;padding:1.25rem 1.5rem}
    .popup-icon{width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:1.25rem}
    .section-card{background:#f8fafc;border:1px solid #dbe7f3;border-radius:14px;padding:1rem}
  </style>
  <script type="text/javascript">
    function enableDisable(obj, estado){
      if(obj){ obj.disabled = estado; }
    }

    function checkea(obj){
      enableDisable(document._FFORM.sRecinto, !obj.checked);
      enableDisable(document._FFORM.sFirma, !obj.checked);
    }

    function chkRespuesta(obj){
      enableDisable(document._FFORM.sRespuesta, !obj.checked);
      enableDisable(document._FFORM.sGlosa, !obj.checked);
    }

    function validaAcuse(){
      var F = document._FFORM;

      if(vacio(F.sRecinto.value, _MSG_ING_RECINTO) == false){
        F.sRecinto.focus();
        return false;
      }

      if(rut(F.sFirma.value, _MSG_FIRMA_RECINTO) == false){
        F.sFirma.focus();
        return false;
      }

      if(confirm(_MSG_ACEPTA_ACUSE_RECIBO))
        return true;
      else
        return false;
    }

    function valida(){
      var F = document._FFORM;
      var D = opener.document._FENV;
      var sTipFac = F.sTipFac.options[F.sTipFac.selectedIndex].value;
      var entra = true;

      if(sTipFac == "IVANORECUPER" && F.nMotIvaNoRec.value == ""){
        alert("Seleccione el motivo de no recuperar el iva.");
        F.nMotIvaNoRec.focus();
        return false;
      }

      if(!confirm(_MSG_ACEPTA_DTE))
        return false;

<?php if($bAceptado){ ?>
      if(F.nGeneraAcuse && F.nGeneraAcuse.checked == true){
        entra = validaAcuse();
        D.sRecinto.value = F.sRecinto.value;
        D.sFirma.value = F.sFirma.value;
        D.nGeneraAcuse.value = 1;
      }
      else{
        D.sRecinto.value = "";
        D.sFirma.value = "";
        D.nGeneraAcuse.value = "";
      }
<?php } ?>

      if(F.nAprobacion.checked == true){
        if((F.sRespuesta.value == "1") || (F.sRespuesta.value == "2")){
          if(F.sGlosa.value == ""){
            alert("Ingrese Motivo");
            F.sGlosa.focus();
            return false;
          }
        }

        D.sRespuesta.value = F.sRespuesta.value;
        D.sGlosa.value = F.sGlosa.value;
        D.nAprobacion.value = 1;
      }
      else{
        D.sRespuesta.value = "";
        D.sGlosa.value = "";
        D.nAprobacion.value = "";
      }

      if(entra == true){
        D.sTipFac.value = sTipFac;
        if(sTipFac == "IVANORECUPER")
          D.nMotIvaNoRec.value = F.nMotIvaNoRec.options[F.nMotIvaNoRec.selectedIndex].value;
        else
          D.nMotIvaNoRec.value = "";

        D.submit();
        window.close();
      }

      return false;
    }

    function cerrar(){
    }

    function cargar(){
      if(document._FFORM.nGeneraAcuse)
        document._FFORM.nGeneraAcuse.checked = false;

      enableDisable(document._FFORM.sRecinto, true);
      enableDisable(document._FFORM.sFirma, true);
      enableDisable(document._FFORM.sRespuesta, true);
      enableDisable(document._FFORM.sGlosa, true);
    }
  </script>
</head>
<body id="mainCP" onload="cargar();" onunload="cerrar();" class="visibilityAdminMode">
  <div class="popup-shell">
    <div class="card popup-card">
      <div class="popup-head">
        <div class="d-flex align-items-start gap-3">
          <div class="popup-icon"><i class="bi bi-ui-checks-grid"></i></div>
          <div>
            <h1 class="h4 mb-1">Categorizar DTE</h1>
            <p class="mb-0 opacity-75">Mantiene el flujo popup legacy y la escritura sobre <strong>opener.document._FENV</strong>.</p>
          </div>
        </div>
      </div>
      <div class="card-body p-4 bg-white">
        <form name="_FFORM" method="post" action="" onsubmit="return false;">
          <div class="row g-4">
            <div class="col-12">
              <div class="section-card">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="sTipFac" class="form-label fw-semibold">Tipo Factura</label>
                    <select name="sTipFac" id="sTipFac" class="form-select">
                      <option value="DELGIRO"<?php echo selectedAttr("DELGIRO", $sTipFac); ?>>Factura del Giro</option>
                      <option value="ACTFIJO"<?php echo selectedAttr("ACTFIJO", $sTipFac); ?>>Factura Activo Fijo</option>
                      <option value="IVAUSOCOMUN"<?php echo selectedAttr("IVAUSOCOMUN", $sTipFac); ?>>Factura IVA uso Comun</option>
                      <option value="IVANORECUPER"<?php echo selectedAttr("IVANORECUPER", $sTipFac); ?>>Factura IVA no Recuperable</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="nMotIvaNoRec" class="form-label fw-semibold">Motivo</label>
                    <select name="nMotIvaNoRec" id="nMotIvaNoRec" class="form-select">
                      <option value="">Aplica a IVA no Recuperable</option>
                      <option value="1"<?php echo selectedAttr("1", $nMotIvaNoRec); ?>>Operaciones no gravados o exentas.</option>
                      <option value="2"<?php echo selectedAttr("2", $nMotIvaNoRec); ?>>Facturas fuera de plazo.</option>
                      <option value="3"<?php echo selectedAttr("3", $nMotIvaNoRec); ?>>Gastos rechazados</option>
                      <option value="4"<?php echo selectedAttr("4", $nMotIvaNoRec); ?>>Entregas gratuitas</option>
                      <option value="9"<?php echo selectedAttr("9", $nMotIvaNoRec); ?>>Otros</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

<?php if($bAceptado){ ?>
            <div class="col-12">
              <div class="section-card">
                <div class="form-check form-switch mb-3">
                  <input class="form-check-input" type="checkbox" name="nGeneraAcuse" id="nGeneraAcuse" value="1" onclick="checkea(this);">
                  <label class="form-check-label fw-semibold" for="nGeneraAcuse">Genera Acuse de recibo de Mercaderia</label>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="sRecinto" class="form-label">Recinto</label>
                    <input type="text" name="sRecinto" id="sRecinto" maxlength="80" size="25" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label for="sFirma" class="form-label">Rut Firma Recepcion Mercaderia</label>
                    <input type="text" name="sFirma" id="sFirma" maxlength="10" size="10" class="form-control">
                    <div class="form-text">Ej: 11111111-1</div>
                  </div>
                </div>
              </div>
            </div>
<?php } ?>

            <div class="col-12">
              <div class="section-card">
                <div class="form-check form-switch mb-3">
                  <input class="form-check-input" type="checkbox" name="nAprobacion" id="nAprobacion" value="1" onclick="chkRespuesta(this);">
                  <label class="form-check-label fw-semibold" for="nAprobacion">Genera Respuesta Comercial</label>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="sRespuesta" class="form-label">Estado</label>
                    <select name="sRespuesta" id="sRespuesta" class="form-select">
<?php if($bAceptado){ ?>
                      <option value="0">DTE ACEPTADO OK</option>
                      <option value="1">DTE ACEPTADO con Discrepancia</option>
<?php } else { ?>
                      <option value="2">DTE Rechazado</option>
<?php } ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="sGlosa" class="form-label">Glosa rechazo o Discrepancia</label>
                    <input type="text" name="sGlosa" id="sGlosa" maxlength="80" size="25" class="form-control">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-primary" onclick="valida();"><i class="bi bi-send me-1"></i>Enviar</button>
            <button type="button" class="btn btn-outline-secondary" onclick="window.close();"><i class="bi bi-x-circle me-1"></i>Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>