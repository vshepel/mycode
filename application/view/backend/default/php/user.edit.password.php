<?php include "user.tabs.php"; ?>
<?php include "user.edit.tabs.php"; ?>

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("user", "edit.password.form.oldPassword")?>: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="edit[old_password]">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("user", "edit.password.form.newPassword")?>: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="edit[new_password]">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("user", "edit.password.form.newPasswordRetype")?>: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="edit[new_password_2]">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-ok"></span> <?=$this->_flang("user", "edit.password.form.submit")?></button>
		</div>
	</div>
</form>
