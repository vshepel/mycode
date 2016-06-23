<?php
/**
 * Database Driver MySQLi class
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

namespace harmony\database\drivers;

use harmony\database\Driver;
use \Exception;

class MySQLi extends Driver {
	protected $_db;

	public function __construct() {
		if (!function_exists("mysqli_get_server_info"))
			throw new Exception("PHP does not support MySQLi");
	}

	public function connect($host, $username, $password, $name, $charset = 'utf8') {
		if (!$this->_db = mysqli_connect($host, $username, $password, $name))
			die('Database error: connect error, ' . __METHOD__);

		if (!mysqli_set_charset($this->_db, $charset))
			die('Database error: charset error, ' . __METHOD__ . ", " . $this->getError());
	}

	public function checkConnect($host, $username, $password, $name) {
		if (!mysqli_connect($host, $username, $password, $name))
			return false;
		else
			return true;
	}

	public function getVersion() {
		return mysqli_get_server_info($this->_db);
	}

	public function insert_id() {
		return mysqli_insert_id($this->_db);
	}

	public function safe($string) {
		return mysqli_real_escape_string($this->_db, $string);
	}

	public function getError() {
		$error = mysqli_error($this->_db);
		return empty($error) ? "" : ($error . (($this->_lastSql === "") ? "" : " in query: {$this->_lastSql}"));
	}

	public function result() {
		if (mysqli_query($this->_db, $this->getSql()))
			return true;
		else
			return false;
	}

	public function result_num() {
		$num = mysqli_num_rows(mysqli_query($this->_db, $this->getSql()));

		if ($num === false)
			return false;
		else
			return $num;
	}

	public function result_array() {
		if ($query = mysqli_query($this->_db, $this->getSql())) {
			$result = array ();

			while ($row = mysqli_fetch_array($query))
				$result[] = $row;

			return $result;
		}

		return false;
	}

	public function close() {
		mysqli_close($this->_db);
	}
}
