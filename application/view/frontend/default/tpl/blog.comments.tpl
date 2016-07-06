<div id="blog-comments">
	[can-add]<h3>[f:blog:comments.form]</h3>

	<form method="post" id="addcomment-form" class="form-horizontal" onsubmit="app.blog.addComment(this); return false;">
		<input type="hidden" name="post" value="{post-id}">
		<input type="hidden" name="reply" value="0" id="blog-reply-id">

		<div class="form-group">
			<label class="control-label col-sm-3">[f:blog:comments.form.comment]</label>
			<div class="col-sm-9">
				<textarea id="comment-textarea" class="form-control" name="comment" rows="5">{comment}</textarea>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-3"></label>
			<div class="col-sm-9">
				<button class="btn btn-primary">[f:blog:comments.form.submit]</button>
				<span id="blog-reply-user"></span> <a id="blog-reply-remove" href="#" onclick="app.blog.replyRemove(); return false;" style="display:none"><div class="fa fa-remove"></div></a>
			</div>
		</div>
	</form>
	<script>
		$(document).ready(function(){
			$('#comment-textarea').keydown(function(e) {
				if (e.ctrlKey && e.keyCode == 13) {
					app.blog.addComment($('#addcomment-form'));
				}
			});
		});
	</script>[/can-add][cant-add]<div class="alert alert-danger">
		[b:blog:comments.cantAdd]
	</div>[/cant-add]

	<span class="fa fa-comment"></span> [f:blog:comments.list.total] <span class="badge">{num}</span><br><br>

	[if num!="0"][foreach rows]
	<div class="media" id="comment_{id}">
		<a class="pull-left" href="{author-link}">
			<img class="media-object img-rounded" src="{author-avatar-link}" alt="{author-login}" style="max-width: 50px">
		</a>
		<div class="media-body">
			<h4 class="media-heading">
				<a href="{author-link}">{author-login}</a> <small>[f:blog:comments.comment.wrote] {date} [f:blog:comments.comment.at] {time}</small>
				[can-add]<button class="btn btn-xs btn-primary" onclick="app.blog.replyComment({id}, '{author-login}')"><span class="fa fa-reply"> [f:blog:comments.comment.reply]</button>[/can-add]
			</h4>
			[remove]<form method="post" class="form-inline" onsubmit="app.blog.removeComment(this); return false;">
				<input type="hidden" name="removecomment[id]" value="{id}">
				<input type="hidden" name="removecomment[post]" value="{post-id}">
				<input type="hidden" name="removecomment[page]" value="{page}">
				<button class="btn btn-xs btn-danger"><span class="fa fa-trash"> [f:blog:comments.comment.remove]</button>
			</form>[/remove]

		</div>
		<div class="clearfix"></div>
		{comment-message}
	</div>
	[/foreach][/if][if num="0"]
	<div class="alert alert-info">
		[f:blog:comments.list.noRows]
	</div>[/if]

	<div id="blog-comments-pagination">
		[if num!="0"]{pagination}[/if]
	</div>
</div>