<?php
/**
 * Database Main class
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

namespace harmony\database;

class DataBase {
	/**
	 * @var DataBase Singleton instance
	 */
	protected static $_instance;

	/**
	 * @var string Driver prefix
	 */
	protected $_driver_class_prefix = "\\harmony\\database\\driver\\";

	/**
	 * @return DataBase Get instance
	 */
	public static function getInstance() {
		if (empty(self::$_instance))
			self::$_instance = new self;

		return self::$_instance;
	}

	/**
	 * Get driver
	 * @param $driver
	 * @return mixed
	 * @throws \Exception
	 */
	public function driver($driver) {
		$driver_class = $this->_driver_class_prefix . $driver;

		if (class_exists($driver_class)) {
			$driver_object = new $driver_class;

			if (!$driver_object instanceof Driver)
				throw new \Exception("Database error: driver {$driver} is not instance of main driver");

			return $driver_object;
		} else
			throw new \Exception("Database error: driver {$driver} is not available");
	}
}
