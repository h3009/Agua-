-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 13-Mar-2025 às 17:40
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
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `sobrenome`, `telefone`, `email`, `endereco`, `bairro`, `capacidade_tanque`, `cpf_nif`, `senha`, `codigo_verificacao`, `verificado`, `data_cadastro`) VALUES
(1, 'Harry', '', '923962517', 'harrymario30@gmail.com', 'Luanda', 'Cazenga, Mabor', 15, NULL, '$2y$10$/oGAQUFoWGQrLRgjZf6UUupQjuJNm6bxr7SNPg3cXe1BNmfFgoWNC', '915766', 0, '2025-03-13 01:39:04');

-- --------------------------------------------------------

--
-- Estrutura da tabela `motoristas`
--

DROP TABLE IF EXISTS `motoristas`;
CREATE TABLE IF NOT EXISTS `motoristas` (
  `id_motorista` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `tipo_cisterna` varchar(50) NOT NULL,
  `placa_veiculo` varchar(20) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `disponivel` tinyint(1) DEFAULT '1',
  `conta_bancaria` varchar(50) DEFAULT NULL,
  `foto_motorista` varchar(255) DEFAULT NULL,
  `foto_cisterna` varchar(255) DEFAULT NULL,
  `senha` varchar(100) NOT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_motorista`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
