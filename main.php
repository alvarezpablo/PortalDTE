<?php 
  include("include/config.php");  
  $_NIVEL_RAIZ = true;
  include("include/ver_aut.php");   

  $sNomUser = $_SESSION["_ALIAS_USU_SESS"];
  $sNomEmp = $_SESSION["_NOM_EMP_USU_SESS"]; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/custom.css">
<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/layout.css">

<style>
  #content {
    background: #fff;
    margin: 20px auto;
    padding: 20px;
    max-width: 800px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }
  h1 {
    text-align: center;
    color: #001f3f;
  }
  h3 {
    color: #001f3f;
  }
  p {
    line-height: 1.6;
  }
  fieldset {
    border: 1px solid #001f3f;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
  }
  legend {
    color: #001f3f;
    font-weight: bold;
  }
</style>

</head>
<body>

<div id="content">
  <h1>Noticias</h1>

<h3>21-2-2025 Migraci&oacute;n de Infraestructura a Oracle Cloud</h3>
<p>
  Informamos que entre el <strong>21 de marzo a las 23:00 hrs</strong> y el <strong>23 de marzo</strong> se realizar&aacute; la migraci&oacute;n de toda la operaci&oacute;n a Oracle Cloud. 
  Por favor revise los detalles en la siguiente carta: 
  <a href="https://objectstorage.sa-santiago-1.oraclecloud.com/n/axbdf1lh9yzq/b/Documentos/o/Carta%20Migraci%C3%B3n%2021-2-2025.pdf" target="_blank">Carta de Migraci&oacute;n</a>.
</p>


<h3>3-1-2025 Mantenci&oacute;n de Respaldos por 8 a&ntilde;os</h3>
<p>Durante el a&ntilde;o 2025 mantendremos los respaldos por 8 a&ntilde;os de los datos hist&oacute;ricos.</p>

  <h3>20-10-2024 Eliminaci&oacute;n de Datos Antiguos</h3>
  <p>Ya est&aacute; disponible la descarga de los archivos hist&oacute;ricos, se van a comenzar a generar esta semana, y el link de descarga se encuentra en: "DTE Emitidos" -> "Descarga XML".</p>
  <p>Si requiere que se mantengan por tiempo superior contacte a soporte@opendte.com.</p>
</div>

</body>
</html>

