<ul class="nav nav-tabs" role="tablist">
	<li<?=$tags["action"] == "list" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>core/packages">
		<span class="main-row-icon fa fa-archive"></span> <?=$this->_lang("core", "packages.moduleName")?>
	</a></li>
	
	<li<?=$tags["action"] == "install" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>core/packages/install">
		<span class="main-row-icon fa fa-plus"></span> <?=$this->_lang("core", "packages.install.moduleName")?>
	</a></li>
</ul><br>
