<ul class="nav nav-tabs" role="tablist">
	<li><a href="{PATH}messages/inbox"><span class="glyphicon glyphicon-save"></span> [b:messages:inbox.moduleName]</a></li>
	<li><a href="{PATH}messages/outbox"><span class="glyphicon glyphicon-open"></span> [b:messages:outbox.moduleName]</a></li>
	<li><a href="{PATH}messages/send"><span class="glyphicon glyphicon-send"></span> [b:messages:send.moduleName]</a></li>
</ul><br>

<form method="post">
	<input type="hidden" name="remove[id]" value="{id}">
	<div class="alert alert-info">
		<h4>[f:messages:remove.title]</h4>
		<p>[f:messages:remove.description]</p>

		<p>
			<button class="btn btn-danger" type="submit">[b:core:options.yes]</button>
			<a href="{PATH}messages" class="btn btn-success">[b:core:options.no]</a>
		</p>
	</div>
</form>
