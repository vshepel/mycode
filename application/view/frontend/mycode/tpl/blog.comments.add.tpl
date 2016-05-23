<h3>[f:blog:comments.form]</h3>

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
</form>
