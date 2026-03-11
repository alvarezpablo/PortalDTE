<?php
include("../include/config.php");
include("../include/ver_aut.php");
include("../include/ver_aut_adm.php");
include("../include/db_lib.php");
include("../include/tables.php");

function h($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1'); }

$hoy = date("d-m-Y");
$conn = conn();
$sLinkActual = "mantencion/list_estado_boleta.php";

$sqlEstado = "SELECT COUNT(TIPO_DOCU) as cant,
CASE
  WHEN est_xdte = '0' THEN 'CARGADO'
  WHEN est_xdte = '1' THEN 'FIRMADO'
  WHEN est_xdte = '5' THEN 'EMPAQUETADO'
  WHEN est_xdte = '13' THEN 'ENVIADO SII'
  WHEN est_xdte = '29' THEN 'ACEPTADO SII'
  WHEN est_xdte = '77' THEN 'RECHAZADO SII'
  WHEN est_xdte = '45' THEN 'ACEPTADO CON REPARO SII'
ELSE
  'ESTADO ' || est_xdte || ' NO CONOCIDO'
END as estado
from xmldte where tipo_docu in (39,41) and est_xdte < 78 and fec_carg < CURRENT_DATE and ts >= to_date('2022-08-01','YYYY-MM-DD') group by est_xdte ORDER BY est_xdte";

$sqlEmpresa = "SELECT COUNT(TIPO_DOCU) as cant,
CASE
  WHEN est_xdte = '0' THEN 'CARGADO'
  WHEN est_xdte = '1' THEN 'FIRMADO'
  WHEN est_xdte = '5' THEN 'EMPAQUETADO'
  WHEN est_xdte = '13' THEN 'ENVIADO SII'
  WHEN est_xdte = '29' THEN 'ACEPTADO SII'
  WHEN est_xdte = '77' THEN 'RECHAZADO SII'
  WHEN est_xdte = '45' THEN 'ACEPTADO CON REPARO SII'
ELSE
  'ESTADO ' || est_xdte || ' NO CONOCIDO'
END as estado,
  (SELECT rs_empr FROM empresa WHERE codi_empr = xmldte.codi_empr) AS rs
from xmldte where tipo_docu in (39,41) and est_xdte < 78 and fec_carg < CURRENT_DATE and ts >= to_date('2022-08-01','YYYY-MM-DD') group by rs, est_xdte ORDER BY rs, est_xdte";

$resultEstado = $conn->selectLimit($sqlEstado, 5000, 5000 * $_NUM_PAG_ACT);
$resultEmpresa = $conn->selectLimit($sqlEmpresa, 5000, 5000 * $_NUM_PAG_ACT);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="shortcut icon" href="/favicon.ico">
    <title>Estado de Boletas - Portal DTE</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <base href="<?php echo h($_LINK_BASE); ?>" />
    <script type="text/javascript" src="javascript/common.js"></script>
    <script type="text/javascript" src="javascript/msg.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body{background:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f2937}
        .page-shell{max-width:1180px;margin:0 auto;padding:1rem}
        .page-hero{background:linear-gradient(135deg,#1e293b 0%,#0b5ed7 100%);color:#fff;border-radius:18px;padding:1.5rem;box-shadow:0 14px 34px rgba(15,23,42,.18);margin-bottom:1.25rem}
        .hero-icon{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:1.4rem}
        .hero-pills{display:flex;flex-wrap:wrap;gap:.75rem}.hero-pill{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:999px;padding:.45rem .85rem;font-size:.82rem}
        .card{border:1px solid rgba(15,23,42,.06);border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden;margin-bottom:1rem}
        .card-header{background:#1e293b;color:#fff;padding:.9rem 1rem}.card-header .small{color:rgba(255,255,255,.75)}
        .table thead th{background:#1e293b;color:#fff;white-space:nowrap;vertical-align:middle}.table tbody td{vertical-align:middle}.table tbody tr:hover{background:#f8fbff}
        .metric-badge{display:inline-flex;align-items:center;justify-content:center;min-width:3.5rem;padding:.35rem .75rem;border-radius:999px;background:#e2e8f0;color:#0f172a;font-weight:700}
        .status-text{font-weight:600;color:#0f172a}.company-text{color:#475569}
        .empty-state{padding:3.5rem 1rem;text-align:center;color:#6b7280}.empty-state i{font-size:3rem}
        #loaderContainer{position:fixed;inset:0;background:rgba(15,23,42,.3);z-index:1050}#loaderContainerWH{vertical-align:middle;text-align:center}#loader{display:inline-block;background:#fff;border-radius:14px;padding:1rem 1.25rem;box-shadow:0 12px 28px rgba(15,23,42,.18)}
        @media (max-width:767.98px){.page-shell{padding:.75rem}.page-hero{padding:1.1rem}}
    </style>
    <script type="text/javascript">
        function _body_onload(){ try{SetContext('clients');setActiveButtonByName('clients');}catch(e){} try{loff();}catch(e){} }
        function _body_onunload(){ try{lon();}catch(e){} }
        var opt_no_frames = false, opt_integrated_mode = false;
    </script>
</head>
<body onload="_body_onload();" onunload="_body_onunload();" id="mainCP">
    <a href="#" name="top" id="top"></a>
    <table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><p class="mb-0"><img src="skins/<?php echo h($_SKINS); ?>/icons/loading.gif" height="32" width="32" alt="" class="me-2" /><strong>Por favor espere.<br>Cargando ...</strong></p></div></td></tr></table>

    <div class="page-shell">
        <div class="page-hero">
            <div class="row g-3 align-items-center">
                <div class="col-lg-7">
                    <div class="d-flex align-items-start gap-3">
                        <div class="hero-icon"><i class="bi bi-receipt"></i></div>
                        <div>
                            <h1 class="h3 mb-2">Estado de Boletas por Empresas</h1>
                            <p class="mb-0 opacity-75">Vista consolidada del estado de las boletas, manteniendo la consulta legacy, el alcance temporal y el desglose por empresa.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-pills justify-content-lg-end">
                        <span class="hero-pill"><i class="bi bi-calendar-event me-1"></i>Excluye boletas del d&iacute;a: <?php echo h($hoy); ?></span>
                        <span class="hero-pill"><i class="bi bi-ticket-perforated me-1"></i>S&oacute;lo boletas</span>
                        <span class="hero-pill"><i class="bi bi-clock-history me-1"></i>Desde 01-08-2022</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-start gap-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
            <div>
                <div class="fw-semibold">Alcance del reporte</div>
                <div>Este informe no incluye las boletas emitidas el d&iacute;a de hoy <strong><?php echo h($hoy); ?></strong> e incluye s&oacute;lo boletas desde el 01-08-2022, igual que en la versi&oacute;n legacy.</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                <div>
                    <div class="fw-semibold"><i class="bi bi-collection me-2"></i>Agrupado por Estado</div>
                    <div class="small mt-1">Resumen general del volumen de boletas por estado.</div>
                </div>
                <span class="badge rounded-pill text-bg-light text-primary-emphasis">Tope legacy: 5.000 registros</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="22%">Cantidad</th>
                                <th width="78%">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!$resultEstado->EOF): ?>
                                <?php while(!$resultEstado->EOF): ?>
                                    <?php $nCant = trim($resultEstado->fields["cant"]); $sEst = trim($resultEstado->fields["estado"]); ?>
                                    <tr>
                                        <td><span class="metric-badge"><?php echo h($nCant); ?></span></td>
                                        <td><span class="status-text"><?php echo h($sEst); ?></span></td>
                                    </tr>
                                    <?php $resultEstado->MoveNext(); ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <h5 class="mt-3">No hay datos para mostrar</h5>
                                            <p class="mb-0">La consulta de boletas no devolvi&oacute; registros en este momento.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                <div>
                    <div class="fw-semibold"><i class="bi bi-building me-2"></i>Agrupado por Estado y Empresa</div>
                    <div class="small mt-1">Desglose por empresa manteniendo el mismo origen de datos y el filtro temporal legacy.</div>
                </div>
                <span class="badge rounded-pill text-bg-light text-primary-emphasis">Consulta SQL sin cambios</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="18%">Cantidad</th>
                                <th width="32%">Estado</th>
                                <th width="50%">Empresa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!$resultEmpresa->EOF): ?>
                                <?php while(!$resultEmpresa->EOF): ?>
                                    <?php $nCant = trim($resultEmpresa->fields["cant"]); $sEst = trim($resultEmpresa->fields["estado"]); $sRs = trim($resultEmpresa->fields["rs"]); ?>
                                    <tr>
                                        <td><span class="metric-badge"><?php echo h($nCant); ?></span></td>
                                        <td><span class="status-text"><?php echo h($sEst); ?></span></td>
                                        <td><span class="company-text"><?php echo h($sRs); ?></span></td>
                                    </tr>
                                    <?php $resultEmpresa->MoveNext(); ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state">
                                            <i class="bi bi-buildings"></i>
                                            <h5 class="mt-3">No hay estados agrupados por empresa</h5>
                                            <p class="mb-0">La consulta de boletas no devolvi&oacute; filas para el desglose por empresa.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>