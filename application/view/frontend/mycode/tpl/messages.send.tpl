<section class="block">
	<div class="title">[f:messages:send.title]</div>
	<div class="block-content">

	<form class="form" enctype="multipart/form-data" method="post">
			<div class="field">
				<span>[f:messages:send.form.user]</span>
				<input type="input" name="user" value="{user}">
			</div>
			<div class="field">
				<span>[f:messages:send.form.topic]</span>
				<input type="input" name="topic" value="{topic}">
			</div>
			<div class="break"></div>
			<div class="field full">
				<span>[f:messages:send.form.message]</span>
				<textarea name="message" data-autoresize>{message}</textarea>
			</div>
			<div class="panel">
				<button class="btn-primary">
					[f:messages:send.form.btn]
				</button>
			</div>
		</form>

	</div>
</section>