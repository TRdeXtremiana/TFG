-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2025 at 10:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestorgastos`
--

-- --------------------------------------------------------

--
-- Table structure for table `etiquetas`
--

CREATE TABLE `etiquetas` (
  `id_etiqueta` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `color` varchar(7) DEFAULT '#000000',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `etiquetas`
--

INSERT INTO `etiquetas` (`id_etiqueta`, `nombre`, `color`, `fecha_creacion`) VALUES
(1, 'Primera necesidad', '#FF5733', '2024-12-31 10:19:23'),
(2, 'Transporte', '#33B5E5', '2024-12-31 10:19:23'),
(3, 'Alimentación', '#FF33A1', '2024-12-31 10:19:23'),
(4, 'Ocio', '#33FF57', '2024-12-31 10:19:23'),
(5, 'Educación', '#FFC300', '2024-12-31 10:19:23'),
(6, 'Salud', '#FF3333', '2024-12-31 10:19:23'),
(7, 'Hogar', '#3366FF', '2024-12-31 10:19:23'),
(8, 'Otros', '#999999', '2024-12-31 10:19:23');

-- --------------------------------------------------------

--
-- Table structure for table `gastos`
--

CREATE TABLE `gastos` (
  `id_gasto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_etiqueta` int(11) DEFAULT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_gasto` date NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `eliminado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gastos`
--

INSERT INTO `gastos` (`id_gasto`, `id_usuario`, `id_etiqueta`, `cantidad`, `descripcion`, `fecha_gasto`, `fecha_creacion`, `fecha_actualizacion`, `eliminado`) VALUES
(1, 1, 3, 12.59, 'nanana', '2024-12-31', '2024-12-31 10:21:31', '2024-12-31 12:04:50', 0),
(2, 1, 1, 1.30, 'a', '2024-12-31', '2024-12-31 12:34:08', '2024-12-31 12:34:08', 0),
(3, 2, 1, 12.00, 'urgencia con la furgoneta', '2024-12-31', '2024-12-31 12:34:42', '2024-12-31 12:34:42', 0),
(14, 1, 1, 790.77, 'Compra de supermercado', '2024-01-24', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(15, 1, 2, 7.54, 'Pasaje de autobús', '2020-04-04', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(16, 1, 3, 33.89, 'Cena en restaurante', '2024-04-23', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(17, 1, 4, 25.14, 'Entradas al cine', '2021-07-31', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(18, 1, 5, 53.63, 'Matrícula curso online', '2023-04-07', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(19, 1, 6, 207.82, 'Compra de medicamentos', '2025-10-16', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(20, 1, 7, 268.01, 'Pago de alquiler', '2022-08-30', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(21, 1, 8, 62.44, 'Gastos imprevistos', '2024-06-29', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(22, 1, 3, 248.23, 'Compra semanal de alimentos', '2021-05-30', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(23, 1, 4, 137.35, 'Suscripción a streaming', '2024-05-14', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(24, 1, 6, 174.15, 'Chequeo médico', '2024-04-22', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(25, 1, 5, 509.00, 'Compra de libros', '2020-07-11', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(26, 1, 2, 89.41, 'Viaje en taxi', '2021-03-29', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(27, 1, 7, 422.80, 'Pago de hipoteca', '2020-11-04', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(28, 1, 8, 32.38, 'Regalo de cumpleaños', '2024-11-21', '2024-12-31 13:23:39', '2024-12-31 13:23:39', 0),
(29, 1, 3, 170.17, 'Gastos imprevistos', '2021-01-26', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(30, 1, 3, 365.46, 'Cena en restaurante', '2021-01-17', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(31, 1, 1, 382.35, 'Matrícula curso online', '2021-02-05', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(32, 1, 1, 399.64, 'Viaje en taxi', '2021-02-13', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(33, 1, 6, 189.77, 'Pago de alquiler', '2021-03-27', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(34, 1, 2, 684.87, 'Regalo de cumpleaños', '2021-03-24', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(35, 1, 2, 478.78, 'Compra de supermercado', '2021-04-08', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(36, 1, 3, 544.17, 'Matrícula curso online', '2021-04-21', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(37, 1, 3, 840.31, 'Regalo de cumpleaños', '2021-05-28', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(38, 1, 1, 412.45, 'Compra de libros', '2021-05-22', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(39, 1, 1, 560.93, 'Compra semanal de alimentos', '2021-06-28', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(40, 1, 2, 802.05, 'Compra de supermercado', '2021-06-20', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(41, 1, 7, 756.41, 'Pasaje de autobús', '2021-07-17', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(42, 1, 6, 79.05, 'Compra de medicamentos', '2021-07-05', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(43, 1, 3, 392.38, 'Viaje en taxi', '2021-08-16', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(44, 1, 8, 971.18, 'Compra de medicamentos', '2021-08-14', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(45, 1, 4, 782.91, 'Compra de medicamentos', '2021-09-18', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(46, 1, 6, 481.75, 'Compra semanal de alimentos', '2021-09-04', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(47, 1, 8, 706.77, 'Compra de supermercado', '2021-10-25', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(48, 1, 1, 266.55, 'Cena en restaurante', '2021-10-20', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(49, 1, 6, 72.01, 'Pago de hipoteca', '2021-11-23', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(50, 1, 2, 839.01, 'Cena en restaurante', '2021-11-03', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(51, 1, 1, 837.57, 'Chequeo médico', '2021-12-24', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(52, 1, 7, 63.84, 'Compra semanal de alimentos', '2021-12-17', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(53, 1, 2, 14.93, 'Pago de hipoteca', '2022-01-18', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(54, 1, 8, 743.75, 'Regalo de cumpleaños', '2022-01-28', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(55, 1, 7, 834.82, 'Cena en restaurante', '2022-02-21', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(56, 1, 6, 500.36, 'Entradas al cine', '2022-02-25', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(57, 1, 3, 392.48, 'Matrícula curso online', '2022-03-14', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(58, 1, 5, 734.93, 'Compra de libros', '2022-03-25', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(59, 1, 4, 228.65, 'Matrícula curso online', '2022-04-07', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(60, 1, 1, 600.25, 'Compra de supermercado', '2022-04-26', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(61, 1, 2, 85.66, 'Regalo de cumpleaños', '2022-05-21', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(62, 1, 1, 95.48, 'Gastos imprevistos', '2022-05-26', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(63, 1, 8, 592.01, 'Compra de libros', '2022-06-20', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(64, 1, 5, 646.78, 'Compra de medicamentos', '2022-06-15', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(65, 1, 8, 86.79, 'Cena en restaurante', '2022-07-28', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(66, 1, 5, 285.36, 'Suscripción a streaming', '2022-07-11', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(67, 1, 8, 122.82, 'Cena en restaurante', '2022-08-28', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(68, 1, 5, 98.49, 'Cena en restaurante', '2022-08-06', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(69, 1, 5, 794.62, 'Entradas al cine', '2022-09-16', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(70, 1, 3, 10.80, 'Suscripción a streaming', '2022-09-18', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(71, 1, 3, 966.23, 'Pago de alquiler', '2022-10-17', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(72, 1, 8, 815.58, 'Entradas al cine', '2022-10-28', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(73, 1, 6, 32.44, 'Pago de alquiler', '2022-11-11', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(74, 1, 3, 53.47, 'Matrícula curso online', '2022-11-07', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(75, 1, 2, 490.99, 'Compra de medicamentos', '2022-12-12', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(76, 1, 5, 481.04, 'Compra de supermercado', '2022-12-21', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(77, 1, 1, 438.18, 'Chequeo médico', '2023-01-07', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(78, 1, 2, 668.79, 'Entradas al cine', '2023-01-26', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(79, 1, 1, 788.52, 'Pasaje de autobús', '2023-02-23', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(80, 1, 1, 118.76, 'Chequeo médico', '2023-02-09', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(81, 1, 6, 529.99, 'Pago de alquiler', '2023-03-19', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(82, 1, 2, 218.06, 'Viaje en taxi', '2023-03-05', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(83, 1, 8, 297.01, 'Pago de alquiler', '2023-04-01', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(84, 1, 8, 555.78, 'Gastos imprevistos', '2023-04-12', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(85, 1, 5, 617.60, 'Pasaje de autobús', '2023-05-15', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(86, 1, 4, 392.62, 'Chequeo médico', '2023-05-19', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(87, 1, 1, 222.04, 'Compra semanal de alimentos', '2023-06-11', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(88, 1, 8, 253.21, 'Compra de medicamentos', '2023-06-26', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(89, 1, 3, 302.44, 'Cena en restaurante', '2023-07-16', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(90, 1, 2, 298.62, 'Regalo de cumpleaños', '2023-07-12', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(91, 1, 5, 861.23, 'Pago de alquiler', '2023-08-01', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(92, 1, 6, 266.91, 'Matrícula curso online', '2023-08-16', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(93, 1, 6, 553.09, 'Matrícula curso online', '2023-09-19', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(94, 1, 8, 89.67, 'Cena en restaurante', '2023-09-21', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(95, 1, 7, 815.31, 'Regalo de cumpleaños', '2023-10-01', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(96, 1, 3, 483.12, 'Chequeo médico', '2023-10-02', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(97, 1, 3, 965.44, 'Chequeo médico', '2023-11-24', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(98, 1, 1, 533.77, 'Pago de hipoteca', '2023-11-22', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(99, 1, 2, 566.98, 'Pago de alquiler', '2023-12-11', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(100, 1, 7, 713.61, 'Pago de alquiler', '2023-12-07', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(101, 1, 2, 134.78, 'Compra de libros', '2024-01-06', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(102, 1, 3, 559.62, 'Matrícula curso online', '2024-01-03', '2024-12-31 13:25:22', '2024-12-31 16:23:48', 1),
(103, 1, 1, 738.84, 'Pasaje de autobús', '2024-02-12', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(104, 1, 3, 975.18, 'Matrícula curso online', '2024-02-28', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(105, 1, 5, 900.55, 'Viaje en taxi', '2024-03-07', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(106, 1, 7, 871.54, 'Regalo de cumpleaños', '2024-03-07', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(107, 1, 2, 398.27, 'Compra semanal de alimentos', '2024-04-20', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(108, 1, 6, 887.66, 'Gastos imprevistos', '2024-04-16', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(109, 1, 3, 840.67, 'Regalo de cumpleaños', '2024-05-26', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(110, 1, 5, 324.53, 'Compra de supermercado', '2024-05-01', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(111, 1, 6, 918.46, 'Gastos imprevistos', '2024-06-04', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(112, 1, 1, 239.43, 'Viaje en taxi', '2024-06-28', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(113, 1, 2, 664.64, 'Compra de supermercado', '2024-07-27', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(114, 1, 8, 262.71, 'Pago de alquiler', '2024-07-28', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(115, 1, 3, 670.62, 'Compra de medicamentos', '2024-08-22', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(116, 1, 1, 485.40, 'Pago de alquiler', '2024-08-04', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(117, 1, 4, 522.73, 'Compra semanal de alimentos', '2024-09-19', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(118, 1, 1, 328.20, 'Suscripción a streaming', '2024-09-07', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(119, 1, 2, 447.73, 'Compra de supermercado', '2024-10-08', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(120, 1, 6, 17.60, 'Regalo de cumpleaños', '2024-10-11', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(121, 1, 8, 834.48, 'Matrícula curso online', '2024-11-07', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(122, 1, 6, 594.03, 'Matrícula curso online', '2024-11-13', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(123, 1, 4, 313.52, 'Cena en restaurante', '2024-12-03', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(124, 1, 6, 837.09, 'Compra de supermercado', '2024-12-17', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(125, 1, 3, 232.83, 'Viaje en taxi', '2025-01-03', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(126, 1, 4, 228.41, 'Compra de libros', '2025-01-06', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(127, 1, 2, 378.36, 'Entradas al cine', '2025-02-11', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(128, 1, 5, 135.28, 'Compra de libros', '2025-02-27', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(129, 1, 5, 465.20, 'Chequeo médico', '2025-03-19', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(130, 1, 4, 41.74, 'Chequeo médico', '2025-03-23', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(131, 1, 1, 909.55, 'Viaje en taxi', '2025-04-04', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(132, 1, 3, 838.02, 'Entradas al cine', '2025-04-12', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(133, 1, 3, 652.02, 'Compra de supermercado', '2025-05-10', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(134, 1, 4, 65.25, 'Gastos imprevistos', '2025-05-11', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(135, 1, 2, 445.57, 'Regalo de cumpleaños', '2025-06-13', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(136, 1, 8, 446.96, 'Gastos imprevistos', '2025-06-17', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(137, 1, 2, 944.58, 'Compra de supermercado', '2025-07-17', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(138, 1, 1, 855.77, 'Gastos imprevistos', '2025-07-11', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(139, 1, 8, 778.65, 'Compra de medicamentos', '2025-08-19', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(140, 1, 3, 649.46, 'Chequeo médico', '2025-08-02', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(141, 1, 4, 72.92, 'Compra de supermercado', '2025-09-04', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(142, 1, 2, 788.71, 'Suscripción a streaming', '2025-09-19', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(143, 1, 2, 138.97, 'Matrícula curso online', '2025-10-26', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(144, 1, 4, 58.80, 'Pago de alquiler', '2025-10-11', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(145, 1, 3, 892.58, 'Compra de supermercado', '2025-11-23', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(146, 1, 4, 512.35, 'Viaje en taxi', '2025-11-08', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(147, 1, 5, 714.59, 'Compra semanal de alimentos', '2025-12-02', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0),
(148, 1, 8, 328.15, 'Viaje en taxi', '2025-12-21', '2024-12-31 13:25:22', '2024-12-31 13:25:22', 0);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `correo`, `foto_perfil`, `descripcion`, `contrasena`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'Diego', 'dextremiana1998@gmail.com', 'assets/images/profileImages/curriculum.jpg', 'Hola que pasa\r\n', '$2y$10$0KehvBT0RE9dBFqG1pAhOeqmJNVjij50kWK2O4Ni8SRhgwcPaCHmm', '2024-12-31 10:11:15', '2024-12-31 16:22:48'),
(2, 'pepe', 'pepe@pepenson.comm', NULL, 'Me llamo Pepe', '$2y$10$14aU4aRDj/IKkLI2W0rpTOnN1PM2ZnDr98lkK91AG5oysSyeHPoxG', '2024-12-31 10:11:40', '2024-12-31 14:14:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `etiquetas`
--
ALTER TABLE `etiquetas`
  ADD PRIMARY KEY (`id_etiqueta`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id_gasto`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_etiqueta` (`id_etiqueta`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `etiquetas`
--
ALTER TABLE `etiquetas`
  MODIFY `id_etiqueta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id_gasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `gastos_ibfk_2` FOREIGN KEY (`id_etiqueta`) REFERENCES `etiquetas` (`id_etiqueta`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
