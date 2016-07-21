<?php
/**
 * Core Backend Controller
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

use model\core\Backup;
use model\core\Main;
use model\core\Statistics;
use model\core\Settings;
use model\core\Packages;
use model\core\Media;

use harmony\http\HTTP;

class Core extends AppController {
	public $__routes = [
		"main" => null,
		"statistics" => null,
		"settings/([a-z]+)" => "settings",
		"settings" => null,
		"packages/install" => "packages_install",
		"packages/remove/([a-z]+)" => "packages_remove",
		"packages" => null,
		"backup" => null,
		"menu/list/(backend)" => "menu_list",
		"menu/list/(frontend)" => "menu_list",
		"menu" => "menu_list",
		"media/list" => "media_list",
		"media/upload" => "media_upload",
		"media/edit/([0-9]+)" => "media_edit",
		"media/remove/([0-9]+)" => "media_remove",
		"media" => "media_list",
	];
	
	public $__default = "main";

	public function action_main() {
		$model = new Main();
		$this->_view->responseRender($model->page());
	}

	public function action_statistics() {
		if (isset($_POST["clear_cache"])) $this->_registry->get("Cache")->clear();
		$model = new Statistics();
		$this->_view->responseRender($model->getPage());
	}

	public function action_settings($args) {
		$name = isset($args[0]) ? $args[0] : "main";
		$model = new Settings();

		if (count($_POST) > 0) {
			$result = $model->save($name, $_POST);
			$this->_view->alert($result->type, $result->message);
		}

		$this->_view->responseRender($model->getPage($name));
	}

	public function action_packages() {
		$model = new Packages();
		$this->_view->responseRender($model->listPage());
	}
	
	public function action_packages_install() {
		$model = new Packages();
		
		if (isset($_FILES["file"])) {
			$upload = $model->upload($_FILES["file"]);
			if ($upload->code != 0) {
				$this->_view->alert($upload->type, $upload->message);
			}
		} elseif (isset($_POST["cancel"])) {
			$model->clearTemp();
		} elseif (isset($_POST["contine"])) {
			$install = $model->install();
			$this->_view->alert($install->type, $install->message);
		}
		
		$this->_view->responseRender($model->installPage());
	}
	
	public function action_packages_remove($args) {
		$model = new Packages();
		
		if (isset($_POST["remove"]["name"])) {
			$result = $model->remove($_POST["remove"]["name"], isset($_POST["remove_links"]));

			if ($result->code == 0)
				HTTP::redirect(ADMIN_PATH . "core/packages");
			else
				$this->_view->alert($result->type, $result->message);
		}

		$this->_view->responseRender($model->removePage($args[0]));
	}

	public function action_backup() {
		$model = new Backup();

		// Make database backup
		if (isset($_POST["make_database"])) {
			$result = $model->makeDatabase();
			$this->_view->alert($result->type, $result->message);
		}

		// Restore database backup
		if (isset($_POST["restore_database"])) {
			$result = $model->restoreDatabase($_POST["restore_database"]);
			$this->_view->alert($result->type, $result->message);
		}

		$this->_view->responseRender($model->getPage());
	}
	
	public function action_menu_list($args) {
		$menu = $this->_registry->get("Menu");
		$type = isset($args[0]) ? $args[0] : FRONTEND;
		$edit_id = 0;
		
		if (isset($_POST["item_id"])) {
			if (isset($_POST["edit"])) $edit_id = $_POST["item_id"];
			if (isset($_POST["remove"])) {
				$menu->remove($_POST["item_id"]);
				HTTP::update();
			}
			
			if (isset($_POST["edit"]["icon"], $_POST["edit"]["pos"], $_POST["edit"]["title"], $_POST["edit"]["link"], $_POST["edit"]["type"])) {
				$result = $menu->edit($edit_id, $_POST["edit"]["icon"], $_POST["edit"]["pos"], $_POST["edit"]["title"], $_POST["edit"]["link"], $_POST["edit"]["type"]);

				if ($result->code == 0) {
					HTTP::update();
				} else {
					$this->_view->alert($result->type, $result->message);
				}
			}
		}
		
		if (isset($_POST["add"]["icon"]) && isset($_POST["add"]["pos"]) && isset($_POST["add"]["title"]) && isset($_POST["add"]["link"]) && isset($_POST["add"]["type"])) {
			$result = $menu->add($_POST["add"]["icon"], $_POST["add"]["pos"], $_POST["add"]["title"], $_POST["add"]["link"], $_POST["add"]["type"]);
			$this->_view->alert($result->type, $result->message);
		}
		
		$this->_view->responseRender($menu->listPage($type, $edit_id));
	}
	
	public function action_media_list() {
		$model = new Media();
		$this->_view->responseRender($model->getList());
	}
	
	public function action_media_upload() {
		$model = new Media();

		if (isset($_FILES["file"])) {
			$upload = $model->upload($_FILES["file"]);
			if ($upload->code == 0 && !$this->_ajax) {
				HTTP::redirect(ADMIN_PATH . "core/media/edit/" . $upload->tags["id"]);
			} else
				$this->_view->alert($upload->type, $upload->message);
		}
		
		$this->_view->responseRender($model->getUploadPage());
	}
	
	public function action_media_edit($args) {
		$model = new Media();
		
		if (isset($_POST["name"]) && isset($_POST["description"])) {
			$upload = $model->edit($_POST["name"], $_POST["description"], $args[0]);
			if ($upload->code == 0 && !$this->_ajax)
				HTTP::redirect(ADMIN_PATH . "core/media");
			else
				$this->_view->alert($upload->type, $upload->message);
		}
		
		$this->_view->responseRender($model->editPage($args[0]));
	}
	
	public function action_media_remove($args) {
		$model = new Media();
		
		if (isset($_POST["remove"]["id"])) {
			$result = $model->remove($_POST["remove"]["id"]);

			if ($result->code == 0)
				HTTP::redirect(ADMIN_PATH . "core/media");
			else
				$this->_view->alert($result->type, $result->message);
		}

		$this->_view->responseRender($model->removePage($args[0]));
	}
}
