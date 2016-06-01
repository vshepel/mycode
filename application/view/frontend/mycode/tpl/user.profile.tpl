<section class="profile">
	<div class="general">
		<img src="{avatar-link}" alt="{username}">
		<div>
			[if name!=""]{name}[/if][if name=""]{username}[/if][if group-id="1"] <span class="group-label">{group}</span>[/if]
			<small>[online][f:user:profile.status.online][/online][offline][f:user:profile.status.offline] {last-online-date} в {last-online-time}[/offline]</small>
		</div>
		<div class="rating">
			<i class="mdi mdi-fire"></i> 1 342
		</div>
	</div>
	<div class="information">
		<div class="counter">
			<span>{blog:user-posts-count:{id}}</span>
			[f:user:profile.blog.postsCount]
		</div>
		<div class="counter">
			<span>{blog:user-comments-count:{id}}</span>
			[f:user:profile.blog.commentsCount]
		</div>
		<ul class="about">
			<li>
				<i class="mdi mdi-link"></i> [if url!=""]<a href="{url}">{url}</a>[/if][if url=""]<span>Empty</span>[/if]
			</li>
			<li>
				<i class="mdi mdi-email"></i> [if public-email!=""]<a href="mailto:{public-email}">{public-email}</a>[/if][if public-email=""]<span>Empty</span>[/if]
			</li>
		</ul>
	</div>
</section>

<!-- <div class="media">
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
 -->