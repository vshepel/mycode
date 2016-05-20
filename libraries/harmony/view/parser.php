<?php
/**
 * View Parser class
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

namespace harmony\view;

abstract class Parser {
	/**
	 * @var array Global tags
	 */
	protected $_globalTags = array ();

	/**
	 * @var array Main tags
	 */
	protected $_mainTags = array ();

	/**
	 * @var array Stack
	 */
	protected $_stack = array ();

	/**
	 * Add global tag
	 * @param string $tag Tag name
	 * @param string $value Tag value
	 * @return $this
	 */
	public function addGlobalTag($tag, $value) {
		$this->_globalTags[$tag] = $value;
		return $this;
	}

	/**
	 * Add global tags by array
	 * @param array $array
	 * @return $this
	 */
	public function addGlobalTags($array) {
		foreach ($array as $tag => $value)
			$this->_globalTags[$tag] = $value;

		return $this;
	}

	/**
	 * Add main tag
	 * @param string $tag Tag name
	 * @param string $value Tag value
	 * @return $this
	 */
	public function addMainTag($tag, $value) {
		$this->_mainTags[$tag] = $value;
		return $this;
	}

	/**
	 * Add main tags by array
	 * @param array $array
	 * @return $this
	 */
	public function addMainTags($array) {
		foreach ($array as $tag => $value)
			$this->_mainTags[$tag] = $value;

		return $this;
	}

	/**
	 * Add view to stack
	 * @param string $name View name
	 * @param array $tags Tags array
	 * @param array $blocks Blocks array
	 * @return $this
	 */
	public function add($name, $tags = array(), $blocks = array()) {
		$view = $this->parse($name, $tags, $blocks);

		if (isset($this->_stack[$name]))
			$this->_stack[$name] .= $view;
		else
			$this->_stack[$name] = $view;

		return $this;
	}

	public abstract function parse($name, $tags = array());

	public abstract function alert($type, $message);

	public abstract function getAlert($type, $message);

	/**
	 * Get view from stack
	 * @param string $stack Stack name
	 * @return bool|mixed
	 */
	public function get($stack) {
		if (isset($this->_stack[$stack])) {
			$content = $this->_stack[$stack];
			$this->_stack[$stack] = "";
			return $content;
		} else
			return false;
	}

	public abstract function render($stack = null, $mainView = null);
	
	public abstract function responseRender($response);

	/**
	 * Get content in div
	 * @param string $content Div content
	 * @param string $id Div id
	 * @param string $class Div class
	 * @return string
	 */
	public function toDiv($content, $id = "", $class = "") {
		$id = empty($id) ? "" : " id=\"{$id}\"";
		$class = empty($class) ? "" : " class=\"{$class}\"";
		return "<div{$id}{$class}>{$content}</div>";
	}

	/**
	 * Render object or array in JSON
	 * @param object|array $object
	 */
	public function jsonRender($object) {
		echo json_encode($object);
	}
}
