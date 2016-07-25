[include "user.tabs"]

[if num!="0"]<table class="table">
	<thead><tr>
		<th style="width: 40px">#</th>
		<th style="width: 40px">[b:user:fields.avatar]</th>
		<th>[b:user:fields.login]</th>
		<th>[b:user:fields.name]</th>
		<th>[b:user:fields.group]</th>
		<th style="width: 80px;"></th>
	</tr></thead>
	<tbody>
		[foreach rows]
			<tr>
				<td>{id}</td>
				<td align="center" valign="center"><a href="{profile-link}"><img src="{avatar-link}" class="media-object img-rounded" style="width:40px"></a></td>
				<td><a href="{profile-link}">{username}</a> <span class="glyphicon glyphicon-globe" [online]style="color: green"[/online][offline]style="color: red"[/offline]"></span></td>
				<td>{name}</td>
				<td>{group}</td>
				<td style="padding: 3px">
					<div class="btn-group btn-group-sm btn-group-justified">
						[if has-permission:user.edit]<a href="{edit-link}" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>[/if]
						[if has-permission:user.remove]<a href="{remove-link}" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a>[/if]
					</div>
				</td>
			</tr>
		[/foreach]
	</tbody>
</table>

{pagination}[/if][if num="0"]<div class="alert alert-info" role="alert">
	[f:user:list.noRows]
</div>[/if]
