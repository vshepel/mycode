<?php include "user.tabs.php"; ?>
<?php include "user.edit.tabs.php"; ?>

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_lang("user", "fields.name")?>:</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="edit[name]" value="<?=$tags["name"]?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_lang("user", "fields.gender")?>:</label>
		<div class="col-sm-9">
			<select class="form-control" name="edit[gender]">
				<?=$tags["gender-options"]?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_lang("user", "fields.birth")?>:</label>
		<div class="col-sm-3 col-md-3">
			<select class="form-control" name="edit[day]">
				<option value="0"><?=$this->_flang("user", "edit.personal.form.birth.day")?></option>
				<?=$tags["day-options"]?>
			</select>
		</div>

		<div class="col-sm-3 col-md-3">
			<select class="form-control" name="edit[mouth]">
				<option value="0"><?=$this->_flang("user", "edit.personal.form.birth.mouth")?></option>
				<?=$tags["mouth-options"]?>
			</select>
		</div>

		<div class="col-sm-3 col-md-3">
			<select class="form-control" name="edit[year]">
				<option value="0"><?=$this->_flang("user", "edit.personal.form.birth.year")?></option>
				<?=$tags["year-options"]?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_lang("user", "fields.location")?>:</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="edit[location]" value="<?=$tags["location"]?>">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_lang("user", "fields.url")?>:</label>
		<div class="col-sm-9">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">http://</span>
				<input class="form-control" type="text" name="edit[url]" value="<?=$tags["url"]?>">
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_lang("user", "fields.publicEmail")?>:</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="edit[public_email]" value="<?=$tags["public-email"]?>">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_lang("user", "fields.lang")?>: *</label>
		<div class="col-sm-9">
			<select class="form-control" name="edit[lang]">
				<option value=""><?=$this->_lang("user", "fields.lang")?></option>
				<?php foreach ($tags["langs"] as $row): ?>
					<option value="<?=$row["value"]?>"<?=$row["active"] ? " selected" : ""?>><?=$row["name"]?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	
	<?php if($tags["group-change"]): ?><div class="form-group">
		<label class="control-label col-sm-3"><?=$this->_lang("user", "fields.group")?>: *</label>
		<div class="col-sm-9">
			<select class="form-control" name="edit[group]"><?php foreach ($tags["groups"] as $row): ?>
				<option value="<?=$row["value"]?>"<?=$row["active"] ? " selected" : ""?>><?=$row["name"]?></option>
			<?php endforeach; ?></select>
		</div>
	</div><?php endif; ?>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> <?=$this->_flang("user", "edit.main.form.submit")?></button>
		</div>
	</div>
</form>
