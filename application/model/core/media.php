<?php
/**
 * Core Media Model
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

use harmony\strings\StringFilters;
use harmony\files\UploadFiles;
use harmony\files\Files;

class Media extends AppModel {
	private function _getImage($name) {
		if (in_array($name, ["zip", "rar", "tar", "tgz", "txz"]))
			return "archive";
		if (in_array($name, ["mp3", "flac", "m4a", "mid", "midi", "imy", "ogg", "wav", "aiff", "ape", "wma"]))
			return "audio";
		if (in_array($name, ["html", "htm", "css", "js", "json", "xml", "java", "c", "cs", "cpp", "php", "py", "sql", "md"]))
			return "code";
		if (in_array($name, ["doc", "docx", "odt"]))
			return "document";
		if (in_array($name, ["sh", "bat", "exe", "so", "dll"]))
			return "executable";
		if (in_array($name, ["ttf", "ttc", "otf", "dfont", "woff", "woff2", "eot"]))
			return "font";
		if (in_array($name, ["ppt", "pptx", "odp"]))
			return "interactive";
		if (in_array($name, ["pdf", "djvu"]))
			return "pdf";
		if (in_array($name, ["jpeg", "jpg", "png", "gif", "svg"]))
			return "picture";
		if (in_array($name, ["xls", "xlsx", "ods"]))
			return "spreadsheet";
		if (in_array($name, ["txt", "text"]))
			return "text";
		if (in_array($name, ["mp4", "avi", "wmv", "mov", "mkv", "3gp", "flv", "swf", "vob", "ifo", "m2v", "m2p"]))
			return "video";
		else
			return "default";
	}

	/**
	 * Check file for exists
	 * @param int $id Media ID
	 * @param bool $name Get name?
	 * @return bool
	 * @throws \Exception
	 */
	public function exists($id, $name = false) {
		$result = $this->_db
			->select([
				"filename"
			])
			->from(DBPREFIX . "core_media")
			->where("id", "=", intval($id))
			->result_array();

		if ($result === false)
			throw new \Exception("Error check file for exists: {$this->_db->getError()}");

		if (isset($result[0][0])) {
			if ($name) return $result[0][0];
			return true;
		} else
			return false;
	}
	
	/**
	 * Get media page
	 * @return Response
	 */
	public function getList() {
		$this->_core
			->addBreadcrumbs($this->_lang->get("core", "media.moduleName"), "core/media")
			->addBreadcrumbs($this->_lang->get("core", "media.list.moduleName"), "core/media/list");

		// Access denied
		if (!$this->_user->hasPermission("core.media.list")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$array = $this->_db
			->select(array(
				"id" ,"filename", "name", "description", "user",
				array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false)
			))
			->from(DBPREFIX . "core_media")
			->order_by("name", $this->_config->get("core", "media.list.sort", "ASC"))
			->result_array();
		
		if ($array === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$num = count($array);

		$rows = [];
	
		foreach ($array as $row) {
			$rows[] = [
				"id" => $row["id"],
				"filename" => $row["filename"],
				"filesize" => UploadFiles::getInstance()->fileSize("media", $row["filename"], true),
				"name" => $row["name"],
				"description" => $row["description"],

				"user-login" => $this->_user->getUserLogin($row["user"]),
				"user-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["user"]),
				"user-id" => $row["user"],
							
				"file-link" => SITE_PATH . "upload/media/" . $row["filename"],
				"icon-link" => SITE_PATH . "images/media/" . $this->_getImage(Files::getFileExtension($row["filename"])) . ".png",
				"edit-link" => ADMIN_PATH . "core/media/edit/" . $row["id"],
				"remove-link" => ADMIN_PATH . "core/media/remove/" . $row["id"],
							
				"date" => $this->_core->getDate($row["timestamp"]),
				"time" => $this->_core->getDate($row["timestamp"]),
			];
		}

		$response = new Response();
		$response->view = "core.media.list";
		$response->tags = [
			"num" => $num,
			"rows" => $rows,
		];
		
		return $response;
	}
	
	/**
	 * Get statistics page
	 * @return Response
	 */
	public function getUploadPage() {
		$this->_core
			->addBreadcrumbs($this->_lang->get("core", "media.moduleName"), "core/media")
			->addBreadcrumbs($this->_lang->get("core", "media.upload.moduleName"), "core/media/upload");

		// Access denied
		if (!$this->_user->hasPermission("core.media.upload")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$response = new Response();
		$response->view = "core.media.upload";
		$response->tags = [
			"max-filesize" => Files::fileSizeFormat(ini_get("upload_max_filesize") * 1000000, true)
		];
		
		return $response;
	}

	/**
	 * @param $file
	 * @return Response
     */
	public function upload($file) {
		// Access denied
		if (!$this->_user->hasPermission("core.media.upload")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$files = UploadFiles::getInstance();
		$fname = $file["name"];
	
		if (isset($file["error"]) && $file["error"] == 4) { // No uploaded file
			return new Response(3, "warning", $this->_lang->get("core", "emptyFields"));
		} elseif ($files->exists("media", $fname)) { // If file exists
			return new Response(4, "warning", $this->_lang->get("core", "media.upload.exists"));
		}

		$upload = $files->upload($file, "media", $fname);
	
		if ($upload->code != 0) {
			return $upload;
		}

		$query = $this->_db
			->insert_into(DBPREFIX . "core_media")
			->values([
				"user" => $this->_user->get("id"),
				"filename" => $fname,
				"name" => $fname
			])
			->result();
						
		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$response = new Response(0, "success", $this->_lang->get("core", "media.upload.success"));
		$response->tags["id"] = $this->_db->insert_id();

		return $response;
	}

	/**
	 * Edit page
	 * @param int $id Media ID
	 * @return Response
	 * @throws \Exception
	 */
	public function editPage($id) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("core", "media.moduleName"), "page")
			->addBreadcrumbs($this->_lang->get("core", "media.edit.moduleName"));

		// Access denied
		if (!$this->_user->hasPermission("core.media.edit")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$id = intval($id);

		if (!$this->exists($id)) {
			return new Response(3, "danger", $this->_lang->get("core", "media.edit.notExists"));
		}

		$row = $this->_db
			->select(array(
				"id" ,"filename", "name", "description", "user",
				array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false)
			))
			->from(DBPREFIX . "core_media")
			->where("id", "=", $id)
			->result_array();

		if ($row === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$response = new Response();
		$response->view = "core.media.edit";
		$response->tags = array_merge($row[0], [
			"url" => FSITE_PATH . "upload/media/" . $row[0]["filename"],
			"user-login" => $this->_user->getUserLogin($row[0]["user"]),
			"user-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row[0]["user"]),
			"user-id" => $row[0]["user"],
			"list-link" => ADMIN_PATH . "core/media",
			"remove-link" => ADMIN_PATH . "core/media/remove/" . $row[0]["id"]
		]);

		return $response;
	}
	
	/**
	 * Edit
	 * @param string $name Name
	 * @param string $description Description
	 * @param int $id Media ID
	 * @return Response
	 */
	public function edit($name, $description, $id = null) {
		// Access denied
		if (!$this->_user->hasPermission("core.media.edit")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$name = StringFilters::filterHtmlTags($name);
		$description = StringFilters::filterHtmlTags($description);
		$id = intval($id);
	
		if (empty($name)) {
			return new Response(3, "warning", $this->_lang->get("core", "emptyFields"));
		}

		$result = $this->_db
			->update(DBPREFIX . "core_media")
			->set([
				"name" => $name,
				"description" => $description,
			])
			->where("id", "=", $id)
			->result();

		if ($result === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		return new Response(0, "success");
	}
	
	/**
	 * Remove page
	 * @param int $id Page ID
	 * @return Response
	 * @throws \Exception
	 */
	public function remove($id) {
		// Access denied
		if (!$this->_user->hasPermission("core.media.remove")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$id = intval($id);
		$file = $this->exists($id, true);
	
		if ($file === false) {
			return new Response(3, "danger", $this->_lang->get("core", "media.remove.notExists"));
		}

		$query = $this->_db
			->delete_from(DBPREFIX . "core_media")
			->where("id", "=",$id)
			->result();
	
		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		UploadFiles::getInstance()->delete("media", $file);

		return new Response(0, "success", $this->_lang->get("core", "media.remove.success"));
	}

	/**
	 * Page for remove media
	 * @param int $id Media ID
	 * @return Response
	 * @throws \Exception
	 */
	public function removePage($id) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("core", "media.moduleName"), "core/media")
			->addBreadcrumbs($this->_lang->get("core", "media.remove.moduleName"));

		// Access denied
		if (!$this->_user->hasPermission("core.media.remove")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$id = intval($id);

		if (!$this->exists($id)) {
			return new Response(3, "danger", $this->_lang->get("core", "media.remove.notExists"));
		}

		$response = new Response();
		$response->view = "core.media.remove";
		$response->tags["id"] = $id;
		return $response;
	}
}
