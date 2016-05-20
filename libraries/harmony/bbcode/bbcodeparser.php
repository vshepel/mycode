<?php
/**
 * BBCodeParser static class
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

namespace harmony\bbcode;

use harmony\strings\Strings;

class BBCodeParser {
	/**
	 * @var array Tags
	 */
	private static $tags = array (
		"#\[quote\](.+?)\[\/quote\]#is" => "<blockquote>\\1</blockquote>",
		"#\[code\](.+?)\[\/code\]#is" => "<code>\\1</code>",

		"#\[img\](.+?)\[\/img\]#is" => "<img src=\"\\1\" />",
		"#\[img width=(.+?),height=(.+?)\](.+?)\[\/img\]#is" => "<img src=\"\\3\" width=\"\\1\" height=\"\\2\" />",
		"#\[img width=(.+?)\](.+?)\[\/img\]#is" => "<img src=\"\\2\" width=\"\\1\" />",
		"#\[img height=(.+?)\](.+?)\[\/img\]#is" => "<img src=\"\\2\" height=\"\\1\" />",
		
		"#\[url\](.+?)\[\/url\]#is" => "<a href=\"\\1\">\\1</a>",
		"#\[url=(.+?)\](.+?)\[\/url\]#is" => "<a href=\"\\1\">\\2</a>",
		"#\[email\](.+?)\[\/email\]#is" => "<a href = 'mailto:\\1'>\\1</a>",

		"#\[center\](.+?)\[\/center\]#is" => "<div style=\"text-align: center;\">\\1</div>",
		"#\[left\](.+?)\[\/left\]#is" => "<div style=\"text-align: left;\">\\1</div>",
		"#\[right\](.+?)\[\/right\]#is" => "<div style=\"text-align: right;\">\\1</div>",
		"#\[justify\](.+?)\[\/justify\]#is" => "<div style=\"text-align: justify;\">\\1</div>",

		"#\[font=(.+?)\](.+?)\[\/font\]#is" => "<span style=\"font-family: '\\1';\">\\2</span>",

		"#\[sup\](.+?)\[\/sup\]#is" => "<sup>\\1</sup>",
		"#\[sub\](.+?)\[\/sub\]#is" => "<sub>\\1</sub>",

		"#\[color=(.+?)\](.+?)\[\/color\]#is" => "<span style=\"color:\\1\">\\2</span>",
		"#\[small\](.+?)\[\/small\]#is" => "<small>\\1</small>",
		"#\[size=(.+?)\](.+?)\[\/size\]#is" => "<span style=\"font-size: \\1%\">\\2</span>",

		"#\[p\](.+?)\[\/p\]#is" => "<p>\\1</p>",
		"#\[b\](.+?)\[\/b\]#is" => "<strong>\\1</strong>",
		"#\[i\](.+?)\[\/i\]#is" => "<em>\\1</em>",
		"#\[s\](.+?)\[\/s\]#is" => "<span style=\"text-decoration: line-through;\">\\1</span>",
		"#\[u\](.+?)\[\/u\]#is" => "<span style=\"text-decoration: underline;\">\\1</span>",

		"#\[list\](.+?)\[\/list\]#is" => "<ul style=\"margin-left: 25px;\">\\1</ul>",
		"#\[list=1\](.+?)\[\/list\]#is" => "<ol style=\"margin-left: 25px;\">\\1</ol>",
		"#\[\*\](.+?)\[\/\*\]#is" => "<li>\\1</li>",

		"#\[table\](.+?)\[\/table\]#is" => "<table class=\"cms-bbcode table\">\\1</table>",
		"#\[tr\](.+?)\[\/tr\]#is" => "<tr>\\1</tr>",
		"#\[td\](.+?)\[\/td\]#is" => "<td>\\1</td>"
	);

	/**
	 * Parse text
	 * @param string $text Test to parse
	 * @return string Parsed Text
	 */
	public static function parse($text) {
		foreach (self::$tags as $reg => $to)
			$text = preg_replace($reg, $to, $text);

		return Strings::lineWrap($text);
	}
}
