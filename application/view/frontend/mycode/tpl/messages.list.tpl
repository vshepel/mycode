<section class="block">
	<div class="title">[f:messages:mail.title]</div>
	<ul class="tabs">
		<li[if type="inbox"] class="active"[/if]>
			<a href="{PATH}messages/inbox">[b:messages:inbox.moduleName][if new-count!="0"] <span>{new-count}</span>[/if]</a>
		</li>
		<li[if type="outbox"] class="active"[/if]>
			<a href="{PATH}messages/outbox">[b:messages:outbox.moduleName]</a>
		</li>
	</ul>
	<div class="block-content message-content">
		[if num!="0"]
		[foreach rows]
		<div class="message[not-readed] not-read[/not-readed]">
			<div class="photo">
				<a href="{from-link}">
					<img src="{from-avatar-link}" alt="{from-login}">
				</a>
			</div>
			<div class="subject">
				<a href="{url}" class="message-text">
					<span>[if from-name!=""]{from-name}[/if][if from-name=""]{from-login}[/if]</span>
					{message}
				</a>
			</div>
			<div class="time">
				[if date!="[b:core:smartDate.today]"]{date}, [/if]{time}
			</div>
		</div>
		[/foreach]
		[/if]
		[if num="0"]
		<div class="post-nope">
			<i class="mdi mdi-email-open"></i>
			[f:messages:list.noRows]
		</div>
		[/if]
	</div>
</section>

{pagination}