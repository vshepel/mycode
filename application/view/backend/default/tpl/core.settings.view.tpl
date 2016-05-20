[include "core.settings.tabs"]

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.view.frontend]</label>
		<div class="col-sm-9">
			<select class="form-control" name="frontend">{frontend-views}</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.view.backend]</label>
		<div class="col-sm-9">
			<select class="form-control" name="backend">{backend-views}</select>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.view.cache]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="cache"[cache] checked[/cache]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.view.compress]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="compress"[compress] checked[/compress]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> [f:core:settings.submit]</button>
		</div>
	</div>
</form>
