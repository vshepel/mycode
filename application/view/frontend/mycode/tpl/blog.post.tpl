<article class="post full_post">
	<div class="post_top">
		<a href="{author-link}"><img src="{author-avatar-link}" alt="{author-login}">{author-login}</a> <span>[f:blog:list.posted] {date}</span> <span class="has-read">[f:blog:list.read]</span>
	</div>
	<div class="post_text">
		<h1>{title}</h1>
		{short-text}
	</div>
	<div class="post_tags">
		<i class="mdi mdi-label"></i> {tags}
	</div>
	<div class="post_bottom">
		<a href="{category-link}" class="category"><i class="mdi mdi-sort-variant"></i> {category-name}</a> <span><i class="mdi mdi-chart-bar"></i> {views-num}</span> <span><i class="mdi mdi-comment-text-outline"></i> {comments-num}</span> <span class="rating"><i class="mdi mdi-thumb-down[rating-minus-active] dislike[/rating-minus-active]" onclick="app.blog.rating.change('{id}', false); return false;"></i> <span id="blog-rating-{id}">{rating}</span> <i class="mdi mdi-thumb-up[rating-plus-active] like[/rating-plus-active]" onclick="app.blog.rating.change('{id}', true); return false;"></i></span>
	</div>
</article>

{comments}
