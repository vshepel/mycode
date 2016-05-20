<div class="media">
	<a class="pull-left" href="{original-avatar-link}">
		<img class="media-object img-rounded" src="{avatar-link}" alt="{username}" style="max-width: 120px">
	</a>
	<div class="media-body">
		<h2 class="media-heading">{username}<small> [online][f:user:profile.status.online][/online][offline][f:user:profile.status.offline] {last-online-date} в {last-online-time}[/offline]</small></h2>
		<p>{group}</p>
		[logged]<p>
			[owner]<a class="btn btn-success" href="{edit-link}"><span class="glyphicon glyphicon-pencil"></span> [f:user:profile.link.edit]</a>
			<a class="btn btn-danger" href="{sessions-link}"><span class="glyphicon glyphicon-list-alt"></span> [f:user:profile.link.sessions]</a>[/owner]
			[not-owner]<a class="btn btn-info" href="{message-send-link}"><span class="glyphicon glyphicon-send"></span> [f:user:profile.link.message-send]</a>[/not-owner]
		</p>[/logged]
	</div>
</div>

<hr>

<dl class="dl-horizontal">
	[if name!=""]<dt>[b:user:fields.name]</dt> <dd>{name}</dd>[/if]
	<dt>[b:user:fields.gender]</dt> <dd>{gender}</dd>
	[birth]<dt>[b:user:fields.birth]</dt> <dd>{birth-date}</dd>[/birth]
	[if location!=""]<dt>[b:user:fields.location]</dt> <dd>{location}</dd>[/if]
	[if url!=""]<dt>[b:user:fields.url]</dt> <dd><a href="{url}">{url}</a></dd>[/if]
	[if public-email!=""]<dt>[b:user:fields.publicEmail]</dt> <dd><a href="mailto:{public-email}">{public-email}</a></dd>[/if]
	<dt>[b:user:fields.group]</dt> <dd>{group}</dd>
</dl>
