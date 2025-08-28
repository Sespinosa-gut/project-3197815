<?php
$host = "localhost";
$user = "root"; // tu usuario de MySQL
$pass = "";     // tu contraseña
$db   = "swap_it";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Error en la conexión: " . $conn->connect_error);
}

?>