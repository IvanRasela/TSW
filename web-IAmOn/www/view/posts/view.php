<?php
//file: view/posts/view.php
require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$post = $view->getVariable("post");
$switch = $view->getVariable("switchs");
$currentuser = $view->getVariable("currentusername");
$newcomment = $view->getVariable("comment");
$errors = $view->getVariable("errors");

$view->setVariable("title", "View Switch");

?><h1>("Switch").": ".htmlentities($switch->getswitchsName())</h1>
<em><?= sprintf("by %s"),$switch->getAliasUser()->getAlias())</em>
<p>
	<?= htmlentities($switch->getDescriptionswitchs()) ?>
</p>

