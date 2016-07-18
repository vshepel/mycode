<?php
/**
 * Blog Categories Singleton Model
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
use Exception;

use harmony\strings\StringFilters;

class Categories extends AppModel {
	/**
	 * @var array Categories array
	 */
	private $_categories = array ();

	/**
	 * @var object Singleton instance
	 */
	private static $_instance = null;

	public static function getInstance() {
		if (self::$_instance === null)
			self::$_instance = new self;

		return self::$_instance;
	}

	/**
	 * @throws \Exception
	 */
	public function __construct() {
		parent::__construct();

		$cache = $this->_cache->get("blog", "categories." . $this->_lang->getLang());

		// Load categories
		if ($cache !== false) {
			$this->_categories = $cache;
		} else {
			$array = $this->_db
				->select(array(
				   "id", "name"
				))
				->from(DBPREFIX . "blog_categories")
				->order_by("id")->asc()
				->result_array();

			if ($array === false)
				throw new \Exception("Blog Categories error: " . $this->_db->getError());
			else {
				$posts = $this->_db
					->select("count(*)")
					->from(DBPREFIX . "blog_posts")
					->where("category", "=", 0)
					->result_array();

				$this->_categories[0] = [
					"id" => 0,
					"name"=> $this->_lang->get("blog", "defaultCategory"),
					"num" => isset($posts[0][0]) ? $posts[0][0] : 0
				];
			}

			foreach ($array as $row) {
				$posts = $this->_db
					->select("count(*)")
					->from(DBPREFIX . "blog_posts")
					->where("category", "=", $row["id"])
					->result_array();

				$this->_categories[$row["id"]] = [
					"id" => $row["id"],
			   		"name" => $row["name"],
					"num" => isset($posts[0][0]) ? $posts[0][0] : 0
				];
			}

			$this->_cache->push("blog", "categories." . $this->_lang->getLang(), $this->_categories);
		}
	}

	/**
	 * @var int Active category
	 */
	public $activeCategory = -1;

	/**
	 * Check category for exists
	 * @param int $id Category ID
	 * @return bool
	 */
	public function exists($id) {
		return isset($this->_categories[$id]);
	}

	/**
	 * Add category
	 * @param string $name Category name
	 * @return Response
	 */
	public function add($name) {
		if (!$this->_user->hasPermission("blog.categories.add")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		} elseif (empty($name)) {
			return new Response(3, "warning", $this->_lang->get("core", "emptyFields"));
		}

		$query = $this->_db
			->insert_into(DBPREFIX . "blog_categories")
			->values(array (
				"name" => StringFilters::filterHtmlTags($name)
			))
			->result();

		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("main", "internalError", [$this->_db->getError()]));
		}

		$this->_cache->remove("blog"); // Clear categories cache

		return new Response(0, "success", $this->_lang->get("blog", "categories.add.success"));
	}

	/**
	 * Remove category
	 * @param int $id Category ID
	 * @return Response
	 */
	public function remove($id) {
		// Access denied
		if (!$this->_user->hasPermission("blog.categories.remove")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		} elseif (!$this->exists($id)) {
			return new Response(2, "danger", $this->_lang->get("blog", "categories.remove.notExists"));
		}

		$query = $this->_db
			->delete_from(DBPREFIX . "blog_categories")
			->where("id", "=", $id)
			->result();

		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$this->_cache->remove("blog"); // Clear categories cache
		return new Response(0, "success", $this->_lang->get("blog", "categories.remove.success"));
	}

	/**
	 * Get categories array
	 * @return array
	 */
	public function get() {
		return $this->_categories;
	}

	/**
	 * Get Categories list
	 * @return string
	 */
	public function getList() {
		$notEmptyCategories = $this->_config->get("blog", "main.not_empty_categories", false);
		$rows = [];

		foreach ($this->get() as $id => $row) {
			if (!($notEmptyCategories && $row["num"] <= 0)) {
				$rows[] = [
					"id" => $id,
					"name" => $row["name"],
					"link" => SITE_PATH . "blog/cat/" . $id,
					"num" => $row["num"],
					"active" => ($this->activeCategory == $id)
				];
			}
		}

		return $this->_view->parse("blog.tag.category", [
			"rows" => $rows
		]);
	}

	/**
	 * Get categories page
	 * @return Response
	 */
	public function getPage() {
		$this->_core
			->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog")
			->addBreadcrumbs($this->_lang->get("blog", "categories.moduleName"));

		// Access denied
		if (!$this->_user->hasPermission("blog.categories.list")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$categories = self::get();
		$tags = array ();
	
		$tags["num"] = count($categories);
		$rows = [];
	
		foreach ($categories as $id => $row) {
			$rows[] = [
				"id" => $id,
				"name" => $row["name"],
				"posts-num" => $row["num"],
				"category-link" => ADMIN_PATH . "blog/posts/cat/" . $id,
			];
		}
	
		$tags["rows"] = $rows;

		$response = new Response();
		$response->view = "blog.categories";
		$response->tags = $tags;
		
		return $response;
	}

	/**
	 * Get category name
	 * @param int $id Category ID
	 * @return string
	 */
	public function getName($id) {
		if ($id == 0)
			return $this->_lang->get("blog", "defaultCategory");
		else
			return isset($this->_categories[$id]["name"]) ? $this->_categories[$id]["name"] : $this->_lang->get("blog", "unknownCategory");
	}

	/**
	 * Get categories URLs
	 * @return array
	 * @throws Exception
	 */
	public function getUrls() {
		$urls = [];

		$array = $this->_db
			->select(["id"])
			->from(DBPREFIX . "blog_categories")
			->result_array();

		if ($array === false) {
			throw new Exception("Error get categories urls: " . $this->_db->getError());
		}

		foreach ($array as $row) {
			$urls[] = [
				"loc" => FSITE_PATH . "blog/cat/" . $row["id"],
				"priority" => 0.7
			];
		}

		return $urls;
	}
}
