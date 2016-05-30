[include "blog.tabs"]

<form method="post" class="form-horizontal">
	<h3 align="center">[f:blog:settings.posts]</h3>

	<div class="form-group">
		<label class="control-label col-sm-5">[f:blog:settings.posts.editor]</label>
		<div class="col-sm-7">
			<select class="form-control" name="posts_editor">{posts-editors}</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-5">[f:blog:settings.posts.ratingActive]</label>
		<div class="col-sm-7">
			<input class="form-control" type="checkbox" name="posts_rating_active"[posts-rating-active] checked[/posts-rating-active]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-5">[f:blog:settings.posts.postsSwitching]</label>
		<div class="col-sm-7">
			<input class="form-control" type="checkbox" name="posts_switching"[posts-switching] checked[/posts-switching]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-5">[f:blog:settings.posts.smartViews]</label>
		<div class="col-sm-7">
			<input class="form-control" type="checkbox" name="posts_advanced_views"[posts-advanced-views] checked[/posts-advanced-views]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-5">[f:blog:settings.posts.readMark]</label>
		<div class="col-sm-7">
			<input class="form-control" type="checkbox" name="posts_read_mark"[posts-read-mark] checked[/posts-read-mark]>
		</div>
	</div>

	<h3 align="center">[f:blog:settings.comments]</h3>

	<div class="form-group">
		<label class="control-label col-sm-5">[f:blog:settings.comments.interval]</label>
		<div class="col-sm-7">
			<input class="form-control" type="number" name="comments_interval" value="{comments-interval}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-5">[f:blog:settings.comments.lengthMin]</label>
		<div class="col-sm-7">
			<input class="form-control" type="number" name="comments_length_min" value="{comments-length-min}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-5">[f:blog:settings.comments.lengthMax]</label>
		<div class="col-sm-7">
			<input class="form-control" type="number" name="comments_length_max" value="{comments-length-max}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-5"></label>
		<div class="col-sm-7">
			<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> [f:blog:settings.submit]</button>
		</div>
	</div>
</form>
