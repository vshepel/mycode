<?php include "user.tabs.php"; ?>

<form method="post">
	<input type="hidden" name="remove[id]" value="<?=$tags["id"]?>">
	<div class="alert alert-info">
		<h4><?=$this->_flang("user", "groups.remove.title")?></h4>
		<p><?=$this->_flang("user", "groups.remove.description")?></p>

		<p>
			<button class="btn btn-danger" type="submit"><?=$this->_lang("core", "options.yes")?></button>
			<a href="<?=ADMIN_PATH?>user/groups" class="btn btn-success"><?=$this->_lang("core", "options.no")?></a>
		</p>
	</div>
</form>
