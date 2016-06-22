<?php
/**
 * HarmonyCMS Captcha generator class
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

namespace harmony\captcha\generators;

use harmony\http\Sessions;
use harmony\captcha\Generator;

class HarmonyCMS extends Generator {
	private $_width = 100;

	private $_height = 60;

	private $_fontSize = 16;

	private $_lettersAmount = 4;

	private $_backgroundLettersAmount = 30;

	//private $_letters = array("a", "b", "c", "d", "e", "f", "g");
	private $_letters = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

	private $_colors = array("90", "110", "130", "150", "170", "190", "210");

	/**
	 * @var resource Captcha image
	 */
	private $_captcha = null;

	/**
	 * @var string Captcha font path
	 */
	private $_font = LIB . DS . "harmony" . DS . "captcha" . DS . "captcha-font.ttf";

	/**
	 * @var string Generate string
	 */
	private $_string = "";

	private function _genBackground() {
		for ($i = 0; $i < $this->_backgroundLettersAmount; $i++) {
			$color = imagecolorallocatealpha($this->_captcha, rand(0, 255), rand(0, 255), rand(0, 255), 100);
			$letter = $this->_letters[rand(0, sizeof($this->_letters) - 1)];
			$size = rand($this->_fontSize - 2, $this->_fontSize + 2);

			imagettftext($this->_captcha, $size, rand(0,45),
				rand($this->_width * 0.1, $this->_width - $this->_width * 0.1),
				rand($this->_height * 0.2, $this->_height),
				$color, $this->_font, $letter
			);
		}
	}

	private function _genCaptcha() {
		$this->_string = "";

		for ($i = 0; $i < $this->_lettersAmount; $i++) {
			$color = imagecolorallocatealpha($this->_captcha,
				$this->_colors[rand(0, sizeof($this->_colors) - 1)],
				$this->_colors[rand(0, sizeof($this->_colors) - 1)],
				$this->_colors[rand(0, sizeof($this->_colors) - 1)],
				rand(20, 40)
			);

			$letter = $this->_letters[rand(0, sizeof($this->_letters) - 1)];
			$size = rand($this->_fontSize * 2 - 2, $this->_fontSize * 2 + 2);

			$x = ($i + 1) * $this->_fontSize + rand(1, 5);
			$y = (($this->_height * 2) / 3) + rand(0, 5);

			$this->_string .= $letter;

			imagettftext($this->_captcha, $size, rand(0,15), $x, $y, $color, $this->_font, $letter);
		}
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
		$sCaptcha = Sessions::get("captcha");

		if ($sCaptcha !== false) {
			Sessions::set("captcha", "");
			return ($sCaptcha == $captcha);
		}
		else
			return false;
	}

	/**
	 * @return string getCaptcha
	 */
	public function getCaptcha() {
		$captcha_link = SITE_PATH . "core/captcha";
		return <<<HTML
<img id="captcha" src="{$captcha_link}" alt="Captcha">
<a href="#" onclick="getElementById('captcha').src='{$captcha_link}?'+ new Date().getTime(); return false"><span class="fa fa-refresh"></span></a>
HTML;
	}

	/**
	 * Generate captcha
	 * @return $this
	 */
	public function gen() {
		$this->_captcha = imagecreatetruecolor($this->_width, $this->_height);
		$background = imagecolorallocate($this->_captcha, 255, 255, 255);
		imagefill($this->_captcha, 0, 0, $background);

		$this->_genBackground();
		$this->_genCaptcha();

		Sessions::set("captcha", $this->_string);

		return $this;
	}
}

