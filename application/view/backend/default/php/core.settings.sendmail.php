<?php include "core.settings.tabs.php"; ?>

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.sendmail.driver")?></label>
		<div class="col-sm-9">
			<select class="form-control" name="drivers"><?=$tags["drivers"]?></select>
		</div>
	</div>

	<h3 align="center"><span class="fa fa-envelope"></span> SMTP</h3>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.sendmail.smtp.name")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="driver_SMTP[name]" value="<?=$tags["smtp-name"]?>">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.sendmail.smtp.user")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="driver_SMTP[user]" value="<?=$tags["smtp-user"]?>">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.sendmail.smtp.password")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="driver_SMTP[password]" value="<?=$tags["smtp-password"]?>">
		</div>           
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.sendmail.smtp.host")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="driver_SMTP[host]" value="<?=$tags["smtp-host"]?>">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_flang("core", "settings.sendmail.smtp.port")?></label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="driver_SMTP[port]" value="<?=$tags["smtp-port"]?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> <?=$this->_flang("core", "settings.submit")?></button>
		</div>
	</div>
</form>
