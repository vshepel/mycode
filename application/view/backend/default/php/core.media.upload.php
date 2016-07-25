<ul class="nav nav-tabs" role="tablist">
	<li><a href="<?=ADMIN_PATH?>core/media/list"><span class="glyphicon glyphicon-picture"></span> <?=$this->_lang("core", "media.list.moduleName")?></a></li>
	<li class="active"><a href="<?=ADMIN_PATH?>core/media/upload"><span class="glyphicon glyphicon-upload"></span> <?=$this->_lang("core", "media.upload.moduleName")?></a></li>
</ul><br>

<form enctype="multipart/form-data" method="post" class="form-horizontal">
	<input type="hidden" name="edit[avatar]">

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "media.upload.form.upload")?>:</label>
		<div class="col-sm-9">
			<input name="file" type="file">
			<small><?=$this->_flang("core", "media.upload.maxFilesize")?>: <b><?=$tags["max-filesize"]?></b></small>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-upload"></span> <?=$this->_flang("core", "media.upload.form.submit")?></button>
		</div>
	</div>
</form>
