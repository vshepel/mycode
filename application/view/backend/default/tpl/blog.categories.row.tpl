<tr>
	<td>[if id!="0"]{id}[/if]</td>
	<td><a href="{category-link}">{name}</a></td>
	<td>{posts-num}</td>
	<td style="padding: 3px">
		<form method="post">
			<input type="hidden" name="category_remove[id]" value="{id}">
			<button type="submit" class="btn btn-danger btn-sm[if id="0"] disabled[/if]" style="width:100%">
				<span class="glyphicon glyphicon-trash"></span></button>
		</form>
	</td>
</tr>
