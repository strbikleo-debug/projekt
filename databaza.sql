CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE todo_app;

DROP TABLE IF EXISTS tasks;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('pending', 'done') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (username) VALUES
('Admin'),
('TestUser');

INSERT INTO tasks (user_id, title, description, status) VALUES
(1, 'Dokoncit databazu', 'Vytvorit tabulky a testovacie data', 'pending'),
(1, 'Pripravit CRUD', 'Zatial len plan', 'pending'),
(2, 'Otestovat ulohy', 'Skontrolovat ci sa data ulozili', 'done');