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
    <h3>22-11-2024 - Nuevos Planes a partir de 2025</h3>
    <p>A partir de marzo 2025 comenzaremos a ofrecer nuevos planes que incluir&aacute;n uptime de 99,9% real basados en la plataforma Cloud de Oracle.</p>
    <p>Si necesitas m&aacute;s informaci&oacute;n escr&iacute;benos a soporte@opendte.com.</p>
    <br>
  <h3>20-10-2024 - Eliminaci&oacute;n de Datos Antiguos</h3>
  <p>Ya est&aacute; disponible la descarga de los archivos hist&oacute;ricos, se van a comenzar a generar esta semana, y el link de descarga se encuentra en: "DTE Emitidos" -> "Descarga XML".</p>
  <p>Si requiere que se mantengan por tiempo superior contacte a soporte@opendte.com.</p>

</div>

</body>
</html>

