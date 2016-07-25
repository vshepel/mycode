<div class="block">
	<div class="title">[f:user:edit.title]</div>
	[include "user.edit.tabs"]
	<div class="block-content">
		<form class="form" method="post">
			<div class="field">
				<span>[f:user:form.old.pass]</span>
				<input type="password" name="edit[old_password]" />
			</div>
			<div class="field">
				<span>[f:user:form.new.pass]</span>
				<input type="password" name="edit[new_password]" />
			</div>
			<div class="field">
				<span>[f:user:form.passre]</span>
				<input type="password" name="edit[new_password_2]" />
			</div>
			<div class="panel">
				<button class="btn-primary">
					[f:user:edit.form.btn]
				</button>
			</div>
		</form>
	</div>
</div>