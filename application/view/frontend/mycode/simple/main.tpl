<!DOCTYPE html>
<html lang="[b:core:lang.fname]">
<head>
	{meta}
	<title>[if MODACT="blog/list"]mycode - make it happen[/if][if MODACT!="blog/list"]{title} - mycode[/if]</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">

	<meta name='yandex-verification' content='5ee21bdee54d526a' />

	{link}
	<link rel="icon" type="image/x-icon" href="{VIEW}img/favicon.png" />
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
				<!--noindex-->
				<a href="/" rel="nofollow">
					M<span class="short">ycode.</span><span class="project_name">P<span class="short">apers</span></span>
				</a>
				<!--/noindex-->
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
					[if has-permission:blog.add]
					<li class="write">
						<!--noindex--><a class="btn-primary" href="{SITE_PATH}blog/add" rel="nofollow">[f:main:panel.write]</a><!--/noindex-->
					</li>
					[/if]
					<li class="search_icon links"><a href="#"><i class="mdi mdi-magnify"></i></a></li>
					<li class="write_icon links"[if has-permission:blog.add]><a href="{SITE_PATH}blog/add"><i class="mdi mdi-pencil"></i></a></li>[/if]
					<li id="notification-open" class="links">
						<!--noindex-->
						<a href="#" rel="nofollow">
							<i class="mdi mdi-bell">[new-notifications]<span class="new"></span>[/new-notifications]</i>
						</a>
						<!--/noindex-->
					</li>
					<li class="links"><!--noindex--><a href="{profile-link}" rel="nofollow"><i class="mdi mdi-account"></i></a><!--/noindex--></li>
					[/logged]
					[not-logged]
					<li>
						<!--noindex-->
						<a class="btn-primary" href="{auth-link}" rel="nofollow">[f:main:panel.login]</a>
						<!--/noindex-->
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
		[if has-permission:admin]
		<section id="panel">
			<a href="{SITE_PATH}admin" target="_blank">
				<i class="mdi mdi-wrench"></i> [f:main:list.admin]
			</a>
		</section>
		[/if]
		[if SELF="/user/profile/{username}"]
		[logged]
		<section id="panel">
			<a href="/messages/inbox">
				<i class="mdi mdi-email"></i> [f:messages:list.title]
			</a>
			<a href="/user/edit">
				<i class="mdi mdi-settings"></i> [f:user:profile.link.edit]
			</a>
			<a href="/user/sessions">
				<i class="mdi mdi-login-variant"></i> [f:user:profile.link.sessions]
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
		[if MODACT="messages/list"]
		[logged]
		<section id="panel">
			<a href="{PATH}messages/send">
				<i class="mdi mdi-email"></i> [f:messages:link.create]
			</a>
			<a href="/user/profile/{username}">
				<i class="mdi mdi-arrow-left"></i> [f:user:edit.link.back]
			</a>
		</section>
		[/logged]
		[/if]
		[if MODACT="messages/page"]
		[logged]
		<section id="panel">
			<a href="/messages">
				<i class="mdi mdi-arrow-left"></i> [f:messages:link.back]
			</a>
		</section>
		[/logged]
		[/if]
		[if SELF="/messages/send"]
		[logged]
		<section id="panel">
			<a href="/messages">
				<i class="mdi mdi-arrow-left"></i> [f:messages:link.back]
			</a>
		</section>
		[/logged]
		[/if]
    	<section id="category">
    		<div class="title">[f:main:sidebar.category]</div>
    		{blog:categories}
    	</section>
    	<section id="popular">
    		<div class="title">[f:main:sidebar.popular]</div>
    		{blog:popular}
    	</section>
    	<section id="adv">
    		<div class="title">[f:main:sidebar.adv]</div>
    		<div class="adv_mockup"></div>
    	</section>
    	<footer>
    		<!--noindex--><a href="/user/list" rel="nofollow">[f:main:footer.users]</a><!--/noindex-->
    		<!--noindex--><a href="#" rel="nofollow">[f:main:footer.terms]</a><!--/noindex-->
    		<!--noindex--><a href="#" rel="nofollow">[f:main:footer.about]</a><!--/noindex-->
    	</footer>
	</aside>
</div>

<script src="{VIEW}js/script.min.js"></script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter34494495 = new Ya.Metrika({
                    id:34494495,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/34494495" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

</body>
</html>
