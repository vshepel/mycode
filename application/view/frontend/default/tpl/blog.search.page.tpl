<form method="post" class="form-horizontal">
	<div class="input-group">
		<input type="text" name="query" class="form-control" placeholder="[f:blog:search.search]..." value="{query}">
	  <span class="input-group-btn">
		<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
	  </span>
	</div>
</form><br>

[if num!="0"][f:blog:search.total] <span class="badge">{num}</span>

{posts}{pagination}[/if][if num="0"]
<div class="alert alert-info">
	[f:blog:search.noRows]
</div>[/if]
