<?php
/**
 * Blog Posts Model
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

namespace model\blog;

use AppModel;
use Response;
use NotFoundException;

use harmony\pagination\Pagination;
use harmony\bbcode\BBCodeParser;
use harmony\strings\StringFilters;

class Posts extends AppModel {
	/**
	 * @var string Side type
	 */
	private $_type;

	/**
	 * @var array Add post tags
	 */
	private $_addTags = array (
		"category" => 0,

		"tags" => array (
			"title" => "",
			"url" => "",
			"short-text" => "",
			"full-text" => "",
			
			"allow-comments" => true,
			"show" => true,
			"show-main" => true,
			"show-category" => true
		)
	);

	/**
	 * @var bool Have edit query?
	 */
	private $_editQuery = false;

	/**
	 * Constructor
	 * @param string $type Side type
	 */
	public function __construct($type) {
		parent::__construct();

		$this->_type = $type;
	}

	/**
	 * Check post for exists
	 * @param int $postId Post ID
	 * @param bool $show = true Post allow to show?
	 * @return bool Is exist?
	 * @throws \Exception
	 */
	public function exists($postId, $show = true) {
		$this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_posts")
			->where("id", "=", intval($postId));

		if ($show)
			$this->_db
				->and_where("show", "=", 1);

		$result = $this->_db->result_array();

		if (isset($result[0][0]))
			return ($result[0][0] > 0);
		else
			throw new \Exception("Error check post for exists: {$this->_db->getError()}");
	}

	/**
	 * Get posts by category and page
	 * @param int $category Posts category (NULL, for all categories)
	 * @param int $page Posts page
	 * @param bool $show = true Show allowed to display posts?
	 * @return Response
	 */
	public function get($category, $page, $show = true) {	  
		$this->_core->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog");
		
		if (!$this->_user->hasPermission("blog.posts.list")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$response = new Response();
		
		$category = ($category === null) ? null : intval($category);
		$page = intval($page);
		$show = (bool)($show);

		// Number query
		if ($this->_type == BACKEND)
			$this->_core->addBreadcrumbs($this->_lang->get("blog", "list.moduleName"), "blog/posts");

		$this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_posts")
			->where("id", ">", 0);
			
		if ($show) $this->_db->and_where("show", ">", 0);

		if ($category !== null) {
			$this->_db->and_where("category", "=", $category);
			if ($show) $this->_db->and_where("show_category", ">", 0);
		} else {
			if ($show) $this->_db ->and_where("show_main", ">", 0);
		}

		$num = $this->_db->result_array();

		if ($num === false) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);

			return $response;
		} else {
			$paginationPrefix = (($this->_type == BACKEND) ? ADMIN_PATH . "blog/posts" : SITE_PATH . "blog") . "/" . (($category === null) ? "page/" : "cat/" . $category . "/page/");
			$num = $num[0][0];
			$pagination = new Pagination($num, $page, $paginationPrefix, $this->_config->get("blog", "list.customPagination", array()));

			// Posts query
			$this->_db
				->select(array(
					"id", "title", "url", "short_text", "full_text", "category", "comments_num", "views_num", "rating",
					array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false),
					"show", "show_main", "show_category", "author"
				))
				->from(DBPREFIX . "blog_posts")
				->where("id", ">", 0);
				
			if ($show) $this->_db->and_where("show", ">", 0);

			if ($category !== null) {
				$this->_db->and_where("category", "=", $category);
				if ($show) $this->_db->and_where("show_category", ">", 0);
			} else {
				if ($show) $this->_db ->and_where("show_main", ">", 0);
			}

			$array = $this->_db
				->order_by("id", $this->_config->get("blog", "list.sort", "DESC"))
				->limit($pagination->getSqlLimits())
				->result_array();

			if ($array === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$noRows = (count($array) == 0);

				// Posts make
				foreach ($array as $row)
					$this->_view->add("blog.list.post", array(
						"id" => $row["id"],
						"link" => SITE_PATH . "blog/" . $row["id"] . "-" . $row["url"],
						"title" => $row["title"],

						"author-id" => $row["author"],
						"author-login" => $this->_user->getUserLogin($row["author"]),
						"author-name" => $this->_user->getUserName($row["author"]),
						"author-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["author"]),

						"avatar-link" => $this->_user->getAvatarLinkById($row["author"]),

						"short-text" => BBCodeParser::parse($row["short_text"]),
						"full-text" => BBCodeParser::parse((empty($row["full-text"]) ? $row["short_text"] : $row["full_text"])),

						"category-id" => $row["category"],
						"category-name" => Categories::getInstance()->getName($row["category"]),
						"category-link" => (($this->_type == BACKEND) ? ADMIN_PATH . "blog/posts" : SITE_PATH . "blog") . "/cat/" . $row["category"],

						"archive-link" => SITE_PATH . "blog/archive/" . date("Y/m/d", $row["timestamp"]),
						"edit-link" => ADMIN_PATH . "blog/edit/" . $row["id"],
						"remove-link" => ADMIN_PATH . "blog/remove/" . $row["id"],

						"iso-datetime" => $this->_core->getISODateTime($row["timestamp"]),
						"date" => $this->_core->getDate($row["timestamp"]),
						"time" => $this->_core->getTime($row["timestamp"]),

						"comments-num" => $row["comments_num"],
						"views-num" => $row["views_num"],
						"rating" => $row["rating"],
						
						"show" => ($row["show"] > 0),
						"not-show" => ($row["show"] < 1),
						
						"show-main" => ($row["show_main"] > 0),
						"not-show-main" => ($row["show_main"] < 1),
						
						"show-category" => ($row["show_category"] > 0),
						"not-show-category" => ($row["show_category"] < 1),
						
						"edit" => $this->_user->hasPermission("admin.blog.posts.edit"),
						"remove" => $this->_user->hasPermission("admin.blog.posts.remove"),
					));

				// Add category breadcrumbs if exist
				if ($category !== null)
					$this->_core->addBreadcrumbs(Categories::getInstance()->getName($category), "blog/cat/" . $category);

				// Formation response
				$response->code = 0;
				$response->view = "blog.list.page";
				$response->tags = array (
					"num" => $num,
					"posts" => $this->_view->get("blog.list.post"),
					"pagination" => $pagination
				);
			}
		}

		return $response;
	}

	/**
	 * Get post page
	 * @param int $id Post ID
	 * @param int $commentsPage Comments page
	 * @param Comments $comments_model Comments model
	 * @return Response
	 */
	public function page($id, $commentsPage, $comments_model) {
		$this->_core->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog");
		
		if (!$this->_user->hasPermission("blog.posts.read")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$response = new Response();

		$id = intval($id);
		$commentsPage = intval($commentsPage);

		/**
		 * Update views
		 */
		$this->_db
			->update(DBPREFIX . "blog_posts")
			->set(array (
				"views_num" => array ("views_num", "+", 1, false)
			))
			->where("id", "=", $id)
			->and_where("show", "=", 1)
			->result();

		/**
		 * Get post
		 */
		$array = $this->_db
			->select(array(
				"id", "title", "url", "short_text", "full_text", "category",
				"comments_num", "views_num", "rating", array ("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false),
				"allow_comments", "author"
			))
			->from(DBPREFIX . "blog_posts")
			->where("id", "=", $id)
			->and_where("show", "=", 1)
			->result_array();

		if ($array === false) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);

			return $response;
		} elseif (count($array) == 0) {
			throw new NotFoundException();
		} else {
			$row = $array[0];

			/**
			 * Add breadcrumbs
			 */
			$this->_core
				->addBreadcrumbs(Categories::getInstance()->getName($row["category"]), "blog/cat/" . $row["category"])
				->addBreadcrumbs($row["title"], SITE_PATH . "blog/" . $row["id"]);

			$comments = $comments_model->get($id, $commentsPage, $row["allow_comments"], $row["url"]);

			if ($comments->code == 0)
				$comments = $this->_view->parse($comments->view, $comments->tags);
			else
				$comments = $this->_view->getAlert($comments->type, $comments->message);

			$response->code = 0;
			$response->view = "blog.post.page";

			/**
			 * Add tags
			 */
			$response->tags = array(
				"id" => $row["id"],
				"link" => SITE_PATH . "blog/" . $row["id"] . "-" . $row["url"],
				"title" => $row["title"],

				"short-text" => BBCodeParser::parse($row["short_text"]),
				"full-text" => BBCodeParser::parse((empty($row["full_text"]) ? $row["short_text"] : $row["full_text"])),

				"category-id" => $row["category"],
				"category-name" => Categories::getInstance()->getName($row["category"]),
				"category-link" => SITE_PATH . "blog/cat/" . $row["category"],
				
				"archive-link" => SITE_PATH . "blog/archive/" . date("Y/m/d", $row["timestamp"]),
				"edit-link" => ADMIN_PATH . "blog/edit/" . $row["id"],
				"remove-link" => ADMIN_PATH . "blog/remove/" . $row["id"],

				"iso-datetime" => $this->_core->getISODateTime($row["timestamp"]),
				"date" => $this->_core->getDate($row["timestamp"]),
				"time" => $this->_core->getTime($row["timestamp"]),

				"author-id" => $row["author"],
				"author-login" => $this->_user->getUserLogin($row["author"]),
				"author-name" => $this->_user->getUserName($row["author"]),
				"author-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["author"]),

				"avatar-link" => $this->_user->getAvatarLinkById($row["author"]),

				"comments-num" => $row["comments_num"],
				"views-num" => $row["views_num"],
				"rating" => $row["rating"],

				"comments" => $comments,
				
				"edit" => $this->_user->hasPermission("admin.blog.posts.edit"),
				"remove" => $this->_user->hasPermission("admin.blog.posts.remove")
			);
		}

		return $response;
	}

	/**
	 * Edit post
	 * @param int $postId Post ID
	 * @param string $title Post title
	 * @param string $url Post url
	 * @param int $category Post category
	 * @param string $shortText Short text
	 * @param string $fullText Full text
	 * @param bool $allowComments Allow comments?
	 * @param bool $show Show posts?
	 * @param bool $showMain Show posts on main?
	 * @param bool $showCaregory Show posts on category?
	 * @return Response
	 */
	public function edit($postId, $title, $url, $category, $shortText, $fullText, $allowComments, $show, $showMain, $showCaregory) {
		if (!$this->_user->hasPermission("blog.posts.edit")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$this->_editQuery = true;

		if ($this->exists($postId, false))
			return $this->add($title, $url, $category, $shortText, $fullText, $allowComments, $show, $showMain, $showCaregory, $postId);
		else {
			$response = new Response();

			$response->code = 3;
			$response->message = $this->_lang->get("blog", "edit.notFound");

			return $response;
		}
	}

	/**
	 * Edit post page
	 * @param int $postId Post ID
	 * @return Response
	 */
	public function editPage($postId) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog")
			->addBreadcrumbs($this->_lang->get("blog", "edit.moduleName"));
			
		if (!$this->_user->hasPermission("blog.posts.edit")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));

		$postId = intval($postId);

		$response = new Response();

		$response->view = "blog.edit.page";

		if ($this->exists($postId, false)) {
			if (!$this->_editQuery) {
				$row = $this->_db
					->select(array(
						"id", "url", "title", "short_text", "full_text", "category",
						"allow_comments", "show", "show_main", "show_category"
					))
					->from(DBPREFIX . "blog_posts")
					->where("id", "=", $postId)
					->result_array();

				if ($row === false) {
					$response->code = 1;
					$response->type = "danger";
					$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);

					return $response;
				} else {
					$row = $row[0];

					$this->_addTags = array (
						"category" => $row["category"],

						"tags" => array (
							"title" => $row["title"],
							"url" => $row["url"],
							"short-text" => $row["short_text"],
							"full-text" => $row["full_text"],
							
							"allow-comments" => ($row["allow_comments"] > 0),
							"show" => ($row["show"] > 0),
							"show-main" => ($row["show_main"] > 0),
							"show-category" => ($row["show_category"] > 0)
						)
					);
				}
			}

			foreach (Categories::getInstance()->get() as $id => $row)
				$this->_view->add("blog.add.category", [
					"id" => $id,
					"name" => $row["name"],
					"num" => $row["num"],
					
					"current" => ($this->_addTags["category"] == $id)
				]);

			$response->tags = array_merge($this->_addTags["tags"], array (
				"categories" => $this->_view->get("blog.add.category")
			));
		} else {
			$response->code = 3;
			$response->type = "danger";
			$response->message = $this->_lang->get("blog", "edit.notExists");
		}

		return $response;
	}

	/**
	 * Add post
	 * @param string $title Post title
	 * @param string $url Post url
	 * @param int $category Post category
	 * @param string $shortText Short text
	 * @param string $fullText Full text
	 * @param bool $allowComments Allow comments?
	 * @param bool $show Show posts?
	 * @param bool $showMain Show posts on main?
	 * @param bool $showCaregory Show posts on category?
	 * @param int $postId = null Edit post ID
	 * @return Response
	 */
	public function add($title, $url, $category, $shortText, $fullText, $allowComments, $show, $showMain, $showCategory, $postId = null) {
		if (!$this->_user->hasPermission("blog.posts.edit") && $postId === null) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$title = StringFilters::filterHtmlTags($title);
		$url = StringFilters::filterForUrl(empty($url) ? $title : $url);
		$category = intval($category);
		$shortText = StringFilters::filterHtmlTags($shortText);
		$fullText = StringFilters::filterHtmlTags($fullText);
		$allowComments = (bool)($allowComments);
		$show = (bool)($show);
		$showMain = (bool)($showMain);
		$showCategory = (bool)($showCategory);
		$postId = ($postId === null) ? $postId : intval($postId);

		$this->_addTags = array (
			"category" => $category,

			"tags" => array (
				"title" => $title,
				"url" => $url,
				"short-text" => $shortText,
				"full-text" => $fullText,
				
				"allow-comments" => $allowComments,
				"show" => $show,
				"show-main" => $showMain,
				"show-category" => $showCategory
			)
		);

		$response = new Response();

		if (empty($title) || empty($shortText)) {
			$response->code = 2;
			$response->type = "warning";
			$response->message = $this->_lang->get("core", "emptyFields");
		} else {			
			$values = array(
				"title" => $title,
				"url" => $url,
				"category" => $category,
				"short_text" => $shortText,
				"full_text" => $fullText,
				"allow_comments" => $allowComments ? 1 : 0,
				"show" => $show ? 1 : 0,
				"show_main" => $showMain ? 1 : 0,
				"show_category" => $showCategory ? 1 : 0,
				"author" => $this->_user->get("id")
			);

			$edit = ($postId !== null);

			if (!$edit)
				$result = $this->_db
					->insert_into(DBPREFIX . "blog_posts")
					->values($values)
					->result();
			else
				$result = $this->_db
					->update(DBPREFIX . "blog_posts")
					->set($values)
					->where("id", "=", $postId)
					->result();

			if ($result === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$response->type = "success";
				$response->message = $this->_lang->get("blog", ($edit ? "edit" : "add") . ".success");
				$this->_cache->remove("blog"); // Clear cache
			}
		}

		return $response;
	}

	/**
	 * Add post page
	 * @return Response
	 */
	public function addPage() {
		$this->_core
			->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog")
			->addBreadcrumbs($this->_lang->get("blog", "add.moduleName"));
			
		if (!$this->_user->hasPermission("blog.posts.edit")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));

		$response = new Response();

		$response->view = "blog.add.page";

		foreach (Categories::getInstance()->get() as $id => $row)
			$this->_view->add("blog.add.category", [
				"id" => $id,
				"name" => $row["name"],
				"num" => $row["num"],
				"current" => ($this->_addTags["category"] == $id)
			]);

		$response->tags = array_merge($this->_addTags["tags"], array (
			"categories" => $this->_view->get("blog.add.category")
		));

		return $response;
	}

	/**
	 * Remove post
	 * @param int $id Post ID
	 * @return Response
	 */
	public function remove($id) {
		if (!$this->_user->hasPermission("blog.posts.remove")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$response = new Response();
		$id = intval($id);

		if ($this->exists($id, false)) {
			$query = $this->_db
				->delete_from(DBPREFIX . "blog_posts")
				->where("id", "=", $id)
				->result();

			if ($query === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("main", "internalError", [$this->_db->getError()]);
			} else {
				$response->type = "success";
				$response->message = $this->_lang->get("blog", "remove.success");
				$this->_cache->remove("blog"); // Clear cache
			}
		} else {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("blog", "remove.notExists");
		}

		return $response;
	}

	/**
	 * Remove post page
	 * @param int $id Post ID
	 * @return Response
	 * @throws \Exception
	 */
	public function removePage($id) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog")
			->addBreadcrumbs($this->_lang->get("blog", "remove.moduleName"));
			
		if (!$this->_user->hasPermission("blog.posts.remove")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$response = new Response();
		$id = intval($id);

		if ($this->exists($id, false)) {
			$response->view = "blog.remove.page";

			$response->tags["id"] = $id;
		} else {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("blog", "remove.notExists");
		}

		return $response;
	}
	
	/**
	 * Get posts by category and page
	 * @param array $args Year, month and day of posts
	 * @param int $page
	 * @return Response
	 */
	public function archive($args, $page) {	  
		$this->_core
			->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog")
			->addBreadcrumbs($this->_lang->get("blog", "archive.moduleName"), "blog/archive");
		
		// Access denied
		if (!$this->_user->hasPermission("blog.posts.archive")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		// Incorrect args
		if (
			isset($args[1]) && ($args[1] < 1 || $args[1] > 12) 
		) return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$response = new Response();

		$this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_posts")
			->where("id", ">", 0)
			->and_where("show", ">", 0);
			
		if (isset($args[0])) $this->_db->and_where("date_format(timestamp, '%Y')", "=", $args[0], false);
		if (isset($args[1])) $this->_db->and_where("date_format(timestamp, '%Y-%m')", "=", $args[0] . "-" . $args[1], false);
		if (isset($args[2])) $this->_db->and_where("date_format(timestamp, '%Y-%m-%d')", "=", $args[0] . "-" . $args[1] . "-" . $args[2], false);
		
		if (isset($args[0]) && isset($args[1])) {
			$this->_calendar_year = $args[0];
			$this->_calendar_month = $args[1];
		}

		$num = $this->_db->result_array();

		if ($num === false) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);

			return $response;
		} else {
			$paginationPrefix = SITE_PATH . "blog/archive/page/";
			$num = $num[0][0];
			$pagination = new Pagination($num, $page, $paginationPrefix, $this->_config->get("blog", "archive.customPagination", array()));
			
			// Breadcrumbs
			$title = "";
			if (isset($args[2])) $title .= $args[2] . " ";
			if (isset($args[1])) $title .= $this->_lang->get("core", "month." . intval($args[1])) . " ";
			if (isset($args[0])) $title .= $args[0];
			$this->_core->addBreadcrumbs($title);
			

			/**
			 * Posts query
			 */
			$this->_db
				->select(array(
					"id", "title", "url", "short_text", "full_text", "category", "comments_num", "views_num", "rating",
					array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false),
					"show", "show_main", "show_category", "author"
				))
				->from(DBPREFIX . "blog_posts")
				->where("id", ">", 0);
				
			if (isset($args[0])) $this->_db->and_where("date_format(timestamp, '%Y')", "=", $args[0], false);
			if (isset($args[1])) $this->_db->and_where("date_format(timestamp, '%Y-%m')", "=", $args[0] . "-" . $args[1], false);
			if (isset($args[2])) $this->_db->and_where("date_format(timestamp, '%Y-%m-%d')", "=", $args[0] . "-" . $args[1] . "-" . $args[2], false);

			$array = $this->_db
				->order_by("id", $this->_config->get("blog", "archive.sort", "DESC"))
				->limit($pagination->getSqlLimits())
				->result_array();

			if ($array === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$noRows = (count($array) == 0);

				/**
				 * Posts make
				 */
				foreach ($array as $row)
					$this->_view->add("blog.archive.post", [
						"id" => $row["id"],
						"link" => SITE_PATH . "blog/" . $row["id"] . "-" . $row["url"],
						"title" => $row["title"],

						"author-id" => $row["author"],
						"author-login" => $this->_user->getUserLogin($row["author"]),
						"author-name" => $this->_user->getUserName($row["author"]),
						"author-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["author"]),

						"avatar-link" => $this->_user->getAvatarLinkById($row["author"]),

						"short-text" => BBCodeParser::parse($row["short_text"]),
						"full-text" => BBCodeParser::parse((empty($row["full-text"]) ? $row["short_text"] : $row["full_text"])),

						"category-id" => $row["category"],
						"category-name" => Categories::getInstance()->getName($row["category"]),
						"category-link" => (($this->_type == BACKEND) ? ADMIN_PATH . "blog/posts" : SITE_PATH . "blog") . "/cat/" . $row["category"],

						"archive-link" => SITE_PATH . "blog/archive/" . date("Y/m/d", $row["timestamp"]),
						"edit-link" => ADMIN_PATH . "blog/edit/" . $row["id"],
						"remove-link" => ADMIN_PATH . "blog/remove/" . $row["id"],

						"iso-datetime" => $this->_core->getISODateTime($row["timestamp"]),
						"date" => $this->_core->getDate($row["timestamp"]),
						"time" => $this->_core->getTime($row["timestamp"]),

						"comments-num" => $row["comments_num"],
						"views-num" => $row["views_num"],
						"rating" => $row["rating"],
						
						"show" => ($row["show"] > 0),
						"not-show" => ($row["show"] < 1),
						
						"show-main" => ($row["show_main"] > 0),
						"not-show-main" => ($row["show_main"] < 1),
						
						"show-category" => ($row["show_category"] > 0),
						"not-show-category" => ($row["show_category"] < 1),
						
						"edit" => $this->_user->hasPermission("admin.blog.posts.edit"),
						"remove" => $this->_user->hasPermission("admin.blog.posts.remove"),
					]);

				/**
				 * Formation response
				 */
				$response->code = 0;
				$response->view = "blog.archive.page";
				$response->tags = array (
					"num" => $num,
					"posts" => $this->_view->get("blog.archive.post"),
					"pagination" => $pagination
				);
			}
		}

		return $response;
	}

	/**
	 * Get archive list
	 * @return string
	 */
	public function getArchive() {
		$list = "";
		$cache_name = "archive." . $this->_lang->getLang();
		$cache = $this->_cache->get("blog", $cache_name);
		
		if ($cache === false) {
			$array = $this->_db
				->select([
					["DATE_FORMAT(timestamp,'%m-%Y')", "date", false],
					["count(`id`)", "num", false]
				])
				->from(DBPREFIX . "blog_posts")
				->group_by("date")
				->order_by("date")->desc()
				->result_array();
				
			if (is_array($array)) {
				foreach($array as $row) {
					$date = explode("-", $row["date"]);
					$link = SITE_PATH . "blog/archive/{$date[1]}/{$date[0]}";
					$date[0] = $this->_lang->get("core", "month." . intval($date[0]));
					
					$list .= $this->_view->parse("blog.tag.archive", [
						"link" => $link,
						"num" => $row["num"],
						"name" => $date[0] . " " . $date[1],
					]);
				}
				
				$this->_cache->push("blog", $cache_name, $list);
			} else {
				$list = $this->_db->getError();
			}
		} else
			$list = $cache;

		return $list;
	}
	
	private $_calendar_year = null;
	private $_calendar_month = null;
	
	public function getCalendar($month = null, $year = null) {
		if ($year == null) $year = date("Y");
		if ($month == null) $month = date("m");

		// Prev month
		if (intval($month) <= 1) {
			$pmonth = 12;
			$pyear = $year-1;
		} else {
			$pmonth = $month-1;
			$pyear = $year;
		}
		
		// Next month
		if (intval($month) >= 12) {
			$nmonth = 1;
			$nyear = $year+1;
		} else {
			$nmonth = $month+1;
			$nyear = $year;
		}
		
		if ($pmonth < 10) $pmonth = "0" . $pmonth;
		if ($nmonth < 10) $nmonth = "0" . $nmonth;
		
		// Building calendar
		$seconds_in_a_day = 60*60*24;
		$start_day= mktime (0, 0, 0, $month, 1, $year);	   
		$date_array = getdate ($start_day);
		$calendar = array ();
			
		for ($i = 0; $i < 6; $i++) {
			for ($j = 0; $j < 7; $j++) {
				$current_day = getdate ($start_day);
				if ($current_day["mon"] != $date_array["mon"]) break;
				if ($current_day["wday"]-1 == $j && $current_day["wday"] != 0) {
					$calendar[$i][$j] = $current_day["mday"];
					$start_day += $seconds_in_a_day;
				} else if ($current_day["wday"] == 0 && $j == 6) {
					$calendar[$i][$j] = $current_day["mday"];
					$start_day += $seconds_in_a_day;
				} else {
					$calendar[$i][$j] = "";
				}
			}
		}

		// Header
		$cal  = "<table id=\"calendar\" class=\"calendar\"><tr><th colspan='7' class=\"monthselect\">";
		$cal .= "<a class=\"monthlink\" onclick=\"app.blog.calendar('{$pmonth}','{$pyear}'); return false;\" href=\"" . SITE_PATH . "blog/archive/{$pyear}/{$pmonth}\" title=\"" . $this->_lang->get("core", "month." . intval($pmonth)) . " {$pyear}\">«</a> ";
		$cal .= $this->_lang->get("core", "month." . intval($month)) . " {$year} ";
		
		if (mktime(0, 0, 0, $nmonth, 0, $nyear) > time()) $cal .= "»";
		else $cal .= "<a class=\"monthlink\" onclick=\"app.blog.calendar('{$nmonth}','{$nyear}'); return false;\" href=\"" . SITE_PATH . "blog/archive/{$nyear}/{$nmonth}\" title=\"" . $this->_lang->get("core", "month." . intval($nmonth)) . " {$nyear}\">»</a> ";			
		
		$cal .= "</th></tr><tr>";
		
		// Week days
		for($i = 1; $i <= 7; $i++) {
			$sday = $this->_lang->get("core", "weekday.short." . $i);
			$fday = $this->_lang->get("core", "weekday.full." . $i);
			$cal .= "<th class=\"" . (($i < 6) ? "workday" : "weekday") . "\" title=\"{$fday}\">{$sday}</th>";
		}
		
		$cal .= "</tr>";
		$today = $this->_core->getDateInFormat(time(), "Y-m-d");
		
		// Render calendar
		foreach ($calendar as $v1) {
			$cal .= "<tr>";
			for ($i = 0; $i < count($v1); $i++) {
				$v2 = $v1[$i];
				$vday = ($v2 < 10 ? "0" : "") . $v2;
				
				// Posts count
				$posts = $this->_db
				->select("count(*)")
				->from(DBPREFIX . "blog_posts")
				->where("date_format(timestamp, '%Y-%m-%d')", "=", $year . "-" . $month . "-" . $vday, false)
				->and_where("show", "=", 1)
				->result_array();
				$posts = isset($posts[0][0]) ? $posts[0][0] : 0;
				
				$current = ($year . "-" . $month . "-" . $vday == $today) ? "-current" : "";
				$rname = ($posts > 0) ? "<a href=\"" . SITE_PATH . "blog/archive/{$year}/{$month}/{$vday}\" " . (($posts > 0) ? " class=\"weekday-active-v\"" : "") . "title=\"{$posts}\">{$v2}</a>" : $v2;
				$cal .= "<td class=\"" . (($i < 5) ? "workday" : "weekday") . " day{$current}\">{$rname}</td>";
			}
			$cal .= "</tr>";
		}
		
		$cal .= "</table>";
		return $cal;
	}
}