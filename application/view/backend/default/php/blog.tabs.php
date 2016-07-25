<ul class="nav nav-tabs" role="tablist">
	<?php if ($this->_user->hasPermission("blog.statistics")): ?><li<?=ACTION == "statistics" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>blog/statistics">
		<span class="main-row-icon fa fa-dashboard"></span> <?=$this->_lang("blog", "statistics.moduleName")?>
	</a></li><?php endif; ?>

	<?php if ($this->_user->hasPermission("blog.settings")): ?><li<?=ACTION == "settings" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>blog/settings">
		<span class="main-row-icon fa fa-cog"></span> <?=$this->_lang("blog", "settings.moduleName")?>
	</a></li><?php endif; ?>

	<?php if ($this->_user->hasPermission("blog.categories")): ?><li<?=ACTION == "categories" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>blog/categories">
		<span class="main-row-icon fa fa-folder"></span> <?=$this->_lang("blog", "categories.moduleName")?>
	</a></li><?php endif; ?>

	<?php if ($this->_user->hasPermission("blog.moderation")): ?><li<?=ACTION == "moderation" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>blog/moderation">
		<span class="main-row-icon fa fa-plus-square"></span> <?=$this->_lang("blog", "moderation.moduleName")?>
	</a></li><?php endif; ?>

	<?php if ($this->_user->hasPermission("blog.posts")): ?><li<?=ACTION == "posts" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>blog/posts">
		<span class="main-row-icon fa fa-list"></span> <?=$this->_lang("blog", "list.moduleName")?>
	</a></li><?php endif; ?>

	<?php if ($this->_user->hasPermission("blog.add")): ?><li<?=ACTION == "add" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>blog/add">
		<span class="main-row-icon fa fa-pencil"></span> <?=$this->_lang("blog", "add.moduleName")?>
	</a></li><?php endif; ?>
</ul><br>
