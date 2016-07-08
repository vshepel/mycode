<?php
/**
 * Blog Moderation Posts Model
 * @copyright Copyright (C) 2016 Evgeny Zakharenko <evgenyz99@yandex.com>. All rights reserved.
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

use Exception;
use AppModel;
use Response;
use harmony\pagination\Pagination;
use harmony\strings\StringFilters;

class PostsModeration extends AppModel {
	private $_addTags = [
		"title" => "",
		"url" => "",
		"category" => 0,
		"text" => "",
		"image-link" => "",
		"tags" => "",
		"lang" => ""
	];

	/**
	 * PostsModeration constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->_addTags["lang"] = $this->_lang->getLang();
	}

	/**
	 * Get posts for moderation
	 * @param $category
	 * @param $page
	 * @return Response
	 * @throws Exception
	 */
	public function get($category, $page) {
		$response = new Response();

		$category = ($category === null) ? null : intval($category);
		$page = intval($page);

		$this->_core
			->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "story")
			->addBreadcrumbs($this->_lang->get("blog", "moderation.moduleName"), "story/modeartion");

		$this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_posts_moderation")
			->where("id", ">", 0);

		if ($category !== null) {
            $this->_db->and_where("category", "=", $category);
        }

		$num = $this->_db->result_array();

		if ($num === false) {
            return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		} else {
			$paginationPrefix = ADMIN_PATH . "blog/moderation/" . (($category === null) ? "page/" : "cat/" . $category . "/page/");
			$num = $num[0][0];
			$pagination = new Pagination($num, $page, $paginationPrefix, $this->_config->get("blog", "moderation.list.customPagination", []));

			$this->_db
				->select(array(
					"id", "title", "text", "image_link", "category", "author",
					array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false),
				))
				->from(DBPREFIX . "blog_posts_moderation")
				->where("id", ">", 0);


			if ($category !== null)
				$this->_db->and_where("category", "=", $category);

			$array = $this->_db
				->order_by("id")->asc()
				->limit($pagination->getSqlLimits())
				->result_array();

			if ($array === false) {
				return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
			}
			
			$rows = [];

			foreach ($array as $row) {
                $rows[] = [
                    "id" => $row["id"],
                    "title" => $row["title"],

                    "author-id" => $row["author"],
                    "author-login" => $this->_user->getUserLogin($row["author"]),
                    "author-name" => $this->_user->getUser($row["author"], "firstname"),
                    "author-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["author"]),
                    "author-avatar-link" => $this->_user->getAvatarLinkById($row["author"]),

                    "text" => Posts::getText($row["text"]),
					"image-link" => $row["image_link"],

                    "category-id" => $row["category"],
                    "category-name" => Categories::getInstance()->getName($row["category"]),
                    "category-link" => ADMIN_PATH . "blog/moderation/cat/" . $row["category"],

                    "good-link" => ADMIN_PATH . "blog/moderation/good/" . $row["id"],
                    "bad-link" => ADMIN_PATH . "blog/moderation/bad/" . $row["id"],

                    "iso-datetime" => $this->_core->getISODateTime($row["timestamp"]),
                    "date" => $this->_core->getDate($row["timestamp"]),
                    "time" => $this->_core->getTime($row["timestamp"]),
                ];
            }

			if ($category !== null) {
				$this->_core->addBreadcrumbs(Categories::getInstance()->getName($category), "blog/moderation/cat/" . $category);
			}

			$response->code = 0;
			$response->view = "blog.moderation";
			$response->tags = [
				"num" => $num,
				"rows" => $rows,
				"pagination" => $pagination,
			];
		}

		return $response;
	}

	/**
	 * Good post
	 * @param int $id Post Moderation ID
	 * @return bool|int Added post ID (false if no post)
	 * @throws Exception
	 */
	public function good($id) {
		// Get post data
		$post = $this->_db
			->select([
				"id", "url", "title", "category", "text", "image_link", "tags", "lang", "author",
				array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false),
			])
			->from(DBPREFIX . "blog_posts_moderation")
			->where("id", "=", intval($id))
			->result_array();

		if (isset($post[0])) {
			$row = $post[0];

			$result = $this->_db
				->insert_into(DBPREFIX . "blog_posts")
				->values(array(
					"title" => $row["title"],
					"url" => $row["url"],
					"category" => $row["category"],
					"text" => $row["text"],
					"text_parsed" => Posts::getText($row["text"]),

					"image_link" => $row["image_link"],
					"tags" => $row["tags"],
					"lang" => $row["lang"],

					"allow_comments" => 1,
					"show" => 0,
					"show_main" => 1,
					"show_category" => 1,
					"author" => $row["author"]
				))
				->result();

			if ($result === false) {
				throw new Exception("Error add post: " . $this->_db->getError());
			}

			$post = $this->_db->insert_id();

			// Send notification
			$this->_registry
				->get("Notifications")
				->add($row["author"], "success", "[blog:notification.moderation.good.title]", $row["title"],
					Posts::getPostLink($post, $row["url"])
				);

			// Remove from moderation list
			$this->remove($id);

			return $post;
		} else {
			return false;
		}
	}

	/**
	 * Bad post
	 * @param int $id Post Moderation ID
	 * @throws Exception
	 */
	public function bad($id) {
		// Get post data
		$post = $this->_db
			->select(["title", "author"])
			->from(DBPREFIX . "blog_posts_moderation")
			->where("id", "=", intval($id))
			->result_array();

		if (isset($post[0])) {
			$this->_registry
				->get("Notifications")
				->add($post[0]["author"], "danger", "[blog:notification.moderation.bad.title]", $post[0]["title"]);

			$this->remove($id);
		}
	}

	/**
	 * Remove post moderation
	 * @param int $id Post ID
	 * @throws Exception
	 */
	public function remove($id) {
		$query = $this->_db
			->delete_from(DBPREFIX . "blog_posts_moderation")
			->where("id", "=", intval($id))
			->result();

		if ($query === false)
			throw new Exception("Error remove post: " . $this->_db->getError());
	}

	/**
	 * Add post for moderation
	 * @param string $title Post title
	 * @param string $url Post url
	 * @param int $category Category ID
	 * @param string $text Post content
	 * @param string $image Image Link
	 * @param string $tags Post tags
	 * @param string $lang Post lang
	 * @return Response
	 */
	public function add($title, $url, $category, $text, $image, $tags, $lang) {
		$response = new Response();

		$title = StringFilters::filterStringForPublic($title);
		$url = StringFilters::filterStringForPublic($url);
		$category = intval($category);
		$image = StringFilters::filterStringForPublic($image);
		$tags = StringFilters::filterTagsString($tags);
		$lang = StringFilters::filterStringForPublic($lang);

		$this->_addTags = array (
			"title" => $title,
			"url" => $url,
			"category" => $category,
			"text" => $text,
			"image-link" => $image,
			"tags" => $tags,
			"lang" => $lang
		);

		if (!$this->_user->isLogged() || !$this->_user->hasPermission("blog.posts.add")) {
			return new Response(2, "danger", $this->_lang->get(null, "core", "accessDenied"));
		}

		$interval = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_posts_moderation")
			->where("author", "=", $this->_user->get("id"))
			->and_where("UNIX_TIMESTAMP(CURRENT_TIMESTAMP)", "<", "UNIX_TIMESTAMP(`timestamp`) + " . $this->_config->get("story", "add.interval", 60), false, false)
			->result_array();

		if ($interval === false) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
		} elseif ($interval[0][0] > 0) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("blog", "add.smallInterval");
		} elseif (empty($title) || empty($text)) {
			$response->code = 2;
			$response->type = "warning";
			$response->message = $this->_lang->get("core", "emptyFields");
		} else {
			$result = $this->_db
				->insert_into(DBPREFIX . "blog_posts_moderation")
				->values(array(
					"title" => $title,
					"url" => $url,
					"category" => $category,
					"text" => $text,
					"image_link" => $image,
					"tags" => $tags,
					"lang" => $lang,
					"author" => $this->_user->get("id"),
				))
				->result();

			if ($result === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$response->type = "success";
				$response->message = $this->_lang->get("blog", "add.success");
			}
		}

		return $response;
	}

	/**
	 * Post write page
	 * @param int|null $category = null Category ID
	 * @return Response
	 */
	public function addPage($category = null) {
		$response = new Response();

		if ($this->_addTags["category"] == 0 && $category !== null) {
			$this->_addTags["category"] = $category;
		}

		$this->_core
			->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog")
			->addBreadcrumbs($this->_lang->get("blog", "add.moduleName"));

		if (!$this->_user->isLogged() || !$this->_user->hasPermission("blog.posts.add")) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
			return $response;
		}

		$response->view = "blog.add";

		// Categories
		$categories = [];
		foreach (Categories::getInstance()->get() as $id => $row) {
			$categories[] = [
				"id" => $id,
				"name" => $row["name"],
				"num" => $row["num"],
				"current" => ($this->_addTags["category"] == $id)
			];
		}

		// Languages
		$langs = [];
		foreach ($this->_lang->getLangs() as $lang => $name) {
			$langs[] = [
				"id" => $lang,
				"name" => $name,
				"current" => ($this->_addTags["lang"] == $lang)
			];
		}

		$response->tags = array_merge($this->_addTags, array (
			"editor" => $this->_config->get("blog", "posts.editor", "BBCode"),
			"categories" => $categories,
			"langs" => $langs
		));

		return $response;
	}
}
