CREATE DATABASE IF NOT EXISTS portal_db;
USE portal_db;

-- Criar tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    departamento VARCHAR(100) NOT NULL,
    mesas TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    admin TINYINT(1) DEFAULT 0
);

-- Criar tabela de logs
CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome_usuario VARCHAR(255) NOT NULL,
    acao VARCHAR(255) NOT NULL,
    processos VARCHAR(255) DEFAULT NULL,
    caminho TEXT DEFAULT NULL,
    data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
