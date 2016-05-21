{addform}
[f:blog:comments.list.total] <span class="badge" id="blog-comments-num">{num}</span><br><br>

<div id="blog-comments">
	[if num!="0"]{rows}[/if][if num="0"]
	<div class="alert alert-info">
		[f:blog:comments.list.noRows]
	</div>[/if]
</div>

<div id="blog-comments-pagination">
	[if num!="0"]{pagination}[/if]
</div>
