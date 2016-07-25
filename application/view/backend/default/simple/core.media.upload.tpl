<ul class="nav nav-tabs" role="tablist">
	<li><a href="{ADMIN_PATH}core/media/list"><span class="glyphicon glyphicon-picture"></span> [b:core:media.list.moduleName]</a></li>
	<li class="active"><a href="{ADMIN_PATH}core/media/upload"><span class="glyphicon glyphicon-upload"></span> [b:core:media.upload.moduleName]</a></li>
</ul><br>

<form enctype="multipart/form-data" method="post" class="form-horizontal">
	<input type="hidden" name="edit[avatar]">

	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:media.upload.form.upload]:</label>
		<div class="col-sm-9">
			<input name="file" type="file">
			<small>[f:core:media.upload.maxFilesize]: <b>{max-filesize}</b></small>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-upload"></span> [f:core:media.upload.form.submit]</button>
		</div>
	</div>
</form>
