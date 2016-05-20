<?php
/**
 * Pagination class
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

namespace harmony\pagination;

use Registry;

class Pagination {
	/**
	 * @var object View object
	 */
	private $_view;

	/**
	 * @var int Num of rows
	 */
	private $_numRows;

	/**
	 * @var int Num of pages
	 */
	private $_numPages;

	/**
	 * @var int Current page
	 */
	private $_currentPage;

	/**
	 * @var string Link prefix
	 */
	private $_prefix;

	/**
	 * @var string Pagination code
	 */
	private $_code;

	/**
	 * @var array Config array
	 */
	private $_config = array (
		"rowsOnPage" => 10,
	);

	/**
	 * Constructor
	 * @param int $numRows Num of rows
	 * @param int $currentPage Current page num
	 * @param string $prefix Link prefix
	 * @param array $customConfig Custom config array
	 */
	public function __construct($numRows, $currentPage,  $prefix, $customConfig = array()) {
		$this->_view = Registry::getInstance()
			->get("View");

		$this->_numRows = intval($numRows);
		$this->_currentPage = $currentPage;
		$this->_prefix = $prefix;

		foreach ($customConfig as $key => $value)
			$this->_config[$key] = $value;

		$numPages = ceil($this->_numRows / $this->_config["rowsOnPage"]);

		if ($numPages > 0)
			$this->_numPages = $numPages;
		else
			$this->_numPages = 1;
				
		if ($currentPage > 0 && $currentPage <= $this->_numPages)
			$this->_currentPage = $currentPage;
		else
			$this->_currentPage = 1;

		$this->_genHtmlCode();
	}

	/**
	 * Get limits for SQL query
	 * @return array Array of limits (min, max)
	 */
	public function getSqlLimits() {
		$minRow = ($this->_currentPage - 1) * $this->_config["rowsOnPage"];
		$maxRow = $this->_config["rowsOnPage"];

		return array (
			$minRow, $maxRow
		);
	}

	/**
	 * Get page row
	 * @param int $num Page num
	 * @return string Page row code
	 */
	private function _getPage($num) {
		$current = ($this->_currentPage == $num);

		return $this->_view->parse("pagination.page", [
			"num" => $num,
			"link" => $this->_prefix . $num,
				
			"current" => $current,
			"not-current" => !$current
		]);
	}

	/**
	 * Get hellip
	 * @return string Hellip
	 */
	private function _getHellip() {
		return $this->_view->parse("pagination.hellip");
	}

	/**
	 * Generate Pagination HTML Code
	 */
	private function _genHtmlCode() {
		if ($this->_numPages > 1) {
			$pages = "";

			$minPage = $this->_currentPage - 1;
			$maxPage = $this->_currentPage + 1;

			$pages .= $this->_getPage(1);

			if ($minPage > 2)
				$pages .= $this->_getHellip();
			else
				$minPage = 2;

			if ($maxPage >= $this->_numPages - 1)
				$maxPage = $this->_numPages - 1;

			for ($i = $minPage; $i <= $maxPage; $i++)
				$pages .= $this->_getPage($i);

			if ($maxPage < $this->_numPages - 1)
				$pages .= $this->_getHellip();

			$pages .= $this->_getPage($this->_numPages, true);

			$back = ($this->_currentPage - 1) > 0;
			$next = ($this->_currentPage + 1) <= $this->_numPages;

			$this->_code = $this->_view->parse("pagination.main", [
				"pages" => $pages,
				"back-link" => $this->_prefix . (($this->_currentPage == 1) ? 1 : ($this->_currentPage - 1)),
				"next-link" => $this->_prefix . (($this->_currentPage == $this->_numPages) ? $this->_currentPage : ($this->_currentPage + 1)),
						
				"back" => $back,
				"not-back" => !$back,
				"next" => $next,
				"not-next" => !$next
			]);
		} else
			$this->_code = "";
	}

	/**
	 * Get num pages
	 * @return int
	 */
	public function getNumPages() {
		return $this->_numPages;
	}

	/**
	 * Get current page
	 * @return int
	 */
	public function getCurrentPage() {
		return $this->_currentPage;
	}

	/**
	 * Get Pagination HTML Code
	 * @return string HTML code
	 */
	public function getHtmlCode() {
		return $this->_code;
	}

	/**
	 * Get pagination string (Pagination code)
	 * @return string HTML code
	 */
	public function __toString() {
		return $this->_code;
	}
}
