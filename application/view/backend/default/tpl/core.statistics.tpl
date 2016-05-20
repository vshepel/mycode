<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="fa fa-fw fa-globe"></span>&nbsp;
			[f:core:statistics.system]
		</h3>
	</div>

	<table class="table">
		<tbody>
		<tr>
			<td>[f:core:statistics.system.version]</td>
			<td>{version}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.system.cacheSize]</td>
			<td>
				<form method="post">
					{cache-size} <button class="btn btn-xs btn-danger" name="clear_cache" type="submit"><span class="glyphicon glyphicon-trash"></span></button>
				</form>
			</td>
		</tr>
		<tr>
			<td>[f:core:statistics.system.uploadsSize]</td>
			<td>
				{uploads-size}
			</td>
		</tr>
		<tr>
			<td>[f:core:statistics.system.freeSpace]</td>
			<td>
				{free-space}
			</td>
		</tr>
		<tr>
			<td>[f:core:statistics.system.debug]</td>
			<td>
				{debug}
			</td>
		</tr>
		</tbody>
	</table>
</div>

<div class="panel panel-warning">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="fa fa-fw fa-server"></span>&nbsp;
			[f:core:statistics.server]
		</h3>
	</div>

	<table class="table">
		<tbody>
		<tr>
			<td>[f:core:statistics.server.os]</td>
			<td>{os}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.server.phpVersion]</td>
			<td>{php-version}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.server.postMaxSize]</td>
			<td>{post-max-size}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.server.uploadMaxFilesize]</td>
			<td>{upload-max-filesize}</td>
		</tr>
		</tbody>
	</table>
</div>

<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="fa fa-fw fa-database"></span>&nbsp;
			[f:core:statistics.db]
		</h3>
	</div>

	<table class="table">
		<tbody>
		<tr>
			<td>[f:core:statistics.db.driver]</td>
			<td>{db-driver}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.db.version]</td>
			<td>{db-version}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.db.size]</td>
			<td>{db-size}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.db.tables]</td>
			<td>{db-tables}</td>
		</tr>
		</tbody>
	</table>
</div>