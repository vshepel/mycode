<form method="post" class="form-horizontal" action="{SITE_PATH}blog/search">
	<div class="input-group">
		<input type="text" name="query" class="form-control" placeholder="[f:blog:list.search]...">
	  <span class="input-group-btn">
		<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
	  </span>
	</div>
</form>

<br>

<div class="pull-left">
	<span class="fa fa-newspaper-o"></span> [f:blog:list.total] <span class="badge">{num}</span>
</div>
<div class="pull-right">
	[if has-permission:"blog.posts.add"]<a href="{SITE_PATH}blog/add" class="btn btn-xs btn-primary">
		<span class="glyphicon glyphicon-plus"></span> [b:blog:add.moduleName]
	</a>[/if]
</div>
<div class="clearfix"></div>

[if num!="0"]
[foreach rows]
<div class="media">
	<div class="media-body">
		<h2><a href="{link}">{title}</a> [read]<span class="fa fa-check text-success"></span>[/read]</h2>

		<div class="pull-left">
			<a href="{author-link}"><img src="{author-avatar-link}" class="img-circle" style="height:24px"></a> <a href="{author-link}">{author-login}</a>,
			<span class="fa fa-folder"></span> <a href="{category-link}">{category-name}</a>,
			<time datetime="{iso-datetime}"><span class="fa fa-calendar"></span> <a href="{archive-link}">{date}</a> {time}</time>,
			<span class="fa fa-comment"></span> {comments-num},
			<span class="fa fa-eye"></span> {views-num}
		</div>

		<div class="pull-right">
			<span class="btn-group">
				<button class="btn btn-xs btn-warning[rating-minus-active] active[/rating-minus-active]" onclick="app.blog.rating.change('{id}', false); return false;"><span class="fa fa-thumbs-down"></span></button>
				<button class="btn btn-xs" id="blog-rating-{id}" disabled>{rating}</button>
				<button class="btn btn-xs btn-info[rating-plus-active] active[/rating-plus-active]" onclick="app.blog.rating.change('{id}', true); return false;"><span class="fa fa-thumbs-up"></span></button>
			</span>

			[edit]<a href="{edit-link}" class="btn btn-xs btn-success">
				<span class="glyphicon glyphicon-pencil"></span>
			</a>[/edit]

			[remove]<a href="{remove-link}" class="btn btn-xs btn-danger">
				<span class="glyphicon glyphicon-trash"></span>
			</a>[/remove]
		</div>
		<div class="clearfix"></div>

		<p>{short-text}</p>

		[if tags!=""]<p class="pull-left"><span class="fa fa-tags"></span> {tags}</p>[/if]
		[if language!=""]<p class="pull-right"><span class="fa fa-language"></span> {language}</p>[/if]
		<div class="clearfix"></div>
	</div>
</div>
[/foreach]
{pagination}[/if][if num="0"]
<div class="alert alert-info">
	[f:blog:list.noRows]
</div>[/if]
