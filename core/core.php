<?php
/**
 * Core class
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

class Core {
	/**
	 * @var object Config object
	 */
	private $_config;

	/**
	 * @var object Lang object
	 */
	private $_lang;

	/**
	 * @var string Custom page title
	 */
	private $_title = "";

	/**
	 * @var array Meta tags
	 */
	private $_meta = array ();

	/**
	 * @var array Link items
	 */
	private $_link = array ();

	/**
	 * @var array Script items
	 */
	private $_script = array ();

	/**
	 * @var string Side type
	 */
	private $_type = "";

	/**
	 * @var array Breadcrumbs array
	 */
	private $_breadcrumbs = array ();
	
	private $_formatDate = "d.m.Y";
	private $_formatTime = "H:i";

	/**
	 * Constructor
	 * @throws \Exception
	 */
	public function __construct() {
		$registry = Registry::getInstance();
		$this->_config = $registry->get("Config");
		$this->_lang = $registry->get("Lang");

		$this->_formatDate = $this->_config->get("core", "format.date", $this->_formatDate);
		$this->_formatTime = $this->_config->get("core", "format.time", $this->_formatTime);
	}

	/**
	 * Set side type
	 * @param string $type Side type
	 * @return $this
	 */
	public function setType($type) {
		$this->_type = $type;
		return $this;
	}

	/**
	 * Set custom page title
	 * @param string $title Page title
	 * @return $this
	 */
	public function setTitle($title) {
		$this->_title = $title;
		return $this;
	}

	/**
	 * Get page title
	 * @return string Page title
	 */
	public function getTitle() {
		if (empty($this->_title)) {
			$end = end($this->_breadcrumbs);
			return $end[0];
		} else
			return $this->_title;
	}

	/**
	 * Add meta item
	 * @param array $meta Meta item
	 * @return $this
	 */
	public function addMeta($meta) {
		$this->_meta[] = $meta;
		return $this;
	}

	/**
	 * Add meta item from Array
	 * @param array $array Array of meta items
	 * @return $this
	 */
	public function addMetaArray($array) {
		foreach ($array as $meta) $this->addMeta($meta);
		return $this;
	}

	/**
	 * Remove meta by vars array
	 * @param array $vars
	 * @return $this
	 */
	public function removeMeta($vars) {
		foreach ($this->_meta as &$meta) {
			$fl = false;

			foreach ($vars as $name => $value)
				if (isset($meta[$name]) && $meta[$name] == $value)
					$fl = true;
				else {
					$fl = false;
					break;
				}

			if ($fl === true) $meta = null;
		}

		return $this;
	}

	/**
	 * Get meta items string
	 * @return string Meta items string
	 */
	public function getMeta() {
		$html = "";

		foreach ($this->_meta as $meta) {
			if ($meta !== null) {
				$con = array();

				foreach ($meta as $name => $content)
					$con[] = "{$name}=\"{$content}\"";

				$con = implode(' ', $con);

				$html .= "<meta {$con}>";
			}
		}

		return $html;
	}

	/**
	 * Add Link item
	 * @param array $link Link item
	 * @return $this
	 */
	public function addLink($link) {
		$this->_link[] = $link;
		return $this;
	}
	
	/**
	 * Add CSS item
	 * @param string|array $link Link for css file (or array of css)
	 * @return $this
	 */
	public function addCSS($link) {
		if (is_array($link)) {
			foreach ($link as $href) {
				$this->_link[] = [
					"rel" => "stylesheet",
					"href" => $href
				];
			}
		} else
			$this->_link[] = [
				"rel" => "stylesheet",
				"href" => $link
			];
			
		return $this;
	}

	/**
	 * Add Link items from Array
	 * @param array $array Array of Link items
	 * @return $this
	 */
	public function addLinkArray(array $array) {
		foreach ($array as $link) $this->addLink($link);
		return $this;
	}

	/**
	 * Remove link by vars array
	 * @param array $vars
	 * @return $this
	 */
	public function removeLink($vars) {
		foreach ($this->_link as &$link) {
			$fl = false;

			foreach ($vars as $name => $value)
				if (isset($link[$name]) && $link[$name] == $value)
					$fl = true;
				else {
					$fl = false;
					break;
				}

			if ($fl === true) $link = null;
		}

		return $this;
	}

	/**
	 * Get Link items string
	 * @return string Link items string
	 */
	public function getLink() {
		$html = "";

		foreach ($this->_link as $link) {
			$con = array ();

			foreach ($link as $name => $content)
				$con[] = "{$name}=\"{$content}\"";

			$con = implode(' ', $con);

			$html .= "<link {$con}>";
		}

		return $html;
	}

	/**
	 * Add Script item
	 * @param string $script Script item
	 * @return $this
	 * @throws \Exception
	 */
	public function addScript($script) {
		$this->_script[] = $script;
		return $this;
	}
	
	/**
	 * Add JS item
	 * @param string|array $link Link for css file (or array of css)
	 * @return $this
	 */
	public function addJS($script) {
		if (is_array($script)) {
			foreach ($script as $src) {
				$this->_script[] = [
					"type" => "text/javascript",
					"src" => $src
				];
			}
		} else
			$this->_script[] = [
				"type" => "text/javascript",
				"src" => $script
			];
			
		return $this;
	}

	/**
	 * Add Script items from array
	 * @param array $array Array of JS items
	 * @return $this
	 */
	public function addScriptArray(array $array) {
		foreach ($array as $script) {
			$this->addScript($script);
		}

		return $this;
	}

	/**
	 * Remove script by vars array
	 * @param array $vars
	 * @return $this
	 */
	public function removeScript($vars) {
		foreach ($this->_meta as &$script) {
			$fl = false;

			foreach ($vars as $name => $value)
				if (isset($script[$name]) && $script[$name] == $value)
					$fl = true;
				else {
					$fl = false;
					break;
				}

			if ($fl === true)
				$script = null;
		}

		return $this;
	}

	/**
	 * Get JS items string
	 * @return string JS items string
	 */
	public function getScript() {
		$html = "";

		foreach ($this->_script as $script) {
			$con = array ();

			foreach ($script as $name => $content)
				$con[] = "{$name}=\"{$content}\"";

			$con = implode(' ', $con);

			$html .= "<script {$con}></script>";
		}

		return $html;
	}

	/**
	 * Add breadcrumbs
	 * @param string $name Breadcrumbs name
	 * @param string $link Breadcrumbs link
	 * @return $this
	 */
	public function addBreadcrumbs($name, $link = null) {
		$this->_breadcrumbs[] = array (
			$name, $link
		);

		return $this;
	}

	/**
	 * Add breadcrumbs from array
	 * @param array $array Array of breadcrumbs
	 * @return $this
	 */
	public function addBreadcrumbsArray(array $array) {
		foreach ($array as $name => $link)
			$this->addBreadcrumbs($name, $link);

		return $this;
	}

	/**
	 * Get breadcrumbs string
	 * @return string Breadcrumbs link
	 */
	public function getBreadcrumbs() {
		$path = ($this->_type == BACKEND) ? ADMIN_PATH : SITE_PATH;
		$html = "";

		foreach ($this->_breadcrumbs as $row) {
			$end = ($row == end($this->_breadcrumbs));

			$active = $end ? " class=\"active\"" : "";
			$name = ($row[1] === null || $end === true) ? $row[0] : "<a href=\"{$path}{$row[1]}\">{$row[0]}</a>";
			$html .= "<li{$active}>{$name}</li>";
		}

		return $html;
	}
	
	 /**
	 * Set time zone
	 * @param int $zone Time zone correction
	 * @return $this
	 */
	public function setTimeZone($zone) {
		date_default_timezone_set("UTC");
		$this->_correction = 60 * 60 * $zone;
		return $this;
	}

	/**
	 * Get date by timestamp in user format
	 * @param int $timestamp Timestamp
	 * @param string $format User format
	 * @return string User formatted date
	 */
	public function getDateInFormat($timestamp, $format) {
		return date($format, $timestamp);
	}

	/**
	 * Get date by timestamp in default format
	 * @param int $timestamp Timestamp
	 * @param bool $smart Smart time?
	 * @return string Formatted date
	 */
	public function getDate($timestamp, $smart = true) {
		if ($smart && $this->_config->get("core", "smartDate", true))
			if (date("z-Y", time() + $this->_correction) == date("z-Y", $timestamp + $this->_correction))
				return $this->_lang->get("core", "smartDate.today");
			else if (date("z-Y", time() + $this->_correction) == date("z-Y", strtotime("+1 day", $timestamp + $this->_correction)))
				return $this->_lang->get("core", "smartDate.yesterday");
			else
				return date($this->_formatDate, $timestamp + $this->_correction);
		else
			return date($this->_formatDate, $timestamp + $this->_correction);
	}

	/**
	 * Get time by timestamp in format
	 * @param int $timestamp Timestamp
	 * @return string Formatted time
	 */
	public function getTime($timestamp) {
		return date($this->_formatTime, $timestamp + $this->_correction);
	}

	/**
	 * Get DateTime in ISO-format by timestamp
	 * @param int $timestamp Timestamp
	 * @return string ISO-formatted DateTime
	 */
	public function getISODatetime($timestamp) {
		return date("c", $timestamp + $this->_correction);
	}
}
