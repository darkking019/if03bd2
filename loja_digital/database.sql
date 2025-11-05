-- Script para criar o banco de dados LOJA_DIGITAL
-- Execute este script no phpMyAdmin ou MySQL

CREATE DATABASE IF NOT EXISTS LOJA_DIGITAL;
USE LOJA_DIGITAL;

-- Tabela de Usuários
CREATE TABLE IF NOT EXISTS Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'cliente') DEFAULT 'cliente',
    ativo BOOLEAN DEFAULT TRUE,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Categorias
CREATE TABLE IF NOT EXISTS Categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Tabela de Arquivos Digitais (Produtos)
CREATE TABLE IF NOT EXISTS Arquivo_Digital (
    id_arquivo INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    id_categoria INT,
    arquivo_url VARCHAR(255),
    imagem_url VARCHAR(255),
    total_vendas INT DEFAULT 0,
    ativo BOOLEAN DEFAULT TRUE,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria)
);

-- Tabela de Compras
CREATE TABLE IF NOT EXISTS Compra (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    data_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valor_total DECIMAL(10, 2) NOT NULL,
    status ENUM('pendente', 'aprovado', 'cancelado') DEFAULT 'pendente',
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario)
);

-- Tabela de Itens da Compra
CREATE TABLE IF NOT EXISTS Item_Compra (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    id_compra INT,
    id_arquivo INT,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    quantidade INT DEFAULT 1,
    FOREIGN KEY (id_compra) REFERENCES Compra(id_compra),
    FOREIGN KEY (id_arquivo) REFERENCES Arquivo_Digital(id_arquivo)
);

-- Inserir dados de exemplo

-- Categorias
INSERT INTO Categoria (nome, descricao, ativo) VALUES
('E-books', 'Livros digitais em diversos formatos', TRUE),
('Cursos', 'Cursos online em vídeo', TRUE),
('Músicas', 'Arquivos de áudio e músicas', TRUE),
('Software', 'Programas e aplicativos', TRUE);

-- Usuários
INSERT INTO Usuario (nome, email, senha, tipo, ativo) VALUES
('Admin Sistema', 'admin@loja.com', MD5('admin123'), 'admin', TRUE),
('João Silva', 'joao@email.com', MD5('123456'), 'cliente', TRUE),
('Maria Santos', 'maria@email.com', MD5('123456'), 'cliente', TRUE),
('Pedro Costa', 'pedro@email.com', MD5('123456'), 'cliente', FALSE);

-- Arquivos Digitais (Produtos)
INSERT INTO Arquivo_Digital (titulo, descricao, preco, id_categoria, arquivo_url, imagem_url, total_vendas, ativo) VALUES
('PHP Completo', 'Curso completo de PHP do básico ao avançado', 99.90, 2, 'cursos/php.zip', 'img/php.jpg', 150, TRUE),
('JavaScript Moderno', 'Aprenda JavaScript ES6+', 89.90, 2, 'cursos/js.zip', 'img/js.jpg', 120, TRUE),
('E-book Python', 'Guia completo de Python para iniciantes', 49.90, 1, 'ebooks/python.pdf', 'img/python.jpg', 200, TRUE),
('Photoshop 2024', 'Software de edição de imagens', 299.90, 4, 'software/ps.zip', 'img/ps.jpg', 80, TRUE),
('Música Relaxante Vol.1', 'Coletânea de músicas para relaxar', 19.90, 3, 'musicas/relax1.zip', 'img/music1.jpg', 95, TRUE),
('Bootstrap 5 na Prática', 'Domine o framework Bootstrap', 79.90, 2, 'cursos/bootstrap.zip', 'img/bootstrap.jpg', 110, TRUE),
('E-book MySQL', 'Banco de dados MySQL do zero', 39.90, 1, 'ebooks/mysql.pdf', 'img/mysql.jpg', 180, TRUE),
('React Avançado', 'Curso avançado de React e Redux', 149.90, 2, 'cursos/react.zip', 'img/react.jpg', 90, TRUE),
('Pacote Office Digital', 'Suite completa de escritório', 199.90, 4, 'software/office.zip', 'img/office.jpg', 75, TRUE),
('Node.js Completo', 'Backend com Node.js e Express', 119.90, 2, 'cursos/node.zip', 'img/node.jpg', 130, TRUE),
('E-book HTML5 e CSS3', 'Desenvolvimento web moderno', 29.90, 1, 'ebooks/html.pdf', 'img/html.jpg', 220, TRUE),
('Trilha Sonora Épica', 'Músicas épicas para seus projetos', 24.90, 3, 'musicas/epic.zip', 'img/epic.jpg', 60, TRUE);

-- Compras
INSERT INTO Compra (id_usuario, valor_total, status, data_compra) VALUES
(2, 149.80, 'aprovado', '2024-11-01 10:30:00'),
(3, 99.90, 'aprovado', '2024-11-02 14:20:00'),
(2, 299.90, 'aprovado', '2024-11-03 09:15:00'),
(3, 189.80, 'pendente', '2024-11-04 16:45:00'),
(2, 79.90, 'aprovado', '2024-11-05 11:00:00');

-- Itens das Compras
INSERT INTO Item_Compra (id_compra, id_arquivo, preco_unitario, quantidade) VALUES
(1, 3, 49.90, 1),
(1, 1, 99.90, 1),
(2, 1, 99.90, 1),
(3, 4, 299.90, 1),
(4, 2, 89.90, 1),
(4, 1, 99.90, 1),
(5, 6, 79.90, 1);
