<?php
//file: view/posts/index.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$switches = $view->getVariable("Switchs");

$currentuser = $view->getVariable("currentusername");


$view->setVariable("title", "Switchs");

?><h1>Mis Switches</h1>
<table border="1">
	<tr>
		<th>Switch Name</th><th>Alias User</th><th>Public_UUID</th><th>Acciones</th>
	</tr>
	<!--Comprobar que switchess es un array y está instanciado-->
	

	<?php foreach ($switches as $switchs):?> 
		<tr>
			<td>
            <a href="index.php?controller=switchs&amp;action=view&amp;Public_UUID=<?= $switchs->getPublic_UUID() ?>"><?= htmlentities($switchs->getSwitchsName()) ?></a>
			</td>
			<td>
				<?= $switchs->getAliasUser()->getAlias() ?>
			</td>
            <td>
				<?= $switchs->getPublic_UUID()?>
			</td>
			<td>
				<?php

				if (isset($currentuser) && $currentuser == $switchs->getAliasUser()->getAlias()):?>
					<?php
					// 'Delete Button': show it as a link, but do POST in order to preserve
					// the good semantic of HTTP
					?>
					<form
					method="POST"
					action="index.php?controller=switchs&amp;action=delete"
					id="delete_switchs_<?= $switchs->getPrivate_UUID(); ?>"
					style="display: inline"
					>

					<input type="hidden" name="Private_UUID" value="<?= $switchs->getPrivate_UUID() ?>">

					<a href="#" 
					onclick="
					if (confirm('are you sure?')) {
						document.getElementById('delete_switchs_<?= $switchs->getPrivate_UUID() ?>').submit()
					}"
					>Delete</a>

			</form>

			&nbsp;

			<?php endif; ?>

			<?php
			// 'Suscribe Button'
			if (isset($currentuser) && $currentuser != $switchs->getAliasUser()->getAlias()):?>	
			
			<form
				method="POST"
				action="index.php?controller=posts&amp;action=suscribe"
				id="suscribe_switch_<?= $switch->getPublic_UUID(); ?>"
				style="display: inline"
				>

				<input type="hidden" name="Public_UUID" value="<?= $switch->getPublic_UUID() ?>">

				<a href="#" 
				onclick="
				if (confirm('are you sure?')) {
						document.getElementById('delete_switchs_<?= $switchs->getPublic_UUID() ?>').submit()
					}"
				>("Suscribe") ?></a>
			</form>	
	
		<?php endif; ?>
	</td>
</tr>
<?php endforeach; ?>


</table>
<?php if (isset($currentuser)): ?>
	<a href="index.php?controller=switchs&amp;action=add">Create switch</a>
<?php endif; ?>