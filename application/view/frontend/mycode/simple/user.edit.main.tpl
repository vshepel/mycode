<div class="block">
	<div class="title">[f:user:edit.title]</div>
	[include "user.edit.tabs"]
	<div class="block-content">
		<form class="form" method="post">
			<div class="field">
				<span>[f:user:form.name]</span>
				<input type="text" name="edit[name]" value="{name}" />
			</div>
			<div class="field">
				<span>[f:user:form.email]</span>
				<input type="text" name="edit[public_email]" value="{public-email}" />
			</div>
			<div class="field">
				<span>[f:user:form.lang]</span>
				<select class="form-control" name="edit[lang]">
					<option value="">[b:user:fields.lang]</option>
					[foreach langs]<option value="{value}"[active] selected[/active]>{name}</option>[/foreach]
				</select>
			</div>
			<div class="break"></div>
			<div class="field full">
				<span>[f:user:form.url]</span>
				<input type="text" name="edit[url]" value="{url}" placeholder="http://" />
			</div>
			<div class="panel">
				<button class="btn-primary">
					[f:user:edit.form.btn]
				</button>
			</div>
		</form>
	</div>
</div>
