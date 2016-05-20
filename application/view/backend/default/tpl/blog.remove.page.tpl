[include "blog.tabs"]

<form method="post">
	<input type="hidden" name="remove[id]" value="{id}">
	<div class="alert alert-info">
		<h4>[f:blog:remove.sure]</h4>
		<p>[f:blog:remove.sureFull]</p>

		<p>
			<button class="btn btn-danger" type="submit">[b:core:options.yes]</button>
			<a href="{ADMIN_PATH}blog/posts" class="btn btn-success">[b:core:options.no]</a>
		</p>
	</div>
</form>
