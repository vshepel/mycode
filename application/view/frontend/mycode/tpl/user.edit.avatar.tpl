<div class="block">
	<div class="title">[f:user:edit.title]</div>
	[include "user.edit.tabs"]
	<div class="block-content">
		<form class="form" enctype="multipart/form-data" method="post">
			<input type="hidden" name="edit[avatar]">
			<img class="img-rounded" src="{avatar-link}">
			<div class="field">
				<span>[f:user:edit.avatar.form.upload]</span>
				<input name="avatar" type="file">
			</div>
			<div class="field">
				<span>[f:user:edit.avatar.form.delete]</span>
				<input name="edit[delete]" type="checkbox">
			</div>
			<div class="panel">
				<button class="btn-primary">
					[f:user:edit.main.form.submit]
				</button>
			</div>
		</form>
	</div>
</div>