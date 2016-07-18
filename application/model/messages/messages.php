<?php
/**
 * Messages Model
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

namespace model\messages;

use AppModel;
use Response;

use harmony\pagination\Pagination;
use harmony\strings\StringFilters;
use harmony\strings\Strings;

class Messages extends AppModel {
	/**
	 * Get messages list
	 * @param string $type Type: inbox, outbox
	 * @param int $page Page num
	 * @return Response
	 */
	public function listPage($type, $page = 1) {
		if ($type != "outbox") {
			$type = "inbox";
		}

		$this->_core
			->addBreadcrumbs($this->_lang->get("messages", "moduleName"), "messages")
			->addBreadcrumbs($this->_lang->get("messages", "{$type}.moduleName"), "messages/" . $type);

		// Access denied
		if (!$this->_user->hasPermission("messages.list")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$page = intval($page);

		$num = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "messages")
			->where("user", "=", $this->_user->get("id"))
			->and_where(($type == "outbox" ? "from" : "to"), "=", $this->_user->get("id"))
			->result_array();

		// Database error
		if ($num === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$num = $num[0][0];
		$pagination = new Pagination($num, $page, SITE_PATH . "messages/{$type}/page/", $this->_config->get("messages", "list.customPagination", array()));

		// Messages list query
		$array = $this->_db
			->select(array(
				"id", "from", "to", "reply", "topic", "message", "readed",
				array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false)
			))
			->from(DBPREFIX . "messages")
			->where("user", "=", $this->_user->get("id"))
			->and_where(($type == "outbox" ? "from" : "to"), "=", $this->_user->get("id"))
			->order_by($this->_config->get("messages", "list.orderBy", "id"),
				$this->_config->get("messages", "list.order", "desc")
			)
			->limit($pagination->getSqlLimits())
			->result_array();

		// Database error
		if ($array === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		// Make messages list
		$rows = [];

		foreach ($array as $row) {
			$message = mb_substr($row["message"], 0, $this->_config->get("messages", "list.messageLength", 32), "UTF-8");
			if (Strings::length($message) < Strings::length($row["message"])) $message .= "...";

			// Tags
			$rows[] = [
				"id" => $row["id"],
				"url" => SITE_PATH . "messages/" . $row["id"],
				"topic" => $row["topic"],
				"message" => $message,
							
				"from-id" => $this->_user->getUserLogin($row["from"]),
				"from-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["from"]),
				"from-login" => $this->_user->getUserLogin($row["from"]),
				"from-name" => $this->_user->getUserName($row["from"]),
				"from-avatar-link" => $this->_user->getAvatarLink($this->_user->getUser($row["from"], "avatar")),
				"from-online" => $this->_user->checkOnline($this->_user->getUser($row["from"], "active")),
				"from-offline" => !$this->_user->checkOnline($this->_user->getUser($row["from"], "active")),
							
				"to-id" => $this->_user->getUserLogin($row["to"]),
				"to-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["to"]),
				"to-login" => $this->_user->getUserLogin($row["to"]),
				"to-name" => $this->_user->getUserName($row["to"]),
				"to-avatar-link" => $this->_user->getAvatarLink($this->_user->getUser($row["to"], "avatar")),
				"to-online" => $this->_user->checkOnline($this->_user->getUser($row["to"], "active")),
				"to-offline" => !$this->_user->checkOnline($this->_user->getUser($row["to"], "active")),
							
				"date" => $this->_core->getDate($row["timestamp"]),
				"time" => $this->_core->getTime($row["timestamp"]),

				"remove" => $this->_user->hasPermission("messages.remove"),
				"remove-link" => SITE_PATH . "messages/remove/" . $row["id"],
							
				"readed" => ($row["readed"] > 0),
				"not-readed" => ($row["readed"]  == 0)
			];
		}

		// New messages num
		$newpm_num = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "messages")
			->where("user", "=", $this->_user->get("id"))
			->and_where("to", "=", $this->_user->get("id"))
			->and_where("readed", "=", 0)
			->result_array();

		$response = new Response();
		$response->view = "messages.list";
		$response->tags = array(
			"num" => $num,
			"new-count" => isset($newpm_num[0][0]) ? $newpm_num[0][0] : 0,
			"rows" => $rows,
			"type" => $type,
			"pagination" => $pagination
		);

		return $response;
	}
	
	/**
	 * Get message page
	 * @param int $id Message ID
	 * @return Response
	 * @throws \Exception
	 */
	public function page($id) {
		$this->_core->addBreadcrumbs($this->_lang->get("messages", "moduleName"), "messages");

		// Access denied
		if (!$this->_user->hasPermission("messages.read")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$id = intval($id);

		$array = $this->_db
			->select(array(
				"id", "from", "to", "reply", "topic", "message", "readed",
				array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false)
			))
			->from(DBPREFIX . "messages")
			->where("id", "=", $id)
			->and_where("user", "=", $this->_user->get("id"))
			->result_array();

		// Database error
		if ($array === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		} elseif (!isset($array[0])) {
			return new Response(2, "danger", $this->_lang->get("messages", "read.notFound"));
		}

		$row = $array[0];
				
		// Mark message for readed
		$this->_db
			->update(DBPREFIX . "messages")
			->set(array (
				"readed" => 1
			))
			->where("id", "=", $row["id"])
			->result();

		$message = Strings::lineWrap($row["message"]);
		$message = $this->_user->replaceProfileLink($message);

		$response = new Response();
		$response->view = "messages.read";
		$response->tags = [
			"id" => $row["id"],
			"url" => SITE_PATH . "messages/" . $row["id"],
			"topic" => $row["topic"],
			"message" => $message,
							
			"from-id" => $this->_user->getUserLogin($row["from"]),
			"from-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["from"]),
			"from-login" => $this->_user->getUserLogin($row["from"]),
			"from-name" => $this->_user->getUserName($row["from"]),
			"from-avatar-link" => $this->_user->getAvatarLink($this->_user->getUser($row["from"], "avatar")),
			"from-online" => $this->_user->checkOnline($this->_user->getUser($row["from"], "active")),
			"from-offline" => !$this->_user->checkOnline($this->_user->getUser($row["from"], "active")),
							
			"to-id" => $this->_user->getUserLogin($row["to"]),
			"to-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["to"]),
			"to-login" => $this->_user->getUserLogin($row["to"]),
			"to-name" => $this->_user->getUserName($row["from"]),
			"to-avatar-link" => $this->_user->getAvatarLink($this->_user->getUser($row["to"], "avatar")),
			"to-online" => $this->_user->checkOnline($this->_user->getUser($row["to"], "active")),
			"to-offline" => !$this->_user->checkOnline($this->_user->getUser($row["to"], "active")),
					
			"date" => $this->_core->getDate($row["timestamp"]),
			"time" => $this->_core->getTime($row["timestamp"]),

			"remove" => $this->_user->hasPermission("messages.remove"),
			"remove-link" => SITE_PATH . "messages/remove/" . $row["id"]
		];

		return $response;
	}

	 private $_sendPageTags = array(
	 	 "user" => "",
	 	 "topic" => "",
	 	 "message" => ""
	 );
	 
	/**
	 * Send Message page
	 * @param string $user = null User Login
	 * @return Response
	 */
	public function sendPage($user = null) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("messages", "moduleName"), "messages")
			->addBreadcrumbs($this->_lang->get("messages", "send.moduleName"), "messages/send");

		// Access denied
		if (!$this->_user->hasPermission("messages.send")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"), "messages/send");
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}
		
		if ($user != null) {
			$this->_sendPageTags["user"] = $user;
		}

		$response = new Response();
		$response->view = "messages.send";
		$response->tags = $this->_sendPageTags;
		return $response;
	}
	
	/**
	 * Send message
	 * @param string $user User Login
	 * @param string $topic Message topic
	 * @param string $message Message text
	 * @param int $reply = 0 Message text
	 * @return Response
	 */
	public function send($user, $topic, $message, $reply = 0) {
		// Access denied
		if (!$this->_user->hasPermission("messages.send")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$user = StringFilters::filterHtmlTags($user);
		$topic = StringFilters::filterHtmlTags($topic);
		$message = StringFilters::filterHtmlTags($message);
		$length = Strings::length($message);
		
		$this->_sendPageTags = array(
			"user" => $user,
			"topic" => $topic,
			"message" => $message
		);
		
		$interval = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "messages")
			->where("from", "=", $this->_user->get("id"))
			->and_where("UNIX_TIMESTAMP(CURRENT_TIMESTAMP)", "<", "UNIX_TIMESTAMP(`timestamp`) + " . $this->_config->get("messages", "send.interval", 30), false, false)
			->result_array();

		if ($interval === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		} elseif ($interval[0][0] > 0) {
			return new Response(2, "danger", $this->_lang->get("messages", "send.smallInterval"));
		} elseif (empty($user) || empty($topic) || empty($message)) {
			return new Response(4, "warning", $this->_lang->get("core", "emptyFields"));
		} elseif (Strings::length($topic) > 32) {
			return new Response(4, "warning", $this->_lang->get("core", "longFields"));
		} elseif ($length < $this->_config->get("messages", "send.minLength", 5)) {
			return new Response(5, "danger", $this->_lang->get("messages", "send.tooShort"));
		} elseif ($length > $this->_config->get("messages", "send.maxLength", 300)) {
			return new Response(6, "danger", $this->_lang->get("messages", "send.tooLong"));
		}

		$query = $this->_db
			->select(["id"])
			->from(DBPREFIX . "user_profiles")
			->where("login", "=", $user)
			->result_array();

		if (!isset($query[0][0])) {
			return new Response(6, "danger", $this->_lang->get("messages", "send.notExists"));
		}

		$from =  $this->_user->get("id");
		$to = $query[0][0];

		// Add to database sender
		$res1 = ($from == $to) ? true : $this->_db
			->insert_into(DBPREFIX . "messages")
			->values(array (
				"user" => $from,
				"from" => $from,
				"to" => $to,
				"topic" => $topic,
				"message" => $message,
				"reply" => $reply,
				"readed" => 1
			))
			->result();
				
		// Add to database reciever
		$res2 = $this->_db
			->insert_into(DBPREFIX . "messages")
			->values(array (
				"user" => $to,
				"from" => $from,
				"to" => $to,
				"topic" => $topic,
				"message" => $message,
				"reply" => $reply,
				"readed" => 0
			))
			->result();

		if ($res1 === false || $res2 == false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$this->_cache->remove("user", "groups");

		// Sent notification reciever
		$this->_registry
			->get("Notifications")
			->add($to, "info", "[messages:newMessage]", "<b>{$this->_user->get("login")}</b> [messages:newMessageBody]",
				SITE_PATH . "messages/" . $this->_db->insert_id()
			);

		return new Response(0, "success", $this->_lang->get("messages", "send.success"));
	}

	/**
	 * Remove message page
	 * @param int $id Group ID
	 * @return Response
	 */
	public function removePage($id) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("messages", "moduleName"), "messages")
			->addBreadcrumbs($this->_lang->get("messages", "remove.moduleName"), "messages/remove/" . $id);

		// Access denied
		if (!$this->_user->hasPermission("messages.remove")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"), "messages/remove/" . $id);
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$id = intval($id);

		$query = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "messages")
			->where("id", "=", $id)
			->and_where("user", "=", $this->_user->get("id"))
			->result_array();

		if (isset($query[0]) && $query[0][0] == 0) {
			return new Response(2, "danger", $this->_lang->get("messages", "remove.notExist"));
		}

		$response = new Response();
		$response->view = "messages.remove";
		$response->tags["id"] = $id;
		return $response;
	}

	/**
	 * Remove message
	 * @param int $id Group ID
	 * @return Response
	 */
	public function remove($id) {
		// Access denied
		if (!$this->_user->hasPermission("messages.remove")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$id = intval($id);

		$query = $this->_db
			->delete_from(DBPREFIX . "messages")
			->where("id", "=", $id)
			->and_where("user", "=", $this->_user->get("id"))
			->result();

		// Database error
		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		return new Response(0, "success");
	}
}
