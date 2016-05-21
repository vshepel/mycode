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
		$response = new Response();

		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "list.moduleName"), "user/list");

		/**
		 * Database query
		 */
		$array = $this->_db
			->select(array(
				"id" ,"login", "active", "name", "group", "avatar",
				"birth_date", "gender", "location", "url", "public_email"
			))
			->from(DBPREFIX . "user_profiles")
			->where("login", "=", $user)
			->result_array();

		/**
		 * Database error
		 */
		if ($array === false) {
			$title = $this->_lang->get("core", "internalError");

			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
		}

		/**
		 * User not found
		 */
		elseif (!isset($array[0])) {
			throw new NotFoundException();
		}

		/**
		 * Have permissions for view profile
		 */
		elseif (($array[0]["id"] == $this->_user->get("id") && $this->_user->hasPermission("user.profile.my")) || $this->_user->hasPermission("user.profile.other")) {
			$row = $array[0];
			$title = $row["login"];

			$response->view = "user.profile";

			$online = $this->_user->checkOnline($row["active"]);
			$owner = ($row["login"] == $this->_user->get("login"));

			$birth_date = "";
			if ($row["birth_date"] !== NULL) {
				$date = explode("-", $row["birth_date"]);
				$birth_date = $this->_lang->get("core", "month." . intval($date[1])) . " " . $date[2] . ", " . $date[0];
			}

			/**
			 * Tags
			 */
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

				"birth" => !empty($birth_date)
			);

			if (!$online) {
				$response->tags["last-online-date"] = $this->_core->getDate($row["active"]);
				$response->tags["last-online-time"] = $this->_core->getTime($row["active"]);
			}
		}

		/**
		 * Haven't permissions for view profile
		 */
		else {
			$title = $this->_lang->get("core", "accessDenied");

			$response->code = 3;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		}

		$this->_core
			->addBreadcrumbs($title);

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

		$response = new Response();

		if ($this->_user->hasPermission("user.add")) {
			$response->view = "user.add";

			$response->tags = array(
				"email" => isset($_POST["add"]["email"]) ? $_POST["add"]["email"] : "",
				"login" => isset($_POST["add"]["login"]) ? $_POST["add"]["login"] : "",
				"name" => isset($_POST["add"]["name"]) ? $_POST["add"]["name"] : "",
			);
		} else {
			$this->_core
				->addBreadcrumbs($this->_lang->get("core", "accessDenied"));

			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		}

		return $response;
	}
	
	/**
	 * Users list page
	 * @param int $page Page num
	 * @return Response
	 * @throws \Exception
	 */
	public function listPage($page) {
		$response = new Response();

		$page = intval($page);

		if ($this->_registry->get("Router")->getType() == BACKEND)
			$this->_core
				->addBreadcrumbs($this->_lang->get("user", "moduleName"), "user");

		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "list.moduleName"), "user/list");

		/**
		 * Check permissions for user list
		 */
		if ($this->_user->hasPermission("user.list")) {
			/**
			 * Users num query
			 */
			$num = $this->_db
				->select("count(*)")
				->from(DBPREFIX . "user_profiles")
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
				$pagination = new Pagination($num, $page, SITE_PATH . "user/list/page/", $this->_config->get("user", "list.customPagination", array()));

				/**
				 * Users list query
				 */
				$array = $this->_db
					->select(array(
						"id", "login", "active", "name", "group", "avatar"
					))
					->from(DBPREFIX . "user_profiles")
					->order_by("id", $this->_config->get("user", "list.sort", "DESC"))
					->limit($pagination->getSqlLimits())
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
				 * Make users list
				 */
				else {
					$rows = [];

					foreach ($array as $row) {
						$online = $this->_user->checkOnline($row["active"]);
						$owner = ($row["login"] == $this->_user->get("login"));

						/**
						 * Tags
						 */
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
							
							"last-online-date" => $this->_core->getDate($row["active"]),
							"last-online-time" => $this->_core->getTime($row["active"])
						];
					}

					$response->code = 0;
					$response->view = "user.list";

					$response->tags = array(
						"num" => $num,
						"rows" => $rows,
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
	 * User add
	 * @param string $email User Email
	 * @param string $login User login
	 * @param string $pass User password
	 * @param string $pass2 User password retype
	 * @param string $name User name
	 * @return Response
	 */
	public function add($email, $login, $pass, $pass2, $name) {
		if ($this->_user->hasPermission("user.add"))
			return $this->_user->add($email, $login, $pass, $pass2, $name);

		$response = new Response();

		$response->code = 2;
		$response->type = "danger";
		$response->message = $this->_lang->get("core", "accessDenied");

		return $response;
	}
	
	/**
	 * User remove page
	 * @param int $id User ID
	 * @return Response
	 */
	public function removePage($id) {
		$response = new Response();

		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "moduleName"), "user")
			->addBreadcrumbs($this->_lang->get("user", "remove.moduleName"), "user/remove/" . $id);

		if ($this->_user->hasPermission("user.remove")) {
			if ($this->_user->exists($id)) {
				$response->view = "user.remove";
				$response->tags["id"] = $id;
			} else {
				$response->code = 2;
				$response->type = "danger";
				$response->message = $this->_lang->get("user", "remove.notExists");
			}
		} else {
			$this->_core
				->addBreadcrumbs($this->_lang->get("core", "accessDenied"));

			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		}

		return $response;
	}

	/**
	 * User remove
	 * @param int $id User ID
	 * @return Response
	 */
	public function remove($id) {
		if ($this->_user->hasPermission("user.remove"))
			return $this->_user->remove($id);

		$response = new Response();

		$response->code = 2;
		$response->type = "danger";
		$response->message = $this->_lang->get("core", "accessDenied");

		return $response;
	}
	
	/**
	 * Auth page
	 * @return Response
	 */
	public function authPage() {
		$response = new Response();

		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "auth.moduleName"));

		if ($this->_user->isLogged()) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "auth.logged");
		} else {
			$response->view = "user.auth";

			$response->tags = array (
				"restore-link" => SITE_PATH . "user/restore",
				"register-link" => SITE_PATH . "user/register"
			);
		}

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
		$response = new Response();

		$tpc = (bool)($tpc);

		if ($this->_user->isLogged()) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "auth.logged");
		} else {
			$query = $this->_db
				->select(array(
					"id", "login"
				))
				->from(DBPREFIX . "user_profiles")
				->where("login", "=", $login)
				->and_where("password", "=", $this->_user->passwordHash($password))
				->result_array();

			if ($query === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} elseif (!isset($query[0])) {
				$response->code = 3;
				$response->type = "danger";
				$response->message = $this->_lang->get("user", "incorrectLoginOrPassword");
			} else {
				$token = $this->_user->genToken($query[0]["id"], $query[0]["login"]);

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

				if ($query === false) {
					$response->code = 1;
					$response->type = "danger";
					$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
				} else {
					if ($tpc)
						Sessions::set("auth_token", $token);
					else
						Cookies::set("auth_token", $token, 3600 * 24 * 31 * 12);

					$response->type = "success";
					$response->message = $this->_lang->get("core", "authSuccess");
				}
			}
		}

		return $response;
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
		$response = new Response();

		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "register.moduleName"));

		if ($this->_user->isLogged()) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "register.needLogout");
		} else {
			$response->view = "user.register";

			$response->tags = array (
				"email" => $this->_email,
				"login" => $this->_login,
				"name" => $this->_name,
				"captcha-link" => $this->_registry
					->get("Captcha")
					->getCaptchaLink()
			);
		}

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
		$response = new Response();

		if ($this->_user->isLogged()) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "register.needLogout");
		} else {
			$this->_email = StringFilters::filterHtmlTags($email);
			$this->_login = StringFilters::filterHtmlTags($login);
			$this->_pass = StringFilters::filterHtmlTags($pass);
			$this->_pass2 = StringFilters::filterHtmlTags($pass2);
			$this->_name = StringFilters::filterHtmlTags($name);

			if (empty($this->_email) || empty($this->_login) || empty($this->_pass) || empty($this->_pass2)) {
				$response->code = 3;
				$response->type = "warning";
				$response->message = $this->_lang->get("core", "emptyFields");
			} elseif (!$this->_registry->get("Captcha")->isCorrect($captcha)) {
				$response->code = 4;
				$response->type = "danger";
				$response->message = $this->_lang->get("user", "register.incorrectCaptcha");
			} else
				$response = $this->_user->add($this->_email, $this->_login, $this->_pass, $this->_pass2, $this->_name);
		}

		return $response;
	}
}