<?php

/**
 * @author  Martin Okorodudu <webmaster@fotocrib.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 */

/**
 * This class is a wrapper for the fototools service.
 */

class Fotocrib {

	private $_imgSrc;

	private $_fileName;
	
	private $_format;

	private $_arrErrorMessages = array(
								"file"       => "File name must be set",
								"source"  => "Image source must be a URL",
								"format"  => "Unsupported image format. Only gif, jpg and png are supported",
								"params" => "Insufficient parameters given to make request" 
								);

	private $_arrSupportedFormats = array("jpg", "png", "gif");


	public function __construct($imgSrc, $fileName, $format) {
		
		if ($this->isValidImageSource($imgSrc) && $this->isValidFileName($fileName) && $this->isValidFormat($format)) {
			$this->_imgSrc = $imgSrc;			
			$this->_fileName = $fileName;
			$this->_format = $format;
		}
	}

	
	/**
	 * Thumbnails an image
	 */
	public function thumbnail() {
		$this->handleRequest(array("s" => $this->_imgSrc, "q" => "thumbnail"));
	}	


	/**
	 * Overlays text on the image in predefined locations
	 *
	 * @param string $text The text to be overlayed on the image
	 * @param string $location One of Center, South, East, West, North, NorthEast, NorthWest, SouthEast, SouthWest
 	 */
	public function label($text, $location) {
		$arrLocations = array("Center", "South", "East", "West", "North", "NorthEast", "NorthWest", "SouthEast", "SouthWest");		
		if (in_array($location, $arrLocations)) {		
			$this->handleRequest(array("s" => $this->_imgSrc, "q" => "label", "t" => $text, "l" => $location));
		} else {
			throw new Exception("Unsupported location supplied to label function");
		}
	}


	/**
	  * Rounds corners
	  *
	  * @param int $radius The radius of the curve.
 	  */
	public function roundCorners($radius) {
		if (is_numeric($radius)) {		
			$this->handleRequest(array("s" => $this->_imgSrc, "q" => "round", "r" => $radius));
		}
	}


	/**
	 * Transforms an image into a cube
	 *
	 * @param int $r Represents the red value of the background color, 0 <= $r <= 255
	 * @param int $g Represents the green value of the background color, 0 <= $g <= 255
	 * @param int $b Represents the blue value of the background color, 0 <= $b <= 255
	 */
	public function cube($r, $g, $b) {
		if (is_numeric($r) && is_numeric($g) && is_numeric($b)) {	
			$this->handleRequest(array("s" => $this->_imgSrc, "q" => "cube", "r" => $r, "g" => $g, "b" => $b));
		} else {
			throw new Exception($this->numericErrorMessage("cube"));
		}
	}	


	/**
	 * Mimics the effect of raising an image
	 *
	 * @param int $height The height displacement
	 */
	public function raise($height) {
		if (is_numeric($height)) {		
			$this->handleRequest(array("s" => $this->_imgSrc, "q" => "raise", "h" => $height));
		} else {
			throw new Exception($this->numericErrorMessage("raise"));
		}
	}


	/**
 	 * Scales an image.
 	 *
 	 * @param integer $pct The percentage to be scaled to.
 	 */
	public function scale($pct) {
		if (is_numeric($pct)) {	
			$this->handleRequest(array("s" => $this->_imgSrc, "q" => "scale", "p" => $pct));
		} else {
			throw new Exception($this->numericErrorMessage("scale"));
		}
	}


	/**
 	 * Resizes an image.
 	 *
 	 * @param integer $width The desired width.
 	 * @param integer $height The desired height.
 	 */
	public function resize($width, $height) {
		if (is_numeric($width) && is_numeric($height)) {	
			$this->handleRequest(array("s" => $this->_imgSrc, "q" => "resize", "w" => $width, "h" => $height));
		} else {
			throw new Exception($this->numericErrorMessage("resize"));
		}
	}

	
	/**
 	 * Places focus on the center of the image.
	 */
	public function focus() {
		$this->handleRequest(array("s" => $this->_imgSrc, "q" => "focus"));
	}


	/**
	 * Embosses the image.
	 */
	public function emboss() {
		$this->handleRequest(array("s" => $this->_imgSrc, "q" => "emboss"));
	}


	/**
	 * Transforms the image into an oil painting.
	 */
	public function paint() {
		$this->handleRequest(array("s" => $this->_imgSrc, "q" => "paint"));
	}	

	
	/**
 	 * Repaints the image with a given color.
 	 *
 	 * @param Integer $r The red value of the color, 0 <= $r <= 255.
 	 * @param Integer $g The green value of the color, 0 <= $g <= 255.
 	 * @param Integer $b The blue value of the color, 0 <= $b <= 255.
 	 */
	public function repaint($r, $g, $b) {
		if (is_numeric($r) && is_numeric($g) && is_numeric($b)) {		
			$this->handleRequest(array("s" => $this->_imgSrc, "q" => "repaint", "r" => $r, "g" => $g, "b" => $b));
		} else {
			throw new Exception($this->numericErrorMessage("repaint"));
		}
	}


	/**
 	 * Creates a frame around an image.
 	 *
 	 * @param integer $thickness The thickness of the frame in pixels.
 	 * @param Integer $r The red value of the frame color, 0 <= $r <= 255.
 	 * @param Integer $g The green value of the frame color, 0 <= $g <= 255.
 	 * @param Integer $b The blue value of the frame color, 0 <= $b <= 255.
 	 */
	public function frame($thickness, $r, $g, $b) {
		if (is_numeric($r) && is_numeric($g) && is_numeric($b) && is_numeric($thickness)) {		
			$this->handleRequest(array(
								"s" => $this->_imgSrc, 
								"q" => "frame", 
								"r" => $r, 
								"g" => $g, 
								"b" => $b, 
								"t"  => $thickness
								)
							);
		} else {
			throw new Exception($this->numericErrorMessage("frame"));
		}
	}


	/**
 	 * Creates rounded frames.
 	 * @param integer $thickness The thickness of the frame. 
 	 * @param integer $radius The radius of the curve.
 	 * @param Integer $r The red value of the bgcolor, 0 <= $r <= 255.
 	 * @param Integer $g The green value of the bgcolor, 0 <= $g <= 255.
 	 * @param Integer $b The blue value of the bgcolor, 0 <= $b <= 255.
 	 */
	public function roundFrame($thickness, $radius, $r, $g, $b) {
		if (is_numeric($r) && is_numeric($g) && is_numeric($b) && is_numeric($thickness) && is_numeric($radius)) {		
			$this->handleRequest(array(
								"s" => $this->_imgSrc, 
								"q" => "rframe", 
								"r"  => $r, 
								"g" => $g, 
								"b" => $b, 
								"t"  => $thickness,
								"v" => $radius
								)
							);
		} else {
			throw new Exception($this->numericErrorMessage("roundFrame"));
		}
	}


	/**
	 * Mirrors the image.
	 */
	public function mirror() {
		$this->handleRequest(array("s" => $this->_imgSrc, "q" => "mirror"));
	}


	/**
	 * Converts the image to grayscale.
	 */
	public function grayscale() {
		$this->handleRequest(array("s" => $this->_imgSrc, "q" => "grayscale"));
	}


	/**
	 * Blurs the image.
	 */
	public function blur() {
		$this->handleRequest(array("s" => $this->_imgSrc, "q" => "blur"));
	}


	/**
	 * Brightens the image.
	 */
	public function brighten() {
		$this->handleRequest(array("s" => $this->_imgSrc, "q" => "brighten"));
	}


	/**
	 * Passes the image through a sobel filter.
	 */
	public function sobel() {
		$this->handleRequest(array("s" => $this->_imgSrc, "q" => "sobel"));
	}


	private function isValidImageSource($imgSrc) {
		if (!eregi("^http://", $imgSrc)) {
			throw new Exception($this->_arrErrorMessages["source"]);
		}
		
		return true;
	}	


	private function isValidFileName($fileName) {
		if (!is_string($fileName) || strlen($fileName) == 0) {
			throw new Exception($this->_arrErrorMessages["file"]);
		}
		
		return true;
	}


	private function isValidFormat($format) {
		if (!in_array($format, $this->_arrSupportedFormats)) {
			throw new Exception($this->_arrErrorMessages["format"]);
		}
		
		return true;
	}


	public function setFileName($fileName) {
		if ($this->isValidFileName($fileName)) {		
			$this->_fileName = $fileName;
		}
	}


	public function getFileName() {
		return $this->_fileName;
	}


	public function setFormat($format) {
		if ($this->isValidFormat($format)) {	
			$this->_format = $format;
		}
	}


	public function getFormat() {
		return $this->_format;
	}


	public function setImgSrc($imgSrc) {
		if ($this->isValidImageSource($imgSrc)) {	
			$this->_imgSrc = $imgSrc;
		}
	}


	public function getImgSrc() {
		return $this->_imgSrc;
	}	


	/**
 	 * Creates an image resource from a url or path to a file of type jpg, gif or png.
 	 * 
 	 * @param string $imgUrl URL or pathname to the image file
 	 * @return image resource
 	 */
	public function myImageCreate($imgUrl) {
		if (eregi(".jpg", $imgUrl) > 0) {
			return imagecreatefromjpeg($imgUrl);
		}
		else if (eregi(".png", $imgUrl) > 0) {
			return imagecreatefrompng($imgUrl);
		}
		else if (eregi(".gif", $imgUrl) > 0) {
			return imagecreatefromgif($imgUrl);
		}
	}


	/**
 	 * Saves an image resource to a file.
 	 * 
 	 * @param resource $img The image to be saved.
 	 * @param string $img_url pathname to the destination file.
 	 * @return true iff save was successful
 	 */
	public function myImageSave($img, $fileName) {
		if (eregi(".jpg$", $fileName) > 0) {
			return imagejpeg($img, $fileName);
		}
		else if (eregi(".png$", $fileName) > 0) {
			return imagepng($img, $fileName);
		}
		else if (eregi(".gif$", $fileName) > 0) {
			return imagegif($img, $fileName);
		}
	}


	private function numericErrorMessage($functionName) {
		return "Non numeric arguments supplied to {$functionName}";
	}
	

	private function handleRequest($params) {
		$url = "http://fotocrib.com/fototools.php?";

		if (is_array($params)) {
			
			foreach ($params as $k => $v) {
				$url .= "{$k}={$v}&"; 
			}
			
			$url = substr($url, 0, -1);
			$this->myImageSave($this->myImageCreate($url), "{$this->_fileName}.{$this->_format}");
		
		} else {
			throw new Exception($this->_arrErrorMessages["params"]);
		}
	}
}

?>
