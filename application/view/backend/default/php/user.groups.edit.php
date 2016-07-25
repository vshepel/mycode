<?php include "user.tabs.php"; ?>

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("user", "groups.edit.form.name")?>: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="edit[name]" value="<?=$tags["name"]?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("user", "groups.edit.form.extends")?>:</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="edit[extends]" value="<?=$tags["extends"]?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("user", "groups.edit.form.permissions")?>:</label>
		<div class="col-sm-9">
			<textarea class="form-control" name="edit[permissions]" rows="7"><?=$tags["permissions"]?></textarea>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary">
				<span class="fa fa-save"></span> <?=$this->_flang("user", "groups.edit.form.submit")?>
			</button>
		</div>
	</div>
</form>
