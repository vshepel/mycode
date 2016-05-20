<?php
/**
 * HarmonyCMS Index
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

/**
 * Gen time start
 */
$start_time = microtime(true);

header('Content-Type: text/html; charset=utf-8');
define("VERSION", "1.0.0");

$args = array (
    "debug" => true
);

require_once("../core/bootstrap.php");
new Bootstrap($args);

// GEN TIME END
if (!defined("AJAX") && defined("DEBUG") && DEBUG) {
	echo(PHP_EOL . "<!-- DEBUG MODE ENABLED! -->");
	echo(PHP_EOL . "<!-- PEAK MEMORY USAGE: " . round(memory_get_peak_usage()/1024, 2) . " KiB -->");
	
	// GENERATION TIME
	echo(PHP_EOL . "<!-- GEN TIME: " . round(((microtime(true) - $start_time) * 1000), 2) . " ms -->");
}
