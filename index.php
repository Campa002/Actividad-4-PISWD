<?php

$titulo_pagina = 'Eventos';
require_once 'includes/conexion.php';
require_once 'includes/header.php';

// ── Recoger mensaje flash (si existe) ─────────────────────
$mensaje_exito = $_SESSION['mensaje_exito'] ?? null;
unset($_SESSION['mensaje_exito']);

$error_comentario = $_SESSION['error_comentario'] ?? null;
unset($_SESSION['error_comentario']);

// ── Obtener todos los eventos con datos del creador ───────
$stmt = $pdo->query("
    SELECT e.*, u.nombre AS creador_nombre
    FROM eventos e
    JOIN usuarios u ON e.usuario_id = u.id
    ORDER BY e.fecha_evento ASC
");
$eventos = $stmt->fetchAll();

// ── Obtener comentarios agrupados por evento ──────────────
$stmt_comentarios = $pdo->query("
    SELECT c.*, u.nombre AS autor_nombre
    FROM comentarios c
    JOIN usuarios u ON c.usuario_id = u.id
    ORDER BY c.fecha_comentario ASC
");
$todos_comentarios = $stmt_comentarios->fetchAll();

// Agrupar comentarios por evento_id
$comentarios_por_evento = [];
foreach ($todos_comentarios as $com) {
    $comentarios_por_evento[$com['evento_id']][] = $com;
}

// ── Imágenes por categoría (Unsplash, uso libre) ──────────
$imagenes_categoria = [
    'Tecnología' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=600&h=300&fit=crop',
    'Música'     => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=300&fit=crop',
    'Educación'  => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=600&h=300&fit=crop',
    'Arte'       => 'https://images.unsplash.com/photo-1561214115-f2f134cc4912?w=600&h=300&fit=crop',
    'Gaming'     => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=600&h=300&fit=crop',
    'General'    => 'https://images.unsplash.com/photo-1540575467063-178a50da2fd8?w=600&h=300&fit=crop',
];
?>

<!-- ═══════════════════════════════════════════════════════════
     HERO SECTION
     ═══════════════════════════════════════════════════════════ -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="grid-container hero-content">
        <div class="grid-x align-center">
            <div class="cell medium-8 text-center">
                <h1 class="hero-title">
                    <i class="fas fa-bolt neon-icon-lg"></i>
                    Descubrí los mejores <span class="neon-text">eventos</span>
                </h1>
                <p class="hero-subtitle">
                    Conectá con la comunidad, explorá experiencias únicas y no te pierdas nada.
                </p>
                <?php if (!isset($_SESSION['usuario_id'])): ?>
                    <a href="registro.php" class="button btn-neon large">
                        <i class="fas fa-rocket"></i> Comenzar Ahora
                    </a>
                <?php else: ?>
                    <p class="hero-welcome">
                        <i class="fas fa-hand-peace"></i>
                        ¡Hola, <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></strong>!
                        Explorá los eventos disponibles.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Partículas decorativas -->
    <div class="hero-particles">
        <span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span><span></span>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     MENSAJE FLASH
     ═══════════════════════════════════════════════════════════ -->
<?php if ($mensaje_exito): ?>
    <div class="grid-container">
        <div class="callout success flash-message" data-closable>
            <i class="fas fa-check-circle"></i> <?php echo $mensaje_exito; ?>
            <button class="close-button" aria-label="Cerrar" type="button" data-close>
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
<?php endif; ?>

<?php if ($error_comentario): ?>
    <div class="grid-container">
        <div class="callout alert flash-message" data-closable>
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_comentario); ?>
            <button class="close-button" aria-label="Cerrar" type="button" data-close>
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     LISTADO DE EVENTOS
     ═══════════════════════════════════════════════════════════ -->
<section class="eventos-section">
    <div class="grid-container">
        <div class="section-header text-center">
            <h2 class="section-title"><i class="fas fa-fire"></i> Próximos Eventos</h2>
            <p class="section-subtitle">Encontrá tu próxima experiencia</p>
        </div>

        <?php if (empty($eventos)): ?>
            <div class="text-center callout">
                <i class="fas fa-calendar-times fa-3x" style="opacity:0.3;"></i>
                <p>No hay eventos disponibles todavía.</p>
            </div>
        <?php else: ?>
            <div class="grid-x grid-margin-x grid-margin-y">
                <?php foreach ($eventos as $evento): ?>
                    <div class="cell medium-6 large-4" id="evento-<?php echo $evento['id']; ?>">
                        <div class="card evento-card">
                            <!-- Imagen del evento -->
                            <div class="card-image-container">
                                <?php
                                    $cat = $evento['categoria'];
                                    $img_url = $imagenes_categoria[$cat] ?? $imagenes_categoria['General'];
                                ?>
                                <img src="<?php echo $img_url; ?>" 
                                     alt="<?php echo htmlspecialchars($evento['titulo']); ?>"
                                     class="card-image"
                                     loading="lazy">
                                <span class="card-categoria"><?php echo htmlspecialchars($evento['categoria']); ?></span>
                            </div>

                            <!-- Contenido de la card -->
                            <div class="card-section">
                                <h3 class="card-title"><?php echo htmlspecialchars($evento['titulo']); ?></h3>

                                <div class="card-meta">
                                    <span><i class="fas fa-calendar"></i>
                                        <?php echo date('d/m/Y H:i', strtotime($evento['fecha_evento'])); ?>
                                    </span>
                                    <span><i class="fas fa-map-pin"></i>
                                        <?php echo htmlspecialchars($evento['ubicacion']); ?>
                                    </span>
                                    <span><i class="fas fa-user"></i>
                                        <?php echo htmlspecialchars($evento['creador_nombre']); ?>
                                    </span>
                                </div>

                                <p class="card-desc">
                                    <?php echo htmlspecialchars(mb_strimwidth($evento['descripcion'], 0, 150, '...')); ?>
                                </p>

                                <!-- ── Sección de Comentarios ──────────────── -->
                                <div class="comentarios-section">
                                    <h4 class="comentarios-titulo">
                                        <i class="fas fa-comments"></i>
                                        Comentarios
                                        <span class="badge">
                                            <?php echo count($comentarios_por_evento[$evento['id']] ?? []); ?>
                                        </span>
                                    </h4>

                                    <!-- Lista de comentarios existentes -->
                                    <?php if (!empty($comentarios_por_evento[$evento['id']])): ?>
                                        <div class="comentarios-lista">
                                            <?php foreach ($comentarios_por_evento[$evento['id']] as $com): ?>
                                                <div class="comentario-item">
                                                    <div class="comentario-autor">
                                                        <i class="fas fa-user-circle"></i>
                                                        <strong><?php echo htmlspecialchars($com['autor_nombre']); ?></strong>
                                                        <small class="comentario-fecha">
                                                            <?php echo date('d/m/Y H:i', strtotime($com['fecha_comentario'])); ?>
                                                        </small>
                                                    </div>
                                                    <p class="comentario-texto"><?php echo htmlspecialchars($com['contenido']); ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="sin-comentarios"><em>Sé el primero en comentar.</em></p>
                                    <?php endif; ?>

                                    <!-- Formulario de comentario (solo logueados) -->
                                    <?php if (isset($_SESSION['usuario_id'])): ?>
                                        <form action="backend/comentar.php" method="POST" class="comentario-form">
                                            <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                                            <div class="input-group">
                                                <input type="text" name="contenido" class="input-group-field"
                                                    placeholder="Escribí un comentario..." maxlength="1000" required>
                                                <div class="input-group-button">
                                                    <button type="submit" class="button btn-neon-sm" title="Enviar">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <p class="login-para-comentar">
                                            <a href="login.php"><i class="fas fa-sign-in-alt"></i> Iniciá sesión para comentar</a>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>