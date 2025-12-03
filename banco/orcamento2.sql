-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 24/11/2025 às 17:16
-- Versão do servidor: 8.4.3
-- Versão do PHP: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `orcamento2`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbcategorias`
--

CREATE TABLE `tbcategorias` (
  `idcat` int NOT NULL,
  `categoria` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `log` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbcategorias`
--

INSERT INTO `tbcategorias` (`idcat`, `categoria`, `log`) VALUES
(8, 'CLIENTE', 'admin'),
(9, 'LEAD', 'admin');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbclientes`
--

CREATE TABLE `tbclientes` (
  `idc` int NOT NULL,
  `nome` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `categoria` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipodoc` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `documento` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cep` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rua` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cidade` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bairro` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uf` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `datareg` date DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefone` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `celular` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `obs` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `log` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `datalog` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cad_status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbempresa`
--

CREATE TABLE `tbempresa` (
  `ide` int NOT NULL,
  `nome` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cnpj` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `inscestadual` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cep` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rua` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `num` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `numcomp` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cidade` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bairro` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uf` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefone` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telcom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `celular` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `site` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `imagem` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id` varchar(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `barra` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `letra` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbempresa`
--

INSERT INTO `tbempresa` (`ide`, `nome`, `cnpj`, `inscestadual`, `cep`, `rua`, `num`, `numcomp`, `cidade`, `bairro`, `uf`, `email`, `telefone`, `telcom`, `celular`, `site`, `imagem`, `id`, `barra`, `letra`) VALUES
(1, 'Empresa do Armando', '56.023.666/6666-66', '111122222', '20530-001', 'Rua da Empresa ', '1122', 'Casa 3 ', 'Rio de Janeiro', 'Tijuca', 'RJ', 'seu@email.com.br', '2100000000', '21000000000', '2100000000', 'www.seusite.com.br', '15logo.png', NULL, '#0275d8', '#f7f7f7');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbhistoricos`
--

CREATE TABLE `tbhistoricos` (
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idh` int NOT NULL,
  `descricao` varchar(350) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `idc` int NOT NULL,
  `nome` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `log` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `conteudo_file` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nome_file` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `pasta_file` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbitens`
--

CREATE TABLE `tbitens` (
  `idi` int NOT NULL,
  `ido` int NOT NULL,
  `idc` int NOT NULL,
  `data` date DEFAULT NULL,
  `descricao` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantidade` int DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbordens`
--

CREATE TABLE `tbordens` (
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ido` int NOT NULL,
  `idc` int NOT NULL,
  `nome` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `log` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_ordem` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbprodutos`
--

CREATE TABLE `tbprodutos` (
  `idp` int NOT NULL,
  `produto` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `log` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbusuarios`
--

CREATE TABLE `tbusuarios` (
  `id` int NOT NULL,
  `nome` varchar(220) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(220) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `usuario` varchar(220) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `senha` varchar(220) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `controle` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `status` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbusuarios`
--

INSERT INTO `tbusuarios` (`id`, `nome`, `email`, `usuario`, `senha`, `controle`, `status`) VALUES
(1, 'Admin', 'admin@admin', 'admin', '$2y$10$Ve.RvDnhVVj3MmsyeEzatO2q4.Y.yglluOLa.XU5TLSFgPYKTHYZG', 'index.php', 'administrador');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbcategorias`
--
ALTER TABLE `tbcategorias`
  ADD PRIMARY KEY (`idcat`);

--
-- Índices de tabela `tbclientes`
--
ALTER TABLE `tbclientes`
  ADD PRIMARY KEY (`idc`);

--
-- Índices de tabela `tbempresa`
--
ALTER TABLE `tbempresa`
  ADD PRIMARY KEY (`ide`);

--
-- Índices de tabela `tbhistoricos`
--
ALTER TABLE `tbhistoricos`
  ADD PRIMARY KEY (`idh`),
  ADD KEY `fk_historicos` (`idc`) USING BTREE;

--
-- Índices de tabela `tbitens`
--
ALTER TABLE `tbitens`
  ADD PRIMARY KEY (`idi`);

--
-- Índices de tabela `tbordens`
--
ALTER TABLE `tbordens`
  ADD PRIMARY KEY (`ido`),
  ADD KEY `fk_historicos` (`idc`) USING BTREE;

--
-- Índices de tabela `tbprodutos`
--
ALTER TABLE `tbprodutos`
  ADD PRIMARY KEY (`idp`);

--
-- Índices de tabela `tbusuarios`
--
ALTER TABLE `tbusuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbcategorias`
--
ALTER TABLE `tbcategorias`
  MODIFY `idcat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `tbclientes`
--
ALTER TABLE `tbclientes`
  MODIFY `idc` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbempresa`
--
ALTER TABLE `tbempresa`
  MODIFY `ide` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbhistoricos`
--
ALTER TABLE `tbhistoricos`
  MODIFY `idh` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbitens`
--
ALTER TABLE `tbitens`
  MODIFY `idi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbordens`
--
ALTER TABLE `tbordens`
  MODIFY `ido` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbprodutos`
--
ALTER TABLE `tbprodutos`
  MODIFY `idp` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbusuarios`
--
ALTER TABLE `tbusuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tbordens`
--
ALTER TABLE `tbordens`
  ADD CONSTRAINT `DELETE` FOREIGN KEY (`idc`) REFERENCES `tbclientes` (`idc`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
