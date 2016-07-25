<?php
/**
 * StringConverters static class
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

class StringConverters {
	/**
	 * Parse string like 'aaa=bbb;foo=bar' to array
	 * @param string $str
	 * @return array
	 */
	public static function toArray($str) {
		$array = [];

		foreach (explode(";", $str) as &$var) {
			$ex = explode("=", $var, 2);
			if (count($ex) == 2) {
				$array[$ex[0]] = $ex[1];
			} else {
				$array[] = $ex[0];
			}
		}

		return $array;
	}

	/**
	 * Converter to Translit
	 * @param string $string Russian text
	 * @return string Translit text
	 */
	public static function toTranslit($string) {
		$that = array (
			"A", "Б", "В", "Г", "Д", "Е", "Ё", "Ж", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ы", "Ь", "Э", "Ю", "Я", "І", "Ў",
			"а", "б", "в", "г", "д", "е", "ё", "ж", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы", "ь", "э", "ю", "я", "і", "ў",
		);

		$to = array (
			"A", "B", "V", "G", "D", "E", "Jo", "Zh", "Z", "I", "J", "K", "L", "M", "N", "O", "P", "R", "S", "T", "U", "F", "H", "C", "Ch", "Sh", "Shh", "##", "Y", "''", "Je", "Ju", "Ja", "I", "U",
			"a", "b", "v", "g", "d", "e", "jo", "zh", "z", "i", "j", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "c", "ch", "sh", "shh", "#", "y", "'", "je", "ju", "ja", "i", "u"
		);

		return str_replace($that, $to, $string);
	}

	/**
	 * Compress HTML code
	 * @param string $html HTML code
	 * @return mixed Compressed HTML code
	 */
	public static function htmlCompress($html) {
		preg_match_all('!(<(?:code|pre|script).*>[^<]+</(?:code|pre|script)>)!', $html, $pre);

		$html = preg_replace('!<(?:code|pre).*>[^<]+</(?:code|pre)>!', '#pre#', $html);
		$html = preg_replace('#<!–[^\[].+–>#', '', $html);
		$html = preg_replace('/[\r\n\t]+/', ' ', $html);
		$html = preg_replace('/>[\s]+</', '><', $html);
		$html = preg_replace('/[\s]+/', ' ', $html);

		if (!empty($pre[0]))
			foreach ($pre[0] as $tag)
				$html = preg_replace('!#pre#!', $tag, $html,1);

		return $html;
	}
}
