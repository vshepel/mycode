<form method="post" class="form-horizontal" action="{SITE_PATH}blog/search">
	<div class="input-group">
		<input type="text" name="query" class="form-control" placeholder="[f:blog:list.search]...">
	  <span class="input-group-btn">
		<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
	  </span>
	</div>
</form><br>

[if num!="0"][f:blog:list.total] <span class="badge">{num}</span>
[foreach rows]
<div class="media">
	<div class="media-body">
		<h2><a href="{link}">{title}</a></h2>

		<div class="pull-left">
			<a href="{author-link}"><img src="{author-avatar-link}" class="img-circle" style="height:24px"></a> <a href="{author-link}">{author-login}</a>,
			<span class="glyphicon glyphicon-folder-open"></span> <a href="{category-link}">{category-name}</a>,
			<time datetime="{iso-datetime}"><span class="glyphicon glyphicon-calendar"></span> <a href="{archive-link}">{date}</a> {time}</time>,
			<span class="glyphicon glyphicon-comment"></span> {comments-num},
			<span class="glyphicon glyphicon-eye-open"></span> {views-num}
		</div>

		<div class="pull-right">
			<span class="btn-group">
				<button class="btn btn-xs btn-warning" onclick="app.blog.rating.change('{id}', false); return false;"><span class="fa fa-thumbs-down"></span></button>
				<button class="btn btn-xs" id="blog-rating-{id}" disabled>{rating}</button>
				<button class="btn btn-xs btn-info" onclick="app.blog.rating.change('{id}', true); return false;"><span class="fa fa-thumbs-up"></span></button>
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
