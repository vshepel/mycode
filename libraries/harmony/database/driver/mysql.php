<?php
/**
 * Database Driver MySQL class
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

namespace harmony\database\driver;

use harmony\database\Driver;
use \Exception;

class MySQL extends Driver {
	protected $_db;

	public function __construct() {
		if (!function_exists("mysql_get_server_info"))
			throw new Exception("PHP does not support MySQL");
	}

	public function connect($host, $username, $password, $name, $charset = 'utf8') {
		if (!$this->_db = mysql_connect($host, $username, $password))
			die('Database error: connect error, ' . __METHOD__);

		if (!mysql_select_db($name, $this->_db))
			die('Database error: select database error, ' . __METHOD__);

		if (!mysql_set_charset($charset, $this->_db))
			die('Database error: set charset error, ' . __METHOD__);
	}

	public function checkConnect($host, $username, $password, $name) {
		if (!mysql_connect($host, $username, $password, $name))
			return false;
		else
			return true;
	}

	public function getVersion() {
		return mysql_get_server_info($this->_db);
	}

	public function insert_id() {
		return mysql_insert_id($this->_db);
	}

	public function safe($string) {
		return mysql_real_escape_string($string, $this->_db);
	}

	public function getError() {
		$error = mysql_error($this->_db);

		return (empty($error) ? "" : ($error . " ")) . (($this->_lastSql === "") ? "" : "in query: {$this->_lastSql}");
	}

	public function result() {
		if (mysql_query($this->getSql(), $this->_db))
			return true;
		else
			return false;
	}

	public function result_num() {
		$num = mysql_num_rows(mysql_query($this->getSql(), $this->_db));

		if ($num === false)
			return false;
		else
			return $num;
	}

	public function result_array() {
		if ($query = mysql_query($this->getSql(), $this->_db)) {
			$result = array ();

			while ($row = mysql_fetch_array($query))
				$result[] = $row;

			return $result;
		}

		return false;
	}

	public function close() {
		mysql_close($this->_db);
	}
}
