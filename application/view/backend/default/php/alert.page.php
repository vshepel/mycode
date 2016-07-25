<div class="alert alert-<?=$tags["type"]?>" role="alert">
	<?php switch ($tags["type"]) {
		case "success": echo '<span class="glyphicon glyphicon-ok-sign"></span>'; break;
		case "warning": echo '<span class="glyphicon glyphicon-warning-sign"></span>'; break;
		case "danger": echo '<span class="glyphicon glyphicon-remove-sign"></span>'; break;
		case "info": echo '<span class="glyphicon glyphicon-info-sign"></span>'; break;
	} ?>
	<?=$tags["message"]?>
</div>