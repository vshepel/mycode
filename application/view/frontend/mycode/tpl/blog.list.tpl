[if num!="0"]
[foreach rows]
<article>
	<div class="post_top">
		<a href="{author-link}"><img src="{author-avatar-link}" alt="{author-login}">{author-login}</a> <span>posted in {date}</span> <span class="has-read">Has read</span>
	</div>
	<div class="post_text">
		<a href="{link}">
			<span>{title}</span>
			{short-text}
		</a>
	</div>
	<div class="post_bottom">
		<a href="{category-link}" class="category"><i class="mdi mdi-sort-variant"></i> {category-name}</a> <span><i class="mdi mdi-chart-bar"></i> {views-num}</span> <span><i class="mdi mdi-comment-text-outline"></i> {comments-num}</span> <span class="rating"><i class="mdi mdi-thumb-down" onclick="app.blog.rating.change('{id}', false); return false;"></i> <span id="blog-rating-{id}">{rating}</span> <i class="mdi mdi-thumb-up" onclick="app.blog.rating.change('{id}', true); return false;"></i></span>
	</div>
</article>
[/foreach]
{pagination}
[/if]
[if num="0"]
<div class="post-nope">
	<i class="mdi mdi-emoticon-sad"></i>
	[f:blog:list.noRows]
</div>
[/if]
