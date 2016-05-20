[include "user.tabs"]

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.email]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="add[email]" value="{email}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.login]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="add[login]" value="{login}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.password]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="add[password]">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.passwordRetype]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="add[password_2]">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.name]:</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="add[name]" value="{name}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary">[f:user:add.form.submit]</button>
		</div>
	</div>
</form>
