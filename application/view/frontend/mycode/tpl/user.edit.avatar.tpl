<div class="block">
	<div class="title">[f:user:edit.title]</div>
	[include "user.edit.tabs"]
	<div class="block-content edit_photo">
		<form class="form" enctype="multipart/form-data" method="post">
			<input type="hidden" name="edit[avatar]">
			<img class="avatar" src="{avatar-link}">
			<div class="photo-panel">
				<div class="inputfile">
					<input id="file" class="file" name="avatar" type="file">
					<label for="file">[f:user:edit.avatar.form.upload]</label>
				</div> <span>or</span>
				<div class="checkbox">
					<input id="avatar-del" name="edit[delete]" type="checkbox"> <label for="avatar-del">[f:user:edit.avatar.form.delete]</label>
				</div>
				<p>
					[f:user:edit.avatar.text]
				</p>
			</div>
			<div class="panel">
				<button class="btn-primary">
					[f:user:edit.personal.form.submit]
				</button>
			</div>
		</form>
	</div>
</div>