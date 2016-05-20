<?php
/**
 * String Filters static class
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

class StringFilters {
	/**
	 * Filter HTML Tags
	 * @param string $string String
	 * @return string Filtered string
	 */
	public static function filterHtmlTags($string) {
		return htmlspecialchars($string);
	}

	/**
	 * Filter all chars, where non words, numbers and some symbols (".", ",", ":", "-" "/", "\", "(", ")" "[", "]", "{", "}", "#")
	 * @param string $string String
	 * @return string
	 */
	public static function filterNonWords($string) {
		return preg_replace("/[^a-zA-ZА-Яа-я0-9\\s\\.\\,\\:\\-\\/\\\\\\(\\)\\[\\]\\{\\}\\#]/", "",$string);
	}

	/**
	 * Filter string for public
	 * @param string $string String
	 * @return string
	 */
	public static function filterStringForPublic($string) {
		$string = self::filterHtmlTags($string);
		$string = trim($string);

		$string = preg_replace("/(\\r\\n){3,}/", "\r\n\r\n", $string);
		$string = preg_replace("/(\\r){3,}/", "\r\r", $string);
		$string = preg_replace("/(\\n){3,}/", "\n\n", $string);

		return $string;
	}
	
	/**
	 * Filter string for url
	 * @param string $string String
	 * @return string
	 */
	public static function filterForUrl($string) {
		return preg_replace( "/[^a-z0-9\_\-]+/mi", "", str_replace(" ", "-", strtolower(StringConverters::toTranslit($string))));
	}
}
