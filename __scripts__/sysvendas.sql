-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 20/10/2023 às 12:52
-- Versão do servidor: 10.4.20-MariaDB
-- Versão do PHP: 7.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sysvendas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tab_metas`
--

CREATE TABLE `tab_metas` (
  `met_codigo` int(11) NOT NULL,
  `met_dtentrada` timestamp NOT NULL DEFAULT current_timestamp(),
  `met_mesref` varchar(20) DEFAULT NULL,
  `met_valor` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `tab_metas`
--

INSERT INTO `tab_metas` (`met_codigo`, `met_dtentrada`, `met_mesref`, `met_valor`) VALUES
(6, '2023-09-13 19:12:05', 'Setembro/2023', '1000.00'),
(7, '2023-09-15 14:55:05', 'Outubro/2023', '30000.00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tab_pomodoro`
--

CREATE TABLE `tab_pomodoro` (
  `pom_codigo` int(11) NOT NULL,
  `pom_dtdata` date NOT NULL,
  `pom_hrhora` time NOT NULL,
  `pom_obs` varchar(100) NOT NULL,
  `pom_finalizado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `tab_pomodoro`
--

INSERT INTO `tab_pomodoro` (`pom_codigo`, `pom_dtdata`, `pom_hrhora`, `pom_obs`, `pom_finalizado`) VALUES
(1, '2023-08-23', '12:45:00', 'horario do pomodoro', 0),
(2, '2023-08-31', '08:00:00', 'asdfsdfasd', 0),
(3, '2023-08-31', '08:00:00', 'yourself', 0),
(4, '2023-08-23', '10:45:00', 'teste de gravação de pomodoro', 0),
(5, '2023-08-24', '10:45:00', 'novo registro do dia', 1),
(6, '2023-08-24', '14:52:00', 'Ligar para o cliente Marcos Assunção de Oliveira', 1),
(7, '2023-08-24', '15:15:00', 'teste de novo atendimento para hoje', 1),
(8, '2023-08-24', '15:22:00', 'novo compromisso', 1),
(9, '2023-08-24', '15:30:00', 'novo atendimento', 1),
(10, '2023-08-24', '15:39:00', 'novo atendimento', 1),
(11, '2023-08-24', '15:42:00', 'novo atendimento aqui', 1),
(12, '2023-08-24', '16:25:00', 'novo atae', 1),
(13, '2023-08-25', '16:30:00', 'nvo', 0),
(14, '2023-08-24', '12:00:00', 'novo', 0),
(15, '2023-09-12', '17:45:00', 'teste de pomodoro', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tab_vendas`
--

CREATE TABLE `tab_vendas` (
  `ven_codigo` int(11) NOT NULL,
  `ven_dtentrada` timestamp NOT NULL DEFAULT current_timestamp(),
  `ven_mesref` varchar(20) DEFAULT NULL,
  `ven_contrato` varchar(200) DEFAULT NULL,
  `ven_valor` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `tab_vendas`
--

INSERT INTO `tab_vendas` (`ven_codigo`, `ven_dtentrada`, `ven_mesref`, `ven_contrato`, `ven_valor`) VALUES
(2, '2023-09-13 19:26:57', 'Setembro/2023', 'contrato 1', '150.00'),
(3, '2023-09-13 19:27:06', 'Setembro/2023', 'contrato 2', '20.00'),
(4, '2023-09-13 19:27:41', 'Setembro/2023', 'contrato 33', '2.00'),
(5, '2023-09-13 19:28:03', 'Setembro/2023', 'ccc', '1.22'),
(6, '2023-09-13 19:28:45', 'Setembro/2023', 'XYZ', '1.20'),
(7, '2023-09-15 14:55:22', 'Outubro/2023', 'CONTRATO DE OUTUBRO', '2000.00');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tab_metas`
--
ALTER TABLE `tab_metas`
  ADD PRIMARY KEY (`met_codigo`),
  ADD KEY `idx_met_mesref` (`met_mesref`),
  ADD KEY `idx_met_dtentrada` (`met_dtentrada`);

--
-- Índices de tabela `tab_pomodoro`
--
ALTER TABLE `tab_pomodoro`
  ADD PRIMARY KEY (`pom_codigo`),
  ADD KEY `idx_pom_dtdata` (`pom_dtdata`);

--
-- Índices de tabela `tab_vendas`
--
ALTER TABLE `tab_vendas`
  ADD PRIMARY KEY (`ven_codigo`),
  ADD KEY `idx_ven_dtentrada` (`ven_dtentrada`),
  ADD KEY `idx_ven_mesref` (`ven_mesref`),
  ADD KEY `idx_ven_contrato` (`ven_contrato`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tab_metas`
--
ALTER TABLE `tab_metas`
  MODIFY `met_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `tab_pomodoro`
--
ALTER TABLE `tab_pomodoro`
  MODIFY `pom_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `tab_vendas`
--
ALTER TABLE `tab_vendas`
  MODIFY `ven_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
