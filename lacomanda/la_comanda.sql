-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2020 at 02:23 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `la_comanda`
--

-- --------------------------------------------------------

--
-- Table structure for table `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `estado` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `codigo_mesa` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `mesas`
--

INSERT INTO `mesas` (`id`, `estado`, `codigo_mesa`, `created_at`, `updated_at`) VALUES
(1, 'Disponible', 'MES44', '2020-12-12 23:37:33', '2020-12-13 03:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `menu` int(11) NOT NULL,
  `estado` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `tiempo_preparacion` int(11) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `codigo_pedido` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_cliente`, `id_mesa`, `menu`, `estado`, `tiempo_preparacion`, `id_empleado`, `codigo_pedido`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 'Pendiente', 0, NULL, 'DLgcG', '2020-12-12 17:22:52', '2020-12-12 17:22:52'),
(2, 1, 1, 5, 'Pendiente', 0, NULL, 'hdUSB', '2020-12-12 17:26:02', '2020-12-12 17:26:02'),
(3, 1, 1, 5, 'Listo para servir', 0, NULL, '8n7DT', '2020-12-12 17:27:09', '2020-12-12 20:15:51'),
(4, 1, 1, 5, 'Pendiente', 0, NULL, 'rTpBt', '2020-12-12 17:28:42', '2020-12-12 17:28:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `clave` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nombre`, `clave`, `tipo`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Josefina', '114477', 'cliente', 'josefina@gmail.com', '2020-12-07 02:23:04', '2020-12-07 02:23:04'),
(2, 'Rodrigo', '225588', 'mozo', 'rodrigo@gmail.com', '2020-12-07 02:25:56', '2020-12-07 02:25:56'),
(3, 'Oscar', '336699', 'socio', 'oscar@gmail.com', '2020-12-07 03:49:00', '2020-12-07 03:49:00'),
(4, 'Marcelo', '119922', 'cocinero', 'marcelo@gmail.com', '2020-12-12 19:39:53', '2020-12-12 19:39:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
