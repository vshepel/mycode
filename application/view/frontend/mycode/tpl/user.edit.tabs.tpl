<ul class="tabs">
	<li [if edit-name="main"]class="active"[/if]>
		<a href="{edit-link}/main">
			[f:user:edit.general]
		</a>
	</li>
	<li [if edit-name="password"]class="active"[/if]>
		<a href="{edit-link}/password">
			[f:user:edit.security]
		</a>
	</li>
	<li [if edit-name="avatar"]class="active"[/if]>
		<a href="{edit-link}/avatar">
			[f:user:edit.photo]
		</a>
	</li>
</ul>