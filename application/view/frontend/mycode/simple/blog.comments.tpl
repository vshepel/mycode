<div id="blog-comments">
	[can-add]
	<form method="post" class="add_form" onsubmit="app.blog.addComment(this); return false;">
		<input type="hidden" name="post" value="{post-id}">
		<input type="hidden" name="reply" value="0" id="blog-reply-id">
		<textarea id="comment-textarea" name="comment" rows="1" placeholder="[f:blog:comments.form.comment]" data-autoresize>{comment}</textarea>
		<button><i class="mdi mdi-send"></i></button>
	</form>
	<div class="comments_reply">
		<a id="blog-reply-remove" href="#" onclick="app.blog.replyRemove(); return false;"><span id="blog-reply-user"></span> <div class="mdi mdi-close-circle"></div></a>
	</div>
	<script>
		$(document).ready(function(){
			$('#comment-textarea').keydown(function(e) {
				if (e.ctrlKey && e.keyCode == 13) {
					app.blog.addComment($('.add_form'));
				}
			});
		});
	</script>
	[/can-add]

	<div id="comments">
		[if num!="0"][foreach rows]
		<div class="comments_item" id="comment_{id}">
			<div class="item-photo">
				<a href="{author-link}">
					<img src="{author-avatar-link}" alt="{author-login}" />
				</a>
			</div>
			<div class="item-text">
				<a href="{author-link}">{author-login}</a> [f:blog:comments.page.says]...
				<div class="text">
					{comment-message}
				</div>
				<div class="date">
					{date} [f:blog:comments.page.in] {time} <span>[can-add]<a href="#" onclick="app.blog.replyComment({id}, '{author-login}'); return false"><i class="mdi mdi-reply"></i> [f:blog:comments.page.reply]</a>[/can-add][remove]<form method="post" onsubmit="app.blog.removeComment(this); return false;"><input type="hidden" name="removecomment[id]" value="{id}"><input type="hidden" name="removecomment[post]" value="{post-id}"><input type="hidden" name="removecomment[page]" value="{page}"><button><i class="mdi mdi-close-circle"></i> [f:blog:comments.page.delete]</button></form>[/remove]</span>
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