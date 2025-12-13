<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="_new_css/styles.css">
    <title>Login - OpenDTE</title>
</head>
<body>
    <div class="login-container">
        <h2>Acceso al Sistema OpenDTE</h2>
        <form action="val_user.php" method="POST">
            <div class="input-field">
                <label for="sUser">Usuario</label>
                <input type="text" id="sUser" name="sUser" placeholder="Ingrese su usuario" required>
            </div>
            <div class="input-field">
                <label for="sClave">Contrase&ntilde;a</label>
                <input type="password" id="sClave" name="sClave" placeholder="Ingrese su contrase&ntilde;a" required>
            </div>
            <button type="submit" name="Submit" class="login-button">Iniciar Sesi&oacute;n</button>
        </form>
        <a href="mailto:soporte@opendte.com" class="support-link">&iquest;Necesitas ayuda? Cont&aacute;ctanos</a>
    </div>
</body>
</html>

