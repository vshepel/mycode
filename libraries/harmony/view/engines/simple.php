<?php
/**
 * View Simple Engine class
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

class Simple extends Engine {
	/**
	 * @var string Template extension
	 */
	private $_extension = "tpl";

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
	 * @var string Side type
	 */
	private $_type = null;

	/**
	 * @var string Template view name
	 */
	private $_view;

	/**
	 * @var array Temp array
	 */
	private $_temp = array ();

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

			$this->_globalTags = array (
				"PATH" => PATH,
				"SITE_PATH" => SITE_PATH,
				"ADMIN_PATH" => ADMIN_PATH,
				"SELF" => $_SERVER["REQUEST_URI"],
				"VIEW" => $this->_config->get("site", "path") . "view/" . $this->_type . "/" . $this->_view . "/",
				"MODULE" => $this->_router->getModule(),
				"ACTION" => $this->_router->getAction(),
				"MODACT" => $this->_router->getModule() . "/" . $this->_router->getAction(),
				"logged" => $this->_user->isLogged(),
				"not-logged" => !$this->_user->isLogged()
			);
		}
	}
	
	private function _ifTag($view, $tags) {
		$view = preg_replace_callback("#\\[if ([A-Za-z0-9._-]+)(\\!=|=)\"(.*)\"\\](.*)\\[/if\\]#isU", function ($args) use ($tags) {
			if (!isset($tags[$args[1]]))
				return false;
	
			$check = ($tags[$args[1]] == $args[3]);
			$check = ($args[2] == "!=") ? !$check : $check;
	
			return $check ? $args[4] : "";
		}, $view);

		$view = preg_replace_callback("#\\[if ([A-Za-z0-9._-]+)\\:(.*)](.*)\\[/if\\]#isU", function ($args) use ($tags) {
			$func = $args[1];
			$arg = $args[2];
			$value = $args[3];

			switch ($func) {
				case "installed-module":
					$pkg = new Packages();
					return $pkg->exists($arg) ? $value : "";

				case "has-permission":
					$arg = preg_replace_callback("#\\{([A-Za-z0-9._-]+)\\}#i", function ($args) use ($tags) {
						return isset($tags[$args[1]]) ? $tags[$args[1]] : "";
					}, $arg);

					return $this->_user->hasPermission($arg) ? $value : "";
			}

			return "";
		}, $view);

		return $view;
	}

	private $_models = [];

	private function _propertyTag($tag) {
		return preg_replace_callback([
			"#\\{([A-Za-z0-9._-]+):([A-Za-z0-9._-]+)\\}#i",
			"#\\{([A-Za-z0-9._-]+):([A-Za-z0-9._-]+):(.*)\\}#i"
		], function ($args) {
			try {
				$name = $args[1];
				$cc = null;

				if (isset($this->_models[$name])) {
					$cc = $this->_models[$name];
				} else {
					if ($this->_router->getType() == FRONTEND && $this->_router->getModule() == $name) {
						$cc = $this->_router->getObject();
					}

					if ($cc === null) {
						$this->_router->existsController($name, "frontend");
						$cname = "controller\\frontend\\" . $name;
						$cc = new $cname;
					}

					$this->_models[$name] = $cc;
				}

				return $cc->getProperty($args[2], (isset($args[3]) ? StringConverters::toArray($args[3]) : []));
			} catch (NotFoundException $e) {
				return "Module '{$args[1]}' not found";
			}
		}, $tag);
	}

	private function _tag($tag, $value, $view) {
		if (is_bool($value)) {
			if ($value === true) {
				$view = str_replace("[{$tag}]", "", $view);
				return str_replace("[/{$tag}]", "", $view);
			} else {
				return preg_replace("#\\[({$tag})\\](.*?)\\[/({$tag})\\]#is", "", $view);
			}
		} elseif (is_array($value)) {
			return preg_replace_callback("#\\[(foreach) ({$tag})\\](.*?)\\[/(foreach)\\]#is", function ($args) use ($value) {
				$rowTemplate = $args[3];
				$content = "";

				foreach ($value as $row) {
					$rowContent = $this->_ifTag($rowTemplate, $row);

					foreach ($row as $rowName => $rowValue) {
						$rowContent = $this->_tag($rowName, $rowValue, $rowContent);
					}

					$content .= $rowContent;
				}

				return $content;
			}, $view);
		} else {
			$value = preg_replace("#\\{([A-Za-z0-9._-]+)\\}#i", "&#123;$1&#125;", $value);
			return str_replace("{" . $tag . "}", $value, $view);
		}
	}

	public function parse($name, $tags = [], $only_file = false) {
		$this->_init();

		if (!isset($this->_temp[$name])) {
			$cpath = "view." . $this->_type . DOT . $this->_view . DOT . LOCALE;
			
			// Check cache if enabled
			$view_cache = $this->_config->get("view", "cache", false);
			$view = $view_cache ? $this->_cache->get($cpath, $name) : false;

			// Load file and cache
			if (!$view) {
				$file = VIEW . DS . $this->_type . DS . $this->_view . DS . "simple" . DS .  $name . "." . $this->_extension;

				if (!file_exists($file)) {
					if (defined("DEBUG")) {
						throw new Exception("Couldn't find view: " . $name . ", on location:  " . $file);
					}
					
					return "Couldn't find view: " . $name . ", on location:  " . $file;
				}

				$view = file_get_contents($file);

				// Compress HTML code
				if ($this->_config->get("view", "compress", false))
					$view = StringConverters::htmlCompress($view);
				
				// Parse language string
				$view = preg_replace_callback("#\\[([bf]):([A-Za-z0-9._-]+):([A-Za-z0-9._-]+)\\]#i", function ($args) {
					if ($args[1] == "b")
						return $this->_lang->get($args[2], $args[3]);
					elseif ($args[1] == "f")
						return $this->_flang->get($args[2], $args[3]);
					else
						return "{{$args[1]}:{$args[2]}:{$args[3]}}";
				}, $view);
				
				// Include other template
				$view = preg_replace_callback("#\\[(include) ['\"]([A-Za-z0-9._-]+)['\"]\\]#i", function ($args) {
					return $this->parse($args[2], [], true);
				}, $view);
				
				// Push cache
				if ($view_cache) $this->_cache->push($cpath, $name, $view);
			}
			
			if ($only_file) return $view;

			foreach ($this->_globalTags as $tag => $value) {
				$view = $this->_tag($tag, $value, $view);
			}

			$this->_temp[$name] = $view;
		} else {
			$view = $this->_temp[$name];
		}
		
		foreach ($tags as $tag => $value) {
			if (is_array($value)) {
				$view = $this->_tag($tag, $value, $view);
			}
		}

		foreach ($tags as $tag => $value) {
			if (!is_array($value)) {
				$view = $this->_tag($tag, $value, $view);
			}
		}
		
		$view = $this->_ifTag($view, array_merge($this->_globalTags, $tags));
		$view = $this->_propertyTag($view);

		return $view;
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

		$tags = array (
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
		);

		if ($this->_user->isLogged()) {
			$notifications = Registry::getInstance()->get("Notifications")->get();
			
			$tags["notifications"] = $notifications->tags["page-rows"];
			$tags["new-notifications"] = ($notifications->tags["num"] > 0);
			
			$tags["username"] = $this->_user->get("login");
			$tags["avatar-link"] = $this->_user->getAvatarLink($this->_user->get("avatar"));

			$tags["profile-link"] = $path . "user/profile/" . $this->_user->get("login");
			$tags["notifications-link"] = $path . "user/notifications";
			$tags["messages-link"] = $path . "messages";
			$tags["logout-link"] = $path . "user/logout";

			$tags["group-id"] = $this->_user->get("group");
			$tags["group-name"] = $this->_user->getGroupName($this->_user->get("group"));

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
