<?php
/**
 * Router class
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

class Router {
	/**
	 * @var object Config object
	 */
	private $_config;

	/**
	 * @var object Lang object
	 */
	private $_lang;

	/**
	 * @var object User object
	 */
	private $_user;

	/**
	 * Vars
	 */

	/**
	 * @var string Request string
	 */
	private $_request;

	/**
	 * @var array Routes
	 */
	private $_routes = array ();

	/**
	 * @var string Side type
	 */
	private $_type;

	/**
	 * @var string Module name
	 */
	private $_module;

	/**
	 * @var string Action name
	 */
	private $_action;

	/**
	 * Methods
	 */

	/**
	 * Constructor
	 * @throws \Exception
	 */
	public function __construct() {
		$registry = Registry::getInstance();

		$this->_config = $registry->get("Config");
		$this->_lang = $registry->get("Lang");
		$this->_user = $registry->get("User");
		
		// Request
		$req = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "";
		$pos = strpos($req, "index.php");
		if ($pos) $req = substr($req, $pos+9, strlen($req));
		$this->_request = $req;
		
		// GET query
		$pos = strpos($req, "?");
		$get = [];
		if ($pos) {
			parse_str(substr($req, $pos+1, strlen($req)), $_GET);
			$this->_request = substr($req, 0, $pos);
		}
	}

	/**
	 * Get Route by id
	 * @param int $id Route ID
	 * @return false|string Route content
	 */
	public function get($id) {
		return isset($this->_routes[$id]) ? $this->_routes[$id] : false;
	}

	/**
	 * Get Module name
	 * @return string Module name
	 */
	public function getModule() {
		return $this->_module;
	}

	/**
	 * Get Action name
	 * @return string Action name
	 */
	public function getAction() {
		return $this->_action;
	}

	/**
	 * Get Side type
	 * @return string Get side type
	 */
	public function getType() {
		return $this->_type;
	}

	/**
	 * @param array $routes
	 * @param string $default
	 */
	private function _customRoutes($routes, $default) {
		$this->_action = $default;

		foreach ($routes as $pattern => $action) {
			if (preg_match("~" . $this->_module . "/" . $pattern . "~", $this->_request, $matches)) {
				$this->_action = ($action !== null) ? $action : $pattern;
				$this->_routes = array_slice($matches, 1);
				break;
			}
		}
	}

	private function _check($module, $type) {
		$path = CON . DS . strtolower($type) . DS . $module . ".php";
		if (!file_exists($path)) throw new NotFoundException();
	}

	/**
	 * Initialization
	 * @throws \Exception
	 */
	private function _init() {
		$this->_routes = array_slice(explode("/", $this->_request), 1);
		$this->_module = $this->get(0) ? $this->get(0) : $this->_config->get("core", "moduleFrontend", "page");
		$this->_action = $this->get(1) ? $this->get(1) : null;

		$type = ($this->get(0) == "admin") ? BACKEND : FRONTEND;

		if ($type == BACKEND)
			if (!$this->_user->hasPermission("admin")) {
				$this->_type = FRONTEND;
				$this->page_404();
			} else {
				Registry::getInstance()
					->get("Core")
					->setType($type)
					->addBreadcrumbs("ControlPanel", "");

				$this->_module = $this->get(1) ? $this->get(1) : $this->_config->get("core", "moduleBackend", "core");
				$this->_action = $this->get(2) ? $this->get(2) : null;
			}

		$this->_type = $type;
		
		define("SIDETYPE", $this->_type);
	}

	/**
	 * Start router
	 */
	public function start() {
		try {
			$this->_init();
			$this->_check($this->_module, $this->_type);
	
			$controller_class = "\\controller\\{$this->_type}\\" . $this->_module;
			$controller = new $controller_class;
	
			if (is_array($controller->__routes))
				$this->_customRoutes($controller->__routes, $controller->__default);
	
			$action = "action_" . (($this->_action === null) ? $controller->__default : $this->_action);
	
			if (method_exists($controller, $action)) {
				$controller->$action($this->_routes);
			} else
				throw new NotFoundException();
		} catch (NotFoundException $e) {
			$this->page_404();
		}
	}

	/**
	 * Render 404 page
	 */
	public function page_404() {
		header("HTTP/1.1 404 Not Found");
		Registry::getInstance()->get("View")->render(null, "404");
		exit;
	}
}
