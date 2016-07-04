<?php
/**
 * Blog Frontend Controller
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

use model\blog\Posts;
use model\blog\Categories;
use model\blog\Comments;
use model\blog\PostsModeration;
use model\blog\Rating;

use harmony\http\HTTP;

class Blog extends AppController {
	public $__default = "list";

	public $__routes = array (
		"([0-9]+)-[a-z0-9\\_\\-]+/page/([0-9]+)" => "post",
		"([0-9]+)-[a-z0-9\\_\\-]+" => "post",
		"([0-9]+)/page/([0-9]+)" => "post",
		"([0-9]+)" => "post",
		
		"(cat)/([0-9]+)/page/([0-9]+)" => "list",
		"(cat)/([0-9]+)" => "list",
		"(tag)/(.+)/page/([0-9]+)" => "list",
		"(tag)/(.+)" => "list",
		"(author)/(.+)/page/([0-9]+)" => "list",
		"(author)/(.+)" => "list",
		"(page)/([0-9]+)" => "list",
		"search/(.*)/page/([0-9]+)" => "search",
		"search/(.*)" => "search",
		"search" => null,
		
		"archive/([0-9]+)/([0-9]+)/([0-9]+)/(page)/([0-9]+)" => "archive",
		"archive/([0-9]+)/([0-9]+)/([0-9]+)" => "archive",
		"archive/([0-9]+)/([0-9]+)/(page)/([0-9]+)" => "archive",
		"archive/([0-9]+)/([0-9]+)" => "archive",
		"archive/([0-9]+)/(page)/([0-9]+)" => "archive",
		"archive/([0-9]+)" => "archive",
		
		"calendar/([0-9]+)/([0-9]+)" => "calendar",
		"rating/([A-Za-z0-9]+)/([0-9]+)" => "rating",
		"add/([0-9]+)" => "add",
		"add" => null
	);

	private $_posts;

	public function __construct() {
		parent::__construct();

		$this->_posts = new Posts(FRONTEND);
		
		// Add Blog Module Script
		$this->_core->addJS(PATH . "js/blog.js");
	}

	public function getUrls() {
		return array_merge(
			Categories::getInstance()->getUrls(),
			$this->_posts->getUrls()
		);
	}

	public function getProperty($name, $arg) {
		switch ($name) {
			case "categories": return Categories::getInstance()->getList();
			case "archive": return $this->_posts->getArchive();
			case "calendar": return $this->_posts->getCalendar();
			case "popular": return $this->_posts->getPopular();
			case "tags-cloud": return $this->_posts->getTagsCloud();
			case "user-posts-count": return $this->_posts->getUserPostsCount($arg);
			case "user-comments-count":
				$comments_model = new Comments();
				return $comments_model->getUserCommentsCount($arg);

			default:
				return parent::getProperty($name, $arg);
		}
	}

	public function action_list($args) {
		$category = null;
		$page = 1;
		$tag = null;
		$author = null;

		if (isset($args[0])) {
			if ($args[0] == "cat") {
				$category = $args[1];
				$page = isset($args[2]) ? $args[2] : 1;

				// Active Category
				Categories::getInstance()->activeCategory = $category;
			} elseif ($args[0] == "tag") {
				$tag = urldecode($args[1]);
				$page = isset($args[2]) ? $args[2] : 1;
			} elseif ($args[0] == "author") {
				$author = urldecode($args[1]);
				$page = isset($args[2]) ? $args[2] : 1;
			} elseif ($args[0] == "page") {
				$page = intval($args[1]);
			}
		}

		$this->_view->responseRender($this->_posts->get($category, $page, $tag, $author));
	}

	public function action_post($args) {
		$comments_model = new Comments();
		$id = $args[0];

		if (isset($_POST["removecomment"]["id"])) {
			$comment = $comments_model->remove($_POST["removecomment"]["id"]);
			$this->_view->alert($comment->type, $comment->message);
		}

		if (isset($_POST["comment"])) {
			$comment = $comments_model->add($id, $_POST["comment"]);
			$this->_view->alert($comment->type, $comment->message);
		}

		$commentsPage = !isset($args[1]) ? 1 : $args[1];
		$post = $this->_posts->page($id, $commentsPage, $comments_model);
		
		// Active Category
		Categories::getInstance()->activeCategory = $post->tags["category-id"];
		$this->_view->responseRender($post);
	}

	public function action_archive($args) {
		$page = 1;
		$a = [];
		
		for ($i = 0; $i < count($args); $i++) {
			if ($args[$i] == "page") {
				$page = $args[$i + 1];
				break;
			} else
				$a[] = $args[$i];
		}
		
		$this->_view->responseRender($this->_posts->archive($a, $page));
	}
	
	public function action_search($args) {
		if (isset($_POST["query"]))
			HTTP::redirect(SITE_PATH . "blog/search/" . urlencode($_POST["query"]));
		else {
			$query = isset($args[0]) ? urldecode($args[0]) : false;
			$page = isset($args[1]) ? $args[1] : 1;
			$this->_view->responseRender($this->_posts->searchPosts($query, $page));
		}
	}
	
	public function action_rating($args) {
		$model = new Rating();

		if ($args[0] == "plus") {
			$this->_view->jsonRender($model->change($args[1], 1));
		} elseif ($args[0] == "minus") {
			$this->_view->jsonRender($model->change($args[1], 0));
		}
	}
	
	public function action_calendar($args) {		
		$this->_view->jsonRender([
			"code" => 0,
			"calendar" => $this->_posts->getCalendar($args[0], $args[1])
		]);
	}

	public function action_add($args) {
		$moder = new PostsModeration();

		if (isset($_POST["title"], $_POST["url"], $_POST["category"], $_POST["text"], $_POST["image_link"], $_POST["tags"], $_POST["lang"])) {
			$result = $moder->add(
				$_POST["title"], $_POST["url"], $_POST["category"], $_POST["text"], $_POST["image_link"], $_POST["tags"], $_POST["lang"]
			);

			$this->_view->alert($result->type, $result->message);
			if ($result->code == 0) $moder = new PostsModeration(); // Renew model
		}

		$category = isset($args[0]) ? $args[0] : null;
		$this->_view->responseRender($moder->addPage($category));
	}
}
