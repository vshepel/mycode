<?php include "user.tabs.php"; ?>

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_lang("user", "fields.email")?>: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="add[email]" value="<?=$tags["email"]?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_lang("user", "fields.login")?>: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="add[login]" value="<?=$tags["login"]?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_lang("user", "fields.password")?>: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="add[password]">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_lang("user", "fields.passwordRetype")?>: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="add[password_2]">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_lang("user", "fields.name")?>:</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="add[name]" value="<?=$tags["name"]?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><?=$this->_flang("user", "add.form.submit")?></button>
		</div>
	</div>
</form>
