<section class="block">
	<div class="title">[f:blog:add.title]</div>
	<div class="block-content">

	<form class="form" enctype="multipart/form-data" method="post">
			<div class="field">
				<span>[f:blog:form.title]</span>
				<input type="text" name="title" value="{title}" autofocus />
			</div>
			<div class="field">
				<span>[f:blog:form.category]</span>
				<select class="form-control" name="category">
					[foreach categories]
					<option value="{id}"[current] selected[/current]>{name}</option>
					[/foreach]
				</select>
			</div>
			<div class="field">
				<span>[f:blog:form.lang]</span>
				<select class="form-control" name="lang">
					[foreach langs]
					<option value="{id}"[current] selected[/current]>{name}</option>
					[/foreach]
				</select>
			</div>
			<div class="break"></div>
			<div class="field full">
				<span>[f:blog:form.text] <a href="#">{editor} [f:blog:form.guide]</a></span>
				<textarea name="text" id="editor" data-autoresize>{text}</textarea>
			</div>
			<div class="field full">
				<span>[f:blog:form.tags]</span>
				<input type="text" name="tags" value="{tags}" />
			</div>
			<div class="field full">
				<span>[f:blog:form.url]</span>
				<input type="text" name="url" value="{url}" />
			</div>
			<div class="panel">
				<button class="btn-primary">
					[f:blog:form.btn]
				</button>
			</div>
		</form>

	</div>
</section>

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
<link rel="stylesheet" href="{PATH}vendor/codemirror/lib/theme.css">
<script src="{PATH}vendor/codemirror/lib/codemirror.js"></script>
<script src="{PATH}vendor/codemirror/mode/markdown.js"></script>

<script>
	var editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
		lineNumbers: true,
		mode: 'markdown',
		theme: 'base16-light'
	});
</script>
[/if]