<?php
/**
 * Controller Abstract class
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

abstract class AppController {
	/**
	 * @var bool Is AJAX?
	 */
	protected $_ajax;

	/**
	 * @var object Registry object
	 */
	protected $_registry;

	/**
	 * @var object Core object
	 */
	protected $_core;

	/**
	 * @var object View object
	 */
	protected $_view;

	/**
	 * @var string Default action
	 */
	public $__default = "index";

	/**
	 * @var array Custom routes
	 */
	public $__routes = null;

	/**
	 * Constructor
	 * @throws \Exception
	 */
	public function __construct() {
		$registry = Registry::getInstance();
		$this->_ajax = defined("AJAX");
		$this->_registry = $registry;
		$this->_core = $registry->get("Core");
		$this->_view = $registry->get("View");
	}
}
