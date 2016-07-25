<?php include "blog.tabs.php"; ?>

<form method="post" class="form-horizontal">
	<h3 align="center"><span class="fa fa-home"></span> <?=$this->_flang("blog", "settings.main")?></h3>

	<div class="form-group">
		<label class="control-label col-sm-6"><?=$this->_flang("blog", "settings.main.notEmptyCategories")?></label>
		<div class="col-sm-6">
			<input class="form-control" type="checkbox" name="main_not_empty_categories"<?=($tags["not-empty-categories"]) ? " checked" : ""?>>
		</div>
	</div>

	<h3 align="center"><span class="fa fa-newspaper-o"></span> <?=$this->_flang("blog", "settings.posts")?></h3>

	<div class="form-group">
		<label class="control-label col-sm-6"><?=$this->_flang("blog", "settings.posts.editor")?></label>
		<div class="col-sm-6">
			<select class="form-control" name="posts_editor"><?=$tags["posts-editors"]?></select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-6"><?=$this->_flang("blog", "settings.posts.ratingActive")?></label>
		<div class="col-sm-6">
			<input class="form-control" type="checkbox" name="posts_rating_active"<?=($tags["posts-rating-active"]) ? " checked" : ""?>>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-6"><?=$this->_flang("blog", "settings.posts.postsSwitching")?></label>
		<div class="col-sm-6">
			<input class="form-control" type="checkbox" name="posts_switching"<?=($tags["posts-switching"]) ? " checked" : ""?>>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-6"><?=$this->_flang("blog", "settings.posts.smartViews")?></label>
		<div class="col-sm-6">
			<input class="form-control" type="checkbox" name="posts_advanced_views"<?=($tags["posts-advanced-views"]) ? " checked" : ""?>>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-6"><?=$this->_flang("blog", "settings.posts.readMark")?></label>
		<div class="col-sm-6">
			<input class="form-control" type="checkbox" name="posts_read_mark"<?=($tags["posts-read-mark"]) ? " checked" : ""?>>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-6"><?=$this->_flang("blog", "settings.posts.related")?></label>
		<div class="col-sm-6">
			<input class="form-control" type="checkbox" name="posts_related"<?=($tags["posts-related"]) ? " checked" : ""?>>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-6"><?=$this->_flang("blog", "settings.posts.onlyLocalLanguage")?></label>
		<div class="col-sm-6">
			<input class="form-control" type="checkbox" name="posts_only_local_language"<?=($tags["posts-only-local-language"]) ? " checked" : ""?>>
		</div>
	</div>

	<h3 align="center"><span class="fa fa-comment"></span> <?=$this->_flang("blog", "settings.comments")?></h3>

	<div class="form-group">
		<label class="control-label col-sm-6"><?=$this->_flang("blog", "settings.comments.interval")?></label>
		<div class="col-sm-6">
			<input class="form-control" type="number" name="comments_interval" value="<?=$tags["comments-interval"]?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-6"><?=$this->_flang("blog", "settings.comments.lengthMin")?></label>
		<div class="col-sm-6">
			<input class="form-control" type="number" name="comments_length_min" value="<?=$tags["comments-length-min"]?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-6"><?=$this->_flang("blog", "settings.comments.lengthMax")?></label>
		<div class="col-sm-6">
			<input class="form-control" type="number" name="comments_length_max" value="<?=$tags["comments-length-max"]?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-6"></label>
		<div class="col-sm-6">
			<button class="btn btn-primary"><span class="fa fa-floppy-disk"></span> <?=$this->_flang("blog", "settings.submit")?></button>
		</div>
	</div>
</form>
