<?php
$host = "localhost";
$user = "root";
$pass = "root"; 
 
$conn = mysqli_connect($host, $user, $pass);
 
if (!$conn) {
    die("Chyba pripojenia: " . mysqli_connect_error());
}
 
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
 
mysqli_select_db($conn, "todo_app");
 
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)");
 
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('pending', 'done') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");
 
echo "Hotovo!";
echo "<br><a href='index.php'>Pokracovat na prihlasenie</a>";
 
mysqli_close($conn);
?>