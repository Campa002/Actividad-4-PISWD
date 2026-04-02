<?php
/**
 * backend/comentar.php — Procesa el envío de comentarios en eventos
 * 
 * Solo usuarios logueados pueden comentar.
 * Usa prepared statements para insertar el comentario en la BD.
 */

session_start();

// Incluir conexión
require_once __DIR__ . '/../includes/conexion.php';

// ── Solo POST y usuarios logueados ────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['errores_login'] = ['Tenés que iniciar sesión para comentar.'];
    header('Location: ../login.php');
    exit;
}

// ── Recoger datos ─────────────────────────────────────────
$evento_id  = intval($_POST['evento_id']  ?? 0);
$contenido  = trim($_POST['contenido']    ?? '');
$usuario_id = $_SESSION['usuario_id'];

// ── Validaciones ──────────────────────────────────────────
if ($evento_id <= 0) {
    $_SESSION['error_comentario'] = 'Evento no válido.';
    header('Location: ../index.php');
    exit;
}

if (empty($contenido)) {
    $_SESSION['error_comentario'] = 'El comentario no puede estar vacío.';
    header('Location: ../index.php#evento-' . $evento_id);
    exit;
}

if (strlen($contenido) > 1000) {
    $_SESSION['error_comentario'] = 'El comentario no puede superar los 1000 caracteres.';
    header('Location: ../index.php#evento-' . $evento_id);
    exit;
}

// ── Insertar comentario ───────────────────────────────────
try {
    $stmt = $pdo->prepare("
        INSERT INTO comentarios (evento_id, usuario_id, contenido) 
        VALUES (:evento_id, :usuario_id, :contenido)
    ");
    $stmt->execute([
        ':evento_id'  => $evento_id,
        ':usuario_id' => $usuario_id,
        ':contenido'  => $contenido
    ]);

    $_SESSION['mensaje_exito'] = '¡Comentario publicado!';
    header('Location: ../index.php#evento-' . $evento_id);
    exit;

} catch (PDOException $e) {
    $_SESSION['error_comentario'] = 'Error al publicar el comentario.';
    header('Location: ../index.php#evento-' . $evento_id);
    exit;
}
?>
