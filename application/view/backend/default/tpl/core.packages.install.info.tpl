[include "core.packages.tabs"]

<form enctype="multipart/form-data" method="post" class="form-horizontal">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				<span class="fa fa-fw fa-globe"></span>&nbsp;
				[f:core:packages.install.meta]
			</h3>
		</div>

		<table class="table">
			<tbody>
			<tr[installed] class="danger"[/installed]>
				<td>[f:core:packages.install.meta.name]</td>
				<td width="25%">{name}</td>
			</tr>
			<tr>
				<td>[f:core:packages.install.meta.description]</td>
				<td width="25%">{description}</td>
			</tr>
			<tr>
				<td>[f:core:packages.install.meta.type]</td>
				<td width="25%">{type}</td>
			</tr>
			<tr>
				<td>[f:core:packages.install.meta.version]</td>
				<td width="25%">{version}</td>
			</tr>
			<tr>
				<td>[f:core:packages.install.meta.author]</td>
				<td width="25%">{author}</td>
			</tr>
			<tr>
				<td>[f:core:packages.install.meta.license]</td>
				<td width="25%">{license}</td>
			</tr>
			</tbody>
		</table>
	</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				<span class="fa fa-fw fa-server"></span>&nbsp;
				[f:core:packages.install.system]
			</h3>
		</div>

		<table class="table">
			<tbody>
			<tr[not-version-compare] class="danger"[/not-version-compare][version-compare] class="success"[/version-compare]>
				<td>[f:core:packages.install.system.version]</td>
				<td width="25%">
					{required-min} ≤ <span class="text-success"><b>{system-version}</b></span> [if required-max!=""]≤ {required-max}[/if]
				</td>
			</tr>
			<tr[dependence-uncompare] class="danger"[/dependence-uncompare]>
				<td>[f:core:packages.install.system.dependence]</td>
				<td width="25%">{dependence}</td>
			</tr>
			[dependence-uncompare]<tr class="danger">
				<td>[f:core:packages.install.system.uncompare]</td>
				<td width="25%">{dependences-uncompare-list}</td>
			</tr>[/dependence-uncompare]
			</tbody>
		</table>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary" type="submit" name="contine"><span class="fa fa-upload"></span> [f:core:packages.install.info.contine]</button>
			<button class="btn btn-danger" name="cancel" type="submit"><span class="fa fa-remove"></span> [f:core:packages.install.info.cancel]</button>
		</div>
	</div>
</form>
