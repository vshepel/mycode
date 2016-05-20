<?php
/**
 * Menu model class
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
use harmony\strings\StringFilters;

class Menu extends AppModel{
	/**
	 * Get menu by type
	 * @param string $type = null Side type
	 * @param bool $array = false Get menu array
	 * @return string|array Menu string or array
	 * @throws Exception
	 */
	public function get($type = null, $array = false) {
		$path = $type . DOT . LOCALE;
		$cache = $this->_cache->get("core.menu", $path);

		if ($cache === false || $array) {
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

			if ($query === false)
				throw new Exception("Menu get error: " . $this->_db->getError());
			else {
				if ($array) return $query; // Return menu array
				
				$menu = "";

				foreach ($query as $row) {
					$row["title"] = $this->_lang->parseString($row["title"]);
					$menu .= $this->_view->parse("core.menu.tag", $row);
				}

				$this->_cache->push("core.menu", $path, $menu);

				return $menu;
			}
		} else
			return $cache;
	}
	
	public function listPage($type, $edit_id = 0) {
		$response = new Response();
		
		// Access denied
		if (!$this->_user->hasPermission("core.menu.list")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}
		
		if ($type != BACKEND) $type = FRONTEND;
			
		$this->_core
			->addBreadcrumbs($this->_lang->get("core", "menu.moduleName"), "core/menu")
			->addBreadcrumbs($this->_lang->get("core", "menu.type." . $type), "core/menu/list");
	
		$array = $this->_db
			->select(array(
				"id", "type", "icon", "title", "link", "pos"
			))
			->from(DBPREFIX . "core_menu")
			->where("type", "=", $type)
			->order_by("pos")->asc()
			->result_array();

		if ($array === false) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
		} else {
			$response->view = "core.menu.list";
			$rows = [];
				
			foreach ($array as $row) {
				$row["edit"] = ($edit_id == $row["id"]);
				$row["not-edit"] = !$row["edit"];
				
				$row["original-title"] = $row["title"];
				$row["title"] = $this->_lang->parseString($row["title"]);
				$rows[] = $row;
			}
		
			$response->tags = array (
				"num" => count($array),
				"rows" => $rows,
				"type" => $type
			);
		}

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
		$response = new Response();

		if (empty($title) || empty($link) || empty($type)) {
			$response->code = 2;
			$response->type = "warning";
			$response->message = $this->_lang->get("core", "emptyFields");
		} else {
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
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("main", "internalError", [$this->_db->getError()]);
			} else {
				$this->_cache->remove("core.menu");
				$response->type = "success";
				$response->message = $this->_lang->get("core", "menu.add.success");
			}
		}

		return $response;
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
		$response = new Response();

		if (empty($pos) || empty($title) || empty($link) || empty($type)) {
			$response->code = 2;
			$response->type = "warning";
			$response->message = $this->_lang->get("core", "emptyFields");
		} else {			
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
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("main", "internalError", [$this->_db->getError()]);
			} else {
				$this->_cache->remove("core.menu");
				$response->type = "success";
				$response->message = $this->_lang->get("core", "menu.edit.success");
			}
		}

		return $response;
	}
	
	/**
	 * Remove menu item
	 * @param int $id Item ID
	 * @throws Exception
	 */
	public function remove($id) {
		$remove = $this->_db
			->delete_from(DBPREFIX . "core_menu")
			->where("id", "=", $id)
			->result();

		if ($remove === false)
			throw new Exception("Menu remove error: " . $this->_db->getError());

		$this->_cache->remove("core.menu");
	}
}
