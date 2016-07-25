<section class="block">
	<div class="title">[f:user:restore.title]</div>
	<ul class="tabs">
		<li class="active">
			<a href="/user/auth">[f:user:auth.tab.entry]</a>
		</li>
		<li>
			<a href="/user/register">[f:user:auth.tab.register]</a>
		</li>
	</ul>
	<div class="block-content">
		<form class="form" role="form" method="post">
			<div class="field">
				<span>[f:user:form.loginOrEmail]</span>
				<input type="login" name="login" value="{login}" autofocus />
			</div>
			<div class="panel">
				<button class="btn-primary">
					[f:user:restore.form.btn]
				</button>
			</div>
		</form>
	</div>
</section>
