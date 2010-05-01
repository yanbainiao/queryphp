<?php
/*
*下载文件输出头部信息
*防止乱码
*$download->setInfo(array()参数)->begin()->output([文件名可选]);
*$download->setInfo("../upload/banner.swf")->begin()->output();
*$download->setInfo("../upload/中文文件名.swf")->begin()->output();
*$download->setInfo(array('filename'=>$filename,
	               'mimetype'=>$mimetype,
				   'length'=>$legnth,
				   'filepath'=>$filepath))->begin()->output();
*/
class download {
	public var $filename;
	public var $mimetype; //文件编码
	public var $length;   //文件长度可以使用filename得到
	public var $browser;
	public var $filepath;
	public function __constructs()
	{
	   $this->browser=$_SERVER["HTTP_USER_AGENT"];
	}
	/***
	*设置文件
	*setInfo("文件名",mime类型,长度或路径,下载文件后(读取时候加多后辍))
	*setInfo($path.$filename);
	*setInfo($filename,'application/msword');
	*setInfo($filename,'application/msword',$length);
	*setInfo($filename,'application/msword','../upload');
	*setInfo($filename,'application/msword','../upload',$fix='ext');
	*setInfo(array('filename'=>$filename,
	               'mimetype'=>$mimetype,
				   'length'=>$legnth,
				   'filepath'=>$filepath))
	***/
	public function setInfo($file,$mime='',$length='',$fix='') {
		if(is_array($file))
		{
          foreach($file as $key=>$value)
		  {
		    $this->{$key}=$value;
		  }
		}else{
		 	$this->filename=$file.$fix;
			if($this->filepath=='')
			{
			 $this->filepath='.';
			}
			if($mime!='')
            {
			  $this->mimetype=$mime;
			}else{
			  $path_parts=pathinfo($file);
			  $this->filepath=$path_parts["dirname"];
			  $this->filename=$path_parts["basename"];
			  $this->mimetype=$this->getMime(strtolower($path_parts["basename"]));
			}
			if(is_numeric($length))
			{
			 $this->length=$length;
			}elseif($length!=''){
			 $this->length=filesize($length."/".$file);
			 $this->filepath=$length;
			}else{
			  $this->length=filesize($this->filepath."/".$this->filename); 
			}
		}
		Return $this;
	}
	public function setPath($filepath) {
		$this->filepath=$filepath;
		Return $this;
	}
	public function setMime($mime) {
		$this->mimetype=$mime;
		Return $this;
	}
	public function setFile($filename) {
		$this->filename=$filename;
		Return $this;
	}
	public function setLength($length) {
		$this->length=$length;
		Return $this;
	}
	public function begin() {
	    header('Content-Type: '.$mimetype.'; charset=utf-8');
		$encoded_filename = urlencode($this->filename);
		$encoded_filename = str_replace("+", "%20", $encoded_filename);
		if (preg_match("/MSIE/",$this->browser)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
		} else if (preg_match("/Firefox/",$this->browser)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '"');
		} else {
			header('Content-Disposition: attachment; filename="' . $filename . '"');
		}
       header("Content-Length: ".$this->length);
	}
	public function output($filename='') {
		if($filename='')
		{
		  readfile($filename);
		}else{
		  readfile($this->filepath."/".$this->filename);
		}
	}
	public function getMime($ext) {
		$filetype=array();
		$filetype['bin']="application/octet-stream";
		$filetype['pdf']="application/pdf";
		$filetype['gif']="image/gif";
		$filetype['jpeg']="image/pjpeg";
		$filetype['jpg']="image/pjpeg";
		$filetype['jpe']="image/pjpeg";
		$filetype['png']="image/png";
        $filetype['tar']="application/x-tar";
		$filetype['zip']="application/zip";
		$filetype['txt']="text/plain";
		$filetype['html']="text/html";
		$filetype['htm']="text/html";
		$filetype['mpeg']="video/mpeg";
		$filetype['mpg']="video/mpeg";
		$filetype['mpe']="video/mpeg";
		$filetype['avi']="video/x-msvideo";
		$filetype['swf']="application/x-shockwave-flash";
		$filetype['doc']="application/msword";
		$filetype['xls']="application/vnd.ms-excel";
		$filetype['ppt']="application/vnd.ms-powerpoint";
		if(isset($filetype[$ext]))
		{
		  $this->mimetype=$filetype[$ext];
		}else{
		  $this->mimetype=$filetype['bin'];
		}
		Return $this->mimetype;
	}
} 
?>