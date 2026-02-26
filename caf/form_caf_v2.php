<?php 
    include("../include/config.php");  
    include("../include/ver_aut.php");      
    include("../include/ver_emp_adm.php");        
    include("../include/ver_aut_adm_super.php");        
    include("../include/tables.php");  
    $sMsgJs = trim($_GET["sMsgJs"]);  
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cargar CAF - Portal DTE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary-color: #001f3f; --secondary-color: #0074d9; }
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 20px; }
        .card-header { background: var(--primary-color); color: white; border-radius: 8px 8px 0 0 !important; font-weight: 600; }
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }
        .upload-area:hover {
            border-color: var(--secondary-color);
            background: #e9f4ff;
        }
        .upload-area i {
            font-size: 3rem;
            color: var(--secondary-color);
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid var(--secondary-color);
            padding: 15px;
            border-radius: 0 8px 8px 0;
        }
    </style>
</head>
<body class="p-3">

<?php if($sMsgJs != ""): ?>
<script>alert('<?php echo addslashes($sMsgJs); ?>');</script>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <i class="bi bi-upload me-2"></i>Carga de CAF (C&oacute;digo de Autorizaci&oacute;n de Folios)
    </div>
    <div class="card-body">
        <form name="_FFORM" enctype="multipart/form-data" action="caf/pro_caf.php" method="post">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $_MAX_FILE_CAF; ?>">
            
            <div class="row">
                <div class="col-md-7">
                    <div class="upload-area mb-3">
                        <i class="bi bi-file-earmark-code d-block mb-3"></i>
                        <h5>Seleccione archivo CAF</h5>
                        <p class="text-muted mb-3">Archivo XML descargado desde el SII</p>
                        <input type="file" name="sFileCaf" class="form-control" accept=".xml" required 
                               style="max-width: 400px; margin: 0 auto;">
                    </div>
                    
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-cloud-upload me-2"></i>Cargar CAF
                        </button>
                        <a href="main.php" class="btn btn-secondary btn-lg">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </a>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="info-box">
                        <h6><i class="bi bi-info-circle me-2"></i>Informaci&oacute;n</h6>
                        <p class="mb-2 small">El CAF es el archivo XML que autoriza la emisi&oacute;n de documentos tributarios electr&oacute;nicos.</p>
                        <hr>
                        <h6 class="mb-2">Pasos para obtener el CAF:</h6>
                        <ol class="small mb-0">
                            <li>Ingrese al sitio del SII</li>
                            <li>Vaya a Factura Electr&oacute;nica &gt; Solicitar Folios</li>
                            <li>Seleccione el tipo de documento</li>
                            <li>Indique la cantidad de folios</li>
                            <li>Descargue el archivo XML</li>
                            <li>Cargue el archivo aqu&iacute;</li>
                        </ol>
                    </div>
                    
                    <div class="alert alert-warning mt-3 small">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> Solo se aceptan archivos XML originales del SII. No modifique el contenido del archivo.
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

