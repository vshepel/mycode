<?php
/**
 * Core Main Model
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

class Main extends AppModel {
	/**
	 * Get main page
	 * @return Response
	 */
	public function page() {
		$this->_core->addBreadcrumbs($this->_lang->get("core", "main.moduleName"), "core/main");

		// Access denied
		if (!$this->_user->hasPermission("core.main")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}
		
		$packages = new Packages();
		
		$pkgs = [];
		
		foreach($packages->get() as $row) {
			if ($row["type"] == "module" && !empty($row["backend-link"])) {
				$pkgs[] = [
					"icon-link" => empty($row["backend-image"]) ? PATH . "images/modules/default.png" : PATH . "images/modules/" . $row["backend-image"],
					"name" => $row["name"],
					"link" => $row["backend-link"],
					"title" => $row["backend-title"],
					"description" => $row["backend-description"],
				];
			}
		}

		$response = new Response();
		$response->view = "core.main";
		$response->tags = array(
			"username" => $this->_user->get("login"),
			"version" => VERSION,

			"group-id" => $this->_user->get("group"),
			"group-name" => $this->_user->getGroupName($this->_user->get("group")),

			"statistics-link" => ADMIN_PATH . "core/statistics",
			"settings-link" => ADMIN_PATH . "core/settings",
			"packages-link" => ADMIN_PATH . "core/packages",
			"backup-link" => ADMIN_PATH . "core/backup",
			"menu-link" => ADMIN_PATH . "core/menu",
			"media-link" => ADMIN_PATH . "core/media",
			
			"packages" => $pkgs
		);

		return $response;
	}
}
