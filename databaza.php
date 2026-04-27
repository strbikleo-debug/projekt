<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db_name = "todo_app";

$conn = mysqli_connect($host, $user, $pass, $db_name);

if (!$conn) {
    die("Spojenie zlyhalo: " . mysqli_connect_error());
}
?>