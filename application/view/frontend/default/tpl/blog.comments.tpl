<div id="blog-comments">
	[can-add]<h3>[f:blog:comments.form]</h3>

	<form method="post" class="form-horizontal" onsubmit="app.blog.addComment(this); return false;">
		<input type="hidden" name="post" value="{post-id}">

		<div class="form-group">
			<label class="control-label col-sm-3">[f:blog:comments.form.comment]</label>
			<div class="col-sm-9">
				<textarea class="form-control" name="comment" rows="5">{comment}</textarea>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-3"></label>
			<div class="col-sm-9">
				<button class="btn btn-primary">[f:blog:comments.form.submit]</button>
			</div>
		</div>
	</form>[/can-add][cant-add]<div class="alert alert-danger">
		[b:blog:comments.cantAdd]
	</div>[/cant-add]

	<span class="fa fa-comment"></span> [f:blog:comments.list.total] <span class="badge">{num}</span><br><br>

	[if num!="0"][foreach rows]
	<div class="media">
		<a class="pull-left" href="{author-link}">
			<img class="media-object img-rounded" src="{author-avatar-link}" alt="{author-login}" style="max-width: 50px">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="{author-link}">{author-login}</a> <small>[f:blog:comments.comment.wrote] {date} [f:blog:comments.comment.at] {time}</small></h4>
			[remove]<form method="post" class="form-horizontal" onsubmit="app.blog.removeComment(this); return false;">
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