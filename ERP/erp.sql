-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:8889
-- Tiempo de generación: 04-06-2025 a las 00:01:58
-- Versión del servidor: 8.0.40
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `erp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cif_nif` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('cliente','proveedor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cliente',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `nombre`, `cif_nif`, `direccion`, `telefono`, `email`, `tipo`, `fecha_creacion`, `fecha_actualizacion`, `eliminado`) VALUES
(1, 'Empresa Test Web', 'CIF987654242424444', 'Avenida de prueba 77', '900123456', 'test@empresa.com', 'proveedor', '2025-05-22 15:37:36', '2025-05-31 13:58:27', 0),
(2, 'Empresa de prueba 2', 'º1713631', 'calle del conde maricon 45', '14144141', 'pepino@dgmil.com', 'cliente', '2025-05-28 20:21:52', '2025-05-29 14:43:11', 0),
(3, 'Empresa 1', 'CIF001X', 'Dirección 1', '600000001', 'empra1@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-06-03 19:22:10', 0),
(4, 'Empresa 2', 'CIF002X', 'Dirección 2', '600000002', 'empresa2@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(5, 'Empresa 3', 'CIF003X', 'Dirección 3', '600000003', 'empresa3@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(6, 'Empresa 4', 'CIF004X', 'Dirección 4', '600000004', 'empresa4@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(7, 'Empresa 5', 'CIF005X', 'Dirección 5', '600000005', 'empresa5@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(8, 'Empresa 6', 'CIF006X', 'Dirección 6', '600000006', 'empresa6@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(9, 'Empresa 7', 'CIF007X', 'Dirección 7', '600000007', 'empresa7@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(10, 'Empresa 8', 'CIF008X', 'Dirección 8', '600000008', 'empresa8@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(11, 'Empresa 9', 'CIF009X', 'Dirección 9', '600000009', 'empresa9@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(12, 'Empresa 10', 'CIF010X', 'Dirección 10', '600000010', 'empresa10@test.com', 'cliente', '2025-05-29 14:55:52', '2025-06-02 15:14:32', 0),
(13, 'Empresa 11', 'CIF011X', 'Dirección 11', '600000011', 'empresa11@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-06-03 19:22:22', 0),
(14, 'Empresa 12', 'CIughacz', 'Dirección 12', '600000012', 'empresa12@gmail.com', 'cliente', '2025-05-29 14:55:52', '2025-05-31 15:09:22', 0),
(15, 'Empresa 13', 'CIF013X', 'Dirección 13', '600000013', 'empresa13@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(16, 'Empresa 14', 'CIF014X', 'Dirección 14', '600000014', 'empresa14@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(17, 'Empresa 15', 'CIF015X', 'Dirección 15', '600000015', 'empresa15@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(18, 'Empresa 16', 'CIF016X', 'Dirección 16', '600000016', 'empresa16@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(19, 'Empresa 17', 'CIF017X', 'Dirección 17', '600000017', 'empresa17@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(20, 'Empresa 18', 'CIF018X', 'Dirección 18', '600000018', 'empresa18@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(21, 'Empresa 19', 'CIF019X', 'Dirección 19', '600000019', 'empresa19@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(22, 'Empresa 20', 'CIF020X', 'Dirección 20', '600000020', 'empresa20@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(23, 'Empresa 21', 'CIF021X', 'Dirección 21', '600000021', 'empresa21@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(24, 'Empresa 22', 'CIF022X', 'Dirección 22', '600000022', 'empresa22@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(25, 'Empresa 23', 'CIF023X', 'Dirección 23', '600000023', 'empresa23@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(26, 'Empresa 24', 'CIF024X', 'Dirección 24', '600000024', 'empresa24@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(27, 'Empresa 25', 'CIF025X', 'Dirección 25', '600000025', 'empresa25@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(28, 'Empresa 26', 'CIF026X', 'Dirección 26', '600000026', 'empresa26@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(29, 'Empresa 27', 'CIF027X', 'Dirección 27', '600000027', 'empresa27@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(30, 'Empresa 28', 'CIF028X', 'Dirección 28', '600000028', 'empresa28@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(31, 'Empresa 29', 'CIF029X', 'Dirección 29', '600000029', 'empresa29@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(32, 'Empresa 30', 'CIF030X', 'Dirección 30', '600000030', 'empresa30@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(33, 'Empresa 31', 'CIF031X', 'Dirección 31', '600000031', 'empresa31@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(34, 'Empresa 32', 'CIF032X', 'Dirección 32', '600000032', 'empresa32@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(35, 'Empresa 33', 'CIF033X', 'Dirección 33', '600000033', 'empresa33@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(36, 'Empresa 34', 'CIF034X', 'Dirección 34', '600000034', 'empresa34@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(37, 'Empresa 35', 'CIF035X', 'Dirección 35', '600000035', 'empresa35@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(38, 'Empresa 36', 'CIF036X', 'Dirección 36', '600000036', 'empresa36@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(39, 'Empresa 37', 'CIF037X', 'Dirección 37', '600000037', 'empresa37@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(40, 'Empresa 38', 'CIF038X', 'Dirección 38', '600000038', 'empresa38@test.com', 'cliente', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(41, 'Empresa 39', 'CIF039X', 'Dirección 39', '600000039', 'empresa39@test.com', 'proveedor', '2025-05-29 14:55:52', '2025-05-29 14:55:52', 0),
(42, 'Empresa 40', 'CIF040X', 'Dirección 40', '60000', 'empresa40@test.com', 'cliente', '2025-05-29 14:55:52', '2025-06-02 09:38:39', 0),
(43, 'empresaaaa prueba', '715317381', 'direccion de prueba t9', 'asdadad', 'pepino@gmai.com', 'proveedor', '2025-05-31 14:20:12', '2025-05-31 14:20:12', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int NOT NULL,
  `pedido_id` int NOT NULL,
  `numero_factura` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_emision` date NOT NULL,
  `importe_total` decimal(10,2) NOT NULL,
  `estado_pago` enum('pendiente','pagado','vencido') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_lineas`
--

CREATE TABLE `factura_lineas` (
  `id` int NOT NULL,
  `factura_id` int NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS ((`cantidad` * `precio_unitario`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `action` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `action`, `ip_address`, `created_at`) VALUES
(1, 6, 'login', '::1', '2025-05-19 17:23:18'),
(2, 6, 'logout', '::1', '2025-05-19 17:48:27'),
(3, 6, 'logout', '::1', '2025-05-19 17:50:29'),
(4, 6, 'logout', '::1', '2025-05-19 17:50:32'),
(5, 6, 'logout', '::1', '2025-05-19 17:50:59'),
(6, 6, 'logout', '::1', '2025-05-19 17:52:24'),
(7, 6, 'login', '::1', '2025-05-19 17:52:38'),
(8, 6, 'login', '::1', '2025-05-21 16:52:04'),
(9, 6, 'login', '::1', '2025-05-22 16:09:05'),
(10, 1, 'test web', '::1', '2025-05-22 17:37:36'),
(11, 6, 'login', '::1', '2025-05-28 21:27:28'),
(12, 6, 'login', '::1', '2025-05-29 16:19:55'),
(13, 6, 'login', '::1', '2025-05-30 13:45:58'),
(14, 6, 'login', '::1', '2025-05-31 15:33:26'),
(15, 6, 'login', '::1', '2025-06-02 17:13:51'),
(16, 6, 'login', '::1', '2025-06-02 17:52:35'),
(17, 6, 'login', '::1', '2025-06-03 14:23:36'),
(18, 6, 'login', '::1', '2025-06-03 17:23:24'),
(19, 6, 'login', '::1', '2025-06-04 00:52:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int NOT NULL,
  `empresa_id` int NOT NULL,
  `fecha_pedido` date NOT NULL,
  `estado` enum('pendiente','confirmado','cancelado') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `importe` decimal(10,2) DEFAULT '0.00',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `empresa_id`, `fecha_pedido`, `estado`, `importe`, `observaciones`, `fecha_creacion`, `fecha_actualizacion`, `eliminado`) VALUES
(1, 1, '2024-06-01', 'pendiente', 0.00, 'Pedidoo urgente para evento de junio', '2025-06-03 11:45:56', '2025-06-03 19:29:15', 0),
(2, 2, '2024-06-03', 'confirmado', 0.00, 'Entrega programada la próxima semana', '2025-06-03 11:45:56', '2025-06-03 11:45:56', 0),
(3, 1, '2024-06-05', 'pendiente', 0.00, 'Revisión pendiente por parte del cliente', '2025-06-03 11:45:56', '2025-06-03 11:45:56', 0),
(11, 5, '2025-05-28', 'cancelado', 0.00, 'Observación aleatoria 548', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(12, 5, '2025-05-08', 'cancelado', 0.00, 'Observación aleatoria 615', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(13, 4, '2025-04-26', 'pendiente', 0.00, 'Observación aleatoria 695', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(14, 1, '2025-05-10', 'cancelado', 0.00, 'Observación aleatoria 546', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(15, 3, '2025-04-23', 'pendiente', 0.00, 'Observación aleatoria 91', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(16, 2, '2025-04-30', 'cancelado', 0.00, 'Observación aleatoria 92', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(17, 1, '2025-04-30', 'confirmado', 0.00, 'Observación aleatoria 213', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(18, 5, '2025-04-15', 'confirmado', 0.00, 'Observación aleatoria 846', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(19, 5, '2025-04-28', 'confirmado', 0.00, 'Observación aleatoria 69', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(20, 3, '2025-04-17', 'pendiente', 0.00, 'Observación aleatoria 280', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(21, 1, '2025-05-19', 'pendiente', 0.00, 'Observación aleatoria 529', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(22, 5, '2025-04-24', 'cancelado', 0.00, 'Observación aleatoria 990', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(23, 3, '2025-04-18', 'pendiente', 0.00, 'Observación aleatoria 883', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(24, 4, '2025-04-18', 'cancelado', 0.00, 'Observación aleatoria 691', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(25, 1, '2025-05-22', 'cancelado', 0.00, 'Observación aleatoria 787', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(26, 2, '2025-05-31', 'confirmado', 0.00, 'Observación aleatoria 134', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(27, 2, '2025-04-06', 'pendiente', 0.00, 'Observación aleatoria 358', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(28, 4, '2025-06-03', 'pendiente', 0.00, 'Observación aleatoria 55', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(29, 4, '2025-04-09', 'cancelado', 0.00, 'Observación aleatoria 162', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(30, 3, '2025-04-22', 'pendiente', 0.00, 'Observación aleatoria 54', '2025-06-03 19:40:36', '2025-06-03 19:40:36', 0),
(42, 3, '2025-06-05', 'pendiente', 0.00, 'holaaa guapo', '2025-06-03 20:02:39', '2025-06-03 20:43:08', 0),
(43, 3, '2025-05-28', 'pendiente', 0.00, 'se crea esto o queee', '2025-06-03 20:43:21', '2025-06-03 21:50:11', 0),
(44, 13, '2025-05-30', 'pendiente', 0.00, 'MIERDA', '2025-06-03 22:14:38', '2025-06-03 22:14:38', 0),
(45, 12, '2025-05-26', 'pendiente', 45.00, 'funcio', '2025-06-03 22:21:27', '2025-06-03 23:06:56', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_lineas`
--

CREATE TABLE `pedido_lineas` (
  `id` int NOT NULL,
  `pedido_id` int NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS ((`cantidad` * `precio_unitario`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','empleado','contable') COLLATE utf8mb4_unicode_ci DEFAULT 'empleado',
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `active`, `created_at`) VALUES
(1, 'Administrador', 'admin@erp.local', '$2y$10$EVNCvC4ZksKLPuIbYWRcO.ZzYFwKQKwSEH4hw4b1zZr6l/EY9kBjy', 'admin', 1, '2025-05-13 15:42:23'),
(6, 'Prueba1', 'prueba1@gmail.com', '$2y$10$zsy3aCiYq/JGW3J9/HSN9eqaniQR3TE3k.PH2cW1E30xfR.IgJ16K', 'admin', 1, '2025-05-14 15:14:02'),
(7, 'javier', 'javier@gmail.com', 'javi', 'empleado', 1, '2025-05-14 15:44:52'),
(9, 'javier', 'javierfunciona@gmail.com', '$2y$10$220hRFgKO.9duE8LP3b0kOoNIQRULlPuXfRfhEbppD.tgI3c3fngy', 'contable', 1, '2025-05-14 15:47:27'),
(10, 'Usuario Test Web', 'usuario@test.com', '$2y$10$JZT.qDT5eb4HKYAYtUFMHOLMpSyWfwNeq.6VSuDLk9T7kBsypN8ky', 'admin', 1, '2025-05-22 15:37:36');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_factura` (`numero_factura`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indices de la tabla `factura_lineas`
--
ALTER TABLE `factura_lineas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factura_id` (`factura_id`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Indices de la tabla `pedido_lineas`
--
ALTER TABLE `pedido_lineas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `factura_lineas`
--
ALTER TABLE `factura_lineas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `pedido_lineas`
--
ALTER TABLE `pedido_lineas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `factura_lineas`
--
ALTER TABLE `factura_lineas`
  ADD CONSTRAINT `factura_lineas_ibfk_1` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedido_lineas`
--
ALTER TABLE `pedido_lineas`
  ADD CONSTRAINT `pedido_lineas_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
