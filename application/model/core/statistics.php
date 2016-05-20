<?php
/**
 * Core Statistics Model
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

use harmony\files\Files;

class Statistics extends AppModel {
	/**
	 * Get statistics page
	 * @return Response
	 */
	public function getPage() {
		$response = new Response();

		$this->_core->addBreadcrumbs($this->_lang->get("core", "statistics.moduleName"), "core/statistics");

		// Access denied
		if (!$this->_user->hasPermission("core.statistics")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}
		
		$response->view = "core.statistics";
		$tags = array();

		$tags["version"] = VERSION;
		$tags["cache-size"] = Files::fileSize(CACHE, true);
		$tags["uploads-size"] = Files::fileSize(PUB . DS . "upload", true);
		$tags["free-space"] = Files::fileSizeFormat(disk_free_space(PUB . DS . "upload"));
		$tags["debug"] = $this->_lang->get("core", "options." . (defined("DEBUG") ? "on" : "off"));

		$tags["os"] = @php_uname("s") . " " . @php_uname("r");
		$tags["php-version"] = phpversion();
		$tags["post-max-size"] = Files::fileSizeFormat(ini_get("post_max_size") * 1000000, true);
		$tags["upload-max-filesize"] = Files::fileSizeFormat(ini_get("upload_max_filesize") * 1000000, true);

		$tags["db-driver"] = $this->_config->get("database", "driver");
		$tags["db-version"] = $this->_db->getVersion();

		$array = $this->_db
			->query("SHOW TABLE STATUS FROM `" . $this->_config->get("database", "base") . "`")
			->result_array();

		if ($array === false) {
			$tags["db-size"] = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			$tags["db-tables"] = $tags["db-size"];
		} else {
			$size = 0;
			$tables = 0;
			
			foreach ($array as $row) {
				if (strpos($row["Name"], DBPREFIX) !== false) {
					$size += $row["Data_length"] + $row["Index_length"];
					$tables++;
				}
			}
			
			$tags["db-size"] = Files::fileSizeFormat($size);
			$tags["db-tables"] = $tables;
		}

		$response->tags = $tags;

		return $response;
	}
}
