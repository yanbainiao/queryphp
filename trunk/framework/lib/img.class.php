<?php
 /************************************************************
*Create: UUQ(Huang Ziquan)
*Date:   2006-11-13
*图像处理和生成水印等
* upload(); 1.为指定大小;2为生成不大于,有一边等于，75*75小图. 默认为自动大小 3为生成固定大小
	$img=C("img");
	$img->setInfo(
		  array("files"=>"upload",
		        "uploadpath"=>$GLOBALS['config']['webprojectpath']."upimages/",
		        "icopath"=>$GLOBALS['config']['webprojectpath']."upimages/",		        
		        "icowidth"=>"128",
		        "icoheight"=>"98",
		        "fangpath"=>$GLOBALS['config']['webprojectpath']."upimages/_ico/",
		        "fangsize"=>"75",
		        "nzsize"=>"180",
		        "uploadsize"=>320000
	            )
	      )->setBasename($_FILES['upload']['name'],true)->init();
	if($img->upload(1))
	{
	  echo("上传成功");
	}else{
	  echo("上传失败");
	  echo $img->message;
	}
	'fill_size' 提定为小图大小
	size_ico    提供指定大小
	auto_ico    自动缩放不大于ico
	fix_ico     提定大小
	fix_side    固定一边到nzsize大小

************************************************************/
class img {
    var $upfile;   //上传文件名字
	var $icopic;   //缩放成小图片的名字;
	var $imgpic;   //大图名字
	var $uploadpath;   //上传图片存放的路径
	var $basename; //基本名字不包括护展名
	var $extfile;  //扩展名
    var $isup;     //是否上传成功
	var $uploadsize;
	var $icowidth;
	var $icoheight; //
	var $imgwidth;
	var $imgheight; //
	var $shuiyin;  //水印
	var $im;      //原图im
	var $newim;   //临时im
	var $type;    //mime类型 
	var $attr;    //直接显示高和宽
	var $info=array();
	function __construct()
	{
	  $this->isup=false;
	  $this->shuiyin=false;
	  $this->icopath=''; //小图保存路径
	}
	function setFiles($upfile){
		$this->info['name']=$this->safeName($_FILES[$upfile]['name']);
		$this->info['tmp_name']=$_FILES[$upfile]['tmp_name'];
		$this->info['type']=$_FILES[$upfile]['type'];
		$this->info['size']=$_FILES[$upfile]['size'];
		$this->info['error']=$_FILES[$upfile]['error'];
		Return $this;
	}
	/*
	*设置图片上传信息
	*icowidth 小图信息
	*icoheight 

	*fangpath 方形图信息
	*fangsize  

	*uploadpath 上传目录
	*files       上传input名
	*/
	function setInfo($info=array()) {
	  if(isset($info['uploadpath']))
	  $this->uploadpath=$info['uploadpath'];
	  if(isset($info['icowidth']))
	  $this->icowidth=$info['icowidth'];
	  if(isset($info['icoheight']))
	  $this->icoheight=$info['icoheight'];

	  $this->icopath=isset($info['icopath'])?$info['icopath']:$this->uploadpath;
	  $this->fangpath=isset($info['fangpath'])?$info['fangpath']:$this->uploadpath;
	  $this->fangsize=isset($info['fangsize'])?$info['fangsize']:75;



	  if(isset($info['files']))
	  {
	   	$this->setFiles($info['files']);

	  }else{
	    	$this->isup=false;
		   $this->message="no upimages from !";
	  }
	  if(isset($info['uploadsize']))
	  {
	   	$this->uploadsize=$info['uploadsize'];
		if($this->info['size']>$this->uploadsize)
		{
		   $this->isup=false;
		   $this->message="up images too size !";
		}

	  }
	   	$this->nzsize=isset($info['nzsize'])?$info['nzsize']:$this->icowidth;
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
	function setFangpath($path)
	{
	  if($path!="")
	  {
	    $this->fangpath=$path;
	  }
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
	function setBasename($name,$fix=false)
	{
	  if($name!="")
	  {
		if($fix)
		{
			$upfile=pathinfo($this->safeName($name));
			$name=basename($name,".".$upfile["extension"]);
		}
	    $this->basename=$name;
		$this->icopic=$name."_ico";
	  }
	  Return $this;
	}
	function init() {
	    if(empty($this->basename))
		{
		 $this->basename=date("Ymdhis").rand(10,99);
		 $this->icopic=$this->basename."_ico";
	    }
			 //设定类型
       switch($this->info['type'])
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
			  $this->isup=false;
		      $this->message="images type error!";
	   }
	   if($this->isup&&$this->info['size']>$this->uploadsize)
	   {
	     $this->isup=false;
		 $this->message="up images too size !";
	   }
	   Return $this->isup;

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
	function upload($up=array(),$updatename="") {
	  if(!$this->isup)
	   { 
		  echo('aa');
	      Return false;
	   }
	   if(empty($this->extfile)||in_array($this->extfile,array('gif','jpg','png')))
	   {
	     $this->init();
	   }
	   if($updatename!="")
	   {
	     $this->basename=$updatename;
		 $this->icopic=$updatename."_ico";
	   }
	  if(!move_uploaded_file($this->info['tmp_name'],$this->uploadpath.$this->basename.$this->extfile))
	   {
	     $this->isup=false;
		 $this->message="uploaded error!";
		 Return $this->isup;
	   }
	   
	   $this->imgpic=$this->uploadpath.$this->basename.$this->extfile;
	   if(empty($up)) Return true;
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
			//如果设置了水印，那么缩小和放大都有水印
			if(isset($up['water'])) $this->shuiyin=true;
			foreach($up as $cutimg)
		    {			
				switch($cutimg)
				{
				  case 'fix_side': //固定一边大小
					$this->Resizenumm();					
					break;
				  case 'size_ico':
					$this->Resizeauto();
					break;
				  case 'fix_ico':
					$this->ResizeIco();
					break;
				  case 'fill_size':
					  $this->Resizecut();
				      break;
				  case 'auto_ico':
					$this->ResizeImage();
					break;
				 }
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
	 ImageJpeg ($newim,$this->uploadpath.$this->icopic.".jpg",100); 
	 ImageDestroy ($newim); 
	}else{ 
	 ImageJpeg ($this->im,$this->uploadpath.$this->icopic.".jpg",100); 
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
    $this->newim = imagecreatetruecolor($this->icowidth,$this->icoheight);
	imagecopyresampled($this->newim,$this->im, 0, 0, 0,0,$this->icowidth,$this->icoheight,$this->imgwidth,$this->imgheight); 
     if($this->shuiyin) $this->shuiyin();
     ImageJpeg($this->newim,$this->uploadpath.$this->icopic.".jpg",100); 
	 imagedestroy($this->newim);
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
     ImageJpeg($this->newim,$this->uploadpath.$this->icopic.".jpg",100); 
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
    ImageJpeg($this->newim,$this->uploadpath.$this->icopic.".jpg",100); 
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

	ImageJpeg($newim,$this->fangpath.$this->basename."_".$size.".jpg",100); 
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