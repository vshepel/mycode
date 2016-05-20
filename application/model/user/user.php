<?php
/**
 * User class
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

use Exception;
use Registry;
use Response;

use harmony\http\HTTP;
use harmony\http\Cookies;
use harmony\http\Sessions;
use harmony\arrays\ArrayKeys;
use harmony\strings\StringFilters;
use harmony\strings\StringCheckers;

class User {
	/**
	 * Objects
	 */

	/**
	 * @var object Core object
	 */
	private $_core;

	/**
	 * @var object Cache object
	 */
	private $_cache;

	/**
	 * @var object Config object
	 */
	private $_config;

	/**
	 * @var object Lang object
	 */
	private $_lang;

	/**
	 * @var object DataBase object
	 */
	private $_db;

	/**
	 * @var object Router object
	 */
	private $_router;

	/**
	 * Vars
	 */

	/**
	 * @var bool Is logged?
	 */
	private $_logged = false;

	/**
	 * @var array User and groups arrays
	 */
	private $_user, $_groups = null;

	/**
	 * @var string|bool|null User token
	 */
	private $_token = null;

	/**
	 * Methods
	 */

	/**
	 * Get user token
	 * @return bool|string Token
	 */
	public function getToken() {
		if ($this->_token === null) {
			if (Sessions::get("auth_token") !== false)
				$this->_token = Sessions::get("auth_token");
			elseif (Cookies::get("auth_token") !== false)
				$this->_token = Cookies::get("auth_token");
			else
				$this->_token = false;
		}

		return $this->_token;
	}

	/**
	 * Wipe token
	 */
	public function wipeToken() {
		$this->_token = null;
	}

	/**
	 * Load objects
	 * @throws Exception
	 */
	public function loadObjects() {
		$registry = Registry::getInstance();

		$this->_core = $registry->get("Core");
		$this->_cache = $registry->get("Cache");
		$this->_config = $registry->get("Config");
		$this->_lang = $registry->get("Lang");
		$this->_db = $registry->get("Database");
		$this->_router = $registry->get("Router");
	}

	/**
	 * Init
	 * @throws Exception
	 */
	public function init() {
		$this->loadObjects();

		$token = $this->getToken();

		if ($token === false) {
			$this->_logged = false;
			$this->_guestInit();
		} else {
			$query = $this->_db
				->select(array(
					"user"
				))
				->from(DBPREFIX . "user_sessions")
				->where("token", "=", $token)
				->result_array();

			if ($query === false)
				throw new Exception("User error: " . $this->_db->getError());
			elseif (isset($query[0]["user"])) {
				$user = $query[0]["user"];

				$array = $this->_db
					->select("*")
					->from(DBPREFIX . "user_profiles")
					->where("id", "=", $user)
					->result_array();

				if ($array === false)
					throw new Exception("User error:" . $this->_db->getError());
				elseif (!isset($array[0])) {
					$this->_logged = false;
					$this->_guestInit();
				} else {
					$this->_user = $array[0];
					$this->_users[$this->_user["id"]] = $this->_user;
					$this->_logged = true;
					$this->_token = $token;

					$active = $this->_db
						->update(DBPREFIX . "user_profiles")
						->set(array(
							"active" => time()
						))
						->where("id", "=", $user)
						->result();

					if ($active === false)
						throw new Exception("User error:" . $this->_db->getError());
				}
			} else {
				Sessions::remove("auth_token");
				Cookies::remove("auth_token");
				
				// Guest
				$this->_logged = false;
				$this->_guestInit();
			}
		}
		
		// LANGUAGE
		$ulang = $this->get("lang");
		$clang = Cookies::get("lang");
   
		if ($ulang != false && !empty($ulang) && $this->_lang->setLang($ulang)) {}
		elseif($clang != false && $this->_lang->setLang($clang)) {}
		else {
			$this->_lang->setLang($this->_config->get("site", "language"));
		}
		
		define ("LOCALE", $this->_lang->getLang());
	}

	/**
	 * Init guest
	 */
	private function _guestInit() {
		$this->_user = array (
			"group" => $this->_config->get("user", "guestGroup", 4)
		);
	}

	/**
	 * Check User Exists
	 * @param int $id User ID
	 * @return bool User exists?
	 */
	public function exists($id) {
		$num = $this->_db
			->select("id")
			->from(DBPREFIX . "user_profiles")
			->where("id", "=", $id)
			->result_num();

		return ($num > 0);
	}

	/**
	 * Check user field for available
	 * @param string $field Field name
	 * @param mixed $value Field value
	 * @return bool Is available?
	 * @throws Exception
	 */
	public function check($field, $value) {

		$user = $this->_db
			->select("count(*)")
			->from(DBPREFIX . "user_profiles")
			->where($field, "=", $value)
			->result_array();

		if (isset($user[0][0]))
			return ($user[0][0] == 0);
		else
			throw new Exception("Check user field error:" . $this->_db->getError());
	}

	/**
	 * Add user
	 * @param string $email User email
	 * @param string $login User login
	 * @param string $pass User password
	 * @param string $pass2 User password retype
	 * @param string $name User name
	 * @return Response
	 */
	public function add($email, $login, $pass, $pass2, $name) {
		$email = StringFilters::filterHtmlTags($email);
		$login = StringFilters::filterHtmlTags($login);
		$pass = StringFilters::filterHtmlTags($pass);
		$pass2 = StringFilters::filterHtmlTags($pass2);
		$name = StringFilters::filterHtmlTags($name);

		$response = new Response;

		if (empty($email) || empty($login) || empty($pass) || empty($pass2)) {
			$response->code = 3;
			$response->type = "warning";
			$response->message = $this->_lang->get("core", "emptyFields");
		} elseif (!StringCheckers::isValidEmail($email)) {
			$response->code = 5;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "add.emailInvalid");
		} elseif (!StringCheckers::isValidLogin($login)) {
			$response->code = 6;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "add.loginInvalid");
		} elseif (!$this->check("email", $email)) {
			$response->code = 7;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "add.emailNotAvailable");
		} elseif (!$this->check("login", $login)) {
			$response->code = 7;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "add.loginNotAvailable");
		} elseif ($pass != $pass2) {
			$response->code = 8;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "add.passwordsNotMatch");
		} elseif (!empty($name) && !StringCheckers::isValidName($name)) {
			$response->code = 9;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "add.nameInvalid");
		} else {
			$query = $this->_db
				->insert_into(DBPREFIX . "user_profiles")
				->values(array(
					"email" => $email,
					"login" => $login,
					"password" => $this->passwordHash($pass),
					"group" => $this->_config->get("user", "defaultGroup", 3),
					"name" => $name,
					"reg_date" => time(),
					"reg_ip" => HTTP::getIp()
				))
				->result();

			if ($query === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$response->type = "success";
				$response->message = $this->_lang->get("user", "success");
			}
		}

		return $response;
	}

	/**
	 * Remove user by ID
	 * @param int $id User ID
	 * @throws Exception
	 */
	public function remove($id) {
		if ($this->exists($id)) {
			$query = $this->_db
				->delete_from(DBPREFIX . "user_profiles")
				->where("id", "=", $id)
				->result();

			if ($query === false)
				throw new Exception("Remove user error:" . $this->_db->getError());

			$query = $this->_db
				->delete_from(DBPREFIX . "user_sessions")
				->where("user", "=", $id)
				->result();

			if ($query === false)
				throw new Exception("Remove user error:" . $this->_db->getError());
		} else
			throw new Exception("Remove user error: Can't remove user because not exists user id " . $id);
	}

	/**
	 * Check user for logged
	 * @return bool Is logged?
	 */
	public function isLogged() {
		return $this->_logged;
	}

	/**
	 * Check User Online
	 * @param int $active Active time
	 * @return bool Is active?
	 */
	public function checkOnline($active) {
		return ((time() - $this->_config->get("user", "activeTime", 60)) <= $active);
	}

	/**
	 * Get User Avatar Link
	 * @param string $avatar User avatar field
	 * @param bool $original Original avatar link?
	 * @return string User avatar link
	 */
	public function getAvatarLink($avatar, $original = false) {
		if (empty($avatar))
			return PATH . "images/noavatar.png";
		else
			return PATH . "upload/avatar/" . ($original ? "original_" : "") . $avatar;
	}

	/**
	 * User fields
	 */

	/**
	 * @var array User fields
	 */
	private $_users = array();

	/**
	 * Get user fields
	 * @param int $id User ID
	 * @param string $field Field
	 * @return mixed|false
	 */
	public function getUser($id, $field) {
		if (!isset($this->_users[$id])) {
			$user = $this->_db
				->select("*")
				->from(DBPREFIX . "user_profiles")
				->where("id", "=", $id)
				->result_array();

			if (isset($user[0]))
				$this->_users[$id] = $user[0];
			else
				$this->_users[$id] = false;
		}

		return isset($this->_users[$id]) ? (
			isset($this->_users[$id][$field]) ?
				$this->_users[$id][$field]
				: false
		) : false;
	}
	/**
	 * Get User Avatar Link By User ID
	 * @param int $id User ID
	 * @return string User avatar link
	 */
	public function getAvatarLinkById($id) {
		$avatar = $this->getUser($id, "avatar");
		return ($avatar === false) ? $this->getAvatarLink("") : $this->getAvatarLink($avatar);
	}

	/**
	 * Get user login by User ID
	 * @param int $id User ID
	 * @return string User Login
	 */
	public function getUserLogin($id) {
		$login = $this->getUser($id, "login");
		return ($login === false) ? "Anonymous" : $login;
	}

	/**
	 * Get user name by User ID
	 * @param int $id User ID
	 * @return string
	 */
	public function getUserName($id) {
		$name = $this->getUser($id, "name");
		return (!empty($name)) ? $name : $this->getUser($id, "login");
	}

	/**
	 * Get Gender name by Gender ID
	 * @param int $num Gender ID
	 * @return string Gender name
	 */
	public function getGender($num) {
		switch ($num) {
			case 1: return $this->_lang->get("user", "gender.man");
			case 2: return $this->_lang->get("user", "gender.woman");
			default: return $this->_lang->get("user", "gender.none");
		}
	}

	/**
	 * Groups
	 */

	/**
	 * Groups init
	 * @throws Exception
	 */
	private function _initGroups() {
		if (!is_array($this->_groups)) {
			$cache = $this->_cache->get("user.groups", $this->_lang->getLang());

			if ($cache === false) {
				$groups = $this->_db
					->select(array(
						"id", "name", "extends", "permissions"
					))
					->from(DBPREFIX . "user_groups")
					->result_array();

				if ($groups === false)
					throw new Exception("Get user groups error: " . $this->_db->getError());
				else {
					$this->_groups = array();

					foreach ($groups as $group) {
						$permissions = array ();

						foreach (json_decode($group["permissions"]) as $permission)
							$permissions = ArrayKeys::continuationByKeys($permissions, explode(".", $permission));

						$this->_groups[$group["id"]] = array(
							$this->_lang->parseString($group["name"]),
							json_decode($group["extends"]),
							$permissions
						);
					}

					foreach ($this->_groups as &$group)
						foreach ($group[1] as $ext)
							$group[2] = array_merge_recursive($this->_groups[$ext][2], $group[2]);
				}

				$this->_cache->push("user.groups", $this->_lang->getLang(), $this->_groups);
			} else
				$this->_groups = $cache;
		}
	}
	
	/**
	 * Get Groups array
	 * @return array
	 */
	public function getGroups() {
		$this->_initGroups();
		return $this->_groups;
	}

	/**
	 * Get Group name by Group ID
	 * @param int $groupId Group ID
	 * @return string Group name
	 */
	public function getGroupName($groupId) {
		$this->_initGroups();

		return isset($this->_groups[$groupId][0]) ? $this->_groups[$groupId][0] : $this->_lang->get("user", "unknownGroup");
	}

	/**
	 *  Check User (Group) permission for action
	 * @param string $permission Permission
	 * @param int $group Group
	 * @return bool Is allow?
	 */
	public function hasPermission($permission, $group = null) {
		$this->_initGroups();

		if ($this->_router->getType() == BACKEND)
			$permission = "admin." . $permission;

		$group = ($group === null) ? $this->get("group") : $group;
		$permission = explode(".", $permission);

		return ArrayKeys::checkByKeys($this->_groups[$group][2], $permission, array("*"));
	}

	/**
	 * User information
	 */

	/**
	 * Get user information
	 * @param string $field Field
	 * @return mixed Get field content
	 */
	public function get($field = null) {
		if ($field == null)
			return $this->_user;
		else
			return isset($this->_user[$field]) ? $this->_user[$field] : false;
	}

	/**
	 * Update user information
	 * @param int $id User ID
	 * @param array $vars Vars array
	 * @throws Exception
	 */
	public function update($id, $vars) {
		if ($id == $this->get("id"))
			foreach ($vars as $key => $value)
				$this->_user[$key] = $value;

		$result = $this->_db
			->update(DBPREFIX . "user_profiles")
			->set($vars)
			->where("id", "=", intval($id))
			->result();

		if ($result === false) {
			throw new Exception("User error:" . $this->_db->getError());
		}
	}

	/**
	 * Security
	 */

	/**
	 * Hash User password
	 * @param string $password Password for hash
	 * @return string Hashed password
	 */
	public function passwordHash($password) {
		return hash("sha256", md5($password) . $this->_config->get("user", "passwordSalt", ""));
	}

	/**
	 * Close user session
	 * @param string $token Session token
	 * @return bool
	 */
	public function sessionClose($token) {
		$query = $this->_db
			->delete_from(DBPREFIX . "user_sessions")
			->where("token", "=", $token)
			->result();

		return !($query === false);
	}

	/**
	 * Generate user token
	 * @param int $userId User ID
	 * @param string $login User Login
	 * @return string User token
	 */
	public function genToken($userId, $login) {
		$hash = "";

		for ($i = 1; $i <= 32; $i++)
			$hash .= mt_rand(0, 9);

		return hash("sha512", $userId . "_" . $login . "_" . $hash);
	}
}
