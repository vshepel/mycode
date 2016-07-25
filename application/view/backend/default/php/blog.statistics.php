<?php include "blog.tabs.php"; ?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><span class="fa fa-fw fa-newspaper-o"></span> <?=$this->_flang("blog", "statistics.rows")?></h3>
	</div>

	<table class="table">
		<tbody>
		<tr>
			<td><?=$this->_flang("blog", "statistics.rows.total")?></td>
			<td width="20%"><?=$tags["posts-total"]?></td>
		</tr>
		<tr>
			<td><?=$this->_flang("blog", "statistics.rows.shown")?></td>
			<td width="20%"><?=$tags["posts-show"]?></td>
		</tr>
		<tr>
			<td><?=$this->_flang("blog", "statistics.rows.comments")?></td>
			<td width="20%"><?=$tags["posts-comments"]?></td>
		</tr>
		<tr<?=($tags["posts-awaiting-moderation"] > 0) ? " class=\"info\"" : ""?>>
			<td><?=$this->_flang("blog", "statistics.rows.awaiting-moderation")?></td>
			<td width="20%"><?=$tags["posts-awaiting-moderation"]?></td>
		</tr>
		</tbody>
	</table>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><span class="fa fa-fw fa-comment"></span> <?=$this->_flang("blog", "statistics.comments")?></h3>
	</div>

	<table class="table">
		<tbody>
		<tr>
			<td><?=$this->_flang("blog", "statistics.comments.total")?></td>
			<td width="20%"><?=$tags["comments-total"]?></td>
		</tr>
		</tbody>
	</table>
</div>
