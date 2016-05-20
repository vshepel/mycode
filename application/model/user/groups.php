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
		$response = new Response();

		$page = intval($page);

		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "moduleName"), "user")
			->addBreadcrumbs($this->_lang->get("user", "groups.moduleName"), "user/groups");

		/**
		 * Check permissions for groups list
		 */
		if ($this->_user->hasPermission("user.groups.list")) {
			/**
			 * Groups num query
			 */
			$num = $this->_db
				->select("count(*)")
				->from(DBPREFIX . "user_groups")
				->result_array();

			/**
			 * Database error
			 */
			if ($num === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$num = $num[0][0];
				$pagination = new Pagination($num, $page, SITE_PATH . "user/groups/page/", $this->_config->get("user", "list.customPagination", array()));

				/**
				 * Groups list query
				 */
				$array = $this->_db
					->select(array(
						"id", "name", "extends"
					))
					->from(DBPREFIX . "user_groups")
					->order_by("id")->asc()
					->limit($pagination->getSqlLimits())
					->result_array();

				/**
				 * Database error
				 */
				if ($array === false) {
					$response->code = 1;
					$response->type = "danger";
					$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
				}

				/**
				 * Make groups list
				 */
				else {
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

					$response->code = 0;
					$response->view = "user.groups.list";

					$response->tags = array(
						"num" => $num,
						"rows" => $rows,
						"pagination" => $pagination
					);
				}
			}
		}

		/**
		 * Access denied
		 */
		else {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		}

		return $response;
	}

	/**
	 * Add
	 */

	/**
	 * Add group
	 * @param string $name Group name
	 * @return Response
	 */
	public function add($name) {
		$response = new Response();

		$name = StringFilters::filterHtmlTags($name);

		/**
		 * If user haven't permission for add group
		 */
		if (!$this->_user->hasPermission("user.groups.add")) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} elseif (empty($name)) {
			$response->code = 3;
			$response->type = "warning";
			$response->message = $this->_lang->get("core", "emptyFields");
		} else {
			$query = $this->_db
				->insert_into(DBPREFIX . "user_groups")
				->values(array (
					"name" => $name,
					"extends" => "[]",
					"permissions" => "[]"
				))
				->result();

			if ($query === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$this->_cache->remove("user.groups");
				$response->type = "success";
				$response->message = $this->_lang->get("user", "groups.add.success");
			}
		}

		return $response;
	}

	/**
	 * Edit
	 */

	/**
	 * Edit group page
	 * @param int $id Group ID
	 * @return Response
	 */
	public function editPage($id) {
		$response = new Response();

		$id = intval($id);

		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "moduleName"), "user")
			->addBreadcrumbs($this->_lang->get("user", "groups.moduleName"), "user/groups");

		/**
		 * Check permission for edit group
		 */
		if ($this->_user->hasPermission("user.groups.edit")) {
			$query = $this->_db
				->select(array(
					"id", "name", "extends", "permissions"
				))
				->from(DBPREFIX . "user_groups")
				->where("id", "=", $id)
				->result_array();

			/**
			 * Database error
			 */
			if ($query === false) {
				$this->_core->addBreadcrumbs($this->_lang->get("core", "internalError"), "user/groups/edit/" . $id);

				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			}

			/**
			 * Not found
			 */
			elseif (!isset($query[0])) {
				$this->_core->addBreadcrumbs($this->_lang->get("user", "notFound"), "user/groups/edit/" . $id);

				$response->code = 3;
				$response->type = "danger";
				$response->message = $this->_lang->get("user", "notFound");
			}

			/**
			 * Show edit page
			 */
			else {
				$response->view = "user.groups.edit";
				$row  = $query[0];

				$this->_core->addBreadcrumbs($this->_lang->parseString($row["name"]), "user/groups/edit/" . $id);

				/**
				 * Tags
				 */
				$response->tags = array (
					"id" => $row["id"],
					"name" => $row["name"],
					"extends" => implode(",", (array) json_decode($row["extends"])),
					"permissions" => implode("\n", (array) json_decode($row["permissions"]))
				);
			}
		} else {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"), "user/groups/edit/" . $id);

			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		}

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
		$response = new Response();

		$id = intval($id);
		$name = StringFilters::filterHtmlTags($name);
		$extends = StringFilters::filterHtmlTags($extends);
		$permissions = StringFilters::filterHtmlTags($permissions);

		if (!$this->_user->hasPermission("user.groups.edit")) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} elseif (empty($name)) {
			$response->code = 3;
			$response->type = "warning";
			$response->message = $this->_lang->get("core", "emptyFields");
		} else {
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
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$this->_cache->remove("user.groups");
				$response->type = "success";
				$response->message = $this->_lang->get("user", "groups.edit.success");
			}
		}

		return $response;
	}

	/**
	 * Remove
	 */

	/**
	 * Remove group page
	 * @param int $id Group ID
	 * @return Response
	 */
	public function removePage($id) {
		$response = new Response();

		$id = intval($id);

		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "moduleName"), "user")
			->addBreadcrumbs($this->_lang->get("user", "groups.moduleName"), "user/groups")
			->addBreadcrumbs($this->_lang->get("user", "remove.moduleName"), "user/groups/remove/" . $id);

		if (!$this->_user->hasPermission("user.groups.remove") || $id < 5) {
			$this->_core
				->addBreadcrumbs($this->_lang->get("core", "accessDenied"), "user/groups/remove/" . $id);

			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			$query = $this->_db
				->select("count(*)")
				->from(DBPREFIX . "user_groups")
				->where("id", "=", $id)
				->result_array();

			if (isset($query[0])) {
				$response->view = "user.groups.remove";

				$response->tags["id"] = $id;
			} else {
				$response->code = 2;
				$response->type = "danger";
				$response->message = $this->_lang->get("user", "remove.notExists");
			}
		}

		return $response;
	}

	/**
	 * Remove group
	 * @param int $id Group ID
	 * @return Response
	 */
	public function remove($id) {
		$response = new Response();

		$id = intval($id);

		if (!$this->_user->hasPermission("user.groups.remove") || $id < 5) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			$query = $this->_db
				->delete_from(DBPREFIX . "user_groups")
				->where("id", "=", $id)
				->result();

			if ($query === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$this->_cache->remove("user.groups");
				$response->type = "success";
			}
		}

		return $response;
	}
}
