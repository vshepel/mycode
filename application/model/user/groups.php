<?php
/**
 * User Groups Model
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

namespace model\user;

use AppModel;
use Response;

use harmony\pagination\Pagination;
use harmony\strings\StringFilters;
use harmony\strings\Strings;

class Groups extends AppModel {
	/**
	 * Get groups list
	 * @param int $page Page num
	 * @return Response
	 * @throws \Exception
	 */
	public function get($page) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "moduleName"), "user")
			->addBreadcrumbs($this->_lang->get("user", "groups.moduleName"), "user/groups");

		// Access denied
		if (!$this->_user->hasPermission("user.groups.list")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$page = intval($page);

		// Groups num query
		$num = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "user_groups")
			->result_array();

		// Database error
		if ($num === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$num = $num[0][0];
		$pagination = new Pagination($num, $page, SITE_PATH . "user/groups/page/", $this->_config->get("user", "list.customPagination", array()));

		// Groups list query
		$array = $this->_db
			->select(array(
				"id", "name", "extends"
			))
			->from(DBPREFIX . "user_groups")
			->order_by("id")->asc()
			->limit($pagination->getSqlLimits())
			->result_array();

		// Database error
		if ($array === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$rows = [];

		foreach ($array as $row) {
			$rows[] = [
				"id" => $row["id"],
				"name" => $this->_lang->parseString($row["name"]),
				"extends" => implode(", ", json_decode($row["extends"])),

				"remove" => $row["id"] > 4,
				"remove-link" => ADMIN_PATH . "user/groups/remove/" . $row["id"],
				"edit-link" => ADMIN_PATH . "user/groups/edit/" . $row["id"],
			];
		}

		$response = new Response();
		$response->view = "user.groups.list";
		$response->tags = [
			"num" => $num,
			"rows" => $rows,
			"pagination" => $pagination
		];

		return $response;
	}

	/**
	 * Add group
	 * @param string $name Group name
	 * @return Response
	 */
	public function add($name) {
		$name = StringFilters::filterHtmlTags($name);

		// Access denied
		if (!$this->_user->hasPermission("user.groups.add")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		} elseif (empty($name)) {
			return new Response(3, "warning", $this->_lang->get("core", "emptyFields"));
		}

		$query = $this->_db
			->insert_into(DBPREFIX . "user_groups")
			->values(array (
				"name" => $name,
				"extends" => "[]",
				"permissions" => "[]"
			))
			->result();

		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$this->_cache->remove("user.groups");

		return new Response(0, "success", $this->_lang->get("user", "groups.add.success"));
	}

	/**
	 * Edit group page
	 * @param int $id Group ID
	 * @return Response
	 */
	public function editPage($id) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "moduleName"), "user")
			->addBreadcrumbs($this->_lang->get("user", "groups.moduleName"), "user/groups");

		// Access denied
		if ($this->_user->hasPermission("user.groups.edit")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$id = intval($id);

		$query = $this->_db
			->select(array(
				"id", "name", "extends", "permissions"
			))
			->from(DBPREFIX . "user_groups")
			->where("id", "=", $id)
			->result_array();

		// Database error
		if ($query === false) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "internalError"), "user/groups/edit/" . $id);
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		} elseif (!isset($query[0])) { // Not found
			$this->_core->addBreadcrumbs($this->_lang->get("user", "notFound"), "user/groups/edit/" . $id);
			return new Response(3, "danger", $this->_lang->get("user", "notFound"));
		}

		$row  = $query[0];
		$this->_core->addBreadcrumbs($this->_lang->parseString($row["name"]), "user/groups/edit/" . $id);

		$response = new Response();
		$response->view = "user.groups.edit";
		$response->tags = [
			"id" => $row["id"],
			"name" => $row["name"],
			"extends" => implode(",", (array) json_decode($row["extends"])),
			"permissions" => implode("\n", (array) json_decode($row["permissions"]))
		];

		return $response;
	}

	/**
	 * Edit
	 * @param int $id Group ID
	 * @param string $name Group name
	 * @param string $extends Group extends
	 * @param string $permissions Group permissions
	 * @return Response
	 */
	public function edit($id, $name, $extends, $permissions) {
		$id = intval($id);
		$name = StringFilters::filterHtmlTags($name);
		$extends = StringFilters::filterHtmlTags($extends);
		$permissions = StringFilters::filterHtmlTags($permissions);

		// Access denied
		if (!$this->_user->hasPermission("user.groups.edit")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		} elseif (empty($name)) {
			return new Response(3, "warning", $this->_lang->get("core", "emptyFields"));
		}

		$query = $this->_db
			->update(DBPREFIX . "user_groups")
			->set(array (
				"name" => $name,
				"extends" => empty($extends) ? "[]" : json_encode(explode(",", $extends)),
				"permissions" => empty($permissions) ? "[]" : json_encode(explode("\n", Strings::lineWrap($permissions, "\n")))
			))
			->where("id", "=", intval($id))
			->result();

		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$this->_cache->remove("user.groups");
		return new Response(0, "success", $this->_lang->get("user", "groups.edit.success"));
	}

	/**
	 * Remove group page
	 * @param int $id Group ID
	 * @return Response
	 */
	public function removePage($id) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "moduleName"), "user")
			->addBreadcrumbs($this->_lang->get("user", "groups.moduleName"), "user/groups")
			->addBreadcrumbs($this->_lang->get("user", "remove.moduleName"), "user/groups/remove/" . $id);

		if (!$this->_user->hasPermission("user.groups.remove") || $id < 5) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"), "user/groups/remove/" . $id);
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$id = intval($id);

		$query = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "user_groups")
			->where("id", "=", $id)
			->result_array();

		if (!isset($query[0])) {
			return new Response(3, "danger", $this->_lang->get("user", "remove.notExists"));
		}

		$response = new Response();
		$response->view = "user.groups.remove";
		$response->tags["id"] = $id;

		return $response;
	}

	/**
	 * Remove group
	 * @param int $id Group ID
	 * @return Response
	 */
	public function remove($id) {
		// Access denied
		if (!$this->_user->hasPermission("user.groups.remove") || $id < 5) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$id = intval($id);

		$query = $this->_db
			->delete_from(DBPREFIX . "user_groups")
			->where("id", "=", $id)
			->result();

		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$this->_cache->remove("user.groups");

		return new Response(0, "success");
	}
}
