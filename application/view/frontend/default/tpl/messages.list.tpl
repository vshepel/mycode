<ul class="nav nav-tabs" role="tablist">
	<li [if type="inbox"]class="active"[/if]><a href="{PATH}messages/inbox"><span class="glyphicon glyphicon-save"></span> [b:messages:inbox.moduleName] <span class="badge">{new-count}</span></a></li>
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
		[foreach rows]
		<tr[not-readed] class="active"[/not-readed]>
			<td>{topic}</td>
			<td><a href="{url}">{message}</a></td>
			<td>
				<img class="img-circle" src="{from-avatar-link}" alt="{from-login}" style="max-width: 20px">
				<a href="{from-link}">{from-login}</a>
			</td>
			<td>
				<img class="img-circle" src="{to-avatar-link}" alt="{to-login}" style="max-width: 20px">
				<a href="{to-link}">{to-login}</a>
			</td>
			<td>{date}, {time}</td>
			<td style="padding: 3px">
				<div class="btn-group btn-group-sm btn-group-justified">
					[remove]<a href="{remove-link}" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a>[/remove]
				</div>
			</td>
		</tr>
		[/foreach]
	</tbody>
</table>

{pagination}[/if][if num="0"]<div class="alert alert-info" role="alert">
	[f:messages:list.noRows]
</div>[/if]
