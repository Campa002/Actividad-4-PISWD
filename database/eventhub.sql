-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-04-2026 a las 05:37:44
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `eventhub`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `evento_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_comentario` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `evento_id`, `usuario_id`, `contenido`, `fecha_comentario`) VALUES
(1, 1, 2, '¡Me encanta esta idea! ¿Se puede ir solo o hay que tener equipo armado?', '2026-04-06 00:22:31'),
(2, 1, 3, 'El año pasado fue increíble. Este año no me lo pierdo.', '2026-04-06 00:22:31'),
(3, 2, 1, 'Gran evento, ya reservé mi lugar. ¿Hay estacionamiento cerca?', '2026-04-06 00:22:31'),
(4, 3, 3, 'Justo lo que necesitaba para mejorar mis skills de diseño.', '2026-04-06 00:22:31'),
(5, 4, 1, 'PHP sigue siendo el rey del backend. ¡Nos vemos ahí!', '2026-04-06 00:22:31'),
(6, 4, 2, 'Excelente, siempre aprendo algo nuevo en estas meetups.', '2026-04-06 00:22:31'),
(7, 1, 4, 'muy top', '2026-04-06 00:25:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_evento` datetime NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `latitud` decimal(10,7) DEFAULT NULL,
  `longitud` decimal(10,7) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT 'evento_default.jpg',
  `categoria` varchar(100) DEFAULT 'General',
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id`, `usuario_id`, `titulo`, `descripcion`, `fecha_evento`, `ubicacion`, `latitud`, `longitud`, `imagen`, `categoria`, `fecha_creacion`) VALUES
(1, 1, 'Hackathon Buenos Aires 2026', 'Un fin de semana intensivo de programación donde equipos de 3 a 5 personas compiten para crear soluciones innovadoras. Habrá mentores, premios y mucha pizza.', '2026-05-15 09:00:00', 'Centro Cultural Kirchner, Buenos Aires', -34.6037000, -58.3816000, 'evento_default.jpg', 'Tecnología', '2026-04-06 00:22:31'),
(2, 2, 'Festival de Jazz Nocturno', 'Disfrutá de las mejores bandas de jazz en vivo bajo las estrellas. Incluye food trucks, bebidas artesanales y una zona lounge.', '2026-06-20 20:00:00', 'Parque Centenario, Buenos Aires', -34.6066000, -58.4352000, 'evento_default.jpg', 'Música', '2026-04-06 00:22:31'),
(3, 1, 'Workshop de Diseño UX/UI', 'Taller práctico de 4 horas donde aprenderás los fundamentos del diseño de experiencia de usuario. Traé tu notebook.', '2026-05-28 14:00:00', 'WeWork Retiro, Buenos Aires', -34.5922000, -58.3740000, 'evento_default.jpg', 'Educación', '2026-04-06 00:22:31'),
(4, 3, 'Meetup de Desarrolladores PHP', 'Charlas sobre las novedades de PHP 8.3, frameworks modernos y buenas prácticas. Networking con developers de la comunidad.', '2026-07-10 18:30:00', 'Espacio Sinergia, Palermo', -34.5875000, -58.4300000, 'evento_default.jpg', 'Tecnología', '2026-04-06 00:22:31'),
(5, 2, 'Exposición de Arte Digital', 'Muestra interactiva de arte generativo, NFTs y experiencias inmersivas. Artistas locales e internacionales.', '2026-08-05 10:00:00', 'Museo de Arte Moderno, San Telmo', -34.6215000, -58.3693000, 'evento_default.jpg', 'Arte', '2026-04-06 00:22:31'),
(6, 3, 'Torneo de E-Sports', 'Competencia de League of Legends y Valorant con premios en efectivo. Inscripción gratuita para equipos amateur.', '2026-09-12 16:00:00', 'Arena GG, Microcentro', -34.6083000, -58.3712000, 'evento_default.jpg', 'Gaming', '2026-04-06 00:22:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT 'default.png',
  `bio` text DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `avatar`, `bio`, `fecha_registro`) VALUES
(1, 'Admin EventHub', 'admin@eventhub.com', '$2y$10$YJ0kUQ1LcD0sF0vE3bW0AO7yR1Xz5r9hKjG6mN8pQ4wT2uV6xYzCa', 'default.png', 'Administrador de la plataforma EventHub.', '2026-04-06 00:22:31'),
(2, 'María García', 'maria@ejemplo.com', '$2y$10$YJ0kUQ1LcD0sF0vE3bW0AO7yR1Xz5r9hKjG6mN8pQ4wT2uV6xYzCa', 'default.png', 'Amante de la tecnología y los eventos culturales.', '2026-04-06 00:22:31'),
(3, 'Carlos López', 'carlos@ejemplo.com', '$2y$10$YJ0kUQ1LcD0sF0vE3bW0AO7yR1Xz5r9hKjG6mN8pQ4wT2uV6xYzCa', 'default.png', 'Organizador de meetups de programación.', '2026-04-06 00:22:31'),
(4, 'Juan', 'luchocampanelli1@gmail.com', '$2y$10$wGI.8xEIRRMM7EG2bUz13uMVriklyPz/SQyhcPwUTKkHUYpLg3Wx.', 'default.png', NULL, '2026-04-06 00:25:38');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evento_id` (`evento_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
