<div class="block">
	<div class="title">[f:user:edit.title]</div>
	[include "user.edit.tabs"]
	<div class="block-content">
		<form class="form" method="post">
			<div class="field">
				<span>[f:user:edit.password.form.oldPassword]</span>
				<input type="password" name="edit[old_password]">
			</div>
			<div class="field">
				<span>[f:user:edit.password.form.newPassword]</span>
				<input type="password" name="edit[new_password]">
			</div>
			<div class="field">
				<span>[f:user:edit.password.form.newPasswordRetype]</span>
				<input type="password" name="edit[new_password_2]">
			</div>
			<div class="panel">
				<button class="btn-primary">
					[f:user:edit.main.form.submit]
				</button>
			</div>
		</form>
	</div>
</div>