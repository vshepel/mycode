<?php
/**
 * Core Modules Model
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
use Exception;
use ZipArchive;

use harmony\strings\Strings;
use harmony\files\UploadFiles;
use harmony\files\Files;

class Packages extends AppModel {
	private $_packages = null;
	
	private function _init() {
		$this->_packages = $this->_cache->get("core.packages", $this->_lang->getLang());
		
		if (!$this->_packages) {
			$lang = $this->_lang->getLang();
			$dir = DAT . DS . "meta";
	
			foreach (scandir($dir) as $file) {
				$fname = $dir . DS . $file;
				$name = str_replace([DAT . DS . "meta" . DS, ".ini"], "", $fname);

				if (is_file($fname) && $name[0] != ".") {
					$ini = parse_ini_file($fname);
					if (isset($ini["package.name"])) $name = $ini["package.name"];
	
					// Make meta array
					$meta = [
						"meta-name" => $fname,
						"name" => $name,
						"type" => isset($ini["package.type"]) ? $ini["package.type"] : "",
						"version" => isset($ini["package.version"]) ? $ini["package.version"] : "0",
						
						"required-min" => isset($ini["package.required.min"]) ? $ini["package.required.min"] : "",
						"required-max" => isset($ini["package.required.max"]) ? $ini["package.required.max"] : "",
						"dependence" => (isset($ini["package.dependence"]) && !empty($ini["package.dependence"])) ? explode(",", $ini["package.dependence"]) : [],
						
						"description" => (isset($ini["package.description." . $lang]) ? $ini["package.description." . $lang] :
							(isset($ini["package.description"]) ? $ini["package.description"] : "")
						),
						
						"backend-image" => isset($ini["backend.image"]) ? $ini["backend.image"] : "",
						"backend-link" => isset($ini["backend.link"]) ? ADMIN_PATH . $ini["backend.link"] : "",
						
						"backend-title" => (isset($ini["backend.title." . $lang]) ? $ini["backend.title." . $lang] :
							(isset($ini["backend.title"]) ? $ini["backend.title"] : "")
						),
						"backend-description" => (isset($ini["backend.description." . $lang]) ? $ini["backend.description." . $lang] :
							(isset($ini["backend.description"]) ? $ini["backend.description"] : "")
						),
	
						"frontend-link" => isset($ini["frontend.link"]) ?  SITE_PATH . $ini["frontend.link"] : "",
						"db-tables" => isset($ini["db.tables"]) ? $ini["db.tables"] : "",
						
						"license" => isset($ini["license"]) ? $ini["license"] : "",
						"author" => isset($ini["author"]) ? $ini["author"] : "",
							
						"files" => isset($ini["files"]) ? (empty($ini["files"]) ? [] : explode(";", $ini["files"])) : [],
					];
	
					// Version compare
					$meta["version-compare"] = (
						isset($meta["required-min"]) && isset($meta["required-max"]) &&
						version_compare(VERSION, $meta["required-min"], '>=') &&
						version_compare(VERSION, $meta["required-max"], '<=')
					);
					
					// Save in array
					if (isset($this->_packages[$name])) {
						$this->_packages[] = $meta;
					} else {
						$this->_packages[$name] = $meta;
					}
				}
			}
			
			// Dependences
			foreach($this->_packages as &$meta) {
				$meta["dependent"] = (count($this->checkDependent($meta["name"])) > 0);
				$meta["conflict"] = (count($this->checkDependences($meta["dependence"])) > 0);
				$meta["unused"] = ($meta["type"] == "library" && count($this->checkDependent($meta["name"])) == 0);
			}
			
			$this->_cache->push("core.packages", $this->_lang->getLang(), $this->_packages);
		}
	}
	
	public function get($module_name = null) {
		if ($this->_packages === null) {
			$this->_init();
		}
		
		if ($module_name === null)
			return $this->_packages; // Return array of packages
		elseif ($this->exists($module_name))
			return $this->_packages[$module_name]; // Return module
		else
			return false; // Module not exist
	}

	/**
	 * Check package dependent
	 * @param string $name Package name
	 * @param bool $onlyName Get only package name without description
	 * @return array Array of dependencies
	 */
	public function checkDependent($name, $onlyName = true) {
		$dep = [];
		foreach ($this->get() as $pkg) {
			if (in_array($name, $pkg["dependence"])) {
				$dep[] = $onlyName ? $pkg["name"] : ($pkg["description"] . " ({$pkg["name"]})");
			}
		}
		
		return $dep;
	}
	
	/**
	 * Check package dependences
	 * @param array $dependence Package dependence
	 * @return array Array of needled denendences
	 */
	public function checkDependences($dependence) {
		$dep = [];
		foreach ($dependence as $pkg) {
			$installed = false;
			foreach($this->get() as $meta) {
				if ($pkg == $meta["name"]) {
					$installed = true;
					break;
				}
			}

			if (!$installed) {
				$dep[] = $pkg;
			}
		}
		
		return $dep;
	}
	
	/**
	 * Check module for exists
	 * @param string $name Module name
	 * @return bool
	 */
	public function exists($name) {
		foreach($this->get() as $mname => $meta)
			if ($mname == $name)
				return true;

		return false;
	}
	
	/**
	 * Get list page
	 * @return Response
	 */
	public function listPage() {
		$response = new Response();

		$this->_core->addBreadcrumbs($this->_lang->get("core", "packages.moduleName"), "core/packages");

		// Access denied
		if (!$this->_user->hasPermission("core.packages.list")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}
		
		$response->view = "core.packages.list";

		$num = 0;
		$rows = [];

		foreach ($this->get() as $row) {
			// Dependences
			foreach ($row["dependence"] as &$dep) {
				$dep = $this->get($dep)["description"] . " ({$dep})";
			}

			$row["files"] = Strings::lineWrap(implode("\n", $row["files"]));
			
			$row["dependence"] = Strings::lineWrap(implode("\n", $row["dependence"]));
			
			$row["remove"] = $this->_user->hasPermission("core.packages.remove");
			$row["remove-link"] = ADMIN_PATH . "core/packages/remove/" . $row["name"];
			$num++;
			
			$rows[] = $row;
		}

		$response->tags = array (
			"action" => "list",
			"num" => $num,
			"rows" => $rows
		);

		return $response;
	}
	
	/**
	 * Get install page
	 * @return Response
	 */
	public function installPage() {
		$response = new Response();

		$this->_core
			->addBreadcrumbs($this->_lang->get("core", "packages.moduleName"), "core/packages")
			->addBreadcrumbs($this->_lang->get("core", "packages.install.moduleName"), "core/packages/install");

		// Access denied
		if (!$this->_user->hasPermission("core.packages.install")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}
		
		$response->view = "core.packages.install";
		$response->tags = array (
			"action" => "install",
		);

		return $response;
	}
	
	private $_files = [];
	
	private function _move($from, $to) {
		if (file_exists($from)) {
			$this->_files = array_merge($this->_files,
				Files::copy($from, $to, true)
			);
		
			Files::delete($from);
		}
	}

	/**
	 * Upload and Install module
	 * @param array $file \$_FILE item array
	 * @return Response
	 */
	public function uploadAndInstall($file) {
		// Access denied
		if (!$this->_user->hasPermission("core.packages.install")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}
		
		// Upload file
		$files = new UploadFiles(TMP);
		$upload = $files->upload($file, "core.packages.install", "package.zip");
		
		if ($upload->code != 0)
			return $upload;
		else
			return $this->install();
	}
	
	/**
	 * Install module
	 * @param string $file = null File path
	 * @return Response
	 */
	public function install($file = null) {		
		// Set default file path
		if ($file === null) $file = TMP . DS . "core.packages.install" . DS . "package.zip";

		// Exctract file
		$dir = TMP . DS . "core.packages.install" . DS . "files";
		Files::delete($dir); // Delete old files
		Files::mkdir($dir);

		try {
			$zip = new ZipArchive;
			$zip->open($file);
			$zip->extractTo($dir);
		} catch (Exception $e) {
			return new Response(9, "danger", $e->getMessage());
		}
		
		// Parse INI file
		if (is_file($dir . DS . "install.ini") && $ini_file = file_get_contents($dir . DS . "install.ini")) {
			$ini = parse_ini_string($ini_file); // Parse INI
			
			// Package info
			$name = $ini["package.name"];
			$compare_min = version_compare(VERSION, $ini["package.required.min"], '>=');
			$compare_max = version_compare(VERSION, $ini["package.required.max"], '<=');
			$dependence = empty($ini["package.dependence"]) ? [] : explode(",", $ini["package.dependence"]);
			
			// Module is already installed
			if ($this->exists($name))
				return new Response(11, "warning", $this->_lang->get("core", "packages.install.alreadyInstalled"));
			
			// Version compare
			if (!$compare_min || !$compare_max)
				return new Response(12, "danger", $this->_lang->get("core", "packages.install.notCompare"));
			
			// Check dependences 
			if (count($dependence) > 0) {
				$dep = $this->checkDependences($dependence);

				if (count($dep) > 0) {
					return new Response(13, "danger", $this->_lang->get("core", "packages.install.depends") . ": <ul><li>" . implode("</li><li>", $dep) . "</li></ul>");
				}
			}
		
			// Frontend controller
			$this->_move(
				$dir . DS . "controller" . DS . "frontend.php",
				CON . DS . "frontend" . DS . $name . ".php"
			);
		
			// Backend controller
			$this->_move(
				$dir . DS . "controller" . DS . "backend.php",
				CON . DS . "backend" . DS . $name . ".php"
			);
		
			// Models
			if (is_dir($dir . DS . "model")) {
				$this->_move($dir . DS . "model", MOD . DS . $name);
			}
		
			// Langs
			if (is_dir($dir . DS . "lang")) foreach(scandir($dir . DS . "lang") as $file) {
				if (!in_array($file, [".", ".."])) $this->_move(
					$dir . DS . "lang" . DS . $file,
					LANG . DS . str_replace(".ini", "", $file) . DS . $name . ".ini"
				);
			}
		
			$bview = "default";
			// Backend view	
			$this->_move(
				$dir . DS . "view" . DS . "backend",
				VIEW . DS . "backend" . DS . $bview . DS . "tpl"
			);
		
			// Backend view langs
			if (is_dir($dir . DS . "view-lang" . DS . "backend")) foreach(scandir($dir . DS . "view-lang" . DS . "backend") as $file) {
				if (!in_array($file, [".", ".."])) $this->_move(
					$dir . DS . "view-lang" . DS . "backend" . DS . $file,
					VIEW . DS . "backend" . DS . $bview . DS . "lang" . DS . str_replace(".ini", "", $file) . DS . $name . ".ini"
				);
			}
		
			// Frontend view
			$view = "default";
			$this->_move(
				$dir . DS . "view" . DS . "frontend",
				VIEW . DS . "frontend" . DS . $view . DS . "tpl"
			);
		
			// Frontend view langs
			if (is_dir($dir . DS . "view-lang" . DS . "frontend")) foreach(scandir($dir . DS . "view-lang" . DS . "frontend") as $file) {
				if (!in_array($file, [".", ".."])) $this->_move(
					$dir . DS . "view-lang" . DS . "frontend" . DS . $file,
					VIEW . DS . "frontend" . DS . $bview . DS . "lang" . DS . str_replace(".ini", "", $file) . DS . $name . ".ini"
				);
			}
			
			// Libraries
			$this->_move($dir . DS . "libraries", LIB);
		
			// Other files
			$this->_move($dir . DS . "files", ROOT);
			
			// Copy module image
			$this->_move(
				$dir . DS . "image.png",
				PUB . DS . "images" . DS . "modules" . DS . $name . ".png"
			);
			
			// Database query
			if (is_file($dir . DS . "install.sql") && $sql = file_get_contents($dir . DS . "install.sql")) {
				foreach (explode(";", $sql) as $query) {
					$query = str_replace("{PREFIX}", DBPREFIX, $query);
					if (!empty($query) && !$this->_db->query($query)->result()) {
						return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
					}
				}
			}
			
			// Menu add links
			if (is_file($dir . DS . "menu.json") && $menu = file_get_contents($dir . DS . "menu.json")) {
				$model = new Menu();
				foreach (json_decode($menu) as $row) {
					$model->add($row[0], null, $row[1], SITE_PATH . $row[2], $row[3]);
				}
			}
			
			// Making INI file
			foreach($this->_files as &$f) $f = str_replace(ROOT . DS, "", $f);
			$ini_file .= "\nfiles = \"" . implode(";", $this->_files) . "\"\n";
			file_put_contents(DAT . DS . "meta" . DS . $name . ".ini", $ini_file);

			// Cache cleaning
			$this->_packages = $this->_cache->remove("core.packages");
			
			// Remove all temp files
			Files::delete(TMP . DS . "core.packages.install");
			
			return new Response(0, "success", $this->_lang->get("core", "packages.install.success"));
		} else {
			return new Response(10, "danger", $this->_lang->get("core", "packages.install.incorrectFormat"));
		}
	}
	
	/**
	 * Page for remove module
	 * @param string $name Module Name
	 * @return Response
	 * @throws \Exception
	 */
	public function removePage($name) {
		if (!$this->_user->hasPermission("core.packages.remove")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}
		
		$response = new Response();

		$this->_core
			->addBreadcrumbs($this->_lang->get("core", "packages.moduleName"), "core/packages")
			->addBreadcrumbs($this->_lang->get("core", "packages.remove.moduleName") . " ({$name})", "core/packages/remove/" . $name);

		if ($this->exists($name)) {
			// Check dependencies
			$dep = $this->checkDependent($name);
			if (count($dep) > 0) {
				return new Response(4, "danger", $this->_lang->get("core", "packages.remove.dependent") . ": <ul><li>" . implode("</li><li>", $dep) . "</li></ul>");
			}
			
			$response->view = "core.packages.remove";
			$response->tags["name"] = $name;
		} else {
			$response->code = 3;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "packages.remove.notExist");
		}

		return $response;
	}
	
	/**
	 * Remove module
	 * @param string $name Modules Name
	 * @param bool $remove_links Remove links in menu
	 * @return Response
	 * @throws \Exception
	 */
	public function remove($name, $remove_links) {
		$core = $this->exists($name) && $this->get($name)["type"] == "core";
		if (!$this->_user->hasPermission("core.packages.remove") || $core) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}
		
		$response = new Response();

		if ($this->exists($name)) {
			$meta = $this->get($name); // Get meta data
			$this->_packages = $this->_cache->remove("core.packages"); // Cache cleaning
			
			// Check dependences
			$dep = $this->checkDependences($name, false);
			if (count($dep) > 0) {
				return new Response(4, "danger", $this->_lang->get("core", "packages.remove.dependent") . ": <ul><li>" . implode("</li><li>", $dep) . "</li></ul>");
			}
			
			// REMOVE MENU LINKS
			if ($remove_links) {
				$model = new Menu();

				// Backend
				if (!empty($meta["backend-link"])) {
					foreach ($model->get("backend", true) as $row) {
						if (preg_match("~{$meta["backend-link"]}~", $row["link"])) {
							$model->remove($row["id"]);
						}
					}
				}
				
				//Frontend
				if (!empty($meta["frontend-link"])) {
					foreach ($model->get("frontend", true) as $row) {
						if (preg_match("~{$meta["frontend-link"]}~", $row["link"])) {
							$model->remove($row["id"]);
						}
					}
				}
			}
			
			// Remove database tables
			if (!empty($meta["db-tables"])) {
				$tables = explode(",", $meta["db-tables"]);
				foreach ($tables as &$tname) $tname = "`" . DBPREFIX . $tname . "`";
				
				if (!$this->_db->query("DROP TABLE " . implode(",", $tables))->result()) {
					return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
				}
			}
			
			// Remove files
			foreach($meta["files"] as $file)
				Files::delete(ROOT . DS . $file);

			// Remove meta file
			Files::delete($meta["meta-name"]);

			$response->type = "success";
			$response->message = $this->_lang->get("core", "packages.remove.success");
		} else {
			$response->code = 3;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "packages.remove.notExist");
		}

		return $response;
	}
}

