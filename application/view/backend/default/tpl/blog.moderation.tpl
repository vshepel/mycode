[include "blog.tabs"]

[if num!="0"][f:blog:moderation.total] <span class="badge">{num}</span><br><br>

<table class="table">
	<thead><tr>
		<th style="width: 40px">#</th>
		<th>[f:blog:moderation.table.title]</th>
		<th>[f:blog:moderation.table.category]</th>
		<th>[f:blog:moderation.table.date]</th>
		<th>[f:blog:moderation.table.author]</th>
		<th style="width: 80px;"></th>
	</tr></thead>
	<tbody>[foreach rows]
		<div class="modal fade" id="post-{id}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">{title}</h4>
					</div>
					<div class="modal-body">
						{text}
					</div>
					<div class="modal-footer">
						<a class="btn btn-success" href="{good-link}">
							<span class="glyphicon glyphicon-thumbs-up"></span> [f:blog:moderation.modal.good]
						</a>
						<a class="btn btn-danger" href="{bad-link}">
							<span class="glyphicon glyphicon-thumbs-down"></span> [f:blog:moderation.modal.bad]
						</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							[f:blog:moderation.modal.close]
						</button>
					</div>
				</div>
			</div>
		</div>

		<tr>
			<td>{id}</td>
			<td><a href="{link}">{title}</a></td>
			<td><a href="{category-link}">{category-name}</a></td>
			<td>{date}, {time}</td>
			<td><a href="{author-link}"><img src="{author-avatar-link}" class="img-circle" style="height:24px"></a> <a href="{author-link}">{author-login}</a></td>
			<td style="padding: 3px">
				<button class="btn btn-sm btn-info" data-toggle="modal" data-target="#post-{id}">
					<span class="glyphicon glyphicon-eye-open"></span> [f:blog:moderation.view]
				</button>
			</td>
		</tr>
	[/foreach]</tbody>
</table>

{pagination}[/if][if num="0"]<div class="alert alert-info" role="alert">
	[f:blog:list.noRows]
</div>[/if]
