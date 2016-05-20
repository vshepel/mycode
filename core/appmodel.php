<?php
/**
 * Model Abstract class
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

abstract class AppModel {
	/**
	 * @var object Required objects
	 */
	protected $_registry;

	/**
	 * @var object Config object
	 */
	protected $_config;

	/**
	 * @var object Lang object
	 */
	protected $_lang;

	/**
	 * @var object Config object
	 */
	protected $_core;

	/**
	 * @var object DataBase object
	 */
	protected $_db;

	/**
	 * @var object View object
	 */
	protected $_view;

	/**
	 * @var object User object
	 */
	protected $_user;

	/**
	 * @var object Cache object
	 */
	protected $_cache;

	/**
	 * Constructor
	 * @throws \Exception
	 */
	public function __construct () {
		$registry = Registry::getInstance();
		$this->_registry = $registry;
		$this->_config = $registry->get("Config");
		$this->_lang = $registry->get("Lang");
		$this->_core = $registry->get("Core");
		$this->_db = $registry->get("Database");
		$this->_view = $registry->get("View");
		$this->_user = $registry->get("User");
		$this->_cache = $registry->get("Cache");
	}
}
