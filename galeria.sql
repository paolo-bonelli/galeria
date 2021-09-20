-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 25-08-2021 a las 03:51:42
-- Versión del servidor: 10.4.19-MariaDB
-- Versión de PHP: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `galeria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Galerias`
--

CREATE TABLE `Galerias` (
  `id_galeria` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `propietario` varchar(20) NOT NULL,
  `tipo_de_galeria` varchar(20) NOT NULL DEFAULT 'Publica',
  `fecha_creacion` date NOT NULL DEFAULT curdate(),
  `ultima_actualizada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `Galerias`
--

INSERT INTO `Galerias` (`id_galeria`, `nombre`, `propietario`, `tipo_de_galeria`, `fecha_creacion`, `ultima_actualizada`) VALUES
(4, 'Señorito', 'paolish', 'Publica', '2021-08-17', '2021-08-17 11:52:21'),
(5, 'hola2', 'paolish', 'Publica', '2021-08-17', '2021-08-17 13:06:24'),
(6, 'prueba privada', 'paolish', 'Privada', '2021-08-20', '2021-08-21 01:43:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Imagenes`
--

CREATE TABLE `Imagenes` (
  `nombre` char(36) NOT NULL,
  `propietario` varchar(20) NOT NULL,
  `fecha` date NOT NULL DEFAULT curdate(),
  `id_galeria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `Imagenes`
--

INSERT INTO `Imagenes` (`nombre`, `propietario`, `fecha`, `id_galeria`) VALUES
('241b8f03706fb39c3ce483dea336e4f4.png', 'paolish', '2021-08-20', 6),
('3e85eac34a040b6b8952b7b6e1f0ade2.png', 'prueba', '2021-08-24', 5),
('91ab030ef4f9de7c14f8e1f60aaa04a7.png', 'paolish', '2021-08-17', 4),
('a1272380ccd8a8b825fb8faeb9521343.png', 'paolish', '2021-08-17', 5),
('a54e4caddf24002a6f69ff975222b86b.png', 'paolish', '2021-08-17', 4),
('fef1cc2f351a5226fc2822052cc68ae1.png', 'paolish', '2021-08-20', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipoDeGaleria`
--

CREATE TABLE `TipoDeGaleria` (
  `nombre_tipo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `TipoDeGaleria`
--

INSERT INTO `TipoDeGaleria` (`nombre_tipo`) VALUES
('Privada'),
('Propia'),
('Publica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TiposDeUsuario`
--

CREATE TABLE `TiposDeUsuario` (
  `nombre` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `TiposDeUsuario`
--

INSERT INTO `TiposDeUsuario` (`nombre`) VALUES
('Administrador'),
('Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `id_usuario` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password_hash` char(60) NOT NULL,
  `nombres` varchar(30) NOT NULL,
  `apellidos` varchar(30) NOT NULL,
  `tipo` varchar(15) NOT NULL DEFAULT 'Usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`id_usuario`, `email`, `password_hash`, `nombres`, `apellidos`, `tipo`) VALUES
('admin', 'bv.vincenzo@gmail.com', '$2y$10$uUgVeOQ3u8qrVhhxW39QmOoSfvyHX06J/MS.18MC8njGvlXXCrgle', 'Vincenzo', 'Bonelli', 'Administrador'),
('paolish', 'example@example.com', '$2y$10$T4K/zrvK70JJRtklUGZcHu1HSeDhuuJtLaugDxSzI/Y.BhPiFFBAe', 'Paolo', 'Bonelli', 'Usuario'),
('prueba', 'example2@example.com', '$2y$10$woSmvnrK3e05dJ191Ux6x..LCsyhFOHOdc9tkpVEt7wUb6RTAWKcW', 'nueva', 'prueba', 'Usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Galerias`
--
ALTER TABLE `Galerias`
  ADD PRIMARY KEY (`id_galeria`),
  ADD UNIQUE KEY `id_galeria` (`id_galeria`),
  ADD KEY `propietario` (`propietario`),
  ADD KEY `tipo_de_galeria` (`tipo_de_galeria`);

--
-- Indices de la tabla `Imagenes`
--
ALTER TABLE `Imagenes`
  ADD PRIMARY KEY (`nombre`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `propietario` (`propietario`),
  ADD KEY `id_galeria` (`id_galeria`);

--
-- Indices de la tabla `TipoDeGaleria`
--
ALTER TABLE `TipoDeGaleria`
  ADD PRIMARY KEY (`nombre_tipo`);

--
-- Indices de la tabla `TiposDeUsuario`
--
ALTER TABLE `TiposDeUsuario`
  ADD PRIMARY KEY (`nombre`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `tipo` (`tipo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Galerias`
--
ALTER TABLE `Galerias`
  MODIFY `id_galeria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Galerias`
--
ALTER TABLE `Galerias`
  ADD CONSTRAINT `Galerias_ibfk_1` FOREIGN KEY (`propietario`) REFERENCES `Usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Galerias_ibfk_2` FOREIGN KEY (`tipo_de_galeria`) REFERENCES `TipoDeGaleria` (`nombre_tipo`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `Imagenes`
--
ALTER TABLE `Imagenes`
  ADD CONSTRAINT `Imagenes_ibfk_1` FOREIGN KEY (`propietario`) REFERENCES `Usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Imagenes_ibfk_2` FOREIGN KEY (`id_galeria`) REFERENCES `Galerias` (`id_galeria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD CONSTRAINT `Usuarios_ibfk_1` FOREIGN KEY (`tipo`) REFERENCES `TiposDeUsuario` (`nombre`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
