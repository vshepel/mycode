{addform}
[f:blog:comments.list.total] <span class="badge" id="blog-comments-num">{num}</span><br><br>

<div id="blog-comments">
	[if num!="0"][foreach rows]
	<div class="media">
		<a class="pull-left" href="{author-link}">
			<img class="media-object img-rounded" src="{avatar-link}" alt="{author-login}" style="max-width: 50px">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="{author-link}">{author-login}</a><small>[remove]<a class="remove" href="{remove-url}">[f:blog:comments.comment.remove]</a>[/remove] [f:blog:comments.comment.wrote] {date} [f:blog:comments.comment.at] {time}</small></h4>
			{comment-message}
		</div>
	</div>
	[/foreach][/if][if num="0"]
	<div class="alert alert-info">
		[f:blog:comments.list.noRows]
	</div>[/if]
</div>

<div id="blog-comments-pagination">
	[if num!="0"]{pagination}[/if]
</div>
