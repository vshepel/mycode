<section class="block">
	<div class="title">[f:user:auth.title]</div>
	<ul class="tabs">
		<li class="active">
			<a href="#">[f:user:auth.tab.entry]</a>
		</li>
		<li>
			<a href="/user/register">[f:user:auth.tab.register]</a>
		</li>
	</ul>
	<div class="block-content">
		<form class="form" role="form" method="post">
			<div class="field">
				<span>[f:user:form.login]</span>
				<input type="text" name="login" autofocus />
			</div>
			<div class="field">
				<span>[f:user:form.password]</span>
				<input type="password" name="password" />
			</div>
			<div class="panel">
				<button class="btn-primary">
					[f:user:auth.form.btn]
				</button>
				<a href="{restore-link}">[f:user:auth.form.restore]</a>
			</div>
		</form>
	</div>
</section>