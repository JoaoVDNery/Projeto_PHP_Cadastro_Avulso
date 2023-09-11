-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/09/2023 às 19:17
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bss`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `arquivos`
--

CREATE TABLE `arquivos` (
  `id` int(4) NOT NULL,
  `id_cadastro` int(4) DEFAULT NULL,
  `path` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cadastro_avulso`
--

CREATE TABLE `cadastro_avulso` (
  `id` int(4) NOT NULL,
  `nome` text DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `admissao` date DEFAULT NULL,
  `sindicato` tinytext DEFAULT NULL,
  `razao_social` varchar(100) DEFAULT NULL,
  `cnpj` varchar(100) DEFAULT NULL,
  `justificativa` varchar(1500) DEFAULT NULL,
  `status` tinytext DEFAULT NULL,
  `data_registro` date DEFAULT NULL,
  `historico` varchar(5000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sindicato`
--

CREATE TABLE `sindicato` (
  `id` int(4) NOT NULL,
  `nome` tinytext DEFAULT NULL,
  `cnpj` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `situacao`
--

CREATE TABLE `situacao` (
  `id` int(4) NOT NULL,
  `status` tinytext DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `status_bss`
--

CREATE TABLE `status_bss` (
  `id` int(4) NOT NULL,
  `nome` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `trabalhadores`
--

CREATE TABLE `trabalhadores` (
  `id` int(4) NOT NULL,
  `nome` text DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `admissao` date DEFAULT NULL,
  `sindicato` tinytext DEFAULT NULL,
  `razao_social` varchar(100) DEFAULT NULL,
  `cnpj` varchar(100) DEFAULT NULL,
  `data_registro` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `arquivos`
--
ALTER TABLE `arquivos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cadastro_avulso`
--
ALTER TABLE `cadastro_avulso`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `sindicato`
--
ALTER TABLE `sindicato`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `situacao`
--
ALTER TABLE `situacao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `status_bss`
--
ALTER TABLE `status_bss`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `trabalhadores`
--
ALTER TABLE `trabalhadores`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `arquivos`
--
ALTER TABLE `arquivos`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cadastro_avulso`
--
ALTER TABLE `cadastro_avulso`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sindicato`
--
ALTER TABLE `sindicato`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `situacao`
--
ALTER TABLE `situacao`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `status_bss`
--
ALTER TABLE `status_bss`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `trabalhadores`
--
ALTER TABLE `trabalhadores`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
