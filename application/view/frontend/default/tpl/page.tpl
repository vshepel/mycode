<div class="article">
  <div class="pull-left">
    <h2>{title}</h2>
  </div>
  <div class="pull-right">
    [if has-permission:admin.page.edit]<br><a href="{edit-link}" class="btn btn-sm btn-primary">
      <span class="glyphicon glyphicon-pencil"></span> [b:page:edit.moduleName]
    </a>[/if]
    [if has-permission:admin.page.remove]<a href="{remove-link}" class="btn btn-sm btn-danger">
      <span class="glyphicon glyphicon-trash"></span>
    </a>[/if]
  </div>
  <div class="clearfix"></div>
  <p>{content}</p>
</div>