<?php
/**
 * HTTP static class
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

namespace harmony\http;

class HTTP {
	/**
	 * @var string Client IP address
	 */
	private static $_ip = null;
	
	/**
	 * @var string User Agent
	 */
	private static $_userAgent = null;
	
	/**
	 * Get Client Referer link
	 * @return string
	 */
	public static function getReferer() {
		return isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "";
	}

	/**
	 * Get Client IP Address
	 * @return string|false Client IP Address (FALSE, if can"t get ip-address)
	 */
	public static function getIp() {
		if (self::$_ip === null) {
			if (!empty($_SERVER["REMOTE_ADDR"]))
				self::$_ip = $_SERVER["REMOTE_ADDR"];
			elseif (!empty($_SERVER["HTTP_CLIENT_IP"]))
				self::$_ip = $_SERVER["HTTP_CLIENT_IP"];
			elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
				self::$_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			else
				self::$_ip = false;
		}

		return self::$_ip;
	}

	/**
	 * Get User Agent
	 * @return string
	 */
	public static function getUserAgent() {
		if (self::$_userAgent === null) {
			if (isset($_SERVER["HTTP_USER_AGENT"]))
				self::$_userAgent = $_SERVER["HTTP_USER_AGENT"];
			else
				self::$_userAgent = "";
		}

		return self::$_userAgent;
	}

	/**
	 * Get Browser by User Agent
	 * @param string $userAgent = null User agent (If null, then using HTTP User-Agent header)
	 * @return string
	 */
	public static function getUserAgentBrowser($userAgent = null) {
		if ($userAgent === null && isset($_SERVER["HTTP_USER_AGENT"]))
			$userAgent = self::getUserAgent();

		$browsers = array (
			"Firefox" => "(Firefox)",
			"Chromium" => "(Chromium)",
			"Chrome" => "(Chrome)",
			"Safari" => "(Safari)",
			"Opera" => "(Opera)",
			"Internet Explorer" => "(MSIE)"
		);

		foreach ($browsers as $browser => $pattern)
			if (preg_match("/$pattern/i", $userAgent))
				return $browser;

		return "Unknown";
	}

	/**
	 * Get OS by User Agent
	 * @param string $userAgent = null User agent (If null, then using HTTP User-Agent header)
	 * @return string OS name
	 */
	public static function getUserAgentOS($userAgent = null) {
		if ($userAgent === null && isset($_SERVER["HTTP_USER_AGENT"]))
			$userAgent = self::getUserAgent();

		$oses = array (
			"iPhone" => "(iPhone)",
			"Android" => "(Android)",
			"Windows 3.11" => "Win16",
			"Windows 95" => "(Windows 95)|(Win95)|(Windows_95)",
			"Windows 98" => "(Windows 98)|(Win98)",
			"Windows 2000" => "(Windows NT 5.0)|(Windows 2000)",
			"Windows XP" => "(Windows NT 5.1)|(Windows XP)",
			"Windows 2003" => "(Windows NT 5.2)",
			"Windows Vista" => "(Windows NT 6.0)|(Windows Vista)",
			"Windows 7" => "(Windows NT 6.1)|(Windows 7)",
			"Windows 8" => "(Windows NT 6.2)|(Windows 8)",
			"Windows 8.1" => "(Windows NT 6.3)|(Windows 8.1)",
			"Windows 10" => "(Windows NT 10)|(Windows 10)",
			"Windows NT 4.0" => "(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)",
			"Windows ME" => "(Windows ME)",
			"Windows" => "(Windows)",
			"OpenBSD" => "(OpenBSD)",
			"SunOS" => "(SunOS)",
			"Linux" => "(Linux)|(X11)",
			"Mac OS" => "(Mac OS)",
			"Macintosh" => "(Mac_PowerPC)|(Macintosh)",
			"QNX" => "(QNX)",
			"BeOS" => "(BeOS)",
			"OS/2" => "(OS\\/2)",
			"Search Bot" => "(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp\\/cat)|(msnbot)|(ia_archiver)"
		);

		foreach ($oses as $os => $pattern)
			if (preg_match("/$pattern/i", $userAgent))
				return $os;

		return "Unknown";
	}

	/**
	 * Redirect to url
	 * @param string $url URL to redirect
	 */
	public static function redirect($url) {
		header("Location: " . $url);
		echo "Redirect to: " . $url;
	}

	/**
	 * Update page
	 */
	public static function update() {
		self::redirect($_SERVER["REQUEST_URI"]);
	}
}
