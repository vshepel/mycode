<ul class="nav nav-tabs" role="tablist">
	<li><a href="{PATH}messages/inbox"><span class="glyphicon glyphicon-save"></span> [b:messages:inbox.moduleName]</a></li>
	<li><a href="{PATH}messages/outbox"><span class="glyphicon glyphicon-open"></span> [b:messages:outbox.moduleName]</a></li>
	<li><a href="{PATH}messages/send"><span class="glyphicon glyphicon-send"></span> [b:messages:send.moduleName]</a></li>
</ul><br>

<dl class="dl-horizontal">
	<dt>[f:messages:read.from]:</dt> <dd><a href="{from-link}">{from-login}</a></dd>
	<dt>[f:messages:read.to]:</dt> <dd><a href="{to-link}">{to-login}</a></dd>
	<dt>[f:messages:read.date]:</dt> <dd>{date}, {time}</dd>
	<hr>
	<dt>[f:messages:read.topic]:</dt> <dd>{topic}</dd>
	<dt>[f:messages:read.message]:</dt> <dd>{message}</dd>
	[remove]<br><dt></dt>
	<dd><a href="{remove-link}" class="btn btn-sm btn-danger">
		<span class="glyphicon glyphicon-trash"></span> [f:messages:read.remove]
	</a></dd>[/remove]
</dl>
