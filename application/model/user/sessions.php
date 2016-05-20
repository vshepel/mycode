<?php
/**
 * User Sessions List Model
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

use harmony\http\HTTP;
use harmony\pagination\Pagination;

class Sessions extends AppModel {
	/**
	 * Get sessions page
	 * @param int $page Page num
	 * @return Response
	 */
	public function get($page) {
		$response = new Response();

		$page = intval($page);

		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "sessions.moduleName"), "user/sessions");

		/**
		 * Check permissions for show sessions
		 */
		if ($this->_user->isLogged() && $this->_user->hasPermission("user.sessions.show")) {
			/**
			 * Sessions num query
			 */
			$num = $this->_db
				->select("count(*)")
				->from(DBPREFIX . "user_sessions")
				->where("user", "=", $this->_user->get("id"))
				->result_array();

			/**
			 * Database error
			 */
			if ($num === false) {
				return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
			} else {
				$num = $num[0][0];
				$pagination = new Pagination($num, $page, SITE_PATH . "user/sessions/page/", $this->_config->get("user", "sessions.customPagination", array()));

				/**
				 * Sessions get query
				 */
				$array = $this->_db
					->select(array(
						"id", "token", "auth_agent", "auth_ip", array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false)
					))
					->from(DBPREFIX . "user_sessions")
					->where("user", "=", $this->_user->get("id"))
					->order_by("id", $this->_config->get("user", "sessions.sort", "DESC"))
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
				 * Make page
				 */
				else {
					$rows = [];

					/**
					 * Make list
					 */
					foreach ($array as $row) {
						$current = ($row["token"] == $this->_user->getToken());

						$rows[] = [
							"id" => $row["id"],
							"browser" => HTTP::getUserAgentBrowser($row["auth_agent"]),
							"os" => HTTP::getUserAgentOS($row["auth_agent"]),
							"ip" => $row["auth_ip"],
							"create-date" => $this->_core->getDate($row["timestamp"]),
							"create-time" => $this->_core->getTime($row["timestamp"]),

							"current" => $current,
							"not-current" => !$current
						];
					}

					$response->view = "user.sessions";

					$response->tags = array(
						"num" => $num,
						"rows" => $rows,
						"pagination" => $pagination
					);
				}
			}
		} else {
			$this->_core
				->addBreadcrumbs($this->_lang->get("core", "accessDenied"));

			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		}

		return $response;
	}

	/**
	 * Close session
	 * @param int $id Session ID. If null, then close sessions by User ID
	 * @param int $user = null The user for which to close the session. Default is current User ID
	 * @param int $closeType = 0 Session close type.
	 * 0 - Close session by $id and $user;
	 * 1 - Close all $user sessions (exclude current session);
	 * 2 (or other values) - Close all $user session (with current session)
	 * @return Response
	 */
	public function close($id, $user = null, $closeType = 0) {
		$response = new Response();
		
		if ($user === null) {
			$user = $this->_user->get("id");
		}

		// Check permissions for close session
		if ($this->_user->hasPermission("user.sessions.close")) {
			// Check session for exists
			$this->_db->select(["token"])
				->from(DBPREFIX . "user_sessions")			
				->where("user", "=", $user);
			
			switch ($closeType) {
				case 0: $this->_db->and_where("id", "=", $id); break;
				case 1: $this->_db->and_where("token", "!=", $this->_user->getToken());
			}
			
			$find = $this->_db->result_array();

			// Database error
			if ($find === false) {
				return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
			}

			/**
			 * Session not exists
			 */
			elseif (count($find) == 0) {
				return new Response(3, "danger", $this->_lang->get("user", "sessions.badToken"));
			}

			/**
			 * Close session
			 */
			else {
				if ($this->_user->sessionClose($find[0]["token"]) === false) {
					$response->code = 1;
					$response->type = "danger";
					$response->message = $this->_lang->get("core", "internalError");
				} else {
					$response->code = 0;
					$response->type = "success";
					$response->message = $this->_lang->get("user", "sessions.success");
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
}
