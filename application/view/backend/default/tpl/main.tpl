<!DOCTYPE html>
<html lang="[b:core:lang.fname]">
<head>
	{meta}
	<title>{title}</title>
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
</head>
<body>
{ajax}

<header class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="{PATH}">
				<img alt="LOGO" src="{PATH}images/harmony-fav.png" height="20">
			</a>
			<a class="navbar-brand" href="{PATH}">[b:core:controlPanel]</a>
			<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>

		<nav class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				[if has-permission:core.main]<li><a href="{ADMIN_PATH}"><span class="fa fa-lg fa-home"></span> [b:core:main.moduleName]</a></li>[/if]
				[if has-permission:core.statistics]<li><a href="{ADMIN_PATH}core/statistics" title="[b:core:statistics.moduleName]">
					<span class="fa fa-lg fa-dashboard"></span>
					<span class="visible-xs-inline">[b:core:statistics.moduleName]</span>
				</a></li>[/if]
				[if has-permission:core.settings]<li><a href="{ADMIN_PATH}core/settings" title="[b:core:settings.moduleName]">
					<span class="fa fa-lg fa-cog"></span>
					<span class="visible-xs-inline">[b:core:settings.moduleName]</span>
				</a></li>[/if]
				[if has-permission:core.packages]<li><a href="{ADMIN_PATH}core/packages" title="[b:core:packages.moduleName]">
					<span class="fa fa-lg fa-archive"></span>
					<span class="visible-xs-inline">[b:core:packages.moduleName]</span>
				</a></li>[/if]
				[if has-permission:core.menu]<li><a href="{ADMIN_PATH}core/menu" title="[b:core:menu.moduleName]">
					<span class="fa fa-lg fa-list"></span>
					<span class="visible-xs-inline">[b:core:menu.moduleName]</span>
				</a></li>[/if]
				[if has-permission:core.media]<li><a href="{ADMIN_PATH}core/media" title="[b:core:media.moduleName]">
					<span class="fa fa-lg fa-image"></span>
					<span class="visible-xs-inline">[b:core:media.moduleName]</span>
				</a></li>[/if]
			</ul>

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
		</nav>
	</div>
</header>

<main class="container">
	<ol class="breadcrumb">
		{breadcrumbs}
	</ol>
	<div class="row">
		<aside class="col-md-3 col-sm-3">
			<div class="media well well-sm">
				<a class="pull-left" href="{profile-link}">
					<img class="media-object img-rounded" src="{avatar-link}" alt="{username}" style="max-width: 50px">
				</a>
				<div class="media-body">
					<h4 class="media-heading"><b>{username}</b></h4>
					{group-name}
				</div>
			</div>

			<div class="list-group">
				{menu}
			</div>
		</aside>

		<section class="col-md-9 col-sm-9">
			<div class="flash">{alerts}</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{title}</h3>
				</div>
				<div class="panel-body">{content}</div>
			</div>
		</section>
	</div>
</main>

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