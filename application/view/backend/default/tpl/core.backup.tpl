<!--div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="fa fa-fw fa-server"></span>&nbsp;
			[f:core:backup.system]
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
			[f:core:backup.database]
		</h3>
	</div>

	<div class="panel-body">
		<form method="post">
			<button type="submit" class="btn btn-success" name="make_database">
				<span class="fa fa-upload"></span> [f:core:backup.database.make]
			</button> {date}
		</form><hr>

		<form method="post">
			<select size="7" style="width:100%" name="restore_database">[foreach database-backups]
				<option value="{name}">{name}</option>
			[/foreach]</select><br><br>

			<button type="submit" class="btn btn-danger">
				<span class="fa fa-download"></span> [f:core:backup.database.restore]
			</button>
		</form>
	</div>
</div>