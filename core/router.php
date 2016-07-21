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
	// Objects
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

	// Vars

	/**
	 * @var string Request string
	 */
	private $_request;

	/**
	 * @var array Routes
	 */
	private $_routes = array ();

	/**
	 * @var string Module name
	 */
	private $_module;

	/**
	 * @var string Action name
	 */
	private $_action;

	/**
	 * @var string Side type
	 */
	private $_type;

	/**
	 * @var null|object Controller object
	 */
	private $_object = null;

	// Methods

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
	 * Get controller object
	 * @return null|object
	 */
	public function getObject() {
		return $this->_object;
	}

	/**
	 * Get request
	 * @return string
	 */
	public function getRequest() {
		return $this->_request;
	}

	/**
	 * Check controller for exists
	 * @param string $module Module name
	 * @param string $type Side type
	 * @throws NotFoundException
     */
	public function existsController($module, $type) {
		$path = CON . DS . strtolower($type) . DS . $module . ".php";
		if (!file_exists($path)) throw new NotFoundException();
	}

	/**
	 * Start router
	 */
	public function start() {
		try {
			// Site disable
			if ($this->_config->get("site", "disabled", false) && !$this->_user->hasPermission("admin")) {
				$this->page("disabled", "HTTP/1.1 503 Service Temporarily Unavailable");
				return;
			}
			
			$this->_routes = array_slice(explode("/", $this->_request), 1);
			$type = ($this->get(0) == "admin") ? BACKEND : FRONTEND;

			if ($type == BACKEND) {
				if (!$this->_user->hasPermission("admin")) {
					$this->_type = FRONTEND;
					throw new NotFoundException();
				} else {
					Registry::getInstance()->get("Core")
						->setType($type)
						->addBreadcrumbs("ControlPanel", "");

					$this->_module = $this->get(1) ? $this->get(1) : $this->_config->get("core", "moduleBackend", "core");
					$this->_action = $this->get(2) ? $this->get(2) : null;
				}
			} else {
				$this->_module = $this->get(0) ? $this->get(0) : $this->_config->get("core", "moduleFrontend", "page");
				$this->_action = $this->get(1) ? $this->get(1) : null;
			}

			$this->_type = $type;
			define("SIDETYPE", $this->_type);

			// Custom routes
			$custom_action = null;
			foreach ($this->_config->get("core", "customRoutes", []) as $pattern => $route) {
				if (preg_match("~{$pattern}~", $this->_request, $matches)) {
					$routes = explode("/", $route);
					$this->_module = $routes[0];
					$custom_action = $routes[1];
					$this->_routes = array_slice($matches, 1);
					break;
				}
			}

			if ($custom_action !== null) {
				$this->_action = $custom_action;
			}

			$this->existsController($this->_module, $this->_type);

			$controller_class = "\\controller\\{$this->_type}\\" . $this->_module;
			$controller = new $controller_class;
			$this->_object = $controller;

			// Method routes
			if (is_array($controller->__routes) && $custom_action === null) {
				$method_action = null;

				foreach ($controller->__routes as $pattern => $action) {
					if (preg_match("~" . $this->_module . "/" . $pattern . "~", $this->_request, $matches)) {
						$method_action = ($action !== null) ? $action : $pattern;
						$this->_routes = array_slice($matches, 1);
						break;
					}
				}

				if ($method_action === null) {
					if ($this->_action === null) {
						if ($this->get(0) == $this->_module && $this->_config->get("core", "moduleFrontend") == $this->_module) {
							header("HTTP/1.1 301 Moved Permanently");
							header("Location: " . SITE_PATH);
							exit;
						}

						$this->_action = $controller->__default;
					} else {
						throw new NotFoundException();
					}
				} else {
					$this->_action = $method_action;
				}
			}
	
			$action = "action_" . (($this->_action === null) ? $controller->__default : $this->_action);
	
			if (method_exists($controller, $action)) {
				$controller->$action($this->_routes);
			} else {
				throw new NotFoundException();
			}
		} catch (NotFoundException $e) {
			$this->page("404", "HTTP/1.1 404 Not Found");
		}
	}

	/**
	 * Render page
	 * @param string $name Page name
	 * @throws Exception
	 */
	public function page($name, $header) {
		$this->_type = FRONTEND;
		header($header);
		Registry::getInstance()->get("View")->render(null, "main.page." . $name);
		exit;
	}
}
