<div class="block">
	<div class="title">[f:user:sessions.title]</div>
	<div class="block-content hastable">
		<table>
			<thead>
				<tr>
					<th>[f:user:sessions.table.client]</th>
					<th>[f:user:sessions.table.ip]</th>
					<th>[f:user:sessions.table.createDate]</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				[foreach rows]
				<tr>
					<td data-head="[f:user:sessions.table.client]">{browser}, {os}</td>
					<td data-head="[f:user:sessions.table.ip]">{ip}</td>
					<td data-head="[f:user:sessions.table.createDate]">{create-date} в {create-time}</td>
					<td>
						[not-current]<form method="post"><input type="hidden" name="id" value="{id}" /><button type="submit"><i class="mdi mdi-close"></i></button></form>[/not-current][current][f:user:sessions.row.use][/current]
					</td>
				</tr>
				[/foreach]
			</tbody>
		</table>
	</div>
</div>