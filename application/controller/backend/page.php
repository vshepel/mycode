<?php
/**
 * Page Backend Controller
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

namespace controller\backend;

use AppController;

use model\page\Page as PageModel;
use model\page\Settings;

use harmony\http\HTTP;

class Page extends AppController {
	public $__default = "list";

	public $__routes = array (
		"settings" => null,
		"list/page/([0-9]+)" => "list",
		"list" => "list",
		"remove/([0-9]+)" => "remove",
		"edit/([0-9]+)" => "edit",
		"add" => "add"
	);

	private $_page;

	public function __construct() {
		parent::__construct();
		$this->_page = new PageModel();
	}

	public function action_list($args) {
		$page = isset($args[0]) ? $args[0] : 1;
		$this->_view->responseRender($this->_page->get($page));
	}
	
	public function action_settings() {
		$model = new Settings();
		
		if (count($_POST) > 0) {
			$result = $model->save($_POST);
			$this->_view->alert($result->type, $result->message);
		}

		$this->_view->responseRender($model->page());
	}

	public function action_remove($args) {
		if (isset($_POST["remove"]["id"])) {
			$result = $this->_page->remove($_POST["remove"]["id"]);

			if ($result->code == 0)
				HTTP::redirect(ADMIN_PATH . "page/list");
			else
				$this->_view->alert($result->type, $result->message);
		}

		$this->_view->responseRender($this->_page->removePage($args[0]));
	}

	public function action_add() {
		if (isset($_POST["name"], $_POST["url"], $_POST["text"])) {
			$result = $this->_page->add(
				$_POST["name"], $_POST["url"], $_POST["text"]
			);

			if ($result->code == 0)
				HTTP::redirect(ADMIN_PATH . "page/list");
			else
				$this->_view->alert($result->type, $result->message);
		}

		$this->_view->responseRender($this->_page->addPage());
	}

	public function action_edit($args) {
		$pageId = $args[0];

		if (isset($_POST["name"], $_POST["url"], $_POST["text"]) && $pageId !== false) {
			$result = $this->_page->edit(
				$_POST["name"], $_POST["url"], $_POST["text"], $pageId
			);

			if ($result->code == 0)
				HTTP::redirect(ADMIN_PATH . "page/list");
			else
				$this->_view->alert($result->type, $result->message);
		}

		$this->_view->responseRender($this->_page->editPage($pageId));
	}
}
