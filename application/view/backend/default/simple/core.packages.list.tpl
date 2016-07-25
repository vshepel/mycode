[include "core.packages.tabs"]

<ul class="nav nav-pills">
	<li id="toggle-all" class="toggle active"><a href="#" onclick="showAll(); return false;">
		<span class="main-row-icon fa fa-home"></span>
		[f:core:packages.list.toggle.all]
	</a></li>
	<li id="toggle-module" class="toggle"><a href="#" onclick="show('module'); return false;">
		<span class="main-row-icon fa fa-archive"></span>
		[f:core:packages.list.toggle.packages]
	</a></li>
	<li id="toggle-library" class="toggle"><a href="#" onclick="show('library'); return false;">
		<span class="main-row-icon fa fa-book"></span>
		[f:core:packages.list.toggle.libraries]
	</a></li>
</ul><br>

<table class="table table-bordered">
	<thead><tr>
		<th width="32"></th>
		<th>[f:core:packages.list.table.title]</th>
		<th>[f:core:packages.list.table.description]</th>
		<th>[f:core:packages.list.table.author]</th>
		<th>[f:core:packages.list.table.license]</th>
		<th>[f:core:packages.list.table.files]</th>
		<th>[f:core:packages.list.table.dependences]</th>
		<th>[f:core:packages.list.table.type]</th>
		<th style="width:40px"></th>
	</tr></thead>
	<tbody>
		[foreach rows]
		<div class="modal fade" id="files-{name}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <h4 class="modal-title" id="myModalLabel">[f:core:packages.list.modal.titleFiles]</h4>
				</div>
				<div class="modal-body">
				  {files}
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-primary" data-dismiss="modal">[f:core:packages.list.modal.close]</button>
				</div>
			  </div>
			</div>
		  </div>

		  <div class="modal fade" id="dependences-{name}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <h4 class="modal-title" id="myModalLabel">[f:core:packages.list.modal.titleDependences]</h4>
				</div>
				<div class="modal-body">
				  {dependence}
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-primary" data-dismiss="modal">[f:core:packages.list.modal.close]</button>
				</div>
			  </div>
			</div>
		  </div>

		  <tr class="package package-type-{type}[dependent] info[/dependent][unused] warning[/unused][conflict] danger[/conflict]">
			  <td><img src="{PATH}images/modules/{backend-image}" width="32" alt="{name}"></td>
			  <td><a href="{frontend-link}">{name}</a> ({version})</td>
			  <td>{description}</td>
			  <td>{author}</td>
			  <td>{license}</td>
			  <td>
				  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#files-{name}">
					  <span class="glyphicon glyphicon-file"></span> [f:core:packages.list.filesList]
				  </button>
			  </td>
			  <td>
				  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#dependences-{name}">
					  <span class="glyphicon glyphicon-th"></span> [f:core:packages.list.dependencesList]
				  </button>
			  </td>
			  <td align="center">
				  [if type="module"]<span class="fa fa-archive"></span>[/if]
				  [if type="library"]<span class="fa fa-book"></span>[/if]
			  </td>
			  <td>
				  [remove]<a href="{remove-link}" class="btn btn-xs btn-danger">
					  <span class="glyphicon glyphicon-trash"></span>
				  </a>[/remove]
			  </td>
		  </tr>
		[/foreach]
	</tbody>
</table>

<table class="table table-bordered"><tbody>
	<tr>
		<td class="danger" width="35px"></td>
		<td>[f:core:packages.list.color.danger]</td>
	</tr>
	<tr>
		<td class="warning" width="35px"></td>
		<td>[f:core:packages.list.color.warning]</td>
	</tr>

	<tr>
		<td class="info" width="35px"></td>
		<td>[f:core:packages.list.color.info]</td>
	</tr>
</tbody></table>
	
<script type="text/javascript">
function show(name) {
	$('.package').css('display', 'none');
	$('.package-type-' + name).css('display', 'table-row');
	$('.toggle').removeClass('active');
	$('#toggle-' + name).addClass('active');
}

function showAll() {
	$('.package').css('display', 'table-row');
	$('.toggle').removeClass('active');
	$('#toggle-all').addClass('active');
}

show('module');
</script>