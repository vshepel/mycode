<ul class="pagination">
	<li<?php if($tags["not-back"]) { echo " class=\"disabled\""; } ?>><a<?php if($tags["back"]) { echo " href=\"{$tags["back-link"]}\""; } ?>><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>
	<?=$tags["pages"]?>
	<li<?php if($tags["not-next"]) { echo " class=\"disabled\""; } ?>><a<?php if($tags["next"]) { echo " href=\"{$tags["next-link"]}\""; } ?>><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>
	<!--li<?php if($tags["not-next"]) { echo " class=\"disabled\""; } ?>><a[next] href="<?=$tags["next-link"]?>"[/next]><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li-->
</ul>