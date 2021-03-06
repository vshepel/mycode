<?php
/**
 * Page Settings Model
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

class Settings extends AppModel {
	/**
	 * Get settings page
	 * @return Response
	 */
	public function page() {
		$this->_core
			->addBreadcrumbs($this->_lang->get("page", "moduleName"), "page")
			->addBreadcrumbs($this->_lang->get("page", "settings.moduleName"), "page/settings");

		if (!$this->_user->hasPermission("page.settings")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$rows = $this->_db
			->select(array(
				"id", "name", "url"
			))
			->from(DBPREFIX . "pages")
			->result_array();

		if ($rows === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}
			
		$pages = "";
		$active = $this->_config->get("page", "page");
		foreach($rows as $row) {
			$pages .= $this->_view->parse("page.settings.selector", [
				"name" => $row["name"] . " ({$row["url"]})",
				"value" => $row["url"],
				"active" => ($row["url"] == $active)
			]);
		}

		$response = new Response();
		$response->view = "page.settings";
		$response->tags = [
			"page" => $pages
		];

		return $response;
	}
	
	/**
	 * Save settings
	 * @param array $values Values
	 * @return Response
	 */
	public function save($values) {
		// Access denied
		if (!$this->_user->hasPermission("page.settings")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		if (!isset($values["page"]) || empty($values["page"])) {
			return new Response(3, "warning", $this->_lang->get("core", "emptyFields"));
		}

		$this->_config->save("page", [
			"page" => $values["page"]
		]);
							
		return new Response(0, "success", $this->_lang->get("page", "settings.success"));
	}
}
