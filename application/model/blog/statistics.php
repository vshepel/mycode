<?php
/**
 * Blog Statistics Model
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

class Statistics extends AppModel {
	/**
	 * Get blog statistics
	 * @return Response
	 */
	public function getPage() {
		if (!$this->_user->hasPermission("blog.statistics")) 
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		
		$response = new Response();
		$response->view = "blog.statistics";

		$this->_core
			->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog")
			->addBreadcrumbs($this->_lang->get("blog", "statistics.moduleName"));

		$tags = array ();

		// POSTS
		$num = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_posts")
			->result_array();

		$tags["posts-total"] = isset($num[0][0]) ? $num[0][0] : $this->_db->getError();

		$num = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_posts")
			->where("show", "=", 1)
			->result_array();

		$tags["posts-show"] = isset($num[0][0]) ? $num[0][0] : $this->_db->getError();

		$num = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_posts")
			->where("comments_num", ">", 0)
			->result_array();

		$tags["posts-comments"] = isset($num[0][0]) ? $num[0][0] : $this->_db->getError();
		
		// COMMENTS
		
		$num = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "blog_comments")
			->result_array();

		$tags["comments-total"] = isset($num[0][0]) ? $num[0][0] : $this->_db->getError();

		$response->tags = $tags;

		return $response;
	}
}
