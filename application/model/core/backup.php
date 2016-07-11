<?php
/**
 * Core Backup Model
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

class Backup extends AppModel {
	/**
	 * Get page
	 * @return Response
	 */
	public function getPage() {
		$response = new Response();

		$this->_core->addBreadcrumbs($this->_lang->get("core", "backup.moduleName"), "core/backup");

		// Access denied
		if (!$this->_user->hasPermission("core.backup")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		// Database backups
		$database_backups = [];

		foreach (array_reverse(glob(ROOT . DS . "backup" . DS . "database" . DS . "*.sql")) as $file) {
			$name = str_replace(ROOT . DS . "backup" . DS . "database" . DS, "", $file);

			$name_array = explode("_", $name);
			$date = strtotime(str_replace(".sql", "", end($name_array)));
			$date = $this->_core->getDate($date) . " " . $this->_core->getTime($date);

			$database_backups[] = [
				"name" => $name,
				"date" => $date
			];
		}
		
		$response->view = "core.backup";

		$response->tags = [
			"date" => date("c", time()),
			"database-backups" => $database_backups
		];

		return $response;
	}

	/**
	 * Make database backup
	 * @return Response
	 */
	public function makeDatabase() {
		// Access denied
		if (!$this->_user->hasPermission("core.backup")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		// Get tables
		$result = $this->_db->query("SHOW TABLES")->result_array();

		if ($result === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$tables = [];
		foreach($result as $row) {
			$tables[] = $row[0];
		}

		// Information variables
		$version = VERSION;
		$database = $this->_config->get("database", "base");
		$date = date("c", time());
		$tables_str = implode(",", $tables);

		// Making dump
		$fp = fopen(ROOT . DS . "backup" . DS . "database" . DS . "{$database}_{$date}.sql", "w");

		$text = "--
-- HarmonyCMS Database Dump
--
-- System version: {$version}
-- Database: {$database}
-- Creation date: {$date}
-- Tables: {$tables_str}
--
";
		fwrite($fp, $text);

		foreach($tables as $item) {
			// Structure
			$text = "\nDROP TABLE IF EXISTS `{$item}`;\n";

			$result = $this->_db->query("SHOW CREATE TABLE " . $item)->result_array();

			if ($result === false) {
				return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
			}

			$text .= $result[0][1].";\n";
			fwrite($fp, $text);

			// Dump
			$result = $this->_db->query("SELECT * FROM `{$item}`")->result_array();

			if ($result === false) {
				return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
			}

			if (count($result) > 0) {
				fwrite($fp, "\nINSERT INTO `" . $item . "` VALUES\n");

				$first = true;

				foreach ($result as $row) {
					$val = [];

					foreach ($row as $k => $v) {
						if (is_int($k)) {
							$val[] = "'{$this->_db->safe($v)}'";
						}
					}

					if ($first) {
						fwrite($fp, "\n(" . implode(", ", $val) . ")");
						$first = false;
					} else {
						fwrite($fp, ",\n(" . implode(", ", $val) . ")");
					}
				}

				fwrite($fp, ";\n");
			}
		}

		fclose($fp);

		return new Response(0, "success", $this->_lang->get("core", "backup.database.make.success"));
	}

	/**
	 * Restore database backup
	 * @param string $name Dump name
	 * @return Response
	 */
	public function restoreDatabase($name) {
		// Access denied
		if (!$this->_user->hasPermission("core.backup")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$name = str_replace(["/", "\\"], "", $name);
		$fname = ROOT . DS . "backup" . DS . "database" . DS . $name;

		if (is_file($fname)) {
			$sql = file_get_contents($fname);

			foreach (explode(";\n", $sql) as $query) {
				$query = str_replace("{PREFIX}", DBPREFIX, $query);
				if (!empty(str_replace([" ", "\n", "\r"], "", $query)) && !$this->_db->query($query)->result()) {
					return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
				}
			}

			$this->_cache->clear();

			return new Response(0, "success", $this->_lang->get("core", "backup.database.restore.success"));
		} else {
			return new Response(2, "danger", $this->_lang->get("core", "backup.database.restore.badFile"));
		}
	}
}
