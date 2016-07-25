<ul class="nav nav-pills" role="tablist">
	<li<?=$tags["edit-name"] == "main" ? " class=\"active\"" : ""?>><a href="<?=$tags["edit-link"]?>/main">
		<span class="fa fa-cog"></span> <?=$this->_lang("user", "edit.main.title")?>
	</a></li>
	<li<?=$tags["edit-name"] == "password" ? " class=\"active\"" : ""?>><a href="<?=$tags["edit-link"]?>/password">
		<span class="fa fa-lock"></span> <?=$this->_lang("user", "edit.password.title")?>
	</a></li>
	<li<?=$tags["edit-name"] == "avatar" ? " class=\"active\"" : ""?>><a href="<?=$tags["edit-link"]?>/avatar">
		<span class="fa fa-image"></span> <?=$this->_lang("user", "edit.avatar.title")?>
	</a></li>
</ul><br>
