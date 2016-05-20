<?php
/**
 * Registry class
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

class Registry {
	/**
	 * @var Registry Singleton instance
	 */
	private static $_instance;

	/**
	 * @var array Objects array
	 */
	private $_objects = array ();

	/**
	 * Singleton get instance
	 * @return Registry
	 */
	public static function getInstance() {
		if (empty(self::$_instance))
			self::$_instance = new self;

		return self::$_instance;
	}

	/**
	 * Add Object to Array
	 * @param string $key Key
	 * @param object $object Object
	 * @return $this
	 * @throws Exception
	 */
	public function add($key, $object) {
		if (array_key_exists($key, $this->_objects))
			throw new Exception("Object {$key} already registered");

		$this->_objects[$key] = $object;

		return $this;
	}

	/**
	 * Get Object from Array
	 * @param string $key Key
	 * @return object
	 * @throws Exception
	 */
	public function get($key) {
		if (!array_key_exists($key, $this->_objects))
			throw new Exception("Object {$key} is not registered");

		return $this->_objects[$key];
	}
}
