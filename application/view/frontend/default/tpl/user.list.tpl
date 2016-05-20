[f:user:list.total] <span class="badge">{num}</span>

<hr>

[foreach rows]
<div class="media">
	<a class="pull-left" href="{profile-link}">
		<img class="media-object img-rounded" src="{avatar-link}" alt="{username}" style="max-height: 50px">
	</a>
	<div class="media-body">
		<h4 class="media-heading"><a href="{profile-link}">{username}</a><small> [online][f:user:list.user.online][/online][offline][f:user:list.user.offline] {last-online-date} в {last-online-time}[/offline]</small></h4>
		{group}
	</div>
</div>
[/foreach]

{pagination}
