[include "user.edit.tabs"]

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.name]:</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="edit[name]" value="{name}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.gender]:</label>
		<div class="col-sm-9">
			<select class="form-control" name="edit[gender]">
				{gender-options}
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.birth]:</label>
		<div class="col-sm-3 col-md-3">
			<select class="form-control" name="edit[day]">
				<option value="0">[f:user:edit.personal.form.birth.day]</option>
				{day-options}
			</select>
		</div>

		<div class="col-sm-3 col-md-3">
			<select class="form-control" name="edit[mouth]">
				<option value="0">[f:user:edit.personal.form.birth.mouth]</option>
				{mouth-options}
			</select>
		</div>

		<div class="col-sm-3 col-md-3">
			<select class="form-control" name="edit[year]">
				<option value="0">[f:user:edit.personal.form.birth.year]</option>
				{year-options}
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.location]:</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="edit[location]" value="{location}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.url]:</label>
		<div class="col-sm-9">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">http://</span>
				<input class="form-control" type="text" name="edit[url]" value="{url}">
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.publicEmail]:</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="edit[public_email]" value="{public-email}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.lang]: *</label>
		<div class="col-sm-9">
			<select class="form-control" name="edit[lang]">
				<option value="">[b:user:fields.lang]</option>
				[foreach langs]<option value="{value}"[active] selected[/active]>{name}</option>[/foreach]
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-floppy-disk"></span> [f:user:edit.main.form.submit]</button>
		</div>
	</div>
</form>
