<?php
/**
 * Captcha Generator class
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

namespace harmony\captcha;

use Registry;

use harmony\http\Sessions;

abstract class Generator {
	/**
	 * @var object Sessions object
	 */
	protected $_sessions;

	/**
	 * @var resource Captcha image
	 */
	protected $_captcha = null;

	/**
	 * @var string Captcha font path
	 */
	protected $_font = null;
	
	/**
	 * Set string
	 * @param string $string String
	 * @return $this
	 */
	public function setFont($font) {
		$this->_font = $font;
	}

	/**
	 * Get Captcha URL
	 * @return string
	 */
	public function getCaptchaLink() {
		return SITE_PATH . "core/captcha";
	}

	/**
	 * Set string
	 * @param string $string String
	 */
	public function setString($string) {
		Sessions::set("captcha", $string);
	}

	/**
	 * Get string
	 * @return string|false
	 */
	public function getString() {
		return Sessions::get("captcha");
	}

	/**
	 * @return resource Get image
	 */
	public function getImage() {
		return $this->_captcha;
	}

	/**
	 * Render image
	 */
	public function out() {
		header("Content-type: image/gif");
		imagegif($this->_captcha);
		exit;
	}

	/**
	 * Check captcha to correct
	 * @param string $captcha Captcha string
	 * @return bool
	 */
	public function isCorrect($captcha) {
		$sCaptcha = $this->getString();

		if ($sCaptcha !== false) {
			$this->setString("");
			return ($sCaptcha == $captcha);
		}
		else
			return false;
	}

	/**
	 * Gen captcha image
	 * @return $this
	 */
	abstract function gen();
}
