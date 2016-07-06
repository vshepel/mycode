[include "blog.tabs"]

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:add.form.title] *</label>
		<div class="col-sm-9">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1"><span class="fa fa-header"></span></span>
				<input class="form-control" type="text" name="title" value="{title}">
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">URL</label>
		<div class="col-sm-9">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1"><span class="fa fa-link"></span></span>
				<input class="form-control" type="text" name="url" value="{url}">
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:add.form.category] *</label>
		<div class="col-sm-9">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1"><span class="fa fa-folder"></span></span>
				<select class="form-control" name="category">[foreach categories]
					<option value="{id}"[current] selected[/current]>{name}</option>
					[/foreach]</select>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-12">
			<textarea class="form-control" name="text" id="editor" rows="6">{text}</textarea>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:add.form.imageLink]</label>
		<div class="col-sm-9">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1"><span class="fa fa-image"></span></span>
				<input class="form-control" type="text" name="image_link" value="{image-link}">
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:add.form.tags]</label>
		<div class="col-sm-9">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1"><span class="fa fa-tags"></span></span>
				<input class="form-control" type="text" name="tags" value="{tags}">
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:add.form.lang]</label>
		<div class="col-sm-9">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1"><span class="fa fa-language"></span></span>
				<select class="form-control" name="lang">[foreach langs]
					<option value="{id}"[current] selected[/current]>{name}</option>
					[/foreach]</select>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:add.form.allowComments]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="allow_comments"[allow-comments] checked[/allow-comments]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:add.form.show]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="show"[show] checked[/show]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:add.form.showOnMain]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="show_main"[show-main] checked[/show-main]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:blog:add.form.showOnCategory]</label>
		<div class="col-sm-9">
			<input class="form-control" type="checkbox" name="show_category"[show-category] checked[/show-category]>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="fa fa-plus"></span> [f:blog:add.form.submit]</button>
		</div>
	</div>
</form>

[if editor="HTML"]
<script src="{PATH}vendor/tinymce/tinymce.min.js"></script>

<script>
	tinymce.init({
		selector:'#editor',
		theme: 'modern',
		plugins: [
			'advlist autolink lists link image charmap print preview hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen',
			'insertdatetime media nonbreaking save table contextmenu directionality',
			'emoticons template paste textcolor colorpicker textpattern imagetools'
		],
		toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect | bullist numlist | forecolor backcolor emoticons",
		toolbar2: "outdent indent | undo redo | link unlink anchor image media | hr table | subscript superscript | charmap | print preview code",
		image_advtab: true,
		content_css: [
			'{PATH}vendor/bootstrap/css/bootstrap.min.css',
		],
		language: '[f:page:edit.tinymce.lang]'
	});
</script>

[/if][if editor="BBCode"]
<script src="{PATH}vendor/wysibb/jquery.wysibb.min.js"></script>
<link href="{PATH}vendor/wysibb/theme/default/wbbtheme.css" type="text/css" rel="stylesheet">

<script>
	$(function() {
		$('#editor').wysibb();
	})
</script>
[/if][if editor="Markdown"]
<link rel="stylesheet" href="{PATH}vendor/codemirror/lib/codemirror.css">
<script src="{PATH}vendor/codemirror/lib/codemirror.js"></script>
<script src="{PATH}vendor/codemirror/mode/markdown.js"></script>

<script>
	var editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
		lineNumbers: true,
		mode: 'markdown'
	});
</script>
[/if]