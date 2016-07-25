<?php include "user.tabs.php"; ?>
<?php include "user.edit.tabs.php"; ?>

<form enctype="multipart/form-data" method="post" class="form-horizontal">
	<input type="hidden" name="edit[avatar]">

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("user", "edit.avatar.form.current")?>:</label>
		<div class="col-sm-9">
			<img class="img-rounded" src="<?=$tags["avatar-link"]?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("user", "edit.avatar.form.upload")?>:</label>
		<div class="col-sm-9">
			<input name="avatar" type="file">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("user", "edit.avatar.form.delete")?>:</label>
		<div class="col-sm-9">
			<input name="edit[delete]" type="checkbox">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-upload"></span> <?=$this->_flang("user", "edit.avatar.form.submit")?></button>
		</div>
	</div>
</form>
