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
			<a href="{author-link}">{author-login}</a> [f:blog:comments.page.says]..
			<div class="text">
				{comment-message}
			</div>
			<div class="date">
				{date} [f:blog:comments.page.in] {time}
			</div>
		</div>
	</div>
	[/foreach][/if][if num="0"]
	<div class="post-nope">
		<i class="mdi mdi-comment-remove-outline"></i>
		[f:blog:comments.page.noRows]
	</div>
	[/if]
</div>

[if num!="0"]{pagination}[/if]
