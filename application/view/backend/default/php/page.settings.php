<?php include "page.tabs.php"; ?>

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("page", "settings.page")?></label>
		<div class="col-sm-9">
			<select class="form-control" name="page"><?=$tags["page"]?></select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="fa fa-floppy-o"></span> <?=$this->_flang("core", "settings.submit")?></button>
		</div>
	</div>
</form>
