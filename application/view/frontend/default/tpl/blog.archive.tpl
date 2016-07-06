<form method="post" class="form-horizontal" action="{SITE_PATH}blog/search">
	<div class="input-group">
		<input type="text" name="query" class="form-control" placeholder="[f:blog:list.search]...">
	  <span class="input-group-btn">
		<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
	  </span>
	</div>
</form><br>

<div class="pull-left">
	<span class="fa fa-newspaper-o"></span> [f:blog:list.total] <span class="badge">{num}</span>
</div>
<div class="pull-right">
	[if has-permission:blog.posts.add]<a href="{SITE_PATH}blog/add" class="btn btn-xs btn-primary">
		<span class="glyphicon glyphicon-plus"></span> [b:blog:add.moduleName]
	</a>[/if]
</div>
<div class="clearfix"></div>

[if num!="0"][foreach rows][include "blog.list.row"][/foreach]
{pagination}[/if][if num="0"]
<div class="alert alert-info">
	[f:blog:list.noRows]
</div>[/if]
