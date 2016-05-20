[include "core.settings.tabs"]

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.main.moduleFrontend]</label>
		<div class="col-sm-9">
			<select class="form-control" name="module-frontend">{module-frontend}</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.main.moduleBackend]</label>
		<div class="col-sm-9">
			<select class="form-control" name="module-backend">{module-backend}</select>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.main.cache]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="cache"[cache] checked[/cache]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.main.smartDate]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="smart-date"[smart-date] checked[/smart-date]>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.main.formatDate]</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="format-date" value="{format-date}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.main.formatTime]</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="format-time" value="{format-time}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.main.rewriteRoutes]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="rewrite-routes"[rewrite-routes] checked[/rewrite-routes]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> [f:core:settings.submit]</button>
		</div>
	</div>
</form>
