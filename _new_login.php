<?php
if ($_GET['sMsgJs'] != '') {
    $alertMessage = "alert(\"" . $_GET['sMsgJs'] . "\");";
} else {
    $alertMessage = '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="ISO-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Portal OpenDTExpress - Plan Full</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="css/_new_styles.css">
<script src="javascript/msg.js"></script>
<script type="text/javascript">
<?php echo $alertMessage; ?>
</script>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="_new_login-container text-center">
            <form name="_FFORM" action="val_user.php" method="post" class="form-group"> 
                <input type='hidden' name='submitted' id='submitted' value='1'/>
                <input class="form-control" name="sUser" value="<?php echo $sUser; ?>" placeholder="Usuario" maxlength="100" required />
                <input type="password" class="form-control" name="sClave" placeholder="Contrase&ntilde;a" maxlength="50" required />
                <input type='submit' name='Submit' value='Submit' class="btn btn-warning btn-block" />
            </form>
            <p>En caso de necesitar soporte enviar un mail a <a href="mailto:soporte@opendte.com" class="text-info">soporte@opendte.com</a></p>
        </div>
    </div>
</body>
</html>

