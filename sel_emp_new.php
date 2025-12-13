<?php 
/**
 * PortalDTE - Selección de Empresa Modernizado
 * Versión con Bootstrap 5
 */

$sUriRetorno = $_GET["sUriRetorno"] ?? '';

include("include/config.php");  
include("include/db_lib.php");   
$conn = conn();
$sMsgJs = $_GET["sMsgJs"] ?? '';

session_start();
if(trim($_SESSION["_COD_USU_SESS"]) == "" || trim($_SESSION["_COD_ROL_SESS"]) == "") {
    header("location:login_new.php?sMsgJs=Sesión expirada");
    exit;
}

// Lógica de empresas (misma que el original)
if(trim($_SESSION["_COD_ROL_SESS"]) != "1") {
    $sql = "SELECT EU.codi_empr, E.rs_empr, E.rut_empr, E.dv_empr, E.dir_empr,
            (SELECT count(codi_empr) FROM caf WHERE tipo_docu in(39,41) and codi_empr=E.codi_empr) as bol, 
            E.is_recep_erp, E.emite_web
            FROM empr_usu EU, empresa E
            WHERE EU.codi_empr = E.codi_empr AND EU.cod_usu = " . $_SESSION["_COD_USU_SESS"];
    $result = rCursor($conn, $sql);
    $nNumRow = $result->RecordCount();
    $_SESSION["_NUM_EMP_USU_SESS"] = $nNumRow;
    
    if($nNumRow == 0) {
        $_SESSION = array();
        session_destroy();
        header("location:login_new.php?sMsgJs=Usuario sin empresa asignada");
        exit;
    } elseif($nNumRow == 1) {
        if(!$result->EOF) {
            $_SESSION["_COD_EMP_USU_SESS"] = trim($result->fields["codi_empr"]);
            $_SESSION["v_codi_empr"] = trim($result->fields["codi_empr"]);
            $_SESSION["_NOM_EMP_USU_SESS"] = trim($result->fields["rs_empr"]);
            $_SESSION["RUT_EMP"] = trim($result->fields["rut_empr"]);
            $_SESSION["DV_EMP"] = trim($result->fields["dv_empr"]);
            $_SESSION["_RUT_EMP_SESS"] = trim($result->fields["rut_empr"]) . "-" . trim($result->fields["dv_empr"]);
            $_SESSION["DIR_EMP"] = trim($result->fields["dir_empr"]);
            $_SESSION["TIENE_BOLETA"] = trim($result->fields["bol"]);
            $_SESSION["_IS_RECEP_ERP_"] = trim($result->fields["is_recep_erp"]);
            $_SESSION["_EMITE_WEB_"] = trim($result->fields["emite_web"]);
            header("location:index_new.php");
            exit;
        }
    }
} else {
    $sql = "SELECT E.codi_empr, E.rs_empr, E.rut_empr, E.dv_empr FROM empresa E ORDER BY E.rs_empr";
    $result = rCursor($conn, $sql);
    $nNumRow = $result->RecordCount();
    $_SESSION["_NUM_EMP_USU_SESS"] = $nNumRow;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Empresa - OpenDTE</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary-color: #001f3f; --secondary-color: #0074d9; }
        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, #003366 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            margin: 20px;
        }
        .card-header-custom {
            background: var(--primary-color);
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 15px 15px 0 0;
        }
        .card-body-custom { padding: 30px; }
        .form-select-lg { padding: 15px; font-size: 1rem; border-radius: 8px; }
        .btn-primary-custom {
            background: var(--secondary-color);
            border: none;
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 8px;
        }
        .btn-primary-custom:hover { background: #005bb5; }
        .user-badge {
            background: rgba(255,255,255,0.1);
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="card-container">
        <div class="card-header-custom">
            <i class="bi bi-building fs-1"></i>
            <h3 class="mt-3 mb-0">Seleccionar Empresa</h3>
            <div class="user-badge">
                <i class="bi bi-person-circle me-1"></i>
                <?= htmlspecialchars($_SESSION["_ALIAS_USU_SESS"] ?? '') ?>
            </div>
        </div>
        <div class="card-body-custom">
            <?php if($sMsgJs): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($sMsgJs) ?>
            </div>
            <?php endif; ?>
            
            <form action="asig_emp.php" method="post" id="formEmp">
                <input type="hidden" name="sNomEmp" id="sNomEmp" value="">
                <input type="hidden" name="sUriRetorno" value="<?= htmlspecialchars($sUriRetorno) ?>">
                
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="bi bi-building me-1"></i>Empresa
                    </label>
                    <select name="nCodEmp" id="nCodEmp" class="form-select form-select-lg" required>
                        <option value="">-- Seleccione una empresa --</option>
                        <?php
                        $result->MoveFirst();
                        while (!$result->EOF) {
                            $cod = htmlspecialchars(trim($result->fields["codi_empr"]));
                            $nom = htmlspecialchars(trim($result->fields["rs_empr"]));
                            $sel = ($cod == ($_SESSION["_COD_EMP_USU_SESS"] ?? '')) ? 'selected' : '';
                            echo "<option value=\"$cod\" $sel>$nom</option>\n";
                            $result->MoveNext();
                        }
                        ?>
                    </select>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-primary-custom">
                        <i class="bi bi-check-circle me-2"></i>Continuar
                    </button>
                    <a href="logout.php" class="btn btn-outline-secondary">
                        <i class="bi bi-box-arrow-left me-2"></i>Cerrar Sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('formEmp').addEventListener('submit', function(e) {
            var select = document.getElementById('nCodEmp');
            if (!select.value) {
                e.preventDefault();
                alert('Por favor seleccione una empresa');
                return false;
            }
            document.getElementById('sNomEmp').value = select.options[select.selectedIndex].text;
        });
    </script>
</body>
</html>

