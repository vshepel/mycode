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

		$cache = $this->_cache->get("blog", "categories");

		// Load categories
		if ($cache === false) {
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
			   		"name" => $row["name"],
					"num" => isset($posts[0][0]) ? $posts[0][0] : 0
				];
			}

			$this->_cache->push("blog", "categories", $this->_categories);
		} else
			$this->_categories = $cache;
	}

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
		$response = new Response();

		if (!$this->_user->hasPermission("blog.categories.add")) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} elseif (empty($name)) {
			$response->code = 3;
			$response->type = "warning";
			$response->message = $this->_lang->get("core", "emptyFields");
		} else {
			$query = $this->_db
				->insert_into(DBPREFIX . "blog_categories")
				->values(array (
					"name" => StringFilters::filterHtmlTags($name)
				))
				->result();

			if ($query === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("main", "internalError", [$this->_db->getError()]);
			} else {
				$response->type = "success";
				$response->message = $this->_lang->get("blog", "categories.add.success");
				$this->_cache->remove("blog", "categories"); // Clear categories cache
			}
		}

		return $response;
	}

	/**
	 * Remove category
	 * @param int $id Category ID
	 * @return Response
	 */
	public function remove($id) {
		$response = new Response();

		if (!$this->_user->hasPermission("blog.categories.remove")) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} elseif ($this->exists($id)) {
			$query = $this->_db
				->delete_from(DBPREFIX . "blog_categories")
				->where("id", "=", $id)
				->result();

			if ($query === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("main", "internalError", [$this->_db->getError()]);
			} else {
				$response->type = "success";
				$response->message = $this->_lang->get("blog", "categories.remove.success");
				$this->_cache->remove("blog", "categories"); // Clear categories cache
			}
		} else {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("blog", "categories.remove.notExists");
		}

		return $response;
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
	 * @param int $active = -1 
	 * @return string
	 */
	public function getList($active = -1) {
		foreach ($this->get() as $id => $row) {
			$this->_view->add("blog.tag.category", [
				"id" => $id,
				"name" => $row["name"],
				"link" => SITE_PATH . "blog/cat/" . $id,
				"num" => $row["num"],
				"active" => ($active == $id)
			]);
		}

		return $this->_view->get("blog.tag.category");
	}

	/**
	 * Get categories page
	 * @return Response
	 */
	public function getPage() {
		$response = new Response();

		$this->_core
			->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog")
			->addBreadcrumbs($this->_lang->get("blog", "categories.moduleName"));
			
		if (!$this->_user->hasPermission("blog.categories.list")) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			$response->view = "blog.categories";
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
			$response->tags = $tags;
		}
		
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
}
