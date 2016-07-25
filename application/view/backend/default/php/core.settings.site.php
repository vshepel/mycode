<?php include "core.settings.tabs.php"; ?>

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.site.link")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="link" value="<?=$tags["link"]?>">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.site.path")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="path" value="<?=PATH?>">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.site.name")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="name" value="<?=$tags["name"]?>">
		</div>           
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.site.description")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="description" value="<?=$tags["description"]?>">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.site.keywords")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="keywords" value="<?=$tags["keywords"]?>">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.site.charset")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="charset" value="<?=$tags["charset"]?>">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.site.language")?></label>
		<div class="col-sm-9">
			<select class="form-control" name="language"><?=$tags["language"]?></select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.site.disabled")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="disabled"<?=$tags["disabled"] ? "checked" : ""?>>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> <?=$this->_flang("core", "settings.submit")?></button>
		</div>
	</div>
</form>
