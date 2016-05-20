<ul class="nav nav-tabs" role="tablist">
	<li><a href="{ADMIN_PATH}core/media/list"><span class="glyphicon glyphicon-picture"></span> [b:core:media.list.moduleName]</a></li>
	<li><a href="{ADMIN_PATH}core/media/upload"><span class="glyphicon glyphicon-upload"></span> [b:core:media.upload.moduleName]</a></li>
</ul><br>

<form method="post">
	<input type="hidden" name="remove[id]" value="{id}">
	<div class="alert alert-info">
		<h4>[f:core:media.remove.sure]</h4>
		<p>[f:core:media.remove.sureFull]</p>

		<p>
			<button class="btn btn-danger" type="submit">[b:core:options.yes]</button>
			<a href="{ADMIN_PATH}core/media" class="btn btn-success">[b:core:options.no]</a>
		</p>
	</div>
</form>
