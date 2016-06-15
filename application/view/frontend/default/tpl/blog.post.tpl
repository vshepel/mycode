<div class="media">
	<div class="media-body">
		<div class="pull-left">
			<h2>{title}</h2>
		</div>
		<div class="pull-right"><br>
			[if has-permission:"blog.posts.add"]<a href="{SITE_PATH}blog/add" class="btn btn-primary">
				<span class="glyphicon glyphicon-plus"></span>
			</a>[/if]
		</div>
		<div class="clearfix"></div>

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
		
		<p>{full-text}</p>

		[if tags!=""]<p class="pull-left"><span class="fa fa-tags"></span> {tags}</p>[/if]
		[if language!=""]<p class="pull-right"><span class="fa fa-language"></span> {language}</p>[/if]
		<div class="clearfix"></div>

		<p>
			<nav>
				<ul class="pager">
					[if previous-link!=""]<li class="previous"><a href="{previous-link}"><span aria-hidden="true">&larr;</span> {previous-title}</a></li>[/if]
					[if next-link!=""]<li class="next"><a href="{next-link}">{next-title} <span aria-hidden="true">&rarr;</span></a></li>[/if]
				</ul>
			</nav>
		</p>
	</div>
</div>

{comments}
