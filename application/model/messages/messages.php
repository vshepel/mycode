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
		$response = new Response();

		$page = intval($page);
		
		if ($type != "outbox")
			$type = "inbox";

		$this->_core
			->addBreadcrumbs($this->_lang->get("messages", "moduleName"), "messages")
			->addBreadcrumbs($this->_lang->get("messages", "{$type}.moduleName"), "messages/" . $type);

		/**
		 * Check permissions for messages list
		 */
		if ($this->_user->hasPermission("messages.list")) {
			$num = $this->_db
				->select("count(*)")
				->from(DBPREFIX . "messages")
				->where("user", "=", $this->_user->get("id"))
				->and_where(($type == "outbox" ? "from" : "to"), "=", $this->_user->get("id"))
				->result_array();

			/**
			 * Database error
			 */
			if ($num === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$num = $num[0][0];
				$pagination = new Pagination($num, $page, SITE_PATH . "messages/{$type}/page/", $this->_config->get("messages", "list.customPagination", array()));

				/**
				 * Messages list query
				 */
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
					)->limit($pagination->getSqlLimits())
					->result_array();

				/**
				 * Database error
				 */
				if ($array === false) {
					$response->code = 1;
					$response->type = "danger";
					$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
				}

				/**
				 * Make messages list
				 */
				else {
					$rows = [];

					foreach ($array as $row) {
						$message = mb_substr($row["message"], 0, $this->_config->get("messages", "list.messageLength", 32), "UTF-8");
						if (Strings::length($message) < Strings::length($row["message"])) $message .= "...";

						/**
						 * Tags
						 */
						$rows[] = [
							"id" => $row["id"],
							"url" => SITE_PATH . "messages/" . $row["id"],
							"topic" => $row["topic"],
							"message" => $message,
							
							"from-id" => $this->_user->getUserLogin($row["from"]),
							"from-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["from"]),
							"from-login" => $this->_user->getUserLogin($row["from"]),
							
							"to-id" => $this->_user->getUserLogin($row["to"]),
							"to-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["to"]),
							"to-login" => $this->_user->getUserLogin($row["to"]),
							
							
							"date" => $this->_core->getDate($row["timestamp"]),
							"time" => $this->_core->getTime($row["timestamp"]),

							"remove" => $this->_user->hasPermission("messages.remove"),
							"remove-link" => SITE_PATH . "messages/remove/" . $row["id"],
							
							"readed" => ($row["readed"] > 0),
							"not-readed" => ($row["readed"]  == 0)
						];
					}

					$response->code = 0;
					$response->view = "messages.list";

					// New messages num
					$newpm_num = $this->_db
						->select("count(*)")
						->from(DBPREFIX . "messages")
						->where("user", "=", $this->_user->get("id"))
						->and_where("to", "=", $this->_user->get("id"))
						->and_where("readed", "=", 0)
						->result_array();

					$response->tags = array(
						"num" => $num,
						"new-count" => isset($newpm_num[0][0]) ? $newpm_num[0][0] : 0,
						"rows" => $rows,
						"type" => $type,
						"pagination" => $pagination
					);
				}
			}
		}

		/**
		 * Access denied
		 */
		else {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		}

		return $response;
	}
	
	/**
	 * Get message page
	 * @param int $id Message ID
	 * @return Response
	 * @throws \Exception
	 */
	public function page($id) {
		$response = new Response();

		$id = intval($id);
		
		$this->_core
			->addBreadcrumbs($this->_lang->get("messages", "moduleName"), "messages");

		/**
		 * Check permissions for messages list
		 */
		if ($this->_user->hasPermission("messages.read")) {
			$array = $this->_db
				->select(array(
					"id", "from", "to", "reply", "topic", "message", "readed",
					array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false)
				))
				->from(DBPREFIX . "messages")
				->where("id", "=", $id)
				->and_where("user", "=", $this->_user->get("id"))
				->result_array();

			/**
			 * Database error
			 */
			if ($array === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} elseif (!isset($array[0])) {
				$response->code = 2;
				$response->type = "danger";
				$response->message = $this->_lang->get("messages", "read.notFound");
			} else {
				$row = $array[0];
				
				// Mark message for readed
				$this->_db
					->update(DBPREFIX . "messages")
					->set(array (
						"readed" => 1
					))
					->where("id", "=", $row["id"])
					->result();

				$response->code = 0;
				$response->view = "messages.read";

				/**
				 * Tags
				 */
				$response->tags = array(
					"id" => $row["id"],
					"url" => SITE_PATH . "messages/" . $row["id"],
					"topic" => $row["topic"],
					"message" => Strings::lineWrap($row["message"]),
							
					"from-id" => $this->_user->getUserLogin($row["from"]),
					"from-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["from"]),
					"from-login" => $this->_user->getUserLogin($row["from"]),
							
					"to-id" => $this->_user->getUserLogin($row["to"]),
					"to-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["to"]),
					"to-login" => $this->_user->getUserLogin($row["to"]),
					
					"date" => $this->_core->getDate($row["timestamp"]),
					"time" => $this->_core->getTime($row["timestamp"]),

					"remove" => $this->_user->hasPermission("messages.remove"),
					"remove-link" => SITE_PATH . "messages/remove/" . $row["id"]
				);
			}
		}

		/**
		 * Access denied
		 */
		else {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		}

		return $response;
	}
	
	
	
	/*****************
	 * SEND MESSAGES *
	 *****************/
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
		$response = new Response();
		
		if ($user != null)
			$this->_sendPageTags["user"] = $user;

		$this->_core
			->addBreadcrumbs($this->_lang->get("messages", "moduleName"), "messages")
			->addBreadcrumbs($this->_lang->get("messages", "send.moduleName"), "messages/send");

		if (!$this->_user->hasPermission("messages.send")) {
			$this->_core
				->addBreadcrumbs($this->_lang->get("core", "accessDenied"), "messages/send");

			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			$response->view = "messages.send";
			$response->tags = $this->_sendPageTags;
		}

		return $response;
	}
	
	/**
	 * Send Message
	 * @param string $user User Login
	 * @param string $topic Message topic
	 * @param string $message Message text
	 * @param int $reply = 0 Message text
	 * @return Response
	 */
	public function send($user, $topic, $message, $reply = 0) {
		$response = new Response();

		$user = StringFilters::filterHtmlTags($user);
		$topic = StringFilters::filterHtmlTags($topic);
		$message = StringFilters::filterHtmlTags($message);
		
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
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			return $response;
		} elseif ($interval[0][0] > 0) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("messages", "send.smallInterval");
			return $response;
		}
		
		$length = Strings::length($message); 

		/**
		 * If user haven't permission for add group
		 */
		if (!$this->_user->hasPermission("messages.send")) {
			$response->code = 3;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} elseif (empty($user) || empty($topic) || empty($message)) {
			$response->code = 4;
			$response->type = "warning";
			$response->message = $this->_lang->get("core", "emptyFields");
		} elseif ($length < $this->_config->get("messages", "send.minLength", 5)) {
			$response->code = 5;
			$response->type = "danger";
			$response->message = $this->_lang->get("messages", "send.tooShort");
		} elseif ($length > $this->_config->get("messages", "send.maxLength", 300)) {
			$response->code = 5;
			$response->type = "danger";
			$response->message = $this->_lang->get("messages", "send.tooLong");
		} else {
			$query = $this->_db
				->select(["id"])
				->from(DBPREFIX . "user_profiles")
				->where("login", "=", $user)
				->result_array();

			if (isset($query[0][0])) {
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
					$response->code = 1;
					$response->type = "danger";
					$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
				} else {
					$this->_cache->remove("user", "groups");
					$response->type = "success";
					$response->message = $this->_lang->get("messages", "send.success");
					
					// Sent notification reciever
					$this->_registry
						->get("Notifications")
						->add($to, "info", "[messages:newMessage]", "<b>{$this->_user->get("login")}</b> [messages:newMessageBody] <b>{$topic}</b>",
							SITE_PATH . "messages/" . $this->_db->insert_id()
						);
				}
			} else {
				$response->code = 6;
				$response->type = "danger";
				$response->message = $this->_lang->get("messages", "send.notExists");
			}
		}

		return $response;
	}

	/*******************
	 * REMOVE MESSAGES *
	 ******************/

	/**
	 * Remove group page
	 * @param int $id Group ID
	 * @return Response
	 */
	public function removePage($id) {
		$response = new Response();

		$id = intval($id);

		$this->_core
			->addBreadcrumbs($this->_lang->get("messages", "moduleName"), "messages")
			->addBreadcrumbs($this->_lang->get("messages", "remove.moduleName"), "messages/remove/" . $id);

		if (!$this->_user->hasPermission("messages.remove") || $id < 5) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"), "messages/remove/" . 	$id);
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			$query = $this->_db
				->select("count(*)")
				->from(DBPREFIX . "messages")
				->where("id", "=", $id)
				->and_where("user", "=", $this->_user->get("id"))
				->result_array();

			if (isset($query[0]) && $query[0][0] > 0) {
				$response->view = "messages.remove";
				$response->tags["id"] = $id;
			} else {
				$response->code = 2;
				$response->type = "danger";
				$response->message = $this->_lang->get("messages", "remove.notExist");
			}
		}

		return $response;
	}

	/**
	 * Remove group
	 * @param int $id Group ID
	 * @return Response
	 */
	public function remove($id) {
		$response = new Response();

		$id = intval($id);

		if (!$this->_user->hasPermission("messages.remove") || $id < 5) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} else {
			$query = $this->_db
				->delete_from(DBPREFIX . "messages")
				->where("id", "=", $id)
				->and_where("user", "=", $this->_user->get("id"))
				->result();

			if ($query === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else
				$response->type = "success";
		}

		return $response;
	}
}
