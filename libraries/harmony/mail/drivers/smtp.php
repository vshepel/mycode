<?php
/**
 * Mail Driver SMTP class
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

namespace harmony\mail\drivers;

use harmony\mail\Driver;

class SMTP extends Driver {
	private $_error = "";

	private $_socket = null;

	private function _check($expected_response) {
		$server_response = "";

		while (substr($server_response, 3, 1) != " ") {
			if (!($server_response = fgets($this->_socket, 256))) {
				$this->_error = "Couldn't get mail server response codes. Please contact the forum administrator.";
				return false;
			}
		}

		if (!(substr($server_response, 0, 3) == $expected_response)) {
			$this->_error = "Unable to send e-mail. Please contact the forum administrator with the following error message reported by the SMTP server: " . $server_response;
			return false;
		}

		return true;
	}

	private function _query($query, $expected_response = null) {
		fwrite($this->_socket, $query . "\r\n");

		if ($expected_response !== null)
			return $this->_check($expected_response);
		else
			return true;
	}

	public function send($to, $subject, $message, $headers = "") {
		// Variable
		$recipients = explode(",", $to);
		$user = $this->_config["user"];
		$pass = $this->_config["password"];
		$smtp_host = $this->_config["host"];
		$smtp_port = $this->_config["port"];

		// Connect to server
		if (!($this->_socket = fsockopen($smtp_host, $smtp_port, $errno, $errstr, 15))) {
			$this->_error = "Couldn't connect to SMTP host {$smtp_host} ({$errno}) ({$errstr})";
			return false;
		}

		if (!$this->_check(220))
			return false;

		// Main queries
		$query_list = array (
			array ("EHLO {$smtp_host}", 250),
			array ("AUTH LOGIN", 334),
			array (base64_encode($user) . "", 334),
			array (base64_encode($pass) . "", 235),
			array ("MAIL FROM: <{$user}>", 250)
		);

		foreach ($query_list as $query)
			if (!$this->_query($query[0], $query[1]))
				return false;

		// To query
		foreach ($recipients as $email)
			if (!$this->_query("RCPT TO: <{$email}>", 250))
				return false;

		// Send query
		$query_list = array (
			array ("DATA", 354),
			array ("Subject: {$subject}"),
			array ("From: {$this->_config["from"]} <{$user}>"),
			array ("To: <" . implode(">, <", $recipients) . ">"),
			array ($headers),
			array (""),
			array ($message),
			array (".")
		);

		foreach ($query_list as $query)
			if (!$this->_query($query[0], isset($query[1]) ? $query[1] : null))
				return false;

		// Close connection
		$this->_query("QUIT");
		fclose($this->_socket);

		return true;
	}

	public function getError() {
		return str_replace(["\n", "\r"], "", $this->_error);
	}
}
