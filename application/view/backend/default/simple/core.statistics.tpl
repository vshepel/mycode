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
			<td width="25%">{version}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.system.cacheSize]</td>
			<td width="25%">
				<form method="post">
					{cache-size} <button class="btn btn-xs btn-danger" name="clear_cache" type="submit"><span class="glyphicon glyphicon-trash"></span></button>
				</form>
			</td>
		</tr>
		<tr>
			<td>[f:core:statistics.system.uploadsSize]</td>
			<td width="25%">{uploads-size}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.system.freeSpace]</td>
			<td width="25%">{free-space}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.system.debug]</td>
			<td width="25%">{debug}</td>
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
			<td width="25%">{os}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.server.phpVersion]</td>
			<td width="25%">{php-version}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.server.postMaxSize]</td>
			<td width="25%">{post-max-size}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.server.uploadMaxFilesize]</td>
			<td width="25%">{upload-max-filesize}</td>
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
			<td width="25%">{db-driver}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.db.version]</td>
			<td width="25%">{db-version}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.db.size]</td>
			<td width="25%">{db-size}</td>
		</tr>
		<tr>
			<td>[f:core:statistics.db.tables]</td>
			<td width="25%">{db-tables}</td>
		</tr>
		</tbody>
	</table>
</div>