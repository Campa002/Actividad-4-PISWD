<?php
/**
 * backend/registro.php — Procesa el formulario de registro
 * 
 * Recibe los datos por POST, valida, hashea la contraseña
 * con password_hash() y crea el usuario en la base de datos.
 * Usa prepared statements para prevenir SQL injection.
 */

session_start();

// Incluir conexión a la base de datos
require_once __DIR__ . '/../includes/conexion.php';

// ── Solo aceptar peticiones POST ───────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../registro.php');
    exit;
}

// ── Recoger y sanear datos del formulario ─────────────────
$nombre   = trim($_POST['nombre']   ?? '');
$email    = trim($_POST['email']    ?? '');
$password = $_POST['password']      ?? '';
$confirm  = $_POST['confirm']       ?? '';

// ── Validaciones ──────────────────────────────────────────
$errores = [];

if (empty($nombre)) {
    $errores[] = 'El nombre es obligatorio.';
} elseif (strlen($nombre) < 3) {
    $errores[] = 'El nombre debe tener al menos 3 caracteres.';
}

if (empty($email)) {
    $errores[] = 'El email es obligatorio.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El email no tiene un formato válido.';
}

if (empty($password)) {
    $errores[] = 'La contraseña es obligatoria.';
} elseif (strlen($password) < 6) {
    $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
}

if ($password !== $confirm) {
    $errores[] = 'Las contraseñas no coinciden.';
}

// ── Verificar si el email ya está registrado ──────────────
if (empty($errores)) {
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);

    if ($stmt->fetch()) {
        $errores[] = 'Ya existe una cuenta con este email.';
    }
}

// ── Si hay errores, volver al formulario ──────────────────
if (!empty($errores)) {
    $_SESSION['errores_registro'] = $errores;
    $_SESSION['datos_registro']   = ['nombre' => $nombre, 'email' => $email];
    header('Location: ../registro.php');
    exit;
}

// ── Hashear la contraseña ─────────────────────────────────
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// ── Insertar el usuario en la base de datos ───────────────
try {
    $stmt = $pdo->prepare("
        INSERT INTO usuarios (nombre, email, password) 
        VALUES (:nombre, :email, :password)
    ");
    $stmt->execute([
        ':nombre'   => $nombre,
        ':email'    => $email,
        ':password' => $password_hash
    ]);

    // Obtener el ID del usuario recién creado
    $usuario_id = $pdo->lastInsertId();

    // Iniciar sesión automáticamente después del registro
    $_SESSION['usuario_id']     = $usuario_id;
    $_SESSION['usuario_nombre'] = $nombre;
    $_SESSION['usuario_email']  = $email;

    $_SESSION['mensaje_exito'] = '¡Bienvenido a EventHub, ' . htmlspecialchars($nombre) . '! Tu cuenta fue creada con éxito.';
    header('Location: ../index.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['errores_registro'] = ['Error al crear la cuenta. Intentá de nuevo más tarde.'];
    header('Location: ../registro.php');
    exit;
}
?>
