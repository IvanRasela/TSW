<?php
//file: view/posts/index.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$switchs = $view->getVariable("switchs");
$currentuser = $view->getVariable("currentusername");

$view->setVariable("title", "Posts");

?><h1>Switches</h1>

<table border="1">
	<tr>
		<th>Switch Name</th><th>Alias User</th><th>Public_UUID</th><th>Acciones</th>
	</tr>

	<?php foreach ($switchs as $switches): ?>
		<tr>
			<td>
            <a href="index.php?controller=switchs&amp;action=view&amp;id=<?= $switches->getSwitchsName() ?>"><?= htmlentities($switches->getSwitchsName()) ?></a>
			</td>
			<td>
				<?= $switches->getAliasUser()->getAlias() ?>
			</td>
            <td>
				<?= $switches->getPublic_UUID()?>
			</td>
			<td>
				<?php
				//show actions ONLY for the author of the post (if logged)


				if (isset($currentuser) && $currentuser == $switches->getAliasUser()->getAlias()): ?>

				<?php
				// 'Delete Button': show it as a link, but do POST in order to preserve
				// the good semantic of HTTP
				?>
				<form
				method="POST"
				action="index.php?controller=switchs&amp;action=delete"
				id="delete_switchs_<?= $switches->getSwitchsName(); ?>"
				style="display: inline"
				>

				<input type="hidden" name="id" value="<?= $switches->getSwitchsName() ?>">

				<a href="#" 
				onclick="
				if (confirm('are you sure?')) {
					document.getElementById('delete_switchs_<?= $switches->getSwitchsName() ?>').submit()
				}"
				>Delete</a>

			</form>

			&nbsp;

			<?php
			// 'Edit Button'
			?>
			<a href="index.php?controller=switchs&amp;action=edit&amp;id=<?= $switches->getSwitchsName() ?>">Edit</a>

		<?php endif; ?>

	</td>
</tr>
<?php endforeach; ?>

</table>
<?php if (isset($currentuser)): ?>
	<a href="index.php?controller=switchs&amp;action=add">Create post</a>
<?php endif; ?>