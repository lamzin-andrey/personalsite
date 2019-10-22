<?php
namespace Landlib;

class Text2Png {
	
	/** @property strings _sPathToTtfFile - path to the TTF file with fonts */
	private $_sPathToTtfFile;
	
	/** @property int _nFontSize */
	private $_nFontSize = 14;
	
	/** @property strings _sBackgroundImagePath - path to the png background image */
	private $_sBackgroundImagePath;
	
	/** @property array _aFontColor [r:int, g:int, b:int] each int from 0 to 255 */
	private $_aFontColor = [0, 0, 0];
	
	/** @property int _nPaddingLeft */
	private $_nPaddingLeft = 30;
	
	/** @property int _nPaddingTop */
	private $_nPaddingTop = 30;
	
	/** @property string _sText */
	private $_sText;
	
	/** @property string  */
	//private $ = ;

	/**
	 * @description Set text font, size, background image, font color,  text padding left, text padding top
	 * @param string $sText
	 * @param string $sPathToTtfFile = ''
	 * @param int $nFontSize = 14
	 * @param string $sBackgroundImagePath = ''
	 * @param array $aFontColor = [0, 0, 0]
	 * @param int $nPaddingLeft = 30
	 * @param int $nPaddingTop = 30
	 * @throw Exception "Font file not found"
	 * @throw Exception "Background image file not found"
	 * @throw Exception "Background image is not png file"
	*/
	public function __construct(string $sText, string $sPathToTtfFile = '', int $nFontSize = 14, string $sBackgroundImagePath = '', array $aFontColor = [0, 0, 0], int $nPaddingLeft = 30, int $nPaddingTop = 30)
	{
		$this->_nFontSize = $nFontSize;
		$this->_aFontColor = $aFontColor;
		$this->_nPaddingTop = $nPaddingTop;
		$this->_nPaddingLeft = $nPaddingLeft;
		$this->_sText = $sText;
		
		if (!$sPathToTtfFile) {
			$sPathToTtfFile = __DIR__ . '/../assets/font4.ttf';
		}
		$this->setFont($sPathToTtfFile);
		
		if (!$sBackgroundImagePath) {
			$sBackgroundImagePath = __DIR__ . '/../assets/blank.png';
		}
		$this->setBgImage($sBackgroundImagePath);
	}
	/**
	 * @description set text font color
	 * @param array _aFontColor [r:int, g:int, b:int] each int from 0 to 255
	*/
	public function setFontColor(array $aFontColor) : void
	{
		$this->_aFontColor = $aFontColor;
	}
	/**
	 * @description set text
	 * @param string $s
	*/
	public function setText(string $s) : void
	{
		$this->_sText = $s;
	}
	/**
	 * @description set text font size
	 * @param int $n
	*/
	public function setFontSize(int $n) : void
	{
		$this->_nFontSize = $n;
	}
	/**
	 * @description set text padding left
	 * @param int $n
	*/
	public function setPaddingLeft(int $n) : void
	{
		$this->_nPaddingLeft = $n;
	}
	/**
	 * @description set text padding top
	 * @param int $n
	*/
	public function setPaddingTop(int $n) : void
	{
		$this->_nPaddingTop = $n;
	}
	/**
	 * @description Output in to the stream png image with http header image/png
	*/
	public function pngResponse()
	{
		$im = imagecreatefrompng( $this->_sBackgroundImagePath);
		$aFontColor = imagecolorresolve($im, $this->_aFontColor[0], $this->_aFontColor[1], $this->_aFontColor[2]);
		
		imagettftext($im, $this->_nFontSize, 0, $this->_nPaddingLeft, $this->_nPaddingTop, $aFontColor, $this->_sPathToTtfFile, $this->_sText);
		header('Cache-Control: no-store, no-cache, must-revalidate'); 
		header('Cache-Control: post-check=0, pre-check=0', FALSE); 
		header('Pragma: no-cache');  
		
		header('Content-Type: image/png');
		imagepng($im);
	}
	/**
	 * @description Save the png image
	*/
	public function save(string $sPath) : bool
	{
		$im = imagecreatefrompng( $this->_sBackgroundImagePath);
		$aFontColor = imagecolorresolve($im, $this->_aFontColor[0], $this->_aFontColor[1], $this->_aFontColor[2]);
		
		imagettftext($im, $this->_nFontSize, 0, $this->_nPaddingLeft, $this->_nPaddingTop, $aFontColor, $this->_sPathToTtfFile, $this->_sText);
		return imagepng($im, $sPath);
	}
	/**
	 * @description Set background image
	 * @param string $sBackgroundImagePath
	 * @throw Exception "Background image file not found"
	 * @throw Exception "Background image is not png file"
	*/
	private function setBgImage(string $sBackgroundImagePath) : void
	{
		if (file_exists($sBackgroundImagePath)) {
			$aData = getImageSize($sBackgroundImagePath);
			$sMime = ($aData['mime'] ?? '');
			if ($sMime != 'image/png') {
				throw new \Exception('"' . $sBackgroundImagePath . '" is not png file');
			}
			$this->_sBackgroundImagePath = $sBackgroundImagePath;
		} else {
			throw new \Exception('Background image file "' . $sBackgroundImagePath . '" not found');
		}
	}
	/**
	 * @description Set ttf font file path
	 * @param string $sBackgroundImagePath
	 * @throw Exception "Font file not found"
	*/
	private function setFont(string $sPathToTtfFile) : void
	{
		if (file_exists($sPathToTtfFile)) {
			$this->_sPathToTtfFile = $sPathToTtfFile;
		} else {
			throw new \Exception('File "' . $sPathToTtfFile . '" not found');
		}
	}
}
