<?php
//file: view/posts/index.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$switches = $view->getVariable("switchs");

$currentuser = $view->getVariable("currentUsername");
print_r($currentuser);

$view->setVariable("title", "Switchs");

?><h1>Mis Switches</h1>
<table border="1">
	<tr>
		<th>Switch Name</th><th>Alias User</th><th>Public_UUID</th><th>Acciones</th>
	</tr>
	<!--foreach(array as iteración)-->
	<?php foreach ($switches as $switchs):?> 
		
		
	<?php
	if (isset($currentuser) && $currentuser == $switchs->getAliasUser()->getAlias()): ?>
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
			
				// 'Delete Button': show it as a link, but do POST in order to preserve
				// the good semantic of HTTP
				?>
				<form
				method="POST"
				action="index.php?controller=switchs&amp;action=delete"
				id="delete_switchs_<?= $switchs->getSwitchsName(); ?>"
				style="display: inline"
				>

				<input type="hidden" name="id" value="<?= $switchs->getSwitchsName() ?>">

				<a href="#" 
				onclick="
				if (confirm('are you sure?')) {
					document.getElementById('delete_switchs_<?= $switchs->getSwitchsName() ?>').submit()
				}"
				>Delete</a>

			</form>

			&nbsp;

			<?php
			// 'Edit Button'
			?>
			<a href="index.php?controller=switchs&amp;action=edit&amp;id=<?= $switchs->getSwitchsName() ?>">Edit</a>

		<?php endif; ?>

	</td>
</tr>
<?php endforeach; ?>

</table>
<?php if (isset($currentuser)): ?>
	<a href="index.php?controller=switchs&amp;action=add">Create switch</a>
<?php endif; ?>