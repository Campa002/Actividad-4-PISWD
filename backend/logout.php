<?php
/**
 * backend/logout.php — Cierra la sesión del usuario
 * 
 * Destruye la sesión actual y redirige al inicio.
 */

session_start();

// Limpiar todas las variables de sesión
$_SESSION = [];

// Destruir la cookie de sesión (si existe)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Redirigir al inicio
header('Location: ../index.php');
exit;
?>
