[include "core.settings.tabs"]

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.site.link]</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="link" value="{link}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.site.path]</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="path" value="{path}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.site.name]</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="name" value="{name}">
		</div>           
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.site.description]</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="description" value="{description}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.site.keywords]</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="keywords" value="{keywords}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.site.charset]</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="charset" value="{charset}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.site.language]</label>
		<div class="col-sm-9">
			<select class="form-control" name="language">{language}</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> [f:core:settings.submit]</button>
		</div>
	</div>
</form>
