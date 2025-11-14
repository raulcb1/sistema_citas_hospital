-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2025 a las 17:44:15
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

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
CREATE DATABASE IF NOT EXISTS `citas_hpc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `citas_hpc`;

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
  `hora` time NOT NULL,
  `estado_cita_id` int(11) DEFAULT 1,
  `cita_id` int(11) DEFAULT NULL,
  `estado` enum('pendiente','completado','cancelado') NOT NULL DEFAULT 'pendiente',
  `motivo_desactiva` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_desactiva` datetime DEFAULT NULL,
  `usuario_desactiva_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignacion_citas`
--

INSERT INTO `asignacion_citas` (`id`, `ups_id`, `paciente_id`, `servicio_id`, `fecha_cita`, `hora`, `estado_cita_id`, `cita_id`, `estado`, `motivo_desactiva`, `activo`, `fecha_desactiva`, `usuario_desactiva_id`) VALUES
(1, 2, 1, 1, '2024-05-22', '09:00:00', 1, 1, 'pendiente', NULL, 1, NULL, NULL),
(8, 2, 1, 2, '2024-05-22', '11:30:00', 1, 1, 'pendiente', NULL, 1, NULL, NULL),
(10, 2, 1, 1, '2024-05-22', '11:00:00', 1, 2, 'pendiente', NULL, 1, NULL, NULL),
(12, 2, 1, 2, '2024-05-22', '10:30:00', 1, 1, 'pendiente', NULL, 1, NULL, NULL),
(18, 2, 1, 1, '2024-05-22', '10:00:00', 1, 2, 'pendiente', NULL, 1, NULL, NULL),
(19, 2, 1, 2, '2024-05-22', '09:30:00', 1, 1, 'pendiente', NULL, 1, NULL, NULL),
(20, 2, 1, 1, '2024-05-23', '12:00:00', 1, 2, 'pendiente', NULL, 1, NULL, NULL),
(21, 2, 1, 2, '2024-05-23', '00:00:00', 1, 2, 'pendiente', NULL, 1, NULL, NULL),
(22, 2, 1, 2, '2024-05-24', '00:00:00', 1, 1, 'pendiente', NULL, 1, NULL, NULL),
(23, 2, 1, 1, '2024-05-20', '10:30:00', 1, 1, 'pendiente', NULL, 1, NULL, NULL),
(24, 2, 1, 2, '2024-05-20', '09:30:00', 1, 2, 'pendiente', NULL, 1, NULL, NULL),
(25, 2, 1, 1, '2024-05-19', '08:30:00', 1, 1, 'pendiente', NULL, 1, NULL, NULL),
(26, 2, 1, 2, '2024-05-21', '11:30:00', 1, 2, 'pendiente', NULL, 1, NULL, NULL),
(27, 2, 1, 3, '2024-05-23', '09:30:00', 1, 2, 'pendiente', NULL, 1, NULL, NULL),
(28, 2, 1, 1, '2025-02-03', '07:30:00', 1, 2, 'pendiente', NULL, 1, NULL, NULL),
(29, 2, 1, 2, '2025-02-03', '08:30:00', 1, 1, 'pendiente', NULL, 1, NULL, NULL),
(30, 2, 1, 1, '2025-02-05', '09:00:00', 1, 2, 'pendiente', NULL, 1, NULL, NULL),
(36, 2, 1, 1, '2025-02-04', '10:30:00', 1, 1, 'pendiente', NULL, 1, NULL, NULL),
(42, 2, 1, 1, '2025-02-03', '10:30:00', 1, 2, 'pendiente', NULL, 1, NULL, NULL),
(56, 2, 1, 1, '2025-06-19', '11:00:00', 1, 1, 'pendiente', NULL, 1, NULL, NULL),
(57, 2, 1, 2, '2025-06-19', '12:00:00', 1, 1, 'pendiente', NULL, 1, NULL, NULL),
(58, 2, 1, 3, '2025-06-19', '13:00:00', 1, 1, 'pendiente', NULL, 1, NULL, NULL),
(72, 2, 1, 1, '2025-07-02', '13:00:00', 1, 70, 'pendiente', NULL, 1, NULL, NULL),
(73, 2, 1, 2, '2025-07-02', '14:00:00', 1, 70, 'pendiente', NULL, 1, NULL, NULL),
(74, 2, 1, 1, '2025-07-02', '12:30:00', 1, 71, 'pendiente', NULL, 1, NULL, NULL),
(75, 2, 1, 2, '2025-07-02', '14:30:00', 4, 71, 'cancelado', 'error', 0, '2025-07-07 18:27:59', 1),
(76, 2, 1, 2, '2025-07-03', '15:30:00', 1, 72, 'pendiente', NULL, 1, NULL, NULL),
(77, 2, 1, 1, '2025-07-09', '12:00:00', 1, 73, 'pendiente', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_consultorios`
--

CREATE TABLE `asignacion_consultorios` (
  `id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `consultorio_id` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `turno` enum('mañana','tarde') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignacion_consultorios`
--

INSERT INTO `asignacion_consultorios` (`id`, `medico_id`, `servicio_id`, `consultorio_id`, `fecha`, `turno`) VALUES
(8, 18, 1, NULL, '2025-01-26', 'mañana'),
(9, 18, 1, NULL, '2025-01-27', 'mañana'),
(10, 18, 2, NULL, '2025-01-14', 'tarde'),
(11, 18, 3, NULL, '2025-01-28', 'tarde'),
(12, 18, 1, NULL, '2025-01-26', 'tarde'),
(13, 18, 2, NULL, '2025-01-28', 'mañana'),
(14, 18, 1, NULL, '2025-02-28', 'mañana'),
(15, 18, 1, NULL, '2025-03-04', 'mañana'),
(16, 18, 1, NULL, '2025-03-05', 'mañana'),
(17, 18, 1, NULL, '2025-03-06', 'mañana'),
(18, 18, 1, NULL, '2025-03-07', 'mañana'),
(19, 18, 1, NULL, '2025-06-18', 'mañana'),
(20, 18, 1, NULL, '2025-06-19', 'mañana'),
(21, 18, 1, NULL, '2025-06-23', 'mañana'),
(22, 18, 1, NULL, '2025-06-24', 'mañana'),
(23, 18, 1, NULL, '2025-06-25', 'mañana'),
(24, 18, 1, NULL, '2025-06-26', 'mañana'),
(25, 18, 1, NULL, '2025-06-30', 'mañana'),
(26, 18, 1, NULL, '2025-07-01', 'mañana'),
(27, 18, 1, NULL, '2025-07-02', 'mañana'),
(28, 18, 1, NULL, '2025-07-03', 'mañana'),
(29, 18, 1, NULL, '2025-07-07', 'mañana'),
(30, 18, 1, NULL, '2025-07-08', 'mañana'),
(31, 18, 1, NULL, '2025-07-09', 'mañana'),
(32, 18, 1, NULL, '2025-07-10', 'mañana'),
(33, 18, 2, NULL, '2025-06-18', 'tarde'),
(34, 18, 2, NULL, '2025-06-19', 'tarde'),
(35, 18, 2, NULL, '2025-06-20', 'tarde'),
(36, 18, 2, NULL, '2025-06-21', 'tarde'),
(37, 18, 2, NULL, '2025-06-22', 'tarde'),
(38, 18, 2, NULL, '2025-06-23', 'tarde'),
(39, 18, 2, NULL, '2025-06-24', 'tarde'),
(40, 18, 2, NULL, '2025-06-25', 'tarde'),
(41, 18, 2, NULL, '2025-06-26', 'tarde'),
(42, 18, 2, NULL, '2025-06-27', 'tarde'),
(43, 18, 2, NULL, '2025-06-28', 'tarde'),
(44, 18, 2, NULL, '2025-06-29', 'tarde'),
(45, 18, 2, NULL, '2025-06-30', 'tarde'),
(46, 18, 2, NULL, '2025-07-01', 'tarde'),
(47, 18, 2, NULL, '2025-07-02', 'tarde'),
(48, 18, 2, NULL, '2025-07-03', 'tarde'),
(49, 18, 3, NULL, '2025-06-18', 'mañana'),
(50, 18, 5, NULL, '2025-06-18', 'mañana'),
(51, 18, 5, NULL, '2025-06-19', 'mañana'),
(52, 18, 5, NULL, '2025-06-20', 'mañana'),
(53, 18, 3, NULL, '2025-06-20', 'mañana'),
(54, 18, 3, NULL, '2025-06-24', 'mañana'),
(55, 18, 3, NULL, '2025-06-27', 'mañana'),
(56, 18, 3, NULL, '2025-07-01', 'mañana'),
(57, 18, 3, NULL, '2025-07-04', 'mañana'),
(58, 18, 3, NULL, '2025-07-08', 'mañana'),
(59, 18, 3, NULL, '2025-07-11', 'mañana'),
(60, 18, 1, NULL, '2025-07-01', 'tarde'),
(61, 19, 1, NULL, '2025-07-01', 'tarde'),
(62, 19, 1, NULL, '2025-07-04', 'tarde'),
(63, 19, 1, NULL, '2025-07-05', 'tarde'),
(64, 19, 1, NULL, '2025-07-06', 'tarde'),
(65, 19, 1, NULL, '2025-07-07', 'tarde'),
(66, 19, 1, NULL, '2025-07-08', 'tarde'),
(67, 19, 1, NULL, '2025-07-09', 'tarde'),
(68, 19, 1, NULL, '2025-07-10', 'tarde'),
(69, 19, 1, NULL, '2025-07-11', 'tarde'),
(70, 19, 1, NULL, '2025-07-12', 'tarde'),
(71, 19, 1, NULL, '2025-07-13', 'tarde'),
(72, 19, 1, NULL, '2025-07-14', 'tarde'),
(73, 19, 1, NULL, '2025-07-15', 'tarde');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita`
--

CREATE TABLE `cita` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) DEFAULT NULL,
  `servicio_ups_id` int(11) DEFAULT NULL,
  `estado_cita_id` int(11) DEFAULT 1,
  `tipo_atencion_id` int(11) DEFAULT NULL,
  `fecha_cita` date DEFAULT NULL,
  `motivo` text NOT NULL,
  `estado` enum('activa','cancelada','finalizada') NOT NULL DEFAULT 'activa',
  `motivo_desactiva` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cita`
--

INSERT INTO `cita` (`id`, `paciente_id`, `servicio_ups_id`, `estado_cita_id`, `tipo_atencion_id`, `fecha_cita`, `motivo`, `estado`, `motivo_desactiva`) VALUES
(1, 1, 2, 1, NULL, '2025-02-16', 'Cita de Prueba', 'finalizada', NULL),
(2, 1, 2, 1, NULL, '2025-02-17', 'Cita de Prueba 2', 'activa', NULL),
(70, 1, 2, 1, 1, '2025-07-02', 'Dolor de barriga 4', 'activa', NULL),
(71, 1, 2, 1, 1, '2025-07-02', 'Dolor de barriga 4', 'activa', NULL),
(72, 1, 2, 1, 1, '2025-07-04', 'Dolor de barriga', 'activa', NULL),
(73, 1, 2, 1, 1, '2025-07-08', 'Dolor de Cabeza', 'activa', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultorios`
--

CREATE TABLE `consultorios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ups_id` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
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

--
-- Volcado de datos para la tabla `estado_cita`
--

INSERT INTO `estado_cita` (`id`, `estado`, `activo`) VALUES
(1, 'pendiente', 1),
(2, 'perdido', 1),
(3, 'atendido', 1),
(4, 'cancelado', 1),
(5, 'activo', 1),
(6, 'finalizado', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historia`
--

CREATE TABLE `historia` (
  `id` int(11) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `fecha` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historia`
--

INSERT INTO `historia` (`id`, `codigo`, `fecha`) VALUES
(1, 'SIN HISTORIA', '0000-00-00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_servicio`
--

CREATE TABLE `horarios_servicio` (
  `id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `dias_semana` varchar(37) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `capacidad_por_intervalo` int(11) DEFAULT 1,
  `activo` tinyint(1) DEFAULT 1,
  `intervalo` int(11) DEFAULT 15
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horarios_servicio`
--

INSERT INTO `horarios_servicio` (`id`, `servicio_id`, `dias_semana`, `hora_inicio`, `hora_fin`, `capacidad_por_intervalo`, `activo`, `intervalo`) VALUES
(1, 1, 'lunes,martes,miércoles,jueves,viernes', '07:30:00', '19:00:00', 1, 1, 30),
(2, 2, 'lunes,martes,miércoles,jueves,viernes', '07:30:00', '19:00:00', 1, 1, 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE `medicos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cmp` varchar(20) NOT NULL,
  `especialidad` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`id`, `usuario_id`, `cmp`, `especialidad`, `activo`) VALUES
(18, 23, '123456', 'Pediatría', 1),
(19, 24, '11111111', 'Cardiologia', 1);

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

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id`, `dni`, `nombre`, `apellido_m`, `apellido_p`, `fecha_nac`, `telefono`, `ups_id`, `seguro_id`, `historia_id`, `activo`) VALUES
(1, '41400877', 'RAÚL PABLO CÉSAR', 'BARBARAN', 'CUENTAS', '1982-01-16', '995622696', 2, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paginas`
--

CREATE TABLE `paginas` (
  `id` int(11) NOT NULL,
  `nombre_menu` varchar(100) NOT NULL COMMENT 'Nombre para mostrar en el menú',
  `nombre` varchar(100) NOT NULL,
  `ruta` varchar(100) NOT NULL,
  `icono` varchar(50) DEFAULT 'circle',
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `paginas`
--

INSERT INTO `paginas` (`id`, `nombre_menu`, `nombre`, `ruta`, `icono`, `activo`) VALUES
(1, 'Dashboard', 'dashboard_admin', 'paginas/dashboard_admin.php', 'circle', 1),
(2, 'Administrar Paginas', 'admin_paginas', 'paginas/admin/paginas.php', 'circle', 1),
(3, 'Administrar Roles', 'admin_roles', 'paginas/admin/roles.php', 'circle', 1),
(4, 'Administrar Permisos', 'admin_permisos', 'paginas/admin/permisos.php', 'circle', 1),
(5, 'Administrar Usuarios', 'admin_usuarios', 'paginas/admin/usuarios.php', 'circle', 1),
(6, 'Citas Médicas', 'citas', 'paginas/citas2/citas_lista.php', 'circle', 1),
(7, 'Crear Cita', 'crear_cita', 'paginas/cita/crear_cita.php', 'circle', 1),
(8, 'Programar Consultorios', 'admin_prog_cons', 'paginas/admin/programar_consultorio.php', 'circle', 1),
(9, 'Dashboard', 'dashboard_medico', 'paginas/medico/dashboard.php', 'circle', 1),
(10, 'Programacion de Consultorios', 'admin_programacion', 'paginas/admin/programacion.php', 'circle', 1),
(11, 'Administrar Servicios', 'admin_servicios', 'paginas/admin/servicios.php', 'circle', 1),
(12, 'Citas 2', 'citas2', 'paginas/citas2/citas_agregar.php', 'circle', 1),
(13, 'Programacion 2', 'programacion_2', 'paginas/programacion/programacion_horarios.php', 'circle', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `rol_id` int(11) NOT NULL,
  `pagina_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`rol_id`, `pagina_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 8),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(2, 6),
(2, 10),
(3, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES
(1, 'admin', 'Administrador del Sistema, accede a todas las páginas'),
(2, 'Recepción', 'encargado de registrar al paciente a ser atendido, y de crearle una cita'),
(3, 'Médico', 'usuario con acceso a los consultorios asignado para atencion de los pacientes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguro`
--

CREATE TABLE `seguro` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `seguro`
--

INSERT INTO `seguro` (`id`, `nombre`, `activo`) VALUES
(1, 'SIN SEGURO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ups_id` int(11) NOT NULL DEFAULT 1,
  `turno` varchar(20) DEFAULT 'MAÑANA',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `color` varchar(20) DEFAULT '#007bff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `ups_id`, `turno`, `activo`, `color`) VALUES
(1, 'NUTRICION', 2, 'MAÑANA', 1, '#D20103'),
(2, 'CRED - Crecimiento y Desarrollo', 2, 'MAÑANA', 1, '#7DDA58'),
(3, 'TAMIZAJE', 2, 'TARDE', 1, '#5DE2E7'),
(5, 'RAYOS_X', 1, 'TARDE', 1, '#DFC57B');

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

--
-- Volcado de datos para la tabla `servicio_ups`
--

INSERT INTO `servicio_ups` (`id`, `ups_id`, `servicio_id`, `capacidad`, `activo`) VALUES
(1, 2, 1, 10, 1),
(2, 2, 2, 15, 1),
(3, 2, 3, 10, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_atencion`
--

CREATE TABLE `tipo_atencion` (
  `id` int(11) NOT NULL,
  `tipo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_atencion`
--

INSERT INTO `tipo_atencion` (`id`, `tipo`) VALUES
(1, 'Local'),
(2, 'Referencia');

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
-- Volcado de datos para la tabla `ups`
--

INSERT INTO `ups` (`id`, `codigo_ups`, `nombre`, `direccion`, `departamento`, `provincia`, `distrito`, `activo`) VALUES
(1, '', 'SIN DETERMINAR', '', '', '', '', 1),
(2, '00005277', 'HOSPITAL PROVINCIAL DE CASCAS', 'CALLE LAS ESMERALDAS NÚMERO 403 URBANIZACIÓN SANTA INES', 'LA LIBERTAD', 'TRUJILLO', 'TRUJILLO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `apellido_p` varchar(50) NOT NULL,
  `apellido_m` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(15) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `es_medico` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `dni`, `password`, `rol_id`, `activo`, `apellido_p`, `apellido_m`, `nombre`, `email`, `telefono`, `username`, `es_medico`) VALUES
(1, '41400877', '$2y$10$cluYuk33M14RtWEITYpf2eZnKrlhTxJtpInNtTwII9VwgFf9OeNiW', 1, 1, 'Cuentas', 'Barbarán', 'Raul', 'raulcb1@hotmail.com', '995622696', 'ADMINISTRADOR', 0),
(2, '12345678', '$2y$10$1Cv1xGRBcClEszC34ej44.hQmuQjytCS4P0DWhe.tdo88Gncp8xZ6', 2, 1, 'Cuenta', 'Prueba', 'Recepción', '-@a.com', '123456789', '', 0),
(23, '987654321', '$2y$10$ZWrnU.EMBJUQrmOhOcFz1emolltK2Vugg1uKvanvuQ4aO8ItPk1Ly', 3, 1, 'Perez', 'Paredes', 'José', 'jopepa@gmail.com', '999999999', NULL, 1),
(24, '11111111', '$2y$10$AjWyDqlI5xorIrH.ooZ.lOyFGiWUaYUIuCZQEp9nbU.thgR3QOe76', 3, 1, 'Peta', 'Pata', 'Juan', 'jupepa@hotmail.com', '111111111', NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignacion_citas`
--
ALTER TABLE `asignacion_citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estado_cita_id` (`estado_cita_id`),
  ADD KEY `ups_id` (`ups_id`),
  ADD KEY `servicio_id` (`servicio_id`),
  ADD KEY `cita_id` (`cita_id`),
  ADD KEY `asignacion_citas_ibfk_7` (`paciente_id`);

--
-- Indices de la tabla `asignacion_consultorios`
--
ALTER TABLE `asignacion_consultorios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medico_id` (`medico_id`),
  ADD KEY `servicio_id` (`servicio_id`),
  ADD KEY `asignacion_consultorios_ibfk_2` (`consultorio_id`);

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
-- Indices de la tabla `consultorios`
--
ALTER TABLE `consultorios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ups_id` (`ups_id`);

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
-- Indices de la tabla `horarios_servicio`
--
ALTER TABLE `horarios_servicio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ups_id` (`ups_id`),
  ADD KEY `seguro_id` (`seguro_id`),
  ADD KEY `historia_id` (`historia_id`);

--
-- Indices de la tabla `paginas`
--
ALTER TABLE `paginas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ruta` (`ruta`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`rol_id`,`pagina_id`),
  ADD KEY `pagina_id` (`pagina_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `seguro`
--
ALTER TABLE `seguro`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ups_id` (`ups_id`);

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
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignacion_citas`
--
ALTER TABLE `asignacion_citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT de la tabla `asignacion_consultorios`
--
ALTER TABLE `asignacion_consultorios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `cita`
--
ALTER TABLE `cita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `consultorios`
--
ALTER TABLE `consultorios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_cita`
--
ALTER TABLE `estado_cita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `historia`
--
ALTER TABLE `historia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `horarios_servicio`
--
ALTER TABLE `horarios_servicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `paginas`
--
ALTER TABLE `paginas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `seguro`
--
ALTER TABLE `seguro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `servicio_ups`
--
ALTER TABLE `servicio_ups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_atencion`
--
ALTER TABLE `tipo_atencion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ups`
--
ALTER TABLE `ups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignacion_citas`
--
ALTER TABLE `asignacion_citas`
  ADD CONSTRAINT `asignacion_citas_ibfk_1` FOREIGN KEY (`ups_id`) REFERENCES `ups` (`id`),
  ADD CONSTRAINT `asignacion_citas_ibfk_3` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`),
  ADD CONSTRAINT `asignacion_citas_ibfk_4` FOREIGN KEY (`estado_cita_id`) REFERENCES `estado_cita` (`id`),
  ADD CONSTRAINT `asignacion_citas_ibfk_5` FOREIGN KEY (`ups_id`) REFERENCES `ups` (`id`),
  ADD CONSTRAINT `asignacion_citas_ibfk_6` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`),
  ADD CONSTRAINT `asignacion_citas_ibfk_7` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `asignacion_citas_ibfk_8` FOREIGN KEY (`cita_id`) REFERENCES `cita` (`id`);

--
-- Filtros para la tabla `asignacion_consultorios`
--
ALTER TABLE `asignacion_consultorios`
  ADD CONSTRAINT `asignacion_consultorios_ibfk_1` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`),
  ADD CONSTRAINT `asignacion_consultorios_ibfk_2` FOREIGN KEY (`consultorio_id`) REFERENCES `consultorios` (`id`),
  ADD CONSTRAINT `asignacion_consultorios_ibfk_3` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);

--
-- Filtros para la tabla `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `cita_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `cita_ibfk_2` FOREIGN KEY (`servicio_ups_id`) REFERENCES `servicio_ups` (`id`),
  ADD CONSTRAINT `cita_ibfk_3` FOREIGN KEY (`estado_cita_id`) REFERENCES `estado_cita` (`id`),
  ADD CONSTRAINT `cita_ibfk_4` FOREIGN KEY (`tipo_atencion_id`) REFERENCES `tipo_atencion` (`id`);

--
-- Filtros para la tabla `consultorios`
--
ALTER TABLE `consultorios`
  ADD CONSTRAINT `consultorios_ibfk_1` FOREIGN KEY (`ups_id`) REFERENCES `ups` (`id`);

--
-- Filtros para la tabla `horarios_servicio`
--
ALTER TABLE `horarios_servicio`
  ADD CONSTRAINT `horarios_servicio_ibfk_1` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);

--
-- Filtros para la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `medicos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `pacientes_ibfk_1` FOREIGN KEY (`ups_id`) REFERENCES `ups` (`id`),
  ADD CONSTRAINT `pacientes_ibfk_2` FOREIGN KEY (`seguro_id`) REFERENCES `seguro` (`id`),
  ADD CONSTRAINT `pacientes_ibfk_3` FOREIGN KEY (`historia_id`) REFERENCES `historia` (`id`);

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `permisos_ibfk_2` FOREIGN KEY (`pagina_id`) REFERENCES `paginas` (`id`);

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`ups_id`) REFERENCES `ups` (`id`);

--
-- Filtros para la tabla `servicio_ups`
--
ALTER TABLE `servicio_ups`
  ADD CONSTRAINT `servicio_ups_ibfk_1` FOREIGN KEY (`ups_id`) REFERENCES `ups` (`id`),
  ADD CONSTRAINT `servicio_ups_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
