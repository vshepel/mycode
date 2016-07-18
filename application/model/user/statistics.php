<?php
/**
 * User Statistics Model
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

class Statistics extends AppModel {
	/**
	 * Get statistics page
	 * @return Response
	 */
	public function getPage() {
		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "moduleName"), "user")
			->addBreadcrumbs($this->_lang->get("user", "statistics.moduleName"), "user/statistics");

		// Access denied
		if (!$this->_user->hasPermission("user.statistics")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$tags = [];

		// Users num
		$num = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "user_profiles")
			->result_array();
		$tags["users-num"] = $num[0][0];

		// User groups num
		$groups = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "user_groups")
			->result_array();
		$tags["users-groups"] = $groups[0][0];

		// User sessions num
		$sessions = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "user_sessions")
			->result_array();
		$tags["users-sessions"] = $sessions[0][0];

		$response = new Response();
		$response->view = "user.statistics";
		$response->tags = $tags;
		return $response;
	}
}
