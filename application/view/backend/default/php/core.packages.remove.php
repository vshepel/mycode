<?php include "core.packages.tabs.php"; ?>

<form method="post">
	<input type="hidden" name="remove[name]" value="<?=$tags["name"]?>">
	<div class="alert alert-info">
		<h4><?=$this->_flang("core", "packages.remove.sure")?></h4>
		<p><?=$this->_flang("core", "packages.remove.sureFull")?></p>
		
		<p>
			<input class="form-control" type="checkbox" name="remove_links" checked>
			<?=$this->_flang("core", "packages.remove.links")?>
		</p>

		<p>
			<button class="btn btn-danger" type="submit"><?=$this->_lang("core", "options.yes")?></button>
			<a href="<?=ADMIN_PATH?>core/packages" class="btn btn-success"><?=$this->_lang("core", "options.no")?></a>
		</p>
	</div>
</form>
