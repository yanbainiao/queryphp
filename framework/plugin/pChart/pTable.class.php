<?php
/*
* 使用pChart画表格和在表格中插入字符
__construct($x1=0,$y1=0,$x2=0,$y2=0,$rownum=1,$colnum=1,$R=0,$G=0,$B=0,$bgtype=0,$br=0,$bg=0,$bb=0)
 $bytype 可以取四种值 1 2 为行着色 3 4为列着色 后面是三个着色rgb参数
 $Test->setFontProperties("Fonts/minisun.ttf",14);//设置字体
 $table=new pChartTable(200,10,780,200,4,4,0,0,0,2,230,230,230);
 $table->setTDxy(0,0,80,20); //设置TD宽度和高度
 $table->xiuTD();            //重新整理TD的宽和高
 $table->drawTable($Test);   //画表格
 $table->setTDtext(0,0,'评测指标');
 $table->setTDtext(0,1,'领导行为');
 $table->drawTDtext($Test);//画文字 
 //$table->draw($Test); 同时调用drawTable drawTDtext
 $Test->Render("example2.png");
*/
class pChartTable {
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
	/*
	* $rownum 多少行
	* $colnum 多少列
	* bgtype 是否画表格阴影
	*/
	public function __construct($x1=0,$y1=0,$x2=0,$y2=0,$rownum=1,$colnum=1,$R=0,$G=0,$B=0,$bgtype=0,$br=0,$bg=0,$bb=0) {
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

		$this->rownum=$rownum;
		$this->colnum=$colnum;
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
	/*
	* 给字段TD赋值 可以是左对齐右对齐 中间对齐
	*/
	public function setTDtext($x=0,$y=0,$text='',$align=ALIGN_CENTER,$r=0,$g=0,$b=0) {
		$this->postxt[$x][$y]['text']=$text;
		$this->postxt[$x][$y]['align']=$align;
		$this->postxt[$x][$y]['r']=$r;
		$this->postxt[$x][$y]['g']=$g;
		$this->postxt[$x][$y]['b']=$b;
		Return $this;
	}
	/*
	* 给TD设置宽度
	*/
	public function setTDxy($x=0,$y=0,$w=0,$h=0){
	   if($h>0)
	   $this->pos[$x][$y][1]=$h;
	   if($w>0)
	   $this->pos[$x][$y][0]=$w;
	   Return $this;
	}
	/*
	* 统一画表格接口，先画表格，再画文字
	*/
	public function draw($p) {
		$this->drawTable($p);
		$this->drawTDtext($p);
		Return $this;
	}
	/* 画TD中的文字 */
	public function drawTDtext($p) {
	  if(is_array($this->postxt))
	  {
		  $h=$this->posy1;
		  //取得TD坐标
	     foreach($this->pos as $k=>$v)
		 {
		    
			$w=$this->posx1;
			if(is_array($v))
			foreach($v as $kk=>$vv)
			{
               
			   if(isset($this->postxt[$k][$kk]))			   
				{   
				  $x2=$w+$vv[0];
				  $y2=$h+$vv[1];
				  $p->drawTextBox($w,$h,$x2,$y2,$this->postxt[$k][$kk]['text'],0,$this->postxt[$k][$kk]['r'],$this->postxt[$k][$kk]['g'],$this->postxt[$k][$kk]['b'],$this->postxt[$k][$kk]['align'],false,0,0,0,0);
				}
				$w=$w+$vv[0];
			}
			$h=$h+$v[0][1];
		 }
	  }
	  Return $this;
	}
	/*
	*画表格	
	*/
	public function drawTable($p) {
	  $p->drawRectangle($this->posx1,$this->posy1,$this->posx2,$this->posy2+2,$this->r,$this->g,$this->b);

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
		$p->drawLine($this->posx1,$YPos,$this->posx2,$YPos,$this->r,$this->g,$this->b);
		}

	 }
     $XPos=$this->posx1;
     for($i=0;$i<$this->colnum;$i++)
	 {
	     $t=$XPos;
		 $XPos=$t+$this->pos[0][$i][0];
         if ( $XPos > $this->posx1 && $XPos < $this->posx2 )
         $p->drawLine($XPos,$this->posy1,$XPos,$this->posy2,$this->r,$this->g,$this->b);
	 }
    Return $this;

	}
	//处理TD宽度和高度
	public function xiuTD() {
		if(is_array($this->pos))
		{
		  $h=0;
		  //统一高度
		  foreach($this->pos as $kk=>$vv)
		  {
			  foreach($this->pos[$kk] as $k=>$v)
			  {
				if($h<$v[1])
				{
				  $h=$v[1];
				}
			  }
			  foreach($this->pos[$kk] as $k=>$v)
			  {
				$this->pos[$kk][$k][1]=$h;
			  }
             $h=0;
          }
		   $w=0;
		   $h=0;
		   $n=count($this->pos);
		   $m=0;
		  //统一其宽度
		  while(isset($this->pos[$m][$h]))
		  {		   
            if($w<$this->pos[$m][$h][0])
			{
			  $w=$this->pos[$m][$h][0];
			}
			$m++;
		    if($n==$w)
		    {
			 for($i=0;$i<$n;$i++)
			 {
			  $this->pos[$m][$i][0]=$w;
		     }
			 $h++;
			 $m=0;
		    }
		  }//endwhile
		}
	 Return $this;
	}
} 
?>