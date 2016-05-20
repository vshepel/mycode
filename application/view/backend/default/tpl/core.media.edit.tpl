<ul class="nav nav-tabs" role="tablist">
	<li><a href="{ADMIN_PATH}core/media/list"><span class="glyphicon glyphicon-picture"></span> [b:core:media.list.moduleName]</a></li>
	<li><a href="{ADMIN_PATH}core/media/upload"><span class="glyphicon glyphicon-upload"></span> [b:core:media.upload.moduleName]</a></li>
</ul><br>

<form method="post" class="form-horizontal">
	<dl class="dl-horizontal">
		<dt>[f:core:media.edit.user]</dt> <dd><a href="{user-link}">{user-login}</a></dd>
		<dt>URL</dt> <dd><a href="{url}">{url}</a></dd>
		<dt>[f:core:media.edit.filename]</dt> <dd>{filename}</dd>
	</dl>
	
	<hr>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:media.edit.form.name] *</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="name" value="{name}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:media.edit.form.description]</label>
		<div class="col-sm-9">
			<textarea class="form-control" name="description" rows="6">{description}</textarea>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary">[f:core:media.edit.form.submit]</button>
		</div>
	</div>
</form>
