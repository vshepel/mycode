<?php
/**
 * Sessions class
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

namespace harmony\http;

class Sessions {
	private static $_init = false;
	
	private static function _init() {
		session_start();
		self::$_init = true;
	}

	/**
	 * Set cookie
	 * @param mixed $name Cookie name
	 * @param mixed $value Cookie value
	 */
	public static function set($name, $value) {
		if (!self::$_init) self::_init();
		$_SESSION[$name] = $value;
	}

	/**
	 * Get cookie value
	 * @param mixed $name Cookie name
	 * @return string|false Cookie value (FALSE if value not exist)
	 */
	public static function get($name) {
		if (!self::$_init) self::_init();
		return isset($_SESSION[$name]) ? $_SESSION[$name] : false;
	}

	/**
	 * Remove cookie value
	 * @param mixed $name Cookie name
	 */
	public static function remove($name) {
		if (!self::$_init) self::_init();
		unset($_SESSION[$name]);
	}
}
