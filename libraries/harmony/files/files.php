<?php
/**
 * Files singleton class
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

namespace harmony\files;

class Files {
	/**
	 * Get file Extension
	 * @param string $filename File name
	 * @return string File extension
	 */
	public static function getFileExtension($filename) {
		$filename = explode(".", $filename);
		return end($filename);
	}
	
	public static function mkdir($dir) {
		if (!is_dir($dir)) mkdir($dir, 0755, true);
	}
	
	/**
	 * Check file for exists
	 * @param string $name File name
	 * @return bool
	 */
	public static function exists($name) {
		return (is_file($name) || is_dir($name));
	}

	/**
	 * Write content to file
	 * @param string $file File name
	 * @param string $value File content
	 * @return int|false Returns the number of bytes written, or FALSE on error.
	 */
	public static function write($file, $value) {
		$link = fopen($file, "w");
		$write = fwrite($link, $value);
		fclose($link);
		return $write;
	}
	
	/**
	 * Copy file
	 * @param string $src Source file name
	 * @param string $dst To file name
	 * @param bool $move = false Move file
	 * @return array Array of copied files
	 */
	public static function copy($src, $dst, $move = false) {
		$afiles = [];

		// Make dirs
		$dir = is_dir($src) ? $dst : dirname($dst);
		$cdir = false;
		if (!file_exists($dir)) {
			mkdir($dir, 0755, true);
			$cdir = true;
		}

		// Move file
		if (is_dir($src)) {
			if (file_exists($dst) && is_file($dst)) { // Remove file
				unlink($dst);
			}
			
			foreach (scandir($src) as $file) {
				if ($file != "." && $file != "..") {
					$afiles = array_merge($afiles,
						self::copy($src . DS . $file, $dst . DS . $file, $move)
					);
				}
			}
		} else if (file_exists($src)) {
			$move ? rename($src, $dst) : copy($src, $dst);
			$afiles[] = $dst;
		}
		
		if ($cdir) {
			$afiles[] = $dir;
		}

		return $afiles;
	}

	/**
	 * Delete file from upload directory
	 * @param string $file File name
	 * @param bool $rmdir = true Remove directory?
	 * @return bool Is success?
	 */
	public static function delete($file, $rmdir = true) {
		$deleted = [];
		
		if (is_dir($file)) { // Is directory
			foreach (scandir($file) as $f) // Remove files
				if ($f != "." && $f != "..") {
					$deleted = array_merge($deleted, self::delete($file . DS . $f));
				}
			
			// Remove directory
			if ($rmdir) {
				rmdir($file);
				$deleted[] = $file;
			}
		} elseif (is_file($file)) { // Is file
			unlink($file);
			$deleted[] = $file;
		}
		
		return $deleted;
	}
	
	/**
	 * Get formatted filesize from bytes
	 * @param int $byte Size in byte
	 * @param bool $ten Use ten
	 * @return string Formatted file size
	 */
	public static function fileSizeFormat($byte, $ten = false) {
		if ($ten) {
			if ($byte >= 1000000000)
				return round($byte / 1000000000 * 100) / 100 . " GB";
			elseif ($byte >= 1000000)
				return round($byte / 1000000 * 100) / 100 . " MB";
			elseif ($byte >= 1000)
				return round($byte / 1000 * 100) / 100 . " KB";
			else
				return $byte . " B";
		} else {
			if ($byte >= 1073741824)
				return round($byte / 1073741824 * 100) / 100 . " GiB";
			elseif ($byte >= 1048576)
				return round($byte / 1048576 * 100) / 100 . " MiB";
			elseif ($byte >= 1024)
				return round($byte / 1024 * 100) / 100 . " KiB";
			else
				return $byte . " B";
		}
	}

	/**
	 * Get filesize
	 * @param string $file File name
	 * @param bool $format = false Get formatted value
	 * @param bool $ten = false Use ten in format
	 * @return bool|int
	 */
	public static function fileSize($file, $format = false, $ten = false) {
		$size = 0;
		
		if (is_dir($file))
			foreach (glob($file . DS . '*', GLOB_NOSORT) as $each)
				$size += is_file($each) ? filesize($each) : self::fileSize($each);
		else
			$size = filesize($file);
		
		if ($format) $size = self::fileSizeFormat($size, $ten);
		return $size;
	}
}
