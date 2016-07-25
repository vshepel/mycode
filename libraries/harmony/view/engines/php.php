<?php
/**
 * View PHP Engine class
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

namespace harmony\view\engines;

use Exception;
use model\core\Packages;
use NotFoundException;
use Registry;

use harmony\strings\StringConverters;
use harmony\view\Engine;
use harmony\lang\Lang;

class PHP extends Engine {
	/**
	 * @var object Core object
	 */
	private $_core;

	/**
	 * @var object Config object
	 */
	private $_config;

	/**
	 * @var object Lang object
	 */
	private $_lang;

	/**
	 * @var Lang Frontend language object
	 */
	private $_flang;
	
	/**
	 * @var object Cache object
	 */
	private $_cache;

	/**
	 * @var object Router object
	 */
	private $_router;

	/**
	 * @var object User object
	 */
	private $_user;

	/**
	 * @var Packages Packages object
	 */
	private $_pkg;

	/**
	 * @var string Side type
	 */
	private $_type = null;

	/**
	 * @var string Template view name
	 */
	private $_view;

	/**
	 * Construct
	 * @throws \Exception
	 */
	public function __construct () {
		$registry = Registry::getInstance();

		$this->_core = $registry->get("Core");
		$this->_config = $registry->get("Config");
		$this->_lang = $registry->get("Lang");
		$this->_flang = $registry->get("Lang");
		$this->_cache = $registry->get("Cache");
		$this->_router = $registry->get("Router");
		$this->_user = $registry->get("User");

		$this->_stack["alert"] = "";
	}

	public function _init() {
		if ($this->_type === null) {
			$this->_type = strtolower($this->_router->getType());
			$this->_view = $this->_config->get("view", strtolower($this->_type), "default");

			$this->_flang = new Lang(VIEW . DS . $this->_type . DS . strtolower($this->_view) . DS . "lang");
			$this->_flang->setLang($this->_lang->getLang());

			define("SELF_PATH", $_SERVER["REQUEST_URI"]);
			define("VIEW_PATH", $this->_config->get("site", "path") . "view/" . $this->_type . "/" . $this->_view . "/");
			define("MODULE", $this->_router->getModule());
			define("ACTION", $this->_router->getAction());
			define("MODACT", $this->_router->getModule() . "/" . $this->_router->getAction());
			define("LOGGED", $this->_user->isLogged());

			$this->_pkg = new Packages();
		}
	}

	private $_models = [];
	
	public function _lang($module, $name) {
		return $this->_lang->get($module, $name);
	}

	public function _flang($module, $name) {
		return $this->_flang->get($module, $name);
	}

	public function _getProperty($module, $name, $args = []) {
		$cc = null;

		if (isset($this->_models[$module])) {
			$cc = $this->_models[$module];
		} else {
			if ($this->_router->getType() == FRONTEND && $this->_router->getModule() == $name) {
				$cc = $this->_router->getObject();
			}

			if ($cc === null) {
				try {
					$this->_router->existsController($module, "frontend");
					$cname = "controller\\frontend\\" . $module;
					$cc = new $cname;
				} catch (NotFoundException $e) {
					return "Module '{$module}' not found";
				}
			}

			$this->_models[$module] = $cc;
		}

		return $cc->getProperty($name, $args);
	}

	public function parse($name, $tags = []) {
		$this->_init();

		$file = VIEW . DS . $this->_type . DS . $this->_view . DS . "php" . DS .  $name . ".php";

		if (!file_exists($file)) {
			return "Couldn't find view: " . $name . ", on location:  " . $file;
		}

		$tags = array_merge($this->_globalTags, $tags);

		ob_start();
		include $file;
		return ob_get_clean();
	}

	public function alert($type, $message) {
		$this->add("alert", array (
			"type" => $type,
			"message" => $message
		));

		return $this;
	}

	public function getAlert($type, $message) {
		return $this->parse("alert", array (
			"type" => $type,
			"message" => $message
		));
	}

	public function render($stack = null, $mainView = null) {
		if ($mainView === null) {
			$mainView = "main";
		}

		$path = SITE_PATH;
		$loadingLayer = $this->_lang->get("core", "ajax.loadingLayer");
		$unknownError = $this->_lang->get("core", "ajax.unknownError");

		$ajax = <<<HTML
<div id="alerts"></div><div id="loading-layer"></div>

<script type="text/javascript">
!function(app, lang) {
	app.core.path = '{$path}';
	lang.loadingLayerText = '{$loadingLayer}';
	lang.unknownError = '{$unknownError}';
}(app, lang);
</script>
HTML;

		// Languages
		$languages = [];
		$active = $this->_lang->getLang();
		foreach ($this->_lang->getLangs() as $lang => $name) {
			$languages[] = [
				"name" => $name,
				"value" => $lang,
				"active" => ($lang == $active)
			];
		}

		$tags = [
			"title" => $this->_core->getTitle(),
			"description" => $this->_config->get("site", "description", "New site"),
			"keywords" => $this->_config->get("site", "keywords", "HarmonyCMS, CMS, Site"),

			"name" => $this->_config->get("site", "name", "HarmonyCMS Site"),

			"meta" => $this->_core->getMeta(),
			"link" => $this->_core->getLink(),
			"script" => $this->_core->getScript(),
			"ajax" => $ajax,

			"menu" => Registry::getInstance()
				->get("Menu")
				->get($this->_type),

			"breadcrumbs" => $this->_core->getBreadcrumbs(),
			"alerts" => $this->_stack["alert"],
			"content" => isset($this->_stack[$stack]) ? $this->_stack[$stack] : "",
			"languages" => $languages
		];

		if ($this->_user->isLogged()) {
			$notifications = Registry::getInstance()->get("Notifications")->get();
			
			$tags["notifications"] = $notifications->tags["page-rows"];
			$tags["new-notifications"] = ($notifications->tags["num"] > 0);
			
			$tags["profile-link"] = $path . "user/profile/" . $this->_user->get("login");
			$tags["notifications-link"] = $path . "user/notifications";
			$tags["messages-link"] = $path . "messages";
			$tags["logout-link"] = $path . "user/logout";

			if ($this->_user->hasPermission("admin")) {
				$tags["admin"] = true;
				$tags["admin-link"] = ADMIN_PATH;
			} else
				$tags["admin"] = false;
		} else {
			$tags["auth-link"] = $path . "user/auth";
			$tags["register-link"] = $path . "user/register";
		}

		$view = $this->parse($mainView, array_merge($tags, $this->_mainTags));
		
		// Compress HTML code
		if ($this->_config->get("view", "compress", false))
			echo StringConverters::htmlCompress($view);
		else
			echo $view; 
	}
	
	public function responseRender($response) {
		if ($response->code == 0)
			$this
				->add($response->view, $response->tags)
				->render($response->view, $response->layout);
		else
			$this->add("alert.page", array (
				"type" => $response->type,
				"message" => $response->message,
			))->render("alert.page");
	}
}
