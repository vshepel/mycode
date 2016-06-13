<section class="block">
	<div class="title">
		[f:messages:remove.title]
	</div>
	<div class="block-content">
		<form method="post" class="form">
			<input type="hidden" name="remove[id]" value="{id}">
			[f:messages:remove.description]
			<div class="panel">
				<button class="btn-primary" type="submit">[b:core:options.yes]</button>
				<a href="{PATH}messages">[b:core:options.no]</a>
			</div>
		</form>
	</div>
</section>