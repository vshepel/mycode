[include "core.packages.tabs"]

<form enctype="multipart/form-data" method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:packages.install.file]:</label>
		<div class="col-sm-9">
			<input name="file" type="file">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-upload"></span> [f:core:packages.install.submit]</button>
		</div>
	</div>
</form>
