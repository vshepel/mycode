<h3>[b:user:register.moduleName]</h3>

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.email]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="email" name="register[email]" value="{email}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.login]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="register[login]" value="{login}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.password]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="register[password]">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.passwordRetype]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="register[password_2]">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[b:user:fields.name]:</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="register[name]" value="{name}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"><img id="captcha" src="{captcha-link}" alt="Captcha"><a href="#" onclick="getElementById('captcha').src='{captcha-link}?'+ new Date().getTime()"><span class="fa fa-refresh"></span></a></label>
		<div class="col-sm-9">
			<input class="form-control" type="text" maxlength="4" name="register[captcha]" style="width:140px;height:80px;font-size:28pt">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> [f:user:register.form.submit]</button>
		</div>
	</div>
</form>
