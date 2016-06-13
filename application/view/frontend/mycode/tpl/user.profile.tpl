<section class="profile">
	<div class="general">
		<img src="{avatar-link}" alt="{username}">
		<div>
			[if name!=""]{name}[/if][if name=""]{username}[/if][if group-id="1"] <span class="group-label">{group}</span>[/if]
			<small>[online][f:user:profile.status.online][/online][offline][f:user:profile.status.offline] {last-online-date} в {last-online-time}[/offline]</small>
		</div>
		<!-- <div class="rating">
			<i class="mdi mdi-fire"></i> 1 342
		</div> -->
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
				<i class="mdi mdi-link"></i> [if url!=""]<a href="{url}" target="_blank">{url}</a>[/if][if url=""]<span>[f:user:profile.empty]</span>[/if]
			</li>
			<li>
				<i class="mdi mdi-email"></i> [if public-email!=""]<a href="mailto:{public-email}">{public-email}</a>[/if][if public-email=""]<span>[f:user:profile.empty]</span>[/if]
			</li>
		</ul>
	</div>
</section>
