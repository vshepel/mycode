<?php
/**
 * User Backend Controller
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

use model\user\UserModule;
use model\user\Statistics;
use model\user\Settings;
use model\user\Groups;
use model\user\Edit;

use harmony\http\HTTP;

class User extends AppController {
	public $__default = "statistics";

	public $__routes = array (
		"statistics" => null,
		"settings" => null,
		"groups/edit/([0-9]+)" => "groups_edit",
		"groups/remove/([0-9]+)" => "groups_remove",
		"groups/page/([0-9]+)" => "groups",
		"groups" => null,
		"list/page/([0-9]+)" => "list",
		"list" => null,
		"auth" => null,
		"add" => null,
		"edit/([0-9]+)/([A-Za-z0-9]+)" => "edit",
		"edit/([0-9]+)" => "edit",
		"remove/([0-9]+)" => "remove"
	);

	private $_model;
	
	public function __construct() {
		parent::__construct();
		$this->_model = new UserModule();
	}

	public function action_statistics() {
		$model = new Statistics();
		$this->_view->responseRender($model->getPage());
	}
	
	public function action_settings() {
		$model = new Settings();
		
		if (count($_POST) > 0) {
			$result = $model->save($_POST);
			$this->_view->alert($result->type, $result->message);
		}

		$this->_view->responseRender($model->page());
	}

	public function action_groups_edit($args) {
		$model = new Groups();

		if (isset($_POST["edit"]["name"], $_POST["edit"]["extends"], $_POST["edit"]["permissions"])) {
			$edit = $model->edit($args[0], $_POST["edit"]["name"], $_POST["edit"]["extends"], $_POST["edit"]["permissions"]);
			$this->_view->alert($edit->type, $edit->message);
		}

		$this->_view->responseRender($model->editPage($args[0]));
	}

	public function action_groups_remove($args) {
		$model = new Groups();

		if (isset($_POST["remove"]["id"])) {
			$model->remove($_POST["remove"]["id"]);
			HTTP::redirect(ADMIN_PATH . "user/groups");
		}

		$this->_view->responseRender($model->removePage($args[0]));
	}

	public function action_groups($args) {
		$model = new Groups();

		if (isset($_POST["add"]["name"])) {
			$edit = $model->add($_POST["add"]["name"]);
			$this->_view->alert($edit->type, $edit->message);
		}
		
		$this->_view->responseRender($model->get(isset($args[1]) ? $args[1] : 1));
	}

	public function action_list($args) {
		$this->_view->responseRender($this->_model->listPage((isset($args[0]) ? $args[0] : 1)));
	}

	public function action_add() {
		if (isset($_POST["add"])) {
			$add = $_POST["add"];

			if (isset($add["email"], $add["login"], $add["password"], $add["password_2"], $add["name"])) {
				$auth = $this->_model->add($add["email"], $add["login"], $add["password"], $add["password_2"], $add["name"]);

				if ($auth->code == 0 && !$this->_ajax)
					HTTP::redirect(ADMIN_PATH . "user/list");
				else
					$this->_view->alert($auth->type, $auth->message);
			}
		}

		$this->_view->responseRender($this->_model->addPage());
	}

	public function action_edit($args) {
		$page = isset($args[1]) ? $args[1] : false;
		$model = new Edit();

		if (isset($_POST["edit"])) {
			$edit = $model->edit($page, $_POST["edit"], $args[0]);
			$this->_view->alert($edit->type, $edit->message);
		}

		$this->_view->responseRender($model->page($page, $args[0]));
	}

	public function action_remove($args) {
		if (isset($_POST["remove"]["id"])) {
			$this->_model->remove($_POST["remove"]["id"]);
			HTTP::redirect(ADMIN_PATH . "user/list");
		}

		$this->_view->responseRender($this->_model->removePage($args[0]));
	}
}
