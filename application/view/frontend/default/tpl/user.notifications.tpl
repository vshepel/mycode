[if num!="0"][foreach rows]
[show-date]
<p align="center"><span class="label label-info">{date}</span></p>
[/show-date]

<div class="alert alert-{type}">
  <button type="button" class="close" onclick="app.core.notifications.remove('{id}'); return false;"><span aria-hidden="true">&times;</span></button>
  <p><strong>{title}</strong></p>
  <p><a href="{link}" class="text-{type}">{text}</a></p>
  <p><small>{date}, {time}</small></p>
</div>
[/foreach][/if]
[if num="0"]<div align="center" style="padding: 20px;">
	<p><span class="fa fa-bell-slash" style="font-size:50px"></span></p>
	<p class="text-primary">[f:user:notifications.noRows]</p>
</div>[/if]
