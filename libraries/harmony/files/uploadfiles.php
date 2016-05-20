<?php
/**
 * Files Upload singleton class
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

namespace harmony\files;

use Exception;
use Registry;
use Response;

class UploadFiles {
	/**
	 * @var Files Singleton instance
	 */
	private static $_instance;

	/**
	 * @var string Upload directory
	 */
	private $_uploadDir;

	/**
	 * @var object Lang
	 */
	private $_lang;

	/**
	 * Get Singleton instance
	 * @return Files
	 */
	public static function getInstance() {
		if (empty(self::$_instance))
			self::$_instance = new self;

		return self::$_instance;
	}

	/**
	 * Constructor
	 * @param $dir = null Upload directory
	 * @throws Exception
	 */
	public function __construct($dir = null) {
		$this->_lang = Registry::getInstance()->get("Lang");
		if ($dir === null) $dir = PUB . DS . "upload";
		self::setUploadDirectory($dir);
	}


	/**
	 * Set upload directory
	 * @param string $dir Upload directory
	 * @return $this
	 */
	public function setUploadDirectory($dir) {
		$this->_uploadDir = $dir;
		return $this;
	}

	/**
	 * Get upload directory
	 * @return string Upload directory
	 */
	public function getUploadDirectory() {
		return $this->_uploadDir;
	}
	
	private function _mkdir($dir) {
		Files::mkdir($this->_uploadDir . DS . $dir);
	}

	/**
	 * Get Error by Code
	 * @param int $code Code num
	 * @return string|null Code description (null, if code incorrect)
	 */
	public function getErrorByCode($code) {
		$array = array(
			"error.upload.success",
			"error.upload.bigFile",
			"error.upload.bigFileByForm",
			"error.upload.halfFile",
			"error.upload.noFile",
			"error.upload.noTempDir",
			"error.upload.cantWrite",
			"error.upload.scriptStopLoad"
		);

		return isset($array[$code]) ? $this->_lang->get("files", $array[$code]) : null;
	}

	/**
	 * Upload file
	 * @param array $file File variable
	 * @param string $directory File directory
	 * @param string $name File name
	 * @return Response
	 */
	public function upload($file, $directory, $name) {
		$response = new Response();

		if (isset($file["error"]) && $file["error"] === 0) {
			$this->_mkdir($directory);
			if (!@move_uploaded_file($file["tmp_name"], $this->_uploadDir . DS . $directory . DS . $name)) {
				$response->code = 8;
				$response->type = "danger";
				$response->message = $this->_lang->get("files", "error.upload.cantMove");
			} else {
				$response->type = "success";
				$response->message = $this->getErrorByCode(0);
			}
		} else {
			$response->code = $file["error"];
			$response->type = "danger";
			$response->message = $this->getErrorByCode($file["error"]);
		}

		return $response;
	}
	
	/**
	 * Check file for exists
	 * @param string $directory File directory
	 * @param string $name File name
	 * @return Response
	 */
	public function exists($directory, $name) {
		return Files::exists($this->_uploadDir . DS . $directory . DS . $name);
	}

	/**
	 * Write content to file
	 * @param string $directory File directory
	 * @param string $name File name
	 * @param string $value File content
	 * @return int|false Returns the number of bytes written, or FALSE on error.
	 */
	public function write($directory, $name, $value) {
		$this->_mkdir($directory);
		return Files::write($this->_uploadDir . DS . $directory . DS . $name, $value);
	}

	/**
	 * Delete file from upload directory
	 * @param string $directory File directory
	 * @param string $name File name
	 * @return bool Is success?
	 */
	public function delete($directory, $name) {
		return Files::delete($this->_uploadDir . DS . str_replace(DOT, DS, $directory) . DS . $name);
	}
	
	/**
	 * Get filesize
	 * @param string $directory File directory
	 * @param string $name File name
	 * @param bool $format = false Get formatted value
	 * @param bool $format_ten = false Use ten in format
	 * @return bool|int
	 */
	public function fileSize($directory, $name, $format = false, $format_ten = false) {
		return Files::fileSize($this->_uploadDir . DS . str_replace(DOT, DS, $directory) . DS . $name, $format, $format_ten);
	}
}
