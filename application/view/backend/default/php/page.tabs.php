<ul class="nav nav-tabs" role="tablist">
	<?php if ($this->_user->hasPermission("page.settings")): ?><li<?=ACTION == "settings" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>page/settings">
		<span class="main-row-icon fa fa-cog"></span> <?=$this->_lang("page", "settings.moduleName")?>
	</a></li><?php endif; ?>
	
	<?php if ($this->_user->hasPermission("page.list")): ?><li<?=ACTION == "list" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>page/list">
		<span class="main-row-icon fa fa-list"></span> <?=$this->_lang("page", "list.moduleName")?>
	</a></li><?php endif; ?>
	
	<?php if ($this->_user->hasPermission("page.add")): ?><li<?=ACTION == "add" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>page/add">
		<span class="main-row-icon fa fa-plus"></span> <?=$this->_lang("page", "add.moduleName")?>
	</a></li><?php endif; ?>
</ul><br>
