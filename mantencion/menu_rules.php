<?php 
/**
 * Reglas de Men&uacute; - Administraci&oacute;n de visibilidad de opciones
 * PortalDTE - Bootstrap 5
 */
include("../include/config.php");  
include("../include/ver_aut.php");      
include("../include/ver_aut_adm.php");        
include("../include/db_lib.php"); 

// Solo administradores (rol 1)
if($_SESSION["_COD_ROL_SESS"] != "1") {
    header("location: ../index_new.php");
    exit;
}

// Obtener datos de sesi&oacute;n para mostrar valores actuales
$codEmp = $_SESSION["_COD_EMP_USU_SESS"] ?? '';
$rutEmp = $_SESSION["RUT_EMP"] ?? '';
$gpuerto = $_SESSION["_GPUERTO_"] ?? '';
$emiteWeb = $_SESSION["_EMITE_WEB_"] ?? '';
$codRol = $_SESSION["_COD_ROL_SESS"] ?? '';

// Definir las reglas actuales del men&uacute;
$menuRules = [
    [
        'menu' => 'Recepci&oacute;n DTE',
        'opcion' => 'DTE Recibidos',
        'condicion' => 'Todos los usuarios',
        'descripcion' => 'Visible para todos los usuarios autenticados',
        'variables' => '-',
        'tipo' => 'siempre'
    ],
    [
        'menu' => 'Seguridad',
        'opcion' => 'Empresas, Usuarios, Reenv&iacute;o Masivo',
        'condicion' => '$codRol == "1"',
        'descripcion' => 'Solo visible para Administradores',
        'variables' => '_COD_ROL_SESS = ' . $codRol,
        'tipo' => 'rol'
    ],
    [
        'menu' => 'Seguridad',
        'opcion' => 'Certificado, Licencia, API Key',
        'condicion' => '$codRol == "1" || $codRol == "3"',
        'descripcion' => 'Administradores y Administradores de Empresa',
        'variables' => '_COD_ROL_SESS = ' . $codRol,
        'tipo' => 'rol'
    ],
    [
        'menu' => 'Consorcio',
        'opcion' => 'Carga Boletas',
        'condicion' => '$codEmp == "85"',
        'descripcion' => 'Solo visible para empresa ID 85 (Consorcio)',
        'variables' => '_COD_EMP_USU_SESS = ' . $codEmp,
        'tipo' => 'empresa'
    ],
    [
        'menu' => 'CAF',
        'opcion' => 'Cargar CAF',
        'condicion' => '$codRol == "1" || $codRol == "3"',
        'descripcion' => 'Administradores y Administradores de Empresa',
        'variables' => '_COD_ROL_SESS = ' . $codRol,
        'tipo' => 'rol'
    ],
    [
        'menu' => 'Carga DTE',
        'opcion' => 'Carga Excel DTE, Reenviar DTE',
        'condicion' => '$gpuerto == "1" || $codRol == "1"',
        'descripcion' => 'Grupo Puerto o Administradores',
        'variables' => '_GPUERTO_ = ' . ($gpuerto ?: '(vac&iacute;o)'),
        'tipo' => 'especial'
    ],
    [
        'menu' => 'VGM Emite DTE',
        'opcion' => 'Carga Excel, Excel Softland, Reenviar Email',
        'condicion' => '$rutEmp IN [77648628, 77648624, 77239803] || $codRol == "1"',
        'descripcion' => 'RUTs espec&iacute;ficos de VGM o Administradores',
        'variables' => 'RUT_EMP = ' . ($rutEmp ?: '(vac&iacute;o)'),
        'tipo' => 'rut'
    ],
    [
        'menu' => 'Emitir DTE',
        'opcion' => 'Facturas, Boletas, Notas, Gu&iacute;as',
        'condicion' => '$emiteWeb == "1" || $codRol == "1"',
        'descripcion' => 'Usuarios con permiso de emisi&oacute;n o Administradores',
        'variables' => '_EMITE_WEB_ = ' . ($emiteWeb ?: '(vac&iacute;o)'),
        'tipo' => 'permiso'
    ],
    [
        'menu' => 'DTE Emitidos',
        'opcion' => 'Descarga XML',
        'condicion' => '$codRol == "1" || $codRol == "3"',
        'descripcion' => 'Administradores y Administradores de Empresa',
        'variables' => '_COD_ROL_SESS = ' . $codRol,
        'tipo' => 'rol'
    ],
    [
        'menu' => 'DTE Emitidos',
        'opcion' => 'DTE No Enviado a SII',
        'condicion' => '$codEmp IN [72,73,70,74,151,318,71]',
        'descripcion' => 'Solo para empresas espec&iacute;ficas',
        'variables' => '_COD_EMP_USU_SESS = ' . $codEmp,
        'tipo' => 'empresa'
    ],
    [
        'menu' => 'Libros',
        'opcion' => 'Cargar Libros',
        'condicion' => '$codRol == "1" || $codRol == "3"',
        'descripcion' => 'Administradores y Administradores de Empresa',
        'variables' => '_COD_ROL_SESS = ' . $codRol,
        'tipo' => 'rol'
    ],
    [
        'menu' => 'Mantenci&oacute;n',
        'opcion' => 'Contribuyentes, Tipo Docs, Estados',
        'condicion' => '$codRol == "1"',
        'descripcion' => 'Solo Administradores',
        'variables' => '_COD_ROL_SESS = ' . $codRol,
        'tipo' => 'rol'
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reglas de Men&uacute; - PortalDTE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .badge-rol { background-color: #0d6efd; }
        .badge-empresa { background-color: #198754; }
        .badge-rut { background-color: #6f42c1; }
        .badge-permiso { background-color: #fd7e14; }
        .badge-especial { background-color: #dc3545; }
        .badge-siempre { background-color: #6c757d; }
        .variable-box { 
            background: #e9ecef; 
            padding: 4px 8px; 
            border-radius: 4px; 
            font-family: monospace;
            font-size: 0.85em;
        }
        .condition-code {
            background: #fff3cd;
            padding: 4px 8px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.85em;
        }
    </style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="bi bi-shield-lock"></i> Reglas de Men&uacute;</h2>
            <p class="text-muted">Administraci&oacute;n de visibilidad de opciones del men&uacute; seg&uacute;n roles, empresas y permisos.</p>
        </div>
    </div>

    <!-- Info de sesión actual -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-info-circle"></i> Variables de Sesi&oacute;n Actuales
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2"><strong>Rol:</strong> <span class="variable-box"><?php echo $codRol; ?></span></div>
                        <div class="col-md-2"><strong>Empresa ID:</strong> <span class="variable-box"><?php echo $codEmp; ?></span></div>
                        <div class="col-md-3"><strong>RUT Empresa:</strong> <span class="variable-box"><?php echo $rutEmp ?: '(vac&iacute;o)'; ?></span></div>
                        <div class="col-md-2"><strong>GPuerto:</strong> <span class="variable-box"><?php echo $gpuerto ?: '0'; ?></span></div>
                        <div class="col-md-3"><strong>Emite Web:</strong> <span class="variable-box"><?php echo $emiteWeb ?: '0'; ?></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leyenda de tipos -->
    <div class="row mb-3">
        <div class="col-12">
            <span class="badge badge-rol bg-primary me-2">Rol</span>
            <span class="badge badge-empresa bg-success me-2">Empresa</span>
            <span class="badge badge-rut bg-purple me-2" style="background-color:#6f42c1">RUT</span>
            <span class="badge badge-permiso bg-warning text-dark me-2">Permiso</span>
            <span class="badge badge-especial bg-danger me-2">Especial</span>
            <span class="badge badge-siempre bg-secondary me-2">Siempre</span>
        </div>
    </div>

    <!-- Tabla de reglas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-list-check"></i> Reglas de Visibilidad Actuales
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:15%">Men&uacute;</th>
                                    <th style="width:20%">Opci&oacute;n(es)</th>
                                    <th style="width:8%">Tipo</th>
                                    <th style="width:20%">Condici&oacute;n</th>
                                    <th style="width:22%">Descripci&oacute;n</th>
                                    <th style="width:15%">Valor Actual</th>
                                </tr>
                            </thead>
                            <tbody>
<?php foreach($menuRules as $rule): ?>
                                <tr>
                                    <td><strong><?php echo $rule['menu']; ?></strong></td>
                                    <td><?php echo $rule['opcion']; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $rule['tipo']; ?>
                                            <?php
                                            switch($rule['tipo']) {
                                                case 'rol': echo 'bg-primary'; break;
                                                case 'empresa': echo 'bg-success'; break;
                                                case 'rut': echo 'bg-purple'; break;
                                                case 'permiso': echo 'bg-warning text-dark'; break;
                                                case 'especial': echo 'bg-danger'; break;
                                                default: echo 'bg-secondary';
                                            }
                                            ?>">
                                            <?php echo ucfirst($rule['tipo']); ?>
                                        </span>
                                    </td>
                                    <td><code class="condition-code"><?php echo $rule['condicion']; ?></code></td>
                                    <td><?php echo $rule['descripcion']; ?></td>
                                    <td><span class="variable-box"><?php echo $rule['variables']; ?></span></td>
                                </tr>
<?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nota informativa -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> <strong>Nota:</strong>
                Actualmente estas reglas est&aacute;n definidas en el c&oacute;digo (<code>templates/layout.php</code>).
                Para modificarlas se requiere editar el archivo directamente.
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

