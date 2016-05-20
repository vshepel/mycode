<?php
/**
 * Mail Driver abstract class
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

namespace harmony\mail;

abstract class Driver {
	/**
	 * @var array
	 */
	protected $_config;

	/**
	 * @param array $config Config array
	 */
	public function __construct($config) {
		$this->_config = $config;
	}

	/**
	 * Send message
	 * @param string $to To
	 * @param string $subject Subject
	 * @param string $message Message
	 * @param string $headers Custom headers
	 * @return boolean Status
	 */
	abstract function send($to, $subject, $message, $headers = "");

	/**
	 * @return string Error message
	 */
	abstract function getError();
}
