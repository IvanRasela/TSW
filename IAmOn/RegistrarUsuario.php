<?php
// Incluir el archivo de configuración y la clase de modelo
require_once('config.php');
require_once('UserModel.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Hashear la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Crear una instancia del modelo
    $userModel = new UserModel($conn);

    // Intentar registrar al usuario en la base de datos
    if ($userModel->insertUser($username, $hashedPassword, $email)) {
        // Registro exitoso, redirigir a una página de éxito o a donde desees
        header("Location: Inicio.php");
    } else {
        // Error al registrar, redirigir a una página de error o a donde desees
        header("Location: ValidationException.php");
    }
}
?>
