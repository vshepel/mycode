<section class="block">
	<div class="title">
		[f:messages:remove.title]
	</div>
	<div class="block-content">
		<form method="post" class="form">
			<input type="hidden" name="remove[id]" value="{id}">
			[f:messages:remove.description]
			<div class="panel">
				<button class="btn-primary" type="submit">[f:messages:remove.yes]</button>
				<a href="{PATH}messages">[f:messages:remove.no]</a>
			</div>
		</form>
	</div>
</section>