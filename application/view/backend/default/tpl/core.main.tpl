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
	[if has-permission:core.statistics]<div class="col-xs-12 col-sm-4 placeholder"><div class="media">
		<a class="media-left" href="{statistics-link}">
			<img class="module-row-icon" src="{PATH}images/icons/dashboard.svg" alt="[b:core:statistics.moduleName]">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="{statistics-link}">[b:core:statistics.moduleName]</a></h4>
			[b:core:statistics.moduleDescription]
		</div>
	</div></div>[/if]

	[if has-permission:core.settings]<div class="col-xs-12 col-sm-4 placeholder"><div class="media">
		<a class="media-left" href="{settings-link}">
			<img class="module-row-icon" src="{PATH}images/icons/cog.svg" alt="[b:core:settings.moduleName]">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="{settings-link}">[b:core:settings.moduleName]</a></h4>
			[b:core:settings.moduleDescription]
		</div>
	</div></div>[/if]

	[if has-permission:core.packages]<div class="col-xs-12 col-sm-4 placeholder"><div class="media">
		<a class="media-left" href="{packages-link}">
			<img class="module-row-icon" src="{PATH}images/icons/brick.svg" alt="[b:core:packages.moduleName]">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="{packages-link}">[b:core:packages.moduleName]</a></h4>
			[b:core:packages.moduleDescription]
		</div>
	</div></div>[/if]
	
	[if has-permission:core.menu]<div class="col-xs-12 col-sm-4 placeholder"><div class="media">
		<a class="media-left" href="{menu-link}">
			<img class="module-row-icon" src="{PATH}images/icons/layers.svg" alt="[b:core:menu.moduleName]">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="{menu-link}">[b:core:menu.moduleName]</a></h4>
			[b:core:menu.moduleDescription]
		</div>
	</div></div>[/if]
	
	[if has-permission:core.media]<div class="col-xs-12 col-sm-4 placeholder"><div class="media">
		<a class="media-left" href="{media-link}">
			<img class="module-row-icon" src="{PATH}images/icons/file-picture.svg" alt="[b:core:media.moduleName]">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="{media-link}">[b:core:media.moduleName]</a></h4>
			[b:core:media.moduleDescription]
		</div>
	</div></div>[/if]
</div>
<hr>

[foreach packages][if has-permission:{name}]
<div class="media">
	<a class="media-left" href="{link}"><img class="module-row-icon" src="{icon-link}" alt="{title}"></a>
	<div class="media-body">
		<h4 class="media-heading"><a href="{link}">{title}</a></h4>
		{description}
	</div>
</div><br>
[/if][/foreach]

<style>
	.module-row-icon {
		width: 48px;
	}
</style>