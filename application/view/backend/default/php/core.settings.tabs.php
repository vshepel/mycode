<ul class="nav nav-tabs" role="tablist">
	<li<?=$tags["action"] == "main" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>core/settings/main">
		<span class="main-row-icon fa fa-cog"></span> <?=$this->_lang("core", "settings.main.moduleName")?>
	</a></li>
	
	<li<?=$tags["action"] == "site" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>core/settings/site">
		<span class="main-row-icon fa fa-globe"></span> <?=$this->_lang("core", "settings.site.moduleName")?>
	</a></li>
	
	<li<?=$tags["action"] == "view" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>core/settings/view">
		<span class="main-row-icon fa fa-eye"></span> <?=$this->_lang("core", "settings.view.moduleName")?>
	</a></li>
	
	<li<?=$tags["action"] == "sendmail" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>core/settings/sendmail">
		<span class="main-row-icon fa fa-envelope"></span> <?=$this->_lang("core", "settings.sendmail.moduleName")?>
	</a></li>
</ul><br>
