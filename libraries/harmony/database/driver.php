<?php
/**
 * Database Driver abstract class
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

abstract class Driver {
	/**
	 * @var bool Database Debug?
	 */
	public $debug = false;

	/**
	 * @var array SQL build array
	 */
	protected $_sql = array ();

	/**
	 * @var string Last SQL query
	 */
	protected $_lastSql = "";

	/**
	 * @var string Operators
	 */
	private
		$_SELECT_OPERATOR		 = "SELECT",

		$_FROM_OPERATOR		   = "FROM",

		$_WHERE_OPERATOR		  = "WHERE",

		$_AND_OPERATOR			= "AND",

		$_OR_OPERATOR			 = "OR",

		$_GROUP_OPERATOR		  = "GROUP",
		
		$_ORDER_OPERATOR		  = "ORDER",

		$_BY_OPERATOR			 = "BY",

		$_DESC_OPERATOR		   = "DESC",

		$_ASC_OPERATOR			= "ASC",

		$_DELETE_OPERATOR		 = "DELETE",

		$_LIMIT_OPERATOR		  = "LIMIT",

		$_INSERT_OPERATOR		 = "INSERT",

		$_INTO_OPERATOR		   = "INTO",

		$_VALUES_OPERATOR		 = "VALUES",

		$_SET_OPERATOR			= "SET",

		$_UPDATE_OPERATOR		 = "UPDATE",

		$_REPLACE_OPERATOR		= "REPLACE";

	/**
	 * Connect to database
	 * @param string $host Database Host
	 * @param string $username Database username
	 * @param string $password Database password
	 * @param string $name Database name
	 * @param string $charset Database charset
	 * @return mixed
	 */
	abstract function connect($host, $username, $password, $name, $charset = "utf8");

	/**
	 * Check the ability to connect to the database
	 * @param string $host Database host
	 * @param string $username Database username
	 * @param string $password Database password
	 * @param string $name Database name
	 * @return bool
	 */
	abstract function checkConnect($host, $username, $password, $name);

	/**
	 * Get database version
	 * @return mixed
	 */
	abstract function getVersion();

	/**
	 * Parse param
	 * @param array|string $params
	 * @return string
	 */
	private function _parseParam($params) {
		if (is_array($params)) {
			foreach ($params as &$param)
				$param = "`" . $this->safe($param) . "`";

			return implode(".", $params);
		} else
			return "`" . $this->safe($params) . "`";
	}

	/**
	 * Query
	 * @param string $sql SQL query
	 * @return $this
	 */
	public function query($sql) {
		$this->_sql[] = $sql;

		return $this;
	}

	/**
	 * Builder
	 */

	/**
	 * Builder: REPLACE INTO
	 * @param $into Database name
	 * @return $this
	 */
	public function replace_into($into) {
		$this->_sql[] = $this->_REPLACE_OPERATOR . " " . $this->_INTO_OPERATOR . " `" . $this->safe($into) . "`";

		return $this;
	}

	/**
	 * Builder: SELECT
	 * @param array|string $args Args
	 * @return $this
	 */
	public function select($args) {
		$select = "";

		if (is_array($args)) {
			foreach ($args as &$arg)
				if (is_array($arg)) {
					if (is_array($arg[0])) {
						$arg0 = $this->_parseParam($arg[0]);
					} else
						if (isset($arg[2]) && $arg[2] === false)
							$arg0 = $arg[0];
						else
							$arg0 = "`" . $this->safe($arg[0]) . "`";

					if (is_array($arg[1])) {
						$arg1 = $this->_parseParam($arg[1]);
					} else
						if (isset($arg[3]) && $arg[3] === false)
							$arg1 = $arg[1];
						else
							$arg1 = "`" . $this->safe($arg[1]) . "`";

					$arg = $arg0 . " as " . $arg1;
				} else
					$arg = "`" . $this->safe($arg) . "`";

			$select .= implode(", ", $args);
		} else
			$select = $this->safe($args);

		$this->_sql[] = $this->_SELECT_OPERATOR . " " . $select . "";

		return $this;
	}

	/**
	 * Builder: FROM
	 * @param string $table Table name
	 * @return $this
	 */
	public function from($table) {
		if (is_array($table)) {
			foreach ($table as &$row)
				if (is_array($row))
					$row = "`{$this->safe($row[0])}` `{$this->safe($row[1])}`";
				else
					$row = "`{$this->safe($row)}`";

			$table = implode(",", $table);
		} else
			$table = "`" . $this->safe($table) . "`";

		$this->_sql[] = $this->_FROM_OPERATOR . " " . $table;

		return $this;
	}

	/**
	 * Builder: WHERE
	 * @param string $that That
	 * @param string $symbol Symbol
	 * @param string $what What
	 * @param bool $safe1 Safe that
	 * @param bool $safe2 Safe what
	 * @return $this
	 */
	public function where($that, $symbol, $what = "", $safe1 = true, $safe2 = true) {
		if (is_array($that)) {
			$that = $this->_parseParam($that);
		} else
			if ($safe1)
				$that = "`" . $this->safe($that) . "`";

		if (is_array($what)) {
			$what = $this->_parseParam($what);
		} else
			if ($safe2)
				$what = $this->string($what);

		$this->_sql[] = $this->_WHERE_OPERATOR . " {$that} " . $this->safe($symbol) . " " . $what;

		return $this;
	}

	/**
	 * Builder: AND for WHERE
	 * @param string $that That
	 * @param string $symbol Symbol
	 * @param string $what What
	 * @param bool $safe1 Safe that
	 * @param bool $safe2 Safe what
	 * @return $this
	 */
	public function and_where($that, $symbol, $what, $safe1 = true, $safe2 = true) {
		if (is_array($that))
			$that = $this->_parseParam($that);
		else
			if ($safe1)
				$that = "`" . $this->safe($that) . "`";

		if (is_array($what))
			$what = $this->_parseParam($what);
		else
			if ($safe2)
				$what = $this->string($what);

		$this->_sql[] = $this->_AND_OPERATOR . " {$that} " . $this->safe($symbol) . " " . $what;

		return $this;
	}

	/**
	 * Builder: OR for WHERE
	 * @param string $that That
	 * @param string $symbol Symbol
	 * @param string $what What
	 * @param bool $safe1 Safe that
	 * @param bool $safe2 Safe what
	 * @return $this
	 */
	public function or_where($that, $symbol, $what, $safe1 = true, $safe2 = true) {
		if (is_array($that))
			$that = $this->_parseParam($that);
		else
			if ($safe1)
				$that = "`" . $this->safe($that) . "`";

		if (is_array($what))
			$what = $this->_parseParam($what);
		else
			if ($safe2)
				$what = $this->string($what);

		$this->_sql[] = $this->_OR_OPERATOR . " {$that} " . $this->safe($symbol) . " " . $what;

		return $this;
	}
	
	/**
	 * Builder: GROUP BY
	 * @param string $by By
	 * @param bool $safe_by Safe $by
	 * @return $this
	 */
	public function group_by($by, $safe_by = true) {
		if ((bool)($safe_by))
			$by = "`{$this->safe($by)}`";

		$this->_sql[] = $this->_GROUP_OPERATOR . " {$this->_BY_OPERATOR} {$by}";

		return $this;
	}

	/**
	 * Builder: ORDER BY
	 * @param string $by By
	 * @param string $order How
	 * @param bool $safe_by Safe $by
	 * @param bool $safe_order Safe $order
	 * @return $this
	 */
	public function order_by($by, $order = null, $safe_by = true, $safe_order = true) {
		$safe_by = (bool)($safe_by);
		$safe_order = (bool)($safe_order);

		if ($safe_by)
			$by = "`{$this->safe($by)}`";

		if ($order !== null && $safe_order)
			$order = $this->safe($order);

		$this->_sql[] = $this->_ORDER_OPERATOR . " {$this->_BY_OPERATOR} {$by}" . (($order !== null) ? (" " . $order) : "");

		return $this;
	}

	/**
	 * Builder: DESC for ORDER BY
	 * @return $this
	 */
	public function desc() {
		$this->_sql[] = $this->_DESC_OPERATOR;

		return $this;
	}

	/**
	 * Builder: ASC for ORDER BY
	 * @return $this
	 */
	public function asc() {
		$this->_sql[] = $this->_ASC_OPERATOR;

		return $this;
	}

	/**
	 * Builder: INSERT INTO
	 * @param string $table Table name
	 * @return $this
	 */
	public function insert_into($table) {
		$this->_sql[] = $this->_INSERT_OPERATOR . " " . $this->_INTO_OPERATOR . " `" . $this->safe($table) . "`";

		return $this;
	}

	/**
	 * Builder: VALUES
	 * @param array $values Values array
	 * @return $this
	 */
	public function values(array $values) {
		$_rows = array();
		$_values = array();

		foreach($values as $row => $value) {
			if (is_array($row)) {
				$_rows[] = $this->_parseParam($row);
			} else
				$_rows[] = "`" . $this->safe($row) . "`";

			if (is_array($value)) {
				$_values[] = $this->_parseParam($value);
			} else
				$_values[] = $this->string($value);
		}

		$this->_sql[] = "(" . implode(", ", $_rows) . ") " . $this->_VALUES_OPERATOR . " (" . implode(", ", $_values) . ")";

		return $this;
	}

	/**
	 * Builder: LIMIT
	 * @param array $limit Limit array (FIRST, END)
	 * @return $this
	 */
	public function limit(array $limit) {
		$this->_sql[] = $this->_LIMIT_OPERATOR . " " . intval($limit[0]) . ", " . intval($limit[1]) ;

		return $this;
	}

	/**
	 * Builder: UPDATE
	 * @param string $table Table name
	 * @return $this
	 */
	public function update($table) {
		$this->_sql[] = $this->_UPDATE_OPERATOR . " `" . $this->safe($table) . "`";

		return $this;
	}

	/**
	 * Builder: SET
	 * @param array $values Values array
	 * @return $this
	 */
	public function set(array $values) {
		$array = array ();

		foreach($values as $row => $value) {
			if (is_array($row))
				$key = $this->_parseParam($row);
			else
				$key = "`" . $this->safe($row) . "`";

			if (is_array($value)) {
				if (count($value) == 2)
					$value = $this->_parseParam($value[1]);
				elseif (count($value) == 3 || count($value) == 4)
					$value = $this->_parseParam($value[0]) . " " .$this->safe($value[1]) . " " . ((isset($value[3]) && ($value[3] === false)) ? $this->safe($value[2]) : $this->_parseParam($value[2]));
			} else
				$value = $this->string($value);

			$array[] = $key . " = " . $value;
		}

		$this->_sql[] = $this->_SET_OPERATOR . " " . implode(", ", $array);

		return $this;
	}

	/**
	 * Builder: DELETE
	 * @param string $table Table name
	 * @return $this
	 */
	public function delete_from($table) {
		$this->_sql[] = $this->_DELETE_OPERATOR . " FROM `" . $this->safe($table) . "`";

		return $this;
	}

	/**
	 * Get insert row id
	 * @return int
	 */
	abstract function insert_id();

	/**
	 * Safe string
	 * @param $string
	 * @return string
	 */
	abstract function safe($string);

	/**
	 * Get string for query
	 * @param $string
	 * @return string
	 * Safe string for use in sql
	 */
	function string($string) {
		if (is_int($string)) {
			return $string;
		} elseif (is_null($string)) {
			return "NULL";
		} else {
			return "'" . $this->safe($string) . "'";
		}
	}

	/**
	 * Get error
	 * @return mixed|string
	 */
	abstract function getError();

	/**
	 * Get result
	 * @return mixed|false FALSE, if query not success
	 */
	abstract function result();

	/**
	 * Get result num rows
	 * @return int|false FALSE, if query not success
	 */
	abstract function result_num();

	/**
	 * Get result in array
	 * @return array|false FALSE, if query not success
	 */
	abstract function result_array();

	/**
	 * Close connection
	 * @return bool
	 */
	abstract function close();

	/**
	 * Get query string
	 * @return string
	 */
	public function getSql() {
		$sql = implode(" ", $this->_sql);

		$this->_lastSql = $sql;
		$this->_sql = array();

		if ($this->debug)
			var_dump($this->_lastSql);

		return $sql;
	}
}
