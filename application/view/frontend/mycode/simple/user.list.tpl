<div class="page_total">
	[f:user:list.total] <span>{num} [f:user:list.total.people]</span>
</div>

<div class="page_search">
	<form method="post" action="{SITE_PATH}user/search">
		<input type="text" name="query" placeholder="[f:user:list.find]" value="{query}" />
		<button><i class="mdi mdi-magnify"></i></button>
	</form>
</div>

<div class="card_content">
	[foreach rows]
	<article class="card">
		<div class="photo_content">
			[if group-id="1"]<span class="group-label">{group}</span>[/if]
			<a href="{profile-link}">
				<img src="{avatar-link}" alt="{username}" />
			</a>
		</div>
		<a class="user_link" href="{profile-link}">[if name!=""]{name}[/if][if name=""]{username}[/if]</a>
		<span class="status">[online]<span class="online">[f:user:profile.online]</span>[/online][offline][f:user:profile.offline] {last-online-date} [f:user:profile.in] {last-online-time}[/offline]</span>
		[logged]
		<div class="panel">
			<span><i class="mdi mdi-fire"></i> {blog:user-rating:user={id}}</span> <a href="{message-send-link}" class="btn-primary"><i class="mdi mdi-email"></i></a>
		</div>
		[/logged]
	</article>
	[/foreach]
</div>

<br clear="both" />

{pagination}