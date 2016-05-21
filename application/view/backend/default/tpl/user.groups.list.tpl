[include "user.tabs"]

[if num!="0"]<table class="table">
	<thead><tr>
		<th style="width: 40px">#</th>
		<th>[f:user:groups.list.table.title]</th>
		<th>[f:user:groups.list.table.extends]</th>
		<th style="width: 90px;"></th>
	</tr></thead>
	<tbody>
		[foreach rows]
			<tr>
				<td>{id}</td>
				<td><a href="{edit-link}">{name}</a></td>
				<td>{extends}</td>
				<td style="padding: 3px">
					<div class="btn-group btn-group-sm btn-group-justified">
						[if has-permission:user.groups.edit]<a href="{edit-link}" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>[/if]
						[if has-permission:user.groups.remove][remove]<a href="{remove-link}" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a>[/remove][/if]
					</div>
				</td>
			</tr>
		[/foreach]

		[if has-permission:user.groups.add]<tr><form method="post">
			<td colspan="3" style="padding: 3px">
				<input type="text" class="form-control input-sm" name="add[name]">
			</td>
			<td style="padding: 3px">
				<button class="btn btn-primary btn-sm" style="width:100%"><span class="glyphicon glyphicon-ok"></span></button>
			</td>

		</form></tr>[/if]
	</tbody>
</table>

{pagination}[/if][if num="0"]<div class="alert alert-info" role="alert">
	[f:user:groups.list.noRows]
</div>[/if]
