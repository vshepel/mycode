[include "user.edit.tabs"]

<form enctype="multipart/form-data" method="post" class="form-horizontal">
	<input type="hidden" name="edit[avatar]">

	<div class="form-group">
		<label class="control-label col-sm-3">[f:user:edit.avatar.form.current]:</label>
		<div class="col-sm-9">
			<a href="{original-avatar-link}">
				<img class="img-rounded" src="{avatar-link}">
			</a>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:user:edit.avatar.form.upload]:</label>
		<div class="col-sm-9">
			<input name="avatar" type="file">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:user:edit.avatar.form.delete]:</label>
		<div class="col-sm-9">
			<input name="edit[delete]" type="checkbox">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-upload"></span> [f:user:edit.avatar.form.submit]</button>
		</div>
	</div>
</form>
