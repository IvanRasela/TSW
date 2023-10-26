<?php
require_once('../models/UserModel.php');

class UserController {
    private $model;

    public function __construct() {
        require('../config/config.php');
        $this->model = new UserModel($conn);
    }

    public function registerUser() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["alias"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hashear la contraseña
            $email = $_POST["email"];

            if ($this->model->insertUser($username, $password, $email)) {
                // Registro exitoso, redirigir a una página de éxito o a donde desees
                header("Location: Inicio.php");
            } else {
                // Error al registrar, redirigir a una página de error o a donde desees
                header("Location: ValidationException.php");
            }
        }
    }
}

$userController = new UserController();
?>
