<?php include "core.packages.tabs.php"; ?>

<form enctype="multipart/form-data" method="post" class="form-horizontal">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				<span class="fa fa-fw fa-globe"></span>&nbsp;
				<?=$this->_flang("core", "packages.install.meta")?>
			</h3>
		</div>

		<table class="table">
			<tbody>
			<tr[installed] class="danger"[/installed]>
				<td><?=$this->_flang("core", "packages.install.meta.name")?></td>
				<td width="25%"><?=$tags["name"]?></td>
			</tr>
			<tr>
				<td><?=$this->_flang("core", "packages.install.meta.description")?></td>
				<td width="25%"><?=$tags["description"]?></td>
			</tr>
			<tr>
				<td><?=$this->_flang("core", "packages.install.meta.type")?></td>
				<td width="25%"><?=$tags["type"]?></td>
			</tr>
			<tr>
				<td><?=$this->_flang("core", "packages.install.meta.version")?></td>
				<td width="25%"><?=$tags["version"]?></td>
			</tr>
			<tr>
				<td><?=$this->_flang("core", "packages.install.meta.author")?></td>
				<td width="25%"><?=$tags["author"]?></td>
			</tr>
			<tr>
				<td><?=$this->_flang("core", "packages.install.meta.license")?></td>
				<td width="25%"><?=$tags["license"]?></td>
			</tr>
			</tbody>
		</table>
	</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				<span class="fa fa-fw fa-server"></span>&nbsp;
				<?=$this->_flang("core", "packages.install.system")?>
			</h3>
		</div>

		<table class="table">
			<tbody>
			<tr class="<?=$tags["version-compare"] ? "success" : "danger"?>">
				<td><?=$this->_flang("core", "packages.install.system.version")?></td>
				<td width="25%">
					<?=$tags["required-min"]?> ≤ <span class="text-success"><b><?=$tags["system-version"]?></b></span><?=empty($tags["required-max"]) ? "" : " ≤ " . $tags["required-max"]?>
				</td>
			</tr>
			<tr<?=$tags["dependence-uncompare"] ? "class=\"danger\"" : "" ?>>
				<td><?=$this->_flang("core", "packages.install.system.dependence")?></td>
				<td width="25%"><?=$tags["dependence"]?></td>
			</tr>
			<?php if ($tags["dependence-uncompare"]): ?><tr class="danger">
				<td><?=$this->_flang("core", "packages.install.system.uncompare")?></td>
				<td width="25%"><?=$tags["dependences-uncompare-list"]?></td>
			</tr><?php endif; ?>
			</tbody>
		</table>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3"></label>
		<div class="col-sm-9">
			<button class="btn btn-primary" type="submit" name="contine"><span class="fa fa-upload"></span> <?=$this->_flang("core", "packages.install.info.contine")?></button>
			<button class="btn btn-danger" name="cancel" type="submit"><span class="fa fa-remove"></span> <?=$this->_flang("core", "packages.install.info.cancel")?></button>
		</div>
	</div>
</form>
