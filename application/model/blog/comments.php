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
	 * @param int $uid User ID
	 * @return int
	 */
	public function getUserCommentsCount($uid) {
		$count = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_comments")
			->where("user", "=", intval($uid))
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
	 * @return Response
	 */
	public function add($post, $comment) {
		$post = intval($post);
		$comment =  StringFilters::filterHtmlTags($comment);

		$this->_comment = $comment;

		$response = new Response();

		if (!$this->_user->isLogged() || !$this->_user->hasPermission("blog.comments.add")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

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
				return new Response(2, "danger", $this->_lang->get("blog", "comments.add.smallInterval"));
			}
		}

		$length = Strings::length($comment, "UTF-8");

		$row = $this->_db
			->select("allow_comments")
			->from(DBPREFIX . "blog_posts")
			->where("id", "=", $post)
			->result_array();

		if ($row === false) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
		} elseif (count($row) == 0) {
			$response->code = 3;
			$response->type = "danger";
			$response->message = $this->_lang->get("blog", "notFound");
		} elseif ($row[0]["allow_comments"] == "0") {
			$response->code = 4;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		} elseif (empty($comment)) {
			$response->code = 5;
			$response->type = "warning";
			$response->message = $this->_lang->get("blog", "comments.add.emptyComment");
		} elseif ($length < $this->_config->get("blog", "comments.length.min", 3)) {
			$response->code = 6;
			$response->type = "danger";
			$response->message = $this->_lang->get("blog", "comments.add.shortComment");
		} elseif ($length > $this->_config->get("blog", "comments.length.max", 300)) {
			$response->code = 7;
			$response->type = "danger";
			$response->message = $this->_lang->get("blog", "comments.add.longComment");
		} else {
			$this->_db
				->update(DBPREFIX . "blog_posts")
				->set(array(
					"comments_num" => array ("comments_num", "+", 1, false)
				))
				->where("id", "=", $post)
				->result();

			$query = $this->_db
				->insert_into(DBPREFIX . "blog_comments")
				->values(array(
					"where" => $post,
					"user" => $this->_user->get("id"),
					"user_ip" => HTTP::getIp(),
					"comment" => $comment
				))
				->result();

			if ($query === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$response->type = "success";
				$response->message = $this->_lang->get("blog", "comments.add.success");
				$this->_comment = "";
			}
		}

		return $response;
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
		$response = new Response();

		$post = intval($post);
		$page = intval($page);
		$allow = (bool)($allow);

		if (!$this->_user->hasPermission("blog.comments.read")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$num = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_comments")
			->where("where", "=", $post)
			->result_array();

		if ($num === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		} else {
			$num = $num[0][0];
			if (!empty($url)) $url = "-" . $url;
			$pagination = new Pagination($num, $page, SITE_PATH . "blog/{$post}{$url}/page/", $this->_config->get("blog", "comments.customPagination", array()));

			$array = $this->_db
				->select(array(
					"id", "user", "comment", "comment",
					array ( "UNIX_TIMESTAMP(`timestamp`)", "timestamp", false )
				))
				->from(DBPREFIX . "blog_comments")
				->where("where", "=", $post)
				->order_by("id", $this->_config->get("blog", "comments.sort", "DESC"))
				->limit($pagination->getSqlLimits())
				->result_array();

			if ($array === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$rows = [];

				foreach ($array as $row) {
					$online = $this->_user->checkOnline($this->_user->getUser($row["user"], "active"));
					$rows[] = [
						"id" => $row["id"],

						"author-login" => $this->_user->getUserLogin($row["user"]),
						"author-name" => $this->_user->getUserName($row["user"]),
						"author-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["user"]),
						"author-avatar-link" => $this->_user->getAvatarLinkById($row["user"]),

						"date" => $this->_core->getDate($row["timestamp"]),
						"time" => $this->_core->getTime($row["timestamp"]),

						"comment-message" => $row["comment"],

						"online" => $online,
						"offline" => !$online,

						"remove" => $this->_user->hasPermission("blog.comments.remove." . ($row["user"] == $this->_user->get("id") ? "my" : "others")),
					];
				}

				$canAdd = $allow ? $this->_user->hasPermission("blog.comments.add") : false;

				if ($canAdd)
					$addform = $this->_view
						->parse("blog.comments.add", array (
							"post-id" => $post,
							"comment" => $this->_comment
						));
				else
					$addform = $this->_view->getAlert("danger", $this->_lang->get("blog", "comments.cantAdd"));

				$response->code = 0;
				$response->view = "blog.comments.page";
				$response->tags = array (
					"num" => $num,
					"rows" => $rows,
					"pagination" => (string) $pagination,
					"addform" => $addform,
					"can-add" => $canAdd,
					"cant-add" => !$canAdd
				);
			}
		}

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
			->select(["user"])
			->from(DBPREFIX . "blog_comments")
			->where("id", "=", $id)
			->result_array();

		if ($array === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		} else if (!isset($array[0]["user"])) {
			return new Response(2, "danger", $this->_lang->get("blog", "comments.remove.notFound"));
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

		return new Response(0, "success", $this->_lang->get("blog", "comments.remove.success"));
	}
}
