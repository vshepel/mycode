<!DOCTYPE html>
<html lang="<?=$this->_lang("core", "lang.fname")?>">
<head>
	<?=$tags["meta"]?>
	<title><?=$tags["title"]?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?=$tags["link"]?>
	<link rel="stylesheet" href="<?=PATH?>vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=PATH?>vendor/bootstrap/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="<?=PATH?>vendor/bootstrap-switch/css/bootstrap-switch.min.css">
	<link rel="stylesheet" href="<?=PATH?>vendor/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?=VIEW_PATH?>styles.css">

	<?=$tags["script"]?>
	<script src="<?=PATH?>vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?=PATH?>vendor/bootstrap-switch/js/bootstrap-switch.min.js"></script>
	<script src="<?=VIEW_PATH?>scripts.js"></script>
</head>
<body>
<?=$tags["ajax"]?>

<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?=ADMIN_PATH?>">
				<img alt="LOGO" src="<?=PATH?>images/harmony-fav.png" height="20">
			</a>
			<a class="navbar-brand" href="<?=ADMIN_PATH?>"><?=$this->_lang("core", "controlPanel")?></a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="<?=$tags["notifications-link"]?>" id="notifications-dropdown" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="fa  fa-lg fa-bell<?php if($tags["new-notifications"]): ?> text-primary<?php endif; ?>" id="core-notifications-new"></span>
						<span class="visible-xs-inline"><?=$this->_flang("main", "menu.notifications")?></span>
					</a>
					<div class="dropdown-menu" aria-labelledby="notifications-dropdown" style="min-width:250px;padding:5px">
						<div id="core-notifications"><?=$tags["notifications"]?></div>
					</div>
				</li>
				<li>
					<a href="#" id="user-dropdown" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="fa fa-lg fa-user"></span> <?=$this->_user->get("login")?> <span class="caret"></span>
					</a>
					<ul class="dropdown-menu" aria-labelledby="user-dropdown">
						<li><a href="<?=$tags["profile-link"]?>"><span class="fa fa-fw fa-user"></span> <?=$this->_flang("main", "user.account")?></a></li>
						<?php if($this->_pkg->exists("messages")): ?><li><a href="<?=$tags["messages-link"]?>">
							<span class="fa fa-fw fa-envelope"></span>
							<?=$this->_lang("messages", "moduleName")?>
						</a></li><?php endif; ?>
						<li><a href="<?=$tags["logout-link"]?>"><span class="fa fa-fw fa-sign-out"></span> <?=$this->_flang("main", "user.logout")?></a></li>
					</ul>
				</li>
				<li><a href="<?=SITE_PATH?>">
						<span class="fa fa-lg fa-globe"></span>
						<?=$this->_flang("main", "menu.siteLink")?>
					</a></li>
			</ul>
		</div>
	</div>
</nav>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<?php foreach ($tags["menu"] as $row): ?>
					<li role="presentation"<?=$row["active"] ? " class=\"active\"" : ""?>><a href="<?=$row["link"]?>"><span class="fa fa-fw fa-<?=$row["icon"]?>"></span> <?=$row["title"]?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<ol class="breadcrumb"><?=$tags["breadcrumbs"]?></ol>
			<div class="flash"><?=$tags["alerts"]?></div>
			<div><?=$tags["content"]?></div>
		</div>
	</div>
</div>

<footer>
	<br><br>
	<div class="text-center">
		Copyright &copy; <?=date("Y")?>. HarmonyCMS. All Rights Reserved
		<br><br>

		<form method="post" action="<?=SITE_PATH?>core/lang" onchange="submit();" style="width:300px;margin:0 auto;">
			<select class="form-control" name="lang">
				<?php foreach($tags["languages"] as $row): ?>
					<option value="<?=$row["value"]?>"<?php if($row["active"]) { echo " selected"; } ?>><?=$row["name"]?></option>
				<?php endforeach; ?>
			</select>
		</form>

	</div>
	<br><br>
</footer>

</body>
</html>
