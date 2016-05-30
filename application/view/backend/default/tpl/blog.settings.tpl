[include "blog.tabs"]

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:settings.editor]</label>
		<div class="col-sm-9">
			<select class="form-control" name="editor">{editors}</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:settings.ratingActive]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="rating_active"[rating-active] checked[/rating-active]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:settings.smartViews]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="advanced_views"[advanced-views] checked[/advanced-views]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:settings.postsSwitching]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="posts_switching"[posts-switching] checked[/posts-switching]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> [f:blog:settings.submit]</button>
		</div>
	</div>
</form>
