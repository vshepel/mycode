<!DOCTYPE html>
<html lang="[b:core:lang.fname]">
<head>
	{meta}
	<title>{title}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">

	{link}
	<link rel="stylesheet" href="{VIEW}css/main.min.css">

	{script}
	<script src="{VIEW}js/script.min.js"></script>
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
				<form method="post" action="{SITE_PATH}blog/search">
					<input type="text" name="query" placeholder="[f:main:panel.search]">
				</form>
			</li>
			<li class="user_panel">
				<ul>
					[logged]
					<li class="write">
						<a class="btn btn-primary" href="{add-link}">[f:main:panel.write]</a>
					</li>
					<li class="search_icon links"><a href="/blog/search"><i class="mdi mdi-magnify"></i></a></li>
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
						<a class="btn btn-primary" href="{auth-link}">[f:main:panel.login]</a>
					</li>
					[/not-logged]
				</ul>
			</li>
		</ul>
	</div>
</header>

<div id="main" class="container">
	<main>
		{alerts}
		{content}
	</main>
	<aside id="sidebar" class="col_3">
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
    		<a href="#">[f:main:footer.help]</a>
    		<a href="#">[f:main:footer.terms]</a>
    		<a href="#">[f:main:footer.about]</a>
    	</footer>
	</aside>
</div>

</body>
</html>