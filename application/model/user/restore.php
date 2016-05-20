<?php
/**
 * User Restore Model
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

use harmony\http\HTTP;

class Restore extends AppModel {
	/**
	 * @var bool Is success?
	 */
	private $_success = false;

	/**
	 * @var string User login
	 */
	private $_login = "";

	/**
	 * Check
	 * @param bool $key Check by key?
	 * @param string|int $var Restore key (if key FALSE, then user id)
	 * @return bool
	 * @throws \Exception
	 */
	private function _check($key, $var) {
		$key = (bool)($key);
		$var = $key ? $var : intval($var);

		if ($key === true)
			$result = $this->_db
				->select("count(*)")
				->from(DBPREFIX . "user_restore")
				->where("key", "=", $var)
				->result_array();
		else
			$result = $this->_db
				->select("count(*)")
				->from(DBPREFIX . "user_restore")
				->where("user", "=", $var)
				->and_where("UNIX_TIMESTAMP(`timestamp`)", ">", time() - 30, false)
				->result_array();

		if (!isset($result[0][0]))
			throw new \Exception("Error restore check: " . $this->_db->getError());

		return ($result[0][0] > 0);
	}

	/**
	 * Auth pages
	 * @return Response
	 */
	public function page() {
		$response = new Response();

		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "restore.moduleName"));

		if ($this->_user->isLogged()) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "auth.logged");
		} else {
			$response->view = "user.restore.page";

			$response->tags = array (
				"auth-link" => SITE_PATH . "user/auth",
				"register-link" => SITE_PATH . "user/register",
				"login" => $this->_login,

				"success" => $this->_success,
				"not-success" => !$this->_success
			);
		}

		return $response;
	}

	/**
	 * Restore page
	 * @param string $key Restore key
	 * @return Response
	 */
	public function pageRestore($key) {
		$response = new Response();

		$this->_core
			->addBreadcrumbs($this->_lang->get("user", "restore.moduleName"));

		if ($this->_user->isLogged()) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "auth.logged");
		} elseif (!$this->_check(true, $key)) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "restore.change.badKey");
		} else {
			$response->view = "user.restore.restore";

			$response->tags = array (
				"auth-link" => SITE_PATH . "user/auth",
				"register-link" => SITE_PATH . "user/register",
				"login" => $this->_login,

				"success" => $this->_success,
				"not-success" => !$this->_success
			);
		}

		return $response;
	}

	/**
	 * Restore password
	 * @param string $login User login or email
	 * @return object
	 */
	public function send($login) {
		$response = new Response();

		$this->_login = $login;

		$query = $this->_db
			->select(array(
				"id", "email"
			))
			->from(DBPREFIX . "user_profiles")
			->where("login", "=", $login)
			->or_where("email", "=", $login)
			->result_array();

		if ($query === false) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
		} elseif (!isset($query[0]["id"])) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "restore.send.userNotFound");
		} elseif ($this->_check(false, $query[0]["id"])) {
			$response->code = 3;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "restore.send.alreadySent");
		} else {
			$key = $this->_user->genToken($query[0]["id"], $query[0]["email"]);

			$update = $this->_db
				->insert_into(DBPREFIX . "user_restore")
				->values(array(
					"user" => $query[0]["id"],
					"key" => $key
				))
				->result();

			if ($update === false) {
				$response->code = 1;
				$response->type = "danger";
				$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
			} else {
				$email = $query[0]["email"];

				$mail = $this->_registry
					->get("SendMail");

				$send = $mail
					->send($email,
						$this->_lang->get("user", "restore.moduleName"),
						$this->_view->parse("user/restore/mail", [
							"restore-link" => FSITE_PATH . "user/restore/" . $key,
							"restore-key" => $key,
							"ip-address" => HTTP::getIp(),
							"email" => $email
						]),
						"Content-Type: text/html; charset=utf-8"
					);

				if (!$send) {
					$response->code = 1;
					$response->type = "danger";
					$response->message = $this->_lang->get("core", "internalError") . " (" . $mail->getError() . ")";
				} else {
					$this->_success = true;

					$response->type = "success";
					$response->message = $this->_lang->get("user", "restore.send.success");
				}
			}
		}

		return $response;
	}

	/**
	 * Change password
	 * @param string $key Restore key
	 * @param string $password Password
	 * @param string $password2 Password retype
	 * @return Response
	 * @throws \Exception
	 */
	public function change($key, $password, $password2) {
		$response = new Response();

		$query = $this->_db
			->select(array(
				"user"
			))
			->from(DBPREFIX . "user_restore")
			->where("key", "=", $key)
			->result_array();

		if ($query === false) {
			$response->code = 1;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "internalError", [$this->_db->getError()]);
		} elseif (!isset($query[0]["user"])) {
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "restore.change.badKey");
		} elseif (empty($password) || empty($password2)) {
			$response->code = 3;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "emptyFields");
		} elseif ($password !== $password2) {
			$response->code = 4;
			$response->type = "danger";
			$response->message = $this->_lang->get("user", "restore.change.passwordsNotMatch");
		} else {
			$this->_user
				->update($query[0]["user"], array (
					"password" => $this->_user->passwordHash($password)
				));

			$this->_db
				->delete_from(DBPREFIX . "user_restore")
				->where("key", "=", $key)
				->result();

			$response->type = "success";
			$response->message = $this->_lang->get("user", "restore.change.success");
		}

		return $response;
	}
}
