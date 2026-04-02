<?php
/**
 * header.php — Encabezado HTML reutilizable
 * 
 * Incluye:
 *  - Meta tags y SEO básico
 *  - Foundation CSS (CDN)
 *  - Hoja de estilos personalizada
 *  - Barra de navegación responsive
 * 
 * Se incluye al inicio de cada página con: include 'includes/header.php';
 * Antes de incluirlo, definir $titulo_pagina con el título de la página.
 */

// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Título por defecto si no se definió uno
if (!isset($titulo_pagina)) {
    $titulo_pagina = 'EventHub';
}
?>
<!DOCTYPE html>
<html lang="es" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EventHub — Descubrí y compartí los mejores eventos cerca tuyo.">
    <title><?php echo htmlspecialchars($titulo_pagina); ?> | EventHub</title>
    <link rel="icon" type="image/png" href="assets/img/favicon.png">

    <!-- Foundation CSS (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.8.1/dist/css/foundation.min.css"
        crossorigin="anonymous">

    <!-- Google Fonts — Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Estilos personalizados (5 archivos modulares) -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/layout.css">
    <link rel="stylesheet" href="assets/css/componentes.css">
    <link rel="stylesheet" href="assets/css/paginas.css">
    <link rel="stylesheet" href="assets/css/efectos.css">
</head>

<body>

    <!-- ═══════════════════════════════════════════════════════════
     BARRA DE NAVEGACIÓN — Foundation Top Bar Responsive
     ═══════════════════════════════════════════════════════════ -->
    <div class="top-bar-container" id="main-nav">
        <div class="top-bar">
            <!-- Logo / Nombre del sitio -->
            <div class="top-bar-left">
                <ul class="menu">
                    <li class="menu-text logo-text">
                        <a href="index.php">
                            <i class="fas fa-bolt neon-icon top"></i> Event<span class="neon-accent">Hub</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Botón hamburguesa para mobile -->
            <div class="top-bar-right">
                <button class="menu-toggle hide-for-medium" id="menu-toggle-btn" aria-label="Abrir menú">
                    <i class="fas fa-bars"></i>
                </button>

                <ul class="menu nav-links" id="nav-menu">
                    <li><a href="index.php"><i class="fas fa-calendar-alt"></i> Eventos</a></li>
                    <li><a href="mapa.php"><i class="fas fa-map-marker-alt"></i> Mapa</a></li>

                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <!-- Usuario logueado -->
                        <li><a href="perfil.php"><i class="fas fa-user-circle"></i> Mi Perfil</a></li>
                        <li><a href="backend/logout.php" class="btn-neon-sm"><i class="fas fa-sign-out-alt"></i> Salir</a>
                        </li>
                    <?php else: ?>
                        <!-- No logueado -->
                        <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Ingresar</a></li>
                        <li><a href="registro.php" class="btn-neon-sm"><i class="fas fa-user-plus"></i> Registrarse</a></li>
                    <?php endif; ?>

                    <!-- Botón modo oscuro -->
                    <li>
                        <button id="toggle-darkmode" class="dark-toggle" title="Cambiar tema"
                            aria-label="Cambiar modo oscuro">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <main class="main-content">