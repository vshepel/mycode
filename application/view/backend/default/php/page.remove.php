<?php include "page.tabs.php"; ?>

<form method="post">
	<input type="hidden" name="remove[id]" value="<?=$tags["id"]?>">
	<div class="alert alert-info">
		<h4><?=$this->_flang("page", "remove.sure")?></h4>
		<p><?=$this->_flang("page", "remove.sureFull")?></p>

		<p>
			<button class="btn btn-danger" type="submit"><?=$this->_lang("core", "options.yes")?></button>
			<a href="<?=ADMIN_PATH?>page/list" class="btn btn-success"><?=$this->_lang("core", "options.no")?></a>
		</p>
	</div>
</form>
