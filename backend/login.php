<?php
/**
 * backend/login.php — Procesa el formulario de inicio de sesión
 * 
 * Verifica las credenciales usando password_verify() contra
 * el hash almacenado en la base de datos. Si son correctas,
 * crea la sesión del usuario.
 */

session_start();

// Incluir conexión a la base de datos
require_once __DIR__ . '/../includes/conexion.php';

// ── Solo aceptar peticiones POST ───────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

// ── Recoger datos del formulario ──────────────────────────
$email    = trim($_POST['email']    ?? '');
$password = $_POST['password']      ?? '';

// ── Validaciones básicas ──────────────────────────────────
$errores = [];

if (empty($email)) {
    $errores[] = 'El email es obligatorio.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El email no tiene un formato válido.';
}

if (empty($password)) {
    $errores[] = 'La contraseña es obligatoria.';
}

// ── Si hay errores de formato, volver al formulario ───────
if (!empty($errores)) {
    $_SESSION['errores_login'] = $errores;
    $_SESSION['datos_login']   = ['email' => $email];
    header('Location: ../login.php');
    exit;
}

// ── Buscar al usuario por email ───────────────────────────
try {
    $stmt = $pdo->prepare("SELECT id, nombre, email, password, avatar, bio FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    // ── Verificar contraseña ──────────────────────────────
    if ($usuario && password_verify($password, $usuario['password'])) {
        // Credenciales correctas: crear sesión
        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_email']  = $usuario['email'];
        $_SESSION['usuario_avatar'] = $usuario['avatar'];

        $_SESSION['mensaje_exito'] = '¡Hola de nuevo, ' . htmlspecialchars($usuario['nombre']) . '!';
        header('Location: ../index.php');
        exit;
    } else {
        // Credenciales inválidas (no revelar cuál dato es incorrecto)
        $_SESSION['errores_login'] = ['Email o contraseña incorrectos.'];
        $_SESSION['datos_login']   = ['email' => $email];
        header('Location: ../login.php');
        exit;
    }

} catch (PDOException $e) {
    $_SESSION['errores_login'] = ['Error al iniciar sesión. Intentá de nuevo.'];
    header('Location: ../login.php');
    exit;
}
?>
