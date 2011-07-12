<?php
/*
*画网状表格
*$bgtype是控制阴影的 0 表示没有 3 4 表示竖的阴影
*/
class pGridTable {
   	var $posx1;
	var $posy1;
	var $posx2;
	var $posy2;
	var $rownum;
	var $colnum;
	var $pos=array();
	var $postxt=array();
	var $r=0;
	var $g=0;
	var $b=0;
	var $bytype=0;
	var $br=0;
	var $bg=0;
	var $bb=0;
	public $total=0;
	public $fontpath;
	public $Hdotte=true;
	public $Vdotte=true;
	/*
	* $rownum 多少行
	* $colnum 多少列
	* bgtype 是否画表格阴影
	*/
	public function __construct($x1=0,$y1=0,$x2=0,$y2=0,$rownum=1,$colnum=1,$total=1,$R=0,$G=0,$B=0,$bgtype=0,$br=0,$bg=0,$bb=0) {
		$this->posx1=$x1;
		$this->posy1=$y1;

		$this->posx2=$x2;
		$this->posy2=$y2;

		$this->r=$R;
		$this->g=$G;
		$this->b=$B;

		$this->bytype=$bgtype;
		$this->br=$br;
		$this->bg=$bg;
		$this->bb=$bb;
        $this->total=$total;
		$this->rownum=$rownum;
		$this->colnum=$colnum;//设置为10因为是百分比
		//处理每个TD宽高
		$xc=$x2-$x1;
		if($xc<1) $xc=1;
	    if($colnum==0) $colnum=1;
		$pxtd=ceil($xc/$colnum);
        
		$yc=$y2-$y1;
		if($yc<1) $yc=1;
	    if($rownum==0) $rownum=1;
        $pytd=ceil($yc/$rownum);
         $i=0;
		 $j=0;
		//初始td坐标
		for($i=0;$i<$rownum;$i++)
        {
		  	for($j=0;$j<$colnum;$j++)
			{
			  $this->pos[$i][$j][0]=$pxtd;
			  $this->pos[$i][$j][1]=$pytd;
			}
		}
		Return $this;
	}
	//是否显示横的虚线
	public function setHdotte($v) {
		$this->Hdotte=$v;
	    Return $this;
	}
	//是否显示竖的虚线
	public function setVdotte($v) {
		$this->Vdotte=$v;
	    Return $this;
	}
	/*
	*画表格	
	*/
	public function drawTable($p) {
	  $p->drawRectangle($this->posx1,$this->posy1,$this->posx2,$this->posy2+4,$this->r,$this->g,$this->b);

     /* Horizontal lines */
	 //先画一个阴影，以免被覆盖
     $XPos=$this->posx1;
	 if($this->bytype==3||$this->bytype==4)
		{
			 for($i=0;$i<$this->colnum;$i++)
			 {
				 $t=$XPos;
				 $XPos=$t+$this->pos[0][$i][0];
						   //画TD阴影
				   if($this->bytype==3)
				   {
					if($i%2==0)
						$p->drawFilledRectangle($t+1,$this->posy1+1,$XPos-1,$this->posy2,$this->br,$this->bg,$this->bb,FALSE,100); 
				   }elseif($this->bytype==4)
				   {
					 if($i%2==1)
						$p->drawFilledRectangle($t+1,$this->posy1+1,$XPos-1,$this->posy2,$this->br,$this->bg,$this->bb,FALSE,100);
				   }
			 }
		}
     $YPos=$this->posy1;
     for($i=0;$i<$this->rownum;$i++)
	 {
		$t=$YPos;
		$YPos=$this->pos[$i][0][1]+$t;
		 	       //画TD阴影
		if($this->bytype==1)
		{
		  if($i%2==0)
			$p->drawFilledRectangle($this->posx1+1,$t+1,$this->posx2-1,$YPos-1,$this->br,$this->bg,$this->bb,FALSE,100); 
		}elseif($this->bytype==2)
		{
		 if($i%2==1)
			$p->drawFilledRectangle($this->posx1+1,$t+1,$this->posx2-1,$YPos-1,$this->br,$this->bg,$this->bb,FALSE,100);
		}
		if ( $YPos > $this->posy1 && $YPos <$this->posy2 )
		{
		  //横的阴影线
		  if($this->Hdotte)
		  $p->drawDottedLine($this->posx1-5,$YPos,$this->posx2,$YPos,4,$this->r,$this->g,$this->b);
		}

	 }
     $XPos=$this->posx1;
     for($i=0;$i<$this->colnum;$i++)
	 {
	     $t=$XPos;
		 $XPos=$t+$this->pos[0][$i][0];
         
		 if ($this->Vdotte&&$XPos > $this->posx1 && $XPos < $this->posx2 )
          $p->drawDottedLine($XPos,$this->posy1,$XPos,$this->posy2+5,4,$this->r,$this->g,$this->b);
	 }
		$XPos=$this->posx1;
		$YPos=$this->posy1;
		$pdx=ceil(($this->posx2-$this->posx1)/($this->colnum*5));
		$p->setFontProperties($this->fontpath."Fonts/yahei.ttf",8);
		$per=$this->total/$this->colnum;
    for($i=0;$i<=$this->colnum;$i++)
    {
	   $p->drawLine($XPos-1,$YPos,$XPos-1,$YPos+10,$this->r,$this->g,$this->b);
	   $p->drawLine($XPos,$YPos,$XPos,$YPos+10,$this->r,$this->g,$this->b);
	   $p->drawLine($XPos+1,$YPos,$XPos+1,$YPos+10,$this->r,$this->g,$this->b);

	   $p->drawLine($XPos-1,$this->posy2-10,$XPos-1,$this->posy2+10,$this->r,$this->g,$this->b);
	   $p->drawLine($XPos,$this->posy2-10,$XPos,$this->posy2+10,$this->r,$this->g,$this->b);
	   $p->drawLine($XPos+1,$this->posy2-10,$XPos+1,$this->posy2+10,$this->r,$this->g,$this->b);

       
       $p->drawTextBox($XPos-15,$this->posy2+15,$XPos+15,$this->posy2+30,ceil($i*$per),0,$this->r,$this->g,$this->b,ALIGN_CENTER,false,0,0,0,0);

       $dx=$XPos;
	   if($i<$this->colnum)
	   for($n=0;$n<4;$n++)
	   {
	     $dx=$dx+$pdx;
		 $p->drawLine($dx,$YPos,$dx,$YPos+5,$this->r,$this->g,$this->b);
		 $p->drawLine($dx,$this->posy2-5,$dx,$this->posy2+5,$this->r,$this->g,$this->b);
	   }
	   $XPos=$XPos+$this->pos[0][$i][0];
	}
		$XPos=$this->posx1;
		$YPos=$this->posy1;
	for($i=0;$i<=$this->rownum;$i++)
	{
	   $p->drawLine($XPos-10,$YPos-1,$XPos+5,$YPos-1,$this->r,$this->g,$this->b);
	   $p->drawLine($XPos-10,$YPos,$XPos+5,$YPos,$this->r,$this->g,$this->b);
	   $p->drawLine($XPos-10,$YPos+1,$XPos+5,$YPos+1,$this->r,$this->g,$this->b);

	   $p->drawLine($this->posx2-5,$YPos-1,$this->posx2,$YPos-1,$this->r,$this->g,$this->b);
	   $p->drawLine($this->posx2-5,$YPos,$this->posx2,$YPos,$this->r,$this->g,$this->b);
	   $p->drawLine($this->posx2-5,$YPos+1,$this->posx2,$YPos+1,$this->r,$this->g,$this->b);

	   $YPos=$YPos+$this->pos[$i][0][1];
	}
    Return $this;
  }
  public function drawBiaoTable($p) {
  	$p->drawFilledRectangle($this->posx1-2,$this->posy1-2,$this->posx2,$this->posy1,$this->r,$this->g,$this->b);

	$p->drawFilledRectangle($this->posx1-2,$this->posy2,$this->posx2,$this->posy2+2,$this->r,$this->g,$this->b);
	$p->drawFilledRectangle($this->posx1-2,$this->posy1-7,$this->posx1,$this->posy2+7,$this->r,$this->g,$this->b);

	$XPos=$this->posx1;
	$YPos=$this->posy1;
	$per=$this->pos[0][0][1]/2;
	//画标尺
	$p->setFontProperties($this->fontpath."Fonts/yahei.ttf",12);
	$p->drawFilledRectangle($this->posx2,$YPos-5,$this->posx2+2,$YPos+5,$this->r,$this->g,$this->b);
			   for($n=0;$n<=$this->colnum;$n++)
			   {
				   if($n!=0)
				 $p->drawFilledRectangle($XPos,$YPos-5,$XPos+2,$YPos+5,$this->r,$this->g,$this->b);
				 $XPos=$XPos+$this->pos[0][0][0];
			   }
			   $XPos=$this->posx1;
			   $pnum=$this->total/$this->colnum;
	for($i=0;$i<=$this->rownum;$i++)
	{
	   $p->drawFilledRectangle($this->posx1-7,$YPos-2,$this->posx1,$YPos,$this->r,$this->g,$this->b);
	   if($i!=$this->rownum)
	   {
	      $p->drawFilledRectangle($this->posx2,$YPos+$per-5,$this->posx2+2,$YPos+$per+5,$this->r,$this->g,$this->b);
		  $p->drawDottedLine($this->posx1,$YPos+$per,$this->posx2,$YPos+$per,3,$this->r,$this->g,$this->b);
		  	   for($n=0;$n<=$this->colnum;$n++)
			   {
				 if($n!=0)
				 $p->drawFilledRectangle($XPos,$YPos+$per-5,$XPos+2,$YPos+$per+5,$this->r,$this->g,$this->b);
				 $XPos=$XPos+$this->pos[0][0][0];
			   }
			   $XPos=$this->posx1;
	   }else{
	     $p->drawFilledRectangle($this->posx2,$YPos-5,$this->posx2+2,$YPos+5,$this->r,$this->g,$this->b);
		 	   for($n=0;$n<=$this->colnum;$n++)
			   {
				   if($n!=0)
				 $p->drawFilledRectangle($XPos,$YPos-5,$XPos+2,$YPos+5,$this->r,$this->g,$this->b);

				 $p->drawTextBox($XPos-15,$this->posy2+15,$XPos+15,$this->posy2+30,ceil($n*$pnum),0,$this->r,$this->g,$this->b,ALIGN_CENTER,false,0,0,0,0);
				 $p->drawTextBox($XPos-15,$this->posy1-25,$XPos+15,$this->posy1-10,ceil($n*$pnum),0,$this->r,$this->g,$this->b,ALIGN_CENTER,false,0,0,0,0);
				 $XPos=$XPos+$this->pos[0][0][0];
			   }
			   $XPos=$this->posx1;
	   }
	   $YPos=$YPos+$this->pos[$i][0][1];

	}
	Return $this;
  }
  /*
  *相对于表格画垂直折线
  *$p为画句柄 
  *$data为数据 并会被转换成100比
  * $r $g $b为线的颜色
  */
	 public function drawVline($p,$data=array(),$r=0,$g=0,$b=0) {
		$pdx=($this->posx2-$this->posx1);
		$i=0;
		$odx=0;
		//$t=$this->colnum*10;
		foreach($data as $key=>$value)
		{
			//$ndx=$this->posx1+ceil(($pdx*$value)/$t);
			$ndx=$this->posx1+ceil(($pdx*$value)/$this->total);
			if($i>0)
			{
			  $ndy=$this->pos[$i][0][1]+$ody;
			  $p->drawLine($odx,$ody,$ndx,$ndy,$r,$g,$b);
			}else{
			  $ndy=ceil($this->pos[0][0][1]/2)+$this->posy1;
			}
			$p->drawRectangle($ndx-5,$ndy-5,$ndx+5,$ndy+5,$r,$g,$b);
			$odx=$ndx;
			$ody=$ndy;
			$i++;
		}
	 }
	  /*
	  *相对于表格画垂直直方图
	  *$p为画句柄 
	  *$data为数据 并会被转换成100比
	  * $r $g $b为线的颜色
	  */
	 public function drawVrect($p,$data=array(),$r=0,$g=0,$b=0) {
		static $kx=array();
		$pdx=($this->posx2-$this->posx1);
		$i=0;
		$odx=0;
		$pe=$this->pos[0][0][1]/4;
		$sdx=$this->posx1;
		foreach($data as $key=>$value)
		{
			$ndx=$this->posx1+ceil(($pdx*$value)/$this->total);
			if($i>0)
			{
			  $ndy=$this->pos[$i][0][1]+$ody;
			}else{
			  $ndy=ceil($this->pos[0][0][1]/2)+$this->posy1;
			}
			//级数值排序
            if(empty($kx[$key])) $kx[$key]=array();
			$t=rand(10,99);
			$t=$ndx*10000+$t;
			$kx[$key][$t]=array("data"=>$ndx,"r"=>$r,"g"=>$g,"b"=>$b);
			ksort($kx[$key]);

			$o=$sdx;
			$u=0;
            foreach($kx[$key] as $v)
			{
			  $t=0;
			  $odx=$ndy;
			  if($o==$v['data'])
			  {
				$odx=$ndy-2;
				$t=2;
				if($v['data']==$u)
				{
				  $odx=$ndy+2;
				  $t=2;
				}
			  }
			 //$o从上次结尾画起 错开画
			 $p->drawFilledRectangle($o+$t,$odx-$pe,$v['data'],$odx+$pe,$v['r'],$v['g'],$v['b'],true,100);
             
			 $u=$o;
			 $o=$v['data'];
			  
			}
			$ody=$ndy;
			$i++;
		}
	 }
	  /*
	  *相对于表格画垂直直方图
	  *$p为画句柄 
	  *$data为数据 并会被转换成100比
	  * $r $g $b为线的颜色
	  */
	 public function drawVBiaoline($p,$data=array(),$r=0,$g=0,$b=0) {
		static $kx=array();
		$pdx=($this->posx2-$this->posx1);
		$i=0;
		$odx=0;
		$sdx=$this->posx1;
		foreach($data as $key=>$value)
		{
			$ndx=$this->posx1+ceil(($pdx*$value)/$this->total);
			if($i>0)
			{
			  $ndy=$this->pos[$i][0][1]+$ody;
			}else{
			  $ndy=ceil($this->pos[0][0][1]/2)+$this->posy1;
			}
			//级数值排序
            if(empty($kx[$key])) $kx[$key]=array();
			 array_push($kx[$key],$ndx);
             
			 //$o从上次结尾画起 错开画
			 $p->drawFilledRectangle($sdx,$ndy-1,$ndx,$ndy+1,$r,$g,$b,true,100);

			$odx=$ndx;
			$ody=$ndy;
			$i++;
		}
	 }
	  /*
	  *相对于表格画垂直直方图
	  *$p为画句柄 
	  *$data为数据 并会被转换成100比
	  * $r $g $b为线的颜色
	  */
	 public function drawVbar($p,$data=array(),$r=0,$g=0,$b=0) {
		static $kx=array();
		$pdx=($this->posx2-$this->posx1);
		$i=0;
		$odx=0;
		$sdx=$this->posx1;
		foreach($data as $key=>$value)
		{
			$ndx=$this->posx1+ceil(($pdx*$value)/$this->total);
			if($i>0)
			{
			  $ndy=$this->pos[$i][0][1]+$ody;
			}else{
			  $ndy=ceil($this->pos[0][0][1]/6)+$this->posy1;
			}
			//级数值排序
            if(empty($kx[$key])) $kx[$key]=array();
			 array_push($kx[$key],$ndx);
             
			 $t=count($kx[$key])*8;

			 //$o从上次结尾画起 错开画
			 $p->drawFilledRectangle($sdx,$ndy+$t,$ndx,$ndy+$t+4,$r,$g,$b,true,100);

			$odx=$ndx;
			$ody=$ndy;
			$i++;
		}
	 }
	  /*
	  *相对于表格画垂直直方图 百分比占满格
	  *$p为画句柄 
	  *$data为数据 并会被转换成100比
	  * $r $g $b为线的颜色
	  *$text=false不显示 百分比在bar上
	  */
	 public function drawVfullbar($p,$data=array(),$r=0,$g=0,$b=0,$text=false) {
		static $kx=array();
		$pdx=($this->posx2-$this->posx1);
		$i=0;
		$odx=0;
		$pe=$this->pos[0][0][1]/4;
		$sdx=$this->posx1+1;
		foreach($data as $key=>$value)
		{
			//级数值排序
            if(empty($kx[$key])) $kx[$key]=$sdx;
			$ndx=$kx[$key]+ceil(($pdx*$value)/$this->total);
			if($i>0)
			{
			  $ndy=$this->pos[$i][0][1]+$ody;
			}else{
			  $ndy=ceil($this->pos[0][0][1]/2)+$this->posy1;
			}
             if($this->posx2<=$ndx) $ndx=$ndx-2;
			 //$o从上次结尾画起 错开画
			 $p->drawFilledRectangle($kx[$key],$ndy-$pe,$ndx,$ndy+$pe,$r,$g,$b,true,100);
			 //是否显示数字
			 if($text)
			  $p->drawTextBox($kx[$key],$ndy-$pe,$ndx,$ndy+$pe,$value."%",0,0,0,0,ALIGN_CENTER,false,0,0,0,0);
			$kx[$key]=$ndx;
			$odx=$ndx;
			$ody=$ndy;
			$i++;
		}
	 }
 public function addItem($i=0,$txt='') {
 	$this->postxt[$i]=$txt;
	Return $this;
 }
 public function drawItemText($p) {
	  if(is_array($this->postxt))
	  {
        $p->setFontProperties($this->fontpath."Fonts/minisun.ttf",12);
		$XPos1=0;
		$XPos2=$this->posx1-10;
		$YPos=$this->posy1;
        for($i=0;$i<$this->rownum;$i++)
		{	      
		  $p->drawTextBox($XPos1,$YPos,$XPos2,$YPos+$this->pos[$i][0][1],$this->postxt[$i],0,$this->r,$this->g,$this->b,ALIGN_RIGHT,false,0,0,0,0);
		  $YPos=$YPos+$this->pos[$i][0][1];
		}
	  }
	  Return $this;	  	
 }
 //画图例样子
 public function lineStyle($p,$text,$ndx,$ndy,$r,$g,$b,$type){
	$p->drawRectangle($ndx-5,$ndy-5,$ndx+5,$ndy+5,$r,$g,$b);
	$p->drawLine($ndx-10,$ndy,$ndx+10,$ndy,$r,$g,$b);
	$p->drawTextBox($ndx+10,$ndy-20,$ndx+120,$ndy+15,$text,0,$r,$g,$b,ALIGN_LEFT,false,0,0,0,0);
 }
  //画图例样子
 public function barStyle($p,$text,$ndx,$ndy,$r,$g,$b,$type){
	$p->drawFilledRectangle($ndx-10,$ndy-5,$ndx+10,$ndy+5,$r,$g,$b);
	$p->drawTextBox($ndx+15,$ndy-20,$ndx+120,$ndy+15,$text,0,$r,$g,$b,ALIGN_LEFT,false,0,0,0,0);
 }
} 
?>