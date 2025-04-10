-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07-Abr-2025 às 21:29
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `3gre`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `registros_de_pontos`
--

CREATE TABLE `registros_de_pontos` (
  `id` int(11) NOT NULL,
  `matricula` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `setor` varchar(50) NOT NULL,
  `data` varchar(50) NOT NULL,
  `hora_entrada` time NOT NULL,
  `hora_saida` time NOT NULL,
  `horas_trabalhadas` varchar(50) NOT NULL,
  `hora_extra` varchar(50) NOT NULL,
  `percentual_jornada` varchar(50) NOT NULL,
  `faltas` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `registros_de_pontos`
--

INSERT INTO `registros_de_pontos` (`id`, `matricula`, `nome`, `setor`, `data`, `hora_entrada`, `hora_saida`, `horas_trabalhadas`, `hora_extra`, `percentual_jornada`, `faltas`) VALUES
(27, '6867685', 'JANETE BESERRA DE FRANÇA', 'NUAFS', '07/04/2025', '07:00:00', '15:52:49', '00:00', '00:00', '0', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios_3gre`
--

CREATE TABLE `usuarios_3gre` (
  `id` int(11) NOT NULL,
  `matricula` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `setor` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `inicio_da_jornada` time NOT NULL,
  `final_da_jornada` time NOT NULL,
  `carga_horaria` int(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `nivel_acesso` enum('admin','rh','funcionario') NOT NULL DEFAULT 'funcionario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios_3gre`
--

INSERT INTO `usuarios_3gre` (`id`, `matricula`, `nome`, `setor`, `email`, `inicio_da_jornada`, `final_da_jornada`, `carga_horaria`, `status`, `nivel_acesso`) VALUES
(1, '6135986', 'ALAN DOUGLAS LIMA SILVA', 'NUAFS', 'teste@gmail.com', '08:00:00', '13:00:00', 5, '', 'funcionario'),
(2, '6377971', 'ADERSON DINIZ PEREIRA', 'NUAFS', 'teste@gmail.com', '08:00:00', '13:00:00', 5, '', 'funcionario'),
(3, '628923', 'JOSÉ LÚCIO DO Ó SILVA', 'NUAFS', 'teste@gmail.com', '08:00:00', '13:00:00', 5, '', 'funcionario'),
(4, '6867685', 'JANETE BESERRA DE FRANÇA', 'NUAFS', '3gre@gmail.com', '08:00:00', '13:00:00', 5, '', 'funcionario');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `registros_de_pontos`
--
ALTER TABLE `registros_de_pontos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios_3gre`
--
ALTER TABLE `usuarios_3gre`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `registros_de_pontos`
--
ALTER TABLE `registros_de_pontos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `usuarios_3gre`
--
ALTER TABLE `usuarios_3gre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
