[include "blog.tabs"]

<div class="row">
	<div class="col-sm-3">
		<ul class="nav nav-pills nav-stacked">{blog:categories}</ul>
	</div>
	<div class="col-sm-9">
		[if num!="0"][f:blog:list.total] <span class="badge">{num}</span><br><br>

		<table class="table">
			<thead><tr>
				<th style="width: 40px">#</th>
				<th>[f:blog:list.table.title]</th>
				<th>[f:blog:list.table.category]</th>
				<th>[f:blog:list.table.date]</th>
				<th>[f:blog:list.table.author]</th>
				<th align="center" style="width: 50px;"><span class="glyphicon glyphicon-comment"></span></th>
				<th align="center" style="width: 50px;"><span class="glyphicon glyphicon-eye-open"></span></th>
				<th style="width: 80px;"></th>
			</tr></thead>
			<tbody>[foreach rows]
				<tr>
					<td>{id}</td>
					<td><a href="{link}">{title}</a>[not-show] <span class="glyphicon glyphicon-eye-close">[/not-show]</td>
					<td><a href="{category-link}">{category-name}</a>[not-show-category] <span class="glyphicon glyphicon-eye-close">[/not-show-category]</td>
					<td>{date}, {time}</td>
					<td><a href="{author-link}"><img src="{author-avatar-link}" class="img-circle" style="height:24px"></a> <a href="{author-link}">{author-login}</a></td>
					<td>{comments-num}</td>
					<td>{views-num}</td>
					<td style="padding: 3px">
						<div class="btn-group btn-group-sm btn-group-justified">
							<a href="{edit-link}" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="{remove-link}" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
						</div>
					</td>
				</tr>
			[/foreach]</tbody>
		</table>

		{pagination}[/if][if num="0"]<div class="alert alert-info" role="alert">
			[f:blog:list.noRows]
		</div>[/if]
	</div>
</div>