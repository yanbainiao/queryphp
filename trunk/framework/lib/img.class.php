<?php
 /************************************************************
*Create: UUQ(Huang Ziquan)
*Date:   2006-11-13
*图像处理和生成水印等
* upload(); 1.为指定大小;2为生成不大于,有一边等于，75*75小图. 默认为自动大小 3为生成固定大小
*	    $up=new img("File",$uploadpath,"640","480");
		$up->setBasename($newfile);
		$up->setIcopath($uploadpath."_ico/");
		if(!$up->upload(5))
		{
		  header("HTTP/1.0 500 Internal Server Error");
 		}else{
		  echo "success!";
		}
************************************************************/
class img {
    var $upfile;   //上传文件名字
	var $icopic;   //缩放成小图片的名字;
	var $imgpic;   //大图名字
	var $uppath;   //上传图片存放的路径
	var $basename; //基本名字不包括护展名
	var $extfile;  //扩展名
    var $isup;     //是否上传成功
	var $upsize;
	var $icowidth;
	var $icoheight; //
	var $imgwidth;
	var $imgheight; //
	var $shuiyin;  //水印
	var $im;      //原图im
	var $newim;   //临时im
	var $type;    //mime类型 
	var $attr;    //直接显示高和宽
	function __construct()
	{
	  $this->isup=false;
	  $this->shuiyin=false;
	  $this->icopath=''; //小图保存路径
	}
	function setImg($upfile,$upimages,$width,$height,$nzsize=0,$upsize=300000) {

	   
       $this->upfile=$upfile;

	   $this->icowidth=$width;
	   $this->icoheight=$height;
	   $this->nzsize=($nzsize==0)?$width:$nzsize;
	   $this->upsize=$upsize;
	   
	   $this->uppath=$upimages;  //图片保存路径	   
	   $this->init();
	   Return $this;
	}
	/*
	*文件名过滤，把中文转为拼音删除非法字符
	*/
  static public function safeName($filename) {         
		$filename=C("zh2pinyin")->T($filename,true);
		$filename=preg_replace("/[^a-zA-Z0-9._=-]+/","",$filename);
	  Return $filename;
	}
	/*
	*设置生成缩略图大小
	*/
	function setIco($width,$height) {
	   $this->icowidth=$width;
	   $this->icoheight=$height;
	   Return $this;
	}
	/*
	*设置生成水印
	*/
	function setWater($mask=false) {
		$this->shuiyin=$mask;
		Return $this;
	}
	function setIcopath($path)
	{
	  if($path!="")
	  {
	    $this->icopath=$path;
	  }
	  Return $this;
	}
	/*
	*生成固定两边大小的小图
	*/
	function setImgfang($size=75) {
		$this->fangsize=$size;
		Return $this;
	}
	function setIconame($name)
	{
	  if($name!="")
	  {
	    $this->icopic=$name;
	  }
	  Return $this;
	}
	function setBasename($name)
	{
	  if($name!="")
	  {
	    $this->basename=$name;
	  }
	  Return $this;
	}
	function init() {
	    $this->basename=date("Ymdhis").rand(10,99);
		$this->icopic=$this->basename."_ico";
			 //设定类型
       switch($_FILES[$this->upfile]['type'])
	   {
	     case 'image/gif':
               $this->extfile=".gif";
		       $this->isup=true;
		       break;
	     case 'image/png':
               $this->extfile=".png";
		       $this->isup=true;
		       break;
	     case 'image/pjpeg':
         case 'image/jpeg':
			  $this->extfile=".jpg";
              $this->isup=true;
		   break;
		 default:
			 $upfile=pathinfo($_FILES[$this->upfile]["name"]);
		     $ext=array("jpg","gif","png");	
	         $extup=strtolower($upfile['extension']);
	         if(in_array($extup,$ext))
		     {
			  $this->extfile=".".$extup;
              $this->isup=true;
			 }else{
			  $this->isup=false;
		      $this->message="images type error!";
			 }
	   }
	   if($_FILES[$this->upfile]['size']>$this->upsize)
	   {
	     $this->isup=false;
		 $this->message="up images too size !";
	   }
	   if(!$this->isup)
	   {
	      Return false;
	   }	 
	}
	/*
	*
	*上传有几种形式
	* 1 是固定一边
	* 2 全部固定小图
	* 3 生成固定大小缩图的程序;
	* 4 根据比例生成图片大小;
	* 大于5 是不生成缩略图
	* 缺省是自动大小，不大于小图大小
	* 如果指定了文件名，将会替换掉原来的文件
	*/
	function upload($up=1,$updatename="") {
	  if(!$this->isup)
	   {
	      Return false;
	   }
	   if($updatename!="")
	   {
	     $this->basename=$updatename;
		 $this->icopic=$updatename."_ico";
	   }
	  if(!move_uploaded_file($_FILES[$this->upfile]['tmp_name'],$this->uppath.$this->basename.$this->extfile))
	   {
	     $this->isup=false;
		 $this->message="uploaded error!";
		 Return $this->isup;
	   }
	   
	   $this->imgpic=$this->uppath.$this->basename.$this->extfile;
	   if($up>4) Return true;
	   if(file_exists($this->imgpic))
	   {
		   list($this->imgwidth, $this->imgheight,$this->type,$this->attr) = getimagesize($this->imgpic);
 		   switch ($this->type) {
				 case 1:
					$this->im = imagecreatefromgif($this->imgpic); 
				    break;
				 case 2:
					$this->im = imagecreatefromjpeg($this->imgpic); 
					break;
				 case 3:
					$this->im = imagecreatefrompng($this->imgpic); 
					break;
			  }
            switch($up)
		    {
			  case '1': //固定一边大小
			  	$this->Resizenumm();
			    $this->Resizecut();
			  	break;
			  case '2':
				$this->Resizeauto();
			  	break;
			  case '3':
				$this->ResizeIco();
			  	break;
			  case '0':
			  case '4':
				$this->ResizeImage();
			    break;
			}
			/*
			*生成缩小75*75图
			*/
			if(!empty($this->fangsize))
		    {
				$this->Resizefang($this->fangsize);
		        $this->icopic.=".jpg";
			}
			ImageDestroy($this->im);
			$this->isup=true;	
			
	   }else
	   {
	     $this->isup=false;
		 $this->message="file_exists error!";
	   }
       Return $this->isup;
	}
	/*
	自动缩放图片大小
	*/
function ResizeImage(){ 
        if(($this->icowidth && $this->imgwidth > $this->icowidth) || ($this->icoheight && $this->imgheight >$this->icoheight)){ 
        if($this->icowidth && $this->imgwidth > $this->icowidth){ 
            $widthratio = $this->icowidth/$this->imgwidth; 
            $RESIZEWIDTH=true; 
        } 
        if($this->icoheight && $this->imgheight > $this->icoheight){ 
            $heightratio = $this->icoheight/$this->imgheight; 
            $RESIZEHEIGHT=true; 
        } 
        if($RESIZEWIDTH && $RESIZEHEIGHT)
         { 
            if($widthratio < $heightratio)
              { 
                      $ratio = $widthratio; 
              }
          else{ 
               $ratio = $heightratio; 
              } 
        }
      elseif($RESIZEWIDTH)
     { 
         $ratio = $widthratio; 
     }elseif($RESIZEHEIGHT)
     { 
        $ratio = $heightratio; 
     } 
    $newwidth = $this->imgwidth * $ratio; 
    $newheight = $this->imgheight * $ratio; 
	 $newim = imagecreatetruecolor($newwidth, $newheight); //生成真彩色图片
	 imagecopyresampled($newim, $this->im, 0, 0, 0, 0, $newwidth, $newheight, $this->imgwidth,$this->imgheight); 
	 if($this->shuiyin) $this->shuiyin();
	 ImageJpeg ($newim,$this->uppath.$this->icopic.".jpg",100); 
	 ImageDestroy ($newim); 
	}else{ 
	 ImageJpeg ($this->im,$this->uppath.$this->icopic.".jpg",100); 
	} 
} 
/*
* 生成640*640 75*75 
*
*生成缩放到固定一边大不
*/
 function Resizenumm()
 {
   if($this->imgwidth>=$this->imgheight)
   {
     $this->icoheight=ceil(($this->nzsize/$this->imgwidth)*$this->imgheight);
	 $this->icowidth=$this->nzsize;
   }else
   {
     $this->icowidth=ceil(($this->nzsize/$this->imgheight)*$this->imgwidth);
	 $this->icoheight=$this->nzsize;
   }
 }
 /*
 *
 * 生成固定大小的图片
 *
 */
function Resizeauto() {
	$tempsize=75;
	$ridao=1;
	if($this->icowidht>=$this->icoheight)
	{
	   $tempsize=$this->icowidht;
	}else
	{
	   $tempsize=$this->icoheight;
	}
	if($this->imgwidth>=$this->imgheight)
	{
	  $ridao=$this->imgheight/$tempsize;
	}else
	{
	  $ridao=$this->imgwidth/$tempsize;
	}
	$x1=floor(($this->imgwidth-$this->icowidth*$ridao)/2);
	$y1=floor(($this->imgheight-$this->icoheight*$ridao)/2);

	$x2=floor($this->icowidth*$ridao);
	$y2=floor($this->icoheight*$ridao);

    $this->newim = imagecreatetruecolor($this->icowidth,$this->icoheight);
	imagecopyresampled($this->newim,$this->im, 0, 0, $x1, $y1,$this->icowidth,$this->icoheight,$x2,$y2); 
     if($this->shuiyin) $this->shuiyin();
     ImageJpeg($this->newim,$this->uppath.$this->icopic.".jpg",100); 
	 imagedestroy($this->newim);
}

	/**
	*
	*水印，最好用png格式，可以透明
	*
	*$this->shuiyinPngurl 可以在后局设置中设置
	*C("waterimg")是水印类;
	* 没有设置水印在那里，现在保设置在右下角
	*/
	function shuiyin() { 	
			$simage1 =imagecreatefrompng(C("waterimg")->getWaterFile());
			$tempw=$this->icowidth-150;
			if($tempw<0)
			{
			  $tempw=0;
			}
			$temph=$this->icoheight-20;
			if($temph<0)
			{
			  $temph=0;
			}
			imagecopy($this->newim,$simage1,$tempw,$temph,0,0,150,20);
			imagedestroy($simage1);
	 }
/*
* 生成640*640 75*75 
*
*/
 function Resizecut($unim=true){
	$this->newim= imagecreatetruecolor($this->icowidth,$this->icoheight);
	imagecopyresampled($this->newim, $this->im, 0, 0, 0, 0,$this->icowidth,$this->icoheight,$this->imgwidth,$this->imgheight); 
    if($this->shuiyin) $this->shuiyin();
    ImageJpeg($this->newim,$this->uppath.$this->icopic.".jpg",100); 
	if($unim) {
		imagedestroy($this->newim);
	}
  } 
/*
* 生成75*75的正方形小图 
*把大于75的图形从中间切成75*75大小
*/
 function Resizefang($size=75){
  if($this->imgwidth>=$this->imgheight)
  {
    $this->nzsize=$this->imgheight;
  }else
  {
    $this->nzsize=$this->imgwidth;
  }
  if($this->imgwidth>=$this->imgheight)
  {
	 if($this->imgheight>=$this->nzsize)
	 {
	    $x1=floor(($this->imgwidth-$this->nzsize)/2);
		$y1=0;
		$x2=$this->nzsize;
		$y2=$this->nzsize;
	 }else{
	    if($this->imgwidth>=$this->nzsize)
		{
		   $x1=floor(($this->imgwidth-$this->nzsize)/2);
		   $y1=0;
		   $x2=$this->nzsize;
		   $y2=$this->imgheight;
		}else
		{
		   $x1=0;
		   $y1=0;
		   $x2=$this->imgwidth;
		   $y2=$this->imgheight; 
		}
	 }
  }else
  {
     if($this->imgwidth>=$this->nzsize)
	 {
	    $x1=0;
		$y1=floor(($this->imgheight-$this->nzsize)/2);
		$x2=$this->nzsize;
		$y2=$y1+$this->nzsize;
	 }else
	 {
	   if($this->imgheight>=$this->nzsize)
		{
		   $x1=0;
		   $y1=floor(($this->imgwidth-$this->nzsize)/2);
		   $x2=$this->imgwidth;
		   $y2=$y1+$this->nzsize;
		}else
		{
		   $x1=0;
		   $y1=0;
		   $x2=$this->imgwidth;
		   $y2=$this->imgheight; 
		}
	 }
  }
	$newim = imagecreatetruecolor($size,$size);
	imagecopyresampled($newim, $this->im, 0, 0, $x1,$y1,$size,$size,$x2,$y2);

	ImageJpeg($newim,$this->icopath.$this->basename."_".$size.".jpg",100); 
	ImageDestroy($newim); 
 }
/*
*生成固定大小缩图的程序;
*/ 
 function ResizeIco($x=121,$y=97){
  if($x!=121)
  {
    $this->icowidth=$x;
  }
  if($y!=97)
  {
    $this->icoheight=$y;
  }
  $a=$this->imgwidth/$this->icowidth;
  $b=$this->imgheight/$this->icoheight;
  if($a>=$b)
  {
	$kx=floor($this->icowidth*$b);
    $ky=$this->imgheight;
  }else{
    $kx=$this->imgwidth;
	$ky=floor($this->icoheight*$a);
  }
  $x1=floor(($this->imgwidth-$kx)/2);
  $y1=floor(($this->imgheight-$ky)/2);
  $x2=$kx;
  $y2=$ky;
	$newim = imagecreatetruecolor($this->icowidth,$this->icoheight);
	imagecopyresampled($newim,$this->im, 0, 0, $x1,$y1,$this->icowidth,$this->icoheight,$x2,$y2);
	ImageJpeg($newim,$this->icopath.$this->basename."_".$this->icowidth."_".$this->icoheight.".jpg",100); 
	ImageDestroy($newim); 
 } 
}
?>