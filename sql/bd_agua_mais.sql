-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 20-Mar-2025 às 20:08
-- Versão do servidor: 8.3.0
-- versão do PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bd_agua_mais`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `sobrenome` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `endereco` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `bairro` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `capacidade_tanque` int NOT NULL,
  `cpf_nif` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `senha` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `codigo_verificacao` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `verificado` tinyint(1) NOT NULL DEFAULT '0',
  `data_cadastro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `sobrenome`, `telefone`, `email`, `endereco`, `bairro`, `capacidade_tanque`, `cpf_nif`, `senha`, `codigo_verificacao`, `verificado`, `data_cadastro`) VALUES
(2, 'Harry', 'Mário', '923962517', 'harrymario30@gmail.com', 'Luanda', 'Cazenga, Mabor', 15000, NULL, '$2y$10$IIrvG.ILTB8KOXhQqYS3PeZzknVZj5TPsVJZdEPuqnlT2MHPZW4nu', '611936', 1, '2025-03-16 01:15:17');

-- --------------------------------------------------------

--
-- Estrutura da tabela `motoristas`
--

DROP TABLE IF EXISTS `motoristas`;
CREATE TABLE IF NOT EXISTS `motoristas` (
  `id_motorista` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `sobrenome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `telefone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `endereco` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `tipo_cisterna` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `placa_veiculo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `disponivel` tinyint(1) DEFAULT '1',
  `conta_bancaria` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cpf_nif` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_motorista` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `foto_cisterna` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `codigo_verificacao` varchar(10) DEFAULT NULL,
  `verificado` tinyint(1) NOT NULL DEFAULT '0',
  `senha` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_motorista`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `motoristas`
--

INSERT INTO `motoristas` (`id_motorista`, `nome`, `sobrenome`, `telefone`, `email`, `endereco`, `bairro`, `tipo_cisterna`, `placa_veiculo`, `latitude`, `longitude`, `disponivel`, `conta_bancaria`, `data_cadastro`, `cpf_nif`, `foto_motorista`, `foto_cisterna`, `codigo_verificacao`, `verificado`, `senha`) VALUES
(2, 'Harry', 'Mário', '923962517', 'harrymario30@gmail.com', 'Luanda', 'Cazenga,Mabor', 'Volvo', 'LD-12-87-HR', 0.00000000, 0.00000000, 1, '435t52vyrty', '2025-03-15 19:02:04', 'ghhhtyudh', '67d5c09c19d66.jpg', '67d5c09c19914.jpg', '815200', 0, '$2y$10$213O.9eo2RDHoXrHdur4W.L5H0A9Zk.ITuIGgagXRJY12JRpFRqma');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `id_motorista` int DEFAULT NULL,
  `quantidade` decimal(10,2) NOT NULL,
  `prioridade` enum('urgente','agendado') NOT NULL,
  `status` enum('pendente','andamento','concluido','cancelado') DEFAULT 'pendente',
  `data_pedido` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`id_cliente`),
  KEY `motorista_id` (`id_motorista`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_cliente`, `id_motorista`, `quantidade`, `prioridade`, `status`, `data_pedido`, `data_atualizacao`, `latitude`, `longitude`) VALUES
(1, 2, NULL, 15000.00, 'urgente', 'cancelado', '2025-03-19 08:43:42', '2025-03-20 11:46:32', NULL, NULL),
(2, 2, 2, 12000.00, 'urgente', 'andamento', '2025-03-19 09:09:04', '2025-03-20 16:55:51', NULL, NULL),
(3, 2, NULL, 10.00, 'urgente', 'pendente', '2025-03-19 09:10:48', '2025-03-19 09:10:48', NULL, NULL),
(4, 2, 2, 10.00, 'urgente', 'andamento', '2025-03-19 09:13:46', '2025-03-20 14:25:31', NULL, NULL),
(5, 2, NULL, 0.00, 'urgente', 'cancelado', '2025-03-19 22:46:11', '2025-03-19 23:15:01', NULL, NULL),
(6, 2, NULL, 0.00, 'urgente', 'cancelado', '2025-03-19 22:46:26', '2025-03-19 23:05:17', NULL, NULL),
(7, 2, NULL, 30000.00, 'urgente', 'cancelado', '2025-03-19 22:47:48', '2025-03-19 23:05:10', NULL, NULL),
(8, 2, NULL, 10000.00, 'urgente', 'pendente', '2025-03-19 23:15:19', '2025-03-19 23:15:19', NULL, NULL),
(9, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:27', '2025-03-20 11:59:27', -8.87357440, 13.25465600),
(10, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:27', '2025-03-20 11:59:27', -8.87357440, 13.25465600),
(11, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:27', '2025-03-20 11:59:27', -8.87357440, 13.25465600),
(12, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:27', '2025-03-20 11:59:27', -8.87357440, 13.25465600),
(13, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:27', '2025-03-20 11:59:27', -8.87357440, 13.25465600),
(14, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:27', '2025-03-20 11:59:27', -8.87357440, 13.25465600),
(15, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:29', '2025-03-20 11:59:29', -8.87357440, 13.25465600),
(16, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:29', '2025-03-20 11:59:29', -8.87357440, 13.25465600),
(17, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:29', '2025-03-20 11:59:29', -8.87357440, 13.25465600),
(18, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:29', '2025-03-20 11:59:29', -8.87357440, 13.25465600),
(19, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:29', '2025-03-20 11:59:29', -8.87357440, 13.25465600),
(20, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:29', '2025-03-20 11:59:29', -8.87357440, 13.25465600),
(21, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:29', '2025-03-20 11:59:29', -8.87357440, 13.25465600),
(22, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:29', '2025-03-20 11:59:29', -8.87357440, 13.25465600),
(23, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:29', '2025-03-20 11:59:29', -8.87357440, 13.25465600),
(24, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:29', '2025-03-20 11:59:29', -8.87357440, 13.25465600),
(25, 2, 2, 9000.00, 'urgente', 'andamento', '2025-03-20 11:59:29', '2025-03-20 14:26:16', -8.87357440, 13.25465600),
(26, 2, NULL, 9000.00, 'urgente', 'pendente', '2025-03-20 11:59:29', '2025-03-20 11:59:29', -8.87357440, 13.25465600),
(27, 2, NULL, 8000.00, 'agendado', 'pendente', '2025-03-20 12:00:47', '2025-03-20 12:00:47', -8.87357440, 13.25465600),
(28, 2, NULL, 99000.00, 'agendado', 'pendente', '2025-03-20 16:20:59', '2025-03-20 16:20:59', -8.86374400, 13.25465600);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
