Register.php
<?php
//file: view/users/register.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$user = $view->getVariable("usuario");
$view->setVariable("title", "Register");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/register.css">
    <title>Registro - IAmOn</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>Registro en IAmOn</h1>
        </header>
        <main>
            <form action="index.php?controller=user&amp;action=register" method="POST">
                <p id="username">Alias: </p> <input type="text" name="alias"
                value="<?= $user->getAlias() ?>">
                <?= isset($errors["alias"])?$errors["alias"]:"" ?><br>

                <p id="password">Contraseña: </p> <input type="password" name="password"
                value="">
                <?= isset($errors["passwd"])?$errors["passwd"]:"" ?><br>

                <p id="email">Correo (Si deseas recibir notificaciones): </p> <input type="email" name="email"
                value="">
                <?= isset($errors["email"])?$errors["email"]:"" ?><br>

                <input type="submit" value="Registrarse">

            </form>
        </main>
        <footer>
            <p>¿Ya tienes una cuenta? <a href="../inicio.html">Inicia Sesión</a></p>
        </footer>
    </div>
</body>
</html>
