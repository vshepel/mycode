<ul class="nav nav-tabs" role="tablist">
	[perm user.statistics]<li[if ACTION="statistics"] class="active"[/if]><a href="{ADMIN_PATH}user/statistics">
		<span class="main-row-icon fa fa-dashboard"></span> [b:user:statistics.moduleName]
	</a></li>[/perm]
	
	[perm user.settings]<li[if ACTION="settings"] class="active"[/if]><a href="{ADMIN_PATH}user/settings">
		<span class="main-row-icon fa fa-cog"></span> [b:user:settings.moduleName]
	</a></li>[/perm]
	
	[perm user.groups]<li[if ACTION="groups"] class="active"[/if]><a href="{ADMIN_PATH}user/groups">
		<span class="main-row-icon fa fa-users"></span> [b:user:groups.moduleName]
	</a></li>[/perm]
	
	[perm user.list]<li[if ACTION="list"] class="active"[/if]><a href="{ADMIN_PATH}user/list">
		<span class="main-row-icon fa fa-list"></span> [b:user:list.moduleName]
	</a></li>[/perm]
	
	[perm user.add]<li[if ACTION="add"] class="active"[/if]><a href="{ADMIN_PATH}user/add">
		<span class="main-row-icon fa fa-plus"></span> [b:user:add.moduleName]
	</a></li>[/perm]
</ul><br>
