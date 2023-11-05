Register.php
<?php
//file: view/users/register.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$user = $view->getVariable("usuario");
$view->setVariable("title", "Register");
?>


<h1>Registro en IAmOn</h1>
            <form action="index.php?controller=users&amp;action=register" method="POST">
                <p id="username">Alias: </p> <input type="text" name="alias"
                value="<?= $user->getAlias() ?>">
                <?= isset($errors["alias"])?$errors["alias"]:"" ?><br>

                <p id="password">Contraseña: </p> <input type="password" name="passwd"
                value="">
                <?= isset($errors["passwd"])?$errors["passwd"]:"" ?><br>

                <p id="email">Correo (Si deseas recibir notificaciones): </p> <input type="email" name="email"
                value="">
                <?= isset($errors["email"])?$errors["email"]:"" ?><br>

                <input type="submit" value="Registrarse">

                <p>Ya tienes cuenta?: <a href="index.php?controller=users&amp;action=login">Inicia sesion aquí!</a></p>

