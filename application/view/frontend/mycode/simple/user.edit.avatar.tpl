<div class="block">
	<div class="title">[f:user:edit.title]</div>
	[include "user.edit.tabs"]
	<div class="block-content edit_photo">
		<form class="form" enctype="multipart/form-data" method="post">
			<input type="hidden" name="edit[avatar]" />
			<img class="avatar" src="{avatar-link}" />
			<div class="photo-panel">
				<div class="inputfile">
					<input id="file" class="file" name="avatar" type="file" />
					<label for="file">[f:user:form.upload]</label>
				</div> <span>[f:user:edit.photo.or]</span>
				<div class="checkbox">
					<input id="avatar-del" name="edit[delete]" type="checkbox" /> <label for="avatar-del">[f:user:edit.photo.delete]</label>
				</div>
				<p>
					[f:user:edit.photo.text]
				</p>
			</div>
			<div class="panel">
				<button class="btn-primary">
					[f:user:edit.form.btn]
				</button>
			</div>
		</form>
	</div>
</div>