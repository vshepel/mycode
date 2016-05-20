<?php
/**
 * Cookies class
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

class Cookies {
	/**
	 * Set cookie
	 * @param mixed $name Cookie name
	 * @param mixed $value Cookie value
	 * @param int $time Cookie lifetime
	 */
	public static function set($name, $value, $time) {
		setcookie($name, $value, time() + $time, "/");
	}

	/**
	 * Get cookie value
	 * @param mixed $name Cookie name
	 * @return mixed|false Cookie value (false, if cookie value not exist)
	 */
	public static function get($name) {
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
	}

	/**
	 * Remove cookie
	 * @param mixed $name Cookie name
	 */
	public static function remove($name) {
		setcookie($name, "", 0);
	}
}
