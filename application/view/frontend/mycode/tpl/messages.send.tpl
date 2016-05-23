<ul class="nav nav-tabs" role="tablist">
	<li><a href="{PATH}messages/inbox"><span class="glyphicon glyphicon-save"></span> [b:messages:inbox.moduleName]</a></li>
	<li><a href="{PATH}messages/outbox"><span class="glyphicon glyphicon-open"></span> [b:messages:outbox.moduleName]</a></li>
	<li class="active"><a href="{PATH}messages/send"><span class="glyphicon glyphicon-send"></span> [b:messages:send.moduleName]</a></li>
</ul><br>

<form enctype="multipart/form-data" method="post" class="form-horizontal">	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:messages:send.form.user]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="input" name="user" value="{user}">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">[f:messages:send.form.topic]: *</label>
		<div class="col-sm-9">
			<input class="form-control" type="input" name="topic" value="{topic}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-3">[f:messages:send.form.message]: *</label>
		<div class="col-sm-9">
			<textarea class="form-control" name="message" rows="5">{message}</textarea>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-send"></span> [f:messages:send.form.submit]</button>
		</div>
	</div>
</form>
