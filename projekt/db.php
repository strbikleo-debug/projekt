<?php

$host = "localhost";
$user = "root";
$pass = "root"; 
$db   = "todo_app";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Chyba pripojenia: " . mysqli_connect_error());
}
