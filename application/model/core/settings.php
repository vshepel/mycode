<?php
/**
 * Core Settings Model
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

class Settings extends AppModel {
	/**
	 * Get settings page
	 * @param string $action Action
	 * @return Response
	 */
	public function getPage($action) {		
		$response = new Response();

		$this->_core->addBreadcrumbs($this->_lang->get("core", "settings.moduleName"), "core/settings");

		// Access denied
		if (!$this->_user->hasPermission("core.settings")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}
		
		switch ($action) {			
			case "view":
				// Frontend Views
				$fviews = "";
				$active = $this->_config->get("view", "frontend", "default");
				foreach(scandir(VIEW . DS . FRONTEND) as $view) {
					if (!in_array($view, [".", ".."])) {
						$fviews .= $this->_view->parse("core.settings.selector", [
							"name" => $view,
							"value" => $view,
							"active" => ($view == $active)
						]);
					}
				}
				
				// Backend Views
				$bviews = "";
				$active = $this->_config->get("view", "backend", "default");
				foreach(scandir(VIEW . DS . BACKEND) as $view) {
					if (!in_array($view, [".", ".."])) {
						$bviews .= $this->_view->parse("core.settings.selector", [
							"name" => $view,
							"value" => $view,

							"active" => ($view == $active)
						]);
					}
				}
				
				$response->view = "core.settings.view";
				$response->tags = [
					"frontend" => $this->_config->get("view", "frontend", "default"),
					"frontend-views" => $fviews,
					"backend" => $this->_config->get("view", "backend", "default"),
					"backend-views" => $bviews,
					"cache" => $this->_config->get("view", "cache"),
					"compress" => $this->_config->get("view", "compress")
				];
			break;
				
  			case "site":
  				// Languages
				$languages = "";
				$active = $this->_config->get("site", "language");
				foreach($this->_lang->getLangs() as $lang => $name) {
					$languages .= $this->_view->parse("core.settings.selector", [
						"name" => $name,
						"value" => $lang,
						"active" => ($lang == $active)
					]);
				}
				
				$response->view = "core.settings.site";
				$response->tags = [
					"link" => $this->_config->get("site", "link"),
					"path" => $this->_config->get("site", "path"),
					"name" => $this->_config->get("site", "name"),
					"description" => $this->_config->get("site", "description"),
					"keywords" => $this->_config->get("site", "keywords"),
					"charset" => $this->_config->get("site", "charset"),
					"language" => $languages
				];
			break;

			case "sendmail":
				$response->view = "core.settings.sendmail";

				// Drivers
				$drivers = "";
				$active = $this->_config->get("sendmail", "driver");
				foreach(["SMTP" => "SMTP"] as $driver => $name) {
					$drivers .= $this->_view->parse("core.settings.selector", [
						"name" => $name,
						"value" => $driver,
						"active" => ($driver == $active)
					]);
				}
				$response->tags["drivers"] = $drivers;

				// Driver: SMTP
				$cfg = $this->_config->get("sendmail", "driver_SMTP", []);

				if (!isset($cfg["name"])) $cfg["name"] = "";
				if (!isset($cfg["user"])) $cfg["user"] = "";
				if (!isset($cfg["password"])) $cfg["password"] = "";
				if (!isset($cfg["host"])) $cfg["host"] = "";
				if (!isset($cfg["port"])) $cfg["port"] = "";

				foreach($cfg as $name => $value) {
					$response->tags["smtp-" . $name] = $value;
				}
			break;
			
			case "main":
			default:
				// Frontend modules
				$active =  $this->_config->get("core", "moduleFrontend");
				$frontend = "";
				foreach (scandir(CON . DS . "frontend") as $var) if (!in_array($var, [".", ".."])) {
					$name = str_replace([".php", CON . DS], "", $var);
					$mname = $this->_lang->get($name, "moduleName");
					$frontend .= $this->_view->parse("core.settings.selector", [
						"name" => empty($mname) ? $name : ($mname . " ({$name})"),
						"value" => $name,
						"active" => ($name == $active)
					]);
				}
				
				// Backend modules
				$active =  $this->_config->get("core", "moduleBackend");
				$backend = "";
				foreach (scandir(CON . DS . "backend") as $var) if (!in_array($var, [".", ".."])) {
					$name = str_replace([".php", CON . DS], "", $var);
					$mname = $this->_lang->get($name, "moduleName");
					$backend .= $this->_view->parse("core.settings.selector", [
						"name" => empty($mname) ? $name : ($mname . " ({$name})"),
						"value" => $name,
						"active" => ($name == $active)
					]);
				}
				
				$action = "main";
				$response->view = "core.settings.main";
				$response->tags = [
					"module-frontend" => $frontend,
					"module-backend" => $backend,
					"format-date" => $this->_config->get("core", "format.date"),
					"format-time" => $this->_config->get("core", "format.time"),
					
					"cache" => $this->_config->get("core", "cache"),
					"smart-date" => $this->_config->get("core", "smartDate"),
					"rewrite-routes" => $this->_config->get("core", "rewriteRoutes")
				];
			break;
		}
			
		$this->_core->addBreadcrumbs($this->_lang->get("core", "settings.{$action}.moduleName"), "core/settings/" . $action);
		$response->tags["action"] = $action;

		return $response;
	}

	/**
	 * Save settings
	 * @param string $action Action
	 * @param array $values Setting values
	 * @return Response
	 */
	public function save($action, $values) {
		$response = new Response();

		if (!$this->_user->hasPermission("core.settings")) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			switch ($action) {
				case "site":
					if (
						isset($values["link"], $values["path"], $values["name"], $values["description"], $values["keywords"], $values["charset"], $values["language"]) &&
						(!empty($values["link"]) && !empty($values["path"]) && !empty($values["name"]) && !empty($values["description"]) && !empty($values["keywords"]) && !empty($values["charset"]) && !empty($values["language"]))
					) {
						$this->_config->save("site", [
							"link" => $values["link"],
							"path" => $values["path"],
							"name" => $values["name"],
							"description" => $values["description"],
							"keywords" => $values["keywords"],
							"charset" => $values["charset"],
							"language" => $values["language"]
						]);
							
						$response = new Response(0, "success", $this->_lang->get("core", "settings.success"));
					} else
						$response = new Response(3, "warning", $this->_lang->get("core", "emptyFields"));
				break;
				
				case "view":
					if (
						isset($values["frontend"], $values["backend"]) &&
						(!empty($values["frontend"]) && !empty($values["backend"]))
					) {
						$this->_config->save("view", [
							"frontend" => $values["frontend"],
							"backend" => $values["backend"],
								
							"cache" => isset($values["cache"]),
							"compress" => isset($values["compress"])
						]);
							
						$response = new Response(0, "success", $this->_lang->get("core", "settings.success"));
					} else
						$response = new Response(3, "warning", $this->_lang->get("core", "emptyFields"));
				break;

				case "sendmail":
					$this->_config->save("sendmail", $values);
					$response = new Response(0, "success", $this->_lang->get("core", "settings.success"));
				break;
				
				case "main":
				default:
					if (
						isset($values["module-frontend"], $values["module-backend"], $values["format-date"], $values["format-time"]) &&
						(!empty($values["module-frontend"]) && !empty($values["module-backend"]) && !empty($values["format-date"]) && !empty($values["format-time"]))
					) {
						$this->_config->save("core", [
							"moduleFrontend" => $values["module-frontend"],
							"moduleBackend" => $values["module-backend"],
							"format.date" => $values["format-date"],
							"format.time" => $values["format-time"],
								
							"cache" => isset($values["cache"]),
							"smartDate" => isset($values["smart-date"]),
							"rewriteRoutes" => isset($values["rewrite-routes"])
						]);
							
						$response = new Response(0, "success", $this->_lang->get("core", "settings.success"));
					} else
						$response = new Response(3, "warning", $this->_lang->get("core", "emptyFields"));
				break;
			}
		}

		$this->_cache->clear();

		return $response;
	}
}
