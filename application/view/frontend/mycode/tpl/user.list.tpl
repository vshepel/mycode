<div class="page_total">
	[f:user:list.total] <span>{num} [f:user:list.total.people]</span>
</div>

[foreach rows]
<article class="card ">
	<div class="photo_content">
		[if group-id="1"]<span class="group-label">{group}</span>[/if]
		<a href="{profile-link}">
			<img src="{avatar-link}" alt="{username}" />
		</a>
	</div>
	<a class="user_link" href="{profile-link}">[if name!=""]{name}[/if][if name=""]{username}[/if]</a>
	<span class="status">[online]<span class="online">[f:user:profile.online]</span>[/online][offline][f:user:profile.offline] {last-online-date} в {last-online-time}[/offline]</span>
	<div class="panel">
		<a href="{message-send-link}" class="btn-primary"><i class="mdi mdi-email"></i></a>
	</div>
</article>
[/foreach]

<br clear="both" />

{pagination}