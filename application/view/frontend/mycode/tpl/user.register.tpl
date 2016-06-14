<section class="block">
	<div class="title">[f:user:register.title]</div>
	<ul class="tabs">
		<li>
			<a href="/user/auth">[f:user:auth.tab.entry]</a>
		</li>
		<li class="active">
			<a href="#">[f:user:auth.tab.register]</a>
		</li>
	</ul>
	<div class="block-content">
		<form class="form" role="form" method="post">
			<div class="field">
				<span>[f:user:form.name]</span>
				<input type="text" name="register[name]" value="{name}" autofocus />
			</div>
			<div class="field">
				<span>[f:user:form.email]</span>
				<input type="email" name="register[email]" value="{email}" />
			</div>
			<div class="break"></div>
			<div class="field">
				<span>[f:user:form.login]</span>
				<input type="text" name="register[login]" value="{login}" />
			</div>
			<div class="field">
				<span>[f:user:form.pass]</span>
				<input type="password" name="register[password]" />
			</div>
			<div class="field">
				<span>[f:user:form.passre]</span>
				<input type="password" name="register[password_2]" />
			</div>
			<div class="panel">
				<button class="btn-primary">
					[f:user:register.form.btn]
				</button>
			</div>
		</form>
	</div>
</section>