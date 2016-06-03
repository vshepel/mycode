<?php
/**
 * Bootstrap
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

class Bootstrap {
	/**
	 * @var array Path
	 */
	private $_path = array (
		"application", "libraries", "core"
	);

	/**
	 * @var array Bootstrap args
	 */
	private $_args = array();

	/**
	 * @var Registry Registry object
	 */
	private $_registry;

	/**
	 * Bootstrap constructor
	 * @param array $args Bootstrap args
	 */
	public function __construct(array $args) {
		try {
			$this->_args = $args;
			
			function errorHandler($errno, $errstr, $errfile, $errline) {
				if (E_RECOVERABLE_ERROR === $errno) {
					throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
				} elseif (defined("DEBUG")) {
					echo "<pre>" . $errstr . "\n\n";
					debug_print_backtrace();
					echo "</pre>";
				}
				return false;
			}
			
			set_error_handler('errorHandler');
			
			define ("FRONTEND", "frontend");
			define ("BACKEND", "backend");
			define ("DS", DIRECTORY_SEPARATOR);
			define ("DOT", ".");
			
			// Root Directiries
			define ("ROOT", dirname(__DIR__));
			define ("APP", ROOT . DS . "application");
			define ("DAT", ROOT . DS . "data");
			define ("CORE", ROOT . DS . "core");
			define ("LIB", ROOT . DS . "libraries");
			define ("PUB", ROOT . DS . "public");
			
			// Application Directories
			define ("VIEW", APP . DS . "view");
			define ("CON", APP . DS . "controller");
			define ("MOD", APP . DS . "model");
	
			// Data Directories
			define ("CACHE", DAT . DS . "cache");
			define ("CONF", DAT . DS . "config");
			define ("LANG", DAT . DS . "lang");
			define ("TMP", DAT . DS . "temp");
	
			if (isset($this->_args["debug"]) && $this->_args["debug"]) define ("DEBUG", true); // DEBUG
			if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") define("AJAX", true); // AJAX
			if (isset($this->_args["path"])) $this->_path = array_merge($this->_path, explode(":", $this->_args["path"])); // PATH

			$this->_autoload();
			$this->_init();

			// Start router
			$this->_registry->get("Router")->start();
		} catch(ErrorException $e) {
			$this->_exception("Error Exception", $e);
		} catch(Exception $e) {
			$this->_exception("Internal Exception", $e);
		}
	}

	/**
	 * Show exception
	 * @param string $title Exception title
	 * @param Exception $e
	 */
	private function _exception($title, Exception $e) {
		echo <<<HTML
<h1>{$title}</h1>
{$e->getMessage()}
<pre>{$e->getTraceAsString()}</pre>
HTML;
	}

	/**
	 * Autoload init
	 */
	private function _autoload() {
		spl_autoload_register(function ($className) {
			$classArray = explode("\\", strtolower($className));
			$classFile = false;

			// Search file
			foreach ($this->_path as $path) {
				$name = ROOT . DS . $path . DS . implode(DS, $classArray) . ".php";
				$lname = ROOT . DS . $path . DS . strtolower(implode(DS, $classArray)) . ".php";

				if (file_exists($name)) {
					$classFile = $name;
					break;
				} elseif (file_exists($lname)) {
					$classFile = $lname;
					break;
				}
			}

			// Require file
			if ($classFile !== false) {
				require_once($classFile);

				if (!class_exists($className)) {
					echo "<b>Class {$className} not exist in file {$classFile}</b><pre>";
					debug_print_backtrace();
					echo "</pre>";
					exit;
				}

				return true;
			} else
				throw new Exception("Class not found: " . $className);
		});
	}

	/**
	 * Init
	 * @throws Exception
	 */
	private function _init() {
		$this->_registry = Registry::getInstance();

		/**
		 * Add primary objects
		 */
		$config = $this->_registry
			->add("Config", new harmony\config\Config(CONF))
			->add("Lang", new harmony\lang\Lang(LANG))
			->add("Cache", new harmony\cache\Cache(CACHE))
			->get("Config");
			
		// Install if system not installed
		if ($config->get("core", "installed", false) === false) {
			$this->_registry->get("Cache")->enable(false); // Disabling cache
			$this->_path[] = "install"; // Adding install path
			new Install(); // Start Installation
			exit;
		}
			
		$this->_registry->get("Cache")->enable($config->get("core", "cache", true));

		// Define vars
		define("PATH", $config->get("site", "path"));
		define("SITE_PATH", PATH . ($config->get("core", "rewriteRoutes", true) ? "" : "index.php/"));
		define("FSITE_PATH", $config->get("site", "link") . SITE_PATH);
		define("ADMIN_PATH", SITE_PATH . "admin/");
		define("FADMIN_PATH", $config->get("site", "link") . ADMIN_PATH);
		define("DBPREFIX", $config->get("database", "prefix"));

		// Add other objects
		$this->_registry
			->add("Core", new Core($this->_args))
			->add("Database", harmony\database\DataBase::getInstance()->driver($config->get("database", "driver")))
			->add("User", new model\user\User())
			->add("Router", new Router())
			->add("View", harmony\view\View::getInstance()->parser($config->get("view", "parser", "HarmonyCMS")))
			->add("Captcha", harmony\captcha\Captcha::getInstance()->driver($config->get("captcha", "generator", "HarmonyCMS")))
			->add("SendMail", harmony\mail\Mail::getInstance()->driver($config->get("sendmail", "driver", "SMTP"), $config->get("sendmail", null, array())))
			->add("Menu", new model\core\Menu())
			->add("Notifications", new model\user\Notifications())
			->get("Database")
			->connect(
				$config->get("database", "host"),
				$config->get("database", "user"),
				$config->get("database", "password"),
				$config->get("database", "base")
			);
			
		// Set captcha font
		$this->_registry->get("Captcha")->setFont(LIB . DS . "harmony" . DS . "captcha" . DS . "captcha-font.ttf");

		// Display settings
		$this->_registry
			->get("Core")
			->setTimeZone($config->get("core", "timezone", 0))
			->addMetaArray(array (
				[
					"charset" => $config->get("site", "charset"),
				], [
					"name" => "description",
					"content" => $config->get("site", "description"),
				], [
					"name" => "keywords",
					"content" => $config->get("site", "keywords")
				]
			))
			->addCSS(PATH . "css/core.css")
			->addJS([
				PATH . "vendor/jquery.min.js",
				PATH . "js/core.js"
			])
			->addBreadcrumbs($config->get("site", "name"), "");
	
		// User initialization
		$this->_registry->get("User")->init();
	}
}

