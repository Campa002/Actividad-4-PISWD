<?php
/**
 * login.php — Formulario de inicio de sesión
 * 
 * Formulario estilizado con Foundation que envía los datos
 * a backend/login.php para su procesamiento.
 * Muestra errores de validación si los hubiera.
 */

// Redirigir si ya está logueado
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$titulo_pagina = 'Iniciar Sesión';
require_once 'includes/header.php';

// ── Recoger errores y datos previos (si existen) ──────────
$errores = $_SESSION['errores_login'] ?? [];
$datos   = $_SESSION['datos_login']   ?? [];
unset($_SESSION['errores_login'], $_SESSION['datos_login']);
?>

<section class="auth-section">
    <div class="grid-container">
        <div class="grid-x align-center">
            <div class="cell small-12 medium-6 large-5">
                
                <div class="auth-card">
                    <!-- Ícono y título -->
                    <div class="auth-header text-center">
                        <div class="auth-icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <h1 class="auth-title">Iniciar Sesión</h1>
                        <p class="auth-subtitle">Ingresá a tu cuenta de EventHub</p>
                    </div>

                    <!-- Errores de validación -->
                    <?php if (!empty($errores)): ?>
                        <div class="callout alert" data-closable>
                            <ul class="no-bullet">
                                <?php foreach ($errores as $error): ?>
                                    <li><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button class="close-button" aria-label="Cerrar" type="button" data-close>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario -->
                    <form action="backend/login.php" method="POST" id="form-login">
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="email" name="email" 
                                   placeholder="tu@email.com"
                                   value="<?php echo htmlspecialchars($datos['email'] ?? ''); ?>"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-lock"></i> Contraseña
                            </label>
                            <div class="password-wrapper">
                                <input type="password" id="password" name="password" 
                                       placeholder="Tu contraseña"
                                       required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Mostrar contraseña">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="button btn-neon expanded">
                            <i class="fas fa-sign-in-alt"></i> Ingresar
                        </button>
                    </form>

                    <!-- Link a registro -->
                    <div class="auth-footer text-center">
                        <p>¿No tenés cuenta? 
                            <a href="registro.php" class="neon-link">Registrate gratis</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
// Función para mostrar/ocultar contraseña
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling.querySelector('i');
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>
