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
use model\blog\Search;
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
		"page/([0-9]+)" => "list",
		"addcomment" => null,
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
		
		"rating/([A-Za-z0-9]+)/([0-9]+)" => "rating"
	);

	private $_posts;

	public function __construct() {
		parent::__construct();

		$this->_posts = new Posts(FRONTEND);
		
		// Add Blog Module Script
		$this->_core->addJS(PATH . "js/blog.js");

		// Add Blog Categories for layout template
		$this->_view->addMainTag("blog-categories",
			Categories::getInstance()->getList()
		);
		
		// Add Blog Archive for layout template
		$this->_view->addMainTag("blog-archive",
			$this->_posts->getArchive()
		);
		
		// Add Blog Calendar for layout template
		$this->_view->addMainTag("blog-calendar",
			$this->_posts->getCalendar()
		);
	}

	public function action_list($args) {
		if (isset($args[0]) && $args[0] == "cat") {
			$category = $args[1];
			$page = isset($args[2]) ? $args[2] : 1;
			
			// Add Blog Categories for layout template
			$this->_view->addMainTag("blog-categories",
				Categories::getInstance()->getList($category)
			);
		} else {
			$category = null;
			$page = isset($args[0]) ? $args[0] : 1;
		}

		$this->_view->responseRender($this->_posts->get($category, $page));
	}

	public function action_post($args) {
		$comments_model = new Comments();
		$id = $args[0];

		if (isset($_POST["comment"])) {
			$comment = $comments_model->add($id, $_POST["comment"]);
			$this->_view->alert($comment->type, $comment->message);
		}

		$commentsPage = !isset($args[1]) ? 1 : $args[1];
		$post = $this->_posts->page($id, $commentsPage, $comments_model);
		
		// Add Blog Categories for layout template
		$this->_view->addMainTag("blog-categories",
			Categories::getInstance()->getList($post->tags["category-id"])
		);

		$this->_view->responseRender($post);
	}

	public function action_addcomment() {
		if ($this->_ajax) {
			$comments_model = new Comments();

			if (isset($_POST["post"], $_POST["comment"])) {
				$add = $comments_model->add($_POST["post"], $_POST["comment"]);
				$rows = $comments_model->get($_POST["post"], 1, true);

				$this->_view
					->jsonRender(array(
						"add" => $add,
						"comments" => array (
							"num" => $rows->tags["num"],
							"rows" => $rows->tags["rows"],
							"pagination" => $rows->tags["pagination"]
						)
					));
			}
		}
	}

	public function action_archive($args) {
		$page = 1;
		$a = [];
		
		for ($i = 0; $i < count($args); $i++) {
			if ($args[$i] == "page") {
				$page = $args[$i+1];
				break;
			} else
				$a[] = $args[$i];
		}
		
		// Add Blog Archive for layout template
		$this->_view->addMainTag("blog-archive",
			$this->_posts->getArchive()
		);
		
		// Add Blog Calendar for layout template
		if (isset($args[0], $args[1])) $this->_view->addMainTag("blog-calendar",
			$this->_posts->getCalendar($args[1], $args[0])
		);
		
		$this->_view->responseRender($this->_posts->archive($a, $page));
	}
	
	public function action_search($args) {
		if (isset($_POST["query"]))
			HTTP::redirect(SITE_PATH . "blog/search/" . urlencode($_POST["query"]));
		else {
			$query = isset($args[0]) ? urldecode($args[0]) : false;
			$page = isset($args[1]) ? $args[1] : 1;
			$model = new Search();
			$this->_view->responseRender($model->searchPosts($query, $page));
		}
	}
	
	public function action_rating($args) {
		$model = new Rating();

		if ($args[0] == "plus") {
			$this->_view
				->jsonRender($model->change($args[1], 1));
		} elseif ($args[0] == "minus") {
			$this->_view
				->jsonRender($model->change($args[1], 0));
		}
	}
	
	public function action_calendar($args) {		
		$this->_view->jsonRender([
			"code" => 0,
			"calendar" => $this->_posts->getCalendar($args[0], $args[1])
		]);
	}
}
