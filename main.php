<?php
include("include/config.php");
$_NIVEL_RAIZ = true;
include("include/ver_aut.php");

$sNomUser = isset($_SESSION["_ALIAS_USU_SESS"]) ? trim((string)$_SESSION["_ALIAS_USU_SESS"]) : "";
$sNomEmp = isset($_SESSION["_NOM_EMP_USU_SESS"]) ? trim((string)$_SESSION["_NOM_EMP_USU_SESS"]) : "";

if (!function_exists('h')) {
    function h($value)
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="ISO-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inicio - PortalDTE</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
  :root { color-scheme: light; }
  body { background: #f4f7fb; color: #16324f; }
  .page-shell { max-width: 980px; margin: 0 auto; padding: 24px 16px 32px; }
  .topbar, .panel, .notice-card { background: #fff; border: 1px solid #dbe7f3; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 31, 63, 0.08); }
  .topbar { padding: 20px 22px; margin-bottom: 20px; }
  .topbar-title { color: #001f3f; font-size: 1.35rem; font-weight: 700; margin: 0; }
  .topbar-meta, .notice-date { color: #5b7088; font-size: .92rem; }
  .topbar-chip { display: inline-flex; align-items: center; gap: 8px; background: #eef4fb; color: #0b5ed7; border-radius: 999px; padding: 6px 12px; font-size: .88rem; font-weight: 600; }
  .panel { padding: 18px 20px; margin-bottom: 18px; }
  .panel-note { color: #5b7088; margin: 6px 0 0; }
  .notice-card { padding: 18px 20px; margin-bottom: 16px; }
  .notice-title { color: #001f3f; font-size: 1.05rem; font-weight: 700; margin-bottom: 10px; }
  .notice-card p:last-child { margin-bottom: 0; }
  .notice-link { font-weight: 600; }
</style>
</head>
<body>
  <div class="page-shell">
    <div class="topbar">
      <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
        <div>
          <p class="topbar-meta mb-2"><i class="bi bi-house-door me-2"></i>Panel de inicio</p>
          <h1 class="topbar-title">Noticias y avisos operativos</h1>
          <p class="panel-note">Pantalla inicial del portal con informaci&oacute;n relevante para el uso diario de la plataforma.</p>
        </div>
        <div class="d-flex flex-column gap-2 align-items-lg-end">
          <?php if ($sNomUser !== ''): ?><span class="topbar-chip"><i class="bi bi-person"></i><?php echo h($sNomUser); ?></span><?php endif; ?>
          <?php if ($sNomEmp !== ''): ?><span class="topbar-chip"><i class="bi bi-building"></i><?php echo h($sNomEmp); ?></span><?php endif; ?>
        </div>
      </div>
    </div>

    <div class="panel">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
          <strong class="d-block text-primary-emphasis"><i class="bi bi-megaphone me-2"></i>Comunicaciones vigentes</strong>
          <p class="panel-note">Se conserva el contenido informativo existente, mejorando s&oacute;lo su presentaci&oacute;n visual.</p>
        </div>
        <span class="topbar-chip"><i class="bi bi-info-circle"></i>Vista informativa</span>
      </div>
    </div>

    <article class="notice-card">
      <div class="notice-date mb-2">21-2-2025</div>
      <h2 class="notice-title">Migraci&oacute;n de Infraestructura a Oracle Cloud</h2>
      <p>Informamos que entre el <strong>21 de marzo a las 23:00 hrs</strong> y el <strong>23 de marzo</strong> se realizar&aacute; la migraci&oacute;n de toda la operaci&oacute;n a Oracle Cloud.</p>
      <p>Por favor revise los detalles en la siguiente carta: <a class="notice-link" href="https://objectstorage.sa-santiago-1.oraclecloud.com/n/axbdf1lh9yzq/b/Documentos/o/Carta%20Migraci%C3%B3n%2021-2-2025.pdf" target="_blank" rel="noopener noreferrer">Carta de Migraci&oacute;n</a>.</p>
    </article>

    <article class="notice-card">
      <div class="notice-date mb-2">3-1-2025</div>
      <h2 class="notice-title">Mantenci&oacute;n de Respaldos por 8 a&ntilde;os</h2>
      <p>Durante el a&ntilde;o 2025 mantendremos los respaldos por 8 a&ntilde;os de los datos hist&oacute;ricos.</p>
    </article>

    <article class="notice-card mb-0">
      <div class="notice-date mb-2">20-10-2024</div>
      <h2 class="notice-title">Eliminaci&oacute;n de Datos Antiguos</h2>
      <p>Ya est&aacute; disponible la descarga de los archivos hist&oacute;ricos; se van a comenzar a generar esta semana, y el link de descarga se encuentra en: <strong>DTE Emitidos</strong> -&gt; <strong>Descarga XML</strong>.</p>
      <p>Si requiere que se mantengan por tiempo superior contacte a <a class="notice-link" href="mailto:soporte@opendte.com">soporte@opendte.com</a>.</p>
    </article>
  </div>
</body>
</html>

