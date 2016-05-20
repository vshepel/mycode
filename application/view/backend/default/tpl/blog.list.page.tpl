[include "blog.tabs"]

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
	<tbody>
		{posts}
	</tbody>
</table>

{pagination}[/if][if num="0"]<div class="alert alert-info" role="alert">
	[f:blog:list.noRows]
</div>[/if]
