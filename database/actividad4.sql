-- ============================================================
-- EventHub - Script de Base de Datos
-- Ejecutar este archivo en phpMyAdmin o desde la consola MySQL
-- ============================================================

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS eventhub
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE eventhub;

-- ============================================================
-- TABLA: usuarios
-- Almacena los datos de registro y perfil de cada usuario.
-- La contraseña se guarda hasheada con password_hash().
-- ============================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100)  NOT NULL,
    email       VARCHAR(150)  NOT NULL UNIQUE,
    password    VARCHAR(255)  NOT NULL,          -- hash generado con password_hash()
    avatar      VARCHAR(255)  DEFAULT 'default.png',
    bio         TEXT          DEFAULT NULL,
    fecha_registro DATETIME  DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA: eventos
-- Cada evento tiene un creador (usuario), título, descripción,
-- fecha/hora, ubicación y coordenadas para el mapa.
-- ============================================================
CREATE TABLE IF NOT EXISTS eventos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT           NOT NULL,
    titulo      VARCHAR(200)  NOT NULL,
    descripcion TEXT          NOT NULL,
    fecha_evento DATETIME     NOT NULL,
    ubicacion   VARCHAR(255)  NOT NULL,
    latitud     DECIMAL(10,7) DEFAULT NULL,
    longitud    DECIMAL(10,7) DEFAULT NULL,
    imagen      VARCHAR(255)  DEFAULT 'evento_default.jpg',
    categoria   VARCHAR(100)  DEFAULT 'General',
    fecha_creacion DATETIME   DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA: comentarios
-- Los usuarios pueden comentar en cualquier evento.
-- ============================================================
CREATE TABLE IF NOT EXISTS comentarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    evento_id   INT           NOT NULL,
    usuario_id  INT           NOT NULL,
    contenido   TEXT          NOT NULL,
    fecha_comentario DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (evento_id)  REFERENCES eventos(id)  ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DATOS DE EJEMPLO
-- Insertamos un usuario demo y algunos eventos para pruebas
-- Contraseña del usuario demo: "Demo1234"
-- ============================================================

-- Usuario demo (password: Demo1234)
INSERT INTO usuarios (nombre, email, password, bio) VALUES
('Admin EventHub', 'admin@eventhub.com',
 '$2y$10$YJ0kUQ1LcD0sF0vE3bW0AO7yR1Xz5r9hKjG6mN8pQ4wT2uV6xYzCa',
 'Administrador de la plataforma EventHub.'),
('María García', 'maria@ejemplo.com',
 '$2y$10$YJ0kUQ1LcD0sF0vE3bW0AO7yR1Xz5r9hKjG6mN8pQ4wT2uV6xYzCa',
 'Amante de la tecnología y los eventos culturales.'),
('Carlos López', 'carlos@ejemplo.com',
 '$2y$10$YJ0kUQ1LcD0sF0vE3bW0AO7yR1Xz5r9hKjG6mN8pQ4wT2uV6xYzCa',
 'Organizador de meetups de programación.');

-- Eventos de ejemplo
INSERT INTO eventos (usuario_id, titulo, descripcion, fecha_evento, ubicacion, latitud, longitud, categoria) VALUES
(1, 'Hackathon Buenos Aires 2026',
 'Un fin de semana intensivo de programación donde equipos de 3 a 5 personas compiten para crear soluciones innovadoras. Habrá mentores, premios y mucha pizza.',
 '2026-05-15 09:00:00', 'Centro Cultural Kirchner, Buenos Aires', -34.6037, -58.3816, 'Tecnología'),
(2, 'Festival de Jazz Nocturno',
 'Disfrutá de las mejores bandas de jazz en vivo bajo las estrellas. Incluye food trucks, bebidas artesanales y una zona lounge.',
 '2026-06-20 20:00:00', 'Parque Centenario, Buenos Aires', -34.6066, -58.4352, 'Música'),
(1, 'Workshop de Diseño UX/UI',
 'Taller práctico de 4 horas donde aprenderás los fundamentos del diseño de experiencia de usuario. Traé tu notebook.',
 '2026-05-28 14:00:00', 'WeWork Retiro, Buenos Aires', -34.5922, -58.3740, 'Educación'),
(3, 'Meetup de Desarrolladores PHP',
 'Charlas sobre las novedades de PHP 8.3, frameworks modernos y buenas prácticas. Networking con developers de la comunidad.',
 '2026-07-10 18:30:00', 'Espacio Sinergia, Palermo', -34.5875, -58.4300, 'Tecnología'),
(2, 'Exposición de Arte Digital',
 'Muestra interactiva de arte generativo, NFTs y experiencias inmersivas. Artistas locales e internacionales.',
 '2026-08-05 10:00:00', 'Museo de Arte Moderno, San Telmo', -34.6215, -58.3693, 'Arte'),
(3, 'Torneo de E-Sports',
 'Competencia de League of Legends y Valorant con premios en efectivo. Inscripción gratuita para equipos amateur.',
 '2026-09-12 16:00:00', 'Arena GG, Microcentro', -34.6083, -58.3712, 'Gaming');

-- Comentarios de ejemplo
INSERT INTO comentarios (evento_id, usuario_id, contenido) VALUES
(1, 2, '¡Me encanta esta idea! ¿Se puede ir solo o hay que tener equipo armado?'),
(1, 3, 'El año pasado fue increíble. Este año no me lo pierdo.'),
(2, 1, 'Gran evento, ya reservé mi lugar. ¿Hay estacionamiento cerca?'),
(3, 3, 'Justo lo que necesitaba para mejorar mis skills de diseño.'),
(4, 1, 'PHP sigue siendo el rey del backend. ¡Nos vemos ahí!'),
(4, 2, 'Excelente, siempre aprendo algo nuevo en estas meetups.');
