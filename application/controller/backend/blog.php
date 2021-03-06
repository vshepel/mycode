<?php
/**
 * Blog Backend Controller
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

use model\blog\Posts;
use model\blog\PostsModeration;
use model\blog\Statistics;
use model\blog\Settings;
use model\blog\Categories;

use harmony\http\HTTP;

class Blog extends AppController {
	private $_posts;

	public $__default = "statistics";

	public $__routes = array (
		"statistics" => null,
		"settings" => null,
		"categories" => null,
		"moderation/(good)/([0-9]+)" => "moderation_act",
		"moderation/(bad)/([0-9]+)" => "moderation_act",
		"moderation/page/([0-9]+)" => "posts",
		"moderation" => null,
		"posts/(cat)/([0-9]+)/page/([0-9]+)" => "posts",
		"posts/(cat)/([0-9]+)" => "posts",
		"posts/page/([0-9]+)" => "posts",
		"posts" => null,
		"remove/([0-9]+)" => "remove",
		"add" => null,
		"edit/([0-9]+)" => "edit"
	);

	public function __construct() {
		parent::__construct();
		$this->_posts = new Posts(BACKEND);
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

	public function action_categories() {
		$model = Categories::getInstance();
		$edit_id = -1;

		if (isset($_POST["item_id"])) {
			if (isset($_POST["edit"])) $edit_id = $_POST["item_id"];
			if (isset($_POST["remove"])) {
				$model->remove($_POST["item_id"]);
				HTTP::update();
			}

			if (isset($_POST["edit"]["name"])) {
				$result = $model->edit($edit_id, $_POST["edit"]["name"]);

				if ($result->code == 0) {
					HTTP::update();
				} else {
					$this->_view->alert($result->type, $result->message);
				}
			}
		}

		if (isset($_POST["add"]["name"])) {
			$result = $model->add($_POST["add"]["name"]);
			if ($result->code == 0) {
				HTTP::update();
			} else {
				$this->_view->alert($result->type, $result->message);
			}
		}

		$this->_view->responseRender($model->getPage($edit_id));
	}

	public function action_moderation_act($args) {
		$model = new PostsModeration();
		$id = intval($args[1]);

		if ($args[0] == "good") {
			$post = $model->good($id);
			if ($post === false) {
				HTTP::redirect(ADMIN_PATH . "blog/moderation");
			} else {
				HTTP::redirect(ADMIN_PATH . "blog/edit/" . $post);
			}
		} elseif ($args[0] == "bad") {
			$model->bad($id);
			HTTP::redirect(ADMIN_PATH . "blog/moderation");
		}
	}

	public function action_moderation($args) {
		$model = new PostsModeration();
		$page = isset($args[0]) ? $args[0] : 1;
		$this->_view->responseRender($model->get(null, $page));
	}

	public function action_posts($args) {
		if (isset($args[0]) && $args[0] == "cat") {
			$category = $args[1];
			$page = isset($args[2]) ? $args[2] : 1;

			// Active Category
			Categories::getInstance()->activeCategory = $category;
		} else {
			$category = null;
			$page = isset($args[0]) ? $args[0] : 1;
		}

		$this->_view->responseRender($this->_posts->get($category, $page, null, null, false));
	}

	public function action_remove($args) {
		if (isset($_POST["remove"]["id"])) {
			$result = $this->_posts->remove($_POST["remove"]["id"]);

			if ($result->code == 0)
				HTTP::redirect(ADMIN_PATH . "blog/posts");
			else
				$this->_view->alert($result->type, $result->message);
		}

		$this->_view->responseRender($this->_posts->removePage($args[0]));
	}

	public function action_add() {
		if (isset($_POST["title"], $_POST["url"], $_POST["category"], $_POST["text"], $_POST["image_link"], $_POST["tags"], $_POST["lang"])) {
			$result = $this->_posts->add(
				$_POST["title"], $_POST["url"], $_POST["category"], $_POST["text"], $_POST["image_link"], $_POST["tags"], $_POST["lang"],
				isset($_POST["allow_comments"]), isset($_POST["show"]), isset($_POST["show_main"]), isset($_POST["show_category"])
			);

			if ($result->code == 0)
				HTTP::redirect(ADMIN_PATH . "blog/posts");
			else
				$this->_view->alert($result->type, $result->message);
		}

		$this->_view->responseRender($this->_posts->addPage());
	}

	public function action_edit($args) {
		$postId = $args[0];

		if (isset($_POST["title"], $_POST["url"], $_POST["category"], $_POST["text"], $_POST["image_link"], $_POST["tags"], $_POST["lang"])) {
			$result = $this->_posts->edit(
				$postId,
				$_POST["title"], $_POST["url"], $_POST["category"], $_POST["text"], $_POST["image_link"], $_POST["tags"], $_POST["lang"],
				isset($_POST["allow_comments"]), isset($_POST["show"]), isset($_POST["show_main"]), isset($_POST["show_category"])
			);

			$this->_view->alert($result->type, $result->message);
		}

		$this->_view->responseRender($this->_posts->editPage($postId));
	}
}
