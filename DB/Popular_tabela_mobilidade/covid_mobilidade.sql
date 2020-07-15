-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15-Jul-2020 às 20:30
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
-- Estrutura da tabela `covid_mobilidade`
--

CREATE TABLE `covid_mobilidade` (
  `id` int(11) NOT NULL,
  `country_region_code` varchar(7) NOT NULL,
  `country_region` varchar(20) NOT NULL,
  `sub_region_1` varchar(25) NOT NULL,
  `sub_region_2` varchar(25) NOT NULL,
  `iso_3166_2_code` int(10) NOT NULL,
  `census_fips_code` int(15) NOT NULL,
  `date` date NOT NULL,
  `retail_and_recreation_percent_change_from_baseline` int(6) NOT NULL,
  `grocery_and_pharmacy_percent_change_from_baseline` int(6) NOT NULL,
  `parks_percent_change_from_baseline` int(6) NOT NULL,
  `transit_stations_percent_change_from_baseline` int(6) NOT NULL,
  `workplaces_percent_change_from_baseline` int(6) NOT NULL,
  `residential_percent_change_from_baseline` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `covid_mobilidade`
--
ALTER TABLE `covid_mobilidade`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `covid_mobilidade`
--
ALTER TABLE `covid_mobilidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
