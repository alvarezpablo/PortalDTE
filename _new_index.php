<?php 
session_start();
if ($_SESSION["_COD_USU_SESS"] == ''){
    header("location: _new_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Portal OpenDTExpress - Plan Full</title>
<link rel="shortcut icon" href="/favicon.ico">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="css/_new_styles.css">
<script src="javascript/common.js"></script>
<script>
if (window != window.top)
    top.location.href = location.href;
var opt_no_frames = false;
var opt_integrated_mode = false;
var _help_prefix = '';
var _help_module = "";
var _context = "";
</script>
</head>
<body>
    <header>
        <?php include 'top.php'; ?>
    </header>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <?php include 'left.php'; ?>
            </nav>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <?php include 'main.php'; ?>
            </main>
        </div>
    </div>
</body>
</html>

