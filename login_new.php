<?php
/**
 * PortalDTE - Login Moderno
 * Nueva version con Bootstrap 5 y diseno responsivo
 *
 * IMPORTANTE: Mantener codificacion ISO-8859-1 por compatibilidad con SII
 */

// Cargar libreria de seguridad
require_once __DIR__ . '/include/security_lib.php';

// Iniciar sesion segura y aplicar headers
secureSessionStart();
setSecurityHeaders();

// Si ya esta autenticado, redirigir
if (!empty($_SESSION["_COD_USU_SESS"])) {
    header("location: index_new.php");
    exit;
}

// Obtener token CSRF
$csrfToken = getCSRFToken();

$errorMsg = '';
if (isset($_GET['sMsgJs'])) {
    $errorMsg = e($_GET['sMsgJs']);
}
$lastUser = isset($_GET['sUser']) ? e($_GET['sUser']) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OpenDTE</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #001f3f;
            --secondary-color: #0074d9;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, #003366 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
            margin: 20px;
        }
        
        .login-header {
            background: var(--primary-color);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-header h2 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .login-header p {
            margin: 10px 0 0;
            opacity: 0.8;
            font-size: 0.95rem;
        }
        
        .login-header .logo-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        
        .login-body {
            padding: 35px;
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-floating > .form-control {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            padding: 15px;
            height: auto;
        }
        
        .form-floating > .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(0,116,217,0.15);
        }
        
        .form-floating > label {
            padding: 15px;
        }
        
        .btn-login {
            background: var(--secondary-color);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background: #005bb5;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,116,217,0.3);
        }
        
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .login-footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #eee;
            background: #f8f9fa;
        }
        
        .login-footer a {
            color: var(--secondary-color);
            text-decoration: none;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .input-group-text {
            background: transparent;
            border: 2px solid #e0e0e0;
            border-right: none;
            border-radius: 8px 0 0 8px;
        }
        
        .input-with-icon .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }
        
        .version-info {
            color: rgba(255,255,255,0.5);
            font-size: 0.8rem;
            position: fixed;
            bottom: 20px;
            text-align: center;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="bi bi-file-earmark-text logo-icon"></i>
            <h2>OpenDTE</h2>
            <p>Sistema de Facturación Electrónica</p>
        </div>
        
        <div class="login-body">
            <?php if ($errorMsg): ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div><?= $errorMsg ?></div>
            </div>
            <?php endif; ?>
            
            <form action="val_user.php" method="POST">
                <!-- Token CSRF para proteccion -->
                <?= csrfField() ?>

                <div class="mb-3">
                    <div class="input-group input-with-icon">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="sUser" name="sUser"
                               placeholder="Usuario" value="<?= $lastUser ?>" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="input-group input-with-icon">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="sClave" name="sClave"
                               placeholder="Contrasena" required>
                    </div>
                </div>

                <button type="submit" name="Submit" class="btn btn-primary btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesion
                </button>
            </form>
        </div>

        <div class="login-footer">
            <p class="mb-0">
                <i class="bi bi-envelope me-1"></i>
                ¿Necesitas ayuda? <a href="mailto:soporte@opendte.com">Contáctanos</a>
            </p>
        </div>
    </div>

    <div class="version-info">
        OpenDTE v2.0 - Portal de Facturación Electrónica
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

