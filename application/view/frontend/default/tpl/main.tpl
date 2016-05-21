<!DOCTYPE html>
<html lang="[b:core:lang.fname]">
<head>
	{meta}
	<title>{title}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	{link}
	<link rel="stylesheet" href="{PATH}vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="{PATH}vendor/bootstrap/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="{PATH}vendor/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="{VIEW}styles.css">

	{script}
	<script src="{PATH}vendor/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
{ajax}

<header class="navbar navbar-default container">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="{SITE_PATH}">{name}</a>
			<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>

		<nav class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				{menu}
			</ul>

			[logged]<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="{notifications-link}" id="notifications-dropdown" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="fa  fa-lg fa-bell[new-notifications] text-primary[/new-notifications]" id="core-notifications-new"></span>
						<span class="visible-xs-inline">[f:main:menu.notifications]</span>
					</a>
					<div class="dropdown-menu" aria-labelledby="notifications-dropdown" style="min-width:250px;padding:5px">
						<div id="core-notifications">{notifications}</div>
					</div>
				</li>
				[admin]<li><a href="{admin-link}">
					<span class="fa fa-lg fa-dashboard"></span>
					[f:main:menu.controlPanel]
				</a></li>[/admin]
			</ul>[/logged]
		</nav>
	</div>
</header>

<main class="container">
	<ol class="breadcrumb">
		{breadcrumbs}
	</ol>

	<div class="row">
		<aside class="col-md-3 col-sm-3">
			[logged]<div class="media well well-sm">
				<a class="pull-left" href="{profile-link}">
					<img class="media-object img-rounded" src="{avatar-link}" alt="{username}" style="max-width: 50px">
				</a>
				<div class="media-body">
					<h4 class="media-heading"><b>{username}</b></h4>
					{group-name}
				</div>
			</div>[/logged]

			<div class="list-group">
				[not-logged]<a class="list-group-item" href="{auth-link}"><span class="fa fa-fw fa-sign-in"></span>&nbsp; [b:user:auth.moduleName]</a>
				<a class="list-group-item" href="{register-link}"><span class="fa fa-fw fa-user-plus"></span>&nbsp; [b:user:register.moduleName]</a>[/not-logged]
				[logged]<a class="list-group-item" href="{profile-link}"><span class="fa fa-fw fa-user"></span>&nbsp; [f:main:user.profile]</a>
				<a class="list-group-item" href="{messages-link}"><span class="fa fa-fw fa-envelope"></span>&nbsp; [b:messages:moduleName]</a>
				<a class="list-group-item" href="{logout-link}"><span class="fa fa-fw fa-sign-out"></span>&nbsp; [f:main:user.logout]</a>[/logged]
			</div>

			[if installed-module:blog]<div class="well well-sm">
				<h4>[b:blog:categories.moduleName]</h4>
				<div class="list-group">{blog:categories}</div>
			</div>
			<div class="well well-sm">
				<h4>[b:blog:calendar.moduleName]</h4>
				{blog:calendar}
			</div>
			<div class="well well-sm">
				<h4>[b:blog:archive.moduleName]</h4>
				<div class="list-group">{blog:archive}</div>
			</div>
			[/if]
		</aside>

		<section class="col-md-9 col-sm-9">
			<div class="flash">{alerts}</div>
			{content}
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