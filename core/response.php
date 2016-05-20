<?php
/**
 * Class for Responses
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

class Response {
	public function Response($code = 0, $type = "", $message = "", $view = "", $tags = array(), $layout = null) {
		$this->code = $code;
		$this->type = $type;
		$this->message = $message;

		$this->view = $view;
		$this->tags = $tags;
		$this->layout = $layout;
	}

	/**
	 * @var int Status code
	 */
	public $code;

	/**
	 * Alert
	 */

	/**
	 * @var string Alert type
	 */
	public $type;

	/**
	 * @var string Alert message
	 */
	public $message;

	/**
	 * View
	 */

	/**
	 * @var string View name
	 */
	public $view;

	/**
	 * @var null|string View layout name
	 */
	public $layout;

	/**
	 * @var array View tags
	 */
	public $tags;
}
