[include "page.tabs"]

<form method="post">
	<input type="hidden" name="remove[id]" value="{id}">
	<div class="alert alert-info">
		<h4>[f:page:remove.sure]</h4>
		<p>[f:page:remove.sureFull]</p>

		<p>
			<button class="btn btn-danger" type="submit">[b:core:options.yes]</button>
			<a href="{ADMIN_PATH}page/list" class="btn btn-success">[b:core:options.no]</a>
		</p>
	</div>
</form>
