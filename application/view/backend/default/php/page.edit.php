<script src="<?=PATH?>vendor/tinymce/tinymce.min.js"></script>

<?php include "page.tabs.php"; ?>

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("page", "edit.form.title")?> *</label>
		<div class="col-sm-9">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1"><span class="fa fa-header"></span></span>
				<input class="form-control" type="text" name="name" value="<?=$tags["name"]?>">
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">URL *</label>
		<div class="col-sm-9">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1"><span class="fa fa-link"></span></span>
				<input class="form-control" type="text" name="url" value="<?=$tags["url"]?>">
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-12">
			<textarea class="form-control" id="editor" name="text" rows="6"><?=$tags["text"]?></textarea>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("page", "edit.form.lang")?></label>
		<div class="col-sm-9">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1"><span class="fa fa-language"></span></span>
				<select class="form-control" name="lang"><?php foreach ($tags["langs"] as $lang): ?>
					<option value="<?=$lang["id"]?>"<?=$lang["current"] ? " selected" : ""?>><?=$lang["name"]?></option>
				<?php endforeach; ?></select>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-success"><span class="fa fa-save"></span> <?=$this->_flang("page", "edit.form.submit")?></button>
			<a href="<?=$tags["list-link"]?>"  class="btn btn-primary"><span class="fa fa-remove"></span> <?=$this->_flang("page", "edit.link.cancel")?></a>
			<a href="<?=$tags["remove-link"]?>" class="btn btn-danger"><span class="fa fa-trash"></span> <?=$this->_flang("page", "edit.link.remove")?></a>
		</div>
	</div>
</form>

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
		'<?=PATH?>vendor/bootstrap/css/bootstrap.min.css',
	],
	language: '<?=$this->_flang("page", "edit.tinymce.lang")?>'
}); 
</script>
