<?php
/**
 * Blog Settings Model
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

namespace model\blog;

use AppModel;
use Response;

class Settings extends AppModel {
	private $_editors = ["HTML", "BBCode", "Markdown"];

	/**
	 * Get settings page
	 * @return Response
	 */
	public function page() {		
		$response = new Response();

		$this->_core
			->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog")
			->addBreadcrumbs($this->_lang->get("blog", "settings.moduleName"), "blog/settings");

		if (!$this->_user->hasPermission("blog.settings")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			// Editors list
			$editors = "";
			$active = $this->_config->get("blog", "editor", "BBCode");
			foreach($this->_editors as $row) {
				$editors .= $this->_view->parse("blog.settings.selector", [
					"name" => $row,
					"value" => $row,
					"active" => ($row == $active)
				]);
			}

			$response->view = "blog.settings";
			$response->tags = [
				"editors" => $editors,
				"rating-active" => $this->_config->get("blog", "rating_active", true),
				"advanced-views" => $this->_config->get("blog", "advanced_views", true),
				"posts-switching" => $this->_config->get("blog", "posts_switching", true)
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
		if (!$this->_user->hasPermission("blog.settings"))
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		elseif (!isset($values["editor"]) || !in_array($values["editor"], $this->_editors)) {
			return new Response(3, "warning", $this->_lang->get("core", "emptyFields"));
		} else {
			$this->_config->save("blog", [
				"editor" => $values["editor"],
				"rating_active" => isset($values["rating_active"]),
				"advanced_views" => isset($values["advanced_views"]),
				"posts_switching" => isset($values["posts_switching"])
			]);

			return new Response(0, "success", $this->_lang->get("page", "settings.success"));
		}
	}
}
