<?php
/**
 * PortalDTE - Libreria de Seguridad
 * Fase 1: Implementacion de mejoras de seguridad criticas
 * 
 * IMPORTANTE: Mantener codificacion ISO-8859-1 por compatibilidad con SII
 * 
 * @author OpenDTE Team
 * @version 1.0.0
 * @date 2025-11-29
 */

// Prevenir acceso directo
if (!defined('SECURITY_LIB_LOADED')) {
    define('SECURITY_LIB_LOADED', true);
}

/**
 * ============================================================================
 * 1.1 HASH DE CONTRASENAS
 * ============================================================================
 */

/**
 * Genera hash seguro de contrasena usando PASSWORD_DEFAULT (bcrypt)
 * @param string $password Contrasena en texto plano
 * @return string Hash de la contrasena
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verifica si una contrasena coincide con su hash
 * @param string $password Contrasena en texto plano
 * @param string $hash Hash almacenado
 * @return bool True si coincide, false si no
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Verifica si un hash necesita ser actualizado (por cambio de algoritmo)
 * @param string $hash Hash actual
 * @return bool True si necesita rehash
 */
function needsRehash($hash) {
    return password_needs_rehash($hash, PASSWORD_DEFAULT);
}

/**
 * Migra contrasena de texto plano a hash (para migracion gradual)
 * Compara primero con texto plano, si coincide genera hash
 * @param string $inputPassword Contrasena ingresada
 * @param string $storedPassword Contrasena almacenada (puede ser texto plano o hash)
 * @param callable $updateCallback Funcion para actualizar la contrasena en BD
 * @return bool True si la contrasena es valida
 */
function verifyAndMigratePassword($inputPassword, $storedPassword, $updateCallback = null) {
    // Si ya es un hash (empieza con $2y$ para bcrypt)
    if (strpos($storedPassword, '$2y$') === 0 || strpos($storedPassword, '$2a$') === 0) {
        return verifyPassword($inputPassword, $storedPassword);
    }
    
    // Es texto plano - comparar directamente
    if ($inputPassword === $storedPassword) {
        // Si hay callback, actualizar a hash
        if (is_callable($updateCallback)) {
            $newHash = hashPassword($inputPassword);
            $updateCallback($newHash);
        }
        return true;
    }
    
    return false;
}

/**
 * ============================================================================
 * 1.2 VARIABLES DE ENTORNO
 * ============================================================================
 */

/**
 * Carga variables de entorno desde archivo .env
 * @param string $path Ruta al archivo .env
 * @return bool True si se cargo correctamente
 */
function loadEnvFile($path = null) {
    if ($path === null) {
        $path = dirname(__DIR__) . '/.env';
    }
    
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorar comentarios
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parsear KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remover comillas si existen
            $value = trim($value, '"\'');
            
            // Setear en entorno
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
    
    return true;
}

/**
 * Obtiene variable de entorno con valor por defecto
 * @param string $key Nombre de la variable
 * @param mixed $default Valor por defecto
 * @return mixed Valor de la variable o default
 */
function env($key, $default = null) {
    $value = getenv($key);
    if ($value === false) {
        $value = isset($_ENV[$key]) ? $_ENV[$key] : $default;
    }
    return $value !== false ? $value : $default;
}

/**
 * ============================================================================
 * 1.3 PREPARED STATEMENTS - Consultas SQL Seguras
 * ============================================================================
 */

/**
 * Escapa un valor para uso seguro en SQL (compatible con ADOdb)
 * @param mixed $value Valor a escapar
 * @param object $conn Conexion ADOdb (opcional)
 * @return string Valor escapado
 */
function escapeSQL($value, $conn = null) {
    if ($value === null) {
        return 'NULL';
    }

    if (is_numeric($value)) {
        return $value;
    }

    // Usar qstr de ADOdb si esta disponible
    if ($conn !== null && method_exists($conn, 'qstr')) {
        return $conn->qstr($value);
    }

    // Fallback: escape manual
    return "'" . str_replace("'", "''", $value) . "'";
}

/**
 * Ejecuta consulta preparada con parametros (compatible con ADOdb)
 * @param object $conn Conexion ADOdb
 * @param string $sql SQL con placeholders (?)
 * @param array $params Parametros a insertar
 * @return mixed Resultado de la consulta
 */
function preparedQuery($conn, $sql, $params = array()) {
    // ADOdb soporta prepared statements
    $stmt = $conn->Prepare($sql);
    return $conn->Execute($stmt, $params);
}

/**
 * Construye clausula WHERE segura desde array de condiciones
 * @param array $conditions Array asociativo ['campo' => 'valor']
 * @param object $conn Conexion ADOdb
 * @param string $operator Operador logico (AND/OR)
 * @return string Clausula WHERE
 */
function buildWhereClause($conditions, $conn, $operator = 'AND') {
    $clauses = array();
    foreach ($conditions as $field => $value) {
        // Validar nombre de campo (solo alfanumerico y guion bajo)
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $field)) {
            continue;
        }
        $clauses[] = "$field = " . escapeSQL($value, $conn);
    }
    return implode(" $operator ", $clauses);
}

/**
 * ============================================================================
 * 1.4 PROTECCION CSRF
 * ============================================================================
 */

/**
 * Genera token CSRF y lo almacena en sesion
 * @return string Token CSRF
 */
function generateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $token = bin2hex(random_bytes(32));
    $_SESSION['_csrf_token'] = $token;
    $_SESSION['_csrf_token_time'] = time();

    return $token;
}

/**
 * Obtiene el token CSRF actual o genera uno nuevo
 * @return string Token CSRF
 */
function getCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['_csrf_token'])) {
        return generateCSRFToken();
    }

    // Regenerar si tiene mas de 1 hora
    if (time() - ($_SESSION['_csrf_token_time'] ?? 0) > 3600) {
        return generateCSRFToken();
    }

    return $_SESSION['_csrf_token'];
}

/**
 * Genera campo HTML hidden con token CSRF
 * @return string HTML del campo hidden
 */
function csrfField() {
    $token = getCSRFToken();
    return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token) . '">';
}

/**
 * Valida token CSRF del request
 * @param string $token Token recibido (opcional, toma de POST si no se pasa)
 * @return bool True si es valido
 */
function validateCSRFToken($token = null) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ($token === null) {
        $token = isset($_POST['_csrf_token']) ? $_POST['_csrf_token'] : '';
    }

    if (empty($_SESSION['_csrf_token']) || empty($token)) {
        return false;
    }

    return hash_equals($_SESSION['_csrf_token'], $token);
}

/**
 * ============================================================================
 * 1.5 ESCAPE XSS - Sanitizacion de Salida
 * ============================================================================
 */

/**
 * Escapa string para salida HTML segura
 * @param string $string Texto a escapar
 * @param string $encoding Codificacion (default ISO-8859-1 para SII)
 * @return string Texto escapado
 */
function e($string, $encoding = 'ISO-8859-1') {
    if ($string === null) {
        return '';
    }
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, $encoding);
}

/**
 * Alias de e() para compatibilidad
 */
function escape($string, $encoding = 'ISO-8859-1') {
    return e($string, $encoding);
}

/**
 * Escapa para uso en atributos HTML
 * @param string $string Texto a escapar
 * @return string Texto escapado para atributos
 */
function escapeAttr($string) {
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'ISO-8859-1');
}

/**
 * Escapa para uso en JavaScript
 * @param string $string Texto a escapar
 * @return string Texto escapado para JS
 */
function escapeJS($string) {
    return addslashes($string);
}

/**
 * Escapa para uso en URLs
 * @param string $string Texto a escapar
 * @return string Texto escapado para URL
 */
function escapeURL($string) {
    return urlencode($string);
}

/**
 * Limpia HTML peligroso de un string
 * @param string $string Texto a limpiar
 * @return string Texto sin tags HTML
 */
function stripTags($string, $allowedTags = '') {
    return strip_tags($string, $allowedTags);
}

/**
 * ============================================================================
 * 1.6 HEADERS DE SEGURIDAD
 * ============================================================================
 */

/**
 * Aplica headers de seguridad HTTP
 * Llamar al inicio de cada pagina antes de cualquier output
 */
function setSecurityHeaders() {
    // Prevenir clickjacking
    header('X-Frame-Options: SAMEORIGIN');

    // Prevenir MIME type sniffing
    header('X-Content-Type-Options: nosniff');

    // Habilitar XSS filter del navegador
    header('X-XSS-Protection: 1; mode=block');

    // Referrer Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Content Security Policy basica (ajustar segun necesidades)
    // Permite scripts inline por compatibilidad con codigo legacy
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://code.jquery.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data:; font-src 'self' https://cdn.jsdelivr.net; frame-ancestors 'self';");

    // Permissions Policy (antes Feature-Policy)
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}

/**
 * Configura cookie de sesion con flags seguros
 */
function setSecureSessionCookie() {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

/**
 * Inicia sesion de forma segura
 */
function secureSessionStart() {
    if (session_status() === PHP_SESSION_NONE) {
        setSecureSessionCookie();
        session_start();

        // Regenerar ID de sesion periodicamente
        if (!isset($_SESSION['_last_regeneration'])) {
            $_SESSION['_last_regeneration'] = time();
        } elseif (time() - $_SESSION['_last_regeneration'] > 300) {
            session_regenerate_id(true);
            $_SESSION['_last_regeneration'] = time();
        }
    }
}

/**
 * ============================================================================
 * UTILIDADES ADICIONALES
 * ============================================================================
 */

/**
 * Valida y sanitiza un email
 * @param string $email Email a validar
 * @return string|false Email valido o false
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Valida RUT chileno
 * @param string $rut RUT con formato XXXXXXXX-X
 * @return bool True si es valido
 */
function validateRUT($rut) {
    $rut = strtoupper(str_replace(['.', '-'], '', $rut));
    if (strlen($rut) < 2) return false;

    $dv = substr($rut, -1);
    $numero = substr($rut, 0, -1);

    if (!is_numeric($numero)) return false;

    $suma = 0;
    $factor = 2;

    for ($i = strlen($numero) - 1; $i >= 0; $i--) {
        $suma += $numero[$i] * $factor;
        $factor = $factor == 7 ? 2 : $factor + 1;
    }

    $dvCalculado = 11 - ($suma % 11);
    if ($dvCalculado == 11) $dvCalculado = '0';
    elseif ($dvCalculado == 10) $dvCalculado = 'K';
    else $dvCalculado = (string)$dvCalculado;

    return $dv === $dvCalculado;
}

/**
 * Genera un token aleatorio seguro
 * @param int $length Longitud del token en bytes
 * @return string Token en hexadecimal
 */
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length));
}

