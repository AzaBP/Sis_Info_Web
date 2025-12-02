-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 02-12-2025 a las 19:00:52
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
-- Base de datos: `recomendador_musica_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cancion`
--

CREATE TABLE `cancion` (
  `nombre` varchar(100) NOT NULL,
  `nombre_creador` varchar(50) NOT NULL,
  `duración` time NOT NULL,
  `valoración` int(11) DEFAULT NULL CHECK (`valoración` between 0 and 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cancion`
--

INSERT INTO `cancion` (`nombre`, `nombre_creador`, `duración`, `valoración`) VALUES
('Bad Guy', 'billieeilish', '00:03:14', 4),
('Blinding Lights', 'theweeknd', '00:03:20', 4),
('Bohemian Rhapsody', 'queen', '00:05:55', 5),
('Dakiti', 'badbunny', '00:03:25', 4),
('Good 4 U', 'oliviarodrigo', '00:02:58', 5),
('Levitating', 'dualipa', '00:03:23', 5),
('Save Your Tears', 'theweeknd', '00:03:35', 4),
('Shape of You', 'edsheeran', '00:03:53', 4),
('Stay', 'thekidlaroi', '00:02:21', 4),
('Watermelon Sugar', 'harrystyles', '00:02:54', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `creador`
--

CREATE TABLE `creador` (
  `usuario_id` varchar(50) NOT NULL,
  `biografía` text DEFAULT NULL,
  `numero_seguidores` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `creador`
--

INSERT INTO `creador` (`usuario_id`, `biografía`, `numero_seguidores`) VALUES
('badbunny', 'Cantante, compositor y rapero puertorriqueño. Pionero del trap y reggaetón en español.', 25000000),
('billieeilish', 'Cantante y compositora estadounidense. Conocida por su estilo musical dark pop y sus letras introspectivas.', 30000000),
('dualipa', 'Cantante, compositora y modelo británica. Conocida por su estilo de pop dance y disco.', 22000000),
('edsheeran', 'Cantante, compositor y guitarrista británico. Conocido por sus canciones acústicas y estilo pop-folk.', 40000000),
('harrystyles', 'Cantante, compositor y actor británico. Ex miembro de One Direction, ahora con carrera solista de pop rock.', 28000000),
('justinbieber', 'Cantante, compositor y actor canadiense. Descubierto en YouTube, ahora uno de los artistas más vendidos.', 55000000),
('oliviarodrigo', 'Cantante, compositora y actriz estadounidense. Conocida por su estilo pop punk y letras emocionales.', 18000000),
('queen', 'Banda británica de rock formada en 1970. Conocida por sus extravagantes presentaciones y su diversidad musical.', 45000000),
('thekidlaroi', 'Cantante, rapero y compositor australiano. Conocido por sus colaboraciones con Justin Bieber.', 15000000),
('theweeknd', 'Cantante, compositor y productor canadiense. Conocido por su estilo de R&B alternativo y sonido atmosférico.', 35000000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista`
--

CREATE TABLE `lista` (
  `lista_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lista`
--

INSERT INTO `lista` (`lista_id`) VALUES
('chill_vibes'),
('exitos_2024'),
('hhhhh'),
('holi'),
('Lista'),
('Lista Prueba'),
('lista_001'),
('listu'),
('mis_favoritas'),
('party_hits'),
('pop_actual'),
('probando'),
('road_trip'),
('rock_clasico'),
('romantic_mood'),
('sos'),
('study_focus'),
('test_manual'),
('workout_mix');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oyente`
--

CREATE TABLE `oyente` (
  `usuario_id` varchar(50) NOT NULL,
  `preferencias` text DEFAULT NULL,
  `historial_reproduccion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `playlist`
--

CREATE TABLE `playlist` (
  `lista_id` varchar(100) NOT NULL,
  `nombre_cancion` varchar(100) NOT NULL,
  `nombre_creador` varchar(50) NOT NULL,
  `es_publica` tinyint(1) DEFAULT 1,
  `usuario_id` varchar(50) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `playlist`
--

INSERT INTO `playlist` (`lista_id`, `nombre_cancion`, `nombre_creador`, `es_publica`, `usuario_id`, `fecha_creacion`) VALUES
('chill_vibes', 'Save Your Tears', 'theweeknd', 1, NULL, '2025-12-02 17:44:06'),
('chill_vibes', 'Stay', 'thekidlaroi', 1, NULL, '2025-12-02 17:44:06'),
('chill_vibes', 'Watermelon Sugar', 'harrystyles', 1, NULL, '2025-12-02 17:44:06'),
('exitos_2024', 'Dakiti', 'badbunny', 1, NULL, '2025-12-02 17:44:06'),
('exitos_2024', 'Save Your Tears', 'theweeknd', 1, NULL, '2025-12-02 17:44:06'),
('exitos_2024', 'Stay', 'thekidlaroi', 1, NULL, '2025-12-02 17:44:06'),
('mis_favoritas', 'Blinding Lights', 'theweeknd', 1, NULL, '2025-12-02 17:44:06'),
('mis_favoritas', 'Bohemian Rhapsody', 'queen', 1, NULL, '2025-12-02 17:44:06'),
('mis_favoritas', 'Good 4 U', 'oliviarodrigo', 1, NULL, '2025-12-02 17:44:06'),
('pop_actual', 'Bad Guy', 'billieeilish', 1, NULL, '2025-12-02 17:44:06'),
('pop_actual', 'Levitating', 'dualipa', 1, NULL, '2025-12-02 17:44:06'),
('pop_actual', 'Watermelon Sugar', 'harrystyles', 1, NULL, '2025-12-02 17:44:06'),
('rock_clasico', 'Bohemian Rhapsody', 'queen', 1, NULL, '2025-12-02 17:44:06'),
('rock_clasico', 'Shape of You', 'edsheeran', 1, NULL, '2025-12-02 17:44:06'),
('workout_mix', 'Blinding Lights', 'theweeknd', 1, NULL, '2025-12-02 17:44:06'),
('workout_mix', 'Good 4 U', 'oliviarodrigo', 1, NULL, '2025-12-02 17:44:06'),
('workout_mix', 'Levitating', 'dualipa', 1, NULL, '2025-12-02 17:44:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suscripcion`
--

CREATE TABLE `suscripcion` (
  `codigo_suscripcion` varchar(50) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `precio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `suscripcion`
--

INSERT INTO `suscripcion` (`codigo_suscripcion`, `tipo`, `precio`) VALUES
('basica_001', 'Básica', 0),
('FREE', 'gratuita', 0),
('premium_001', 'Premium', 9.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuario_id` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `codigo_suscripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `nombre`, `password`, `correo`, `telefono`, `codigo_suscripcion`) VALUES
('318831d8b7522588b23ce2e0', 'Persona', '$2y$10$v7k80Ll.MmRjwcChlSmJvOpQNmjHwnz1z.27.gjXqDxY2icDgdB6S', 'correo@persona.com', '666666666', 'FREE'),
('5916648c0f382624e523e39a', 'Aza', '$2y$10$fxwNDLsCFEXl5DvgIaV3DO/99A.wRLgPkcZwNc9sB8q0TGHMTCDGG', 'aza@gmail.com', '666666666', 'FREE'),
('99bb67e341cbd2ab7295141a', 'Pepe', '$2y$10$5iJC4rAUqS6uOswpsMEne.xmJyLzUMzaOg6LVxLFZ/5EaJ8vb52l2', 'pepe@gmail.com', '666666666', 'FREE'),
('badbunny', 'Bad Bunny', '$2y$10$examplehashedpassword', 'badbunny@music.com', '+1787234567', 'FREE'),
('billieeilish', 'Billie Eilish', '$2y$10$examplehashedpassword', 'billie@music.com', '+1212555123', 'FREE'),
('creador001', 'Artista Uno', 'pass123', 'artista1@email.com', '111111111', 'premium_001'),
('dualipa', 'Dua Lipa', '$2y$10$examplehashedpassword', 'dua@music.com', '+4412345678', 'FREE'),
('edsheeran', 'Ed Sheeran', '$2y$10$examplehashedpassword', 'ed@music.com', '+4412345678', 'FREE'),
('harrystyles', 'Harry Styles', '$2y$10$examplehashedpassword', 'harry@music.com', '+4412345678', 'FREE'),
('justinbieber', 'Justin Bieber', '$2y$10$examplehashedpassword', 'justin@music.com', '+1437234567', 'FREE'),
('oliviarodrigo', 'Olivia Rodrigo', '$2y$10$examplehashedpassword', 'olivia@music.com', '+1212555123', 'FREE'),
('queen', 'Queen', '$2y$10$examplehashedpassword', 'queen@music.com', '+4412345678', 'FREE'),
('thekidlaroi', 'The Kid LAROI', '$2y$10$examplehashedpassword', 'kidlaroi@music.com', '+6112345678', 'FREE'),
('theweeknd', 'The Weeknd', '$2y$10$examplehashedpassword', 'weeknd@music.com', '+1604123456', 'FREE'),
('user001', 'Juan', 'pass123', 'juan@email.com', '666666666', 'basica_001'),
('user002', 'María García', 'pass456', 'maria@email.com', '987654321', 'premium_001');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cancion`
--
ALTER TABLE `cancion`
  ADD PRIMARY KEY (`nombre`,`nombre_creador`),
  ADD KEY `nombre_creador` (`nombre_creador`);

--
-- Indices de la tabla `creador`
--
ALTER TABLE `creador`
  ADD PRIMARY KEY (`usuario_id`);

--
-- Indices de la tabla `lista`
--
ALTER TABLE `lista`
  ADD PRIMARY KEY (`lista_id`);

--
-- Indices de la tabla `oyente`
--
ALTER TABLE `oyente`
  ADD PRIMARY KEY (`usuario_id`);

--
-- Indices de la tabla `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`lista_id`,`nombre_cancion`,`nombre_creador`),
  ADD KEY `nombre_cancion` (`nombre_cancion`,`nombre_creador`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `suscripcion`
--
ALTER TABLE `suscripcion`
  ADD PRIMARY KEY (`codigo_suscripcion`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `codigo_suscripcion` (`codigo_suscripcion`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cancion`
--
ALTER TABLE `cancion`
  ADD CONSTRAINT `cancion_ibfk_1` FOREIGN KEY (`nombre_creador`) REFERENCES `creador` (`usuario_id`);

--
-- Filtros para la tabla `creador`
--
ALTER TABLE `creador`
  ADD CONSTRAINT `creador_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Filtros para la tabla `oyente`
--
ALTER TABLE `oyente`
  ADD CONSTRAINT `oyente_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Filtros para la tabla `playlist`
--
ALTER TABLE `playlist`
  ADD CONSTRAINT `playlist_ibfk_1` FOREIGN KEY (`lista_id`) REFERENCES `lista` (`lista_id`),
  ADD CONSTRAINT `playlist_ibfk_2` FOREIGN KEY (`nombre_cancion`,`nombre_creador`) REFERENCES `cancion` (`nombre`, `nombre_creador`),
  ADD CONSTRAINT `playlist_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`codigo_suscripcion`) REFERENCES `suscripcion` (`codigo_suscripcion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
