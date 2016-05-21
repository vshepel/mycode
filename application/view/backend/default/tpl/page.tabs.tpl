<ul class="nav nav-tabs" role="tablist">
	[if has-permission:page.settings]<li[if ACTION="settings"] class="active"[/if]><a href="{ADMIN_PATH}page/settings">
		<span class="main-row-icon fa fa-cog"></span> [b:page:settings.moduleName]
	</a></li>[/if]
	
	[if has-permission:page.list]<li[if ACTION="list"] class="active"[/if]><a href="{ADMIN_PATH}page/list">
		<span class="main-row-icon fa fa-list"></span> [b:page:list.moduleName]
	</a></li>[/if]
	
	[if has-permission:page.add]<li[if ACTION="add"] class="active"[/if]><a href="{ADMIN_PATH}page/add">
		<span class="main-row-icon fa fa-plus"></span> [b:page:add.moduleName]
	</a></li>[/if]
</ul><br>
