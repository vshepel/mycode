<?php include "page.tabs.php"; ?>

<?php if($tags["num"] > 0): ?><?=$this->_flang("page", "list.total")?> <span class="badge"><?=$tags["num"]?></span><br><br>

<table class="table">
	<thead><tr>
		<th style="width: 40px">#</th>
		<th><?=$this->_flang("page", "list.table.title")?></th>
		<th>URL</th>
		<th><?=$this->_flang("page", "list.table.language")?></th>
		<th style="width: 80px;"></th>
	</tr></thead>
	<tbody>
		<?php foreach ($tags["rows"] as $row): ?>
			<tr>
				<td><?=$row["id"]?></td>
				<td><a href="<?=$row["page-link"]?>"><?=$row["name"]?></a></td>
				<td><?=SITE_PATH?>page/<?=$row["url"]?></td>
				<td><?=$row["language"]?></td>
				<td style="padding: 3px">
					<div class="btn-group btn-group-sm btn-group-justified">
						<?php if ($this->_user->hasPermission("page.edit")): ?><a href="<?=$row["edit-link"]?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a><?php endif; ?>
						<?php if ($this->_user->hasPermission("page.remove")): ?><a href="<?=$row["remove-link"]?>" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a><?php endif; ?>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?=$tags["pagination"]?><?php else: ?><div class="alert alert-info" role="alert">
	<?=$this->_flang("page", "list.noRows")?>
</div><?php endif; ?>
