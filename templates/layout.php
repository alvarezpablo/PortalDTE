<?php
/**
 * PortalDTE - Layout Principal Moderno
 * Reemplaza los framesets por un diseño responsivo con Bootstrap 5
 */

// Verificar autenticación
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (trim($_SESSION["_COD_USU_SESS"]) == "" || trim($_SESSION["_COD_ROL_SESS"]) == "") {
    header("location: login.php");
    exit;
}

// Incluir configuración
$_NIVEL_RAIZ = true;
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/db_lib.php';

// Datos del usuario
$sNomUser = $_SESSION["_ALIAS_USU_SESS"] ?? '';
$sNomEmp = $_SESSION["_NOM_EMP_USU_SESS"] ?? '';
$sCodRol = $_SESSION["_COD_ROL_SESS"] ?? '';

// Función para generar el menú
function generarMenu($skins, $codRol, $codEmp, $rutEmp, $gpuerto, $emiteWeb) {
    $menu = [];
    
    // Recepción DTE
    $menu[] = [
        'id' => 'recepcion',
        'titulo' => 'Recepci&oacute;n DTE',
        'icon' => 'bi-inbox',
        'items' => [
            ['link' => 'factura/list_dte_recep_v3.php', 'text' => 'DTE Recibidos', 'icon' => 'bi-file-earmark-text'],
            ['link' => 'factura/list_dte_recep_v2.php', 'text' => 'DTE Recibidos (Antiguo)', 'icon' => 'bi-file-earmark-text']
        ]
    ];
    
    // Seguridad (solo roles 1 y 3)
    if ($codRol == "1" || $codRol == "3") {
        $segItems = [];
        if ($codRol == "1") {
            $segItems[] = ['link' => 'empresa/listempre.php', 'text' => 'Empresas', 'icon' => 'bi-building'];
            $segItems[] = ['link' => 'usuario/list_user.php', 'text' => 'Usuarios', 'icon' => 'bi-people'];
            $segItems[] = ['link' => 'reenvio/reenvio.php', 'text' => 'Reenv&iacute;o Masivo', 'icon' => 'bi-arrow-repeat'];
        }
        $segItems[] = ['link' => 'empresa/certificado.php', 'text' => 'Certificado Empresa', 'icon' => 'bi-shield-check'];
        $segItems[] = ['link' => 'empresa/licencia.php', 'text' => 'Licencia Empresa', 'icon' => 'bi-key'];
        $segItems[] = ['link' => 'empresa/uuid.php', 'text' => 'API Key', 'icon' => 'bi-code-square'];
        
        $menu[] = [
            'id' => 'seguridad',
            'titulo' => 'Seguridad',
            'icon' => 'bi-shield-lock',
            'items' => $segItems
        ];
    }
    
    // CAF
    $cafItems = [];
    if ($codRol == "1" || $codRol == "3") {
        $cafItems[] = ['link' => 'caf/form_caf_v2.php', 'text' => 'Cargar CAF', 'icon' => 'bi-upload'];
    }
    $cafItems[] = ['link' => 'caf/disp_caf_v2.php', 'text' => 'Consultar CAF', 'icon' => 'bi-search'];
    $cafItems[] = ['link' => 'caf/disp_caf.php', 'text' => 'Consultar CAF (Antiguo)', 'icon' => 'bi-search'];
    $menu[] = [
        'id' => 'caf',
        'titulo' => 'CAF',
        'icon' => 'bi-file-earmark-code',
        'items' => $cafItems
    ];
    
    // Emitir DTE (si tiene permiso)
    if ($emiteWeb == "1" || $codRol == "1") {
        $menu[] = [
            'id' => 'emitir',
            'titulo' => 'Emitir DTE',
            'icon' => 'bi-plus-circle',
            'items' => [
                ['link' => 'emitir/emitir.php?t=33', 'text' => 'Factura Electr&oacute;nica', 'icon' => 'bi-receipt'],
                ['link' => 'emitir/emitir.php?t=34', 'text' => 'Factura Exenta', 'icon' => 'bi-receipt'],
                ['link' => 'emitir/emitir.php?t=39', 'text' => 'Boleta Electr&oacute;nica', 'icon' => 'bi-receipt-cutoff'],
                ['link' => 'emitir/emitir.php?t=56', 'text' => 'Nota de D&eacute;bito', 'icon' => 'bi-file-minus'],
                ['link' => 'emitir/emitir.php?t=61', 'text' => 'Nota de Cr&eacute;dito', 'icon' => 'bi-file-plus'],
                ['link' => 'emitir/emitir.php?t=52', 'text' => 'Guía de Despacho', 'icon' => 'bi-truck']
            ]
        ];
    }
    
    // DTE Emitidos
    $dteItems = [
        ['link' => 'dte/list_dte_v3.php', 'text' => 'DTE Listado', 'icon' => 'bi-list-ul'],
        ['link' => 'dte/list_dte_v2.php', 'text' => 'DTE Listado (Antiguo)', 'icon' => 'bi-list-task']
    ];
    if ($codRol == "1" || $codRol == "3") {
        $dteItems[] = ['link' => 'exportXML/consulta_xml_exportado.php', 'text' => 'Descarga XML', 'icon' => 'bi-download'];
    }
    $menu[] = [
        'id' => 'dte_emitidos',
        'titulo' => 'DTE Emitidos',
        'icon' => 'bi-file-earmark-check',
        'items' => $dteItems
    ];
    
    // Libros
    $librosItems = [
        ['link' => 'libros/list_libro.php?sTipo=COMPRA', 'text' => 'Libros Compras', 'icon' => 'bi-journal-arrow-down'],
        ['link' => 'libros/list_libro.php?sTipo=VENTA', 'text' => 'Libros Ventas', 'icon' => 'bi-journal-arrow-up'],
        ['link' => 'libros/list_libro.php?sTipo=GUIA', 'text' => 'Libros Gu&iacute;a', 'icon' => 'bi-journal-text']
    ];
    if ($codRol == "1" || $codRol == "3") {
        $librosItems[] = ['link' => 'libros/form_libro.php', 'text' => 'Cargar Libros', 'icon' => 'bi-upload'];
    }
    $menu[] = [
        'id' => 'libros',
        'titulo' => 'Libros',
        'icon' => 'bi-journal-bookmark',
        'items' => $librosItems
    ];
    
    // Mantención
    $mantItems = [
        ['link' => 'mantencion/list_clie.php', 'text' => 'Clientes', 'icon' => 'bi-person-lines-fill']
    ];
    if ($codRol == "1") {
        $mantItems[] = ['link' => 'mantencion/form_cont_elec_v2.php', 'text' => 'Contribuyentes Electr&oacute;nicos', 'icon' => 'bi-people'];
        $mantItems[] = ['link' => 'mantencion/form_cont_elec.php', 'text' => 'Contribuyentes (Antiguo)', 'icon' => 'bi-people'];
        $mantItems[] = ['link' => 'mantencion/list_tip_doc.php', 'text' => 'Tipo Documentos', 'icon' => 'bi-file-text'];
        $mantItems[] = ['link' => 'mantencion/list_estado.php', 'text' => 'Estado Documentos', 'icon' => 'bi-clipboard-check'];
    }
    $mantItems[] = ['link' => 'sel_emp.php', 'text' => 'Cambiar Empresa', 'icon' => 'bi-arrow-left-right'];
    $mantItems[] = ['link' => 'mantencion/form_user_sii.php', 'text' => 'Act. Contacto SII', 'icon' => 'bi-person-badge'];
    
    $menu[] = [
        'id' => 'mantencion',
        'titulo' => 'Mantenci&oacute;n',
        'icon' => 'bi-gear',
        'items' => $mantItems
    ];
    
    return $menu;
}

// Obtener menú
$menuItems = generarMenu(
    $_SKINS ?? 'aqua',
    $sCodRol,
    $_SESSION["_COD_EMP_USU_SESS"] ?? '',
    $_SESSION["RUT_EMP"] ?? '',
    $_SESSION["_GPUERTO_"] ?? '',
    $_SESSION["_EMITE_WEB_"] ?? ''
);

// Página inicial por defecto
$defaultPage = 'main.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal OpenDTE - NUEVO 2024</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/app.css" rel="stylesheet">
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h4><i class="bi bi-file-earmark-text me-2"></i>OpenDTE</h4>
            </div>
            <nav class="sidebar-menu">
                <?php foreach ($menuItems as $category): ?>
                <div class="menu-category" id="cat-<?= $category['id'] ?>">
                    <div class="menu-category-title" onclick="toggleCategory('<?= $category['id'] ?>')">
                        <span><i class="bi <?= $category['icon'] ?> me-2"></i><?= $category['titulo'] ?></span>
                        <i class="bi bi-chevron-right arrow"></i>
                    </div>
                    <ul class="menu-items">
                        <?php foreach ($category['items'] as $item): ?>
                        <li>
                            <a href="<?= $item['link'] ?>" onclick="loadPage('<?= $item['link'] ?>'); return false;">
                                <i class="bi <?= $item['icon'] ?>"></i>
                                <?= $item['text'] ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>

                <!-- Logout -->
                <div class="menu-category">
                    <a href="logout.php" class="menu-category-title text-danger">
                        <span><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesi&oacute;n</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <!-- Header -->
            <header class="top-header">
                <div class="header-left">
                    <button class="toggle-sidebar" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    <h5 class="header-title d-none d-md-block">Portal de Facturaci&oacute;n Electr&oacute;nica</h5>
                </div>
                <div class="header-right">
                    <div class="user-info">
                        <div class="user-name"><i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($sNomUser) ?></div>
                        <div class="company-name"><i class="bi bi-building me-1"></i><?= htmlspecialchars($sNomEmp) ?></div>
                    </div>
                    <a href="logout.php" class="btn btn-logout d-none d-md-inline-block">
                        <i class="bi bi-box-arrow-right me-1"></i>Salir
                    </a>
                </div>
            </header>

            <!-- Content Frame -->
            <iframe id="contentFrame" class="content-frame" src="<?= htmlspecialchars($defaultPage) ?>"></iframe>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (actualizado) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // Toggle sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainContent').classList.toggle('expanded');
        }

        // Toggle menu category
        function toggleCategory(id) {
            const cat = document.getElementById('cat-' + id);
            cat.classList.toggle('open');
        }

        // Load page in iframe
        function loadPage(url) {
            document.getElementById('contentFrame').src = url;

            // Mark active link
            document.querySelectorAll('.menu-items a').forEach(a => a.classList.remove('active'));
            event.target.closest('a').classList.add('active');

            // Close sidebar on mobile
            if (window.innerWidth < 768) {
                document.getElementById('sidebar').classList.remove('show');
            }
        }

        // Mobile sidebar toggle
        document.querySelector('.toggle-sidebar').addEventListener('click', function() {
            if (window.innerWidth < 768) {
                document.getElementById('sidebar').classList.toggle('show');
            }
        });

        // Open first category by default
        document.addEventListener('DOMContentLoaded', function() {
            const firstCat = document.querySelector('.menu-category');
            if (firstCat) firstCat.classList.add('open');
        });
    </script>
</body>
</html>

