[include "page.tabs"]

[if num!="0"][f:page:list.total] <span class="badge">{num}</span><br><br>

<table class="table">
	<thead><tr>
		<th style="width: 40px">#</th>
		<th>[f:page:list.table.title]</th>
		<th>URL</th>
		<th style="width: 80px;"></th>
	</tr></thead>
	<tbody>
		[foreach rows]
			<tr>
				<td>{id}</td>
				<td><a href="{page-link}">{name}</a></td>
				<td>{url}</td>
				<td style="padding: 3px">
					<div class="btn-group btn-group-sm btn-group-justified">
						[if has-permission:page.edit]<a href="{edit-link}" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>[/if]
						[if has-permission:page.remove]<a href="{remove-link}" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a>[/if]
					</div>
				</td>
			</tr>
		[/foreach]
	</tbody>
</table>

{pagination}[/if][if num="0"]<div class="alert alert-info" role="alert">
	[f:page:list.noRows]
</div>[/if]
