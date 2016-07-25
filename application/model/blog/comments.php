<?php
/**
 * Blog Comments Model
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

namespace model\blog;

use AppModel;
use Response;

use harmony\http\HTTP;
use harmony\pagination\Pagination;
use harmony\strings\StringFilters;
use harmony\strings\Strings;

class Comments extends AppModel {
	/**
	 * Get User comments count by User ID
	 * @param array $args
	 * @return int
	 */
	public function getUserCommentsCount($args) {
		if (!isset($args["user"])) return -1;

		$count = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_comments")
			->where("user", "=", intval($args["user"]))
			->result_array();

		return isset($count[0][0]) ? $count[0][0] : 0;
	}

	/**
	 * @var string Comment
	 */
	private $_comment = "";

	/**
	 * Add comment
	 * @param int $post Post ID
	 * @param string $comment Comment
	 * @param int $reply Reply Comment ID
	 * @return Response
	 */
	public function add($post, $comment, $reply) {
		// Access denied
		if (!$this->_user->isLogged() || !$this->_user->hasPermission("blog.comments.add")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$post = intval($post);
		$reply = intval($reply);

		$comment = preg_replace("/[\n]{2,}/i", "\n", str_replace("\r", "", StringFilters::filterHtmlTags($comment)));
		$comment = preg_replace("/ +/", " ", trim($comment));
		$comment = preg_replace(["/^/", "/$/"], "", $comment);

		$this->_comment = $comment;

		// Check interval
		$check_interval  = $this->_config->get("blog", "comments.interval", 10);

		if ($check_interval > 0) {
			$interval = $this->_db
				->select("count(*)")
				->from(DBPREFIX . "blog_comments")
				->where("user_ip", "=", HTTP::getIp())
				->and_where("UNIX_TIMESTAMP(CURRENT_TIMESTAMP)", "<", "UNIX_TIMESTAMP(`timestamp`) + " . $check_interval, false, false)
				->result_array();

			if ($interval === false) {
				return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
			} elseif ($interval[0][0] > 0) {
				return new Response(3, "danger", $this->_lang->get("blog", "comments.add.smallInterval"));
			}
		}

		$length = Strings::length($comment);

		$row = $this->_db
			->select(["url", "allow_comments"])
			->from(DBPREFIX . "blog_posts")
			->where("id", "=", $post)
			->result_array();

		if ($row === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		} elseif (count($row) == 0) {
			return new Response(3, "danger", $this->_lang->get("blog", "notFound"));
		} elseif ($row[0]["allow_comments"] == "0") {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		} elseif (empty($comment)) {
			return new Response(4, "warning", $this->_lang->get("blog", "comments.add.emptyComment"));
		} elseif ($length < $this->_config->get("blog", "comments.length.min", 3)) {
			return new Response(5, "danger", $this->_lang->get("blog", "comments.add.shortComment"));
		} elseif ($length > $this->_config->get("blog", "comments.length.max", 300)) {
			return new Response(6, "danger", $this->_lang->get("blog", "comments.add.longComment"));
		}

		$reply_row = $this->_db
			->select("user")
			->from(DBPREFIX . "blog_comments")
			->where("id", "=", $reply)
			->result_array();

		$reply_user = 0;
		$original_comment = $comment;

		if (isset($reply_row[0])) {
			$reply_user = $reply_row[0]["user"];
			$login = $this->_user->getUserLogin($reply_user);
			$comment = "@{$login}, " . $comment;
		}

		$query = $this->_db
			->insert_into(DBPREFIX . "blog_comments")
			->values(array(
				"where" => $post,
				"user" => $this->_user->get("id"),
				"user_ip" => HTTP::getIp(),
				"comment" => $comment,
				"reply" => $reply
			))
			->result();

		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$comment_id = $this->_db->insert_id();

		// Update comments counter
		$this->_db
			->update(DBPREFIX . "blog_posts")
			->set(array(
				"comments_num" => array ("comments_num", "+", 1, false)
			))
			->where("id", "=", $post)
			->result();

		// Send reply notification
		if ($reply_user != 0) {
			$this->_registry
				->get("Notifications")
				->add($reply_user, "info", "[blog:notification.comments.reply.title] " . $this->_user->get("login"),
					$original_comment, Posts::getPostLink($post, $row[0]["url"]) . "#comment_" . $comment_id
				);
		}

		$this->_comment = "";

		return new Response(0, "success", $this->_lang->get("blog", "comments.add.success"));
	}

	/**
	 * Get comments
	 * @param int $post Post ID
	 * @param int $page Comments Page
	 * @param bool $allow Allow comments?
	 * @param string $url = "" URL link
	 * @return Response
	 */
	public function get($post, $page, $allow, $url = "") {
		// Access denied
		if (!$this->_user->hasPermission("blog.comments.read")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$post = intval($post);
		$page = intval($page);
		$allow = (bool)($allow);

		$num = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_comments")
			->where("where", "=", $post)
			->result_array();

		if ($num === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$num = $num[0][0];
		$pagination = new Pagination($num, $page, Posts::getPostLink($post, $url) . "/page/", $this->_config->get("blog", "comments.customPagination", array()));

		$array = $this->_db
			->select([
				"id", "user", "comment", "comment", "reply",
				array ( "UNIX_TIMESTAMP(`timestamp`)", "timestamp", false )
			])
			->from(DBPREFIX . "blog_comments")
			->where("where", "=", $post)
			->order_by("id", $this->_config->get("blog", "comments.sort", "DESC"))
			->limit($pagination->getSqlLimits())
			->result_array();

		if ($array === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$rows = [];

		foreach ($array as $row) {
			$online = $this->_user->checkOnline($this->_user->getUser($row["user"], "active"));

			$message = Strings::lineWrap($row["comment"]);
			$message = $this->_user->replaceProfileLink($message);

			$rows[] = [
				"id" => $row["id"],

				"author-login" => $this->_user->getUserLogin($row["user"]),
				"author-name" => $this->_user->getUserName($row["user"]),
				"author-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["user"]),
				"author-avatar-link" => $this->_user->getAvatarLinkById($row["user"]),

				"date" => $this->_core->getDate($row["timestamp"]),
				"time" => $this->_core->getTime($row["timestamp"]),

				"comment-message" => $message,
				"reply" => $row["reply"],

				"online" => $online,
				"offline" => !$online,

				"remove" => $this->_user->hasPermission("blog.comments.remove." . ($row["user"] == $this->_user->get("id") ? "my" : "others")),
			];
		}

		$canAdd = $allow ? $this->_user->hasPermission("blog.comments.add") : false;

		$response = new Response();
		$response->view = "blog.comments";
		$response->tags = [
			"post-id" => $post,
			"comment" => $this->_comment,
			"page" => $page,
					
			"num" => $num,
			"rows" => $rows,
			"pagination" => (string) $pagination,
			"can-add" => $canAdd,
			"cant-add" => !$canAdd
		];

		return $response;
	}

	/**
	 * Remove comment
	 * @param int $id Comment ID
	 * @return Response
	 */
	public function remove($id) {
		$id = intval($id);

		// Check comment for exists
		$array = $this->_db
			->select(["where", "user"])
			->from(DBPREFIX . "blog_comments")
			->where("id", "=", $id)
			->result_array();

		if ($array === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		} else if (!isset($array[0]["user"])) {
			return new Response(3, "danger", $this->_lang->get("blog", "comments.remove.notFound"));
		}

		$user = $array[0]["user"];

		// Check for permission
		if (!$this->_user->isLogged() || !$this->_user->hasPermission("blog.comments.remove." . ($user == $this->_user->get("id") ? "my" : "others"))) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		// Comment remove
		$query = $this->_db
			->delete_from(DBPREFIX . "blog_comments")
			->where("id", "=", $id)
			->result();

		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		// Comments number update in post
		$query = $this->_db
			->update(DBPREFIX . "blog_posts")
			->set(array(
				"comments_num" => array ("comments_num", "-", 1, false)
			))
			->where("id", "=", $array[0]["where"])
			->result();

		if ($query === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		return new Response(0, "success", $this->_lang->get("blog", "comments.remove.success"));
	}
}
