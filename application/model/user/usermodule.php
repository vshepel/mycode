<?php
/**
 * User Module Model
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

use AppModel;
use Response;
use NotFoundException;

use harmony\http\HTTP;
use harmony\http\Cookies;
use harmony\http\Sessions;
use harmony\strings\StringFilters;
use harmony\pagination\Pagination;

class UserModule extends AppModel {
	/**
	 * User profile page
	 * @param string $user User login
	 * @return Response
	 * @throws NotFoundException
	 */
	public function profilePage($user) {
		$this->_core->addBreadcrumbs($this->_lang->get("user", "list.moduleName"), "user/list");

		// Database query
		$array = $this->_db
			->select(array(
				"id" ,"login", "active", "name", "group", "avatar",
				"birth_date", "gender", "location", "url", "public_email", "reg_date"
			))
			->from(DBPREFIX . "user_profiles")
			->where("login", "=", $user)
			->result_array();

		// Database error
		if ($array === false) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "internalError"));
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		// User not found
		if (!isset($array[0])) {
			throw new NotFoundException();
		}

		// Haven't permissions for view profile
		if (!($array[0]["id"] == $this->_user->get("id") && $this->_user->hasPermission("user.profile.my")) && !$this->_user->hasPermission("user.profile.other")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(3, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$response = new Response();
		$row = $array[0];

		$this->_core->addBreadcrumbs($row["login"]);

		$online = $this->_user->checkOnline($row["active"]);
		$owner = ($row["login"] == $this->_user->get("login"));

		$birth_date = "";
		if ($row["birth_date"] !== NULL && $row["birth_date"] != "0000-00-00") {
			$date = explode("-", $row["birth_date"]);
			$birth_date = $this->_lang->get("core", "month." . intval($date[1])) . " " . $date[2] . ", " . $date[0];
		}

		$response->view = "user.profile";

		// Tags
		$response->tags = array (
			"id" => $row["id"],
			"username" => $row["login"],
			"name" => $row["name"],
			"group" => $this->_user->getGroupName($row["group"]),
			"group-id" => $row["group"],

			"gender" => $this->_user->getGender($row["gender"]),
			"birth-date" => $birth_date,
			"location" => $row["location"],
			"url" => $row["url"],
			"public-email" => $row["public_email"],

			"avatar-link" => $this->_user->getAvatarLink($row["avatar"]),
			"original-avatar-link" => $this->_user->getAvatarLink($row["avatar"], true),

			"edit-link" => SITE_PATH . "user/edit",
			"sessions-link" => SITE_PATH . "user/sessions",
			"message-send-link" => SITE_PATH . "messages/send/" . $row["login"],
				
			"online" => $online,
			"offline" => !$online,

			"owner" => $owner,
			"not-owner" => !$owner,

			"birth" => (!empty($birth_date)),

			"registration-date" => $this->_core->getDate($row["reg_date"]),
			"registration-time" => $this->_core->getTime($row["reg_date"])
		);

		if (!$online) {
			$response->tags["last-online-date"] = $this->_core->getDate($row["active"]);
			$response->tags["last-online-time"] = $this->_core->getTime($row["active"]);
		}

		return $response;
	}
	
	/**
	 * User add page
	 * @return Response
	 */
	public function addPage() {
		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "moduleName"), "user")
			->addBreadcrumbs($this->_lang->get("user", "add.moduleName"), "user/add");

		// Access denied
		if (!$this->_user->hasPermission("user.add")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		$response = new Response();

		$response->view = "user.add";

		$response->tags = array(
			"email" => isset($_POST["add"]["email"]) ? $_POST["add"]["email"] : "",
			"login" => isset($_POST["add"]["login"]) ? $_POST["add"]["login"] : "",
			"name" => isset($_POST["add"]["name"]) ? $_POST["add"]["name"] : "",
		);

		return $response;
	}
	
	/**
	 * Users list page
	 * @param int $page Page num
	 * @param string $query = null Search query
	 * @return Response
	 * @throws \Exception
	 */
	public function listPage($page, $query = null) {
		$page = intval($page);

		if ($this->_registry->get("Router")->getType() == BACKEND) {
			$this->_core->addBreadcrumbs($this->_lang->get("user", "moduleName"), "user");
		}

		$this->_core->addBreadcrumbs($this->_lang->get("user", "list.moduleName"), "user/list");

		if ($query !== null) {
			$this->_core->addBreadcrumbs($this->_lang->get("user", "search.moduleName"), "user/search");
		}

		// Access denied
		if (!$this->_user->hasPermission("user.list")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		// Users num query
		$this->_db
			->select("count(*)")
			->from(DBPREFIX . "user_profiles");

		if ($query !== null) {
			$this->_db
				->where("login", "LIKE", "%{$query}%")
				->or_where("name", "LIKE", "%{$query}%");
		}

		$num = $this->_db->result_array();

		// Database error
		if ($num === false) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "internalError"));
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		$num = $num[0][0];
		$pagination = new Pagination($num, $page, ((SIDETYPE == BACKEND) ? ADMIN_PATH : SITE_PATH) . "user/list/page/", $this->_config->get("user", "list.customPagination", array()));

		// Users list query
		$this->_db
			->select(array(
				"id", "login", "active", "name", "group", "avatar", "reg_date"
			))
			->from(DBPREFIX . "user_profiles");

		if ($query !== null) {
			$this->_db
				->where("login", "LIKE", "%{$query}%")
				->or_where("name", "LIKE", "%{$query}%");
		}

		$array = $this->_db
			->order_by("id", $this->_config->get("user", "list.sort", "DESC"))
			->limit($pagination->getSqlLimits())
			->result_array();

		// Database error
		if ($array === false) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "internalError"));
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		// Make users list
		$rows = [];

		foreach ($array as $row) {
			$online = $this->_user->checkOnline($row["active"]);
			$owner = ($row["login"] == $this->_user->get("login"));

			// Tags
			$rows[] = [
				"id" => $row["id"],
				"username" => $row["login"],
				"name" => $row["name"],
				"group" => $this->_user->getGroupName($row["group"]),
				"group-id" => $row["group"],
				"avatar-link" => $this->_user->getAvatarLink($row["avatar"]),
				"profile-link" => SITE_PATH . "user/profile/" . $row["login"],

				"edit-link" => ADMIN_PATH . "user/edit/" . $row["id"],
				"remove-link" => ADMIN_PATH . "user/remove/" . $row["id"],
				"message-send-link" => SITE_PATH . "messages/send/" . $row["login"],

				"online" => $online,
				"offline" => !$online,
				"owner" => $owner,
				"not-owner" => !$owner,

				"registration-date" => $this->_core->getDate($row["reg_date"]),
				"registration-time" => $this->_core->getTime($row["reg_date"]),
							
				"last-online-date" => $this->_core->getDate($row["active"]),
				"last-online-time" => $this->_core->getTime($row["active"])
			];
		}

		$response = new Response();

		$response->view = "user.list";

		$response->tags = array(
			"num" => $num,
			"rows" => $rows,
			"pagination" => $pagination,
			"query" => ($query === null ? "" : $query)
		);

		return $response;
	}

	/**
	 * User add
	 * @param string $email User Email
	 * @param string $login User login
	 * @param string $pass User password
	 * @param string $pass2 User password retype
	 * @param string $name User name
	 * @return Response
	 */
	public function add($email, $login, $pass, $pass2, $name) {
		// Access denied
		if (!$this->_user->hasPermission("user.add")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		return $this->_user->add($email, $login, $pass, $pass2, $name);
	}
	
	/**
	 * User remove page
	 * @param int $id User ID
	 * @return Response
	 */
	public function removePage($id) {
		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "moduleName"), "user")
			->addBreadcrumbs($this->_lang->get("user", "remove.moduleName"), "user/remove/" . $id);

		// Access denied
		if (!$this->_user->hasPermission("user.remove")) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "accessDenied"));
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		// If user not exists
		if (!$this->_user->exists($id)) {
			return new Response(2, "danger", $this->_lang->get("user", "remove.notExists"));
		}

		$response = new Response();
		$response->view = "user.remove";
		$response->tags["id"] = $id;
		return $response;
	}

	/**
	 * User remove
	 * @param int $id User ID
	 * @return Response
	 */
	public function remove($id) {
		// Access denied
		if (!$this->_user->hasPermission("user.remove")) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		return $this->_user->remove($id);
	}
	
	/**
	 * Auth page
	 * @return Response
	 */
	public function authPage() {
		$this->_core->addBreadcrumbs($this->_lang->get("user", "auth.moduleName"));

		// If user is logged
		if ($this->_user->isLogged()) {
			return new Response(1, "danger", $this->_lang->get("user", "auth.logged"));
		}

		$response = new Response();

		$response->view = "user.auth";
		$response->tags = array (
			"restore-link" => SITE_PATH . "user/restore",
			"register-link" => SITE_PATH . "user/register"
		);

		return $response;
	}

	/**
	 * User auth
	 * @param string $login User login
	 * @param string $password User password
	 * @param bool $tpc Third person computer
	 * @return Response
	 */
	public function auth($login, $password, $tpc) {
		// If user is logged
		if ($this->_user->isLogged()) {
			return new Response(2, "danger", $this->_lang->get("user", "auth.logged"));
		}
		
		$tpc = (bool)($tpc);

		// Check login and password for exists
		$query = $this->_db
			->select(array(
				"id", "login"
			))
			->from(DBPREFIX . "user_profiles")
			->where("login", "=", $login)
			->and_where("password", "=", $this->_user->passwordHash($password))
			->result_array();

		// Database error
		if ($query === false) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "internalError"));
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		// If incorrect login or password
		if (!isset($query[0])) {
			return new Response(3, "danger", $this->_lang->get("user", "incorrectLoginOrPassword"));
		}

		// Generate token
		$token = $this->_user->genToken($query[0]["id"], $query[0]["login"]);

		// Add token to database
		$query = $this->_db
			->insert_into(DBPREFIX . "user_sessions")
			->values(array(
				"user" => $query[0]["id"],
				"token" => $token,
				"auth_type" => 1,
				"auth_agent" => HTTP::getUserAgent(),
				"auth_ip" => HTTP::getIp()
			))
			->result();


		// Database error
		if ($query === false) {
			$this->_core->addBreadcrumbs($this->_lang->get("core", "internalError"));
			return new Response(1, "danger", $this->_lang->get("core", "internalError", [$this->_db->getError()]));
		}

		// If third person computer
		if ($tpc) {
			Sessions::set("auth_token", $token);
		} else {
			Cookies::set("auth_token", $token, 3600 * 24 * 31 * 12);
		}

		return new Response(0, "success", $this->_lang->get("core", "authSuccess"));
	}
	
	private $_email = "";
	private $_login = "";

	private $_pass = "";
	private $_pass2 = "";

	private $_name = "";

	/**
	 * Register page
	 * @return Response
	 */
	public function registerPage() {
		$this->_core->addBreadcrumbs($this->_lang->get("user", "register.moduleName"));

		if ($this->_user->isLogged()) {
			return new Response(1, "danger", $this->_lang->get("user", "register.needLogout"));
		}

		$response = new Response();

		$response->view = "user.register";

		$response->tags = array (
			"email" => $this->_email,
			"login" => $this->_login,
			"name" => $this->_name,
			"captcha" => $this->_registry->get("Captcha")->getCaptcha()
		);

		return $response;
	}

	/**
	 * User Register
	 * @param string $email User Email
	 * @param string $login User login
	 * @param string $pass User password
	 * @param string $pass2 User password retype
	 * @param string $name User name
	 * @param string $captcha Captcha code
	 * @return Response
	 */
	public function register($email, $login, $pass, $pass2, $name, $captcha) {
		// If user is logged
		if ($this->_user->isLogged()) {
			new Response(2, "danger", $this->_lang->get("user", "register.needLogout"));
		}

		$this->_email = StringFilters::filterHtmlTags($email);
		$this->_login = StringFilters::filterHtmlTags($login);
		$this->_pass = StringFilters::filterHtmlTags($pass);
		$this->_pass2 = StringFilters::filterHtmlTags($pass2);
		$this->_name = StringFilters::filterHtmlTags($name);

		// If empty fields
		if (empty($this->_email) || empty($this->_login) || empty($this->_pass) || empty($this->_pass2)) {
			return new Response(3, "warning", $this->_lang->get("core", "emptyFields"));
		}

		// If captcha is incorrect
		if (!$this->_registry->get("Captcha")->isCorrect($captcha)) {
			return new Response(4, "danger", $this->_lang->get("user", "register.incorrectCaptcha"));
		}

		return $this->_user->add($this->_email, $this->_login, $this->_pass, $this->_pass2, $this->_name);
	}
}