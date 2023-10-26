<?php
require('../controllers/UserController.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userController->registerUser();
}

// Aquí puedes incluir la vista correspondiente si lo necesitas
?>
