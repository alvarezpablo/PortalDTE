<?php
/**
 * Configuración del Frontend - PortalDTE
 * 
 * Este archivo controla qué versión del frontend se utiliza.
 * Cambiar USE_NEW_FRONTEND a true para usar la versión modernizada.
 */

// Activar nuevo frontend con Bootstrap 5 (cambiar a true para probar)
define('USE_NEW_FRONTEND', true);

// Versión del frontend
define('FRONTEND_VERSION', '2.0.0');

/**
 * Obtener la URL de redirección para el index
 */
function getIndexUrl() {
    return USE_NEW_FRONTEND ? 'index_new.php' : 'index.php';
}

/**
 * Obtener la URL de redirección para el login
 */
function getLoginUrl() {
    return USE_NEW_FRONTEND ? 'login_new.php' : 'login.php';
}

/**
 * Obtener la URL para selección de empresa
 * Por ahora usa el original, se puede modernizar después
 */
function getSelEmpUrl() {
    return USE_NEW_FRONTEND ? 'sel_emp_new.php' : 'sel_emp.php';
}

