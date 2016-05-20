[f:user:sessions.total] <span class="badge">{num}</span>

<hr>

<table class="table">
	<thead><tr>
		<th style="width: 40px">#</th>
		<th>[f:user:sessions.table.client]</th>
		<th>[f:user:sessions.table.ip]</th>
		<th>[f:user:sessions.table.createDate]</th>
		<th style="width: 90px;"></th>
	</tr></thead>
	<tbody>
	[foreach rows]
		<tr>
			<td>{id}</td>
			<td>{browser}, {os}</td>
			<td>{ip}</td>
			<td>{create-date} в {create-time}</td>
			<td>
				[not-current]<form method="post">
					<input type="hidden" name="id" value="{id}" />
					<button class="btn btn-xs btn-danger" type="submit"><span class="glyphicon glyphicon-remove"></span> [f:user:sessions.row.close]</button>
				</form>[/not-current][current]([f:user:sessions.row.use])[/current]
			</td>
		</tr>
	[/foreach]
	</tbody>
</table>

{pagination}
