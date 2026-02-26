<?php
/**
 * PortalDTE - Login
 * Redirección automática al frontend moderno
 */
require_once __DIR__ . '/include/frontend_config.php';

// Redirigir al nuevo frontend
header("Location: " . getLoginUrl());
exit;
?>

