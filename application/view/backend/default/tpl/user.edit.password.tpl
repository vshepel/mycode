[include "user.tabs"]
[include "user.edit.tabs"]

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[f:user:edit.password.form.oldPassword]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="edit[old_password]">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:user:edit.password.form.newPassword]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="edit[new_password]">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:user:edit.password.form.newPasswordRetype]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="edit[new_password_2]">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-ok"></span> [f:user:edit.password.form.submit]</button>
		</div>
	</div>
</form>
