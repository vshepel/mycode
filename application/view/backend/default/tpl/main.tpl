<!DOCTYPE html>
<html lang="[b:core:lang.fname]">
<head>
	{meta}
	<title>{title}</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	{link}
	<link rel="icon" href="{PATH}images/harmony-fav.png" sizes="16x16" type="image/png">
	<link rel="stylesheet" href="{PATH}vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="{PATH}vendor/bootstrap/css/bootstrap-theme.min.css">
	<link href="{PATH}vendor/bootstrap-switch/css/bootstrap-switch.min.css" type="text/css" rel="stylesheet">
	<link rel="stylesheet" href="{PATH}vendor/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="{VIEW}styles.css">

	{script}
	<script src="{PATH}vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="{PATH}vendor/bootstrap-switch/js/bootstrap-switch.min.js"></script>
	<script src="{VIEW}scripts.js"></script>

	<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
	<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
	<script src="../../assets/js/ie-emulation-modes-warning.js"></script>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head><body>
{ajax}

<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="{ADMIN_PATH}">
				<img alt="LOGO" src="{PATH}images/harmony-fav.png" height="20">
			</a>
			<a class="navbar-brand" href="{ADMIN_PATH}">[b:core:controlPanel]</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="{notifications-link}" id="notifications-dropdown" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="fa fa-lg fa-bell[new-notifications] text-primary[/new-notifications]" id="core-notifications-new"></span>
						<span class="visible-xs-inline">[f:main:menu.notifications]</span>
					</a>
					<div class="dropdown-menu" aria-labelledby="notifications-dropdown" style="min-width:250px;padding:5px">
						<div id="core-notifications">{notifications}</div>
					</div>
				</li>
				<li>
					<a href="#" id="user-dropdown" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="fa fa-lg fa-user"></span> {username} <span class="caret"></span>
					</a>
					<ul class="dropdown-menu" aria-labelledby="user-dropdown">
						<li><a href="{profile-link}"><span class="fa fa-fw fa-user"></span> [f:main:user.account]</a></li>
						[if installed-module:messages]<li><a href="{messages-link}"><span class="fa fa-fw fa-envelope"></span> [b:messages:moduleName]</a></li>[/if]
						<li><a href="{logout-link}"><span class="fa fa-fw fa-sign-out"></span> [f:main:user.logout]</a></li>
					</ul>
				</li>
				<li><a href="{PATH}">
						<span class="fa fa-lg fa-globe"></span>
						[f:main:menu.siteLink]
					</a></li>
			</ul>
		</div>
	</div>
</nav>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				[foreach menu]
				<li role="presentation"[active] class="active"[/active]><a href="{link}"><span class="fa fa-fw fa-{icon}"></span>&nbsp; {title}</a></li>
				[/foreach]
			</ul>
		</div>
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<ol class="breadcrumb">{breadcrumbs}</ol>
			<div class="flash">{alerts}</div>
			<div>{content}</div>
		</div>
	</div>
</div>

<footer>
	<br><br>
	<div class="text-center">
		Copyright &copy; 2016. HarmonyCMS. All Rights Reserved
		<br><br>

		<form method="post" action="{SITE_PATH}core/lang" onchange="submit();" style="width:300px;margin:0 auto;">
			<select class="form-control" name="lang">
				[foreach languages]
				<option value="{value}"[active] selected[/active]>{name}</option>
				[/foreach]
			</select>
		</form>

	</div>
	<br><br>
</footer>

</body>
</html>
