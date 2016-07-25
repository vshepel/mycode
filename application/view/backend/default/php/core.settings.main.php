<?php include "core.settings.tabs.php"; ?>

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.main.moduleFrontend")?></label>
		<div class="col-sm-9">
			<select class="form-control" name="module-frontend"><?=$tags["module-frontend"]?></select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.main.moduleBackend")?></label>
		<div class="col-sm-9">
			<select class="form-control" name="module-backend"><?=$tags["module-backend"]?></select>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.main.cache")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="cache"<?=$tags["cache"] ? "checked" : ""?>>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.main.smartDate")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="smart-date"<?=$tags["smart-date"] ? "checked" : ""?>>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.main.formatDate")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="format-date" value="<?=$tags["format-date"]?>">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.main.formatTime")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="format-time" value="<?=$tags["format-time"]?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.main.timezone")?></label>
		<div class="col-sm-9">
			<select class="form-control" name="timezone"><?=$tags["timezones"]?></select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.main.rewriteRoutes")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="rewrite-routes"<?=$tags["rewrite-routes"] ? "checked" : ""?>>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> <?=$this->_flang("core", "settings.submit")?></button>
		</div>
	</div>
</form>
