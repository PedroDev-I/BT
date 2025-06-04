-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04-Jun-2025 às 03:19
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `banco_animalsave`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id_agendamento` int(11) NOT NULL,
  `id_animal` int(11) DEFAULT NULL,
  `data_hora` datetime NOT NULL,
  `id_servico` int(11) DEFAULT NULL,
  `status` enum('confirmado','cancelado','concluído') DEFAULT 'confirmado',
  `valor` decimal(10,2) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_cliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `agendamentos`
--

INSERT INTO `agendamentos` (`id_agendamento`, `id_animal`, `data_hora`, `id_servico`, `status`, `valor`, `observacoes`, `created`, `modified`, `id_cliente`) VALUES
(20, 6, '2025-06-11 11:26:00', 3, '', '90.00', '', '2025-06-03 22:26:28', '2025-06-03 22:26:51', NULL),
(21, 7, '2025-06-11 11:05:00', 3, '', '90.00', 'Tem alergia a cu', '2025-06-03 23:03:51', '2025-06-03 23:04:04', NULL),
(22, 8, '2025-06-11 11:47:00', 3, '', '90.00', '', '2025-06-04 00:46:26', '2025-06-04 00:46:29', NULL),
(23, 8, '2025-06-11 11:49:00', 2, '', '40.00', '', '2025-06-04 00:49:52', '2025-06-04 00:50:24', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `animais`
--

CREATE TABLE `animais` (
  `id_animal` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `raca` varchar(100) DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `animais`
--

INSERT INTO `animais` (`id_animal`, `id_cliente`, `nome`, `foto`, `tipo`, `raca`, `idade`, `peso`, `observacoes`, `created`, `modified`) VALUES
(6, 8, 'Jack', '', 'Cachorro', 'Husky', 2, '13.00', NULL, '2025-06-03 22:25:47', '2025-06-03 22:25:47'),
(7, 8, 'Tiziu', '', 'Gato', 'Nsei', 15, '100.00', NULL, '2025-06-03 23:03:30', '2025-06-03 23:03:30'),
(8, 9, 'Zulu', '', 'Cachorro', 'Husky', 100, '2.00', NULL, '2025-06-04 00:46:13', '2025-06-04 00:46:13');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `foto` varchar(255) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `cpf`, `foto`, `telefone`, `endereco`, `email`, `senha`, `created`, `modified`) VALUES
(8, 'Pedro', NULL, '', NULL, 'adsf', 'teste123@gmail.com', '$2y$10$II.KOIYkvmZsLN4nJsQklOjH6o2PKIo9L5vrxHtqA.2IPcb4nMbJm', '2025-06-03 22:24:45', '2025-06-03 22:25:14'),
(9, 'Lucas', NULL, '', NULL, '', '123@gmail.com', '$2y$10$arBwy3tWg.r/V9e8LHXBUuMep0.AL/pRaGv9Wwuv88hdNuZ78PYFy', '2025-06-04 00:44:55', '2025-06-04 00:52:28');

-- --------------------------------------------------------

--
-- Estrutura da tabela `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id_feedback` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_agendamento` int(11) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `avaliacao` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id_funcionario` int(11) NOT NULL,
  `codigo` int(6) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `servicos_que_realiza` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `funcionarios`
--

INSERT INTO `funcionarios` (`id_funcionario`, `codigo`, `nome`, `foto`, `telefone`, `servicos_que_realiza`, `created`, `modified`, `senha`) VALUES
(2, 555666, 'Pedro', '', NULL, NULL, '2025-06-04 00:24:33', '2025-06-04 00:24:33', '$2y$10$TII9sQjwav9H1P2U/H5C7O2qaepp.sjdaIHa9gmXeXxcbh5SYiUcm'),
(3, 777888, 'Isaac', '', NULL, NULL, '2025-06-04 00:25:31', '2025-06-04 00:25:31', '$2y$10$UX1tZEfe/eujveKp/1c2xedSoE8a1afq2zHAiaFzVxZgHg2KOe5ym');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pagamentos`
--

CREATE TABLE `pagamentos` (
  `id_pagamento` int(11) NOT NULL,
  `id_agendamento` int(11) DEFAULT NULL,
  `valor_pago` decimal(10,2) NOT NULL,
  `forma_pagamento` enum('dinheiro','cartão','pix') NOT NULL,
  `data_pagamento` datetime NOT NULL,
  `status` enum('pago','pendente') DEFAULT 'pendente',
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `pagamentos`
--

INSERT INTO `pagamentos` (`id_pagamento`, `id_agendamento`, `valor_pago`, `forma_pagamento`, `data_pagamento`, `status`, `created`, `modified`) VALUES
(21, 20, '90.00', 'pix', '2025-06-04 00:26:51', 'pendente', '2025-06-03 22:26:51', '2025-06-03 22:26:51'),
(22, 21, '90.00', 'pix', '2025-06-04 01:04:04', 'pendente', '2025-06-03 23:04:04', '2025-06-03 23:04:04'),
(23, 22, '90.00', 'pix', '2025-06-04 02:46:29', 'pendente', '2025-06-04 00:46:29', '2025-06-04 00:46:29'),
(24, 23, '40.00', 'pix', '2025-06-04 02:50:24', 'pendente', '2025-06-04 00:50:24', '2025-06-04 00:50:24');

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos`
--

CREATE TABLE `servicos` (
  `id_servico` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `servicos`
--

INSERT INTO `servicos` (`id_servico`, `nome`, `descricao`, `preco`, `created`, `modified`) VALUES
(1, 'Banho', NULL, '50.00', '2025-06-03 04:20:39', '2025-06-03 04:20:39'),
(2, 'Tosa', NULL, '40.00', '2025-06-03 04:20:39', '2025-06-03 04:20:39'),
(3, 'Banho com Tosa', NULL, '90.00', '2025-06-03 04:20:39', '2025-06-03 04:20:39');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id_agendamento`),
  ADD KEY `id_animal` (`id_animal`),
  ADD KEY `id_servico` (`id_servico`),
  ADD KEY `fk_agendamento_cliente` (`id_cliente`);

--
-- Índices para tabela `animais`
--
ALTER TABLE `animais`
  ADD PRIMARY KEY (`id_animal`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Índices para tabela `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id_feedback`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_agendamento` (`id_agendamento`);

--
-- Índices para tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id_funcionario`);

--
-- Índices para tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`id_pagamento`),
  ADD KEY `id_agendamento` (`id_agendamento`);

--
-- Índices para tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id_servico`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id_agendamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `animais`
--
ALTER TABLE `animais`
  MODIFY `id_animal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id_feedback` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id_funcionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `id_pagamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id_servico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`id_animal`) REFERENCES `animais` (`id_animal`) ON DELETE CASCADE,
  ADD CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`id_servico`) REFERENCES `servicos` (`id_servico`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_agendamento_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);

--
-- Limitadores para a tabela `animais`
--
ALTER TABLE `animais`
  ADD CONSTRAINT `animais_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedbacks_ibfk_2` FOREIGN KEY (`id_agendamento`) REFERENCES `agendamentos` (`id_agendamento`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD CONSTRAINT `pagamentos_ibfk_1` FOREIGN KEY (`id_agendamento`) REFERENCES `agendamentos` (`id_agendamento`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
