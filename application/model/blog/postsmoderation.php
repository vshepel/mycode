<?php
/**
 * Blog Moderation Posts Model
 * @copyright Copyright (C) 2016 Evgeny Zakharenko <evgenyz99@yandex.com>. All rights reserved.
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

use Exception;
use AppModel;
use Response;
use harmony\pagination\Pagination;
use harmony\strings\StringFilters;
use harmony\strings\Strings;

class PostsModeration extends AppModel {
    private $_addTags = [
        "title" => "",
        "url" => "",
        "category" => 0,
        "text" => "",
        "tags" => "",
        "lang" => ""
    ];

    /**
     * PostsModeration constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->_addTags["lang"] = $this->_lang->getLang();
    }

    /**
     * Get posts for future
     * @param $category
     * @param $page
     * @return Response
     * @throws Exception
     */
    public function get($category, $page) {
        $response = new Response();

        $category = ($category === null) ? null : intval($category);
        $page = intval($page);

        $this->_core
            ->addBreadcrumbs($this->_lang->get("story", "main", "moduleName"), "story");

        $this->_db
            ->select("count(*)")
            ->from(DBPREFIX . "blog_posts_moderation")
            ->where("id", ">", 0);

        if ($category !== null)
            $this->_db
                ->and_where("category", "=", $category);

        $num = $this->_db
            ->result_array();

        if ($num === false) {
            $response->code = 1;
            $response->type = "danger";
            $response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);

            return $response;
        } else {
            $paginationPrefix = PATH . "story/future/" . (($category === null) ? "page/" : "cat/" . $category . "/page/");
            $num = $num[0][0];
            $pagination = new Pagination($num, $page, $paginationPrefix, $this->_config->get("story", "list", "customPagination"));

            $this->_db
                ->select(array(
                    "id", "text", "category", "author",
                    array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false),
                ))
                ->from(DBPREFIX . "blog_posts_moderation")
                ->where("id", ">", 0);


            if ($category !== null)
                $this->_db->and_where("category", "=", $category);

            $array = $this->_db
                ->order_by("id")->asc()
                ->limit($pagination->getSqlLimits())
                ->result_array();

            if ($array === false) {
                $response->code = 1;
                $response->type = "danger";
                $response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
            } else {
                foreach ($array as $row) {
                    $anonymous = ($row["author"] == 0);

                    $this->_view->add("story/future/post", array(
                        "id" => $row["id"],
                        "title" => $this->_lang->get("story", "main", "postId") . $row["id"],

                        "author-id" => $row["author"],
                        "author-login" => $this->_user->getUserLogin($row["author"]),
                        "author-name" => $this->_user->getUser($row["author"], "firstname"),
                        "author-link" => $anonymous ? "#" : (PATH . "user/profile/" . $this->_user->getUserLogin($row["author"])),

                        "avatar-link" => $this->_user->getAvatarLinkById($row["author"]),

                        "text" => Strings::lineWrap($row["text"]),

                        "category-id" => $row["category"],
                        "category-name" => Categories::getInstance()->getName($row["category"]),
                        "category-link" => PATH . "story/cat/" . $row["category"],

                        "good-link" => PATH . "story/future/good/" . $row["id"],
                        "bad-link" => PATH . "story/future/bad/" . $row["id"],

                        "iso-datetime" => $this->_core->getISODateTime($row["timestamp"]),
                        "date" => $this->_core->getDate($row["timestamp"]),
                        "time" => $this->_core->getTime($row["timestamp"]),
                    ), array (
                        "anonymous" => $anonymous,
                        "not-anonymous" => !$anonymous
                    ));
                }

                if ($category !== null)
                    $this->_core
                        ->addBreadcrumbs(Categories::getInstance()->getName($category), "story/cat/" . $category);

                $response->code = 0;
                $response->view = "story/future/page";
                $response->tags = array (
                    "num" => $num,
                    "posts" => $this->_view->get("story/future/post"),
                    "pagination" => $pagination,
                );
            }
        }

        return $response;
    }

    public function good($id) {
        /**
         * Get post data
         */
        $post = $this->_db
            ->select(array(
                "id", "text", "category", "author",
                array("UNIX_TIMESTAMP(`timestamp`)", "timestamp", false),
            ))
            ->from(DBPREFIX . "blog_posts_moderation")
            ->where("id", "=", intval($id))
            ->result_array();

        if (isset($post[0])) {
            $row = $post[0];
            $author = $row["author"];

            $result = $this->_db
                ->insert_into(DBPREFIX . "story_posts")
                ->values(array(
                    "category" => $row["category"],
                    "text" => $row["text"],
                    "allow_comments" => 1,
                    "show" => 1,
                    "author" => $row["author"]
                ))
                ->result();

            if ($result === false)
                throw new Exception("Error add post: " . $this->_db->getError());

            $postId = $this->_db->insert_id();

            if ($row["author"] > 0) {
                /**
                 * Add timeline event
                 */
                $this->_registry
                    ->getObject("TimeLine")
                    ->add($author, 4, $this->_db->insert_id());

                /**
                 * Send notification
                 */
                $this->_registry
                    ->getObject("Notifications")
                    ->add($author, 0, array (
                        $postId,
                        substr($row["text"], 0, 30)
                    ));

                /**
                 * Update user posts count
                 */
                $this->_user->update($author, array(
                    "posts_num" => $this->_user->getUser($author, "posts_num") + 1
                ));


                $this->_registry
                    ->getObject("Achievements")
                    ->addAchievements($author, array(
                        5 => 11,
                        10 => 12,
                        30 => 13,
                        50 => 14,
                        100 => 15
                    ), $this->_user->getUser($author, "posts_num"));
            }

            $this->remove($id);
        }
    }

    public function bad($id) {
        /**
         * Get post data
         */
        $post = $this->_db
            ->select(array(
                "author", "text"
            ))
            ->from(DBPREFIX . "blog_posts_moderation")
            ->where("id", "=", intval($id))
            ->result_array();

        if ($post === false)
            throw new Exception("Error get post: " . $this->_db->getError());

        if (isset($post[0])) {
            $row = $post[0];

            /**
             * Send notification
             */
            $this->_registry
                ->getObject("Notifications")
                ->add($row["author"], 1, substr($row["text"], 0, 30));

            $this->remove($id);
        }
    }

    public function remove($id) {
        $query = $this->_db
            ->delete_from(DBPREFIX . "blog_posts_moderation")
            ->where("id", "=", intval($id))
            ->result();

        if ($query === false)
            throw new Exception("Error remove post: " . $this->_db->getError());
    }

    /**
     * Add post for future
     * @param string $title Post title
     * @param string $url Post url
     * @param int $category Category ID
     * @param string $text Post content
     * @param string $tags Post tags
     * @param string $lang Post lang
     * @return Response
     */
    public function add($title, $url, $category, $text, $tags, $lang) {
        $response = new Response();

        $title = StringFilters::filterStringForPublic($title);
        $url = StringFilters::filterStringForPublic($url);
        $category = intval($category);
        $tags = StringFilters::filterTagsString($tags);
        $lang = StringFilters::filterStringForPublic($lang);

        $this->_addTags = array (
            "title" => $title,
            "url" => $url,
            "category" => $category,
            "text" => $text,
            "tags" => $tags,
            "lang" => $lang
        );

        if (!$this->_user->isLogged() || !$this->_user->hasPermission("blog.posts.add")) {
            return new Response(2, "danger", $this->_lang->get(null, "core", "accessDenied"));
        }

        $interval = $this->_db
            ->select("count(*)")
            ->from(DBPREFIX . "blog_posts_moderation")
            ->where("author", "=", $this->_user->get("id"))
            ->and_where("UNIX_TIMESTAMP(CURRENT_TIMESTAMP)", "<", "UNIX_TIMESTAMP(`timestamp`) + " . $this->_config->get("story", "add.interval", 60), false, false)
            ->result_array();

        if ($interval === false) {
            $response->code = 1;
            $response->type = "danger";
            $response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
        } elseif ($interval[0][0] > 0) {
            $response->code = 1;
            $response->type = "danger";
            $response->message = $this->_lang->get("blog", "add.smallInterval");
        } elseif (empty($title) || empty($text)) {
            $response->code = 2;
            $response->type = "warning";
            $response->message = $this->_lang->get("core", "emptyFields");
        } else {
            $result = $this->_db
                ->insert_into(DBPREFIX . "blog_posts_moderation")
                ->values(array(
                    "title" => $title,
                    "url" => $url,
                    "category" => $category,
                    "text" => $text,
                    "tags" => $tags,
                    "lang" => $lang,
                    "author" => $this->_user->get("id"),
                ))
                ->result();

            if ($result === false) {
                $response->code = 1;
                $response->type = "danger";
                $response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
            } else {
                $response->type = "success";
                $response->message = $this->_lang->get("blog", "add.success");
            }
        }

        return $response;
    }

    /**
     * Post write page
     * @param int|null $category = null Category ID
     * @return Response
     */
    public function addPage($category = null) {
        $response = new Response();

        if ($this->_addTags["category"] == 0 && $category !== null) {
            $this->_addTags["category"] = $category;
        }

        $this->_core
            ->addBreadcrumbs($this->_lang->get("blog", "moduleName"), "blog")
            ->addBreadcrumbs($this->_lang->get("blog", "add.moduleName"));

        if (!$this->_user->isLogged() || !$this->_user->hasPermission("blog.posts.add")) {
            $response->code = 2;
            $response->type = "danger";
            $response->message = $this->_lang->get("core", "accessDenied");
            return $response;
        }

        $response->view = "blog.add";

        // Categories
        $categories = [];
        foreach (Categories::getInstance()->get() as $id => $row) {
            $categories[] = [
                "id" => $id,
                "name" => $row["name"],
                "num" => $row["num"],
                "current" => ($this->_addTags["category"] == $id)
            ];
        }

        // Languages
        $langs = [];
        foreach ($this->_lang->getLangs() as $lang => $name) {
            $langs[] = [
                "id" => $lang,
                "name" => $name,
                "current" => ($this->_addTags["lang"] == $lang)
            ];
        }

        $response->tags = array_merge($this->_addTags, array (
            "editor" => $this->_config->get("blog", "posts.editor", "BBCode"),
            "categories" => $categories,
            "langs" => $langs
        ));

        return $response;
    }
}
