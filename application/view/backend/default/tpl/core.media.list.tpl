<ul class="nav nav-tabs" role="tablist">
	<li class="active"><a href="{ADMIN_PATH}core/media/list"><span class="glyphicon glyphicon-picture"></span> [b:core:media.list.moduleName]</a></li>
	[if has-permission:core.media.upload]<li><a href="{ADMIN_PATH}core/media/upload"><span class="glyphicon glyphicon-upload"></span> [b:core:media.upload.moduleName]</a></li>[/if]
</ul><br>

[if num!="0"][foreach rows]
<div class="media">
	<div class="media-left">
		<a href="{file-link}"> 
			<img src="{icon-link}" style="width: 48px; height: 64px;" class="media-object">
		</a>
	</div>
	<div class="media-body">
		<h4 class="media-heading">
			<text title="{filename}" data-toggle="tooltip" data-placement="bottom">{name}</text>
			<small>{filesize}</small>
			[if has-permission:core.media.edit]<a href="{edit-link}"><i class="glyphicon glyphicon-pencil"></i></a>[/if]
			[if has-permission:core.media.remove]<a href="{remove-link}"><i class="glyphicon glyphicon-trash"></i></a>[/if]
		</h4>
		<p>{description}</p>
	</div>
</div>
[/foreach][/if][if num="0"]<div class="alert alert-info" role="alert">
	[f:core:media.list.noRows]
</div>[/if]

<script>
$(function () {
  	$('[data-toggle="tooltip"]').tooltip()
});
</script>