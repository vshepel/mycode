<?php
/**
 * Sitemap Model
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

namespace model\sitemap;

use AppModel;

class Sitemap extends AppModel {
	public function getSitemapUrls() {
		$urls = [];

		foreach (scandir(CON . DS . FRONTEND) as $name) {
			if (is_file(CON . DS . FRONTEND . DS . $name)) {
				$name = str_replace(".php", "", $name);
				$controller_name = "\\controller\\frontend\\{$name}";
				$controller = new $controller_name;
				$urls = array_merge($urls, $controller->getUrls());
			}
		}

		return $urls;
	}

	public function getSitemapXml() {
		$cache_name = date("Y-m-d");
		$cache = $this->_cache->get("sitemap", $cache_name);

		if ($cache === false) {
			$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

			foreach ($this->getSitemapUrls() as $url) {
				$xml .= "	<url>\n";

				foreach ($url as $prop => $value) {
					$xml .= "		<{$prop}>{$value}</{$prop}>\n";
				}

				$xml .= "	</url>\n";
			}

			$xml .= "</urlset>\n";

			$this->_cache->remove("sitemap");
			$this->_cache->push("sitemap", $cache_name, $xml);
			return $xml;
		} else {
			return $cache;
		}
	}
}