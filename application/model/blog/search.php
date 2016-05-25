<?php
/**
 * Blog Search Model
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

use harmony\pagination\Pagination;
use harmony\bbcode\BBCodeParser;
use harmony\strings\StringFilters;
use harmony\strings\Strings;

class Search extends AppModel {	
	/**
	 * Get posts by category and page
	 * @param string $query Search query
	 * @param string $page Search posts page
	 * @return Response
	 */
	public function searchPosts($query, $page) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog")
			->addBreadcrumbs($this->_lang->get("blog", "search.moduleName"), "blog/search")
			->addBreadcrumbs($this->_lang->get("blog", "search.posts"), "blog/search");
			
		if (!$this->_user->hasPermission("blog.posts.search")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$response = new Response();
		$query = StringFilters::filterHtmlTags($this->_db->safe($query));
		$page = intval($page);

		/**
		 * Number query
		 */
		$minLength = $this->_config->get("blog", "search.posts.queryMinLength", 3);

		if (Strings::length($query) >= $minLength) {
			$num = $this->_db
				->select("count(*)")
				->from(DBPREFIX . "blog_posts")
				->where("title", "LIKE", "%{$query}%")
				->or_where("text", "LIKE", "%{$query}%")
				->or_where("tags", "LIKE", "%{$query}%")
				->result_array();

			if ($num === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);

				return $response;
			} else {
				$paginationPrefix = SITE_PATH . "blog/search/{$query}/page/";
				$num = $num[0][0];
				$pagination = new Pagination($num, $page, $paginationPrefix, $this->_config->get("blog", "search.customPagination", array()));

				/**
				 * Posts query
				 */
				$array = $this->_db
					->select(array(
						"id", "title", "url", "text", "category", "comments_num", "views_num", "rating",
						"tags", "lang", array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false),
						"show", "author"
					))
					->from(DBPREFIX . "blog_posts")
					->where("title", "LIKE", "%{$query}%")
					->or_where("text", "LIKE", "%{$query}%")
					->or_where("tags", "LIKE", "%{$query}%")
					->order_by("id", $this->_config->get("blog", "search.sort", "DESC"))
					->limit($pagination->getSqlLimits())
					->result_array();

				if ($array === false) {
					$response->code = 1;
					$response->type = "danger";
					$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
				} else {
					// Posts make
					$rows = [];
					foreach ($array as $row)
						$rows[] = [
							"id" => $row["id"],
							"link" => SITE_PATH . "blog/" . $row["id"] . "-" . $row["url"],
							"title" => $row["title"],

							"author-id" => $row["author"],
							"author-login" => $this->_user->getUserLogin($row["author"]),
							"author-name" => $this->_user->getUserName($row["author"]),
							"author-link" => SITE_PATH . "user/profile/" . $this->_user->getUserLogin($row["author"]),
							"author-avatar-link" => $this->_user->getAvatarLinkById($row["author"]),

							"full-text" => Posts::getText($row["text"]),

							"tags" => $row["tags"],
							"lang" => $row["lang"],
							"language" => $this->_lang->getLangName($row["lang"]),

							"category-id" => $row["category"],
							"category-name" => Categories::getInstance()->getName($row["category"]),
							"category-link" => SITE_PATH . "blog/cat/" . $row["category"],

							"archive-link" => SITE_PATH . "blog/archive/" . date("Y/m/d", $row["timestamp"]),
							"edit-link" => ADMIN_PATH . "blog/edit/" . $row["id"],
							"remove-link" => ADMIN_PATH . "blog/remove/" . $row["id"],

							"iso-datetime" => $this->_core->getISODateTime($row["timestamp"]),
							"date" => $this->_core->getDate($row["timestamp"]),
							"time" => $this->_core->getTime($row["timestamp"]),

							"comments-num" => $row["comments_num"],
							"views-num" => $row["views_num"],
							"rating" => $row["rating"],
							
							"show" => ($row["show"] > 0),
							"not-show" => ($row["show"] < 1),
							
							"edit" => $this->_user->hasPermission("admin.blog.posts.edit"),
							"remove" => $this->_user->hasPermission("admin.blog.posts.remove")
						];

					/**
					 * Formation response
					 */
					$response->code = 0;
					$response->view = "blog.search";
					$response->tags = array(
						"query" => $query,
						"num" => $num,
						"rows" => $rows,
						"pagination" => $pagination
					);
				}
			}
		} else {
			if (!$query)
				$response->code = 0;
			else {
				$response->code = 2;
				$response->type = "danger";
				$response->message = $this->_lang->get("blog", "search.posts.shortQuery") . " - " . $minLength;
			}

			$response->view = "blog.search";
			$response->tags = array(
				"query" => $query
			);
		}

		return $response;
	}
}
