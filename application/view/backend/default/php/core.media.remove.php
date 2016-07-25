<ul class="nav nav-tabs" role="tablist">
	<li><a href="<?=ADMIN_PATH?>core/media/list"><span class="glyphicon glyphicon-picture"></span> <?=$this->_lang("core", "media.list.moduleName")?></a></li>
	<li><a href="<?=ADMIN_PATH?>core/media/upload"><span class="glyphicon glyphicon-upload"></span> <?=$this->_lang("core", "media.upload.moduleName")?></a></li>
</ul><br>

<form method="post">
	<input type="hidden" name="remove[id]" value="<?=$tags["id"]?>">
	<div class="alert alert-info">
		<h4><?=$this->_flang("core", "media.remove.sure")?></h4>
		<p><?=$this->_flang("core", "media.remove.sureFull")?></p>

		<p>
			<button class="btn btn-danger" type="submit"><?=$this->_lang("core", "options.yes")?></button>
			<a href="<?=ADMIN_PATH?>core/media" class="btn btn-success"><?=$this->_lang("core", "options.no")?></a>
		</p>
	</div>
</form>
