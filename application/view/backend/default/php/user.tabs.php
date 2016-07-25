<ul class="nav nav-tabs" role="tablist">
	<?php if ($this->_user->hasPermission("user.statistics")): ?><li<?=ACTION == "statistics" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>user/statistics">
		<span class="main-row-icon fa fa-dashboard"></span> <?=$this->_lang("user", "statistics.moduleName")?>
	</a></li><?php endif; ?>
	
	<?php if ($this->_user->hasPermission("user.settings")): ?><li<?=ACTION == "settings" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>user/settings">
		<span class="main-row-icon fa fa-cog"></span> <?=$this->_lang("user", "settings.moduleName")?>
	</a></li><?php endif; ?>
	
	<?php if ($this->_user->hasPermission("user.groups")): ?><li<?=ACTION == "groups" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>user/groups">
		<span class="main-row-icon fa fa-users"></span> <?=$this->_lang("user", "groups.moduleName")?>
	</a></li><?php endif; ?>
	
	<?php if ($this->_user->hasPermission("user.list")): ?><li<?=ACTION == "list" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>user/list">
		<span class="main-row-icon fa fa-list"></span> <?=$this->_lang("user", "list.moduleName")?>
	</a></li><?php endif; ?>
	
	<?php if ($this->_user->hasPermission("user.add")): ?><li<?=ACTION == "add" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>user/add">
		<span class="main-row-icon fa fa-plus"></span> <?=$this->_lang("user", "add.moduleName")?>
	</a></li><?php endif; ?>
</ul><br>
