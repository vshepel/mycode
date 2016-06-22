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

abstract class Generator {
	/**
	 * Render image
	 */
	abstract function out();

	/**
	 * Check captcha for correct
	 * @param string $captcha Captcha string
	 * @return bool
	 */
	abstract function isCorrect($captcha);

	/**
	 * @return string Get Captcha code
	 */
	abstract function getCaptcha();

	/**
	 * Generate captcha
	 * @return $this
	 */
	abstract function gen();
}
