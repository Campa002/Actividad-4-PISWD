<?php
/**
 * conexion.php — Conexión a la base de datos MySQL usando PDO
 * 
 * Este archivo se incluye en todos los scripts que necesiten
 * comunicarse con la base de datos. Usa PDO con prepared statements
 * para prevenir inyecciones SQL.
 * 
 * Configuración para XAMPP local por defecto.
 */

// ── Datos de conexión ──────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'actividad4');
define('DB_USER', 'root');
define('DB_PASS', '');           // XAMPP no tiene password por defecto
define('DB_CHARSET', 'utf8mb4');

// ── Crear la conexión PDO ──────────────────────────────────
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    $opciones = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Lanza excepciones en errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Devuelve arrays asociativos
        PDO::ATTR_EMULATE_PREPARES => false,                    // Prepared statements nativos
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);

} catch (PDOException $e) {
    // En producción, loguear el error en vez de mostrarlo
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>