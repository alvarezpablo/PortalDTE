<?php 
    ini_set('post_max_size', '800M');
    ini_set('upload_max_filesize', '800M');
    ini_set('memory_limit', '1024M');
    ini_set('max_execution_time', '36000');
    ini_set('max_input_time', '36000');

    include("../include/config.php");  
    include("../include/ver_aut.php");      
    include("../include/db_lib.php"); 
    include("../include/tables.php");  
    
    $sMsgJs = trim($_GET["sMsgJs"]);  
    $sLinkActual = "mantencion/form_cont_elec_v2.php";  
    $_NUM_ROW_LIST = 50;
    $conn = conn();

    // Parametros de ordenamiento
    $orderCol = isset($_GET["_ORDER_BY_COLUM"]) ? trim($_GET["_ORDER_BY_COLUM"]) : "rs_contr";
    $orderDir = isset($_GET["_NIVEL_BY_ORDER"]) ? trim($_GET["_NIVEL_BY_ORDER"]) : "ASC";
    $searchCol = isset($_GET["_COLUM_SEARCH"]) ? trim($_GET["_COLUM_SEARCH"]) : "";
    $searchStr = isset($_GET["_STRING_SEARCH"]) ? trim($_GET["_STRING_SEARCH"]) : "";
    $pagina = isset($_GET["pagina"]) ? intval($_GET["pagina"]) : 1;
    if ($pagina < 1) $pagina = 1;

    // Toggle orden
    if (isset($_GET["_ORDER_CAMBIA"]) && $_GET["_ORDER_CAMBIA"] == "Y") {
        $orderDir = ($orderDir == "ASC") ? "DESC" : "ASC";
    }

    // Iconos de ordenamiento
    $iconRut = $iconRs = $iconFec = $iconEm = "";
    $arrow = ($orderDir == "ASC") ? "<i class='bi bi-arrow-up'></i>" : "<i class='bi bi-arrow-down'></i>";
    if ($orderCol == "rut_contr") $iconRut = $arrow;
    elseif ($orderCol == "rs_contr") $iconRs = $arrow;
    elseif ($orderCol == "fecres_contr") $iconFec = $arrow;
    elseif ($orderCol == "email_contr") $iconEm = $arrow;

    // Query string para links
    $qrstring = "&_COLUM_SEARCH=" . urlencode($searchCol) . "&_STRING_SEARCH=" . urlencode($searchStr);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contribuyentes Electr&oacute;nicos - Portal DTE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary-color: #001f3f; --secondary-color: #0074d9; }
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 20px; }
        .card-header { background: var(--primary-color); color: white; border-radius: 8px 8px 0 0 !important; font-weight: 600; }
        .table thead th { background: var(--primary-color); color: white; font-weight: 500; font-size: 0.85rem; position: sticky; top: 0; }
        .table tbody td { vertical-align: middle; font-size: 0.85rem; }
        .table tbody tr:hover { background-color: #e9ecef; }
        .sort-link { color: white; text-decoration: none; }
        .sort-link:hover { color: #ccc; }
        .table-responsive { max-height: 60vh; overflow-y: auto; }
    </style>
</head>
<body class="p-3">

<?php if($sMsgJs != ""): ?>
<script>alert('<?php echo addslashes($sMsgJs); ?>');</script>
<?php endif; ?>

<!-- Card: Cargar Archivo -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-upload me-2"></i>Carga de Contribuyentes Electr&oacute;nicos
    </div>
    <div class="card-body">
        <form name="_FFORM" enctype="multipart/form-data" action="mantencion/pro_clie_elec_v2.php" method="post">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $_MAX_FILE_CLIE_ELEC; ?>">
            <div class="row align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Archivo CSV <span class="text-danger">*</span></label>
                    <input type="file" name="sFileClieElec" class="form-control" accept=".csv,.txt" required>
                    <small class="text-muted">Formato: RUT;Raz&oacute;n Social;N&ordm; Resoluci&oacute;n;Fecha;Email</small>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-cloud-upload me-1"></i>Cargar Archivo
                    </button>
                    <a href="main.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Card: Busqueda y Listado -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people me-2"></i>Listado de Contribuyentes</span>
    </div>
    <div class="card-body">
        <!-- Formulario de busqueda -->
        <form name="_FSEARCH" method="get" action="<?php echo $sLinkActual; ?>" class="mb-3">
            <div class="row g-2">
                <div class="col-md-3">
                    <select name="_COLUM_SEARCH" class="form-select form-select-sm">
                        <option value="rut_contr" <?php echo ($searchCol=="rut_contr")?"selected":""; ?>>Rut</option>
                        <option value="rs_contr" <?php echo ($searchCol=="rs_contr")?"selected":""; ?>>Raz&oacute;n Social</option>
                        <option value="email_contr" <?php echo ($searchCol=="email_contr")?"selected":""; ?>>Email</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="_STRING_SEARCH" class="form-control form-control-sm" 
                           placeholder="Buscar..." value="<?php echo htmlspecialchars($searchStr); ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <a href="<?php echo $sLinkActual; ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Limpiar
                    </a>
                </div>
            </div>
        </form>

        <!-- Tabla de resultados -->
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm mb-0">
                <thead>
                    <tr>
                        <th style="width:15%">
                            <a href="<?php echo $sLinkActual; ?>?_ORDER_BY_COLUM=rut_contr&_NIVEL_BY_ORDER=<?php echo $orderDir; ?><?php echo $qrstring; ?>&_ORDER_CAMBIA=Y" class="sort-link">
                                Rut <?php echo $iconRut; ?>
                            </a>
                        </th>
                        <th style="width:35%">
                            <a href="<?php echo $sLinkActual; ?>?_ORDER_BY_COLUM=rs_contr&_NIVEL_BY_ORDER=<?php echo $orderDir; ?><?php echo $qrstring; ?>&_ORDER_CAMBIA=Y" class="sort-link">
                                Raz&oacute;n Social <?php echo $iconRs; ?>
                            </a>
                        </th>
                        <th style="width:15%">
                            <a href="<?php echo $sLinkActual; ?>?_ORDER_BY_COLUM=fecres_contr&_NIVEL_BY_ORDER=<?php echo $orderDir; ?><?php echo $qrstring; ?>&_ORDER_CAMBIA=Y" class="sort-link">
                                Fecha <?php echo $iconFec; ?>
                            </a>
                        </th>
                        <th style="width:35%">
                            <a href="<?php echo $sLinkActual; ?>?_ORDER_BY_COLUM=email_contr&_NIVEL_BY_ORDER=<?php echo $orderDir; ?><?php echo $qrstring; ?>&_ORDER_CAMBIA=Y" class="sort-link">
                                Email <?php echo $iconEm; ?>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
<?php
    // Construir query
    $sql = "SELECT rut_contr, rs_contr, nrores_contr, fecres_contr, email_contr
            FROM contrib_elec WHERE 1=1";

    $sqlWhere = "";
    if ($searchStr != "") {
        $sqlWhere = " AND UPPER(CAST(" . $orderCol . " AS varchar)) LIKE UPPER('" . str_replace("'", "''", $searchStr) . "%')";
        $sql .= $sqlWhere;
    }

    $sql .= " ORDER BY " . $orderCol . " " . $orderDir;

    // Contar total de registros
    $sqlCount = "SELECT COUNT(*) as total FROM contrib_elec WHERE 1=1" . $sqlWhere;
    $resCount = rCursor($conn, $sqlCount);
    $totalFilas = intval($resCount->fields["total"]);
    $totalPaginas = ceil($totalFilas / $_NUM_ROW_LIST);
    if ($totalPaginas < 1) $totalPaginas = 1;
    if ($pagina > $totalPaginas) $pagina = $totalPaginas;

    // Obtener registros con paginacion
    $offset = $_NUM_ROW_LIST * ($pagina - 1);
    $result = $conn->selectLimit($sql, $_NUM_ROW_LIST, $offset);

    $numFila = $offset;
    while (!$result->EOF) {
        $numFila++;
        $sRut = trim($result->fields["rut_contr"]);
        $sRs = trim($result->fields["rs_contr"]);
        $dFec = trim($result->fields["fecres_contr"]);
        $sEm = trim($result->fields["email_contr"]);
?>
                    <tr>
                        <td><?php echo htmlspecialchars($sRut); ?></td>
                        <td><?php echo htmlspecialchars($sRs); ?></td>
                        <td><?php echo $dFec; ?></td>
                        <td><?php echo htmlspecialchars($sEm); ?></td>
                    </tr>
<?php
        $result->MoveNext();
    }

    if ($totalFilas == 0) {
?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No se encontraron contribuyentes
                        </td>
                    </tr>
<?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Paginacion -->
        <?php if ($totalPaginas > 1): ?>
        <nav class="mt-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Mostrando <?php echo $offset + 1; ?> - <?php echo min($offset + $_NUM_ROW_LIST, $totalFilas); ?>
                    de <?php echo $totalFilas; ?> registros
                </small>
                <ul class="pagination pagination-sm mb-0">
                    <?php if ($pagina > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $sLinkActual; ?>?pagina=1&_ORDER_BY_COLUM=<?php echo $orderCol; ?>&_NIVEL_BY_ORDER=<?php echo $orderDir; ?><?php echo $qrstring; ?>">
                            <i class="bi bi-chevron-double-left"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $sLinkActual; ?>?pagina=<?php echo $pagina-1; ?>&_ORDER_BY_COLUM=<?php echo $orderCol; ?>&_NIVEL_BY_ORDER=<?php echo $orderDir; ?><?php echo $qrstring; ?>">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    <?php endif; ?>

                    <li class="page-item active">
                        <span class="page-link"><?php echo $pagina; ?> / <?php echo $totalPaginas; ?></span>
                    </li>

                    <?php if ($pagina < $totalPaginas): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $sLinkActual; ?>?pagina=<?php echo $pagina+1; ?>&_ORDER_BY_COLUM=<?php echo $orderCol; ?>&_NIVEL_BY_ORDER=<?php echo $orderDir; ?><?php echo $qrstring; ?>">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $sLinkActual; ?>?pagina=<?php echo $totalPaginas; ?>&_ORDER_BY_COLUM=<?php echo $orderCol; ?>&_NIVEL_BY_ORDER=<?php echo $orderDir; ?><?php echo $qrstring; ?>">
                            <i class="bi bi-chevron-double-right"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

