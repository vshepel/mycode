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
		$response = new Response();
		
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
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
		} else {
			$num = count($array);

			function image($name) {
				if (in_array($name, ["zip", "tar"]))
					return "archive";
				if (in_array($name, ["mp3", "flac", "m4a", "mid", "midi", "imy", "ogg", "wav", "aiff", "ape", "wma"]))
					return "audio";
				if (in_array($name, ["html", "htm", "css", "js", "json", "xml", "java", "c", "cs", "cpp", "php", "py", "sql", "sh", "bat"]))
					return "code";
				if (in_array($name, ["doc", "docx", "odt", "pdf", "djvu"]))
					return "document";
				if (in_array($name, ["ppt", "pptx", "odp", "jpeg", "jpg", "png", "gif"]))
					return "interactive";
				if (in_array($name, ["xls", "xlsx", "ods"]))
					return "spreadsheet";
				if (in_array($name, ["txt", "text"]))
					return "text";
				if (in_array($name, ["mp4", "avi", "wmv", "mov", "mkv", "3gp", "flv", "swf", "vob", "ifo", "m2v", "m2p"]))
					return "video";
				else
					return "default";
			}
			
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
					"icon-link" => SITE_PATH . "images/media/" . image(Files::getFileExtension($row["filename"])) . ".png",
					"edit-link" => ADMIN_PATH . "core/media/edit/" . $row["id"],
					"remove-link" => ADMIN_PATH . "core/media/remove/" . $row["id"],
							
					"date" => $this->_core->getDate($row["timestamp"]),
					"time" => $this->_core->getDate($row["timestamp"]),
				];
			}
	
			$response->view = "core.media.list";
			$response->tags = array (
				"num" => $num,
				"rows" => $rows,
			);
		}
		
		return $response;
	}
	
	/**
	 * Get statistics page
	 * @return Response
	 */
	public function getUploadPage() {
		$response = new Response();
		
		if (!$this->_user->hasPermission("core.media.upload")) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			$this->_core
				->addBreadcrumbs($this->_lang->get("core", "media.moduleName"), "core/media")
				->addBreadcrumbs($this->_lang->get("core", "media.upload.moduleName"), "core/media/upload");
	
			$response->view = "core.media.upload";
			$response->tags = [
				"max-filesize" => Files::fileSizeFormat(ini_get("upload_max_filesize") * 1000000, true)
			];
		}
		
		return $response;
	}

	/**
	 * @param $file
	 * @return Response
     */
	public function upload($file) {
		$response = new Response();
		
		if (!$this->_user->hasPermission("core.media.upload")) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			$files = UploadFiles::getInstance();
			$fname = $file["name"];
	
			if (isset($file["error"]) && $file["error"] == 4) { // No uploaded file
				$response->type = "warning";
				$response->code = 3;
				$response->message = $this->_lang->get("core", "emptyFields");   		
			} elseif ($files->exists("media", $fname)) { // If file exists
				$response->type = "warning";
				$response->code = 4;
				$response->message = $this->_lang->get("core", "media.upload.exists");
			} else { // If upload
				 $upload = $files->upload($file, "media", $fname);
	
				 if ($upload->code == 0) {
					 $query = $this->_db
						 ->insert_into(DBPREFIX . "core_media")
						 ->values([
							 "user" => $this->_user->get("id"),
							 "filename" => $fname,
							 "name" => $fname
						 ])
						 ->result();
						
					 if ($query === false) {
						 $response->code = 1;
						 $response->type = "danger";
						 $response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
					 } else {
						 $response->type = "success";
						 $response->message = $this->_lang->get("core", "media.upload.success");
						 $response->tags["id"] = $this->_db->insert_id();
					 }
				 } else
					$response = $upload;
			}
		}
		
		return $response;
	}

	/**
	 * Edit page
	 * @param int $id Media ID
	 * @return Response
	 * @throws \Exception
	 */
	public function editPage($id) {
		$response = new Response();
		
		if (!$this->_user->hasPermission("core.media.edit")) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			$this->_core
				->addBreadcrumbs($this->_lang->get("core", "media.moduleName"), "page")
				->addBreadcrumbs($this->_lang->get("core", "media.edit.moduleName"));
	
			$id = intval($id);
			$response->view = "core.media.edit";
	
			if ($this->exists($id)) {
					$row = $this->_db
						->select(array(
							"id" ,"filename", "name", "description", "user",
							array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false)
						))
						->from(DBPREFIX . "core_media")
						->where("id", "=", $id)
						->result_array();
	
					if ($row === false) {
						$response->code = 1;
						$response->type = "danger";
						$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
						return $response;
					} else {
						$response->tags = $row[0];
						$response->tags["url"] = FSITE_PATH . "upload/media/" . $row[0]["filename"];
						$response->tags["user-login"] = $this->_user->getUserLogin($row[0]["user"]);
						$response->tags["user-link"] = SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row[0]["user"]);
						$response->tags["user-id"] = $row[0]["user"];
					}
			} else {
				$response->code = 3;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "media.edit.notExists");
			}
		}

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
		$response = new Response();
		
		if (!$this->_user->hasPermission("core.media.edit")) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			$name = StringFilters::filterHtmlTags($name);
			$description = StringFilters::filterHtmlTags($description);
			$id = intval($id);
	
			if (empty($name)) {
				$response->code = 3;
				$response->type = "warning";
				$response->message = $this->_lang->get("core", "emptyFields");
			} else {
				$result = $this->_db
					->update(DBPREFIX . "core_media")
					->set([
						"name" => $name,
						"description" => $description,
					])
					->where("id", "=", $id)
					->result();
	
				if ($result === false) {
					$response->code = 1;
					$response->type = "danger";
					$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
				} else {
					$response->type = "success";
				}
			}
		}

		return $response;
	}
	
	/**
	 * Remove page
	 * @param int $id Page ID
	 * @return Response
	 * @throws \Exception
	 */
	public function remove($id) {
		$response = new Response();
		
		if (!$this->_user->hasPermission("core.media.remove")) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			$id = intval($id);
			$file = $this->exists($id, true);
	
			if ($file !== false) {
				$query = $this->_db
					->delete_from(DBPREFIX . "core_media")
					->where("id", "=",$id)
					->result();
	
				if ($query === false) {
					$response->code = 1;
					$response->type = "danger";
					$response->message = $this->_lang->get("main", "internalError", [$this->_db->getError()]);
				} else {
					$response->type = "success";
					$response->message = $this->_lang->get("core", "media.remove.success");
					UploadFiles::getInstance()->delete("media", $file);
				}
			} else {
				$response->code = 3;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "media.remove.notExists");
			}
		}

		return $response;
	}

	/**
	 * Page for remove media
	 * @param int $id Media ID
	 * @return Response
	 * @throws \Exception
	 */
	public function removePage($id) {
		$response = new Response();
		
		if (!$this->_user->hasPermission("core.media.remove")) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			$id = intval($id);
	
			$this->_core
				->addBreadcrumbs($this->_lang->get("core", "media.moduleName"), "core/media")
				->addBreadcrumbs($this->_lang->get("core", "media.remove.moduleName"));
	
			if ($this->exists($id)) {
				$response->view = "core.media.remove";
	
				$response->tags["id"] = $id;
			} else {
				$response->code = 3;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "media.remove.notExists");
			}
		}

		return $response;
	}
}
