<script src="{PATH}vendor/wysibb/jquery.wysibb.min.js"></script>
<link href="{PATH}vendor/wysibb/theme/default/wbbtheme.css" type="text/css" rel="stylesheet">

[include "blog.tabs"]

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:edit.form.title] *</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="title" value="{title}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">URL</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="url" value="{url}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:edit.form.category] *</label>
		<div class="col-sm-9">
			<select class="form-control" name="category">[foreach categories]
				<option value="{id}"[current] selected[/current]>{name}</option>
			[/foreach]</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:edit.form.text] *</label>
		<div class="col-sm-9">
			<textarea class="form-control" name="text" id="editor" rows="6">{text}</textarea>
		</div>
	</div>


	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:edit.form.tags]</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="tags" value="{tags}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:edit.form.lang]</label>
		<div class="col-sm-9">
			<select class="form-control" name="lang">[foreach langs]
				<option value="{id}"[current] selected[/current]>{name}</option>
			[/foreach]</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:edit.form.allowComments]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="allow_comments"[allow-comments] checked[/allow-comments]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:edit.form.show]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="show"[show] checked[/show]>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:edit.form.showOnMain]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="show_main"[show-main] checked[/show-main]>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:edit.form.showOnCategory]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="show_category"[show-category] checked[/show-category]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary">[f:blog:edit.form.submit]</button>
		</div>
	</div>
</form>

<script>
	$(function() {
		$('#editor, #editor-full').wysibb();
	})
</script>
