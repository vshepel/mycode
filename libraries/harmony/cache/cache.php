<?php
/**
 * Cache class
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

namespace harmony\cache;

use Exception;
use harmony\files\Files;

class Cache {
	/**
	 * @var bool Is enabled?
	 */
	private $_enable = true;

	/**
	 * @var string Cache directory
	 */
	private $_dir;

	/**
	 * Constructor
	 * @param string $dir Cache directory
	 * @param bool $enable Enable cache
	 */
	public function __construct($dir, $enable = true) {
		$this->_dir = $dir;
		$this->_enable = $enable;
	}

	/**
	 * Enable cache
	 * @param bool $enable Enable cache?
	 */
	public function enable($enable) {
		$this->_enable = $enable;
	}

	/**
	 * Push cache content
	 * @param string $path Cache path
	 * @param string $name Cache name
	 * @param string $content Cache content
	 * @throws Exception
	 */
	public function push($path, $name, $content) {
		if ($this->_enable) {
			$content = json_encode($content);
			$name = str_replace(DS, DOT, $name);

			$dir = $this->_dir . DS . $path;
			$fileName = $dir . DS . $name . ".json";

			if (!file_exists($dir)) Files::mkdir($dir);

			if (!file_put_contents($fileName, $content))
				throw new Exception("Cache error: error put file (" . $path . "; " . $name . ")");
		}
	}

	/**
	 * Get cache content
	 * @param string $path Cache path
	 * @param string $name Cache name
	 * @return string|false Cache content (false, if content not exist)
	 */
	public function get($path, $name) {
		if ($this->_enable) {
			$name = str_replace(DS, DOT, $name);
			$fileName = $this->_dir . DS . $path . DS . $name . ".json";

			if (file_exists($fileName) && $file = file_get_contents($fileName)) {
				$file = json_decode($file, true);

				return $file;
			}
		}

		return false;
	}

	/**
	 * Remove cache content
	 * @param string $path Cache path
	 * @param string $name Cache name
	 */
	public function remove($path, $name = null) {
		if ($this->_enable) {
			if ($name === null && file_exists($this->_dir . DS . $path))
				Files::delete($this->_dir . DS . $path);
			elseif (file_exists($this->_dir . DS . $path . DS . $name . ".json"))
				unlink($this->_dir . DS . $path . DS . $name . ".json");
		}
	}
	
	/**
	 * Clear cache
	 */
	public function clear() {
		Files::delete($this->_dir, false);
	}
}
