-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08-Jul-2020 às 17:15
-- Versão do servidor: 10.4.11-MariaDB
-- versão do PHP: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `comp_urbana`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `dados_covid19`
--

CREATE TABLE `dados_covid19` (
  `id` int(11) NOT NULL,
  `city` varchar(30) NOT NULL,
  `city_ibge_code` int(11) NOT NULL,
  `date` varchar(11) NOT NULL,
  `epidemiological_week` int(11) NOT NULL,
  `estimated_population_2019` int(11) NOT NULL,
  `is_last` varchar(7) NOT NULL,
  `is_repeated` varchar(7) NOT NULL,
  `last_available_confirmed` int(11) NOT NULL,
  `la_confirmed_per_100k_hab` float NOT NULL,
  `last_available_date` varchar(11) NOT NULL,
  `last_available_death_rate` float NOT NULL,
  `last_available_deaths` int(11) NOT NULL,
  `order_for_place` int(11) NOT NULL,
  `place_type` varchar(10) NOT NULL,
  `state` varchar(6) NOT NULL,
  `new_confirmed` int(11) NOT NULL,
  `new_deaths` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `dados_covid19`
--
ALTER TABLE `dados_covid19`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `dados_covid19`
--
ALTER TABLE `dados_covid19`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
