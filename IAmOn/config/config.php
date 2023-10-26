<?php
// Configuración de la base de datos
$servername = "127.0.0.1";
$username = "admin";
$password = "MuX8qT0T17m5";
$database = "iamon";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
