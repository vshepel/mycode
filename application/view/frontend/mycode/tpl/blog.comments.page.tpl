{addform}

<div id="comments">
	[if num!="0"][foreach rows]
	<div class="comments_item">
		<div class="item-photo">
			<a class="pull-left" href="{author-link}">
				<img src="{author-avatar-link}" alt="{author-login}">
			</a>
		</div>
		<div class="item-text">
			<a href="{author-link}">{author-login}</a> [f:blog:comments.list.says]..
			<div class="text">
				{comment-message}
			</div>
			<div class="date">
				{date} [f:blog:comments.list.in] {time}
			</div>
		</div>
	</div>
	<!-- <div class="media">
		<a class="pull-left" href="{author-link}">
			<img class="media-object img-rounded" src="{avatar-link}" alt="{author-login}" style="max-width: 50px">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="{author-link}">{author-login}</a><small>[remove]<a class="remove" href="{remove-url}">[f:blog:comments.comment.remove]</a>[/remove] [f:blog:comments.comment.wrote] {date} [f:blog:comments.comment.at] {time}</small></h4>
			{comment-message}
		</div>
	</div> -->
	[/foreach][/if][if num="0"]
	<div class="post-nope">
		<i class="mdi mdi-comment-remove-outline"></i>
		[f:blog:comments.list.noRows]
	</div>
	[/if]
</div>

[if num!="0"]{pagination}[/if]
