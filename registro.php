<?php
/**
 * registro.php — Formulario de registro de nuevo usuario
 * 
 * Formulario completo con validación visual que envía los datos
 * a backend/registro.php. Incluye campo de confirmación de contraseña.
 */

// Redirigir si ya está logueado
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$titulo_pagina = 'Registro';
require_once 'includes/header.php';

// ── Recoger errores y datos previos (si existen) ──────────
$errores = $_SESSION['errores_registro'] ?? [];
$datos   = $_SESSION['datos_registro']   ?? [];
unset($_SESSION['errores_registro'], $_SESSION['datos_registro']);
?>

<section class="auth-section">
    <div class="grid-container">
        <div class="grid-x align-center">
            <div class="cell small-12 medium-6 large-5">
                
                <div class="auth-card">
                    <!-- Ícono y título -->
                    <div class="auth-header text-center">
                        <div class="auth-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h1 class="auth-title">Crear Cuenta</h1>
                        <p class="auth-subtitle">Unite a la comunidad EventHub</p>
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
                    <form action="backend/registro.php" method="POST" id="form-registro">
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-user"></i> Nombre completo
                            </label>
                            <input type="text" id="nombre" name="nombre" 
                                   placeholder="Tu nombre"
                                   value="<?php echo htmlspecialchars($datos['nombre'] ?? ''); ?>"
                                   minlength="3" required>
                        </div>

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
                                       placeholder="Mínimo 6 caracteres"
                                       minlength="6" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Mostrar contraseña">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength" id="password-strength"></div>
                        </div>

                        <div class="form-group">
                            <label for="confirm">
                                <i class="fas fa-lock"></i> Confirmar contraseña
                            </label>
                            <div class="password-wrapper">
                                <input type="password" id="confirm" name="confirm" 
                                       placeholder="Repetí tu contraseña"
                                       minlength="6" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('confirm')" aria-label="Mostrar contraseña">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="button btn-neon expanded">
                            <i class="fas fa-user-plus"></i> Crear mi cuenta
                        </button>
                    </form>

                    <!-- Link a login -->
                    <div class="auth-footer text-center">
                        <p>¿Ya tenés cuenta? 
                            <a href="login.php" class="neon-link">Iniciá sesión</a>
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

// Indicador de fuerza de contraseña
document.getElementById('password').addEventListener('input', function() {
    const strength = document.getElementById('password-strength');
    const val = this.value;
    let nivel = 0;
    let texto = '';
    let clase = '';

    if (val.length >= 6)  nivel++;
    if (val.length >= 10) nivel++;
    if (/[A-Z]/.test(val)) nivel++;
    if (/[0-9]/.test(val)) nivel++;
    if (/[^A-Za-z0-9]/.test(val)) nivel++;

    if (val.length === 0) {
        strength.innerHTML = '';
        return;
    }

    if (nivel <= 2) {
        texto = 'Débil';
        clase = 'strength-weak';
    } else if (nivel <= 3) {
        texto = 'Media';
        clase = 'strength-medium';
    } else {
        texto = 'Fuerte';
        clase = 'strength-strong';
    }

    strength.innerHTML = '<div class="strength-bar ' + clase + '"></div><small>' + texto + '</small>';
});
</script>

<?php require_once 'includes/footer.php'; ?>
