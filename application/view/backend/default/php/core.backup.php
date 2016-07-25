<!--div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="fa fa-fw fa-server"></span>&nbsp;
			<?=$this->_flang("core", "backup.system")?>
		</h3>
	</div>

	<div class="panel-body">
		Coming soon...
	</div>
</div-->

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="fa fa-fw fa-database"></span>&nbsp;
			<?=$this->_flang("core", "backup.database")?>
		</h3>
	</div>

	<div class="panel-body">
		<form method="post">
			<button type="submit" class="btn btn-success" name="make_database">
				<span class="fa fa-upload"></span> <?=$this->_flang("core", "backup.database.make")?>
			</button> <?=$tags["date"]?>
		</form><hr>

		<form method="post">
			<select size="7" style="width:100%" name="restore_database"><?php foreach ($tags["database-backups"] as $row): ?>
				<option value="<?=$row["name"]?>"><?=$row["name"]?> (<?=$row["date"]?>)</option>
			<?php endforeach; ?></select><br><br>

			<button type="submit" class="btn btn-danger">
				<span class="fa fa-download"></span> <?=$this->_flang("core", "backup.database.restore")?>
			</button>
		</form>
	</div>
</div>