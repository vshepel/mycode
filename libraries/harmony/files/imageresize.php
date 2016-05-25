<?php
/**
 * ImageResize class
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

use Exception;

class ImageResize {
	/**
	 * @var resource
	 */
	private $_image;

	/**
	 * @var int
	 */
	private $_type;

	/**
	 * Load file
	 * @param string $file Image file
	 * @throws Exception
	 */
	public function __construct($file = null) {
		if ($file !== null)
			$this->load($file);
	}

	/**
	 * Load image file
	 * @param string $file File
	 * @return $this
	 * @throws Exception
	 */
	public function load($file) {
		$image_info = getimagesize($file);
		$this->_type = $image_info[2];

		if ($this->_type == IMAGETYPE_JPEG)
			$this->_image = imagecreatefromjpeg($file);
		elseif ($this->_type == IMAGETYPE_GIF)
			$this->_image = imagecreatefromgif($file);
		elseif ($this->_type == IMAGETYPE_PNG)
			$this->_image = imagecreatefrompng($file);
		else
			throw new Exception("Can't load image: unsupported format");

		return $this;
	}

	/**
	 * Save image file
	 * @param string $file File
	 * @param int $type Image type
	 * @param int $compression Compression
	 * @param int $permissions Permissions
	 * @return $this
	 * @throws Exception
	 */
	public function save($file, $type = IMAGETYPE_JPEG, $compression = 75, $permissions = null) {
		if ($type == IMAGETYPE_JPEG)
			imagejpeg($this->_image, $file, $compression);
		elseif ($type == IMAGETYPE_GIF)
			imagegif($this->_image, $file);
		elseif ($type == IMAGETYPE_PNG)
			imagepng($this->_image, $file);
		else
			throw new Exception("Can't save image: unsupported format");

		if($permissions != null)
			chmod($file, $permissions);

		return $this;
	}

	/**
	 * Output image
	 * @param int $type Image type
	 * @throws Exception
	 */
	public function output($type = IMAGETYPE_JPEG) {
		if ($type == IMAGETYPE_JPEG)
			imagejpeg($this->_image);
		elseif ($type == IMAGETYPE_GIF)
			imagegif($this->_image);
		elseif ($type == IMAGETYPE_PNG)
			imagepng($this->_image);
		else
			throw new Exception("Can't output image: unsupported format");
	}

	/**
	 * Get image width
	 * @return int
	 */
	public function getWidth() {
		return imagesx($this->_image);
	}

	/**
	 * Get image weight
	 * @return int
	 */
	public function getHeight() {
		return imagesy($this->_image);
	}

	/**
	 * Resize image to height
	 * @param int $height
	 * @return $this
	 */
	public function resizeToHeight($height) {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width, $height);
		return $this;
	}

	/**
	 * Resize image to width
	 * @param int $width
	 * @return $this
	 */
	public function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getHeight() * $ratio;
		$this->resize($width, $height);
		return $this;
	}

	/**
	 * Image scale
	 * @param int $scale Scale
	 * @return $this
	 */
	public function scale($scale) {
		$width = $this->getWidth() * $scale/100;
		$height = $this->getHeight() * $scale/100;
		$this->resize($width, $height);
		return $this;
	}

	/**
	 * Image resize
	 * @param int $width Width
	 * @param int $height Height
	 * @return $this
	 */
	public function resize($width, $height) {
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->_image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->_image = $new_image;
		return $this;
	}
}
