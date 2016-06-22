<?php
/**
 * Page Model
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

namespace model\page;

use AppModel;
use Response;
use NotFoundException;

use harmony\pagination\Pagination;
use harmony\strings\StringFilters;

class Page extends AppModel {
	/**
	 * @var array Add tags
	 */
	private $_addTags = [
		"name" => "",
		"url" => "",
		"text" => ""
	];

	/**
	 * @var array|null
	 */
	private $_editQuery = null;

	/**
	 * Check page for exists
	 * @param int $id Page ID
	 * @return bool
	 * @throws \Exception
	 */
	public function exists($id) {
		$result = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "pages")
			->where("id", "=", intval($id))
			->result_array();

		if (!isset($result[0][0]))
			throw new \Exception("Error check page for exists: {$this->_db->getError()}");

		return ($result[0][0] > 0);
	}

	/**
	 * @param string $name Page name
	 * @return Response
	 * @throws NotFoundException
	 */
	public function page($name) {
		$response = new Response();

		$name = StringFilters::filterHtmlTags($name);

		$array = $this->_db
			->select(array (
				"id", "name", "text"
			))
			->from(DBPREFIX . "pages")
			->where("url", "=", $name)
			->and_where("lang", "=", $this->_lang->getLang())
			->result_array();

		if ($array === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [""]));
		}

		// Any language
		if (!isset($array[0])) {
			$array = $this->_db
				->select(array (
					"id", "name", "text"
				))
				->from(DBPREFIX . "pages")
				->where("url", "=", $name)
				->result_array();

			if ($array === false) {
				return new Response(1, "danger", $this->_lang->get("core", "internalError", [""]));
			}
		}

		if (isset($array[0])) {
			$row = $array[0];
			$title = $row["name"];
			$response->view = "page";

			$response->tags = [
				"id" => $row["id"],
				"title" => $row["name"],
				"content" => $row["text"],
				"edit-link" => ADMIN_PATH . "page/edit/" . $row["id"],
				"remove-link" => ADMIN_PATH . "page/remove/" . $row["id"]
			];
		} else
			throw new NotFoundException();

		$this->_core->addBreadcrumbs($title);

		return $response;
	}

	/**
	 * Get page list
	 * @param int $page Page num
	 * @return Response
	 */
	public function get($page) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("page", "moduleName"), "page")
			->addBreadcrumbs($this->_lang->get("page", "list.moduleName"));
			
		if (!$this->_user->hasPermission("page.list")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$response = new Response();
		$page = intval($page);

		$num = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "pages")
			->result_array();

		if ($num === false) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
		} else {
			$num = $num[0][0];
			$pagination = new Pagination($num, $page, SITE_PATH . "page/list/page/", $this->_config->get("page", "list.customPagination", array()));

			$array = $this->_db
				->select(array(
					"id" ,"name", "url", "lang"
				))
				->from(DBPREFIX . "pages")
				->order_by("url", $this->_config->get("page", "list.sort", "DESC"))
				->limit($pagination->getSqlLimits())
				->result_array();

			if ($array === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$rows = [];

				foreach ($array as $row) {
					$row["language"] = $this->_lang->getLangName($row["lang"]);

					$rows[] = array_merge($row, [
						"page-link" => SITE_PATH . "page/" . $row["url"],
						"edit-link" => ADMIN_PATH . "page/edit/" . $row["id"],
						"remove-link" => ADMIN_PATH . "page/remove/" . $row["id"]
					]);
				}

				$response->view = "page.list";

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
	 * Add page
	 * @param string $name Page name
	 * @param string $url Page url
	 * @param string $text Page text
	 * @param string $lang anguage name
	 * @param int|null $pageId Edit page ID
	 * @return Response
	 */
	public function add($name, $url, $text, $lang, $pageId = null) {
		if (!$this->_user->hasPermission("page.add") && $pageId === null) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$this->_addTags = array (
			"name" => $name,
			"url" => $url,
			"text" => $text,
			"lang" => $lang
		);

		$response = new Response();

		// Filtering tags
		$name = StringFilters::filterHtmlTags($name);
		$url = StringFilters::filterHtmlTags($url);
		$lang = StringFilters::filterHtmlTags($lang);
		$pageId = ($pageId === null) ? null : intval($pageId);

		if (empty($name) || empty($url) || empty($text) || empty($lang)) {
			$response->code = 3;
			$response->type = "warning";
			$response->message = $this->_lang->get("core", "emptyFields");
		} else {
			$edit = ($pageId != null);

			if (!$edit) {
				$this->_db
					->select("count(*)")
					->from(DBPREFIX . "pages")
					->where("url", "=", $url)
					->and_where("lang", "=", $lang);

				if ($pageId != null) {
					$this->_db->and_where("id", "!=", $pageId);
				}

				$num = $this->_db->result_array();

				if ($num[0][0] > 0) {
					$response->code = 3;
					$response->type = "danger";
					$response->message = $this->_lang->get("page", "add.badUrl");
				} else {
					$result = $this->_db
						->insert_into(DBPREFIX . "pages")
						->values($this->_addTags)
						->result();

					if ($result === false) {
						$response->code = 1;
						$response->type = "danger";
						$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
					} else {
						$response->type = "success";
						$response->message = $this->_lang->get("page", ($edit ? "edit" : "add") . "success");
					}
				}
			} else {
				$result = $this->_db
					->update(DBPREFIX . "pages")
					->set($this->_addTags)
					->where("id", "=", $pageId)
					->result();

				if ($result === false) {
					$response->code = 1;
					$response->type = "danger";
					$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
				} else {
					$response->type = "success";
					$response->message = $this->_lang->get("page", ($edit ? "edit" : "add") . "success");
				}
			}
		}

		return $response;
	}

	/**
	 * Add page
	 * @return Response
	 */
	public function addPage() {
		$this->_core
			->addBreadcrumbs($this->_lang->get("page", "moduleName"), "page")
			->addBreadcrumbs($this->_lang->get("page", "add.moduleName"));
			
		if (!$this->_user->hasPermission("page.add"))
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));

		$response = new Response();

		// Languages
		$langs = [];
		$current = isset($this->_addTags["lang"]) ? $this->_addTags["lang"] : $this->_lang->getLang();
		foreach ($this->_lang->getLangs() as $lang => $name)
			$langs[] = [
				"id" => $lang,
				"name" => $name,
				"current" => ($current == $lang)
			];

		$response->view = "page.add";
		$response->tags = array_merge($this->_addTags, [
			"langs" => $langs
		]);

		return $response;
	}

	/**
	 * Edit page
	 * @param string $name Page name
	 * @param string $url Page url
	 * @param string $text Page text
	 * @param string $lang Language name
	 * @param int|null $pageId Edit page ID
	 * @return Response
	 */
	public function edit($name, $url, $text, $lang, $pageId) {
		if (!$this->_user->hasPermission("page.edit"))
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		return $this->add($name, $url, $text, $lang, $pageId);
	}

	/**
	 * Edit page
	 * @param int $pageId Page ID
	 * @return Response
	 * @throws \Exception
	 */
	public function editPage($pageId) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("page", "moduleName"), "page")
			->addBreadcrumbs($this->_lang->get("page", "edit.moduleName"));
			
		if (!$this->_user->hasPermission("page.edit"))
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));

		$response = new Response();

		$pageId = intval($pageId);
		$response->view = "page.edit";

		if ($this->exists($pageId)) {
			if ($this->_editQuery === null) {
				$row = $this->_db
					->select(array(
						"id", "name", "url", "text", "lang"
					))
					->from(DBPREFIX . "pages")
					->where("id", "=", $pageId)
					->result_array();

				if (!isset($row[0])) {
					return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
				} else {
					$response->tags = $row[0];
				}
			} else
				$response->tags = $this->_editQuery;

			// Languages
			$langs = [];
			foreach ($this->_lang->getLangs() as $lang => $name)
				$langs[] = [
					"id" => $lang,
					"name" => $name,
					"current" => ($response->tags["lang"] == $lang)
				];

			$response->tags = array_merge($response->tags, [
				"langs" => $langs,
				"list-link" => ADMIN_PATH . "page/list",
				"remove-link" => ADMIN_PATH . "page/remove/" . $pageId
			]);
		} else {
			$response->code = 3;
			$response->type = "danger";
			$response->message = $this->_lang->get("page", "edit.notExists");
		}

		return $response;
	}

	/**
	 * Remove page
	 * @param int $id Page ID
	 * @return Response
	 * @throws \Exception
	 */
	public function remove($id) {
		if (!$this->_user->hasPermission("page.remove"))
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$response = new Response();
		$id = intval($id);

		if ($this->exists($id)) {
			$query = $this->_db
				->delete_from(DBPREFIX . "pages")
				->where("id", "=",$id)
				->result();

			if ($query === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("main", "internalError", [$this->_db->getError()]);
			} else {
				$response->type = "success";
				$response->message = $this->_lang->get("page", "remove.success");
			}
		} else {
			$response->code = 3;
			$response->type = "danger";
			$response->message = $this->_lang->get("page", "remove.notExists");
		}

		return $response;
	}

	/**
	 * Page for remove page
	 * @param int $id Page ID
	 * @return Response
	 * @throws \Exception
	 */
	public function removePage($id) {
		if (!$this->_user->hasPermission("page.remove"))
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$response = new Response();
		$id = intval($id);

		$this->_core
			->addBreadcrumbs($this->_lang->get("page", "moduleName"), "page")
			->addBreadcrumbs($this->_lang->get("page", "remove.moduleName"));

		if ($this->exists($id)) {
			$response->view = "page.remove";

			$response->tags["id"] = $id;
		} else {
			$response->code = 3;
			$response->type = "danger";
			$response->message = $this->_lang->get("page", "remove.notExists");
		}

		return $response;
	}
}