<div>
	<div class="pull-left">
		[f:core:welcome], <strong>{username}</strong><br>
		[f:core:group]: <strong>{group-name}</strong><br>
		[f:core:version]: <strong>{version}</strong>
	</div>
	<div class="pull-right">
		<img alt="HarmonyCMS" src="{PATH}images/harmony-noshadow.png" height="60">
	</div>
	<div class="clearfix"></div>
</div>

<hr>

<div class="row placeholders">
	[if has-permission:core.statistics]<div class="col-xs-6 col-sm-4 placeholder"><div class="media">
		<a class="media-left" href="{statistics-link}"><span class="main-row-icon fa fa-fw fa-dashboard"></span></a>
		<div class="media-body">
			<h4 class="media-heading"><a href="{statistics-link}">[b:core:statistics.moduleName]</a></h4>
			[b:core:statistics.moduleDescription]
		</div>
	</div></div>[/if]

	[if has-permission:core.settings]<div class="col-xs-6 col-sm-4 placeholder"><div class="media">
		<a class="media-left" href="{settings-link}"><span class="main-row-icon fa fa-fw fa-cog"></span></a>
		<div class="media-body">
			<h4 class="media-heading"><a href="{settings-link}">[b:core:settings.moduleName]</a></h4>
			[b:core:settings.moduleDescription]
		</div>
	</div></div>[/if]

	[if has-permission:core.packages]<div class="col-xs-6 col-sm-4 placeholder"><div class="media">
		<a class="media-left" href="{packages-link}"><span class="main-row-icon fa fa-fw fa-archive"></span></a>
		<div class="media-body">
			<h4 class="media-heading"><a href="{packages-link}">[b:core:packages.moduleName]</a></h4>
			[b:core:packages.moduleDescription]
		</div>
	</div></div>[/if]
	
	[if has-permission:core.menu]<div class="col-xs-6 col-sm-4 placeholder"><div class="media">
		<a class="media-left" href="{menu-link}"><span class="main-row-icon fa fa-fw fa-list"></span></a>
		<div class="media-body">
			<h4 class="media-heading"><a href="{menu-link}">[b:core:menu.moduleName]</a></h4>
			[b:core:menu.moduleDescription]
		</div>
	</div></div>[/if]
	
	[if has-permission:core.media]<div class="col-xs-6 col-sm-4 placeholder"><div class="media">
		<a class="media-left" href="{media-link}"><span class="main-row-icon fa fa-fw fa-image"></span></a>
		<div class="media-body">
			<h4 class="media-heading"><a href="{media-link}">[b:core:media.moduleName]</a></h4>
			[b:core:media.moduleDescription]
		</div>
	</div></div>[/if]
</div>

[foreach packages][if has-permission:{name}]
<hr><div class="media">
	<a class="media-left" href="{link}"><img src="{icon-link}" width="70" height="70" alt="{title}"></a>
	<div class="media-body">
		<h4 class="media-heading"><a href="{link}">{title}</a></h4>
		{description}
	</div>
</div>
[/if][/foreach]


<style> .main-row-icon { font-size:40px; color:#333333; } </style>