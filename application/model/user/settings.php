<?php
/**
 * User Settings Model
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

class Settings extends AppModel {
	/**
	 * Get settings page
	 * @return Response
	 */
	public function page() {		
		$response = new Response();

		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "moduleName"), "page")
			->addBreadcrumbs($this->_lang->get("user", "settings.moduleName"), "page/settings");

		if (!$this->_user->hasPermission("user.settings")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			$groups = "";
			$active = $this->_config->get("user", "guestGroup");
			foreach($this->_user->getGroups() as $id => $row) {
				$groups .= $this->_view->parse("user.settings.selector", [
					"name" => $row[0] . " ({$id})",
					"value" => $id,
					"active" => ($id == $active)
				]);
			}
			
			$response->view = "user.settings";
			$response->tags = [
				"groups" => $groups,
				"active-time" => $this->_config->get("user", "activeTime", 60),
				"avatar-compress" => $this->_config->get("user", "avatarCompress", 80)
			];
		}

		return $response;
	}
	
	/**
	 * Save settings
	 * @param array $values Values
	 * @return Response
	 */
	public function save($values) {
		if (!$this->_user->hasPermission("user.settings"))
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		else {
			if (
				isset($values["guest_group"], $values["active_time"], $values["avatar_compress"]) &&
				(!empty($values["guest_group"]) && !empty($values["active_time"]) && !empty($values["avatar_compress"]))
			) {
				$this->_config->save("user", [
					"guestGroup" => $values["guest_group"],
					"activeTime" => intval($values["active_time"]),
					"avatarCompress" => intval($values["avatar_compress"])
				]);
							
				return new Response(0, "success", $this->_lang->get("page", "settings.success"));
		
			}
		}
		
		return new Response(3, "warning", $this->_lang->get("core", "emptyFields"));
	}
}
