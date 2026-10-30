-- ============================================================
-- MIGRAÇÃO: Google Maps
-- Use este script se você JÁ possui o banco bd_mundo criado.
-- Para uma instalação nova, basta importar o bd_mundo.sql.
-- ============================================================
USE bd_mundo;

ALTER TABLE Cidades
    ADD COLUMN latitude  DECIMAL(10,7) NULL AFTER dt_fundacao,
    ADD COLUMN longitude DECIMAL(10,7) NULL AFTER latitude;

-- Coordenadas das cidades de exemplo
UPDATE Cidades SET latitude = -23.5505, longitude = -46.6333  WHERE nome = 'São Paulo';
UPDATE Cidades SET latitude = -22.9068, longitude = -43.1729  WHERE nome = 'Rio de Janeiro';
UPDATE Cidades SET latitude = -15.7939, longitude = -47.8828  WHERE nome = 'Brasília';
UPDATE Cidades SET latitude =  40.7128, longitude = -74.0060  WHERE nome = 'Nova York';
UPDATE Cidades SET latitude =  34.0522, longitude = -118.2437 WHERE nome = 'Los Angeles';
UPDATE Cidades SET latitude = -25.7479, longitude =  28.2293  WHERE nome = 'Pretória';
UPDATE Cidades SET latitude = -33.9249, longitude =  18.4241  WHERE nome = 'Cidade do Cabo';
UPDATE Cidades SET latitude =  35.6762, longitude = 139.6503  WHERE nome = 'Tóquio';
UPDATE Cidades SET latitude =  34.6937, longitude = 135.5023  WHERE nome = 'Osaka';
UPDATE Cidades SET latitude =  52.5200, longitude =  13.4050  WHERE nome = 'Berlim';
UPDATE Cidades SET latitude =  48.1351, longitude =  11.5820  WHERE nome = 'Munique';
