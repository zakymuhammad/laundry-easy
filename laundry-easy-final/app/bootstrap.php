<?php

// ============================================================
//  BOOTSTRAP  —  session + autoload + base url
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Root proyek (folder yang berisi /app)
define('APP_ROOT', dirname(__DIR__));

// Deteksi base URL proyek relatif terhadap document root (aman utk sub-folder)
$__fsRoot  = str_replace(DIRECTORY_SEPARATOR, '/', dirname(__DIR__));
$__docRoot = str_replace(DIRECTORY_SEPARATOR, '/', rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/'));
if ($__docRoot !== '' && strpos($__fsRoot, $__docRoot) === 0) {
    $__base = substr($__fsRoot, strlen($__docRoot));
} else {
    $__base = '';
}
define('BASE_URL', rtrim($__base, '/'));

// Autoload class dari Config / Core / Models / Controllers
spl_autoload_register(function ($class) {
    $dirs = ['/app/Config/', '/app/Core/', '/app/Models/', '/app/Controllers/'];
    foreach ($dirs as $d) {
        $file = APP_ROOT . $d . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});
