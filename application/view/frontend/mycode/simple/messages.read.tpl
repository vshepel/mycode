<section class="block">
	<div class="title title-message">
		<a href="{from-link}"><img src="{from-avatar-link}" alt="{from-login}" /></a>
		<ul>
			<li>[f:messages:read.from]: <a href="{from-link}">[if from-name!=""]{from-name}[/if][if from-name=""]{from-login}[/if]</a></li>
			<li class="date">{date}, {time}</li>
		</ul>
	</div>
	<div class="block-content">
		<h2>{topic}</h2>
		<p>{message}</p>
		<div class="panel">
			<a href="/messages/send/{from-login}" class="btn-primary">[f:messages:read.reply]</a>
			[remove]<a href="{remove-link}" class="remove">[f:messages:read.remove]</a>[/remove]
		</div>
	</div>
</section>