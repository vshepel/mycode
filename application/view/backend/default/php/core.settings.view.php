<?php include "core.settings.tabs.php"; ?>

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.view.frontend")?></label>
		<div class="col-sm-9">
			<select class="form-control" name="frontend"><?=$tags["frontend-views"]?></select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.view.backend")?></label>
		<div class="col-sm-9">
			<select class="form-control" name="backend"><?=$tags["backend-views"]?></select>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.view.cache")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="cache"<?=$tags["cache"] ? "checked" : ""?>>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.view.compress")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="compress"<?=$tags["compress"] ? "checked" : ""?>>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> <?=$this->_flang("core", "settings.submit")?></button>
		</div>
	</div>
</form>
