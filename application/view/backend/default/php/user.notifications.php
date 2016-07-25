<?php if ($tags["num"] > 0): ?>
<button class="btn btn-danger btn-xs pull-right" onclick="app.core.notifications.clear(); return false;"><span class="fa fa-remove"></span></button>
<?php foreach ($tags["rows"] as $row): ?>
<?php if($row["show-date"]): ?>
<p align="center"><span class="label label-info"><?=$row["date"]?></span></p>
<?php endif; ?>

<div class="alert alert-<?=$row["type"]?>">
  <button type="button" class="close" onclick="app.core.notifications.remove('<?=$row["id"]?>'); return false;"><span aria-hidden="true">&times;</span></button>
  <p><strong><?=$row["title"]?></strong></p>
  <p><a href="<?=$row["link"]?>" class="text-<?=$row["type"]?>"><?=$row["text"]?></a></p>
  <p><small><?=$row["date"]?>, <?=$row["time"]?></small></p>
</div>
<?php endforeach; ?><?php else: ?><div align="center" style="padding: 20px;">
	<p><span class="fa fa-bell-slash" style="font-size:50px"></span></p>
	<p class="text-primary"><?=$this->_flang("user", "notifications.noRows")?></p>
</div><?php endif; ?>
