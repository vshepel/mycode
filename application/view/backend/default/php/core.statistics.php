<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="fa fa-fw fa-globe"></span>&nbsp;
			<?=$this->_flang("core", "statistics.system")?>
		</h3>
	</div>

	<table class="table">
		<tbody>
		<tr>
			<td><?=$this->_flang("core", "statistics.system.version")?></td>
			<td width="25%"><?=$tags["version"]?></td>
		</tr>
		<tr>
			<td><?=$this->_flang("core", "statistics.system.cacheSize")?></td>
			<td width="25%">
				<form method="post">
					<?=$tags["cache-size"]?> <button class="btn btn-xs btn-danger" name="clear_cache" type="submit"><span class="glyphicon glyphicon-trash"></span></button>
				</form>
			</td>
		</tr>
		<tr>
			<td><?=$this->_flang("core", "statistics.system.uploadsSize")?></td>
			<td width="25%"><?=$tags["uploads-size"]?></td>
		</tr>
		<tr>
			<td><?=$this->_flang("core", "statistics.system.freeSpace")?></td>
			<td width="25%"><?=$tags["free-space"]?></td>
		</tr>
		<tr>
			<td><?=$this->_flang("core", "statistics.system.debug")?></td>
			<td width="25%"><?=$tags["debug"]?></td>
		</tr>
		</tbody>
	</table>
</div>

<div class="panel panel-warning">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="fa fa-fw fa-server"></span>&nbsp;
			<?=$this->_flang("core", "statistics.server")?>
		</h3>
	</div>

	<table class="table">
		<tbody>
		<tr>
			<td><?=$this->_flang("core", "statistics.server.os")?></td>
			<td width="25%"><?=$tags["os"]?></td>
		</tr>
		<tr>
			<td><?=$this->_flang("core", "statistics.server.phpVersion")?></td>
			<td width="25%"><?=$tags["php-version"]?></td>
		</tr>
		<tr>
			<td><?=$this->_flang("core", "statistics.server.postMaxSize")?></td>
			<td width="25%"><?=$tags["post-max-size"]?></td>
		</tr>
		<tr>
			<td><?=$this->_flang("core", "statistics.server.uploadMaxFilesize")?></td>
			<td width="25%"><?=$tags["upload-max-filesize"]?></td>
		</tr>
		</tbody>
	</table>
</div>

<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="fa fa-fw fa-database"></span>&nbsp;
			<?=$this->_flang("core", "statistics.db")?>
		</h3>
	</div>

	<table class="table">
		<tbody>
		<tr>
			<td><?=$this->_flang("core", "statistics.db.driver")?></td>
			<td width="25%"><?=$tags["db-driver"]?></td>
		</tr>
		<tr>
			<td><?=$this->_flang("core", "statistics.db.version")?></td>
			<td width="25%"><?=$tags["db-version"]?></td>
		</tr>
		<tr>
			<td><?=$this->_flang("core", "statistics.db.size")?></td>
			<td width="25%"><?=$tags["db-size"]?></td>
		</tr>
		<tr>
			<td><?=$this->_flang("core", "statistics.db.tables")?></td>
			<td width="25%"><?=$tags["db-tables"]?></td>
		</tr>
		</tbody>
	</table>
</div>