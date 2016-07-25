<?php include "blog.tabs.php"; ?>

<?=$this->_flang("blog", "categories.total")?> <span class="badge"><?=$tags["num"]?></span><br><br>

<table class="table">
	<thead><tr>
		<th style="width: 40px">#</th>
		<th><?=$this->_flang("blog", "categories.table.title")?></th>
		<th style="width: 100px;"><?=$this->_flang("blog", "categories.table.rows")?></th>
		<th style="width: 75px;"></th>
	</tr></thead>
	<tbody>
		<?php foreach($tags["rows"] as $row): ?><tr <?=$row["edit"] ? "class=\"info\"" : ""?>><form method="post"><?php if($row["not-edit"]): ?>
			<input type="hidden" name="item_id" value="<?=$row["id"]?>">
			<td><?=$row["id"] == 0 ? "" : $row["id"]?></td>
			<td><a href="<?=$row["category-link"]?>"><?=$row["name"]?></a></td>
			<td><?=$row["posts-num"]?></td>
			<td style="padding: 3px">
				<form method="post">
					<div class="btn-group btn-group-sm">
						<button type="submit" name="edit" class="btn btn-success btn-sm<?=$row["id"] == 0 ? " disabled" : ""?>"><span class="glyphicon glyphicon-pencil"></span></button>
						<button type="submit" name="remove" class="btn btn-danger btn-sm<?=$row["id"] == 0 ? " disabled" : ""?>">
						<span class="glyphicon glyphicon-trash"></span>
						</button>
					</div>
				</form>
			</td>
		<?php else: ?>
			<input type="hidden" name="item_id" value="<?=$row["id"]?>">
			<td style="padding: 3px">
				<input type="text" class="form-control input-sm" value="<?=$row["id"]?>" disabled>
			</td>
			<td style="padding: 3px">
				<input type="text" class="form-control input-sm" name="edit[name]" value="<?=$row["original-name"]?>">
			</td>
			<td style="padding: 3px">
				<input type="text" class="form-control input-sm" value="<?=$row["posts-num"]?>" disabled>
			</td>
			<td style="padding: 3px">
				<button type="submit" class="btn btn-primary btn-sm" style="width:100%"><span class="glyphicon glyphicon-floppy-disk"></span></button>
			</td>
		<?php endif; ?></form></tr><?php endforeach; ?>

		<tr><form method="post">

			<td colspan="3" style="padding: 3px">
				<input type="text" class="form-control input-sm" name="add[name]">
			</td>
			<td style="padding: 3px">
				<button class="btn btn-primary btn-sm" style="width:100%"><span class="glyphicon glyphicon-plus"></span></button>
			</td>

		</form></tr>
	</tbody>
</table>
