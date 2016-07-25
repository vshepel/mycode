<?php include "user.tabs.php"; ?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?=$this->_flang("user", "statistics.users")?></h3>
	</div>

	<table class="table">
		<tbody>
		<tr>
			<td><?=$this->_flang("user", "statistics.users.num")?></td>
			<td><?=$tags["users-num"]?></td>
		</tr>
		<tr>
			<td><?=$this->_flang("user", "statistics.users.groups")?></td>
			<td><?=$tags["users-groups"]?></td>
		</tr>
		<tr>
			<td><?=$this->_flang("user", "statistics.users.sessions")?></td>
			<td><?=$tags["users-sessions"]?></td>
		</tr>
		</tbody>
	</table>
</div>
