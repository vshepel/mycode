[include "user.tabs"]

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[f:user:groups.edit.form.name]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="edit[name]" value="{name}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:user:groups.edit.form.extends]:</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="edit[extends]" value="{extends}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:user:groups.edit.form.permissions]:</label>
		<div class="col-sm-9">
			<textarea class="form-control" name="edit[permissions]" rows="7">{permissions}</textarea>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary">
				<span class="fa fa-save"></span> [f:user:groups.edit.form.submit]
			</button>
		</div>
	</div>
</form>
