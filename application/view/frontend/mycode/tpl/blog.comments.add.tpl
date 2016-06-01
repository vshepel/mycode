<form method="post" class="add_form" onsubmit="app.blog.addComment(this); return false;">
	<input type="hidden" name="post" value="{post-id}">
	<textarea name="comment" rows="1" placeholder="[f:blog:comments.form.comment]" data-autoresize>{comment}</textarea>
	<button><i class="mdi mdi-send"></i></button>
</form>
