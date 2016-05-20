[include "page.tabs"]

<form method="post" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-3">[f:page:settings.page]</label>
		<div class="col-sm-9">
			<select class="form-control" name="page">{page}</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> [f:core:settings.submit]</button>
		</div>
	</div>
</form>
