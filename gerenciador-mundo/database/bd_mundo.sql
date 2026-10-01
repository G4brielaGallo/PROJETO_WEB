-- DROP DATABASE IF EXISTS bd_mundo;
CREATE DATABASE bd_mundo;
USE bd_mundo;

CREATE TABLE Governantes(
	pk_governante INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(90) NOT NULL,
    partido_politico VARCHAR(90) NOT NULL,
    dt_nascimento DATE NOT NULL,
    idade TINYINT NOT NULL,
    dt_inicio_mandato DATE NOT NULL,
    dt_fim_mandato DATE NOT NULL
);

CREATE TABLE Continentes(
	pk_continente INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(90) NOT NULL UNIQUE,
    populacao BIGINT NOT NULL,
    area DECIMAL(15,2) NOT NULL,
    total_paises INT NOT NULL
);

CREATE TABLE Paises(
	pk_pais INT PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(90) NOT NULL UNIQUE,
    populacao BIGINT NOT NULL,
    area DECIMAL(15,2) NOT NULL,
    idioma VARCHAR(45) NOT NULL,
    clima VARCHAR(90) NOT NULL,
    regime_politico VARCHAR(90) NOT NULL,
    moeda VARCHAR(45) NOT NULL,
    fk_governante INT NOT NULL,
    fk_continente INT NOT NULL,
    FOREIGN KEY (fk_governante) REFERENCES Governantes(pk_governante),
    FOREIGN KEY (fk_continente) REFERENCES Continentes(pk_continente)
);

CREATE TABLE Cidades(
	pk_cidade INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(90) NOT NULL,
    populacao INT NOT NULL,
    area INT NOT NULL,
    clima VARCHAR(90) NOT NULL,
    dt_fundacao DATE,
    fk_governante INT NOT NULL,
    fk_pais INT NOT NULL,
    FOREIGN KEY (fk_governante) REFERENCES Governantes(pk_governante),
    FOREIGN KEY (fk_pais) REFERENCES Paises(pk_pais)
);

CREATE TABLE Usuarios(
	pk_username VARCHAR(30) PRIMARY KEY,
    senha VARCHAR(128) NOT NULL,
    nome VARCHAR(80) NOT NULL,
    status CHAR(1) NOT NULL,
    tipo CHAR(1) NOT NULL,
    qtd_acesso INT NOT NULL,
    tentativas_login INT NOT NULL DEFAULT 0,
    primeiro_acesso CHAR(1) NOT NULL DEFAULT 'S',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE Log_Acesso_Usuario(
	pk_log_acesso_usuario INT PRIMARY KEY AUTO_INCREMENT,
    dt_acesso DATE NOT NULL,
    hr_acesso TIME NOT NULL,
    descricao VARCHAR(200),
    fk_username VARCHAR(30) NOT NULL
);

INSERT INTO Continentes
(nome, populacao, area, total_paises)
VALUES ("Ásia", 4700000000, 44000000, 54),
("África", 1500000000, 30000000, 54),
("América", 1050000000, 42000000, 35),
("Antártida", 4000, 14000000, 0),
("Europa", 745000000, 10000000, 44),
("Oceania", 45000000, 8500000, 14);

INSERT INTO Governantes
(nome, partido_politico, dt_nascimento, idade, dt_inicio_mandato, dt_fim_mandato)
VALUES ("Donald John Trump", "Partido Republicano", '1946-06-14', 80, '2017-01-20', '2021-01-20'),
("Donald John Trump", "Partido Republicano", '1946-06-14', 80, '2025-01-20', '2029-01-20'),
("Nelson Rolihlahla Mandela", "Congresso Nacional Africano", '1918-07-18', 107, '1994-05-10', '1999-06-14'),
("Luiz Inácio Lula da Silva", "Partido dos Trabalhadores", '1945-10-27', 81, '2003-01-01', '2007-01-01'),
("Luiz Inácio Lula da Silva", "Partido dos Trabalhadores", '1945-10-27', 81, '2007-01-01', '2011-01-01'),
("Luiz Inácio Lula da Silva", "Partido dos Trabalhadores", '1945-10-27', 81, '2023-01-01', '2027-01-01'),
('Shigeru Ishiba', 'Partido Liberal Democrata', '1957-02-04', 69, '2024-10-01', '2028-10-01'),
('Fumio Kishida', 'Partido Liberal Democrata', '1957-07-29', 68, '2021-10-04', '2024-10-01'),
('Olaf Scholz', 'Partido Social-Democrata da Alemanha', '1958-06-14', 68, '2021-12-08', '2025-05-06'),
('Friedrich Merz', 'União Democrata Cristã', '1955-11-11', 71, '2025-05-06', '2029-05-06');

INSERT INTO Paises
(nome, populacao, area, idioma, clima, regime_politico, moeda, fk_governante, fk_continente)
VALUES
('Brasil', 203000000, 8515767.00, 'Português', 'Tropical', 'República Presidencialista', 'Real', 6, 3),
('Estados Unidos', 340000000, 9833520.00, 'Inglês', 'Temperado', 'República Presidencialista', 'Dólar Americano', 2, 3),
('África do Sul', 62000000, 1221037.00, 'Zulu e outros', 'Subtropical', 'República Parlamentarista', 'Rand', 3, 2),
('Japão', 124000000, 377975.00, 'Japonês', 'Temperado', 'Monarquia Constitucional', 'Iene', 7, 1),
('Alemanha', 84000000, 357588.00, 'Alemão', 'Temperado', 'República Parlamentarista', 'Euro', 10, 5);

INSERT INTO Cidades
(nome, populacao, area, clima, dt_fundacao, fk_governante, fk_pais)
VALUES
('São Paulo', 11450000, 1521, 'Tropical', '1554-01-25', 6, 1),
('Rio de Janeiro', 6211000, 1200, 'Tropical', '1565-03-01', 6, 1),
('Brasília', 3094000, 5760, 'Tropical de Altitude', '1960-04-21', 6, 1),
('Nova York', 8800000, 783, 'Temperado', '1624-01-01', 2, 2),
('Los Angeles', 3900000, 1302, 'Mediterrâneo', '1781-09-04', 2, 2),
('Pretória', 820000, 687, 'Subtropical', '1855-11-16', 3, 3),
('Cidade do Cabo', 4900000, 2461, 'Mediterrâneo', '1652-04-06', 3, 3),
('Tóquio', 14000000, 2194, 'Temperado', '1603-01-01', 7, 4),
('Osaka', 2750000, 225, 'Temperado', '1889-04-01', 7, 4),
('Berlim', 3800000, 892, 'Temperado', '1237-01-01', 10, 5),
('Munique', 1500000, 310, 'Temperado', '1158-06-14', 10, 5);

INSERT INTO Usuarios
(pk_username, senha, nome, status, tipo, qtd_acesso)
VALUES
("adm_tst", "Etec123", "Sou um Administrador de Teste", "A", "A", 0),
("usuario_tst", "Etec123", "Sou um Usuário de Teste", "A", "U", 0);

SET GLOBAL event_scheduler = ON;

CREATE EVENT atualiza_idade_governantes
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_DATE + INTERVAL 1 DAY
DO
UPDATE Governantes
SET idade = idade + 1
WHERE MONTH(dt_nascimento) = MONTH(CURDATE())
  AND DAY(dt_nascimento) = DAY(CURDATE());
  
-- TRIGGER (log)
DELIMITER //

CREATE TRIGGER trg_log_acesso_usuario
AFTER UPDATE ON Usuarios
FOR EACH ROW
BEGIN
	IF OLD.qtd_acesso <> NEW.qtd_acesso THEN
		INSERT INTO Log_Acesso_Usuario
        (dt_acesso, hr_acesso, fk_username)
        VALUES
        (CURDATE(), CURTIME(), NEW.pk_username);
	END IF;
END //

DELIMITER ;

-- TESTE trg_log_acesso_usuario
/*
-- 1. Verificar estado atual
SELECT * FROM Usuarios WHERE pk_username = 'adm_tst';
SELECT * FROM Log_Acesso_Usuario;

-- 2. Atualizar qtd_acesso (isso vai disparar a trigger)
UPDATE Usuarios SET qtd_acesso = qtd_acesso + 1 WHERE pk_username = 'adm_tst';

-- 3. Verificar se o log foi criado
SELECT * FROM Log_Acesso_Usuario;
*/