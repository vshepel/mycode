[include "user.tabs"]

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[f:user:settings.guestGroup]</label>
		<div class="col-sm-9">
			<select class="form-control" name="guest_group">{groups}</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:user:settings.activeTime]</label>
		<div class="col-sm-9">
			<input class="form-control" type="number" name="active_time" value="{active-time}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:user:settings.avatarCompress]</label>
		<div class="col-sm-9">
			<input class="form-control" type="number" name="avatar_compress" min="1" max="100" value="{avatar-compress}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="fa fa-save"></span> [f:user:settings.submit]</button>
		</div>
	</div>
</form>
