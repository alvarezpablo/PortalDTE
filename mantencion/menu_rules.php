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

$conn = conn();

// Obtener datos de sesi&oacute;n para mostrar valores actuales
$codEmp = $_SESSION["_COD_EMP_USU_SESS"] ?? '';
$rutEmp = $_SESSION["RUT_EMP"] ?? '';
$gpuerto = $_SESSION["_GPUERTO_"] ?? '';
$emiteWeb = $_SESSION["_EMITE_WEB_"] ?? '';
$codRol = $_SESSION["_COD_ROL_SESS"] ?? '';

if (!function_exists('h')) {
    function h($value)
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'ISO-8859-1');
    }
}

// Verificar si existe la tabla menu_reglas
$tablaExiste = false;
$menuRules = [];

$checkTable = $conn->Execute("SELECT to_regclass('public.menu_reglas') as existe");
if ($checkTable && !$checkTable->EOF && $checkTable->fields['existe'] != '') {
    $tablaExiste = true;
    // Obtener reglas desde BD
    $sql = "SELECT * FROM menu_reglas ORDER BY orden, menu, opcion";
    $result = $conn->Execute($sql);
    while (!$result->EOF) {
        $menuRules[] = [
            'id' => $result->fields['id'],
            'menu' => $result->fields['menu'],
            'opcion' => $result->fields['opcion'],
            'link' => $result->fields['link'],
            'icono' => $result->fields['icono'],
            'tipo_regla' => $result->fields['tipo_regla'],
            'variable' => $result->fields['variable'],
            'operador' => $result->fields['operador'],
            'valor' => $result->fields['valor'],
            'descripcion' => $result->fields['descripcion'],
            'activo' => $result->fields['activo'],
            'orden' => $result->fields['orden']
        ];
        $result->MoveNext();
    }
}
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
        :root { color-scheme: light; }
        body {
            background: #f4f7fb;
            color: #16324f;
        }
        .page-shell {
            max-width: 1320px;
            margin: 0 auto;
            padding: 24px 16px 32px;
        }
        .topbar,
        .panel,
        .modal-content {
            background: #fff;
            border: 1px solid #dbe7f3;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 31, 63, 0.08);
        }
        .topbar {
            padding: 22px 24px;
            margin-bottom: 20px;
        }
        .topbar-title {
            color: #001f3f;
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0;
        }
        .topbar-meta,
        .panel-note,
        .legend-note {
            color: #5b7088;
        }
        .topbar-chip,
        .legend-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 0.88rem;
            font-weight: 600;
        }
        .topbar-chip {
            background: #eef4fb;
            color: #0b5ed7;
        }
        .panel {
            margin-bottom: 18px;
            overflow: hidden;
        }
        .panel-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e4edf6;
            background: linear-gradient(180deg, #f8fbff 0%, #f1f6fc 100%);
        }
        .panel-body {
            padding: 18px 20px;
        }
        .session-grid > div {
            margin-bottom: 12px;
        }
        .session-label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.82rem;
            color: #5b7088;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .badge-rol { background-color: #0d6efd; }
        .badge-empresa { background-color: #198754; }
        .badge-rut { background-color: #6f42c1; }
        .badge-permiso { background-color: #fd7e14; }
        .badge-especial { background-color: #dc3545; }
        .badge-siempre { background-color: #6c757d; }
        .variable-box {
            display: inline-flex;
            align-items: center;
            min-height: 34px;
            background: #eef4fb;
            color: #16324f;
            padding: 6px 10px;
            border-radius: 10px;
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
        .btn-action { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
        .row-inactive { opacity: 0.5; }
        .legend-chip {
            color: #fff;
            margin: 0 8px 8px 0;
        }
        .legend-chip.text-dark { color: #212529 !important; }
        .table thead.table-dark th {
            background: #001f3f;
            border-color: #001f3f;
            white-space: nowrap;
        }
        .table > :not(caption) > * > * {
            vertical-align: middle;
        }
        .table tbody td {
            font-size: 0.93rem;
        }
        .modal-header-accent {
            background: linear-gradient(135deg, #001f3f 0%, #0b5ed7 100%);
            color: #fff;
            border-bottom: 0;
            border-radius: 16px 16px 0 0;
        }
        .modal-footer {
            border-top-color: #e4edf6;
        }
        pre.sql-preview {
            max-height: 300px;
            overflow: auto;
            margin-top: 12px;
            margin-bottom: 0;
            border-radius: 12px;
        }
    </style>
</head>
<body>
<div class="page-shell">
    <div class="topbar">
        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div>
                <p class="topbar-meta mb-2"><i class="bi bi-shield-lock me-2"></i>Administraci&oacute;n avanzada</p>
                <h1 class="topbar-title">Reglas de Men&uacute;</h1>
                <p class="panel-note mb-0">Administraci&oacute;n de visibilidad de opciones del men&uacute; seg&uacute;n roles, empresas y permisos.</p>
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-xl-end">
                <span class="topbar-chip"><i class="bi bi-person-badge"></i>Rol <?php echo h($codRol); ?></span>
                <span class="topbar-chip"><i class="bi bi-building"></i>Empresa <?php echo h($codEmp); ?></span>
                <?php if($tablaExiste): ?><span class="topbar-chip"><i class="bi bi-list-check"></i><?php echo count($menuRules); ?> regla(s)</span><?php endif; ?>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
            <div>
                <strong class="d-block text-primary-emphasis"><i class="bi bi-info-circle me-2"></i>Variables de sesi&oacute;n actuales</strong>
                <span class="legend-note">Referencia r&aacute;pida para construir y validar las condiciones de visibilidad.</span>
            </div>
            <span class="topbar-chip"><i class="bi bi-sliders"></i>Contexto activo</span>
        </div>
        <div class="panel-body">
            <div class="row session-grid">
                <div class="col-sm-6 col-lg-2">
                    <span class="session-label">Rol</span>
                    <span class="variable-box"><?php echo h($codRol); ?></span>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <span class="session-label">Empresa ID</span>
                    <span class="variable-box"><?php echo h($codEmp); ?></span>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <span class="session-label">RUT Empresa</span>
                    <span class="variable-box"><?php echo h($rutEmp ?: '(vac&iacute;o)'); ?></span>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <span class="session-label">GPuerto</span>
                    <span class="variable-box"><?php echo h($gpuerto ?: '0'); ?></span>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <span class="session-label">Emite Web</span>
                    <span class="variable-box"><?php echo h($emiteWeb ?: '0'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-body">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div>
                    <div class="mb-2">
                        <span class="legend-chip bg-primary">Rol</span>
                        <span class="legend-chip bg-success">Empresa</span>
                        <span class="legend-chip" style="background-color:#6f42c1">RUT</span>
                        <span class="legend-chip bg-warning text-dark">Permiso</span>
                        <span class="legend-chip bg-danger">Especial</span>
                        <span class="legend-chip bg-secondary">Siempre</span>
                    </div>
                    <p class="legend-note mb-0">Cada regla controla la aparici&oacute;n de una opci&oacute;n del men&uacute; seg&uacute;n el contexto activo.</p>
                </div>
                <?php if($tablaExiste): ?>
                <div class="text-xl-end">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegla" onclick="limpiarModal()">
                        <i class="bi bi-plus-circle me-1"></i>Nueva Regla
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php if(!$tablaExiste): ?>
    <div class="panel">
        <div class="panel-body">
            <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle"></i> <strong>Tabla no encontrada:</strong>
                La tabla <code>menu_reglas</code> no existe en la base de datos.
                <br><br>
                <strong>Para crearla, ejecute el siguiente script SQL:</strong>
                <pre class="bg-dark text-light p-3 sql-preview"><?php echo h(file_get_contents("../sql/menu_reglas.sql")); ?></pre>
                <a href="menu_rules.php" class="btn btn-primary mt-2"><i class="bi bi-arrow-clockwise"></i> Recargar</a>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="panel">
        <div class="panel-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
            <div>
                <strong class="d-block text-primary-emphasis"><i class="bi bi-list-check me-2"></i>Reglas de visibilidad</strong>
                <span class="legend-note">Edite, active o elimine reglas sin salir de esta pantalla.</span>
            </div>
            <span class="topbar-chip"><i class="bi bi-collection"></i><?php echo count($menuRules); ?> registro(s)</span>
        </div>
        <div class="panel-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm mb-0" id="tablaReglas">
                    <thead class="table-dark">
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:12%">Men&uacute;</th>
                            <th style="width:15%">Opci&oacute;n</th>
                            <th style="width:8%">Tipo</th>
                            <th style="width:10%">Variable</th>
                            <th style="width:8%">Op.</th>
                            <th style="width:12%">Valor</th>
                            <th style="width:5%">Act.</th>
                            <th style="width:12%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
<?php foreach($menuRules as $rule): ?>
                        <tr class="<?php echo $rule['activo']=='N' ? 'row-inactive' : ''; ?>" id="row-<?php echo $rule['id']; ?>">
                            <td><?php echo $rule['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($rule['menu']); ?></strong></td>
                            <td><?php echo htmlspecialchars($rule['opcion']); ?></td>
                            <td>
                                <span class="badge <?php
                                    switch($rule['tipo_regla']) {
                                        case 'rol': echo 'bg-primary'; break;
                                        case 'empresa': echo 'bg-success'; break;
                                        case 'rut': echo 'bg-purple'; break;
                                        case 'permiso': echo 'bg-warning text-dark'; break;
                                        case 'especial': echo 'bg-danger'; break;
                                        default: echo 'bg-secondary';
                                    }
                                ?>" style="<?php echo $rule['tipo_regla']=='rut' ? 'background-color:#6f42c1' : ''; ?>">
                                    <?php echo ucfirst($rule['tipo_regla']); ?>
                                </span>
                            </td>
                            <td><code><?php echo htmlspecialchars($rule['variable']); ?></code></td>
                            <td><code><?php echo htmlspecialchars($rule['operador']); ?></code></td>
                            <td><span class="variable-box"><?php echo htmlspecialchars($rule['valor']); ?></span></td>
                            <td>
                                <span class="badge <?php echo $rule['activo']=='S' ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?php echo $rule['activo']=='S' ? 'S&iacute;' : 'No'; ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary btn-action" onclick="editarRegla(<?php echo htmlspecialchars(json_encode($rule)); ?>)" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-<?php echo $rule['activo']=='S' ? 'warning' : 'success'; ?> btn-action" onclick="toggleActivo(<?php echo $rule['id']; ?>)" title="<?php echo $rule['activo']=='S' ? 'Desactivar' : 'Activar'; ?>">
                                    <i class="bi bi-<?php echo $rule['activo']=='S' ? 'pause' : 'play'; ?>"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger btn-action" onclick="eliminarRegla(<?php echo $rule['id']; ?>)" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
<?php endforeach; ?>
<?php if(empty($menuRules)): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">No hay reglas configuradas. Haga clic en "Nueva Regla" para agregar.</td></tr>
<?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>

<!-- Modal Regla -->
<div class="modal fade" id="modalRegla" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-accent">
                <h5 class="modal-title" id="modalReglaTitle"><i class="bi bi-plus-circle"></i> Nueva Regla</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRegla">
                <div class="modal-body">
                    <input type="hidden" name="accion" id="accion" value="I">
                    <input type="hidden" name="id" id="regla_id" value="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Men&uacute; *</label>
                            <input type="text" class="form-control" name="menu" id="menu" required placeholder="Ej: DTE Emitidos">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Opci&oacute;n *</label>
                            <input type="text" class="form-control" name="opcion" id="opcion" required placeholder="Ej: DTE No Enviado a SII">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Link</label>
                            <input type="text" class="form-control" name="link" id="link" placeholder="Ej: dte/list_dte_no_enviado.php">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Icono</label>
                            <input type="text" class="form-control" name="icono" id="icono" placeholder="Ej: bi-file-text">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipo Regla *</label>
                            <select class="form-select" name="tipo_regla" id="tipo_regla" required>
                                <option value="rol">Rol</option>
                                <option value="empresa">Empresa</option>
                                <option value="rut">RUT</option>
                                <option value="permiso">Permiso</option>
                                <option value="especial">Especial</option>
                                <option value="siempre">Siempre</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Variable *</label>
                            <select class="form-select" name="variable" id="variable" required>
                                <option value="_COD_ROL_SESS">_COD_ROL_SESS (Rol)</option>
                                <option value="_COD_EMP_USU_SESS">_COD_EMP_USU_SESS (Empresa ID)</option>
                                <option value="RUT_EMP">RUT_EMP (RUT Empresa)</option>
                                <option value="_GPUERTO_">_GPUERTO_ (Grupo Puerto)</option>
                                <option value="_EMITE_WEB_">_EMITE_WEB_ (Emite Web)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Operador *</label>
                            <select class="form-select" name="operador" id="operador" required>
                                <option value="==">== (Igual)</option>
                                <option value="!=">!= (Distinto)</option>
                                <option value="IN">IN (Est&aacute; en lista)</option>
                                <option value="NOT IN">NOT IN (No est&aacute; en lista)</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Valor(es) *</label>
                            <input type="text" class="form-control" name="valor" id="valor" required placeholder="Ej: 1 o 72,73,70,74">
                            <small class="text-muted">Para IN/NOT IN use valores separados por coma</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Orden</label>
                            <input type="number" class="form-control" name="orden" id="orden" value="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripci&oacute;n</label>
                            <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripci&oacute;n de la regla">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="activo" id="activo" checked>
                                <label class="form-check-label" for="activo">Regla activa</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar"><i class="bi bi-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var modalRegla = null;

$(document).ready(function() {
    modalRegla = new bootstrap.Modal(document.getElementById('modalRegla'));

    $('#formRegla').on('submit', function(e) {
        e.preventDefault();
        guardarRegla();
    });
});

function limpiarModal() {
    $('#accion').val('I');
    $('#regla_id').val('');
    $('#menu').val('');
    $('#opcion').val('');
    $('#link').val('');
    $('#icono').val('');
    $('#tipo_regla').val('rol');
    $('#variable').val('_COD_ROL_SESS');
    $('#operador').val('==');
    $('#valor').val('');
    $('#orden').val('0');
    $('#descripcion').val('');
    $('#activo').prop('checked', true);
    $('#modalReglaTitle').html('<i class="bi bi-plus-circle"></i> Nueva Regla');
}

function editarRegla(regla) {
    $('#accion').val('M');
    $('#regla_id').val(regla.id);
    $('#menu').val(regla.menu);
    $('#opcion').val(regla.opcion);
    $('#link').val(regla.link);
    $('#icono').val(regla.icono);
    $('#tipo_regla').val(regla.tipo_regla);
    $('#variable').val(regla.variable);
    $('#operador').val(regla.operador);
    $('#valor').val(regla.valor);
    $('#orden').val(regla.orden);
    $('#descripcion').val(regla.descripcion);
    $('#activo').prop('checked', regla.activo == 'S');
    $('#modalReglaTitle').html('<i class="bi bi-pencil"></i> Editar Regla #' + regla.id);
    modalRegla.show();
}

function guardarRegla() {
    $.ajax({
        url: 'pro_menu_rules.php',
        type: 'POST',
        data: $('#formRegla').serialize(),
        dataType: 'json',
        success: function(resp) {
            if(resp.success) {
                modalRegla.hide();
                location.reload();
            } else {
                alert('Error: ' + resp.message);
            }
        },
        error: function() { alert('Error de conexion'); }
    });
}

function toggleActivo(id) {
    $.ajax({
        url: 'pro_menu_rules.php',
        type: 'POST',
        data: { accion: 'T', id: id },
        dataType: 'json',
        success: function(resp) {
            if(resp.success) { location.reload(); }
            else { alert('Error: ' + resp.message); }
        }
    });
}

function eliminarRegla(id) {
    if(confirm('Eliminar esta regla?')) {
        $.ajax({
            url: 'pro_menu_rules.php',
            type: 'POST',
            data: { accion: 'E', id: id },
            dataType: 'json',
            success: function(resp) {
                if(resp.success) { location.reload(); }
                else { alert('Error: ' + resp.message); }
            }
        });
    }
}
</script>
</body>
</html>
