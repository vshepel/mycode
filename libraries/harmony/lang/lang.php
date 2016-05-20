<?php
/**
 * Lang class
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

namespace harmony\lang;

use Exception;
use harmony\arrays\ArrayConverters;

class Lang {
	/**
	 * @var string Lang Directory
	 */
	private $_dir;

	/**
	 * @var string Lang name
	 */
	private $_name = "en_GB";
	
	/**
	 * @var string Default lang name
	 */
	private $_defaultName = "en_GB";

	/**
	 * @var array Lang array
	 */
	private $_lang = array ();

	/**
	 * Constructor
	 * @param string $dir Lang directory
	 * @param string $lang Lang name
	 */
	public function __construct($dir, $lang = "") {
		$this->_dir = str_replace(array ("../", "..\\"), "", $dir);
		$this->_name = $lang;
	}

	/**
	 * Set lang
	 * @param string $name Lang name
	 */
	public function setLang($name) {
		if ($this->available($name)) {
			$this->_name = $name;
			$this->_lang = array();
			return true;
		} else
			return false;
	}

	/**
	 * Get lang name
	 * @return string
	 */
	public function getLang() {
		return $this->_name;
	}
	
	/**
	 * Check language for available
	 * @param string $name Language name
	 * @return bool
	 */
	public function available($name) {
		return is_dir(LANG . DS . $name);
	}
	
	/**
	 * Get languages array
	 * @return array
	 */
	public function getLangs(){
		$langs = [];
		
		foreach(scandir($this->_dir) as $lang) {
			$file = $this->_dir . DS . $lang . DS . "core.ini";
			if (!in_array($lang, [".", ".."]) && is_file($file)) {
				$ini = parse_ini_file($file);
				$langs[$lang] = $ini["lang.name"];
			}
		}
		
		return $langs;
	}

	/**
	 * Load lang
	 * @param string $name Lang name
	 * @return bool
	 */
	public function load($name) {
		$file = $this->_dir . DS . $this->_name . DS . str_replace(".", DS, $name) . ".ini";
		
		// Use Default
		if (!is_file($file))
			$file = $this->_dir . DS . $this->_defaultName . DS . str_replace(".", DS, $name) . ".ini";

		if (is_file($file)) {
			$this->_lang[$name] = parse_ini_file($file);
			return true;
		} else {
			$this->_lang[$name] = false;
			return false;
		}
	}

	/**
	 * Get Lang value
	 * @param string $name Lang name
	 * @param string $key = null Value key
	 * @param array $args String args
	 * @return mixed|false Get lang value (false, if value not exist in array)
	 */
	public function get($name, $key = null, $args = []) {
		if (!isset($this->_lang[$name]))
			$this->load($name);

		if (isset($this->_lang[$name])) {
			if ($key === null) {
				if ($this->_lang[$name] === false)
					return ($name . (($key === null) ? "" : (":" . $key)));
				else
					return $this->_lang[$name];
			} elseif (isset($this->_lang[$name][$key]))
				return vsprintf($this->_lang[$name][$key], $args);
			else
				return ($name . (($key === null) ? "" : (":" . $key)));
		} else
			return ($name . (($key === null) ? "" : (":" . $key)));
	}

	/**
	 * Save Lang file
	 * @param string $path Config path
	 * @param string $name Config name
	 * @param array $values Values array
	 * @throws Exception
	 */
	public function save($path, $name, $values) {
		$file = $this->_dir . DS . $this->_name . DS . $name . EXT;
		$config = ArrayConverters::arrayToFile($values);

		if (!isset($this->_lang[$path]))
			$this->_lang[$path] = array ();

		$this->_lang[$path][$name] = $values;

		if (!@file_put_contents($file, $config))
			throw new Exception ("Config error: error save file {$file}");
	}
	
	/**
	 * Parse lang string
	 * @param string $str Language string
	 * @return string Parsed string
	 */
	public function parseString($str) {
		return preg_replace_callback("#\\[([A-Za-z0-9._-]+):([A-Za-z0-9._-]+)\\]#i", function($args) {
			return $this->get($args[1], $args[2]);
		}, $str);
	}
}
