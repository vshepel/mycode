<ul class="nav nav-tabs" role="tablist">
	<li [if edit-name="main"]class="active"[/if]><a href="{edit-link}/main">
		<span class="fa fa-cog"></span> [b:user:edit.main.title]
	</a></li>
	<li [if edit-name="password"]class="active"[/if]><a href="{edit-link}/password">
		<span class="fa fa-lock"></span> [b:user:edit.password.title]
	</a></li>
	<li [if edit-name="avatar"]class="active"[/if]><a href="{edit-link}/avatar">
		<span class="fa fa-image"></span> [b:user:edit.avatar.title]
	</a></li>
</ul><br>
