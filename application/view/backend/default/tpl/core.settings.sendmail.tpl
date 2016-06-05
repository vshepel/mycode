[include "core.settings.tabs"]

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.sendmail.driver]</label>
		<div class="col-sm-9">
			<select class="form-control" name="drivers">{drivers}</select>
		</div>
	</div>

	<h3 align="center"><span class="fa fa-envelope"></span> SMTP</h3>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.sendmail.smtp.name]</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="driver_SMTP[name]" value="{smtp-name}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.sendmail.smtp.user]</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="driver_SMTP[user]" value="{smtp-user}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.sendmail.smtp.password]</label>
		<div class="col-sm-9">
			<input class="form-control" type="password" name="driver_SMTP[password]" value="{smtp-password}">
		</div>           
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.sendmail.smtp.host]</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="driver_SMTP[host]" value="{smtp-host}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:core:settings.sendmail.smtp.port]</label>
		<div class="col-sm-9">
			<input class="form-control" type="text" name="driver_SMTP[port]" value="{smtp-port}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> [f:core:settings.submit]</button>
		</div>
	</div>
</form>
