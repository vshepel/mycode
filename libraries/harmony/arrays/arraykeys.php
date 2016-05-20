<?php
/**
 * Array Keys static class
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

namespace harmony\arrays;

class ArrayKeys {
	/**
	 * Continuation of the array by ordinal keys
	 * @param array $array Array
	 * @param array $keys Keys
	 * @return array
	 */
	public static function continuationByKeys($array, $keys) {
		if (is_array($keys) && count($keys) > 0) {
			$continuation = self::continuationByKeys(isset($array[$keys[0]]) ? $array[$keys[0]] : array(), array_slice($keys, 1));
			$array[$keys[0]] = isset($array[$keys[0]]) ? array_merge_recursive($array[$keys[0]], $continuation) : $continuation;
			return $array;
		} else
			return array();
	}

	/**
	 * @param array $array Array
	 * @param array $keys Keys
	 * @param array $good Good chars
	 * @return bool
	 */
	public static function checkByKeys($array, $keys, $good = array ()) {
		if ((count($array) == 0) || (count($keys) == 0))
			return (count($array) >= count($keys));
		elseif (in_array($keys[0], $good))
			return true;
		elseif (array_key_exists($keys[0], $array))
			return self::checkByKeys($array[$keys[0]], array_slice($keys, 1), $good);
		else
			foreach ($good as $key)
				if (array_key_exists($key, $array))
					return true;

		return false;
	}
}
