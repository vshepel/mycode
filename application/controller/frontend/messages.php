<?php
/**
 * Messages Frontend Controller
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
use Response;

use model\messages\Messages as MessagesModel;

use harmony\http\HTTP;

class Messages extends AppController {
	public $__default = "list";

	public $__routes = array (
		"(inbox)/page/([0-9]+)" => "list",
		"(inbox)" => "list",
		"(outbox)/page/([0-9]+)" => "list",
		"(outbox)" => "list",
		"send/([A-Za-z0-9._-]+)" => "send",
		"send" => "send",
		"([0-9]+)" => "page",
		"remove/([0-9]+)" => "remove"
	);
	
	private $_messages;
	
	public function __construct() {
		parent::__construct();
		$this->_messages = new MessagesModel();
	}

	public function action_list($args) {
		$type = isset($args[0]) ? $args[0] : "inbox";
		$page = isset($args[1]) ? $args[1] : 1;

		$this->_view->responseRender($this->_messages->listPage($type, $page));
	}
	
	public function action_send($args) {
		if (isset($_POST["user"], $_POST["topic"], $_POST["message"])) {
			$auth = $this->_messages->send($_POST["user"], $_POST["topic"], $_POST["message"]);

			if ($auth->code == 0 && !$this->_ajax)
				HTTP::redirect(SITE_PATH . "messages/outbox");
			else
				$this->_view->alert($auth->type, $auth->message);
		}
		
		$this->_view->responseRender($this->_messages->sendPage(isset($args[0]) ? $args[0] : null));
	}
	
	public function action_page($args) {
		$this->_view->responseRender($this->_messages->page($args[0]));
	}
	
	public function action_notifications($args) {
		$model = $this->_registry->get("Notifications");

		switch($args[0]) {
			case "remove":
				if (isset($_POST["id"])) {
					$model->removeById($_POST["id"], true);
					$this->_view->jsonRender(
						new Response()
					);
				}
			break;

			case "clear":
				$model->remove(null, null, $this->_registry->get("User")->get("id"));
				$this->_view->jsonRender(
					new Response()
				);
			break;

			case "get":
				$this->_view->jsonRender(
					$model->get()
				);
			break;
		}
	}
	
	public function action_remove($args) {
		if (isset($_POST["remove"]["id"])) {
			$result = $this->_messages->remove($_POST["remove"]["id"]);

			if ($result->code == 0)
				HTTP::redirect(SITE_PATH . "messages");
			else
				$this->_view->alert($result->type, $result->message);
		}

		$this->_view->responseRender($this->_messages->removePage($args[0]));
	}
}
