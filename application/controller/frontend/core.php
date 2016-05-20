<?php
/**
 * Core Frontend Controller
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

namespace controller\frontend;

use AppController;

use harmony\http\HTTP;
use harmony\http\Cookies;

class Core extends AppController {
	public $__routes = array (
		"captcha" => null,
		"lang/([A-Za-z\\-\\_]+)" => "lang",
		"lang" => null
	);

	public function action_index() {
		$lang = $this->_registry->get("Lang");
		$this->_core
			->setTitle($lang->get("core", "accessDenied"))
			->addBreadcrumbs($lang->get("core", "accessDenied"));

		$this->_view
			->alert("danger", $lang->get("core", "accessDenied"))
			->render();
	}

	public function action_captcha() {
		$this->_registry->get("Captcha")->gen()->out();
	}

	public function action_lang($args) {
		$lang = isset($_POST["lang"]) ? $_POST["lang"] : $args[0];
		
		if ($this->_registry->get("Lang")->available($args[0])) {
			Cookies::set("lang", $lang, 3600 * 24 * 31 * 12);
			
			$user = $this->_registry->get("User");
				
			if ($user->isLogged())
				$user->update($user->get("id"), [
					"lang" => $lang
				]);
		}
		
		$referer = HTTP::getReferer();
		
		if (!empty($referer) && !preg_match("~core/lang/([A-Za-z\\-\\_]+)~", $referer))
			HTTP::redirect($referer);
		else
			HTTP::redirect(SITE_PATH);
	}
}
