Inicio.php
<?php
//file: view/users/login.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$view->setVariable("title", "Login");
$errors = $view->getVariable("errors");
?>


<h1>IAmOn</h1>
            <p>Inicia sesión en tu cuenta</p>
        <?= isset($errors["general"])?$errors["general"]:"" ?>
            <form action="index.php?controller=switch&amp;action=index" method="POST">
                <label for="alias">Usuario:</label>
                <input type="text" id="alias" name="alias" required>

                <label for="passwd">Contraseña:</label>
                <input type="password" id="passwd" name="passwd" required>

                <input type="submit" value="<?= "Login" ?>">
            </form>

            <p>Usuario no registrado?: <a href="index.php?controller=users&amp;action=register">Registrate aquí!</a></p>
            <p>Iniciar como modo invitado <a href="index.php?controller=switch&amp;action=index">Continuar</a></p>
            <?php $view->moveToFragment("css");?>
            <link rel="stylesheet" type="text/css" src="css/style2.css">
            <?php $view->moveToDefaultFragment(); ?>