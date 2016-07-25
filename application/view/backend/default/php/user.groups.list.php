<?php include "user.tabs.php"; ?>

<table class="table">
	<thead><tr>
		<th style="width: 40px">#</th>
		<th><?=$this->_flang("user", "groups.list.table.title")?></th>
		<th><?=$this->_flang("user", "groups.list.table.extends")?></th>
		<th style="width: 90px;"></th>
	</tr></thead>
	<tbody>
		<?php foreach ($tags["rows"] as $row): ?><tr>
			<td><?=$row["id"]?></td>
			<td><a href="<?=$row["edit-link"]?>"><?=$row["name"]?></a></td>
			<td><?=$row["extends"]?></td>
			<td style="padding: 3px">
				<div class="btn-group btn-group-sm btn-group-justified">
					<?php if ($this->_user->hasPermission("user.groups.edit")): ?><a href="<?=$row["edit-link"]?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a><?php endif; ?>
					<?php if ($this->_user->hasPermission("user.groups.remove")): ?><a href="<?=$row["remove-link"]?>" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a><?php endif; ?>
				</div>
			</td>
		</tr><?php endforeach; ?>

		<?php if($this->_user->hasPermission("user.groups.add")): ?><tr><form method="post">
			<td colspan="3" style="padding: 3px">
				<input type="text" class="form-control input-sm" name="add[name]">
			</td>
			<td style="padding: 3px">
				<button class="btn btn-primary btn-sm" style="width:100%"><span class="glyphicon glyphicon-ok"></span></button>
			</td>
		</form></tr><?php endif; ?>
	</tbody>
</table>

<?=$tags["pagination"]?>