[f:user:list.total] <span class="badge">{num}</span>

<hr>

[foreach rows]
<div class="media">
	<a class="pull-left" href="{profile-link}">
		<img class="media-object img-rounded" src="{avatar-link}" alt="{username}" style="max-height: 50px">
	</a>
	<div class="media-body">
		<div class="pull-left">
			<h4 class="media-heading"><a href="{profile-link}">{username}</a><small> [if name!=""]({name})[/if] [online][f:user:list.user.online][/online][offline][f:user:list.user.offline] {last-online-date} в {last-online-time}[/offline]</small></h4>
			{group}
		</div>
		<div class="pull-right"><a href="{message-send-link}" style="font-size:30px"><span class="fa fa-envelope"></span></a></div>
		<div class="clearfix"></div>
	</div>
</div>
[/foreach]

{pagination}
