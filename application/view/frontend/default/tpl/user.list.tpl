<form method="post" class="form-horizontal" action="{SITE_PATH}user/search">
	<div class="input-group">
		<input type="text" name="query" class="form-control" placeholder="[f:user:search.holder]" value="{query}">
	  <span class="input-group-btn">
		<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
	  </span>
	</div>
</form><br>

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
		[if installed-module:messages]<div class="pull-right">
			<a href="{message-send-link}" style="font-size:30px"><span class="fa fa-envelope"></span></a>
		</div>[/if]
		<div class="clearfix"></div>
	</div>
</div>
[/foreach]

{pagination}
