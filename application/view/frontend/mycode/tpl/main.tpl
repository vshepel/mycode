<!DOCTYPE html>
<html lang="[b:core:lang.fname]">
<head>
	{meta}
	<title>{title} &bull; MYCODE.PRO</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />

	{link}
	<link rel="icon" type="image/x-icon" href="{VIEW}img/favicon.ico" />
	<link rel="stylesheet" href="{VIEW}css/main.min.css" />

	{script}
	<!--[if IE]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv-printshiv.min.js"></script>
	<![endif]-->
</head>
<body>
{ajax}

<div id="overlay"></div>
[logged]
<div class="notification-list">
	<div class="notification">
		<div class="notification-head">
			<div class="head-close">
				<i id="notification-close" class="mdi mdi-arrow-right"></i>
			</div>
			<div class="head-count">
				[f:user:notifications.title]
			</div>
			<div class="head-clear">
				<i class="mdi mdi-delete-sweep" onclick="app.core.notifications.clear(); return false;"></i>
			</div>
		</div>
		<div id="core-notifications">
			{notifications}
		</div>
	</div>
</div>
[/logged]

<header class="top_panel">
	<div class="container">
		<ul>
			<li class="logotype">
				<a href="/">
					M<span class="short">ycode.</span><span class="project_name">P<span class="short">apers</span></span>
				</a>
			</li>
			<li class="search">
				<i class="mdi mdi-close"></i>
				<form method="post" action="{SITE_PATH}blog/search">
					<input type="text" name="query" placeholder="[f:main:panel.search]">
				</form>
			</li>
			<li class="user_panel">
				<ul>
					[logged]
					<li class="write">
						<a class="btn-primary" href="{add-link}">[f:main:panel.write]</a>
					</li>
					<li class="search_icon links"><a href="#"><i class="mdi mdi-magnify"></i></a></li>
					<li class="write_icon links"><a href="{add-link}"><i class="mdi mdi-pencil"></i></a></li>
					<li id="notification-open" class="links">
						<a href="#">
							<i class="mdi mdi-bell">[new-notifications]<span class="new"></span>[/new-notifications]</i>
						</a>
					</li>
					<li class="links"><a href="{profile-link}"><i class="mdi mdi-account"></i></a></li>
					[/logged]
					[not-logged]
					<li>
						<a class="btn-primary" href="{auth-link}">[f:main:panel.login]</a>
					</li>
					[/not-logged]
				</ul>
			</li>
		</ul>
	</div>
</header>

<div id="main" class="container">
	<main id="content">
		{alerts}
		{content}
	</main>
	<aside id="sidebar">
		[if SELF="/user/profile/{username}"]
		[logged]
		<section id="panel">
			<a href="/user/edit">
				<i class="mdi mdi-settings"></i> [f:user:profile.link.edit]
			</a>
			<a href="/user/sessions">
				<i class="mdi mdi-login-variant"></i> [f:user:edit.link.sessions]
			</a>
			<a href="/user/logout">
				<i class="mdi mdi-logout-variant"></i> [f:user:profile.link.exit]
			</a>
		</section>
		[/logged]
		[/if]
		[if SELF="/user/edit"]
		[logged]
		<section id="panel">
			<a href="/user/profile/{username}">
				<i class="mdi mdi-arrow-left"></i> [f:user:edit.link.back]
			</a>
		</section>
		[/logged]
		[/if]
		[if SELF="/user/sessions"]
		[logged]
		<section id="panel">
			<a href="/user/profile/{username}">
				<i class="mdi mdi-arrow-left"></i> [f:user:edit.link.back]
			</a>
		</section>
		[/logged]
		[/if]
    	<section id="category">
    		<div class="title">[b:blog:categories.moduleName]</div>
    		{blog:categories}
    	</section>
    	<section id="popular">
    		<div class="title">[b:blog:popular.moduleName]</div>
    		{blog:popular}
    	</section>
    	<section id="adv">
    		<div class="title">[f:main:sidebar.adv]</div>
    		<div class="adv_mockup"></div>
    	</section>
    	<footer>
    		<a href="/user/list">[f:main:footer.users]</a>
    		<a href="#">[f:main:footer.terms]</a>
    		<a href="#">[f:main:footer.about]</a>
    	</footer>
	</aside>
</div>

<script src="{VIEW}js/script.min.js"></script>

</body>
</html>