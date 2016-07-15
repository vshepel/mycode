<?php
/**
 * User Auth Model
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
use harmony\strings\StringFilters;
use harmony\strings\Strings;
use Response;
use Exception;

use harmony\strings\StringCheckers;
use harmony\files\UploadFiles;
use harmony\files\ImageResize;
use harmony\files\Files;

class Edit extends AppModel {
	/**
	 * @var array
	 */
	private $_args = null;

	/**
	 * @var array User vars
	 */
	private $_vars;

	/**
	 * Get user field value
	 * @param string $field Field
	 * @return string
	 */
	private function _get($field) {
		return isset($this->_vars[$field]) ? $this->_vars[$field] : "";
	}

	/**
	 * Update user edit vars
	 * @param int $userId User ID
	 * @throws Exception
	 */
	private function _updateVars($userId) {
		if ($userId == $this->_user->get("id"))
			$this->_vars = $this->_user->get();
		else {
			$array = $this->_db
				->select("*")
				->from(DBPREFIX . "user_profiles")
				->where("id", "=", $userId)
				->result_array();

			if ($array === false)
				throw new Exception("User edit error: " . $this->_db->getError());
			elseif (!isset($array[0]))
				throw new Exception("User edit error: User not found");
			else
				$this->_vars = $array[0];
		}
	}

	/**
	 * Edit user info page
	 * @param string $name Page name
	 * @param int|null $userId = null User ID (null, if edit self profile) 
	 * @return Response
	 * @throws \Exception
	 */
	public function page($name, $userId = null) {
		$response = new Response();

		if ($this->_user->isLogged() && $this->_user->hasPermission("user.edit." . $name)) {
			if ($userId == null) $userId = $this->_user->get("id");
			$this->_updateVars($userId);
			
			if ($name === false) $name = "main";

			// Pages
			switch($name) {
				// Main
				case "main":
					$title = $this->_lang->get("user", "edit.main.title");
					$response->view = "user.edit.main";
					$options = array ("gender" => "", "day" => "", "mouth" => "", "year" => "");

					// Gender
					$gender = $this->_get("gender");
					$options["gender"] .= "<option value=\"0\"" . (($gender == 0) ? " selected" : "") . ">" . $this->_lang->get("user", "gender.none") . "</option>";
					$options["gender"] .= "<option value=\"1\"" . (($gender == 1) ? " selected" : "") . ">" . $this->_lang->get("user", "gender.man") . "</option>";
					$options["gender"] .= "<option value=\"2\"" . (($gender == 2) ? " selected" : "") . ">" . $this->_lang->get("user", "gender.woman") . "</option>";

					// Birth date
					if ($this->_get("birth_date")) {
						$birth_date = explode("-", $this->_get("birth_date"));
					} else {
						$birth_date = [0, 0, 0];
					}

					for ($i = 31; $i >= 1; $i--)
						$options["day"] .= "<option value=\"{$i}\"" . (($birth_date[2] == $i) ? " selected" : "") . ">{$i}</option>";

					for ($i = 12; $i >= 1; $i--)
						$options["mouth"] .= "<option value=\"{$i}\"" . (($birth_date[1] == $i) ? " selected" : "") . ">" . $this->_lang->get("core", "month.{$i}") . "</option>";

					for ($i = date("Y"); $i >= 1900; $i--)
						$options["year"] .= "<option value=\"{$i}\"" . (($birth_date[0] == $i) ? " selected" : "") . ">{$i}</option>";

					// Languages
					$languages = "";
					$active = $this->_get("lang");
					foreach ($this->_lang->getLangs() as $lang => $name) {
						$languages[] = [
							"name" => $name,
							"value" => $lang,
							"active" => ($lang == $active)
						];
					}
					
					// Groups
					$groups = [];
					$group_change = $this->_user->hasPermission("user.edit.group");
					if ($group_change) {
						$active = $this->_get("group");
						foreach ($this->_user->getGroups() as $id => $row) {
							$groups[] = [
								"name" => $row[0] . " ({$id})",
								"value" => $id,
								"active" => ($id == $active)
							];
						}
					}

					if ($this->_args === null)
						$response->tags = array (
							"gender" => $this->_get("gender"),
							"gender-options" => $options["gender"],

							"birth-date" => $this->_get("birth_date"),
							"day-options" => $options["day"],
							"mouth-options" => $options["mouth"],
							"year-options" => $options["year"],

							"name" => $this->_get("name"),
							"location" => $this->_get("location"),
							"url" => $this->_get("url"),
							"public-email" => $this->_get("public_email"),
						);
					else
						$response->tags = $this->_args;
						
					$response->tags["langs"] = $languages;
					$response->tags["groups"] = $groups;
					$response->tags["group-change"] = $group_change;
				break;

				// Password change page
				case "password":
					$title = $this->_lang->get("user", "edit.password.title");
					$response->view = "user.edit.password";
				break;

				// Avatar change page
				case "avatar":
					$title = $this->_lang->get("user", "edit.avatar.title");
					$response->view = "user.edit.avatar";

					$response->tags = array (
						"avatar-link" => $this->_user->getAvatarLink($this->_get("avatar")),
						"original-avatar-link" => $this->_user->getAvatarLink($this->_get("avatar"), true)
					);
				break;

				// Action not found page
				default:
					$title = $this->_lang->get("core", "actionNotFound");
					$response->code = 3;
					$response->type = "danger";
					$response->message = $this->_lang->get("core", "actionNotFound");
				break;
			}

			$response->tags["user-id"] = $userId;
			$response->tags["edit-name"] = $name;
			
			if (SIDETYPE == BACKEND)
				$response->tags["edit-link"] = ADMIN_PATH . "user/edit/" . $userId;
			else
				$response->tags["edit-link"] = SITE_PATH . "user/edit";
		} else {
			$title = $this->_lang->get("core", "accessDenied");
			$response->code = 2;
			$response->type = "danger";
			$response->message = $this->_lang->get("core", "accessDenied");
		}

		if (SIDETYPE == BACKEND)
			$this->_core
				->addBreadcrumbs($this->_lang->get("user", "moduleName"), "user")
				->addBreadcrumbs($this->_lang->get("user", "edit.moduleName"), "user/edit")
				->addBreadcrumbs($this->_get("login"), SITE_PATH . "user/profile/" . $this->_get("login"))
				->addBreadcrumbs($title);
		else
			$this->_core
				->addBreadcrumbs($this->_lang->get("user", "edit.moduleName"), "user/edit")
				->addBreadcrumbs($title);

		return $response;
	}

	/**
	 * Edit user info
	 * @param string $type Edit type
	 * @param array $args Edit args
	 * @param int|null $userId = null User ID (null, if edit self profile)
	 * @return Response
	 */
	public function edit($type, array $args, $userId = null) {
		// Access denied
		if (!$this->_user->isLogged() || !$this->_user->hasPermission("user.edit." . $type)) {
			return new Response(2, "danger", $this->_lang->get("core", "accessDenied"));
		}

		if ($userId == null) {
			$userId = $this->_user->get("id");
		}

		$this->_updateVars($userId);
			
		// ACTIONS
		switch($type) {
			// ACTION: MAIN
			case false:
			case "main":
				$db_values = []; // Database values

				// Name
				if (isset($args["name"]) && !empty($args["name"])) {
					if (StringCheckers::isValidName($args["name"])) {
						$db_values["name"] = $args["name"];
					} else {
						return new Response(5, "danger", $this->_lang->get("user", "edit.main.nameInvalid"));
					}
				}

				// Gender
				if (isset($args["gender"])) {
					$db_values["gender"] = intval($args["gender"]);

					if (!in_array($db_values["gender"], [0, 1, 2])) {
						$db_values["gender"] = 0;
					}
				}

				// Update birth date
				if (isset($args["year"], $args["mouth"], $args["day"])) {
					$year = intval($args["year"]);
					$mouth = intval($args["mouth"]);
					$day = intval($args["day"]);

						if ($year >= 1900 && $year <= date("Y") && $mouth > 0 && $mouth <= 12 && $day > 0 && $day <= 31) {
						$db_values["birth_date"] = date("Y-m-d", mktime(0, 0, 0, $mouth, $day, $year));
						echo $db_values["birth_date"];
					} else {
						$db_values["birth_date"] = null;
					}
				}

				// Location
				if (isset($args["location"])) {
					$db_values["location"] = StringFilters::filterHtmlTags($args["location"]);

					if (Strings::length($db_values["location"]) > 128) {
						return new Response(4, "warning", $this->_lang->get("core", "longFields"));
					}
				}

				// URL
				if (isset($args["url"])) {
					$db_values["url"] = StringFilters::filterHtmlTags($args["url"]);

					if (Strings::length($db_values["url"]) > 256) {
						return new Response(4, "warning", $this->_lang->get("core", "longFields"));
					}
				}

				// Public Email
				if (!empty($args["public_email"])) {
					if (StringCheckers::isValidEmail($args["public_email"])) {
						$db_values["public_email"] = $args["public_email"];
					} else {
						return new Response(6, "danger", $this->_lang->get("user", "edit.main.emailInvalid"));
					}
				}

				// Lang
				if (isset($args["lang"])) {
					if ($this->_lang->available($args["lang"])) {
						$db_values["lang"] = $args["lang"];
					} else {
						return new Response(4, "danger", $this->_lang->get("core", "incorrectFields"));
					}
				}

				// Update group
				if ($this->_user->hasPermission("user.edit.group") && isset($args["group"])) {
					$db_values["group"] = intval($args["group"]);
				}

				// Update query
				$this->_user->update($userId, $db_values);
				return new Response(0, "success", $this->_lang->get("user", "edit.success"));
			break;

			// ACTION: PASSWORD CHANGE
			case "password":
				if (isset($args["old_password"], $args["new_password"], $args["new_password_2"])) {
					if (empty($args["old_password"]) || empty($args["new_password"]) || empty($args["new_password_2"])) {
						return new Response(4, "warning", $this->_lang->get("core", "emptyFields"));
					} elseif ($args["new_password"] != $args["new_password_2"]) {
						return new Response(5, "warning", $this->_lang->get("user", "edit.password.notMatch"));
					} elseif ($this->_user->passwordHash($args["old_password"]) != $this->_get("password")) {
						return new Response(6, "warning", $this->_lang->get("user", "edit.password.incorrectPassword"));
					}

					$this->_user->update($userId, [
						"password" => $this->_user->passwordHash($args["new_password"])
					]);

					return new Response(0, "success", $this->_lang->get("user", "edit.success"));
				}
			break;

			// ACTION: AVATAR CHANGE
			case "avatar":
				$avatar = $this->_get("avatar");
				$files = UploadFiles::getInstance();

				// Avatar delete
				if (isset($args["delete"])) {
					if (empty($avatar)) {
						return new Response(4, "danger", $this->_lang->get("user", "edit.avatar.delete.none"));
					} else {
						if (!empty($avatar)) {
							$this->_user->update($userId, [
								"avatar" => ""
							]);

							$files->delete("avatar", $avatar);
							$files->delete("avatar", "original_" . $avatar);

							return new Response(0, "success", $this->_lang->get("user", "edit.avatar.delete.success"));
						}
					}
				}

				// Avatar upload
				if (isset($_FILES["avatar"])) {
					// No uploaded file
					if (isset($_FILES["avatar"]["error"]) && $_FILES["avatar"]["error"] == 4) {
						return new Response(5, "warning", $this->_lang->get("core", "emptyFields"));
					}

					$file = $_FILES["avatar"];

					// Invalid avatar extension
					if (!in_array(Files::getFileExtension($file["name"]), ["jpg", "jpeg", "png", "gif"])) {
						return new Response(6, "danger", $this->_lang->get("user", "edit.avatar.invalid"));
					}
						
					// Names
					$name = strtolower($userId . "_" . time() . "." . Files::getFileExtension($file["name"]));
					$originalFilePath = $files->getUploadDirectory() . DS . "avatar" . DS . "original_" . $name;
					$filePath = $files->getUploadDirectory() . DS . "avatar" . DS . $name;

					// Upload avatar original
					$upload = $files->upload($file, "avatar", "original_" . $name);

					if ($upload->code != 0) {
						return $upload;
					}

					// Resize new avatar
					$resize = new ImageResize();

					try {
						$resize->load($originalFilePath);
					} catch (\Exception $e) {
						return new Response(1, "danger", $this->_lang->get("core", "internalError") . " ({$e->getMessage()})");
					}

					// If avatar too small
					if ($resize->getHeight() < 200 || $resize->getWidth() < 200) {
						return new Response(7, "danger", $this->_lang->get("user", "edit.avatar.small"));
					}

					// Delete old avatar
					if (!empty($avatar)) {
						$files->delete("avatar", $avatar);
						$files->delete("avatar", "original_" . $avatar);
					}

					// Values
					$compress = $this->_config->get("user", "avatarCompress", 80);
					$type = IMAGETYPE_JPEG;

					// Save new avatar
					$resize->resize(200, 200)->save($filePath, $type, $compress);

					// Load original
					$orig = new ImageResize();
					$orig->load($originalFilePath);

					// Change original image size
					$new_x = $orig->getWidth(); $new_y = $orig->getHeight();
					$max_x = 1280; $max_y = 1280;
					if ($new_x > $max_x || $new_y > $max_y) {
						$max = ($new_x > $new_y) ? $new_x : $new_y;
						$new_x = $new_x * ($max_x / $max);
						$new_y = $new_y * ($max_y / $max);
					}

					// Compress original
					$orig->resize($new_x, $new_y)->save($originalFilePath, $type, $compress);

					$this->_user->update($userId, array (
						"avatar" => $name
					));

					return new Response(0, "success", $this->_lang->get("user", "edit.avatar.success"));
				}

			break;
		}

		// Action not found
		return new Response(3, "danger", $this->_lang->get("core", "actionNotFound"));
	}
}
