-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 15-Mar-2025 às 15:35
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
  `conta_bancaria` varchar(50) DEFAULT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cpf_nif` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_motorista` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `foto_cisterna` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `codigo_verificacao` varchar(10) DEFAULT NULL,
  `verificado` tinyint(1) NOT NULL DEFAULT '0',
  `senha` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_motorista`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `motoristas`
--

INSERT INTO `motoristas` (`id_motorista`, `nome`, `sobrenome`, `telefone`, `email`, `endereco`, `bairro`, `tipo_cisterna`, `placa_veiculo`, `latitude`, `longitude`, `disponivel`, `conta_bancaria`, `data_cadastro`, `cpf_nif`, `foto_motorista`, `foto_cisterna`, `codigo_verificacao`, `verificado`, `senha`) VALUES
(1, 'Harry', 'Mário', '923962517', 'harrymario30@gmail.com', 'Luanda', 'Cazenga, Mabor', 'Volvo', 'LD-12-87-HR', 0.00000000, 0.00000000, 1, NULL, '2025-03-15 16:32:27', NULL, NULL, NULL, '992338', 0, '$2y$10$JJjN1hcLdn/j07VNDHXho.bGU9t9ct9vENmFHW4lrYA.XWmvb4Um2');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
