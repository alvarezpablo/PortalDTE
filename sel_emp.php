<?php
/**
 * PortalDTE - Selección de Empresa
 * Redirección automática al frontend moderno
 */
require_once __DIR__ . '/include/frontend_config.php';

// Pasar parámetros GET al nuevo archivo
$params = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
header("Location: " . getSelEmpUrl() . $params);
exit;

