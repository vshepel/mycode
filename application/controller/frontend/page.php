<?php
/**
 * Page Frontend Controller
 * @copyright Copyright (C) 2016 al3xable <al3xable@yandex.com>. All rights reserved.
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 */

namespace controller\frontend;

use AppController;
use model\page\Page as PageModel;

class Page extends AppController {
	public $__default = "page";

	public $__routes = array (
		"([A-Za-z0-9\\/\\\\\\_\\-]+)" => "page"
	);

	public function action_page($args) {
		$name = (isset($args[0]) && !empty($args[0])) ? $args[0] : $this->_registry->get("Config")->get("page", "page", "main");
		$model = new PageModel();
		$this->_view->responseRender($model->page($name));
	}
}
