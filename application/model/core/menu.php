<?php
/**
 * Core Menu Model
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
 
namespace model\core;

use AppModel;
use Response;
use Exception;
use harmony\strings\StringFilters;

class Menu extends AppModel{
	/**
	 * Get menu by type
	 * @param string $type = null Side type
	 * @return array Menu array
	 * @throws Exception
	 */
	public function get($type = null) {
		$this->_db
			->select(array(
				"id", "type", "icon", "title", "link"
			))
			->from(DBPREFIX . "core_menu");

		if ($type != null && $type != "")
			$this->_db->where("type", "=", $type);

		$query = $this->_db
			->order_by("pos")->asc()
			->result_array();

		if ($query === false) {
			throw new Exception("Menu get error: " . $this->_db->getError());
		}

		$menu = "";
		$pkg = new Packages();
		$mod = $this->_registry->get("Router")->getModule();
		$url = $this->_registry->get("Router")->getRequest();

		foreach ($query as $row) {
			$module = "";

			$row["title"] = $this->_lang->parseString($row["title"]);
			$row["link"] = preg_replace_callback("#\\{(.*)}#isU", function ($args) use ($pkg, &$module) {
				$module = $args[1];
				return $pkg->get($module)[SIDETYPE . "-link"];
			}, $row["link"]);

			$row["active"] = ($mod == $module || $url == $row["link"]);

			$menu[] = $row;
		}

		return $menu;
	}
	
	public function listPage($type, $edit_id = 0) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("core", "menu.moduleName"), "core/menu")
			->addBreadcrumbs($this->_lang->get("core", "menu.type." . $type), "core/menu/list");

		// Access denied
		if (!$this->_user->hasPermission("core.menu.list")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}
		
		if ($type != BACKEND) $type = FRONTEND;
	
		$array = $this->_db
			->select(array(
				"id", "type", "icon", "title", "link", "pos"
			))
			->from(DBPREFIX . "core_menu")
			->where("type", "=", $type)
			->order_by("pos")->asc()
			->result_array();

		if ($array === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$rows = [];
				
		foreach ($array as $row) {
			$rows[] = array_merge($row, [
				"edit" => ($edit_id == $row["id"]),
				"not-edit" => !($edit_id == $row["id"]),

				"original-title" => $row["title"],
				"title" => $this->_lang->parseString($row["title"])
			]);
		}

		$response = new Response();
		$response->view = "core.menu.list";
		$response->tags = [
			"num" => count($array),
			"rows" => $rows,
			"type" => $type
		];

		return $response;
	}
	
	/**
	 * Add menu item
	 * @param string $icon Item icon
	 * @param int $pos Item position
	 * @param string $title Item title
	 * @param string $link Item link
	 * @param string $type Item type
	 * @return Response
	 */
	public function add($icon, $pos, $title, $link, $type) {
		// Access denied
		if (!$this->_user->hasPermission("core.menu.add")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		if (empty($title) || empty($link) || empty($type)) {
			return new Response(2, "warning", $this->_lang->get("core", "emptyFields"));
		}

		if ($pos === null || empty($pos)) {
			$array = $this->_db
			->select(array(
				"pos"
			))
			->from(DBPREFIX . "core_menu")
			->where("type", "=", $type)
			->order_by("pos")->desc()
			->limit([0,1])
			->result_array();
				
			$pos = isset($array[0][0]) ? $array[0][0]+10 : 0;
		}
			
		$query = $this->_db
			->insert_into(DBPREFIX . "core_menu")
			->values(array (
				"icon" => StringFilters::filterHtmlTags($icon),
				"pos" => intval($pos),
				"title" => StringFilters::filterHtmlTags($title),
				"link" => StringFilters::filterHtmlTags($link),
				"type" => StringFilters::filterHtmlTags($type)
			))
			->result();

		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$this->_cache->remove("core.menu");

		return new Response(0, "success", $this->_lang->get("core", "menu.add.success"));
	}
	
	/**
	 * Edit menu item
	 * @param int $item_id Item id
	 * @param string $icon Item icon
	 * @param int $pos Item position
	 * @param string $title Item title
	 * @param string $link Item link
	 * @param string $type Item type
	 * @return Response
	 */
	public function edit($item_id, $icon, $pos, $title, $link, $type) {
		// Access denied
		if (!$this->_user->hasPermission("core.menu.edit")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		if (empty($pos) || empty($title) || empty($link) || empty($type)) {
			return new Response(3, "warning", $this->_lang->get("core", "emptyFields"));
		}

		$query = $this->_db
			->update(DBPREFIX . "core_menu")
			->set(array (
				"icon" => StringFilters::filterHtmlTags($icon),
				"pos" => intval($pos),
				"title" => StringFilters::filterHtmlTags($title),
				"link" => StringFilters::filterHtmlTags($link),
				"type" => StringFilters::filterHtmlTags($type)
			))
			->where("id", "=", $item_id)
			->result();

		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$this->_cache->remove("core.menu");

		return new Response(0, "success");
	}

	/**
	 * Remove menu item
	 * @param int $id Item ID
	 * @return bool
	 * @throws Exception
	 */
	public function remove($id) {
		// Access denied
		if (!$this->_user->hasPermission("core.menu.remove")) {
			return false;
		}

		$remove = $this->_db
			->delete_from(DBPREFIX . "core_menu")
			->where("id", "=", $id)
			->result();

		if ($remove === false) {
			throw new Exception("Menu remove error: " . $this->_db->getError());
		}

		$this->_cache->remove("core.menu");

		return true;
	}
}
