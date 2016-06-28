<?php
/**
 * User Frontend Controller
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
use Response;

use model\user\UserModule;
use model\user\Restore;
use model\user\Edit;
use model\user\Sessions;

use harmony\http\HTTP;

class User extends AppController {
	public $__routes = array (
		"profile/([A-Za-z0-9._-]+)" => "profile",
		"auth" => null,
		"logout" => null,
		"restore/([A-Za-z0-9._-]+)" => "restore",
		"restore" => null,
		"register" => null,
		"list/page/([0-9]+)" => "list",
		"list" => null,
		"edit/([A-Za-z0-9]+)" => "edit",
		"edit" => null,
		"sessions/page/([0-9]+)" => "sessions",
		"sessions" => null,
		"notifications/([A-Za-z0-9]+)" => "notifications"
	);
	
	private $_model;
	
	public function __construct() {
		parent::__construct();
		$this->_model = new UserModule();
	}

	public function action_index() {
		HTTP::redirect(SITE_PATH);
	}

	public function action_profile($args) {
		$this->_view->responseRender($this->_model->profilePage($args[0]));
	}

	public function action_auth() {
		if (isset($_POST["login"], $_POST["password"])) {
			$auth = $this->_model->auth($_POST["login"], $_POST["password"], isset($_POST["tpc"]));

			if ($auth->code == 0 && !$this->_ajax)
				HTTP::redirect(SITE_PATH);
			else
				$this->_view->alert($auth->type, $auth->message);
		}

		$this->_view->responseRender($this->_model->authPage());
	}

	public function action_logout() {
		$user = $this->_registry->get("User");
		$logout = $user->sessionClose($user->getToken());

		if ($logout) {
			HTTP::redirect(SITE_PATH);
		} else {
			$this->_view
				->alert("danger", $this->_registry->get("Lang")->get("user", "logout.error"))
				->render();
		}
	}

	public function action_restore($args) {
		$key = isset($args[0]) ? $args[0] : false;

		if ($key === false) {
			$model = new Restore();

			if (isset($_POST["login"])) {
				$restore = $model->send($_POST["login"]);
				$this->_view->alert($restore->type, $restore->message);
			}

			$this->_view->responseRender($model->page());
		} else {
			$model = new Restore();

			if (isset($_POST["password"], $_POST["password_2"])) {
				$restore = $model->change($key, $_POST["password"], $_POST["password_2"]);

				if ($restore->code == 0)
					HTTP::redirect(SITE_PATH . "user/auth");
				else
					$this->_view->alert($restore->type, $restore->message);
			}

			$this->_view->responseRender($model->pageRestore($key));
		}
	}

	public function action_register() {
		if (isset($_POST["register"])) {
			$reg = $_POST["register"];

			if (isset($reg["email"], $reg["login"], $reg["password"], $reg["password_2"], $reg["name"], $reg["captcha"])) {
				$auth = $this->_model->register($reg["email"], $reg["login"], $reg["password"], $reg["password_2"], $reg["name"], $reg["captcha"]);

				if ($auth->code == 0 && !$this->_ajax) {
					$this->_model->auth($reg["login"], $reg["password"], false);
					HTTP::redirect(SITE_PATH);
				} else {
					$this->_view->alert($auth->type, $auth->message);
				}
			}
		}

		$this->_view->responseRender($this->_model->registerPage());
	}

	public function action_list($args) {
		$this->_view->responseRender($this->_model->listPage((isset($args[0]) ? $args[0] : 1)));
	}

	public function action_edit($args) {
		$page = isset($args[0]) ? $args[0] : false;
		$model = new Edit(FRONTEND);

		if (isset($_POST["edit"])) {
			$edit = $model->edit($page, $_POST["edit"]);
			$this->_view->alert($edit->type, $edit->message);
		}

		$this->_view->responseRender($model->page($page));
	}

	public function action_sessions($args) {
		$sessions = new Sessions();

		// Close session
		if (isset($_POST["id"])) {
			$close = $sessions->close($_POST["id"]);
			$this->_view->alert($close->type, $close->message);
		} elseif(isset($_POST["close_all"])) {
			$close = $sessions->close(null, null, 1);
			$this->_view->alert($close->type, $close->message);
		}

		$page = isset($args[0]) ? $args[0] : 1;
		$this->_view->responseRender($sessions->get($page));
	}
	
	public function action_notifications($args) {
		$model = $this->_registry->get("Notifications");

		switch($args[0]) {
			case "remove":
				if (isset($_POST["id"])) {
					$model->removeById($_POST["id"], true);
					$this->_view->jsonRender(
						new Response()
					);
				}
			break;

			case "clear":
				$model->clear($this->_registry->get("User")->get("id"));
				$this->_view->jsonRender(
					new Response()
				);
			break;

			case "get":
				$this->_view->jsonRender(
					$model->get()
				);
			break;
		}
	}
}
