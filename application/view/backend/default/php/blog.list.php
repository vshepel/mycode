<?php include "blog.tabs.php"; ?>

<div class="row">
	<div class="col-sm-3">
		<ul class="nav nav-pills nav-stacked"><?php foreach($this->_getProperty("blog", "categories", ["template" => false]) as $row): ?>
			<li role="presentation"<?=$row["active"] ? " class=\"active\"" : ""?>><a href="<?=$row["link"]?>">
				<?=$row["name"]?> <span class="badge"><?=$row["num"]?></span>
			</a></li>
		<?php endforeach; ?></ul>
	</div>
	<div class="col-sm-9">
		<?php if($tags["num"] > 0): ?><?=$this->_flang("blog", "list.total")?> <span class="badge"><?=$tags["num"]?></span><br><br>

		<table class="table">
			<thead><tr>
				<th style="width: 40px">#</th>
				<th><?=$this->_flang("blog", "list.table.title")?></th>
				<th><?=$this->_flang("blog", "list.table.category")?></th>
				<th><?=$this->_flang("blog", "list.table.date")?></th>
				<th><?=$this->_flang("blog", "list.table.author")?></th>
				<th align="center" style="width: 50px;"><span class="glyphicon glyphicon-comment"></span></th>
				<th align="center" style="width: 50px;"><span class="glyphicon glyphicon-eye-open"></span></th>
				<th style="width: 80px;"></th>
			</tr></thead>
			<tbody><?php foreach ($tags["rows"] as $row): ?>
				<tr>
					<td><?=$row["id"]?></td>
					<td><a href="<?=$row["link"]?>"><?=$row["title"]?></a><?=$row["not-show"] ? " <span class=\"glyphicon glyphicon-eye-close\">" : ""?></td>
					<td><a href="<?=$row["category-link"]?>"><?=$row["category-name"]?></a><?=$row["not-show-category"] ? " <span class=\"glyphicon glyphicon-eye-close\">" : ""?></td>
					<td><?=$row["date"]?>, <?=$row["time"]?></td>
					<td><a href="<?=$tags["author-link"]?>"><img src="<?=$row["author-avatar-link"]?>" class="img-circle" style="height:24px"></a> <a href="<?=$row["author-link"]?>"><?=$row["author-login"]?></a></td>
					<td><?=$row["comments-num"]?></td>
					<td><?=$row["views-num"]?></td>
					<td style="padding: 3px">
						<div class="btn-group btn-group-sm btn-group-justified">
							<a href="<?=$row["edit-link"]?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="<?=$row["remove-link"]?>" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
						</div>
					</td>
				</tr>
			<?php endforeach; ?></tbody>
		</table>

		<?=$tags["pagination"]?><?php else: ?><div class="alert alert-info" role="alert">
			<?=$this->_flang("blog", "list.noRows")?>
		</div><?php endif; ?>
	</div>
</div>