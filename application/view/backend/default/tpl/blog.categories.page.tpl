[include "blog.tabs"]

[f:blog:categories.total] <span class="badge">{num}</span><br><br>

<table class="table">
	<thead><tr>
		<th style="width: 40px">#</th>
		<th>[f:blog:categories.table.title]</th>
		<th style="width: 100px;">[f:blog:categories.table.rows]</th>
		<th style="width: 20px;"></th>
	</tr></thead>
	<tbody>
		{categories}

		<tr><form method="post">

			<td colspan="3" style="padding: 3px">
				<input type="text" class="form-control input-sm" name="category_add[name]">
			</td>
			<td style="padding: 3px">
				<button class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-ok"></span></button>
			</td>

		</form></tr>
	</tbody>
</table>
