<ul class="nav nav-tabs" role="tablist">
	<li class="active"><a href="<?=ADMIN_PATH?>core/media/list"><span class="glyphicon glyphicon-picture"></span> <?=$this->_lang("core", "media.list.moduleName")?></a></li>
	<?php if($this->_user->hasPermission("core.media.upload")): ?><li><a href="<?=ADMIN_PATH?>core/media/upload"><span class="glyphicon glyphicon-upload"></span> <?=$this->_lang("core", "media.upload.moduleName")?></a></li><?php endif; ?>
</ul><br>

<?php if($tags["num"] > 0): foreach ($tags["rows"] as $row): ?>
<div class="media">
	<div class="media-left">
		<a href="<?=$row["file-link"]?>">
			<img src="<?=$row["icon-link"]?>" style="width: 48px" class="media-object">
		</a>
	</div>
	<div class="media-body">
		<h4 class="media-heading">
			<text title="<?=$row["filename"]?>" data-toggle="tooltip" data-placement="bottom"><?=$row["name"]?></text>
			<small><?=$row["filesize"]?></small>
			<?php if ($this->_user->hasPermission("core.media.edit")): ?><a href="<?=$row["edit-link"]?>"><i class="glyphicon glyphicon-pencil"></i></a><?php endif; ?>
			<?php if ($this->_user->hasPermission("core.media.remove")): ?><a href="<?=$row["remove-link"]?>"><i class="glyphicon glyphicon-trash"></i></a><?php endif; ?>
		</h4>
		<p><?=$row["description"]?></p>
	</div>
</div>
<?php endforeach; else: ?><div class="alert alert-info" role="alert">
	<?=$this->_flang("core", "media.list.noRows")?>
</div><?php endif; ?>

<script>
$(function () {
  	$('[data-toggle="tooltip"]').tooltip()
});
</script>