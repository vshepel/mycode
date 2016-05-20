[include "core.packages.tabs"]

<form method="post">
	<input type="hidden" name="remove[name]" value="{name}">
	<div class="alert alert-info">
		<h4>[f:core:packages.remove.sure]</h4>
		<p>[f:core:packages.remove.sureFull]</p>
		
		<p>
			<input class="form-control" type="checkbox" name="remove_links" checked>
			[f:core:packages.remove.links]
		</p>

		<p>
			<button class="btn btn-danger" type="submit">[b:core:options.yes]</button>
			<a href="{ADMIN_PATH}core/packages" class="btn btn-success">[b:core:options.no]</a>
		</p>
	</div>
</form>
