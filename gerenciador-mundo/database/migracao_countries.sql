-- ============================================================
-- MIGRAÇÃO: REST Countries
-- Use este script se você JÁ possui o banco bd_mundo criado.
-- Para uma instalação nova, basta importar o bd_mundo.sql.
-- (Execute também o migracao_maps.sql, se ainda não executou.)
-- ============================================================
USE bd_mundo;

-- Novos campos vindos da API
ALTER TABLE Paises
    ADD COLUMN codigo_iso CHAR(2) NULL AFTER nome,
    ADD COLUMN capital VARCHAR(100) NULL AFTER codigo_iso,
    ADD COLUMN sub_regiao VARCHAR(100) NULL AFTER capital,
    ADD UNIQUE KEY uq_paises_codigo_iso (codigo_iso);

-- A API não fornece estes dados; países importados ficam com NULL
-- até que um administrador complete o cadastro pela tela de edição.
ALTER TABLE Paises
    MODIFY area DECIMAL(15,2) NULL,
    MODIFY idioma VARCHAR(45) NULL,
    MODIFY clima VARCHAR(90) NULL,
    MODIFY regime_politico VARCHAR(90) NULL,
    MODIFY moeda VARCHAR(45) NULL,
    MODIFY fk_governante INT NULL;

-- Código ISO, capital e sub-região dos países de exemplo
-- (o código ISO é o que impede importar o mesmo país duas vezes)
UPDATE Paises SET codigo_iso='BR', capital='Brasília',        sub_regiao='South America'    WHERE nome='Brasil';
UPDATE Paises SET codigo_iso='US', capital='Washington, D.C.', sub_regiao='Northern America' WHERE nome='Estados Unidos';
UPDATE Paises SET codigo_iso='ZA', capital='Pretória',        sub_regiao='Southern Africa'  WHERE nome='África do Sul';
UPDATE Paises SET codigo_iso='JP', capital='Tóquio',          sub_regiao='Eastern Asia'     WHERE nome='Japão';
UPDATE Paises SET codigo_iso='DE', capital='Berlim',          sub_regiao='Western Europe'   WHERE nome='Alemanha';
