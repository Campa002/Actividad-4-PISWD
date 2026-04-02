<?php
/**
 * perfil.php — Página de perfil del usuario logueado
 * 
 * Muestra los datos del usuario, sus eventos creados
 * y sus últimos comentarios. Solo accesible si hay sesión activa.
 */

session_start();

// ── Proteger la página: redirigir si no está logueado ─────
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['errores_login'] = ['Tenés que iniciar sesión para ver tu perfil.'];
    header('Location: login.php');
    exit;
}

$titulo_pagina = 'Mi Perfil';
require_once 'includes/conexion.php';
require_once 'includes/header.php';

$usuario_id = $_SESSION['usuario_id'];

// ── Obtener datos completos del usuario ───────────────────
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->execute([':id' => $usuario_id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    // Si el usuario no existe en la BD (sesión corrupta)
    session_destroy();
    header('Location: login.php');
    exit;
}

// ── Obtener eventos creados por el usuario ────────────────
$stmt_eventos = $pdo->prepare("
    SELECT * FROM eventos 
    WHERE usuario_id = :id 
    ORDER BY fecha_evento DESC
");
$stmt_eventos->execute([':id' => $usuario_id]);
$mis_eventos = $stmt_eventos->fetchAll();

// ── Obtener últimos comentarios del usuario ───────────────
$stmt_comentarios = $pdo->prepare("
    SELECT c.*, e.titulo AS evento_titulo 
    FROM comentarios c
    JOIN eventos e ON c.evento_id = e.id
    WHERE c.usuario_id = :id
    ORDER BY c.fecha_comentario DESC
    LIMIT 10
");
$stmt_comentarios->execute([':id' => $usuario_id]);
$mis_comentarios = $stmt_comentarios->fetchAll();

// ── Estadísticas del usuario ──────────────────────────────
$total_eventos     = count($mis_eventos);
$total_comentarios = count($mis_comentarios);
$fecha_registro    = date('d/m/Y', strtotime($usuario['fecha_registro']));
?>

<section class="perfil-section">
    <div class="grid-container">

        <!-- ══════════════════════════════════════════════════
             CABECERA DEL PERFIL
             ══════════════════════════════════════════════════ -->
        <div class="perfil-header">
            <div class="grid-x align-center">
                <div class="cell medium-8 text-center">
                    <div class="perfil-avatar">
                        <i class="fas fa-user-astronaut"></i>
                    </div>
                    <h1 class="perfil-nombre"><?php echo htmlspecialchars($usuario['nombre']); ?></h1>
                    <p class="perfil-email">
                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($usuario['email']); ?>
                    </p>
                    <?php if ($usuario['bio']): ?>
                        <p class="perfil-bio"><?php echo htmlspecialchars($usuario['bio']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════
             ESTADÍSTICAS
             ══════════════════════════════════════════════════ -->
        <div class="grid-x grid-margin-x stats-row">
            <div class="cell medium-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-number"><?php echo $total_eventos; ?></div>
                    <div class="stat-label">Eventos Creados</div>
                </div>
            </div>
            <div class="cell medium-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-comments"></i></div>
                    <div class="stat-number"><?php echo $total_comentarios; ?></div>
                    <div class="stat-label">Comentarios</div>
                </div>
            </div>
            <div class="cell medium-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-number"><?php echo $fecha_registro; ?></div>
                    <div class="stat-label">Miembro desde</div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════
             MIS EVENTOS
             ══════════════════════════════════════════════════ -->
        <div class="perfil-seccion">
            <h2 class="section-title"><i class="fas fa-calendar-alt"></i> Mis Eventos</h2>
            
            <?php if (empty($mis_eventos)): ?>
                <div class="callout secondary text-center">
                    <p><i class="fas fa-info-circle"></i> Todavía no creaste ningún evento.</p>
                </div>
            <?php else: ?>
                <div class="grid-x grid-margin-x grid-margin-y">
                    <?php foreach ($mis_eventos as $evento): ?>
                    <div class="cell medium-6">
                        <div class="card evento-card-mini">
                            <div class="card-section">
                                <span class="card-categoria"><?php echo htmlspecialchars($evento['categoria']); ?></span>
                                <h4><?php echo htmlspecialchars($evento['titulo']); ?></h4>
                                <p class="card-meta">
                                    <span><i class="fas fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($evento['fecha_evento'])); ?></span>
                                    <span><i class="fas fa-map-pin"></i> <?php echo htmlspecialchars($evento['ubicacion']); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ══════════════════════════════════════════════════
             MIS COMENTARIOS
             ══════════════════════════════════════════════════ -->
        <div class="perfil-seccion">
            <h2 class="section-title"><i class="fas fa-comment-dots"></i> Últimos Comentarios</h2>
            
            <?php if (empty($mis_comentarios)): ?>
                <div class="callout secondary text-center">
                    <p><i class="fas fa-info-circle"></i> Todavía no hiciste ningún comentario.</p>
                </div>
            <?php else: ?>
                <div class="comentarios-perfil">
                    <?php foreach ($mis_comentarios as $com): ?>
                        <div class="comentario-perfil-item">
                            <div class="comentario-evento-ref">
                                <i class="fas fa-reply"></i> En: 
                                <strong><?php echo htmlspecialchars($com['evento_titulo']); ?></strong>
                            </div>
                            <p><?php echo htmlspecialchars($com['contenido']); ?></p>
                            <small class="comentario-fecha">
                                <?php echo date('d/m/Y H:i', strtotime($com['fecha_comentario'])); ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
