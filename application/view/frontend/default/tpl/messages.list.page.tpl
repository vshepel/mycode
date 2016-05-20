<ul class="nav nav-tabs" role="tablist">
	<li [if type="inbox"]class="active"[/if]><a href="{PATH}messages/inbox"><span class="glyphicon glyphicon-save"></span> [b:messages:inbox.moduleName]</a></li>
	<li [if type="outbox"]class="active"[/if]><a href="{PATH}messages/outbox"><span class="glyphicon glyphicon-open"></span> [b:messages:outbox.moduleName]</a></li>
	<li><a href="{PATH}messages/send"><span class="glyphicon glyphicon-send"></span> [b:messages:send.moduleName]</a></li>
</ul><br>


[if num!="0"]<table class="table">
	<thead><tr>
		<th>[f:messages:list.table.topic]</th>
		<th>[f:messages:list.table.message]</th>
		<th>[f:messages:list.table.from]</th>
		<th>[f:messages:list.table.to]</th>
		<th>[f:messages:list.table.date]</th>
		<th style="width: 40px;"></th>
	</tr></thead>
	<tbody>
		{rows}
	</tbody>
</table>

{pagination}[/if][if num="0"]<div class="alert alert-info" role="alert">
	[f:messages:list.noRows]
</div>[/if]
