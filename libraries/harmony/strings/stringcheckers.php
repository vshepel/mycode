<?php
/**
 * String Checkers static class
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

namespace harmony\strings;

class StringCheckers {
	/**
	 * Check email for valid
	 * @param string $email Email
	 * @return bool Is valid?
	 */
	public static function isValidEmail($email) {
		return Strings::length($email) > 32 ? false : ((boolean) filter_var($email, FILTER_VALIDATE_EMAIL));
	}

	/**
	 * Check login for valid
	 * @param string $login Login
	 * @return bool Is valid?
	 */
	public static function isValidLogin($login) {
		return Strings::length($login) > 32 ? false : ((boolean) preg_match("/^[A-Za-z0-9._-]/i", $login));
	}

	/**
	 * Check name for valid
	 * @param string $name Name
	 * @return bool Is valid?
	 */
	public static function isValidName($name) {
		return Strings::length($name) > 32 ? false : ((boolean) (StringFilters::filterHtmlTags($name) == $name));
	}
}
