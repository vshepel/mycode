[include "blog.tabs"]

[f:blog:categories.total] <span class="badge">{num}</span><br><br>

<table class="table">
	<thead><tr>
		<th style="width: 40px">#</th>
		<th>[f:blog:categories.table.title]</th>
		<th style="width: 100px;">[f:blog:categories.table.rows]</th>
		<th style="width: 75px;"></th>
	</tr></thead>
	<tbody>
		[foreach rows]<tr [edit] class="info"[/edit]"><form method="post">[not-edit]
			<input type="hidden" name="item_id" value="{id}">
			<td>[if id!="0"]{id}[/if]</td>
			<td><a href="{category-link}">{name}</a></td>
			<td>{posts-num}</td>
			<td style="padding: 3px">
				<form method="post">
					<div class="btn-group btn-group-sm">
						<button type="submit" name="edit" class="btn btn-success btn-sm[if id="0"] disabled[/if]"><span class="glyphicon glyphicon-pencil"></span></button>
						<button type="submit" name="remove" class="btn btn-danger btn-sm[if id="0"] disabled[/if]">
						<span class="glyphicon glyphicon-trash"></span>
						</button>
					</div>
				</form>
			</td>
			[/not-edit][edit]
			<input type="hidden" name="item_id" value="{id}">
			<td style="padding: 3px">
				<input type="text" class="form-control input-sm" value="{id}" disabled>
			</td>
			<td style="padding: 3px">
				<input type="text" class="form-control input-sm" name="edit[name]" value="{original-name}">
			</td>
			<td style="padding: 3px">
				<input type="text" class="form-control input-sm" value="{posts-num}" disabled>
			</td>
			<td style="padding: 3px">
				<button type="submit" class="btn btn-primary btn-sm" style="width:100%"><span class="glyphicon glyphicon-floppy-disk"></span></button>
			</td>
			[/edit]
		</form></tr>[/foreach]

		<tr><form method="post">

			<td colspan="3" style="padding: 3px">
				<input type="text" class="form-control input-sm" name="add[name]">
			</td>
			<td style="padding: 3px">
				<button class="btn btn-primary btn-sm" style="width:100%"><span class="glyphicon glyphicon-plus"></span></button>
			</td>

		</form></tr>
	</tbody>
</table>
