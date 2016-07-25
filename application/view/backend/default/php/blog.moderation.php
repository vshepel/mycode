<?php include "blog.tabs.php"; ?>

<?php if($tags["num"] > 0): ?><?=$this->_flang("blog", "moderation.total")?> <span class="badge"><?=$tags["num"]?></span><br><br>

<table class="table">
	<thead><tr>
		<th style="width: 40px">#</th>
		<th><?=$this->_flang("blog", "moderation.table.title")?></th>
		<th><?=$this->_flang("blog", "moderation.table.category")?></th>
		<th><?=$this->_flang("blog", "moderation.table.date")?></th>
		<th><?=$this->_flang("blog", "moderation.table.author")?></th>
		<th style="width: 80px;"></th>
	</tr></thead>
	<tbody><?php foreach ($tags["rows"] as $row): ?>
		<div class="modal fade" id="post-<?=$row["id"]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel"><?=$row["title"]?></h4>
					</div>
					<div class="modal-body">
						<?php if(!empty($row["image-link"])): ?><p align="center"><img src="<?=$row["image-link"]?>" alt="<?=$row["title"]?>" style="max-width:100%"></p><?php endif; ?>
						<p><?=$row["text"]?></p>
					</div>
					<div class="modal-footer">
						<a class="btn btn-success" href="<?=$row["good-link"]?>">
							<span class="glyphicon glyphicon-thumbs-up"></span> <?=$this->_flang("blog", "moderation.modal.good")?>
						</a>
						<a class="btn btn-danger" href="<?=$row["bad-link"]?>">
							<span class="glyphicon glyphicon-thumbs-down"></span> <?=$this->_flang("blog", "moderation.modal.bad")?>
						</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							<?=$this->_flang("blog", "moderation.modal.close")?>
						</button>
					</div>
				</div>
			</div>
		</div>

		<tr>
			<td><?=$row["id"]?></td>
			<td><?=$row["title"]?></td>
			<td><a href="<?=$row["category-link"]?>"><?=$row["category-name"]?></a></td>
			<td><?=$row["date"]?>, <?=$row["time"]?></td>
			<td><a href="<?=$row["author-link"]?>"><img src="<?=$row["author-avatar-link"]?>" class="img-circle" style="height:24px"></a> <a href="<?=$row["author-link"]?>"><?=$row["author-login"]?></a></td>
			<td style="padding: 3px">
				<button class="btn btn-sm btn-info" data-toggle="modal" data-target="#post-<?=$row["id"]?>">
					<span class="glyphicon glyphicon-eye-open"></span> <?=$this->_flang("blog", "moderation.view")?>
				</button>
			</td>
		</tr>
	<?php endforeach; ?></tbody>
</table>

<?=$tags["pagination"]?><?php else: ?><div class="alert alert-info" role="alert">
	<?=$this->_flang("blog", "list.noRows")?>
</div><?php endif; ?>
