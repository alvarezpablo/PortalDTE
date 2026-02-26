<?php 
    include("../include/config.php");  
    include("../include/ver_aut.php");      
    include("../include/ver_emp_adm.php");        
    include("../include/db_lib.php"); 

    $conn = conn();  
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAF Disponibles - Portal DTE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary-color: #001f3f; --secondary-color: #0074d9; }
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 20px; }
        .card-header { background: var(--primary-color); color: white; border-radius: 8px 8px 0 0 !important; font-weight: 600; }
        .card-header.expired { background: #6c757d; }
        .table thead th { background: var(--primary-color); color: white; font-weight: 500; font-size: 0.85rem; }
        .table tbody td { vertical-align: middle; font-size: 0.85rem; }
        .table tbody tr:hover { background-color: #e9ecef; }
        .badge-vigente { background-color: #28a745; }
        .badge-vencido { background-color: #dc3545; }
        .progress { height: 20px; }
    </style>
</head>
<body class="p-3">

<!-- Card: CAF Vigentes -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-check-circle me-2"></i>CAF Vigentes
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:40%">Documento</th>
                        <th style="width:15%" class="text-end">Folio Desde</th>
                        <th style="width:15%" class="text-end">Folio Hasta</th>
                        <th style="width:15%" class="text-end">Folio Actual</th>
                        <th style="width:15%" class="text-end">Disponibles</th>
                    </tr>
                </thead>
                <tbody>
<?php 
    $sql = "SELECT 
                C.ini_num_caf, 
                C.ter_num_caf, 
                C.fol_disp_caf, 
                D.desc_tipo_docu, 
                (C.ter_num_caf - C.fol_disp_caf + 1) as disp,
                C.estado
            FROM 
                caf C, 
                dte_tipo D 
            WHERE 
                D.tipo_docu = C.tipo_docu AND estado = 1 AND
                C.codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'
            ORDER BY D.desc_tipo_docu, C.ini_num_caf";
    $result = rCursor($conn, $sql);        
    $hayVigentes = false;
    
    while (!$result->EOF) {
        $hayVigentes = true;
        $nNumIni = trim($result->fields["ini_num_caf"]);        
        $nNumFin = trim($result->fields["ter_num_caf"]);        
        $nNumAct = trim($result->fields["fol_disp_caf"]);        
        $sTipDoc = trim($result->fields["desc_tipo_docu"]);        
        $nNumDisp = intval($result->fields["disp"]);
        $total = $nNumFin - $nNumIni + 1;
        $usados = $nNumAct - $nNumIni;
        $pctUsado = ($total > 0) ? round(($usados / $total) * 100) : 0;
        
        $badgeClass = "bg-success";
        if ($pctUsado > 80) $badgeClass = "bg-warning text-dark";
        if ($pctUsado > 95) $badgeClass = "bg-danger";
?>
                    <tr>
                        <td><i class="bi bi-file-earmark-text me-2"></i><?php echo htmlspecialchars($sTipDoc); ?></td>
                        <td class="text-end"><?php echo number_format($nNumIni, 0, '', '.'); ?></td>
                        <td class="text-end"><?php echo number_format($nNumFin, 0, '', '.'); ?></td>
                        <td class="text-end"><?php echo number_format($nNumAct, 0, '', '.'); ?></td>
                        <td class="text-end">
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo number_format($nNumDisp, 0, '', '.'); ?></span>
                        </td>
                    </tr>
<?php
        $result->MoveNext();
    }
    
    if (!$hayVigentes) {
?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No hay CAF vigentes
                        </td>
                    </tr>
<?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Card: CAF Vencidos -->
<div class="card">
    <div class="card-header expired">
        <i class="bi bi-x-circle me-2"></i>CAF Vencidos
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th style="width:40%">Documento</th>
                        <th style="width:20%" class="text-end">Folio Desde</th>
                        <th style="width:20%" class="text-end">Folio Hasta</th>
                        <th style="width:20%" class="text-end">Estado</th>
                    </tr>
                </thead>
                <tbody>
<?php
    $sql = "SELECT
                C.ini_num_caf,
                C.ter_num_caf,
                C.fol_disp_caf,
                D.desc_tipo_docu,
                C.estado
            FROM
                caf C,
                dte_tipo D                                    
            WHERE
                D.tipo_docu = C.tipo_docu AND estado = 2 AND
                C.codi_empr = '" . trim($_SESSION["_COD_EMP_USU_SESS"]) . "'
            ORDER BY D.desc_tipo_docu, C.ini_num_caf";
    $result = rCursor($conn, $sql);
    $hayVencidos = false;

    while (!$result->EOF) {
        $hayVencidos = true;
        $nNumIni = trim($result->fields["ini_num_caf"]);
        $nNumFin = trim($result->fields["ter_num_caf"]);
        $sTipDoc = trim($result->fields["desc_tipo_docu"]);
?>
                    <tr class="table-danger">
                        <td><i class="bi bi-file-earmark-x me-2"></i><?php echo htmlspecialchars($sTipDoc); ?></td>
                        <td class="text-end"><?php echo number_format($nNumIni, 0, '', '.'); ?></td>
                        <td class="text-end"><?php echo number_format($nNumFin, 0, '', '.'); ?></td>
                        <td class="text-end"><span class="badge bg-danger">Vencido</span></td>
                    </tr>
<?php
        $result->MoveNext();
    }
    
    if (!$hayVencidos) {
?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">
                            No hay CAF vencidos
                        </td>
                    </tr>
<?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="text-end">
    <a href="main.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

