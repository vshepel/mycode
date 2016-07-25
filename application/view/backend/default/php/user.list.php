<?php include "user.tabs.php"; ?>

<?php if($tags["num"] > 0): ?><table class="table">
	<thead><tr>
		<th style="width: 40px">#</th>
		<th style="width: 40px"><?=$this->_lang("user", "fields.avatar")?></th>
		<th><?=$this->_lang("user", "fields.login")?></th>
		<th><?=$this->_lang("user", "fields.name")?></th>
		<th><?=$this->_lang("user", "fields.group")?></th>
		<th style="width: 80px;"></th>
	</tr></thead>
	<tbody>
		<?php foreach ($tags["rows"] as $row): ?>
			<tr>
				<td><?=$row["id"]?></td>
				<td align="center" valign="center"><a href="<?=$row["profile-link"]?>"><img src="<?=$row["avatar-link"]?>" class="media-object img-rounded" style="width:40px"></a></td>
				<td><a href="<?=$row["profile-link"]?>"><?=$row["username"]?></a> <span class="glyphicon glyphicon-globe" style="color:<?=$row["online"] ? "green" : "red"?>"></span></td>
				<td><?=$row["name"]?></td>
				<td><?=$row["group"]?></td>
				<td style="padding: 3px">
					<div class="btn-group btn-group-sm btn-group-justified">
						<?php if ($this->_user->hasPermission("user.edit")): ?><a href="<?=$row["edit-link"]?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a><?php endif; ?>
						<?php if ($this->_user->hasPermission("user.remove")): ?><a href="<?=$row["remove-link"]?>" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a><?php endif; ?>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?=$tags["pagination"]?><?php else: ?><div class="alert alert-info" role="alert">
	<?=$this->_flang("user", "list.noRows")?>
</div><?php endif; ?>
