<?php
/**
 * Strings static class
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

namespace harmony\strings;

class Strings {
	/**
	 * Line Wrap
	 * @param string $string String
	 * @param string $lineWrapTag Line Wrap tag
	 * @return string Line wrapped string
	 */
	public static function lineWrap($string, $lineWrapTag = "<br>") {
		return str_replace(array("\r\n", "\n", "\r"), $lineWrapTag, $string);
	}

	/**
	 * Cut string
	 * @param string $string String
	 * @param int $length String length
	 * @return string
	 */
	public static function cut($string, $length) {
		$string = substr($string, 0, $length);
		$pos = strrpos($string, " ");
		$string = substr($string, 0, $pos);
		return $string;
	}

	/**
	 * Get string length
	 * @param string $string String
	 * @param string $encoding Encoding
	 * @return int
	 */
	public static function length($string, $encoding = "UTF-8") {
		return mb_strlen($string, $encoding);
	}
	
	/**
	 * Generate salt string
	 * @param int $length String length (default - 8)
	 * @return string
	 */
	public static function genSalt($length = 8) {
		$chars = "abdefhiknrstyzABDEFGHKNQRSTYZ23456789";
		$numChars = strlen($chars);
		$string = "";

		for ($i = 0; $i < $length; $i++)
			$string .= substr($chars, rand(1, $numChars) - 1, 1);

		return $string;
	}
}
