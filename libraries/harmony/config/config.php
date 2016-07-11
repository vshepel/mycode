<?php
/**
 * Config class
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

namespace harmony\config;

use Exception;
use harmony\arrays\ArrayConverters;
use harmony\files\Files;

class Config {
	/**
	 * @var string Config Directory
	 */
	private $_dir;

	/**
	 * @var array Config array
	 */
	private $_config = array ();

	/**
	 * Constructor
	 * @param string $dir Config directory
	 */
	public function __construct($dir) {
		$this->_dir = str_replace(array ("../", "..\\"), "", $dir);
	}

	/**
	 * Load config
	 * @param string $name Config name
	 * @return bool
	 */
	public function load($name) {
		$file = $this->_dir . DS . $name . ".php";

		if (is_file($file)) {
			$this->_config[$name] = include $file;
			return true;
		} else {
			$this->_config[$name] = false;
			return false;
		}
	}

	/**
	 * Get Config value
	 * @param string $name Config name
	 * @param string $key = null Value key
	 * @param mixed $default = null Default value
	 * @return mixed|false Get config value (false, if value not exist in array)
	 */
	public function get($name, $key = null, $default = null) {
		if (!isset($this->_config[$name]))
			$this->load($name);

		if (isset($this->_config[$name])) {
			if ($key === null)
				return $this->_config[$name];
			elseif (isset($this->_config[$name][$key]))
				return $this->_config[$name][$key];
		}

		// Set default
		if ($default !== null) {
			if (!isset($this->_config[$name]))
				$this->_config[$name] = array();
			
			$this->_config[$name][$key] = $default;
			$this->save($name);
			return $default;
		}
		
		return false;
	}

	/**
	 * Save Config file
	 * Save current stack or save stack from $values
	 * @param string $name Config name
	 * @param array $values Values array (null default)
	 * @param bool $replace = false Replace all values
	 * @throws Exception
	 */
	public function save($name, $values = null, $replace = false) {
		if ($values != null) {
			if ($replace)
				$this->_config[$name] = $values;
			else {
				// Load config
				if (!isset($this->_config[$name])) {
					if (!$this->load($name))
						$this->_config[$name] = [];
				}
				
				// Set new values
				foreach ($values as $n => $v)
					$this->_config[$name][$n] = $v;
			}
		}

		
		$file = $this->_dir . DS . $name . ".php";
		$config = ArrayConverters::arrayToFile($this->_config[$name], true);

		// Make dir if not exists
		if (!file_exists($this->_dir)) {
			Files::mkdir($this->_dir);
		}

		// File put contents
		if (!@file_put_contents($file, $config)) {
			throw new Exception("Config error: error save file {$file}");
		}

		// OPCache reset
		if (function_exists("opcache_reset")) {
			opcache_reset();
		}
	}
}
