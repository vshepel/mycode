<?php
/**
 * View Main class
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

namespace harmony\view;

use Exception;

class View {
	/**
	 * @var View Singleton instance
	 */
	private static $_instance;

	/**
	 * @var string Driver prefix
	 */
	private $_parser_class_prefix = "\\harmony\\view\\engines\\";

	/**
	 * Get singleton instance
	 * @return View
	 */
	public static function getInstance() {
		if (empty(self::$_instance))
			self::$_instance = new self;

		return self::$_instance;
	}

	/**
	 * Get parser
	 * @param $parser
	 * @return Engine
	 * @throws Exception
	 */
	public function parser($parser) {
		$parser_class = $this->_parser_class_prefix . $parser;

		if (class_exists($parser_class)) {
			$parser_object = new $parser_class;

			if (!$parser_object instanceof Engine)
				throw new Exception("View error: parser {$parser} is not instance of main parser");

			return $parser_object;
		} else
			throw new Exception("View error: parser {$parser} is not available");
	}
}
