<?php
/**
 * Notifications class
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

namespace model\user;

use Exception;
use AppModel;
use Response;

class Notifications extends AppModel {
	/**
	 * Send notification to user
	 * @param int $user User ID
	 * @param int $type Type
	 * @param string $title Title
	 * @param string $text Text
	 * @param string $link = "" Link
	 * @return $this
	 * @throws Exception
	 */
	public function add($user, $type, $title, $text, $link = "") {
		$add = $this->_db
			->insert_into(DBPREFIX . "user_notifications")
			->values(array (
				"user" => $user,
				"type" => $type,
				"title" => $title,
				"text" => $text,
				"link" => $link
			))
			->result();

		if ($add === false) {
			throw new Exception("Notification Add Error: " . $this->_db->getError());
		}

		return $this;
	}

	/**
	 * Get notifications by User ID
	 * @param int $user User ID
	 * @return Response
	 * @throws Exception
	 */
	public function get($user = null) {
		if ($user === null) {
			$user = $this->_user->get("id");
		}

		$query = $this->_db
			->select(array (
				"id", "user", "type", "title", "text", "link", array ("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false)
			))
			->from(DBPREFIX . "user_notifications")
			->where("user", "=", $user)
			->order_by("timestamp")->desc()
			->result_array();

		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$lastdate = "";
		$rows = [];

		foreach ($query as $row) {
			$date = $this->_core->getDate($row["timestamp"]);
			$time = $this->_core->getTime($row["timestamp"]);

			$rows[] = [
				"id" => $row["id"],
				"user" => $this->_user->getUserLogin($row["user"]),
				"profile-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["user"]),
				"link" => SITE_PATH . "user/notifications/" . $row["id"],

				"iso-datetime" => $this->_core->getISODateTime($row["timestamp"]),
				"date" => $date,
				"time" => $time,

				"title" => $this->_lang->parseString($row["title"]),
				"type" => $row["type"],
				"text" => $this->_lang->parseString($row["text"]),
				"show-date" => ($lastdate != $date)
			];
				
			$lastdate = $date;
		}

		$response = new Response();
		$response->tags = [
			"num" => count($rows),
			"rows" => $rows
		];
			
		$response->tags["page-rows"] = $this->_view->parse("user.notifications", $response->tags);

		return $response;
	}

	/**
	 * Get link of message
	 * @param int $id Notification ID
	 * @param int $user = null User ID
	 * @return string|bool Link (false if link not found)
	 * @throws Exception
	 */
	public function getLink($id, $user = null) {
		if ($user === null) {
			$user = $this->_user->get("id");
		}

		$query = $this->_db
			->select(array(
				"id", "link"
			))
			->from(DBPREFIX . "user_notifications")
			->where("user", "=", $user)
			->and_where("id", "=", $id)
			->result_array();

		if ($query === false) {
			throw new Exception("Notification Get Error: " . $this->_db->getError());
		}

		if (!isset($query[0]["link"])) {
			return false;
		}

		return $query[0]["link"];
	}

	/**
	 * Remove Notification item by ID
	 * @param int $id Item ID
	 * @param bool $checkUser Remove notification from this user
	 * @throws Exception
	 */
	public function removeById($id, $checkUser = true) {
		$id = intval($id);

		$this->_db
			->delete_from(DBPREFIX . "user_notifications")
			->where("id", "=", $id);

		if ($checkUser) {
			$this->_db->and_where("user", "=", $this->_user->get("id"));
		}

		$remove = $this->_db->result();

		if ($remove === false) {
			throw new Exception("Notifications Remove Error: " . $this->_db->getError());
		}
	}

	/**
	 * Clear Notifications
	 * @param int $id User ID
	 * @throws Exception
	 */
	public function clear($id) {
		$id = intval($id);
		$remove = $this->_db
			->delete_from(DBPREFIX . "user_notifications")
			->where("user", "=", $id)
			->result();

		if ($remove === false) {
			throw new Exception("Notifications Remove Error: " . $this->_db->getError());
		}
	}
}
