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
	<div class="notification-list">
		[if num!="0"][foreach rows]
		[show-date]
		<p>
			{date}
		</p>
		[/show-date]
		<div class="notif notif-{type}">
			<a href="{link}">
				<div class="type">
					
				</div>
				<p>
					{text}
					<small>{title}</small>
				</p>
			</a>
		</div>
		[/foreach][/if]
		[if num="0"]
		<div class="notification-nope">
			<i class="mdi mdi-emoticon-sad"></i>
			<p>[f:user:notifications.noRows]</p>
		</div>
		[/if]
	</div>
</div>