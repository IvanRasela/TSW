<?php
//file: view/posts/add.php
require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$switchs = $view->getVariable("switchs");
$errors = $view->getVariable("errors");

$view->setVariable("title", "Edit Post");

?><h1>("Create switch")?</h1>
<form action="index.php?controller=Switchs&amp;action=add" method="POST">
	("Nombre"): <input type="text" name="SwitchName"
	value="<?= $post->getSwitchName() ?>">
	<?= isset($errors["title"])($errors["title"]):"" ?><br>

	("Descripcion"): <br>
	<textarea name="content" rows="4" cols="50"><?=
	htmlentities($post->getDescriptionSwicth()) ?></textarea>
	<?= isset($errors["content"])($errors["content"]):"" ?><br>

	<input type="submit" name="submit" value="submit">
</form>