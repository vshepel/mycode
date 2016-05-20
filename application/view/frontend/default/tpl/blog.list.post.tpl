<div class="media">
	<div class="media-body">
		<h2><a href="{link}">{title}</a></h2>

		<div class="pull-left">
			<span class="glyphicon glyphicon-user"></span> <a href="{author-link}">{author-login}</a>,
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
	</div>
</div>
