<?php
/**
 * Blog Rating Model
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

class Rating extends AppModel {
	private function _change($postId, $act) {
		$response = new Response();

		$result = $this->_db
			->update(DBPREFIX . "blog_posts")
			->set(array (
				"rating" => array ("rating", $act, 1, false)
			))
			->where("id", "=", $postId)
			->result();

		if ($result === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$result = $this->_db
			->select("rating")
			->from(DBPREFIX . "blog_posts")
			->where("id", "=", $postId)
			->result_array();

		if ($result === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$response->tags["num"] = $result[0]["rating"];

		return $response;
	}

	public function change($postId, $r_type) {
		// Access denied
		if (!$this->_user->isLogged()) {
			return new Response(2, "danger", $this->_lang->get("blog", "rating.needAuth"));
		} elseif(!$this->_user->hasPermission("blog.posts.rating")) {
			return new Response(3, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$postId = intval($postId);
		$r_type = intval($r_type);
		$type = ($r_type == 1) ? "+" : "-";

		$posts = new Posts(FRONTEND);

		if (!$posts->exists($postId)) {
			return new Response(3, "danger", $this->_lang->get("blog", "notFound"));
		}

		$result = $this->_db
			->select(array(
				"id", "type"
			))
			->from(DBPREFIX . "blog_rating")
			->where("post", "=", $postId)
			->and_where("user", "=", $this->_user->get("id"))
			->result_array();

		if ($result !== false && count($result) > 0 && $r_type != $result[0]["type"]) {
			$this->_db
				->delete_from(DBPREFIX . "blog_rating")
				->where("id", "=", $result[0]["id"]);

			if ($this->_db->result() !== false) {
				$this->_change($postId, $type);
				$result = [];
			}
		}

		if ($result === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		} elseif (count($result) > 0) {
			return new Response(4, "danger", $this->_lang->get("blog", "rating.already"));
		}
		$result = $this->_db
			->insert_into(DBPREFIX . "blog_rating")
			->values(array (
				"post" => $postId,
				"user" => $this->_user->get("id"),
				"type" => $r_type
			))
			->result();

		if ($result === false) {
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		return $this->_change($postId, $type);
	}
}
