<?php
/**
 * PortalDTE - Validacion de Usuario
 * Actualizado con mejoras de seguridad (Fase 1)
 *
 * IMPORTANTE: Mantener codificacion ISO-8859-1 por compatibilidad con SII
 */

  include("include/config.php");
  include("include/db_lib.php");
  include("include/frontend_config.php");

  // Aplicar headers de seguridad
  setSecurityHeaders();

  $conn = conn();

  // Sanitizar entrada
  $sUser = trim($_POST["sUser"] ?? '');
  $sClave = $_POST["sClave"] ?? '';

  function exisUser($conn){
    global $sUser, $sClave;

    // Consulta SQL usando escape seguro (compatible con migracion gradual de passwords)
    $sql = "SELECT cod_usu, cod_rol, est_usu, pass_usu, ";
    $sql .= "   (select codi_empr from empr_usu where codi_empr in (select codi_empr from empresa where is_gpuerto = 1) and cod_usu=usuario.cod_usu limit 1) as gpuerto ";
    $sql .= " FROM usuario ";
    $sql .= " WHERE id_usu = " . escapeSQL($sUser, $conn);

    $result = rCursor($conn, $sql);

    if(!$result->EOF) {
      $sCodUsu = trim($result->fields["cod_usu"]);
      $sCodRol = trim($result->fields["cod_rol"]);
      $sEstUsu = trim($result->fields["est_usu"]);
      $nGPuerto = trim($result->fields["gpuerto"]);
      $storedPassword = $result->fields["pass_usu"];

      if($nGPuerto != "") $nGPuerto = "1";

      // Verificar contrasena con soporte para migracion gradual a hash
      $passwordCallback = function($newHash) use ($conn, $sCodUsu) {
          // Actualizar contrasena a hash en BD
          $sqlUpdate = "UPDATE usuario SET pass_usu = " . escapeSQL($newHash, $conn) .
                       " WHERE cod_usu = " . escapeSQL($sCodUsu, $conn);
          nrExecuta($conn, $sqlUpdate);
      };

      $passwordValid = verifyAndMigratePassword($sClave, $storedPassword, $passwordCallback);

      if (!$passwordValid) {
          noValido("Clave Incorrecta", $sUser);
          return;
      }

      if($sEstUsu == "0") {
        noValido("Usuario Deshabilitado", $sUser);
      } else {
         // Iniciar sesion de forma segura
         secureSessionStart();

         $_SESSION["_COD_USU_SESS"] = $sCodUsu;
         $_SESSION["_COD_ROL_SESS"] = $sCodRol;
         $_SESSION["_ALIAS_USU_SESS"] = $sUser;
         $_SESSION["_GPUERTO_"] = $nGPuerto;

         // Redirigir segun rol
         if($sCodRol == "1")
            header("location:" . getIndexUrl());
         else
            header("location:" . getSelEmpUrl());
         exit;
      }
    }
    else {
      noValido("Clave Incorrecta", $sUser);
    }
  }

  function noValido($_MSG, $sUser){
    header("location:" . getLoginUrl() . "?sUser=" . escapeURL($sUser) . "&sMsgJs=" . escapeURL($_MSG));
    exit;
  }

  exisUser($conn);

?>
