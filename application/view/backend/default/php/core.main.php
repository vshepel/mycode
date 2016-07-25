<div>
	<div class="pull-left">
		<?=$this->_flang("core", "welcome")?>, <strong><?=$tags["username"]?></strong><br>
		<?=$this->_flang("core", "group")?>: <strong><?=$tags["group-name"]?></strong><br>
		<?=$this->_flang("core", "version")?>: <strong><?=$tags["version"]?></strong>
	</div>
	<div class="pull-right">
		<img alt="HarmonyCMS" src="<?=PATH?>images/harmony-noshadow.png" height="60">
	</div>
	<div class="clearfix"></div>
</div>

<hr>

<div class="row placeholders">
	<?php if ($this->_user->hasPermission("core.statistics")): ?><div class="col-xs-12 col-sm-4 placeholder"><div class="media module-row">
		<a class="media-left" href="<?=$tags["statistics-link"]?>">
			<img class="module-row-icon" src="<?=PATH?>images/icons/dashboard.svg" alt="<?=$this->_lang("core", "statistics.moduleName")?>">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="<?=$tags["statistics-link"]?>"><?=$this->_lang("core", "statistics.moduleName")?></a></h4>
			<?=$this->_lang("core", "statistics.moduleDescription")?>
		</div>
	</div></div><?php endif; ?>

	<?php if ($this->_user->hasPermission("core.settings")): ?><div class="col-xs-12 col-sm-4 placeholder"><div class="media module-row">
		<a class="media-left" href="<?=$tags["settings-link"]?>">
			<img class="module-row-icon" src="<?=PATH?>images/icons/cog.svg" alt="<?=$this->_lang("core", "settings.moduleName")?>">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="<?=$tags["settings-link"]?>"><?=$this->_lang("core", "settings.moduleName")?></a></h4>
			<?=$this->_lang("core", "settings.moduleDescription")?>
		</div>
	</div></div><?php endif; ?>

	<?php if ($this->_user->hasPermission("core.packages")): ?><div class="col-xs-12 col-sm-4 placeholder"><div class="media module-row">
		<a class="media-left" href="<?=$tags["packages-link"]?>">
			<img class="module-row-icon" src="<?=PATH?>images/icons/brick.svg" alt="<?=$this->_lang("core", "packages.moduleName")?>">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="<?=$tags["packages-link"]?>"><?=$this->_lang("core", "packages.moduleName")?></a></h4>
			<?=$this->_lang("core", "packages.moduleDescription")?>
		</div>
	</div></div><?php endif; ?>

	<?php if ($this->_user->hasPermission("core.backup")): ?><div class="col-xs-12 col-sm-4 placeholder"><div class="media module-row">
		<a class="media-left" href="<?=$tags["backup-link"]?>">
			<img class="module-row-icon" src="<?=PATH?>images/icons/database.svg" alt="<?=$this->_lang("core", "backup.moduleName")?>">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="<?=$tags["backup-link"]?>"><?=$this->_lang("core", "backup.moduleName")?></a></h4>
			<?=$this->_lang("core", "backup.moduleDescription")?>
		</div>
	</div></div><?php endif; ?>

	<?php if ($this->_user->hasPermission("core.menu")): ?><div class="col-xs-12 col-sm-4 placeholder"><div class="media module-row">
		<a class="media-left" href="<?=$tags["menu-link"]?>">
			<img class="module-row-icon" src="<?=PATH?>images/icons/layers.svg" alt="<?=$this->_lang("core", "menu.moduleName")?>">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="<?=$tags["menu-link"]?>"><?=$this->_lang("core", "menu.moduleName")?></a></h4>
			<?=$this->_lang("core", "menu.moduleDescription")?>
		</div>
	</div></div><?php endif; ?>

	<?php if ($this->_user->hasPermission("core.media")): ?><div class="col-xs-12 col-sm-4 placeholder"><div class="media module-row">
		<a class="media-left" href="<?=$tags["media-link"]?>">
			<img class="module-row-icon" src="<?=PATH?>images/icons/file-picture.svg" alt="<?=$this->_lang("core", "media.moduleName")?>">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="<?=$tags["media-link"]?>"><?=$this->_lang("core", "media.moduleName")?></a></h4>
			<?=$this->_lang("core", "media.moduleDescription")?>
		</div>
	</div></div><?php endif; ?>
</div>
<hr>

<?php foreach($tags["packages"] as $row): if ($this->_user->hasPermission($row["name"])): ?>
<div class="media module-row">
	<a class="media-left" href="<?=$row["link"]?>"><img class="module-row-icon" src="<?=$row["icon-link"]?>" alt="<?=$row["title"]?>"></a>
	<div class="media-body">
		<h4 class="media-heading"><a href="<?=$row["link"]?>"><?=$row["title"]?></a></h4>
		<?=$row["description"]?>
	</div>
</div>
<?php endif; endforeach; ?>

<style>
	.module-row {
		margin: 10px 0;
	}

	.module-row-icon {
		width: 48px;
	}
</style>