<?php
$host = "localhost";
$user = "root";
$pass = "root"; // Doplň "root", ak používaš MAMP, inak nechaj prázdne pre XAMPP

// 1. Pripojenie k databázovému serveru (bez výberu databázy, keďže ju ideme vytvoriť)
$conn = mysqli_connect($host, $user, $pass);

// Kontrola pripojenia
if (!$conn) {
    die("Chyba pripojenia k MySQL: " . mysqli_connect_error());
}

// 2. Vloženie tvojho SQL kódu do premennej
$sql = <<<SQL
CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE todo_app;

DROP TABLE IF EXISTS tasks;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
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

INSERT INTO users (username, password) VALUES
('Admin', 'admin123'),
('TestUser', 'test456');

INSERT INTO tasks (user_id, title, description, status) VALUES
(1, 'Dokoncit databazu', 'Vytvorit tabulky a testovacie data', 'pending'),
(1, 'Pripravit CRUD', 'Zatial len plan', 'pending'),
(2, 'Otestovat ulohy', 'Skontrolovat ci sa data ulozili', 'done');
SQL;

// 3. Spustenie viacerých SQL príkazov naraz pomocou multi_query
if (mysqli_multi_query($conn, $sql)) {
    do {
        // Musíme spracovať/uvoľniť výsledky každého jedného dotazu z bloku
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_more_results($conn) && mysqli_next_result($conn));
    
    echo "<h3>✅ Databáza, tabuľky a testovacie dáta boli úspešne vytvorené!</h3>";
} else {
    echo "<h3>❌ Chyba pri spúšťaní SQL: " . mysqli_error($conn) . "</h3>";
}

// Zatvorenie pripojenia
mysqli_close($conn);
?>