<ul class="nav nav-tabs" role="tablist">
	<li<?=$tags["type"] == "frontend" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>core/menu/list/frontend">
        <span class="main-row-icon fa fa-globe"></span> <?=$this->_lang("core", "menu.type.frontend")?>
    </a></li>
	<li<?=$tags["type"] == "backend" ? " class=\"active\"" : ""?>><a href="<?=ADMIN_PATH?>core/menu/list/backend">
        <span class="main-row-icon fa fa-dashboard"></span> <?=$this->_lang("core", "menu.type.backend")?>
    </a></li>
</ul><br>

<?=$this->_flang("core", "menu.list.total")?> <span class="badge"><?=$tags["num"]?></span><br><br>

<table class="table">
	<thead><tr>
		<th style="width: 40px">#</th>
		<th style="width: 40px"><?=$this->_flang("core", "menu.list.table.icon")?></th>
		<th style="width: 150px"><?=$this->_flang("core", "menu.list.table.link")?></th>
		<th><?=$this->_flang("core", "menu.list.table.title")?></th>
		<th style="width: 100px;"><?=$this->_flang("core", "menu.list.table.pos")?></th>
		<th style="width: 75px;"></th>
	</tr></thead>
	<tbody>
		<?php foreach($tags["rows"] as $row): ?><tr <?=$row["edit"] ? "class=\"info\"" : ""?>><form method="post"><?php if($row["not-edit"]): ?>
			<input type="hidden" name="item_id" value="<?=$row["id"]?>">
			<td><?=$row["id"]?></td>
			<td align="center"><span class="fa fa-<?=$row["icon"]?>"></span></td>
			<td><a href="<?=$row["link"]?>"><?=$row["link"]?></a></td>
			<td><?=$row["title"]?></td>
			<td align="center"><?=$row["pos"]?></td>
			<td style="padding: 3px">
				<form method="post">
					<div class="btn-group btn-group-sm">
						<button type="submit" name="edit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-pencil"></span></button>
						<button type="submit" name="remove" class="btn btn-danger btn-sm">
							<span class="glyphicon glyphicon-trash"></span>
						</button>
					</div>
				</form>
			</td>
		<?php else: ?>
			<input type="hidden" name="item_id" value="<?=$row["id"]?>">
			<input type="hidden" name="edit[type]" value="<?=$row["type"]?>">
			<td colspan="2" style="padding: 3px">
				<input type="text" class="form-control input-sm" name="edit[icon]" value="<?=$row["icon"]?>">
			</td>
			<td colspan="1" style="padding: 3px">
				<input type="text" class="form-control input-sm" name="edit[link]" value="<?=$row["link"]?>">
			</td>
			<td colspan="1" style="padding: 3px">
				<input type="text" class="form-control input-sm" name="edit[title]" value="<?=$row["original-title"]?>">
			</td>
			<td colspan="1" style="padding: 3px">
				<input type="text" class="form-control input-sm" name="edit[pos]" value="<?=$row["pos"]?>">
			</td>
			<td style="padding: 3px">
				<button type="submit" class="btn btn-primary btn-sm" style="width:100%"><span class="glyphicon glyphicon-floppy-disk"></span></button>
			</td>
        <?php endif; ?></form></tr><?php endforeach; ?>

		<tr><form method="post">
			<input type="hidden" name="add[type]" value="<?=$tags["type"]?>">
			<td colspan="2" style="padding: 3px">
				<input type="text" class="form-control input-sm" name="add[icon]">
			</td>
			<td colspan="1" style="padding: 3px">
				<input type="text" class="form-control input-sm" name="add[link]">
			</td>
			<td colspan="1" style="padding: 3px">
				<input type="text" class="form-control input-sm" name="add[title]">
			</td>
			<td colspan="1" style="padding: 3px">
				<input type="text" class="form-control input-sm" name="add[pos]">
			</td>
			<td style="padding: 3px">
				<button class="btn btn-primary btn-sm" style="width:100%"><span class="glyphicon glyphicon-plus"></span></button>
			</td>

		</form></tr>
	</tbody>
</table>

<style>
.fontawesome-icon-list {
  margin-top: 22px;
}
.fontawesome-icon-list .fa-hover a {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  display: block;
  color: #222222;
  line-height: 32px;
  height: 32px;
  padding-left: 10px;
  border-radius: 4px;
}
.fontawesome-icon-list .fa-hover a .fa {
  width: 32px;
  font-size: 14px;
  display: inline-block;
  text-align: right;
  margin-right: 10px;
}
.fontawesome-icon-list .fa-hover a:hover {
  background-color: #1d9d74;
  color: #ffffff;
  text-decoration: none;
}
.fontawesome-icon-list .fa-hover a:hover .fa {
  font-size: 28px;
  vertical-align: -6px;
}
.fontawesome-icon-list .fa-hover a:hover .text-muted {
  color: #bbe2d5;
}
</style>

<h3>
	<?=$this->_flang("core", "menu.list.icons")?>
	<button class="btn btn-primary btn-sm" onclick="$('#icons-list').toggle(); return false;">
		<span class="glyphicon glyphicon-menu-hamburger"></span>
	</button>
</h3>

<div class="bs-glyphicons" id="icons-list" style="display:none">
	<div id="icons">

    <section id="web-application">
  <h2 class="page-header">Web Application Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/adjust"><i class="fa fa-adjust"></i> adjust</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/anchor"><i class="fa fa-anchor"></i> anchor</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/archive"><i class="fa fa-archive"></i> archive</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/area-chart"><i class="fa fa-area-chart"></i> area-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrows"><i class="fa fa-arrows"></i> arrows</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrows-h"><i class="fa fa-arrows-h"></i> arrows-h</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrows-v"><i class="fa fa-arrows-v"></i> arrows-v</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/asterisk"><i class="fa fa-asterisk"></i> asterisk</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/at"><i class="fa fa-at"></i> at</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/car"><i class="fa fa-automobile"></i> automobile <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/balance-scale"><i class="fa fa-balance-scale"></i> balance-scale</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/ban"><i class="fa fa-ban"></i> ban</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/university"><i class="fa fa-bank"></i> bank <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bar-chart"><i class="fa fa-bar-chart"></i> bar-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bar-chart"><i class="fa fa-bar-chart-o"></i> bar-chart-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/barcode"><i class="fa fa-barcode"></i> barcode</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bars"><i class="fa fa-bars"></i> bars</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/battery-empty"><i class="fa fa-battery-0"></i> battery-0 <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/battery-quarter"><i class="fa fa-battery-1"></i> battery-1 <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/battery-half"><i class="fa fa-battery-2"></i> battery-2 <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/battery-three-quarters"><i class="fa fa-battery-3"></i> battery-3 <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/battery-full"><i class="fa fa-battery-4"></i> battery-4 <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/battery-empty"><i class="fa fa-battery-empty"></i> battery-empty</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/battery-full"><i class="fa fa-battery-full"></i> battery-full</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/battery-half"><i class="fa fa-battery-half"></i> battery-half</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/battery-quarter"><i class="fa fa-battery-quarter"></i> battery-quarter</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/battery-three-quarters"><i class="fa fa-battery-three-quarters"></i> battery-three-quarters</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bed"><i class="fa fa-bed"></i> bed</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/beer"><i class="fa fa-beer"></i> beer</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bell"><i class="fa fa-bell"></i> bell</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bell-o"><i class="fa fa-bell-o"></i> bell-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bell-slash"><i class="fa fa-bell-slash"></i> bell-slash</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bell-slash-o"><i class="fa fa-bell-slash-o"></i> bell-slash-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bicycle"><i class="fa fa-bicycle"></i> bicycle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/binoculars"><i class="fa fa-binoculars"></i> binoculars</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/birthday-cake"><i class="fa fa-birthday-cake"></i> birthday-cake</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bluetooth"><i class="fa fa-bluetooth"></i> bluetooth</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bluetooth-b"><i class="fa fa-bluetooth-b"></i> bluetooth-b</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bolt"><i class="fa fa-bolt"></i> bolt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bomb"><i class="fa fa-bomb"></i> bomb</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/book"><i class="fa fa-book"></i> book</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bookmark"><i class="fa fa-bookmark"></i> bookmark</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bookmark-o"><i class="fa fa-bookmark-o"></i> bookmark-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/briefcase"><i class="fa fa-briefcase"></i> briefcase</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bug"><i class="fa fa-bug"></i> bug</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/building"><i class="fa fa-building"></i> building</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/building-o"><i class="fa fa-building-o"></i> building-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bullhorn"><i class="fa fa-bullhorn"></i> bullhorn</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bullseye"><i class="fa fa-bullseye"></i> bullseye</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bus"><i class="fa fa-bus"></i> bus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/taxi"><i class="fa fa-cab"></i> cab <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/calculator"><i class="fa fa-calculator"></i> calculator</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/calendar"><i class="fa fa-calendar"></i> calendar</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/calendar-check-o"><i class="fa fa-calendar-check-o"></i> calendar-check-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/calendar-minus-o"><i class="fa fa-calendar-minus-o"></i> calendar-minus-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/calendar-o"><i class="fa fa-calendar-o"></i> calendar-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/calendar-plus-o"><i class="fa fa-calendar-plus-o"></i> calendar-plus-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/calendar-times-o"><i class="fa fa-calendar-times-o"></i> calendar-times-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/camera"><i class="fa fa-camera"></i> camera</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/camera-retro"><i class="fa fa-camera-retro"></i> camera-retro</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/car"><i class="fa fa-car"></i> car</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-down"><i class="fa fa-caret-square-o-down"></i> caret-square-o-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-left"><i class="fa fa-caret-square-o-left"></i> caret-square-o-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-right"><i class="fa fa-caret-square-o-right"></i> caret-square-o-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-up"><i class="fa fa-caret-square-o-up"></i> caret-square-o-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cart-arrow-down"><i class="fa fa-cart-arrow-down"></i> cart-arrow-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cart-plus"><i class="fa fa-cart-plus"></i> cart-plus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc"><i class="fa fa-cc"></i> cc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/certificate"><i class="fa fa-certificate"></i> certificate</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/check"><i class="fa fa-check"></i> check</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/check-circle"><i class="fa fa-check-circle"></i> check-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/check-circle-o"><i class="fa fa-check-circle-o"></i> check-circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/check-square"><i class="fa fa-check-square"></i> check-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/check-square-o"><i class="fa fa-check-square-o"></i> check-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/child"><i class="fa fa-child"></i> child</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/circle"><i class="fa fa-circle"></i> circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/circle-o"><i class="fa fa-circle-o"></i> circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/circle-o-notch"><i class="fa fa-circle-o-notch"></i> circle-o-notch</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/circle-thin"><i class="fa fa-circle-thin"></i> circle-thin</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/clock-o"><i class="fa fa-clock-o"></i> clock-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/clone"><i class="fa fa-clone"></i> clone</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/times"><i class="fa fa-close"></i> close <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cloud"><i class="fa fa-cloud"></i> cloud</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cloud-download"><i class="fa fa-cloud-download"></i> cloud-download</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cloud-upload"><i class="fa fa-cloud-upload"></i> cloud-upload</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/code"><i class="fa fa-code"></i> code</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/code-fork"><i class="fa fa-code-fork"></i> code-fork</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/coffee"><i class="fa fa-coffee"></i> coffee</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cog"><i class="fa fa-cog"></i> cog</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cogs"><i class="fa fa-cogs"></i> cogs</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/comment"><i class="fa fa-comment"></i> comment</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/comment-o"><i class="fa fa-comment-o"></i> comment-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/commenting"><i class="fa fa-commenting"></i> commenting</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/commenting-o"><i class="fa fa-commenting-o"></i> commenting-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/comments"><i class="fa fa-comments"></i> comments</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/comments-o"><i class="fa fa-comments-o"></i> comments-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/compass"><i class="fa fa-compass"></i> compass</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/copyright"><i class="fa fa-copyright"></i> copyright</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/creative-commons"><i class="fa fa-creative-commons"></i> creative-commons</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/credit-card"><i class="fa fa-credit-card"></i> credit-card</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/credit-card-alt"><i class="fa fa-credit-card-alt"></i> credit-card-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/crop"><i class="fa fa-crop"></i> crop</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/crosshairs"><i class="fa fa-crosshairs"></i> crosshairs</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cube"><i class="fa fa-cube"></i> cube</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cubes"><i class="fa fa-cubes"></i> cubes</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cutlery"><i class="fa fa-cutlery"></i> cutlery</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/tachometer"><i class="fa fa-dashboard"></i> dashboard <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/database"><i class="fa fa-database"></i> database</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/desktop"><i class="fa fa-desktop"></i> desktop</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/diamond"><i class="fa fa-diamond"></i> diamond</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/dot-circle-o"><i class="fa fa-dot-circle-o"></i> dot-circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/download"><i class="fa fa-download"></i> download</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pencil-square-o"><i class="fa fa-edit"></i> edit <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/ellipsis-h"><i class="fa fa-ellipsis-h"></i> ellipsis-h</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/ellipsis-v"><i class="fa fa-ellipsis-v"></i> ellipsis-v</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/envelope"><i class="fa fa-envelope"></i> envelope</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/envelope-o"><i class="fa fa-envelope-o"></i> envelope-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/envelope-square"><i class="fa fa-envelope-square"></i> envelope-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/eraser"><i class="fa fa-eraser"></i> eraser</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/exchange"><i class="fa fa-exchange"></i> exchange</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/exclamation"><i class="fa fa-exclamation"></i> exclamation</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/exclamation-circle"><i class="fa fa-exclamation-circle"></i> exclamation-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/exclamation-triangle"><i class="fa fa-exclamation-triangle"></i> exclamation-triangle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/external-link"><i class="fa fa-external-link"></i> external-link</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/external-link-square"><i class="fa fa-external-link-square"></i> external-link-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/eye"><i class="fa fa-eye"></i> eye</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/eye-slash"><i class="fa fa-eye-slash"></i> eye-slash</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/eyedropper"><i class="fa fa-eyedropper"></i> eyedropper</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/fax"><i class="fa fa-fax"></i> fax</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/rss"><i class="fa fa-feed"></i> feed <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/female"><i class="fa fa-female"></i> female</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/fighter-jet"><i class="fa fa-fighter-jet"></i> fighter-jet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-archive-o"><i class="fa fa-file-archive-o"></i> file-archive-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-audio-o"><i class="fa fa-file-audio-o"></i> file-audio-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-code-o"><i class="fa fa-file-code-o"></i> file-code-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-excel-o"><i class="fa fa-file-excel-o"></i> file-excel-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-image-o"><i class="fa fa-file-image-o"></i> file-image-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-video-o"><i class="fa fa-file-movie-o"></i> file-movie-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-pdf-o"><i class="fa fa-file-pdf-o"></i> file-pdf-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-image-o"><i class="fa fa-file-photo-o"></i> file-photo-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-image-o"><i class="fa fa-file-picture-o"></i> file-picture-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-powerpoint-o"><i class="fa fa-file-powerpoint-o"></i> file-powerpoint-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-audio-o"><i class="fa fa-file-sound-o"></i> file-sound-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-video-o"><i class="fa fa-file-video-o"></i> file-video-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-word-o"><i class="fa fa-file-word-o"></i> file-word-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-archive-o"><i class="fa fa-file-zip-o"></i> file-zip-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/film"><i class="fa fa-film"></i> film</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/filter"><i class="fa fa-filter"></i> filter</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/fire"><i class="fa fa-fire"></i> fire</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/fire-extinguisher"><i class="fa fa-fire-extinguisher"></i> fire-extinguisher</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/flag"><i class="fa fa-flag"></i> flag</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/flag-checkered"><i class="fa fa-flag-checkered"></i> flag-checkered</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/flag-o"><i class="fa fa-flag-o"></i> flag-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bolt"><i class="fa fa-flash"></i> flash <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/flask"><i class="fa fa-flask"></i> flask</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/folder"><i class="fa fa-folder"></i> folder</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/folder-o"><i class="fa fa-folder-o"></i> folder-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/folder-open"><i class="fa fa-folder-open"></i> folder-open</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/folder-open-o"><i class="fa fa-folder-open-o"></i> folder-open-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/frown-o"><i class="fa fa-frown-o"></i> frown-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/futbol-o"><i class="fa fa-futbol-o"></i> futbol-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/gamepad"><i class="fa fa-gamepad"></i> gamepad</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/gavel"><i class="fa fa-gavel"></i> gavel</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cog"><i class="fa fa-gear"></i> gear <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cogs"><i class="fa fa-gears"></i> gears <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/gift"><i class="fa fa-gift"></i> gift</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/glass"><i class="fa fa-glass"></i> glass</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/globe"><i class="fa fa-globe"></i> globe</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/graduation-cap"><i class="fa fa-graduation-cap"></i> graduation-cap</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/users"><i class="fa fa-group"></i> group <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-rock-o"><i class="fa fa-hand-grab-o"></i> hand-grab-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-lizard-o"><i class="fa fa-hand-lizard-o"></i> hand-lizard-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-paper-o"><i class="fa fa-hand-paper-o"></i> hand-paper-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-peace-o"><i class="fa fa-hand-peace-o"></i> hand-peace-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-pointer-o"><i class="fa fa-hand-pointer-o"></i> hand-pointer-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-rock-o"><i class="fa fa-hand-rock-o"></i> hand-rock-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-scissors-o"><i class="fa fa-hand-scissors-o"></i> hand-scissors-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-spock-o"><i class="fa fa-hand-spock-o"></i> hand-spock-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-paper-o"><i class="fa fa-hand-stop-o"></i> hand-stop-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hashtag"><i class="fa fa-hashtag"></i> hashtag</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hdd-o"><i class="fa fa-hdd-o"></i> hdd-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/headphones"><i class="fa fa-headphones"></i> headphones</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/heart"><i class="fa fa-heart"></i> heart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/heart-o"><i class="fa fa-heart-o"></i> heart-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/heartbeat"><i class="fa fa-heartbeat"></i> heartbeat</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/history"><i class="fa fa-history"></i> history</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/home"><i class="fa fa-home"></i> home</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bed"><i class="fa fa-hotel"></i> hotel <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hourglass"><i class="fa fa-hourglass"></i> hourglass</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hourglass-start"><i class="fa fa-hourglass-1"></i> hourglass-1 <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hourglass-half"><i class="fa fa-hourglass-2"></i> hourglass-2 <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hourglass-end"><i class="fa fa-hourglass-3"></i> hourglass-3 <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hourglass-end"><i class="fa fa-hourglass-end"></i> hourglass-end</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hourglass-half"><i class="fa fa-hourglass-half"></i> hourglass-half</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hourglass-o"><i class="fa fa-hourglass-o"></i> hourglass-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hourglass-start"><i class="fa fa-hourglass-start"></i> hourglass-start</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/i-cursor"><i class="fa fa-i-cursor"></i> i-cursor</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/picture-o"><i class="fa fa-image"></i> image <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/inbox"><i class="fa fa-inbox"></i> inbox</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/industry"><i class="fa fa-industry"></i> industry</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/info"><i class="fa fa-info"></i> info</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/info-circle"><i class="fa fa-info-circle"></i> info-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/university"><i class="fa fa-institution"></i> institution <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/key"><i class="fa fa-key"></i> key</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/keyboard-o"><i class="fa fa-keyboard-o"></i> keyboard-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/language"><i class="fa fa-language"></i> language</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/laptop"><i class="fa fa-laptop"></i> laptop</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/leaf"><i class="fa fa-leaf"></i> leaf</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/gavel"><i class="fa fa-legal"></i> legal <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/lemon-o"><i class="fa fa-lemon-o"></i> lemon-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/level-down"><i class="fa fa-level-down"></i> level-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/level-up"><i class="fa fa-level-up"></i> level-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/life-ring"><i class="fa fa-life-bouy"></i> life-bouy <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/life-ring"><i class="fa fa-life-buoy"></i> life-buoy <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/life-ring"><i class="fa fa-life-ring"></i> life-ring</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/life-ring"><i class="fa fa-life-saver"></i> life-saver <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/lightbulb-o"><i class="fa fa-lightbulb-o"></i> lightbulb-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/line-chart"><i class="fa fa-line-chart"></i> line-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/location-arrow"><i class="fa fa-location-arrow"></i> location-arrow</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/lock"><i class="fa fa-lock"></i> lock</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/magic"><i class="fa fa-magic"></i> magic</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/magnet"><i class="fa fa-magnet"></i> magnet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/share"><i class="fa fa-mail-forward"></i> mail-forward <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/reply"><i class="fa fa-mail-reply"></i> mail-reply <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/reply-all"><i class="fa fa-mail-reply-all"></i> mail-reply-all <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/male"><i class="fa fa-male"></i> male</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/map"><i class="fa fa-map"></i> map</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/map-marker"><i class="fa fa-map-marker"></i> map-marker</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/map-o"><i class="fa fa-map-o"></i> map-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/map-pin"><i class="fa fa-map-pin"></i> map-pin</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/map-signs"><i class="fa fa-map-signs"></i> map-signs</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/meh-o"><i class="fa fa-meh-o"></i> meh-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/microphone"><i class="fa fa-microphone"></i> microphone</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/microphone-slash"><i class="fa fa-microphone-slash"></i> microphone-slash</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/minus"><i class="fa fa-minus"></i> minus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/minus-circle"><i class="fa fa-minus-circle"></i> minus-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/minus-square"><i class="fa fa-minus-square"></i> minus-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/minus-square-o"><i class="fa fa-minus-square-o"></i> minus-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/mobile"><i class="fa fa-mobile"></i> mobile</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/mobile"><i class="fa fa-mobile-phone"></i> mobile-phone <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/money"><i class="fa fa-money"></i> money</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/moon-o"><i class="fa fa-moon-o"></i> moon-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/graduation-cap"><i class="fa fa-mortar-board"></i> mortar-board <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/motorcycle"><i class="fa fa-motorcycle"></i> motorcycle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/mouse-pointer"><i class="fa fa-mouse-pointer"></i> mouse-pointer</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/music"><i class="fa fa-music"></i> music</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bars"><i class="fa fa-navicon"></i> navicon <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/newspaper-o"><i class="fa fa-newspaper-o"></i> newspaper-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/object-group"><i class="fa fa-object-group"></i> object-group</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/object-ungroup"><i class="fa fa-object-ungroup"></i> object-ungroup</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/paint-brush"><i class="fa fa-paint-brush"></i> paint-brush</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/paper-plane"><i class="fa fa-paper-plane"></i> paper-plane</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/paper-plane-o"><i class="fa fa-paper-plane-o"></i> paper-plane-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/paw"><i class="fa fa-paw"></i> paw</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pencil"><i class="fa fa-pencil"></i> pencil</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pencil-square"><i class="fa fa-pencil-square"></i> pencil-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pencil-square-o"><i class="fa fa-pencil-square-o"></i> pencil-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/percent"><i class="fa fa-percent"></i> percent</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/phone"><i class="fa fa-phone"></i> phone</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/phone-square"><i class="fa fa-phone-square"></i> phone-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/picture-o"><i class="fa fa-photo"></i> photo <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/picture-o"><i class="fa fa-picture-o"></i> picture-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pie-chart"><i class="fa fa-pie-chart"></i> pie-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/plane"><i class="fa fa-plane"></i> plane</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/plug"><i class="fa fa-plug"></i> plug</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/plus"><i class="fa fa-plus"></i> plus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/plus-circle"><i class="fa fa-plus-circle"></i> plus-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/plus-square"><i class="fa fa-plus-square"></i> plus-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/plus-square-o"><i class="fa fa-plus-square-o"></i> plus-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/power-off"><i class="fa fa-power-off"></i> power-off</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/print"><i class="fa fa-print"></i> print</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/puzzle-piece"><i class="fa fa-puzzle-piece"></i> puzzle-piece</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/qrcode"><i class="fa fa-qrcode"></i> qrcode</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/question"><i class="fa fa-question"></i> question</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/question-circle"><i class="fa fa-question-circle"></i> question-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/quote-left"><i class="fa fa-quote-left"></i> quote-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/quote-right"><i class="fa fa-quote-right"></i> quote-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/random"><i class="fa fa-random"></i> random</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/recycle"><i class="fa fa-recycle"></i> recycle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/refresh"><i class="fa fa-refresh"></i> refresh</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/registered"><i class="fa fa-registered"></i> registered</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/times"><i class="fa fa-remove"></i> remove <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bars"><i class="fa fa-reorder"></i> reorder <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/reply"><i class="fa fa-reply"></i> reply</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/reply-all"><i class="fa fa-reply-all"></i> reply-all</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/retweet"><i class="fa fa-retweet"></i> retweet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/road"><i class="fa fa-road"></i> road</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/rocket"><i class="fa fa-rocket"></i> rocket</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/rss"><i class="fa fa-rss"></i> rss</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/rss-square"><i class="fa fa-rss-square"></i> rss-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/search"><i class="fa fa-search"></i> search</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/search-minus"><i class="fa fa-search-minus"></i> search-minus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/search-plus"><i class="fa fa-search-plus"></i> search-plus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/paper-plane"><i class="fa fa-send"></i> send <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/paper-plane-o"><i class="fa fa-send-o"></i> send-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/server"><i class="fa fa-server"></i> server</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/share"><i class="fa fa-share"></i> share</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/share-alt"><i class="fa fa-share-alt"></i> share-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/share-alt-square"><i class="fa fa-share-alt-square"></i> share-alt-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/share-square"><i class="fa fa-share-square"></i> share-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/share-square-o"><i class="fa fa-share-square-o"></i> share-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/shield"><i class="fa fa-shield"></i> shield</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/ship"><i class="fa fa-ship"></i> ship</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/shopping-bag"><i class="fa fa-shopping-bag"></i> shopping-bag</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/shopping-basket"><i class="fa fa-shopping-basket"></i> shopping-basket</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/shopping-cart"><i class="fa fa-shopping-cart"></i> shopping-cart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sign-in"><i class="fa fa-sign-in"></i> sign-in</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sign-out"><i class="fa fa-sign-out"></i> sign-out</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/signal"><i class="fa fa-signal"></i> signal</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sitemap"><i class="fa fa-sitemap"></i> sitemap</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sliders"><i class="fa fa-sliders"></i> sliders</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/smile-o"><i class="fa fa-smile-o"></i> smile-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/futbol-o"><i class="fa fa-soccer-ball-o"></i> soccer-ball-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sort"><i class="fa fa-sort"></i> sort</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sort-alpha-asc"><i class="fa fa-sort-alpha-asc"></i> sort-alpha-asc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sort-alpha-desc"><i class="fa fa-sort-alpha-desc"></i> sort-alpha-desc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sort-amount-asc"><i class="fa fa-sort-amount-asc"></i> sort-amount-asc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sort-amount-desc"><i class="fa fa-sort-amount-desc"></i> sort-amount-desc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sort-asc"><i class="fa fa-sort-asc"></i> sort-asc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sort-desc"><i class="fa fa-sort-desc"></i> sort-desc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sort-desc"><i class="fa fa-sort-down"></i> sort-down <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sort-numeric-asc"><i class="fa fa-sort-numeric-asc"></i> sort-numeric-asc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sort-numeric-desc"><i class="fa fa-sort-numeric-desc"></i> sort-numeric-desc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sort-asc"><i class="fa fa-sort-up"></i> sort-up <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/space-shuttle"><i class="fa fa-space-shuttle"></i> space-shuttle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/spinner"><i class="fa fa-spinner"></i> spinner</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/spoon"><i class="fa fa-spoon"></i> spoon</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/square"><i class="fa fa-square"></i> square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/square-o"><i class="fa fa-square-o"></i> square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/star"><i class="fa fa-star"></i> star</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/star-half"><i class="fa fa-star-half"></i> star-half</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/star-half-o"><i class="fa fa-star-half-empty"></i> star-half-empty <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/star-half-o"><i class="fa fa-star-half-full"></i> star-half-full <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/star-half-o"><i class="fa fa-star-half-o"></i> star-half-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/star-o"><i class="fa fa-star-o"></i> star-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sticky-note"><i class="fa fa-sticky-note"></i> sticky-note</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sticky-note-o"><i class="fa fa-sticky-note-o"></i> sticky-note-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/street-view"><i class="fa fa-street-view"></i> street-view</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/suitcase"><i class="fa fa-suitcase"></i> suitcase</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sun-o"><i class="fa fa-sun-o"></i> sun-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/life-ring"><i class="fa fa-support"></i> support <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/tablet"><i class="fa fa-tablet"></i> tablet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/tachometer"><i class="fa fa-tachometer"></i> tachometer</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/tag"><i class="fa fa-tag"></i> tag</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/tags"><i class="fa fa-tags"></i> tags</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/tasks"><i class="fa fa-tasks"></i> tasks</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/taxi"><i class="fa fa-taxi"></i> taxi</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/television"><i class="fa fa-television"></i> television</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/terminal"><i class="fa fa-terminal"></i> terminal</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/thumb-tack"><i class="fa fa-thumb-tack"></i> thumb-tack</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/thumbs-down"><i class="fa fa-thumbs-down"></i> thumbs-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/thumbs-o-down"><i class="fa fa-thumbs-o-down"></i> thumbs-o-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/thumbs-o-up"><i class="fa fa-thumbs-o-up"></i> thumbs-o-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/thumbs-up"><i class="fa fa-thumbs-up"></i> thumbs-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/ticket"><i class="fa fa-ticket"></i> ticket</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/times"><i class="fa fa-times"></i> times</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/times-circle"><i class="fa fa-times-circle"></i> times-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/times-circle-o"><i class="fa fa-times-circle-o"></i> times-circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/tint"><i class="fa fa-tint"></i> tint</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-down"><i class="fa fa-toggle-down"></i> toggle-down <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-left"><i class="fa fa-toggle-left"></i> toggle-left <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/toggle-off"><i class="fa fa-toggle-off"></i> toggle-off</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/toggle-on"><i class="fa fa-toggle-on"></i> toggle-on</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-right"><i class="fa fa-toggle-right"></i> toggle-right <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-up"><i class="fa fa-toggle-up"></i> toggle-up <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/trademark"><i class="fa fa-trademark"></i> trademark</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/trash"><i class="fa fa-trash"></i> trash</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/trash-o"><i class="fa fa-trash-o"></i> trash-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/tree"><i class="fa fa-tree"></i> tree</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/trophy"><i class="fa fa-trophy"></i> trophy</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/truck"><i class="fa fa-truck"></i> truck</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/tty"><i class="fa fa-tty"></i> tty</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/television"><i class="fa fa-tv"></i> tv <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/umbrella"><i class="fa fa-umbrella"></i> umbrella</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/university"><i class="fa fa-university"></i> university</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/unlock"><i class="fa fa-unlock"></i> unlock</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/unlock-alt"><i class="fa fa-unlock-alt"></i> unlock-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sort"><i class="fa fa-unsorted"></i> unsorted <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/upload"><i class="fa fa-upload"></i> upload</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/user"><i class="fa fa-user"></i> user</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/user-plus"><i class="fa fa-user-plus"></i> user-plus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/user-secret"><i class="fa fa-user-secret"></i> user-secret</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/user-times"><i class="fa fa-user-times"></i> user-times</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/users"><i class="fa fa-users"></i> users</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/video-camera"><i class="fa fa-video-camera"></i> video-camera</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/volume-down"><i class="fa fa-volume-down"></i> volume-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/volume-off"><i class="fa fa-volume-off"></i> volume-off</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/volume-up"><i class="fa fa-volume-up"></i> volume-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/exclamation-triangle"><i class="fa fa-warning"></i> warning <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/wheelchair"><i class="fa fa-wheelchair"></i> wheelchair</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/wifi"><i class="fa fa-wifi"></i> wifi</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/wrench"><i class="fa fa-wrench"></i> wrench</a></div>
    
  </div>

</section>

    <section id="hand">
  <h2 class="page-header">Hand Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-rock-o"><i class="fa fa-hand-grab-o"></i> hand-grab-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-lizard-o"><i class="fa fa-hand-lizard-o"></i> hand-lizard-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-o-down"><i class="fa fa-hand-o-down"></i> hand-o-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-o-left"><i class="fa fa-hand-o-left"></i> hand-o-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-o-right"><i class="fa fa-hand-o-right"></i> hand-o-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-o-up"><i class="fa fa-hand-o-up"></i> hand-o-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-paper-o"><i class="fa fa-hand-paper-o"></i> hand-paper-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-peace-o"><i class="fa fa-hand-peace-o"></i> hand-peace-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-pointer-o"><i class="fa fa-hand-pointer-o"></i> hand-pointer-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-rock-o"><i class="fa fa-hand-rock-o"></i> hand-rock-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-scissors-o"><i class="fa fa-hand-scissors-o"></i> hand-scissors-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-spock-o"><i class="fa fa-hand-spock-o"></i> hand-spock-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-paper-o"><i class="fa fa-hand-stop-o"></i> hand-stop-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/thumbs-down"><i class="fa fa-thumbs-down"></i> thumbs-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/thumbs-o-down"><i class="fa fa-thumbs-o-down"></i> thumbs-o-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/thumbs-o-up"><i class="fa fa-thumbs-o-up"></i> thumbs-o-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/thumbs-up"><i class="fa fa-thumbs-up"></i> thumbs-up</a></div>
    
  </div>

</section>

    <section id="transportation">
  <h2 class="page-header">Transportation Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/ambulance"><i class="fa fa-ambulance"></i> ambulance</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/car"><i class="fa fa-automobile"></i> automobile <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bicycle"><i class="fa fa-bicycle"></i> bicycle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bus"><i class="fa fa-bus"></i> bus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/taxi"><i class="fa fa-cab"></i> cab <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/car"><i class="fa fa-car"></i> car</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/fighter-jet"><i class="fa fa-fighter-jet"></i> fighter-jet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/motorcycle"><i class="fa fa-motorcycle"></i> motorcycle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/plane"><i class="fa fa-plane"></i> plane</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/rocket"><i class="fa fa-rocket"></i> rocket</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/ship"><i class="fa fa-ship"></i> ship</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/space-shuttle"><i class="fa fa-space-shuttle"></i> space-shuttle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/subway"><i class="fa fa-subway"></i> subway</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/taxi"><i class="fa fa-taxi"></i> taxi</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/train"><i class="fa fa-train"></i> train</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/truck"><i class="fa fa-truck"></i> truck</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/wheelchair"><i class="fa fa-wheelchair"></i> wheelchair</a></div>
    
  </div>

</section>

    <section id="gender">
  <h2 class="page-header">Gender Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/genderless"><i class="fa fa-genderless"></i> genderless</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/transgender"><i class="fa fa-intersex"></i> intersex <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/mars"><i class="fa fa-mars"></i> mars</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/mars-double"><i class="fa fa-mars-double"></i> mars-double</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/mars-stroke"><i class="fa fa-mars-stroke"></i> mars-stroke</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/mars-stroke-h"><i class="fa fa-mars-stroke-h"></i> mars-stroke-h</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/mars-stroke-v"><i class="fa fa-mars-stroke-v"></i> mars-stroke-v</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/mercury"><i class="fa fa-mercury"></i> mercury</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/neuter"><i class="fa fa-neuter"></i> neuter</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/transgender"><i class="fa fa-transgender"></i> transgender</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/transgender-alt"><i class="fa fa-transgender-alt"></i> transgender-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/venus"><i class="fa fa-venus"></i> venus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/venus-double"><i class="fa fa-venus-double"></i> venus-double</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/venus-mars"><i class="fa fa-venus-mars"></i> venus-mars</a></div>
    
  </div>

</section>

    <section id="file-type">
  <h2 class="page-header">File Type Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file"><i class="fa fa-file"></i> file</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-archive-o"><i class="fa fa-file-archive-o"></i> file-archive-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-audio-o"><i class="fa fa-file-audio-o"></i> file-audio-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-code-o"><i class="fa fa-file-code-o"></i> file-code-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-excel-o"><i class="fa fa-file-excel-o"></i> file-excel-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-image-o"><i class="fa fa-file-image-o"></i> file-image-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-video-o"><i class="fa fa-file-movie-o"></i> file-movie-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-o"><i class="fa fa-file-o"></i> file-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-pdf-o"><i class="fa fa-file-pdf-o"></i> file-pdf-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-image-o"><i class="fa fa-file-photo-o"></i> file-photo-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-image-o"><i class="fa fa-file-picture-o"></i> file-picture-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-powerpoint-o"><i class="fa fa-file-powerpoint-o"></i> file-powerpoint-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-audio-o"><i class="fa fa-file-sound-o"></i> file-sound-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-text"><i class="fa fa-file-text"></i> file-text</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-text-o"><i class="fa fa-file-text-o"></i> file-text-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-video-o"><i class="fa fa-file-video-o"></i> file-video-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-word-o"><i class="fa fa-file-word-o"></i> file-word-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-archive-o"><i class="fa fa-file-zip-o"></i> file-zip-o <span class="text-muted">(alias)</span></a></div>
    
  </div>

</section>

    <section id="spinner">
  <h2 class="page-header">Spinner Icons</h2>

  <div class="alert alert-success">
    <ul class="fa-ul">
      <li>
        <i class="fa fa-info-circle fa-lg fa-li"></i>
        These icons work great with the <code>fa-spin</code> class.
      </li>
    </ul>
  </div>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/circle-o-notch"><i class="fa fa-circle-o-notch"></i> circle-o-notch</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cog"><i class="fa fa-cog"></i> cog</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cog"><i class="fa fa-gear"></i> gear <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/refresh"><i class="fa fa-refresh"></i> refresh</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/spinner"><i class="fa fa-spinner"></i> spinner</a></div>
    
  </div>
</section>

    <section id="form-control">
  <h2 class="page-header">Form Control Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/check-square"><i class="fa fa-check-square"></i> check-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/check-square-o"><i class="fa fa-check-square-o"></i> check-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/circle"><i class="fa fa-circle"></i> circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/circle-o"><i class="fa fa-circle-o"></i> circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/dot-circle-o"><i class="fa fa-dot-circle-o"></i> dot-circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/minus-square"><i class="fa fa-minus-square"></i> minus-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/minus-square-o"><i class="fa fa-minus-square-o"></i> minus-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/plus-square"><i class="fa fa-plus-square"></i> plus-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/plus-square-o"><i class="fa fa-plus-square-o"></i> plus-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/square"><i class="fa fa-square"></i> square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/square-o"><i class="fa fa-square-o"></i> square-o</a></div>
    
  </div>
</section>

    <section id="payment">
  <h2 class="page-header">Payment Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-amex"><i class="fa fa-cc-amex"></i> cc-amex</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-diners-club"><i class="fa fa-cc-diners-club"></i> cc-diners-club</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-discover"><i class="fa fa-cc-discover"></i> cc-discover</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-jcb"><i class="fa fa-cc-jcb"></i> cc-jcb</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-mastercard"><i class="fa fa-cc-mastercard"></i> cc-mastercard</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-paypal"><i class="fa fa-cc-paypal"></i> cc-paypal</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-stripe"><i class="fa fa-cc-stripe"></i> cc-stripe</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-visa"><i class="fa fa-cc-visa"></i> cc-visa</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/credit-card"><i class="fa fa-credit-card"></i> credit-card</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/credit-card-alt"><i class="fa fa-credit-card-alt"></i> credit-card-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/google-wallet"><i class="fa fa-google-wallet"></i> google-wallet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/paypal"><i class="fa fa-paypal"></i> paypal</a></div>
    
  </div>

</section>

    <section id="chart">
  <h2 class="page-header">Chart Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/area-chart"><i class="fa fa-area-chart"></i> area-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bar-chart"><i class="fa fa-bar-chart"></i> bar-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bar-chart"><i class="fa fa-bar-chart-o"></i> bar-chart-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/line-chart"><i class="fa fa-line-chart"></i> line-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pie-chart"><i class="fa fa-pie-chart"></i> pie-chart</a></div>
    
  </div>

</section>

    <section id="currency">
  <h2 class="page-header">Currency Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/btc"><i class="fa fa-bitcoin"></i> bitcoin <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/btc"><i class="fa fa-btc"></i> btc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/jpy"><i class="fa fa-cny"></i> cny <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/usd"><i class="fa fa-dollar"></i> dollar <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/eur"><i class="fa fa-eur"></i> eur</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/eur"><i class="fa fa-euro"></i> euro <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/gbp"><i class="fa fa-gbp"></i> gbp</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/gg"><i class="fa fa-gg"></i> gg</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/gg-circle"><i class="fa fa-gg-circle"></i> gg-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/ils"><i class="fa fa-ils"></i> ils</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/inr"><i class="fa fa-inr"></i> inr</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/jpy"><i class="fa fa-jpy"></i> jpy</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/krw"><i class="fa fa-krw"></i> krw</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/money"><i class="fa fa-money"></i> money</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/jpy"><i class="fa fa-rmb"></i> rmb <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/rub"><i class="fa fa-rouble"></i> rouble <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/rub"><i class="fa fa-rub"></i> rub</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/rub"><i class="fa fa-ruble"></i> ruble <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/inr"><i class="fa fa-rupee"></i> rupee <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/ils"><i class="fa fa-shekel"></i> shekel <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/ils"><i class="fa fa-sheqel"></i> sheqel <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/try"><i class="fa fa-try"></i> try</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/try"><i class="fa fa-turkish-lira"></i> turkish-lira <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/usd"><i class="fa fa-usd"></i> usd</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/krw"><i class="fa fa-won"></i> won <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/jpy"><i class="fa fa-yen"></i> yen <span class="text-muted">(alias)</span></a></div>
    
  </div>

</section>

    <section id="text-editor">
  <h2 class="page-header">Text Editor Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/align-center"><i class="fa fa-align-center"></i> align-center</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/align-justify"><i class="fa fa-align-justify"></i> align-justify</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/align-left"><i class="fa fa-align-left"></i> align-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/align-right"><i class="fa fa-align-right"></i> align-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bold"><i class="fa fa-bold"></i> bold</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/link"><i class="fa fa-chain"></i> chain <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/chain-broken"><i class="fa fa-chain-broken"></i> chain-broken</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/clipboard"><i class="fa fa-clipboard"></i> clipboard</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/columns"><i class="fa fa-columns"></i> columns</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/files-o"><i class="fa fa-copy"></i> copy <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/scissors"><i class="fa fa-cut"></i> cut <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/outdent"><i class="fa fa-dedent"></i> dedent <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/eraser"><i class="fa fa-eraser"></i> eraser</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file"><i class="fa fa-file"></i> file</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-o"><i class="fa fa-file-o"></i> file-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-text"><i class="fa fa-file-text"></i> file-text</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/file-text-o"><i class="fa fa-file-text-o"></i> file-text-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/files-o"><i class="fa fa-files-o"></i> files-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/floppy-o"><i class="fa fa-floppy-o"></i> floppy-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/font"><i class="fa fa-font"></i> font</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/header"><i class="fa fa-header"></i> header</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/indent"><i class="fa fa-indent"></i> indent</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/italic"><i class="fa fa-italic"></i> italic</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/link"><i class="fa fa-link"></i> link</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/list"><i class="fa fa-list"></i> list</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/list-alt"><i class="fa fa-list-alt"></i> list-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/list-ol"><i class="fa fa-list-ol"></i> list-ol</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/list-ul"><i class="fa fa-list-ul"></i> list-ul</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/outdent"><i class="fa fa-outdent"></i> outdent</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/paperclip"><i class="fa fa-paperclip"></i> paperclip</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/paragraph"><i class="fa fa-paragraph"></i> paragraph</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/clipboard"><i class="fa fa-paste"></i> paste <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/repeat"><i class="fa fa-repeat"></i> repeat</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/undo"><i class="fa fa-rotate-left"></i> rotate-left <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/repeat"><i class="fa fa-rotate-right"></i> rotate-right <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/floppy-o"><i class="fa fa-save"></i> save <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/scissors"><i class="fa fa-scissors"></i> scissors</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/strikethrough"><i class="fa fa-strikethrough"></i> strikethrough</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/subscript"><i class="fa fa-subscript"></i> subscript</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/superscript"><i class="fa fa-superscript"></i> superscript</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/table"><i class="fa fa-table"></i> table</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/text-height"><i class="fa fa-text-height"></i> text-height</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/text-width"><i class="fa fa-text-width"></i> text-width</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/th"><i class="fa fa-th"></i> th</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/th-large"><i class="fa fa-th-large"></i> th-large</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/th-list"><i class="fa fa-th-list"></i> th-list</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/underline"><i class="fa fa-underline"></i> underline</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/undo"><i class="fa fa-undo"></i> undo</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/chain-broken"><i class="fa fa-unlink"></i> unlink <span class="text-muted">(alias)</span></a></div>
    
  </div>

</section>

    <section id="directional">
  <h2 class="page-header">Directional Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/angle-double-down"><i class="fa fa-angle-double-down"></i> angle-double-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/angle-double-left"><i class="fa fa-angle-double-left"></i> angle-double-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/angle-double-right"><i class="fa fa-angle-double-right"></i> angle-double-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/angle-double-up"><i class="fa fa-angle-double-up"></i> angle-double-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/angle-down"><i class="fa fa-angle-down"></i> angle-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/angle-left"><i class="fa fa-angle-left"></i> angle-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/angle-right"><i class="fa fa-angle-right"></i> angle-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/angle-up"><i class="fa fa-angle-up"></i> angle-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrow-circle-down"><i class="fa fa-arrow-circle-down"></i> arrow-circle-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrow-circle-left"><i class="fa fa-arrow-circle-left"></i> arrow-circle-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrow-circle-o-down"><i class="fa fa-arrow-circle-o-down"></i> arrow-circle-o-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrow-circle-o-left"><i class="fa fa-arrow-circle-o-left"></i> arrow-circle-o-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrow-circle-o-right"><i class="fa fa-arrow-circle-o-right"></i> arrow-circle-o-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrow-circle-o-up"><i class="fa fa-arrow-circle-o-up"></i> arrow-circle-o-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrow-circle-right"><i class="fa fa-arrow-circle-right"></i> arrow-circle-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrow-circle-up"><i class="fa fa-arrow-circle-up"></i> arrow-circle-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrow-down"><i class="fa fa-arrow-down"></i> arrow-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrow-left"><i class="fa fa-arrow-left"></i> arrow-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrow-right"><i class="fa fa-arrow-right"></i> arrow-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrow-up"><i class="fa fa-arrow-up"></i> arrow-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrows"><i class="fa fa-arrows"></i> arrows</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrows-alt"><i class="fa fa-arrows-alt"></i> arrows-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrows-h"><i class="fa fa-arrows-h"></i> arrows-h</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrows-v"><i class="fa fa-arrows-v"></i> arrows-v</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-down"><i class="fa fa-caret-down"></i> caret-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-left"><i class="fa fa-caret-left"></i> caret-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-right"><i class="fa fa-caret-right"></i> caret-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-down"><i class="fa fa-caret-square-o-down"></i> caret-square-o-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-left"><i class="fa fa-caret-square-o-left"></i> caret-square-o-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-right"><i class="fa fa-caret-square-o-right"></i> caret-square-o-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-up"><i class="fa fa-caret-square-o-up"></i> caret-square-o-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-up"><i class="fa fa-caret-up"></i> caret-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/chevron-circle-down"><i class="fa fa-chevron-circle-down"></i> chevron-circle-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/chevron-circle-left"><i class="fa fa-chevron-circle-left"></i> chevron-circle-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/chevron-circle-right"><i class="fa fa-chevron-circle-right"></i> chevron-circle-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/chevron-circle-up"><i class="fa fa-chevron-circle-up"></i> chevron-circle-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/chevron-down"><i class="fa fa-chevron-down"></i> chevron-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/chevron-left"><i class="fa fa-chevron-left"></i> chevron-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/chevron-right"><i class="fa fa-chevron-right"></i> chevron-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/chevron-up"><i class="fa fa-chevron-up"></i> chevron-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/exchange"><i class="fa fa-exchange"></i> exchange</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-o-down"><i class="fa fa-hand-o-down"></i> hand-o-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-o-left"><i class="fa fa-hand-o-left"></i> hand-o-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-o-right"><i class="fa fa-hand-o-right"></i> hand-o-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hand-o-up"><i class="fa fa-hand-o-up"></i> hand-o-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/long-arrow-down"><i class="fa fa-long-arrow-down"></i> long-arrow-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/long-arrow-left"><i class="fa fa-long-arrow-left"></i> long-arrow-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/long-arrow-right"><i class="fa fa-long-arrow-right"></i> long-arrow-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/long-arrow-up"><i class="fa fa-long-arrow-up"></i> long-arrow-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-down"><i class="fa fa-toggle-down"></i> toggle-down <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-left"><i class="fa fa-toggle-left"></i> toggle-left <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-right"><i class="fa fa-toggle-right"></i> toggle-right <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/caret-square-o-up"><i class="fa fa-toggle-up"></i> toggle-up <span class="text-muted">(alias)</span></a></div>
    
  </div>

</section>

    <section id="video-player">
  <h2 class="page-header">Video Player Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/arrows-alt"><i class="fa fa-arrows-alt"></i> arrows-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/backward"><i class="fa fa-backward"></i> backward</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/compress"><i class="fa fa-compress"></i> compress</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/eject"><i class="fa fa-eject"></i> eject</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/expand"><i class="fa fa-expand"></i> expand</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/fast-backward"><i class="fa fa-fast-backward"></i> fast-backward</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/fast-forward"><i class="fa fa-fast-forward"></i> fast-forward</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/forward"><i class="fa fa-forward"></i> forward</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pause"><i class="fa fa-pause"></i> pause</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pause-circle"><i class="fa fa-pause-circle"></i> pause-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pause-circle-o"><i class="fa fa-pause-circle-o"></i> pause-circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/play"><i class="fa fa-play"></i> play</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/play-circle"><i class="fa fa-play-circle"></i> play-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/play-circle-o"><i class="fa fa-play-circle-o"></i> play-circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/random"><i class="fa fa-random"></i> random</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/step-backward"><i class="fa fa-step-backward"></i> step-backward</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/step-forward"><i class="fa fa-step-forward"></i> step-forward</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/stop"><i class="fa fa-stop"></i> stop</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/stop-circle"><i class="fa fa-stop-circle"></i> stop-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/stop-circle-o"><i class="fa fa-stop-circle-o"></i> stop-circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/youtube-play"><i class="fa fa-youtube-play"></i> youtube-play</a></div>
    
  </div>

</section>

    <section id="brand">
  <h2 class="page-header">Brand Icons</h2>

  <div class="alert alert-warning">
    <h4><i class="fa fa-warning"></i> Warning!</h4>
Apparently, Adblock Plus can remove Font Awesome brand icons with their "Remove Social
Media Buttons" setting. We will not use hacks to force them to display. Please
<a href="https://adblockplus.org/en/bugs" class="alert-link">report an issue with Adblock Plus</a> if you believe this to be
an error. To work around this, you'll need to modify the social icon class names.

  </div>

  <div class="row fontawesome-icon-list margin-bottom-lg">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/500px"><i class="fa fa-500px"></i> 500px</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/adn"><i class="fa fa-adn"></i> adn</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/amazon"><i class="fa fa-amazon"></i> amazon</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/android"><i class="fa fa-android"></i> android</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/angellist"><i class="fa fa-angellist"></i> angellist</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/apple"><i class="fa fa-apple"></i> apple</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/behance"><i class="fa fa-behance"></i> behance</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/behance-square"><i class="fa fa-behance-square"></i> behance-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bitbucket"><i class="fa fa-bitbucket"></i> bitbucket</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bitbucket-square"><i class="fa fa-bitbucket-square"></i> bitbucket-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/btc"><i class="fa fa-bitcoin"></i> bitcoin <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/black-tie"><i class="fa fa-black-tie"></i> black-tie</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bluetooth"><i class="fa fa-bluetooth"></i> bluetooth</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/bluetooth-b"><i class="fa fa-bluetooth-b"></i> bluetooth-b</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/btc"><i class="fa fa-btc"></i> btc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/buysellads"><i class="fa fa-buysellads"></i> buysellads</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-amex"><i class="fa fa-cc-amex"></i> cc-amex</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-diners-club"><i class="fa fa-cc-diners-club"></i> cc-diners-club</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-discover"><i class="fa fa-cc-discover"></i> cc-discover</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-jcb"><i class="fa fa-cc-jcb"></i> cc-jcb</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-mastercard"><i class="fa fa-cc-mastercard"></i> cc-mastercard</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-paypal"><i class="fa fa-cc-paypal"></i> cc-paypal</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-stripe"><i class="fa fa-cc-stripe"></i> cc-stripe</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/cc-visa"><i class="fa fa-cc-visa"></i> cc-visa</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/chrome"><i class="fa fa-chrome"></i> chrome</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/codepen"><i class="fa fa-codepen"></i> codepen</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/codiepie"><i class="fa fa-codiepie"></i> codiepie</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/connectdevelop"><i class="fa fa-connectdevelop"></i> connectdevelop</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/contao"><i class="fa fa-contao"></i> contao</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/css3"><i class="fa fa-css3"></i> css3</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/dashcube"><i class="fa fa-dashcube"></i> dashcube</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/delicious"><i class="fa fa-delicious"></i> delicious</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/deviantart"><i class="fa fa-deviantart"></i> deviantart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/digg"><i class="fa fa-digg"></i> digg</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/dribbble"><i class="fa fa-dribbble"></i> dribbble</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/dropbox"><i class="fa fa-dropbox"></i> dropbox</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/drupal"><i class="fa fa-drupal"></i> drupal</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/edge"><i class="fa fa-edge"></i> edge</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/empire"><i class="fa fa-empire"></i> empire</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/expeditedssl"><i class="fa fa-expeditedssl"></i> expeditedssl</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/facebook"><i class="fa fa-facebook"></i> facebook</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/facebook"><i class="fa fa-facebook-f"></i> facebook-f <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/facebook-official"><i class="fa fa-facebook-official"></i> facebook-official</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/facebook-square"><i class="fa fa-facebook-square"></i> facebook-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/firefox"><i class="fa fa-firefox"></i> firefox</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/flickr"><i class="fa fa-flickr"></i> flickr</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/fonticons"><i class="fa fa-fonticons"></i> fonticons</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/fort-awesome"><i class="fa fa-fort-awesome"></i> fort-awesome</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/forumbee"><i class="fa fa-forumbee"></i> forumbee</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/foursquare"><i class="fa fa-foursquare"></i> foursquare</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/empire"><i class="fa fa-ge"></i> ge <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/get-pocket"><i class="fa fa-get-pocket"></i> get-pocket</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/gg"><i class="fa fa-gg"></i> gg</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/gg-circle"><i class="fa fa-gg-circle"></i> gg-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/git"><i class="fa fa-git"></i> git</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/git-square"><i class="fa fa-git-square"></i> git-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/github"><i class="fa fa-github"></i> github</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/github-alt"><i class="fa fa-github-alt"></i> github-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/github-square"><i class="fa fa-github-square"></i> github-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/gratipay"><i class="fa fa-gittip"></i> gittip <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/google"><i class="fa fa-google"></i> google</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/google-plus"><i class="fa fa-google-plus"></i> google-plus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/google-plus-square"><i class="fa fa-google-plus-square"></i> google-plus-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/google-wallet"><i class="fa fa-google-wallet"></i> google-wallet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/gratipay"><i class="fa fa-gratipay"></i> gratipay</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hacker-news"><i class="fa fa-hacker-news"></i> hacker-news</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/houzz"><i class="fa fa-houzz"></i> houzz</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/html5"><i class="fa fa-html5"></i> html5</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/instagram"><i class="fa fa-instagram"></i> instagram</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/internet-explorer"><i class="fa fa-internet-explorer"></i> internet-explorer</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/ioxhost"><i class="fa fa-ioxhost"></i> ioxhost</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/joomla"><i class="fa fa-joomla"></i> joomla</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/jsfiddle"><i class="fa fa-jsfiddle"></i> jsfiddle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/lastfm"><i class="fa fa-lastfm"></i> lastfm</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/lastfm-square"><i class="fa fa-lastfm-square"></i> lastfm-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/leanpub"><i class="fa fa-leanpub"></i> leanpub</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/linkedin"><i class="fa fa-linkedin"></i> linkedin</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/linkedin-square"><i class="fa fa-linkedin-square"></i> linkedin-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/linux"><i class="fa fa-linux"></i> linux</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/maxcdn"><i class="fa fa-maxcdn"></i> maxcdn</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/meanpath"><i class="fa fa-meanpath"></i> meanpath</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/medium"><i class="fa fa-medium"></i> medium</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/mixcloud"><i class="fa fa-mixcloud"></i> mixcloud</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/modx"><i class="fa fa-modx"></i> modx</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/odnoklassniki"><i class="fa fa-odnoklassniki"></i> odnoklassniki</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/odnoklassniki-square"><i class="fa fa-odnoklassniki-square"></i> odnoklassniki-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/opencart"><i class="fa fa-opencart"></i> opencart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/openid"><i class="fa fa-openid"></i> openid</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/opera"><i class="fa fa-opera"></i> opera</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/optin-monster"><i class="fa fa-optin-monster"></i> optin-monster</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pagelines"><i class="fa fa-pagelines"></i> pagelines</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/paypal"><i class="fa fa-paypal"></i> paypal</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pied-piper"><i class="fa fa-pied-piper"></i> pied-piper</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pied-piper-alt"><i class="fa fa-pied-piper-alt"></i> pied-piper-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pinterest"><i class="fa fa-pinterest"></i> pinterest</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pinterest-p"><i class="fa fa-pinterest-p"></i> pinterest-p</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/pinterest-square"><i class="fa fa-pinterest-square"></i> pinterest-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/product-hunt"><i class="fa fa-product-hunt"></i> product-hunt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/qq"><i class="fa fa-qq"></i> qq</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/rebel"><i class="fa fa-ra"></i> ra <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/rebel"><i class="fa fa-rebel"></i> rebel</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/reddit"><i class="fa fa-reddit"></i> reddit</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/reddit-alien"><i class="fa fa-reddit-alien"></i> reddit-alien</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/reddit-square"><i class="fa fa-reddit-square"></i> reddit-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/renren"><i class="fa fa-renren"></i> renren</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/safari"><i class="fa fa-safari"></i> safari</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/scribd"><i class="fa fa-scribd"></i> scribd</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/sellsy"><i class="fa fa-sellsy"></i> sellsy</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/share-alt"><i class="fa fa-share-alt"></i> share-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/share-alt-square"><i class="fa fa-share-alt-square"></i> share-alt-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/shirtsinbulk"><i class="fa fa-shirtsinbulk"></i> shirtsinbulk</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/simplybuilt"><i class="fa fa-simplybuilt"></i> simplybuilt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/skyatlas"><i class="fa fa-skyatlas"></i> skyatlas</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/skype"><i class="fa fa-skype"></i> skype</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/slack"><i class="fa fa-slack"></i> slack</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/slideshare"><i class="fa fa-slideshare"></i> slideshare</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/soundcloud"><i class="fa fa-soundcloud"></i> soundcloud</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/spotify"><i class="fa fa-spotify"></i> spotify</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/stack-exchange"><i class="fa fa-stack-exchange"></i> stack-exchange</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/stack-overflow"><i class="fa fa-stack-overflow"></i> stack-overflow</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/steam"><i class="fa fa-steam"></i> steam</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/steam-square"><i class="fa fa-steam-square"></i> steam-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/stumbleupon"><i class="fa fa-stumbleupon"></i> stumbleupon</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/stumbleupon-circle"><i class="fa fa-stumbleupon-circle"></i> stumbleupon-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/tencent-weibo"><i class="fa fa-tencent-weibo"></i> tencent-weibo</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/trello"><i class="fa fa-trello"></i> trello</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/tripadvisor"><i class="fa fa-tripadvisor"></i> tripadvisor</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/tumblr"><i class="fa fa-tumblr"></i> tumblr</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/tumblr-square"><i class="fa fa-tumblr-square"></i> tumblr-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/twitch"><i class="fa fa-twitch"></i> twitch</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/twitter"><i class="fa fa-twitter"></i> twitter</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/twitter-square"><i class="fa fa-twitter-square"></i> twitter-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/usb"><i class="fa fa-usb"></i> usb</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/viacoin"><i class="fa fa-viacoin"></i> viacoin</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/vimeo"><i class="fa fa-vimeo"></i> vimeo</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/vimeo-square"><i class="fa fa-vimeo-square"></i> vimeo-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/vine"><i class="fa fa-vine"></i> vine</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/vk"><i class="fa fa-vk"></i> vk</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/weixin"><i class="fa fa-wechat"></i> wechat <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/weibo"><i class="fa fa-weibo"></i> weibo</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/weixin"><i class="fa fa-weixin"></i> weixin</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/whatsapp"><i class="fa fa-whatsapp"></i> whatsapp</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/wikipedia-w"><i class="fa fa-wikipedia-w"></i> wikipedia-w</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/windows"><i class="fa fa-windows"></i> windows</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/wordpress"><i class="fa fa-wordpress"></i> wordpress</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/xing"><i class="fa fa-xing"></i> xing</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/xing-square"><i class="fa fa-xing-square"></i> xing-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/y-combinator"><i class="fa fa-y-combinator"></i> y-combinator</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hacker-news"><i class="fa fa-y-combinator-square"></i> y-combinator-square <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/yahoo"><i class="fa fa-yahoo"></i> yahoo</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/y-combinator"><i class="fa fa-yc"></i> yc <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hacker-news"><i class="fa fa-yc-square"></i> yc-square <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/yelp"><i class="fa fa-yelp"></i> yelp</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/youtube"><i class="fa fa-youtube"></i> youtube</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/youtube-play"><i class="fa fa-youtube-play"></i> youtube-play</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/youtube-square"><i class="fa fa-youtube-square"></i> youtube-square</a></div>
    
  </div>

<div class="alert alert-success">
    <ul class="margin-bottom-none padding-left-lg">
  <li>All brand icons are trademarks of their respective owners.</li>
  <li>The use of these trademarks does not indicate endorsement of the trademark holder by Font Awesome, nor vice versa.</li>
  <li>Brand icons should only be used to represent the company or product to which they refer.</li>
</ul>

  </div>

</section>

    <section id="medical">
  <h2 class="page-header">Medical Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/ambulance"><i class="fa fa-ambulance"></i> ambulance</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/h-square"><i class="fa fa-h-square"></i> h-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/heart"><i class="fa fa-heart"></i> heart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/heart-o"><i class="fa fa-heart-o"></i> heart-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/heartbeat"><i class="fa fa-heartbeat"></i> heartbeat</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/hospital-o"><i class="fa fa-hospital-o"></i> hospital-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/medkit"><i class="fa fa-medkit"></i> medkit</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/plus-square"><i class="fa fa-plus-square"></i> plus-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/stethoscope"><i class="fa fa-stethoscope"></i> stethoscope</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/user-md"><i class="fa fa-user-md"></i> user-md</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="https://fortawesome.github.io/Font-Awesome/icon/wheelchair"><i class="fa fa-wheelchair"></i> wheelchair</a></div>
    
  </div>

</section>

  </div>
</div>
