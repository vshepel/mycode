[include "blog.tabs"]

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><span class="fa fa-fw fa-newspaper-o"></span> [f:blog:statistics.rows]</h3>
	</div>

	<table class="table">
		<tbody>
		<tr>
			<td>[f:blog:statistics.rows.total]</td>
			<td width="20%">{posts-total}</td>
		</tr>
		<tr>
			<td>[f:blog:statistics.rows.shown]</td>
			<td width="20%">{posts-show}</td>
		</tr>
		<tr>
			<td>[f:blog:statistics.rows.comments]</td>
			<td width="20%">{posts-comments}</td>
		</tr>
		<tr[if posts-awaiting-moderation!="0"] class="info"[/if]>
			<td>[f:blog:statistics.rows.awaiting-moderation]</td>
			<td width="20%">{posts-awaiting-moderation}</td>
		</tr>
		</tbody>
	</table>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><span class="fa fa-fw fa-comment"></span> [f:blog:statistics.comments]</h3>
	</div>

	<table class="table">
		<tbody>
		<tr>
			<td>[f:blog:statistics.comments.total]</td>
			<td width="20%">{comments-total}</td>
		</tr>
		</tbody>
	</table>
</div>
