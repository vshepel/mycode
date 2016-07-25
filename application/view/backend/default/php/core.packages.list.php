<?php include "core.packages.tabs.php"; ?>

<ul class="nav nav-pills">
	<li id="toggle-all" class="toggle active"><a href="#" onclick="showAll(); return false;">
		<span class="main-row-icon fa fa-home"></span>
		<?=$this->_flang("core", "packages.list.toggle.all")?>
	</a></li>
	<li id="toggle-module" class="toggle"><a href="#" onclick="show('module'); return false;">
		<span class="main-row-icon fa fa-archive"></span>
		<?=$this->_flang("core", "packages.list.toggle.packages")?>
	</a></li>
	<li id="toggle-library" class="toggle"><a href="#" onclick="show('library'); return false;">
		<span class="main-row-icon fa fa-book"></span>
		<?=$this->_flang("core", "packages.list.toggle.libraries")?>
	</a></li>
</ul><br>

<table class="table table-bordered">
	<thead><tr>
		<th width="32"></th>
		<th><?=$this->_flang("core", "packages.list.table.title")?></th>
		<th><?=$this->_flang("core", "packages.list.table.description")?></th>
		<th><?=$this->_flang("core", "packages.list.table.author")?></th>
		<th><?=$this->_flang("core", "packages.list.table.license")?></th>
		<th><?=$this->_flang("core", "packages.list.table.files")?></th>
		<th><?=$this->_flang("core", "packages.list.table.dependences")?></th>
		<th><?=$this->_flang("core", "packages.list.table.type")?></th>
		<th style="width:40px"></th>
	</tr></thead>
	<tbody>
		<?php foreach ($tags["rows"] as $row): ?>
		<div class="modal fade" id="files-<?=$row["name"]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <h4 class="modal-title" id="myModalLabel"><?=$this->_flang("core", "packages.list.modal.titleFiles")?></h4>
				</div>
				<div class="modal-body">
				  <?=$row["files"]?>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-primary" data-dismiss="modal"><?=$this->_flang("core", "packages.list.modal.close")?></button>
				</div>
			  </div>
			</div>
		  </div>

		  <div class="modal fade" id="dependences-<?=$row["name"]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <h4 class="modal-title" id="myModalLabel"><?=$this->_flang("core", "packages.list.modal.titleDependences")?></h4>
				</div>
				<div class="modal-body">
				  <?=$row["dependence"]?>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-primary" data-dismiss="modal"><?=$this->_flang("core", "packages.list.modal.close")?></button>
				</div>
			  </div>
			</div>
		  </div>

		  <tr class="package package-type-<?=$row["type"]?><?=$row["dependent"] ? " info" : ""?><?=$row["unused"] ? " warning" : ""?><?=$row["conflict"] ? " danger" : ""?>">
			  <td><img src="<?=PATH?>images/modules/<?=$row["backend-image"]?>" width="32" alt="<?=$row["name"]?>"></td>
			  <td><a href="<?=$row["frontend-link"]?>"><?=$row["name"]?></a> (<?=$row["version"]?>)</td>
			  <td><?=$row["description"]?></td>
			  <td><?=$row["author"]?></td>
			  <td><?=$row["license"]?></td>
			  <td>
				  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#files-<?=$row["name"]?>">
					  <span class="glyphicon glyphicon-file"></span> <?=$this->_flang("core", "packages.list.filesList")?>
				  </button>
			  </td>
			  <td>
				  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#dependences-<?=$row["name"]?>">
					  <span class="glyphicon glyphicon-th"></span> <?=$this->_flang("core", "packages.list.dependencesList")?>
				  </button>
			  </td>
			  <td align="center">
				  <?php if ($row["type"] == "module"): ?><span class="fa fa-archive"></span><?php endif; ?>
				  <?php if ($row["type"] == "library"): ?><span class="fa fa-book"></span><?php endif; ?>
			  </td>
			  <td>
				  <?php if ($row["remove"]): ?><a href="<?=$row["remove-link"]?>" class="btn btn-xs btn-danger">
					  <span class="glyphicon glyphicon-trash"></span>
				  </a><?php endif; ?>
			  </td>
		  </tr>
		<?php endforeach; ?>
	</tbody>
</table>

<table class="table table-bordered"><tbody>
	<tr>
		<td class="danger" width="35px"></td>
		<td><?=$this->_flang("core", "packages.list.color.danger")?></td>
	</tr>
	<tr>
		<td class="warning" width="35px"></td>
		<td><?=$this->_flang("core", "packages.list.color.warning")?></td>
	</tr>

	<tr>
		<td class="info" width="35px"></td>
		<td><?=$this->_flang("core", "packages.list.color.info")?></td>
	</tr>
</tbody></table>
	
<script type="text/javascript">
function show(name) {
	$('.package').css('display', 'none');
	$('.package-type-' + name).css('display', 'table-row');
	$('.toggle').removeClass('active');
	$('#toggle-' + name).addClass('active');
}

function showAll() {
	$('.package').css('display', 'table-row');
	$('.toggle').removeClass('active');
	$('#toggle-all').addClass('active');
}

show('module');
</script>