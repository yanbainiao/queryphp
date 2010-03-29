<?php
/**
*水印处理类
*C("waterimg")->createWaterPng("水印开始");
*****/
class waterimg {
	public $bgcolor;
	public $waterpath;
	public $width;
	public $height;
	public $waterfilename;

	function __construct($path="",$waterfilname='site_water.png',$width=120,$height=25) {
	  $this->waterpath=$path?$path:P("frameworkpath")."config/";
	  $this->waterfilename=	$waterfilname;
	  $this->width=$width;
	  $this->height=$height;
	  $this->bgcolor='#F0F0F0';
	}
	function setBgcolor($color) {
		$this->bgcolor=$color;
		Return $this;
	}
	/*
	*上传保存水印图片
	*/
	function saveWaterFile($file) {
		Return move_uploaded_file($_FILES[$file]["tmp_name"],$this->getWaterFile());
	}
	/*
	*复制图片文件保存水印图片
	*/
	function copyWaterFile($file) {
		$ext=pathinfo($file,PATHINFO_EXTENSION);
		if(in_array($ext,array("gif","jpg","png")))
		{
		  Return copy($file,$this->getWaterFile());
		}else
			Return false;
	}
	/*
	*取得水印文件名
	*返回路径
	*
	*/
	function getWaterFile() {	  
	  return $this->waterpath.$this->waterfilename;
	}
	/*
	*设置水印路径，主要是保存
	*
	*
	*/
	function setWaterPath($path) {
		$this->waterpath=$path;
	}
	/*
	*把webcolor颜色转为R G B;
	*比如从js选择颜色中得到$rgb='#996633';
	*/
	function webcolorTorgb($rgb) {
		//$rgb='#996633';
		$rgb=hexdec(str_replace("#",'',$rgb));
		$color=array();
		$color['red']  = ($rgb >> 16) & 0xFF;
        $color['green'] = ($rgb >> 8)  & 0xFF;
        $color['blue']  = $rgb & 0xFF;
       Return $color;
	}
	/*
	*创建水印文字
	*设置字体
	*设置颜色
	*/
	function createWaterPng($text) {
		$im = imagecreatetruecolor($this->width,$this->height);
		$white = imagecolorallocate($im, 255, 255, 255);
        $rgb=$this->webcolorTorgb($this->bgcolor);

		$brown = imagecolorallocate($im, $rgb['red'], $rgb['green'], $rgb['blue']);
		imagefilledrectangle($im, 0, 0, $this->width, $this->height, $white);
		ImageColorTransparent($im,$white);
		$font = $this->waterpath.'waterfont.TTF';
		imagettftext($im, 16, 0, 10, 20, $brown, $font, $text);
		imagepng($im,$this->getWaterFile());
		imagedestroy($im);
	}
}
?>