<!DOCTYPE html>
<html lang="[b:core:lang.fname]">
<head>
	{meta}
	<title>{title}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">

	{link}
	<link rel="stylesheet" href="{VIEW}css/main.min.css">
</head>
<body>
{ajax}

<div id="overlay"></div>
<div id="core-notifications">{notifications}</div>

<section class="top_panel">
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
			[logged]
			<li class="user_panel">
				<ul>
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
				</ul>
			</li>
			[/logged]
		</ul>
	</div>
</section>

{script}
<script src="{VIEW}js/script.min.js"></script>
</body>
</html>