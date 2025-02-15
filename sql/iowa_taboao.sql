/*
 Navicat Premium Data Transfer

 Source Server         : MySQL Local
 Source Server Type    : MySQL
 Source Server Version : 100136
 Source Host           : 127.0.0.1:3306
 Source Schema         : iowa

 Target Server Type    : MySQL
 Target Server Version : 100136
 File Encoding         : 65001

 Date: 15/01/2019 11:21:07
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for alunos
-- ----------------------------
DROP TABLE IF EXISTS `alunos`;
CREATE TABLE `alunos`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_situacao` int(11) NULL DEFAULT NULL,
  `id_unidade` int(11) NULL DEFAULT NULL,
  `id_origem` int(11) NULL DEFAULT NULL,
  `material` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `login` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `senha` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nome` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_nascimento` date NULL DEFAULT NULL,
  `rg` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cpf` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefone1` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefone2` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefone3` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `endereco` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `numero` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bairro` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `complemento` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estado` int(11) NULL DEFAULT NULL,
  `cidade` int(11) NULL DEFAULT NULL,
  `cep` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email1` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email2` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `facebook` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `menor` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `parentesco_responsavel` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nome_responsavel` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_nascimento_responsavel` date NULL DEFAULT NULL,
  `rg_responsavel` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cpf_responsavel` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefone1_responsavel` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefone2_responsavel` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefone3_responsavel` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `endereco_responsavel` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `numero_responsavel` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bairro_responsavel` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `complemento_responsavel` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estado_responsavel` int(11) NULL DEFAULT NULL,
  `cidade_responsavel` int(11) NULL DEFAULT NULL,
  `cep_responsavel` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email1_responsavel` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email2_responsavel` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `facebook_responsavel` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `situacao_aluno` int(11) NULL DEFAULT NULL,
  `responsavel_financeiro` int(11) NULL DEFAULT NULL,
  `id_empresa_financeiro` int(11) NULL DEFAULT NULL,
  `porcentagem_empresa` float(9, 2) NULL DEFAULT NULL,
  `responsavel_pedagogico` int(11) NULL DEFAULT NULL,
  `id_empresa_pedagogico` int(11) NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for alunos_observacoes
-- ----------------------------
DROP TABLE IF EXISTS `alunos_observacoes`;
CREATE TABLE `alunos_observacoes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_aluno` int(11) NULL DEFAULT NULL,
  `observacao` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for alunos_turmas
-- ----------------------------
DROP TABLE IF EXISTS `alunos_turmas`;
CREATE TABLE `alunos_turmas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_matricula` int(11) NULL DEFAULT NULL,
  `id_aluno` int(11) NULL DEFAULT NULL,
  `id_turma` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for arquivos_cnab
-- ----------------------------
DROP TABLE IF EXISTS `arquivos_cnab`;
CREATE TABLE `arquivos_cnab`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime(0) NULL DEFAULT NULL,
  `arquivo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for atas_coach
-- ----------------------------
DROP TABLE IF EXISTS `atas_coach`;
CREATE TABLE `atas_coach`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_turma` int(11) NULL DEFAULT NULL,
  `id_aluno` int(11) NULL DEFAULT NULL,
  `id_colega` int(11) NULL DEFAULT NULL,
  `id_coach` int(11) NULL DEFAULT NULL,
  `data` datetime(0) NULL DEFAULT NULL,
  `ata` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for aulas_alunos
-- ----------------------------
DROP TABLE IF EXISTS `aulas_alunos`;
CREATE TABLE `aulas_alunos`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_aula` int(11) NULL DEFAULT NULL,
  `id_aluno_turma` int(11) NULL DEFAULT NULL,
  `id_aluno` int(11) NULL DEFAULT NULL,
  `id_turma` int(11) NULL DEFAULT NULL,
  `presente` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tarefa` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for aulas_help
-- ----------------------------
DROP TABLE IF EXISTS `aulas_help`;
CREATE TABLE `aulas_help`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_help` int(11) NULL DEFAULT NULL,
  `data` date NULL DEFAULT NULL,
  `id_colega` int(11) NULL DEFAULT NULL,
  `numero_aula` int(11) NULL DEFAULT NULL,
  `conteudo_dado` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `hora_inicio` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `valor_hora_aula` float(9, 2) NULL DEFAULT NULL,
  `id_situacao_aula` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for aulas_turmas
-- ----------------------------
DROP TABLE IF EXISTS `aulas_turmas`;
CREATE TABLE `aulas_turmas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_turma` int(11) NULL DEFAULT NULL,
  `data` date NULL DEFAULT NULL,
  `id_nome_produto` int(11) NULL DEFAULT NULL,
  `id_situacao_aula` int(11) NULL DEFAULT NULL,
  `numero_aula` int(11) NULL DEFAULT NULL,
  `id_colega` int(11) NULL DEFAULT NULL,
  `conteudo_padrao` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `conteudo_dado` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `hora_inicio` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `valor_hora_aula` float(9, 2) NULL DEFAULT NULL,
  `tarefa` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reposicao` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for boletos
-- ----------------------------
DROP TABLE IF EXISTS `boletos`;
CREATE TABLE `boletos`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chave` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_parcela` int(11) NULL DEFAULT NULL,
  `numero_boleto` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `multa` float(9, 2) NULL DEFAULT NULL,
  `taxa` float(9, 2) NULL DEFAULT NULL,
  `juros_mora` float(9, 2) NULL DEFAULT NULL,
  `data_vencimento` date NULL DEFAULT NULL,
  `valor` float(9, 2) NULL DEFAULT NULL,
  `agencia` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `conta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `convenio` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nosso_numero` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sequencia` int(11) NULL DEFAULT NULL,
  `data_processamento` date NULL DEFAULT NULL,
  `demonstratitvo1` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `demonstratitvo2` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `demonstratitvo3` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `informacoes1` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `informacoes2` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `informacoes3` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `informacoes4` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pago` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cancelado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `renegociado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `observacoes` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for boletos_cnab
-- ----------------------------
DROP TABLE IF EXISTS `boletos_cnab`;
CREATE TABLE `boletos_cnab`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_arquivo` int(11) NULL DEFAULT NULL,
  `id_lote` int(11) NULL DEFAULT NULL,
  `numero_boleto` int(11) NULL DEFAULT NULL,
  `data_boleto` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for caixas
-- ----------------------------
DROP TABLE IF EXISTS `caixas`;
CREATE TABLE `caixas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_abertura` date NULL DEFAULT NULL,
  `hora_abertura` time(0) NULL DEFAULT NULL,
  `data_fechamento` date NULL DEFAULT NULL,
  `hora_fechamento` time(0) NULL DEFAULT NULL,
  `usuario_abertura` int(11) NULL DEFAULT NULL,
  `usuario_fechamento` int(11) NULL DEFAULT NULL,
  `id_colega` int(11) NULL DEFAULT NULL,
  `saldo_inicial` double(19, 2) NULL DEFAULT NULL,
  `total_entradas` double(19, 2) NULL DEFAULT NULL,
  `total_saidas` double(19, 2) NULL DEFAULT NULL,
  `total_dinheiro` double(19, 2) NULL DEFAULT NULL,
  `total_caixa` double(19, 2) NULL DEFAULT NULL,
  `situacao` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for categorias_instrutor
-- ----------------------------
DROP TABLE IF EXISTS `categorias_instrutor`;
CREATE TABLE `categorias_instrutor`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` float(9, 1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for categorias_lancamentos
-- ----------------------------
DROP TABLE IF EXISTS `categorias_lancamentos`;
CREATE TABLE `categorias_lancamentos`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cidades
-- ----------------------------
DROP TABLE IF EXISTS `cidades`;
CREATE TABLE `cidades`  (
  `id` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `estado_id` int(2) UNSIGNED ZEROFILL NOT NULL DEFAULT 00,
  `uf` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `nome` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  UNIQUE INDEX `id`(`id`) USING BTREE,
  INDEX `id_2`(`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for colegas
-- ----------------------------
DROP TABLE IF EXISTS `colegas`;
CREATE TABLE `colegas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_idioma` int(11) NULL DEFAULT NULL,
  `id_funcao` int(11) NULL DEFAULT NULL,
  `id_unidade` int(11) NULL DEFAULT NULL,
  `apelido` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nome` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rg` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cpf` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_nascimento` date NULL DEFAULT NULL,
  `telefone` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `celular` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `endereco` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `numero` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bairro` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `complemento` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estado` int(11) NULL DEFAULT NULL,
  `cidade` int(11) NULL DEFAULT NULL,
  `cep` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_admissao` date NULL DEFAULT NULL,
  `data_demissao` date NULL DEFAULT NULL,
  `banco` int(11) NULL DEFAULT NULL,
  `agencia` int(11) NULL DEFAULT NULL,
  `conta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `adm_contabil` float(9, 2) NULL DEFAULT NULL,
  `adm_valor_iowa` float(9, 2) NULL DEFAULT NULL,
  `choach_valor_hora` float(9, 2) NULL DEFAULT NULL,
  `coach_id_choach` int(11) NULL DEFAULT NULL,
  `instrutor_categoria` float(9, 1) NULL DEFAULT NULL,
  `instrutor_id_coach` int(11) NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for contas_pagar
-- ----------------------------
DROP TABLE IF EXISTS `contas_pagar`;
CREATE TABLE `contas_pagar`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int(11) NULL DEFAULT NULL,
  `id_categoria` int(11) NULL DEFAULT NULL,
  `id_natureza` int(11) NULL DEFAULT NULL,
  `id_unidade` int(11) NULL DEFAULT NULL,
  `data_lancamento` date NULL DEFAULT NULL,
  `data_vencimento` date NULL DEFAULT NULL,
  `valor` double(19, 2) NULL DEFAULT NULL,
  `descricao` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `observacoes` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `compartilhada` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pago` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cancelada` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_pagamento` date NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for detalhes_movimento
-- ----------------------------
DROP TABLE IF EXISTS `detalhes_movimento`;
CREATE TABLE `detalhes_movimento`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_movimento` int(11) NULL DEFAULT NULL,
  `id_parcela` int(11) NULL DEFAULT NULL,
  `id_aluno` int(11) NULL DEFAULT NULL,
  `numero_movimento` int(11) NULL DEFAULT NULL,
  `total` double(19, 2) NULL DEFAULT NULL,
  `desconto` double(19, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for empresas
-- ----------------------------
DROP TABLE IF EXISTS `empresas`;
CREATE TABLE `empresas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chave` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `login` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `senha` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nome_fantasia` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `razao_social` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cnpj` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ie` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rua` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `numero` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bairro` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `complemento` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estado` int(11) NULL DEFAULT NULL,
  `cidade` int(11) NULL DEFAULT NULL,
  `cep` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefone1` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefone2` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dia_vencimento` int(11) NULL DEFAULT NULL,
  `valor_hora_aula_help` float(9, 2) NULL DEFAULT NULL,
  `id_gerente` int(11) NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for empresas_observacoes
-- ----------------------------
DROP TABLE IF EXISTS `empresas_observacoes`;
CREATE TABLE `empresas_observacoes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NULL DEFAULT NULL,
  `observacao` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for envio_emails
-- ----------------------------
DROP TABLE IF EXISTS `envio_emails`;
CREATE TABLE `envio_emails`  (
  `id` int(2) NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `senha` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `smtp` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `porta_smtp` int(11) NULL DEFAULT NULL,
  `requer_autenticacao` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tipo_autenticacao` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for estados
-- ----------------------------
DROP TABLE IF EXISTS `estados`;
CREATE TABLE `estados`  (
  `estado_id` int(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `uf` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `nome` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`estado_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 28 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for formas_pagamento
-- ----------------------------
DROP TABLE IF EXISTS `formas_pagamento`;
CREATE TABLE `formas_pagamento`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forma_pagamento` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for fornecedores
-- ----------------------------
DROP TABLE IF EXISTS `fornecedores`;
CREATE TABLE `fornecedores`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fornecedor` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for funcoes
-- ----------------------------
DROP TABLE IF EXISTS `funcoes`;
CREATE TABLE `funcoes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `funcao` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for helps
-- ----------------------------
DROP TABLE IF EXISTS `helps`;
CREATE TABLE `helps`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_unidade` int(11) NULL DEFAULT NULL,
  `id_empresa` int(11) NULL DEFAULT NULL,
  `id_colega` int(11) NULL DEFAULT NULL,
  `tipo_help` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_aluno` int(11) NULL DEFAULT NULL,
  `data_inicio` date NULL DEFAULT NULL,
  `data_termino` date NULL DEFAULT NULL,
  `quantidade_helps` int(11) NULL DEFAULT NULL,
  `segunda` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `terca` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `quarta` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `quinta` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sexta` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sabado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `domingo` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_segunda` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_segunda` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_terca` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_terca` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_quarta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_quarta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_quinta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_quinta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_sexta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_sexta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_sabado` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_sabado` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_domingo` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_domingo` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 41 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for idiomas
-- ----------------------------
DROP TABLE IF EXISTS `idiomas`;
CREATE TABLE `idiomas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idioma` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for lotes_cnab
-- ----------------------------
DROP TABLE IF EXISTS `lotes_cnab`;
CREATE TABLE `lotes_cnab`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_arquivo` int(11) NULL DEFAULT NULL,
  `lote` int(11) NULL DEFAULT NULL,
  `data` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for matriculas
-- ----------------------------
DROP TABLE IF EXISTS `matriculas`;
CREATE TABLE `matriculas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_turma` int(11) NULL DEFAULT NULL,
  `id_aluno` int(11) NULL DEFAULT NULL,
  `numero_parcelas` int(11) NULL DEFAULT NULL,
  `valor_parcela` float(9, 2) NULL DEFAULT NULL,
  `data_vencimento` date NULL DEFAULT NULL,
  `data_matricula` date NULL DEFAULT NULL,
  `situacao` int(11) NULL DEFAULT NULL,
  `responsavel_financeiro` int(11) NULL DEFAULT NULL,
  `id_empresa_financeiro` int(11) NULL DEFAULT NULL,
  `porcentagem_empresa` float(9, 2) NULL DEFAULT NULL,
  `responsavel_pedagogico` int(11) NULL DEFAULT NULL,
  `id_empresa_pedagogico` int(11) NULL DEFAULT NULL,
  `id_situacao_aluno_turma` int(11) NULL DEFAULT NULL,
  `id_motivo_desistencia` int(11) NULL DEFAULT NULL,
  `data_desistencia` date NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 639 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for motivos_desistencia
-- ----------------------------
DROP TABLE IF EXISTS `motivos_desistencia`;
CREATE TABLE `motivos_desistencia`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `motivo` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for motivos_parcela
-- ----------------------------
DROP TABLE IF EXISTS `motivos_parcela`;
CREATE TABLE `motivos_parcela`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `motivo` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for movimentos_caixa
-- ----------------------------
DROP TABLE IF EXISTS `movimentos_caixa`;
CREATE TABLE `movimentos_caixa`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_caixa` int(11) NULL DEFAULT NULL,
  `id_categoria` int(11) NULL DEFAULT NULL,
  `id_conta_pagar` int(11) NULL DEFAULT NULL,
  `numero` int(11) NULL DEFAULT NULL,
  `data` date NULL DEFAULT NULL,
  `hora` time(0) NULL DEFAULT NULL,
  `total` double(19, 2) NULL DEFAULT NULL,
  `desconto` double(19, 2) NULL DEFAULT NULL,
  `descricao` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_aluno` int(11) NULL DEFAULT NULL,
  `tipo` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_forma_pagamento` int(11) NULL DEFAULT NULL,
  `estorno` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for natureza_conta
-- ----------------------------
DROP TABLE IF EXISTS `natureza_conta`;
CREATE TABLE `natureza_conta`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `natureza` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for nome_provas
-- ----------------------------
DROP TABLE IF EXISTS `nome_provas`;
CREATE TABLE `nome_provas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_idioma` int(11) NULL DEFAULT NULL,
  `nome` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 44 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for nomes_produtos
-- ----------------------------
DROP TABLE IF EXISTS `nomes_produtos`;
CREATE TABLE `nomes_produtos`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_material` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nome_pacote_horas` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `horas_semanais` float(9, 1) NULL DEFAULT NULL,
  `horas_estagio` float(9, 1) NULL DEFAULT NULL,
  `numero_aulas` int(11) NULL DEFAULT NULL,
  `programacao` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `programacao_utilizada` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 98 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for notas_provas
-- ----------------------------
DROP TABLE IF EXISTS `notas_provas`;
CREATE TABLE `notas_provas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_prova_turma` int(11) NULL DEFAULT NULL,
  `id_turma` int(11) NULL DEFAULT NULL,
  `id_aluno_turma` int(11) NULL DEFAULT NULL,
  `id_aluno` int(11) NULL DEFAULT NULL,
  `nota` float(9, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 37 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for observacoes_professores
-- ----------------------------
DROP TABLE IF EXISTS `observacoes_professores`;
CREATE TABLE `observacoes_professores`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_turma` int(11) NULL DEFAULT NULL,
  `id_aluno` int(11) NULL DEFAULT NULL,
  `id_colega` int(11) NULL DEFAULT NULL,
  `data` datetime(0) NULL DEFAULT NULL,
  `observacao` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for opcoes_cobranca
-- ----------------------------
DROP TABLE IF EXISTS `opcoes_cobranca`;
CREATE TABLE `opcoes_cobranca`  (
  `id` int(11) NOT NULL,
  `tipo_acao` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `iniciar_sequencia` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `numero_inicial` int(11) NULL DEFAULT NULL,
  `quantidade_maxima` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `quantidade` int(11) NULL DEFAULT NULL,
  `adicionar_taxa` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `taxa` float(9, 2) NULL DEFAULT NULL,
  `discriminar_observacao` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `imprimir_endereco` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `instrucoes_atraso` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `multa` float(9, 6) NULL DEFAULT NULL,
  `instrucoes_mora` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `juros` float(9, 6) NULL DEFAULT NULL,
  `campo_livre1` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `campo_livre2` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mensagem_complementar` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for origem_aluno
-- ----------------------------
DROP TABLE IF EXISTS `origem_aluno`;
CREATE TABLE `origem_aluno`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `origem` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for parcelas
-- ----------------------------
DROP TABLE IF EXISTS `parcelas`;
CREATE TABLE `parcelas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_matricula` int(11) NULL DEFAULT NULL,
  `id_turma` int(11) NULL DEFAULT NULL,
  `id_idioma` int(11) NULL DEFAULT NULL,
  `id_aluno` int(11) NOT NULL,
  `id_empresa` int(11) NULL DEFAULT NULL,
  `pagante` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `parcela` int(11) NULL DEFAULT NULL,
  `id_motivo` int(11) NULL DEFAULT NULL,
  `data_pagamento` date NULL DEFAULT NULL,
  `data_vencimento` date NULL DEFAULT NULL,
  `valor` float(9, 2) NULL DEFAULT NULL,
  `juros` float(9, 2) NULL DEFAULT NULL,
  `multa` float(9, 2) NULL DEFAULT NULL,
  `acrescimo` float(9, 2) NULL DEFAULT NULL,
  `desconto` float(9, 2) NULL DEFAULT NULL,
  `total` float(9, 2) NULL DEFAULT NULL,
  `pago` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_forma_pagamento` int(11) NULL DEFAULT NULL,
  `cancelada` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `renegociada` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lote_remessa` int(11) NULL DEFAULT NULL,
  `arquivo_remessa` int(11) NULL DEFAULT NULL,
  `data_emissao_boleto` date NULL DEFAULT NULL,
  `numero_boleto` int(11) NULL DEFAULT NULL,
  `nosso_numero_boleto` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `situacao_cnab` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `boleto` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `impresso` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `enviado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `observacoes` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4731 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for perfis
-- ----------------------------
DROP TABLE IF EXISTS `perfis`;
CREATE TABLE `perfis`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `perfil` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `listar_como_gerente` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for permissoes
-- ----------------------------
DROP TABLE IF EXISTS `permissoes`;
CREATE TABLE `permissoes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `ordem` int(11) NULL DEFAULT NULL,
  `tela` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `opcoes` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `p` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `a` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `i` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `e` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `c` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ai` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `imp` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  INDEX `id_usuario`(`id_usuario`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2210 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for permissoes_perfil
-- ----------------------------
DROP TABLE IF EXISTS `permissoes_perfil`;
CREATE TABLE `permissoes_perfil`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) NOT NULL,
  `ordem` int(11) NULL DEFAULT NULL,
  `tela` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `opcoes` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `p` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `a` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `i` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `e` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `c` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ai` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `imp` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  INDEX `id_perfil`(`id_perfil`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 403 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for programa_aulas
-- ----------------------------
DROP TABLE IF EXISTS `programa_aulas`;
CREATE TABLE `programa_aulas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nome_produto` int(11) NULL DEFAULT NULL,
  `aula` int(11) NULL DEFAULT NULL,
  `conteudo` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4985 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for provas_turmas
-- ----------------------------
DROP TABLE IF EXISTS `provas_turmas`;
CREATE TABLE `provas_turmas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_turma` int(11) NULL DEFAULT NULL,
  `id_sistema_nota` int(11) NULL DEFAULT NULL,
  `id_nome_prova` int(11) NULL DEFAULT NULL,
  `prova` char(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 112 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for responsaveis_caixa
-- ----------------------------
DROP TABLE IF EXISTS `responsaveis_caixa`;
CREATE TABLE `responsaveis_caixa`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_caixa` int(11) NULL DEFAULT NULL,
  `id_usuario` int(11) NULL DEFAULT NULL,
  `tipo` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for responsaveis_contas_pagar
-- ----------------------------
DROP TABLE IF EXISTS `responsaveis_contas_pagar`;
CREATE TABLE `responsaveis_contas_pagar`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_conta_pagar` int(11) NULL DEFAULT NULL,
  `id_unidade` int(11) NULL DEFAULT NULL,
  `porcentagem` float(3, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for responsavel_financeiro
-- ----------------------------
DROP TABLE IF EXISTS `responsavel_financeiro`;
CREATE TABLE `responsavel_financeiro`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `responsavel` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for responsavel_pedagogico
-- ----------------------------
DROP TABLE IF EXISTS `responsavel_pedagogico`;
CREATE TABLE `responsavel_pedagogico`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `responsavel` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for resultado_retornos
-- ----------------------------
DROP TABLE IF EXISTS `resultado_retornos`;
CREATE TABLE `resultado_retornos`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime(0) NULL DEFAULT NULL,
  `arquivo` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for sistema_notas
-- ----------------------------
DROP TABLE IF EXISTS `sistema_notas`;
CREATE TABLE `sistema_notas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_idioma` int(11) NULL DEFAULT NULL,
  `id_nome_prova_oral` int(11) NULL DEFAULT NULL,
  `id_nome_prova1` int(11) NULL DEFAULT NULL,
  `id_nome_prova2` int(11) NULL DEFAULT NULL,
  `id_nome_prova3` int(11) NULL DEFAULT NULL,
  `id_nome_prova4` int(11) NULL DEFAULT NULL,
  `id_nome_prova5` int(11) NULL DEFAULT NULL,
  `id_nome_prova6` int(11) NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for situacao_aluno
-- ----------------------------
DROP TABLE IF EXISTS `situacao_aluno`;
CREATE TABLE `situacao_aluno`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `situacao` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for situacao_aluno_turma
-- ----------------------------
DROP TABLE IF EXISTS `situacao_aluno_turma`;
CREATE TABLE `situacao_aluno_turma`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `situacao` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for situacao_aulas
-- ----------------------------
DROP TABLE IF EXISTS `situacao_aulas`;
CREATE TABLE `situacao_aulas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `situacao` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `descricao` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pagar` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reposicao` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `gera_programacao` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `excluir_programacao` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for situacao_turma
-- ----------------------------
DROP TABLE IF EXISTS `situacao_turma`;
CREATE TABLE `situacao_turma`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `situcao` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for textos
-- ----------------------------
DROP TABLE IF EXISTS `textos`;
CREATE TABLE `textos`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `texto` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for transferencias
-- ----------------------------
DROP TABLE IF EXISTS `transferencias`;
CREATE TABLE `transferencias`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_aluno` int(11) NULL DEFAULT NULL,
  `id_matricula` int(11) NULL DEFAULT NULL,
  `id_tuma_origem` int(11) NULL DEFAULT NULL,
  `id_turma_destino` int(11) NULL DEFAULT NULL,
  `data` date NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for turmas
-- ----------------------------
DROP TABLE IF EXISTS `turmas`;
CREATE TABLE `turmas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_unidade` int(11) NULL DEFAULT NULL,
  `id_idioma` int(11) NULL DEFAULT NULL,
  `id_produto` int(11) NULL DEFAULT NULL,
  `id_sistema_notas` int(11) NULL DEFAULT NULL,
  `id_colega` int(11) NULL DEFAULT NULL,
  `id_valor_hora_aula` int(11) NULL DEFAULT NULL,
  `segunda` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `terca` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `quarta` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `quinta` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sexta` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sabado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `domingo` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_segunda` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_segunda` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_terca` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_terca` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_quarta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_quarta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_quinta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_quinta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_sexta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_sexta` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_sabado` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_sabado` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_inicio_domingo` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora_termino_domingo` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_inicio` date NULL DEFAULT NULL,
  `data_termino` date NULL DEFAULT NULL,
  `id_situacao_turma` int(11) NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 342 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for unidades
-- ----------------------------
DROP TABLE IF EXISTS `unidades`;
CREATE TABLE `unidades`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chave` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cnpj` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rua` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `numero` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bairro` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `complemento` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estado` int(11) NULL DEFAULT NULL,
  `cidade` int(11) NULL DEFAULT NULL,
  `cep` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefone1` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefone2` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `valor_hora_aula_help` float(9, 2) NULL DEFAULT NULL,
  `id_gerente` int(11) NULL DEFAULT NULL,
  `proximo_boleto` int(11) NULL DEFAULT NULL,
  `numero_banco` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `carteira` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `especie` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `agencia` int(11) NULL DEFAULT NULL,
  `conta` int(11) NULL DEFAULT NULL,
  `codigo_cliente` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `juros` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `multa` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `razao_social` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nome_fantasia` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `local_pag_antes_vencto` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `local_pag_depois_vencto` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `numero_sequencial` int(11) NULL DEFAULT NULL,
  `impressao_bolelto` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `desconto_ate_vencimento` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `incluir_mora_multa` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `informar_descontos_adicionais` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `protestar_atrasados` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dias_para_protestar` int(11) NULL DEFAULT NULL,
  `beneficiario` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `boleto_posicao_inicial_leitura` int(11) NULL DEFAULT NULL,
  `boleto_numero_caracteres` int(11) NULL DEFAULT NULL,
  `data_pag_posicao_inicial_leitura` int(11) NULL DEFAULT NULL,
  `data_pag_numero_caracteres` int(11) NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for unidades_contas_pagar
-- ----------------------------
DROP TABLE IF EXISTS `unidades_contas_pagar`;
CREATE TABLE `unidades_contas_pagar`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_conta_pagar` int(11) NULL DEFAULT NULL,
  `id_unidade` int(11) NULL DEFAULT NULL,
  `porcentagem` float(9, 2) NULL DEFAULT NULL,
  `valor` double(19, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) NULL DEFAULT NULL,
  `id_colega` int(11) NULL DEFAULT NULL,
  `imagem` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nome` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `login` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `senha` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 56 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for valores_hora_aula
-- ----------------------------
DROP TABLE IF EXISTS `valores_hora_aula`;
CREATE TABLE `valores_hora_aula`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_tipo` int(11) NULL DEFAULT NULL,
  `nome` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `valor` float(9, 2) NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `utilizado` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data_criacao` datetime(0) NULL DEFAULT NULL,
  `criado_por` int(11) NULL DEFAULT NULL,
  `data_alteracao` datetime(0) NULL DEFAULT NULL,
  `alterado_por` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- View structure for v_matriculas
-- ----------------------------
DROP VIEW IF EXISTS `v_matriculas`;
CREATE VIEW `v_matriculas` AS SELECT
matriculas.id,
matriculas.id_turma,
turmas.id_unidade,
matriculas.id_aluno,
matriculas.numero_parcelas,
matriculas.valor_parcela,
matriculas.data_vencimento,
matriculas.data_matricula,
matriculas.situacao,
matriculas.responsavel_financeiro,
matriculas.id_empresa_financeiro,
matriculas.porcentagem_empresa,
matriculas.responsavel_pedagogico,
matriculas.id_empresa_pedagogico,
matriculas.id_situacao_aluno_turma,
matriculas.id_motivo_desistencia,
matriculas.data_desistencia,
matriculas.`status`,
matriculas.data_criacao,
matriculas.criado_por,
matriculas.data_alteracao,
matriculas.alterado_por
FROM
matriculas
inner join
turmas
ON
matriculas.id_turma = turmas.id ;

-- ----------------------------
-- View structure for v_nomes_provas
-- ----------------------------
DROP VIEW IF EXISTS `v_nomes_provas`;
CREATE VIEW `v_nomes_provas` AS select `nome_provas`.`id` AS `id`,`nome_provas`.`id_idioma` AS `id_idioma`,`idiomas`.`idioma` AS `idioma`,`nome_provas`.`nome` AS `nome`,`nome_provas`.`status` AS `status`,`nome_provas`.`utilizado` AS `utilizado`,`nome_provas`.`data_criacao` AS `data_criacao`,`nome_provas`.`criado_por` AS `criado_por`,`nome_provas`.`data_alteracao` AS `data_alteracao`,`nome_provas`.`alterado_por` AS `alterado_por` from (`nome_provas` join `idiomas` on((`nome_provas`.`id_idioma` = `idiomas`.`id`))) ;

-- ----------------------------
-- View structure for v_parcelas
-- ----------------------------
DROP VIEW IF EXISTS `v_parcelas`;
CREATE VIEW `v_parcelas` AS SELECT
parcelas.id AS id,
parcelas.id_matricula AS id_matricula,
parcelas.id_turma AS id_turma,
parcelas.id_idioma AS id_idioma,
parcelas.id_aluno AS id_aluno,
alunos.nome AS nome,
parcelas.id_empresa AS id_empresa,
parcelas.pagante AS pagante,
parcelas.parcela AS parcela,
parcelas.id_motivo AS id_motivo,
parcelas.data_pagamento AS data_pagamento,
parcelas.data_vencimento AS data_vencimento,
parcelas.valor AS valor,
parcelas.juros AS juros,
parcelas.multa AS multa,
parcelas.acrescimo AS acrescimo,
parcelas.desconto AS desconto,
parcelas.total AS total,
parcelas.pago AS pago,
parcelas.id_forma_pagamento AS id_forma_pagamento,
parcelas.cancelada AS cancelada,
parcelas.observacoes
from (`parcelas` join `alunos` on((`parcelas`.`id_aluno` = `alunos`.`id`))) ;

SET FOREIGN_KEY_CHECKS = 1;
