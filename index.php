<?php
/**
 * PortalDTE - Index Principal
 * Redirección automática al frontend moderno
 */
require_once __DIR__ . '/include/frontend_config.php';

session_start();

// Si no está autenticado, ir al login
if (empty($_SESSION["_COD_USU_SESS"])) {
    header("Location: " . getLoginUrl());
    exit;
}

// Redirigir al nuevo frontend
header("Location: " . getIndexUrl());
exit;
?>
