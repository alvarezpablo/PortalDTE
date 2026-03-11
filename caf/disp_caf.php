<?php
  include("../include/config.php");
  include("../include/ver_aut.php");
  include("../include/ver_emp_adm.php");
  include("../include/db_lib.php");

  function h($value){
    return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
  }

  function fetchCafRows($conn, $nCodEmp, $estado){
    $rows = array();
    if($nCodEmp === "") return $rows;

    $sql = "SELECT C.ini_num_caf, C.ter_num_caf, C.fol_disp_caf, D.desc_tipo_docu, (C.ter_num_caf - C.fol_disp_caf) as disp, C.estado ";
    $sql .= "FROM caf C, dte_tipo D ";
    $sql .= "WHERE D.tipo_docu = C.tipo_docu AND estado = " . (int)$estado . " AND C.codi_empr = '" . str_replace("'", "''", $nCodEmp) . "'";
    $result = rCursor($conn, $sql);

    while(!$result->EOF){
      $rows[] = array(
        'desc_tipo_docu' => trim($result->fields["desc_tipo_docu"]),
        'ini_num_caf' => trim($result->fields["ini_num_caf"]),
        'ter_num_caf' => trim($result->fields["ter_num_caf"]),
        'estado' => trim($result->fields["estado"])
      );
      $result->MoveNext();
    }

    return $rows;
  }

  $conn = conn();
  $nCodEmp = trim(isset($_SESSION["_COD_EMP_USU_SESS"]) ? (string)$_SESSION["_COD_EMP_USU_SESS"] : "");
  $companyName = trim(isset($_SESSION["_NOM_EMP_USU_SESS"]) ? (string)$_SESSION["_NOM_EMP_USU_SESS"] : "Empresa actual");
  $vigentes = fetchCafRows($conn, $nCodEmp, 1);
  $vencidos = fetchCafRows($conn, $nCodEmp, 2);
  $homeHref = "main.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <link rel="shortcut icon" href="/favicon.ico">
  <title>OpenB</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <base href="<?php echo h($_LINK_BASE); ?>" />
  <script type="text/javascript" src="javascript/common.js"></script>
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/general.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/custom.css">
  <link rel="stylesheet" type="text/css" href="skins/<?php echo h($_SKINS); ?>/css/main/layout.css">
  <link rel="stylesheet" type="text/nonsense" href="skins/<?php echo h($_SKINS); ?>/css/misc.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
    .page-shell{max-width:1140px;margin:0 auto;padding:1rem}
    .page-hero{background:linear-gradient(135deg,#0f172a 0%,#1d4ed8 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(15,23,42,.18);margin-bottom:1.25rem}
    .hero-icon{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:1.4rem}
    .card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden}
    .card-header{background:#0f172a;color:#fff;padding:1rem 1.25rem}
    .status-chip{display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .75rem;border-radius:999px;background:#f8fafc;border:1px solid #dbe7f3;font-size:.82rem;color:#334155}
    #loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}
    #loaderContainerWH{vertical-align:middle;text-align:center}
    #loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
  </style>
  <script type="text/javascript">
    function _body_onload(){
      try{loff();}catch(e){}
      try{SetContext('cl_ed');}catch(e){}
    }
    function _body_onunload(){ try{lon();}catch(e){} }
  </script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
  <a href="#" name="top" id="top"></a>
  <table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2"/><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>

  <div class="page-shell">
    <div class="page-hero">
      <div class="d-flex align-items-start gap-3">
        <div class="hero-icon"><i class="bi bi-journal-check"></i></div>
        <div>
          <h1 class="h3 mb-2">CAF disponibles</h1>
          <p class="mb-0 opacity-75">Consulta de CAF vigentes y vencidos para la empresa activa, manteniendo la consulta legacy.</p>
        </div>
      </div>
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <div class="d-flex flex-wrap gap-2">
        <div class="status-chip"><i class="bi bi-house-door"></i><a href="<?php echo h($_LINK_BASE . $homeHref); ?>" class="text-decoration-none">Home</a></div>
        <div class="status-chip"><i class="bi bi-building"></i><?php echo h($companyName); ?></div>
      </div>
      <a href="<?php echo h($_LINK_BASE . $homeHref); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-1"></i>Volver</a>
    </div>

    <div class="row g-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <div class="fw-semibold"><i class="bi bi-check-circle me-2"></i>CAF vigentes</div>
            <span class="badge rounded-pill text-bg-light text-success-emphasis"><?php echo count($vigentes); ?> registro(s)</span>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Documento</th>
                    <th class="text-end">Desde</th>
                    <th class="text-end">Hasta</th>
                  </tr>
                </thead>
                <tbody>
<?php if(count($vigentes) > 0){ ?>
<?php foreach($vigentes as $row){ ?>
                  <tr>
                    <td><?php echo h($row['desc_tipo_docu']); ?></td>
                    <td class="text-end"><?php echo h($row['ini_num_caf']); ?></td>
                    <td class="text-end"><?php echo h($row['ter_num_caf']); ?></td>
                  </tr>
<?php } ?>
<?php }else{ ?>
                  <tr>
                    <td colspan="3" class="text-center text-muted py-4">No hay CAF vigentes para la empresa actual.</td>
                  </tr>
<?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <div class="fw-semibold"><i class="bi bi-x-circle me-2"></i>CAF vencidos</div>
            <span class="badge rounded-pill text-bg-light text-danger-emphasis"><?php echo count($vencidos); ?> registro(s)</span>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Documento</th>
                    <th class="text-end">Desde</th>
                    <th class="text-end">Hasta</th>
                  </tr>
                </thead>
                <tbody>
<?php if(count($vencidos) > 0){ ?>
<?php foreach($vencidos as $row){ ?>
                  <tr class="table-danger">
                    <td><?php echo h($row['desc_tipo_docu']); ?></td>
                    <td class="text-end"><?php echo h($row['ini_num_caf']); ?></td>
                    <td class="text-end"><?php echo h($row['ter_num_caf']); ?></td>
                  </tr>
<?php } ?>
<?php }else{ ?>
                  <tr>
                    <td colspan="3" class="text-center text-muted py-4">No hay CAF vencidos para la empresa actual.</td>
                  </tr>
<?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
      <a href="<?php echo h($_LINK_BASE . $homeHref); ?>" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i>Aceptar</a>
    </div>
  </div>

  <script type="text/javascript">
    try{ lsetup(); }catch(e){}
  </script>
</body>
</html>