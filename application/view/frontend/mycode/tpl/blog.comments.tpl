<div id="blog-comments">
	<form method="post" class="add_form" onsubmit="app.blog.addComment(this); return false;">
		<input type="hidden" name="post" value="{post-id}">
		<textarea name="comment" rows="1" placeholder="[f:blog:comments.form.comment]" data-autoresize>{comment}</textarea>
		<button><i class="mdi mdi-send"></i></button>
	</form>

	<div id="comments">
		[if num!="0"][foreach rows]
		<div class="comments_item">
			<div class="item-photo">
				<a class="pull-left" href="{author-link}">
					<img src="{author-avatar-link}" alt="{author-login}">
				</a>
				[remove]<div class="pull-left" align="center"><form method="post" onsubmit="app.blog.removeComment(this); return false;">
					<input type="hidden" name="removecomment[id]" value="{id}">
					<input type="hidden" name="removecomment[post]" value="{post-id}">
					<input type="hidden" name="removecomment[page]" value="{page}">
					<button style="background:none;border:0;color:#999;padding:0"><i class="mdi mdi-close-circle"></i></button>
				</form></div>[/remove]
			</div>
			<div class="item-text">
				<a href="{author-link}">{author-login}</a> [f:blog:comments.page.says]...
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
</div>
