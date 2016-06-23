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
use harmony\http\HTTP;
use Registry;
use Response;
use NotFoundException;
use Exception;

use harmony\pagination\Pagination;
use harmony\parsers\BBCodeParser;
use harmony\strings\StringFilters;
use harmony\strings\Strings;

use erusev\parsedown\Parsedown;

class Posts extends AppModel {
	/**
	 * @var string Side type
	 */
	private $_type;

	/**
	 * @var string Posts editor
	 */
	private $_editor;

	/**
	 * @var array Add post tags
	 */
	private $_addTags = array (
		"category" => 0,

		"tags" => array (
			"title" => "",
			"url" => "",
			"text" => "",

			"tags" => "",
			"lang" => "",
			
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
		$this->_editor = $this->_config->get("blog", "posts.editor", "BBCode");
		$this->_addTags["tags"]["lang"] = $this->_lang->getLang();
	}

	/**
	 * Get post text
	 * @param string $text Original post text
	 * @param bool $parse = true Parse text
	 * @param bool $short = false Get short text?
	 * @return string
	 * @throws \Exception
	 */
	public static function getText($text, $parse = true, $short = false) {
		// Return text if not need parse
		if (!$parse) {
			if ($short) {
				$text = explode("[separator]", $text)[0];
			} else {
				$text = str_replace("[separator]", "", $text);
			}

			return $text;
		} else {
			$editor = Registry::getInstance()->get("Config")->get("blog", "posts.editor", "BBCode");

			switch ($editor) {
				case "Markdown":
					$parsedown = new Parsedown();
					return $parsedown->parse($text);

				case "BBCode":
					return BBCodeParser::parse($text);

				default:
					return $text;
			}
		}
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
	 * Make a links for tags string
	 * @param string $tags Tags string
	 * @return string
     */
	public function makeTagsLinks($tags) {
		if ($tags == "") return "";

		$tarray = explode(", ", $tags);

		foreach ($tarray as &$tag) {
			$tag = "<a href=\"" . SITE_PATH . "blog/tag/" . urlencode($tag) . "\">{$tag}</a>";
		}

		return implode(", ", $tarray);
	}

	/**
	 * Get User posts count by User ID
	 * @param int $uid User ID
	 * @return int
     */
	public function getUserPostsCount($uid) {
		$count = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_posts")
			->where("author", "=", intval($uid))
			->result_array();

		return isset($count[0][0]) ? $count[0][0] : 0;
	}

	/**
	 * Get posts by category and page
	 * @param int $category Posts category (NULL, for all categories)
	 * @param int $page Posts page
	 * @param string $tag = null Tag name
	 * @param bool $show = true Show allowed to display posts?
	 * @return Response
	 */
	public function get($category, $page, $tag = null, $show = true) {
		$this->_core->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog");
		
		if (!$this->_user->hasPermission("blog.posts.list")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$response = new Response();
		
		$category = ($category === null) ? null : intval($category);
		$page = intval($page);
		$show = (bool)($show);

		// Number query
		if ($this->_type == BACKEND) {
			$this->_core->addBreadcrumbs($this->_lang->get("blog", "list.moduleName"), "blog/posts");
		}

		$this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_posts")
			->where("id", ">", 0);
			
		if ($show) $this->_db->and_where("show", ">", 0);

		// Category
		if ($category !== null) {
			$this->_db->and_where("category", "=", $category);
			if ($show) $this->_db->and_where("show_category", ">", 0);
		} else {
			if ($show) $this->_db ->and_where("show_main", ">", 0);
		}

		// Only local language
		if ($this->_config->get("blog", "posts.only_local_language", false)) {
			$this->_db->and_where("lang", "=", $this->_lang->getLang());
		}

		// Tag
		if ($tag !== null) {
			$this->_db->and_where("tags", "LIKE", "%{$tag}%");
			$this->_core->addBreadcrumbs($tag, "blog/tag/" . $tag);
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
					"id", "title", "url", "text", "text_parsed", "category", "comments_num", "views_num", "rating",
					"tags", "lang",
					array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false),
					"show", "show_main", "show_category", "author"
				))
				->from(DBPREFIX . "blog_posts")
				->where("id", ">", 0);
				
			if ($show) $this->_db->and_where("show", ">", 0);

			// Category
			if ($category !== null) {
				$this->_db->and_where("category", "=", $category);
				if ($show) $this->_db->and_where("show_category", ">", 0);
			} else {
				if ($show) $this->_db ->and_where("show_main", ">", 0);
			}

			// Only local language
			if ($this->_config->get("blog", "posts.only_local_language", false)) {
				$this->_db->and_where("lang", "=", $this->_lang->getLang());
			}

			// Tag
			if ($tag !== null) {
				$this->_db->and_where("tags", "LIKE", "%{$tag}%");
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
				// Posts make
				$rows = [];

				foreach ($array as $row) {
					// Rating Active
					$ratingPlusActive = false;
					$ratingMinusActive = false;

					if ($this->_config->get("blog", "posts.rating_active", true)) {
						$result = $this->_db
							->select(array(
								"id", "type"
							))
							->from(DBPREFIX . "blog_rating")
							->where("post", "=", $row["id"])
							->and_where("user", "=", $this->_user->get("id"))
							->result_array();

						if (isset($result[0])) {
							if ($result[0]["type"] == 0) $ratingMinusActive = true;
							if ($result[0]["type"] == 1) $ratingPlusActive = true;
						}
					}

					// Read
					$read = false;

					if ($this->_config->get("blog", "posts.read_mark", true)) {
						$query = $this->_db
							->select("count(*)")
							->from(DBPREFIX . "blog_views")
							->where("post", "=", $row["id"])
							->and_where("user", "=", $this->_user->get("id"))
							->result_array();

						$read = (isset($query[0][0]) && $query[0][0] > 0);
					}

					// Rows array
					$rows[] = [
						"id" => $row["id"],
						"link" => SITE_PATH . "blog/" . $row["id"] . "-" . $row["url"],
						"title" => $row["title"],

						"author-id" => $row["author"],
						"author-login" => $this->_user->getUserLogin($row["author"]),
						"author-name" => $this->_user->getUserName($row["author"]),
						"author-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["author"]),
						"author-avatar-link" => $this->_user->getAvatarLinkById($row["author"]),

						"full-text" => Posts::getText($row["text_parsed"], false),
						"short-text" => Posts::getText($row["text_parsed"], false, true),
						
						"tags" => $this->makeTagsLinks($row["tags"]),
						"lang" => $row["lang"],
						"language" => $this->_lang->getLangName($row["lang"]),

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
						"read" => $read,

						"rating" => $row["rating"],
						"rating-minus-active" => $ratingMinusActive,
						"rating-plus-active" => $ratingPlusActive,

						"show" => ($row["show"] > 0),
						"not-show" => ($row["show"] < 1),

						"show-main" => ($row["show_main"] > 0),
						"not-show-main" => ($row["show_main"] < 1),

						"show-category" => ($row["show_category"] > 0),
						"not-show-category" => ($row["show_category"] < 1),

						"edit" => $this->_user->hasPermission("admin.blog.posts.edit"),
						"remove" => $this->_user->hasPermission("admin.blog.posts.remove"),
					];
				}

				// Add category breadcrumbs if exist
				if ($category !== null)
					$this->_core->addBreadcrumbs(Categories::getInstance()->getName($category), "blog/cat/" . $category);

				// Formation response
				$response->code = 0;
				$response->view = "blog.list";
				$response->tags = array (
					"num" => $num,
					"rows" => $rows,
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
	 * @throws NotFoundException
	 */
	public function page($id, $commentsPage, $comments_model) {
		$this->_core->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog");
		
		if (!$this->_user->hasPermission("blog.posts.read")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$response = new Response();

		$id = intval($id);
		$commentsPage = intval($commentsPage);

		// Get post
		$array = $this->_db
			->select(array(
				"id", "title", "url", "text", "text_parsed", "category", "tags", "lang",
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

			// Update views
			if ($this->_config->get("blog", "posts.advanced_views", true)) {
				$this->_db
					->select(["id"])
					->from(DBPREFIX . "blog_views")
					->where("post", "=", $id);

				if ($this->_user->isLogged())
					$this->_db->and_where("user", "=", $this->_user->get("id"));
				else
					$this->_db->and_where("ip", "=", HTTP::getIp());

				$views = $this->_db
					->and_where("UNIX_TIMESTAMP(`timestamp`)", ">", time() - 86400, false)
					->result_array();

				if ($views === false) {
					return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
				} elseif (count($views) < 1) {
					// Update post views
					$this->_db
						->update(DBPREFIX . "blog_posts")
						->set(array(
							"views_num" => array("views_num", "+", 1, false)
						))
						->where("id", "=", $id)
						->and_where("show", "=", 1)
						->result();

					// Add view to database
					$this->_db
						->insert_into(DBPREFIX . "blog_views")
						->values(array (
							"post" => $id,
							"user" => $this->_user->isLogged() ? $this->_user->get("id") : 0,
							"ip" => HTTP::getIp()
						))
						->result();

					// Update row counter
					$row["views_num"]++;
				}
			} else {
				$this->_db
					->update(DBPREFIX . "blog_posts")
					->set(array(
						"views_num" => array("views_num", "+", 1, false)
					))
					->where("id", "=", $id)
					->and_where("show", "=", 1)
					->result();
			}

			// Add breadcrumbs
			$this->_core
				->addBreadcrumbs(Categories::getInstance()->getName($row["category"]), "blog/cat/" . $row["category"])
				->addBreadcrumbs($row["title"], SITE_PATH . "blog/" . $row["id"]);

			// Posts switching links
			$previous = "";
			$previousTitle = "";
			$next = "";
			$nextTitle = "";

			if ($this->_config->get("blog", "posts.posts_switching", true)) {
				// Previous post link
				$query = $this->_db
					->select(array(
						"id", "url", "title"
					))
					->from(DBPREFIX . "blog_posts")
					->where("id", "<", $id)
					->and_where("show", "=", "1")
					->order_by("id")->desc()
					->query("LIMIT 1")
					->result_array();

				if ($query !== false && count($query) == 1) {
					$previous = PATH . "blog/" . $query[0]["id"] . "-" . $query[0]["url"];
					$previousTitle = $query[0]["title"];
				}

				// Next post link
				$query = $this->_db
					->select(array(
						"id", "url", "title"
					))
					->from(DBPREFIX . "blog_posts")
					->where("id", ">", $id)
					->and_where("show", "=", "1")
					->order_by("id")->asc()
					->query("LIMIT 1")
					->result_array();

				if ($query !== false && count($query) == 1) {
					$next = PATH . "blog/" . $query[0]["id"] . "-" . $query[0]["url"];
					$nextTitle = $query[0]["title"];
				}
			}

			// Rating Active
			$ratingPlusActive = false;
			$ratingMinusActive = false;

			if ($this->_config->get("blog", "posts.rating_active", true)) {
				$result = $this->_db
					->select(array(
						"id", "type"
					))
					->from(DBPREFIX . "blog_rating")
					->where("post", "=", $id)
					->and_where("user", "=", $this->_user->get("id"))
					->result_array();

				if (isset($result[0])) {
					if ($result[0]["type"] == 0) $ratingMinusActive = true;
					if ($result[0]["type"] == 1) $ratingPlusActive = true;
				}
			}

			// Comments
			$comments = $comments_model->get($id, $commentsPage, $row["allow_comments"], $row["url"]);

			if ($comments->code == 0)
				$comments = $this->_view->parse($comments->view, $comments->tags);
			else
				$comments = $this->_view->getAlert($comments->type, $comments->message);

			$response->code = 0;
			$response->view = "blog.post";

			// Add tags
			$response->tags = array(
				"id" => $row["id"],
				"link" => SITE_PATH . "blog/" . $row["id"] . "-" . $row["url"],
				"title" => $row["title"],

				"full-text" => Posts::getText($row["text_parsed"], false),
				"short-text" => Posts::getText($row["text_parsed"], false, true),

				"tags" => $this->makeTagsLinks($row["tags"]),
				"lang" => $row["lang"],
				"language" => $this->_lang->getLangName($row["lang"]),

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
				"author-avatar-link" => $this->_user->getAvatarLinkById($row["author"]),

				"comments-num" => $row["comments_num"],
				"views-num" => $row["views_num"],

				"rating" => $row["rating"],
				"rating-minus-active" => $ratingMinusActive,
				"rating-plus-active" => $ratingPlusActive,

				"comments" => $comments,

				"previous-link" => $previous,
				"previous-title" => $previousTitle,
				"next-link" => $next,
				"next-title" => $nextTitle,
				
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
	 * @param string $text Post text
	 * @param string $tags Tags
	 * @param string $lang Post language
	 * @param bool $allowComments Allow comments?
	 * @param bool $show Show posts?
	 * @param bool $showMain Show posts on main?
	 * @param bool $showCaregory Show posts on category?
	 * @return Response
	 */
	public function edit($postId, $title, $url, $category, $text, $tags, $lang, $allowComments, $show, $showMain, $showCaregory) {
		if (!$this->_user->hasPermission("blog.posts.edit")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$this->_editQuery = true;

		return $this->add($title, $url, $category, $text, $tags, $lang, $allowComments, $show, $showMain, $showCaregory, $postId);
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

		$response->view = "blog.edit";

		if ($this->exists($postId, false)) {
			if (!$this->_editQuery) {
				$row = $this->_db
					->select(array(
						"id", "url", "title", "text", "text_parsed", "category",
						"tags", "lang", "author",
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
							"text" => $row["text"],

							"tags" => $row["tags"],
							"lang" => $row["lang"],

							"author-id" => $row["author"],
							"author-login" => $this->_user->getUserLogin($row["author"]),
							"author-name" => $this->_user->getUserName($row["author"]),
							
							"allow-comments" => ($row["allow_comments"] > 0),
							"show" => ($row["show"] > 0),
							"show-main" => ($row["show_main"] > 0),
							"show-category" => ($row["show_category"] > 0)
						)
					);
				}
			}

			// Categories
			$categories = [];
			foreach (Categories::getInstance()->get() as $id => $row)
				$categories[] = [
					"id" => $id,
					"name" => $row["name"],
					"num" => $row["num"],
					"current" => ($this->_addTags["category"] == $id)
				];

			// Languages
			$langs = [];
			foreach ($this->_lang->getLangs() as $lang => $name)
				$langs[] = [
					"id" => $lang,
					"name" => $name,
					"current" => ($this->_addTags["tags"]["lang"] == $lang)
				];

			$response->tags = array_merge($this->_addTags["tags"], array (
				"editor" => $this->_editor,

				"categories" => $categories,
				"langs" => $langs,

				"list-link" => ADMIN_PATH . "blog/list",
				"remove-link" => ADMIN_PATH . "blog/remove/" . $postId
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
	 * @param string $text Post text
	 * @param string $tags Tags
	 * @param string $lang Post language
	 * @param bool $allowComments Allow comments?
	 * @param bool $show Show posts?
	 * @param bool $showMain Show posts on main?
	 * @param bool $showCategory Show posts in category page?
	 * @param int $postId = null Edit post ID
	 * @return Response
	 */
	public function add($title, $url, $category, $text, $tags, $lang, $allowComments, $show, $showMain, $showCategory, $postId = null) {
		$edit = ($postId !== null);
		if (!$this->_user->hasPermission("blog.posts.add") && $edit) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}
		
		$title = StringFilters::filterHtmlTags($title);
		$url = StringFilters::filterForUrl(empty($url) ? $title : $url);
		$category = intval($category);
		$text = ($this->_editor == "HTML") ? $text : StringFilters::filterHtmlTags($text);
		$tags = StringFilters::filterTagsString($tags);
		$lang = StringFilters::filterHtmlTags($lang);
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
				"text" => $text,

				"tags" => $tags,
				"lang" => $lang,
				
				"allow-comments" => $allowComments,
				"show" => $show,
				"show-main" => $showMain,
				"show-category" => $showCategory
			)
		);

		$response = new Response();

		$author = $this->_user->get("id");

		if ($edit) {
			$query = $this->_db
				->select(["author"])
				->from(DBPREFIX . "blog_posts")
				->where("id", "=", intval($postId))
				->and_where("show", "=", 1)
				->result_array();
		
			if (!isset($query[0])) {
				return new Response(2, "danger", $this->_lang->get("blog", "edit.notExists"));
			}

			$author = $query[0]["author"];
		}

		if (empty($title) || empty($text)) {
			$response->code = 3;
			$response->type = "warning";
			$response->message = $this->_lang->get("core", "emptyFields");
		} else {
			$values = array(
				"title" => $title,
				"url" => $url,
				"category" => $category,
				"text" => $text,
				"text_parsed" => Posts::getText($text),

				"tags" => $tags,
				"lang" => $lang,

				"allow_comments" => $allowComments ? 1 : 0,
				"show" => $show ? 1 : 0,
				"show_main" => $showMain ? 1 : 0,
				"show_category" => $showCategory ? 1 : 0,
				"author" => $author
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
		$response->view = "blog.add";

		// Categories
		$categories = [];
		foreach (Categories::getInstance()->get() as $id => $row)
			$categories[] = [
				"id" => $id,
				"name" => $row["name"],
				"num" => $row["num"],
				"current" => ($this->_addTags["category"] == $id)
			];

		// Languages
		$langs = [];
		foreach ($this->_lang->getLangs() as $lang => $name)
			$langs[] = [
				"id" => $lang,
				"name" => $name,
				"current" => ($this->_addTags["tags"]["lang"] == $lang)
			];

		$response->tags = array_merge($this->_addTags["tags"], array (
			"editor" => $this->_editor,

			"categories" => $categories,
			"langs" => $langs
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
			$response->view = "blog.remove";

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

		// Only local language
		if ($this->_config->get("blog", "posts.only_local_language", false)) {
			$this->_db->and_where("lang", "=", $this->_lang->getLang());
		}
			
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
					"id", "title", "url", "text", "text_parsed", "category", "comments_num", "views_num", "rating",
					"tags", "lang",
					array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false),
					"show", "show_main", "show_category", "author"
				))
				->from(DBPREFIX . "blog_posts")
				->where("show", ">", 0);

			// Only local language
			if ($this->_config->get("blog", "posts.only_local_language", false)) {
				$this->_db->and_where("lang", "=", $this->_lang->getLang());
			}
				
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
				// Posts list
				$rows = [];
				
				foreach ($array as $row) {
					// Rating Active
					$ratingPlusActive = false;
					$ratingMinusActive = false;

					if ($this->_config->get("blog", "posts.rating_active", true)) {
						$result = $this->_db
							->select(array(
								"id", "type"
							))
							->from(DBPREFIX . "blog_rating")
							->where("post", "=", $row["id"])
							->and_where("user", "=", $this->_user->get("id"))
							->result_array();

						if (isset($result[0])) {
							if ($result[0]["type"] == 0) $ratingMinusActive = true;
							if ($result[0]["type"] == 1) $ratingPlusActive = true;
						}
					}

					$rows[] = [
						"id" => $row["id"],
						"link" => SITE_PATH . "blog/" . $row["id"] . "-" . $row["url"],
						"title" => $row["title"],

						"author-id" => $row["author"],
						"author-login" => $this->_user->getUserLogin($row["author"]),
						"author-name" => $this->_user->getUserName($row["author"]),
						"author-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["author"]),
						"author-avatar-link" => $this->_user->getAvatarLinkById($row["author"]),

						"full-text" => Posts::getText($row["text_parsed"], false),
						"short-text" => Posts::getText($row["text_parsed"], false, true),

						"tags" => $this->makeTagsLinks($row["tags"]),
						"lang" => $row["lang"],
						"language" => $this->_lang->getLangName($row["lang"]),

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
						"rating-minus-active" => $ratingMinusActive,
						"rating-plus-active" => $ratingPlusActive,

						"show" => ($row["show"] > 0),
						"not-show" => ($row["show"] < 1),

						"show-main" => ($row["show_main"] > 0),
						"not-show-main" => ($row["show_main"] < 1),

						"show-category" => ($row["show_category"] > 0),
						"not-show-category" => ($row["show_category"] < 1),

						"edit" => $this->_user->hasPermission("admin.blog.posts.edit"),
						"remove" => $this->_user->hasPermission("admin.blog.posts.remove"),
					];
				}

				// Formation response
				$response->code = 0;
				$response->view = "blog.archive";
				$response->tags = array (
					"num" => $num,
					"rows" => $rows,
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
	public function getPopular() {
		$list = "";
		$array = $this->_db
			->select(["id", "title", "rating"])
			->from(DBPREFIX . "blog_posts")
			->where("show", "=", 1)
			->order_by("rating")->desc()
			->limit([0, $this->_config->get("blog", "popular.count", 10)])
			->result_array();
		
		if (is_array($array)) {
			foreach($array as $row) {
				$link = SITE_PATH . "blog/" . $row["id"];
				$list .= $this->_view->parse("blog.tag.popular", [
					"link" => $link,
					"id" => $row["id"],
					"rating" => $row["rating"],
					"title" => $row["title"]
				]);
			}
		} else {
			$list = $this->_db->getError();
		}

		return $list;
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
			$this->_db
				->select([
					["DATE_FORMAT(timestamp,'%m-%Y')", "date", false],
					["count(`id`)", "num", false]
				])
				->from(DBPREFIX . "blog_posts")
				->where("show", "=", 1);

			// Only local language
			if ($this->_config->get("blog", "posts.only_local_language", false)) {
				$this->_db->and_where("lang", "=", $this->_lang->getLang());
			}

			$array = $this->_db
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

	/**
	 * @var null|int Calendar year active
	 */
	private $_calendar_year = null;

	/**
	 * @var null|int Calendar month active
	 */
	private $_calendar_month = null;

	/**
	 * Get calendar code
	 * @param int $month = null Month number
	 * @param int $year = null Year number
	 * @return string
	 */
	public function getCalendar($month = null, $year = null) {
		if ($year == null && $month == null) {
			$year = $this->_calendar_year;
			$month = $this->_calendar_month;
		}

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

		// Header
		$cal  = "<table id=\"calendar\" class=\"calendar\"><tr><th colspan='7' class=\"monthselect\">";
		$cal .= "<a class=\"monthlink\" onclick=\"app.blog.calendar('{$pmonth}','{$pyear}'); return false;\" href=\"" . SITE_PATH . "blog/archive/{$pyear}/{$pmonth}\" title=\"" . $this->_lang->get("core", "month." . intval($pmonth)) . " {$pyear}\">«</a> ";
		$cal .= $this->_lang->get("core", "month." . intval($month)) . " {$year} ";

		if (mktime(0, 0, 0, $nmonth, 0, $nyear) > time()) $cal .= "»";
		else $cal .= "<a class=\"monthlink\" onclick=\"app.blog.calendar('{$nmonth}','{$nyear}'); return false;\" href=\"" . SITE_PATH . "blog/archive/{$nyear}/{$nmonth}\" title=\"" . $this->_lang->get("core", "month." . intval($nmonth)) . " {$nyear}\">»</a> ";

		$cal .= "</th></tr>";

		// Building calendar
		$content = $this->_cache->get("blog", "calendar_" . $nyear . "-" . $nmonth);
		if ($content === false) {
			$seconds_in_a_day = 60 * 60 * 24;
			$start_day = mktime(0, 0, 0, $month, 1, $year);
			$date_array = getdate($start_day);
			$calendar = array();

			for ($i = 0; $i < 6; $i++) {
				for ($j = 0; $j < 7; $j++) {
					$current_day = getdate($start_day);
					if ($current_day["mon"] != $date_array["mon"]) break;
					if ($current_day["wday"] - 1 == $j && $current_day["wday"] != 0) {
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

			$content = "<tr>";

			// Week days
			for ($i = 1; $i <= 7; $i++) {
				$sday = $this->_lang->get("core", "weekday.short." . $i);
				$fday = $this->_lang->get("core", "weekday.full." . $i);
				$content .= "<th class=\"" . (($i < 6) ? "workday" : "weekday") . "\" title=\"{$fday}\">{$sday}</th>";
			}

			$content .= "</tr>";
			$today = $this->_core->getDateInFormat(time(), "Y-m-d");

			// Render calendar
			foreach ($calendar as $v1) {
				$content .= "<tr>";
				for ($i = 0; $i < count($v1); $i++) {
					$v2 = $v1[$i];
					$vday = ($v2 < 10 ? "0" : "") . $v2;

					// Posts count
					$this->_db
						->select("count(*)")
						->from(DBPREFIX . "blog_posts")
						->where("date_format(timestamp, '%Y-%m-%d')", "=", $year . "-" . $month . "-" . $vday, false)
						->and_where("show", "=", 1);

					// Only local language
					if ($this->_config->get("blog", "posts.only_local_language", false)) {
						$this->_db->and_where("lang", "=", $this->_lang->getLang());
					}

					$posts = $this->_db->result_array();
					$posts = isset($posts[0][0]) ? $posts[0][0] : 0;

					$current = ($year . "-" . $month . "-" . $vday == $today) ? "-current" : "";
					$rname = ($posts > 0) ? "<a href=\"" . SITE_PATH . "blog/archive/{$year}/{$month}/{$vday}\" " . (($posts > 0) ? " class=\"weekday-active-v\"" : "") . "title=\"{$posts}\">{$v2}</a>" : $v2;
					$content .= "<td class=\"" . (($i < 5) ? "workday" : "weekday") . " day{$current}\">{$rname}</td>";
				}
				$content .= "</tr>";
			}

			$this->_cache->push("blog", "calendar_" . $nyear . "-" . $nmonth, $content); // Save cache
		}
		
		$cal .= $content . "</table>";
		return $cal;
	}

	/**
	 * Get tags array
	 * @return array
	 * @throws Exception
	 */
	public function getTagsArray() {
		$cache = $this->_cache->get("blog", "tags");
		if ($cache !== false) return $cache;

		$array = $this->_db
			->select(["tags"])
			->from(DBPREFIX . "blog_posts")
			->where("show", ">", 0)
			->and_where("tags", "!=", "")
			->result_array();

		if ($array == false) {
			throw new Exception("Error get tags: " . $this->_db->getError());
		}

		$tags = [];

		// Making tags array
		foreach ($array as $row) {
			foreach (explode(", ", $row["tags"]) as $tag) {
				if (isset($tags[$tag])) {
					$tags[$tag]["count"]++;
				} else {
					$tags[$tag]= ["name" => $tag, "count" => 1];
				}
			}
		}

		// Sorting tags by name
		usort($tags, function ($a, $b) {
			if ($a["name"] == $b["name"]) {
				return 0;
			} else {
				return strcasecmp($a["name"], $b["name"]);
			}
		});

		// Minumum and maximum value
		$min = 0; $max = 0;
		foreach ($tags as $tag) {
			$c = $tag["count"];
			if ($min == 0 || $c < $min) $min = $c;
			if ($max < $c) $max = $c;
		}
		$range = $max-$min;

		// Adding size item
		$sizes = ["xsmall", "small", "medium", "large", "xlarge"];
		foreach ($tags as &$tag) {
			$tag["size"] = $sizes[intval(($tag["count"] - $min) / ($range) * 4)];
		}

		$this->_cache->push("blog", "tags", $tags);
		return $tags;
	}

	/**
	 * Get tags cloud
	 * @return string
	 */
	public function getTagsCloud() {
		$cache = $this->_cache->get("blog", "tagscloud");
		if ($cache !== false) return $cache;

		$tags = $this->getTagsArray();

		// Sorting tags by size
		usort($tags, function ($a, $b) {
			if ($a["size"] == $b["size"]) {
				return 0;
			} else {
				return strcasecmp($a["size"], $b["size"]);
			}
		});

		// Splice array
		array_splice($tags, 40);

		// Sorting tags by name
		usort($tags, function ($a, $b) {
			if ($a["name"] == $b["name"]) {
				return 0;
			} else {
				return strcasecmp($a["name"], $b["name"]);
			}
		});

		// Minumum and maximum value
		$min = 0; $max = 0;
		foreach ($tags as $tag) {
			$c = $tag["count"];
			if ($min == 0 || $c < $min) $min = $c;
			if ($max < $c) $max = $c;
		}

		$range = $max-$min;
		$sizes = ["xsmall", "small", "medium", "large", "xlarge"];

		foreach ($tags as $tag) {
			$tag["size"] = $sizes[intval(($tag["count"] - $min) / ($range) * 4)];
			$tag["link"] = SITE_PATH . "blog/tag/" . urlencode($tag["name"]);
			$this->_view->add("blog.tag.tagscloud", $tag);
		}

		$result = $this->_view->get("blog.tag.tagscloud");
		$this->_cache->push("blog", "tagscloud", $result);

		return $result;
	}

	/**
	 * Search posts by query and page
	 * @param string $query Search query
	 * @param string $page Search posts page
	 * @return Response
	 */
	public function searchPosts($query, $page) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog")
			->addBreadcrumbs($this->_lang->get("blog", "search.moduleName"), "blog/search")
			->addBreadcrumbs($this->_lang->get("blog", "search.posts"), "blog/search");

		if (!$this->_user->hasPermission("blog.posts.search"))
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));

		$response = new Response();
		$query = StringFilters::filterHtmlTags($this->_db->safe($query));
		$page = intval($page);

		// Minumum query length
		$minLength = $this->_config->get("blog", "search.posts.queryMinLength", 3);

		if (Strings::length($query) >= $minLength) {
			$this->_db
				->select("count(*)")
				->from(DBPREFIX . "blog_posts")
				->where("show", "=", 1);

			// Only local language
			if ($this->_config->get("blog", "posts.only_local_language", false)) {
				$this->_db->and_where("lang", "=", $this->_lang->getLang());
			}

			$num = $this->_db
				->query("AND (`title` LIKE '%{$this->_db->safe($query)}%'")
				->or_where("title", "LIKE", "")
				->or_where("text", "LIKE", "%{$query}%")
				->or_where("tags", "LIKE", "%{$query}%")
				->query(")")
				->result_array();

			if ($num === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);

				return $response;
			} else {
				$paginationPrefix = SITE_PATH . "blog/search/{$query}/page/";
				$num = $num[0][0];
				$pagination = new Pagination($num, $page, $paginationPrefix, $this->_config->get("blog", "search.customPagination", array()));

				/**
				 * Posts query
				 */
				$this->_db
					->select(array(
						"id", "title", "url", "text", "text_parsed", "category", "comments_num", "views_num", "rating",
						"tags", "lang", array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false),
						"show", "author"
					))
					->from(DBPREFIX . "blog_posts")
					->where("show", "=", 1);

				// Only local language
				if ($this->_config->get("blog", "posts.only_local_language", false)) {
					$this->_db->and_where("lang", "=", $this->_lang->getLang());
				}

				$array = $this->_db
					->query("AND (`title` LIKE '%{$this->_db->safe($query)}%'")
					->or_where("title", "LIKE", "")
					->or_where("text", "LIKE", "%{$query}%")
					->or_where("tags", "LIKE", "%{$query}%")
					->query(")")
					->order_by("id", $this->_config->get("blog", "search.sort", "DESC"))
					->limit($pagination->getSqlLimits())
					->result_array();

				if ($array === false) {
					$response->code = 1;
					$response->type = "danger";
					$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
				} else {
					// Posts make
					$rows = [];
					foreach ($array as $row) {
						// Rating Active
						$ratingPlusActive = false;
						$ratingMinusActive = false;

						if ($this->_config->get("blog", "posts.rating_active", true)) {
							$result = $this->_db
								->select(array(
									"id", "type"
								))
								->from(DBPREFIX . "blog_rating")
								->where("post", "=", $row["id"])
								->and_where("user", "=", $this->_user->get("id"))
								->result_array();

							if (isset($result[0])) {
								if ($result[0]["type"] == 0) $ratingMinusActive = true;
								if ($result[0]["type"] == 1) $ratingPlusActive = true;
							}
						}

						$rows[] = [
							"id" => $row["id"],
							"link" => SITE_PATH . "blog/" . $row["id"] . "-" . $row["url"],
							"title" => $row["title"],

							"author-id" => $row["author"],
							"author-login" => $this->_user->getUserLogin($row["author"]),
							"author-name" => $this->_user->getUserName($row["author"]),
							"author-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["author"]),
							"author-avatar-link" => $this->_user->getAvatarLinkById($row["author"]),

							"full-text" => Posts::getText($row["text_parsed"], false),
							"short-text" => Posts::getText($row["text_parsed"], false, true),

							"tags" => $this->makeTagsLinks($row["tags"]),
							"lang" => $row["lang"],
							"language" => $this->_lang->getLangName($row["lang"]),

							"category-id" => $row["category"],
							"category-name" => Categories::getInstance()->getName($row["category"]),
							"category-link" => SITE_PATH . "blog/cat/" . $row["category"],

							"archive-link" => SITE_PATH . "blog/archive/" . date("Y/m/d", $row["timestamp"]),
							"edit-link" => ADMIN_PATH . "blog/edit/" . $row["id"],
							"remove-link" => ADMIN_PATH . "blog/remove/" . $row["id"],

							"iso-datetime" => $this->_core->getISODateTime($row["timestamp"]),
							"date" => $this->_core->getDate($row["timestamp"]),
							"time" => $this->_core->getTime($row["timestamp"]),

							"comments-num" => $row["comments_num"],
							"views-num" => $row["views_num"],

							"rating" => $row["rating"],
							"rating-minus-active" => $ratingMinusActive,
							"rating-plus-active" => $ratingPlusActive,

							"show" => ($row["show"] > 0),
							"not-show" => ($row["show"] < 1),

							"edit" => $this->_user->hasPermission("admin.blog.posts.edit"),
							"remove" => $this->_user->hasPermission("admin.blog.posts.remove")
						];
					}

					/**
					 * Formation response
					 */
					$response->code = 0;
					$response->view = "blog.search";
					$response->tags = array(
						"query" => $query,
						"num" => $num,
						"rows" => $rows,
						"pagination" => $pagination
					);
				}
			}
		} else {
			if (!$query)
				$response->code = 0;
			else {
				$response->code = 2;
				$response->type = "danger";
				$response->message = $this->_lang->get("blog", "search.posts.shortQuery") . " - " . $minLength;
			}

			$response->view = "blog.search";
			$response->tags = array(
				"query" => $query
			);
		}

		return $response;
	}
}
