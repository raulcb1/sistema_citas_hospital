-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-02-2025 a las 00:13:23
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
-- Base de datos: `citas_hpc`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_citas`
--

CREATE TABLE `asignacion_citas` (
  `id` int(11) NOT NULL,
  `ups_id` int(11) DEFAULT NULL,
  `paciente_id` int(11) DEFAULT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `fecha_cita` date DEFAULT NULL,
  `estado_cita_id` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita`
--

CREATE TABLE `cita` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) DEFAULT NULL,
  `servicio_ups_id` int(11) DEFAULT NULL,
  `estado_cita_id` int(11) DEFAULT NULL,
  `tipo_atencion_id` int(11) DEFAULT NULL,
  `fecha_cita` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_cita`
--

CREATE TABLE `estado_cita` (
  `id` int(11) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historia`
--

CREATE TABLE `historia` (
  `id` int(11) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `fecha` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL,
  `dni` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_m` varchar(100) NOT NULL,
  `apellido_p` varchar(100) NOT NULL,
  `fecha_nac` date NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `ups_id` int(11) DEFAULT NULL,
  `seguro_id` int(11) DEFAULT NULL,
  `historia_id` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguro`
--

CREATE TABLE `seguro` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `turno` varchar(20) DEFAULT 'MAÑANA',
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_ups`
--

CREATE TABLE `servicio_ups` (
  `id` int(11) NOT NULL,
  `ups_id` int(11) DEFAULT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `capacidad` int(2) DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_atencion`
--

CREATE TABLE `tipo_atencion` (
  `id` int(11) NOT NULL,
  `tipo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ups`
--

CREATE TABLE `ups` (
  `id` int(11) NOT NULL,
  `codigo_ups` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `departamento` varchar(100) NOT NULL,
  `provincia` varchar(100) NOT NULL,
  `distrito` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignacion_citas`
--
ALTER TABLE `asignacion_citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ups_id` (`ups_id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `servicio_id` (`servicio_id`),
  ADD KEY `estado_cita_id` (`estado_cita_id`);

--
-- Indices de la tabla `cita`
--
ALTER TABLE `cita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `servicio_ups_id` (`servicio_ups_id`),
  ADD KEY `estado_cita_id` (`estado_cita_id`),
  ADD KEY `tipo_atencion_id` (`tipo_atencion_id`);

--
-- Indices de la tabla `estado_cita`
--
ALTER TABLE `estado_cita`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historia`
--
ALTER TABLE `historia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ups_id` (`ups_id`),
  ADD KEY `seguro_id` (`seguro_id`),
  ADD KEY `historia_id` (`historia_id`);

--
-- Indices de la tabla `seguro`
--
ALTER TABLE `seguro`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicio_ups`
--
ALTER TABLE `servicio_ups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ups_id` (`ups_id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indices de la tabla `tipo_atencion`
--
ALTER TABLE `tipo_atencion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ups`
--
ALTER TABLE `ups`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignacion_citas`
--
ALTER TABLE `asignacion_citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cita`
--
ALTER TABLE `cita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_cita`
--
ALTER TABLE `estado_cita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historia`
--
ALTER TABLE `historia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `seguro`
--
ALTER TABLE `seguro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicio_ups`
--
ALTER TABLE `servicio_ups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_atencion`
--
ALTER TABLE `tipo_atencion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ups`
--
ALTER TABLE `ups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignacion_citas`
--
ALTER TABLE `asignacion_citas`
  ADD CONSTRAINT `asignacion_citas_ibfk_1` FOREIGN KEY (`ups_id`) REFERENCES `ups` (`id`),
  ADD CONSTRAINT `asignacion_citas_ibfk_2` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `asignacion_citas_ibfk_3` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`),
  ADD CONSTRAINT `asignacion_citas_ibfk_4` FOREIGN KEY (`estado_cita_id`) REFERENCES `estado_cita` (`id`);

--
-- Filtros para la tabla `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `cita_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `cita_ibfk_2` FOREIGN KEY (`servicio_ups_id`) REFERENCES `servicio_ups` (`id`),
  ADD CONSTRAINT `cita_ibfk_3` FOREIGN KEY (`estado_cita_id`) REFERENCES `estado_cita` (`id`),
  ADD CONSTRAINT `cita_ibfk_4` FOREIGN KEY (`tipo_atencion_id`) REFERENCES `tipo_atencion` (`id`);

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `pacientes_ibfk_1` FOREIGN KEY (`ups_id`) REFERENCES `ups` (`id`),
  ADD CONSTRAINT `pacientes_ibfk_2` FOREIGN KEY (`seguro_id`) REFERENCES `seguro` (`id`),
  ADD CONSTRAINT `pacientes_ibfk_3` FOREIGN KEY (`historia_id`) REFERENCES `historia` (`id`);

--
-- Filtros para la tabla `servicio_ups`
--
ALTER TABLE `servicio_ups`
  ADD CONSTRAINT `servicio_ups_ibfk_1` FOREIGN KEY (`ups_id`) REFERENCES `ups` (`id`),
  ADD CONSTRAINT `servicio_ups_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
