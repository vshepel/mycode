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
{text}..
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
