[include "user.tabs"]

<form method="post">
	<input type="hidden" name="remove[id]" value="{id}">
	<div class="alert alert-info">
		<h4>[f:user:groups.remove.title]</h4>
		<p>[f:user:groups.remove.description]</p>

		<p>
			<button class="btn btn-danger" type="submit">[b:core:options.yes]</button>
			<a href="{ADMIN_PATH}user/groups" class="btn btn-success">[b:core:options.no]</a>
		</p>
	</div>
</form>
