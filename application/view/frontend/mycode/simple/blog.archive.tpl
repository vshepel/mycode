[if num!="0"]
[foreach rows]
<article class="post">
	[if image-link!=""]
	<div class="post-image">
		<img src="{image-link}" alt="{title}" />
	</div>
	[/if]
	<div class="post_top">
		<a href="{author-link}"><img src="{author-avatar-link}" alt="{author-login}">{author-login}</a> <span>[f:blog:list.posted] {date}</span>[read]<span class="has-read">[f:blog:list.read]</span>[/read]
	</div>
	<div class="post_text">
		<span>{title}</span>
		{short-text}
	</div>
	<div class="post_more">
		<a class="btn btn-primary" href="{link}">[f:blog:list.more]</a>
	</div>
	<div class="post_tags">
		<i class="mdi mdi-label"></i> {tags}
	</div>
	<div class="post_bottom">
		<a href="{category-link}" class="category"><i class="mdi mdi-sort-variant"></i> {category-name}</a> <span><i class="mdi mdi-chart-bar"></i> {views-num}</span> <span><i class="mdi mdi-comment-text-outline"></i> {comments-num}</span> <span class="rating"><i class="mdi mdi-thumb-down[rating-minus-active] dislike[/rating-minus-active]" onclick="app.blog.rating.change('{id}', false); return false;"></i> <span id="blog-rating-{id}">{rating}</span> <i class="mdi mdi-thumb-up[rating-plus-active] like[/rating-plus-active]" onclick="app.blog.rating.change('{id}', true); return false;"></i></span>
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
