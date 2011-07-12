<?php
/*
* 垂直项目百分比图表
* 所有项目在在Y轴上列出来。
*  "r"=>"红色","g"=>"绿色","b"=>"蓝色"
* $zhibiao[0]=array("data"=>array(45,28,55,67,35),"title"=>"评测指标","r"=>"255","g"=>"0","b"=>"0");
* $zhibiao[1]=array("data"=>array(66,63,78,76,72),"title"=>"领导行为","r"=>"0","g"=>"0","b"=>"255");
* data中的数据是百分比数据,因为要映射到图表中
* 跟上面data中的数据对应
* $items=array("评测指标六字","评测指标","评测指标","评测指标","评测指标六字");
*
*  $chartw=720;
*  $charth=460;
  
*  $dw=580;
*  $dh=360;

*  $dx=120; 起始是120为项目留空间
*  $dy=40;  为图例留空间

*  $iw=$dw;
*  $ih=count($zhibiao)*30;

*  $ix=$dx;
*  $iy=$dh+$dy+40;
*/
 class PStyle1 {
	public $chartw=1;
	public $charth=1;
	public $dw=1;
	public $dh=1;
	public $dx=1;
	public $dy=1;
	public $iw=1;
	public $ih=1;
	public $ix=1;
	public $iy=1;

    public $itemrow=0;
	public $itemcol=0;

	public $data=array();
	public $items=array();
	public $style; //显示图标样式
	public $totalnum; //显示X轴标尺最大数
	public $percol; //最大数分成多少等份

	public $bx; //画图开始参考点X 默认为x=0 y=0
	public $by; //画图开始参考点y

	public $datatable=true;//是否显示数据表格;
	public $vdotte=true;
	public $hdotte=true;

 	public function __construct($data=array(),$items=array(),$cw=1,$ch=1,$style=1,$bx=0,$by=0,$dw=0,$dh=0,$dx=0,$dy=0,$iw=0,$ih=0,$ix=0,$iy=0) {
 		 $this->data=$data;
		 $this->items=$items;
		 
		 $this->chartw=$cw;
		 $this->charth=$ch;
		 $this->style=$style;
         $this->itemcol=count($items);
         $this->itemrow=count($data);
		 
		 if($dw>0){
		 $this->dw=$dw;
		 }else{
		   $this->dw=$this->chartw-120-20;
		 }
		 if($dh>0){
		  $this->dh=$dh;
		 }else{
		   $this->dh=$this->charth-120-$this->itemrow*30;
		 }
      
	     $this->bx=$bx;
		 $this->by=$by;

         
		 if($dx>0){
		  $this->dx=$dx;
		 }else{
		  $this->dx=$this->bx+120;
		 }
		 if($dy>0){
		  $this->dy=$dy;
	     }else{
		   $this->dy=$this->by+40;
		 }
		 if($iw>0){
		    $this->iw=$iw;
		 }else
		   $this->iw=$this->dw;        


		 if($ih>0)
		  $this->ih=$ih;
		 else
		  $this->ih=$this->itemrow*30+30;

         if($ix>0) 
		   $this->ix=$ix;
		 else
		 $this->ix=$this->dx;
        
		 if($iy>0){
			$this->iy=$iy;
		 }else
		 $this->iy=$this->dh+$this->dy+40;
		 Return $this;
 	}
	//初始化整个布局
	public function init($cw=null,$ch=null,$dw=null,$dh=null,$dx=null,$dy=null)
	{
		if($cw) $this->chartw=$cw;
		if($ch) $this->charth=$ch;

		if($dw) $this->dw=$dw;
		if($dh) $this->dh=$dh;

         if($dx){
		  $this->dx=$dx;
		 }else{
		  $this->dx=$this->bx+120;
		 }
		 if($dy){
		  $this->dy=$dy;
	     }else{
		   $this->dy=$this->by+40;
		 }

		$this->iw=$this->dw;
		$this->ih=count($this->itemrow)*30+30;

		$this->ix=$this->dx;        
		$this->iy=$this->dh+$this->dy+40;
		Return $this;
	}

	//设置项目
	public function setBeseXY($bx,$by)
	{
	     $this->bx=$bx;
		 $this->by=$by;
	  Return $this;
	}
    //datatable数据表格显示
	//设置项目
	public function setDatatable($i)
	{
	  $this->datatable=$i?true:false;
	  Return $this;
	}
	//设置项目
	public function setGrid($r,$c)
	{
	  $this->totalnum=$r;
	  $this->percol=$c;
	  Return $this;
	}
	//设置项目
	public function setStyle($i)
	{
	  $this->style=$i;
	  Return $this;
	}
	//设置项目
	public function setItems($i)
	{
	  $this->items=$i;
	  $this->itemcol=count($this->items);
	  Return $this;
	}
	//设置数据
	public function setData($data)
	{
	  $this->data=$data;
	  $this->itemrow=count($this->data);
	  Return $this;
	}
	//设置表格开始画的位置
	public function setPitemsXY($x=NULL,$y=NULL)
	{
	   	 if($x) $this->ix=$x;
		 if($y) $this->iy=$y;
		 Return $this;
	}
	//设置表格开始画的位置
	public function setPgridXY($x=NULL,$y=NULL)
	{
	   	 if($x) $this->dx=$x;
		 if($y) $this->dy=$y;
		 Return $this;
	}
	//设置画布宽度和高度
	public function setPchartWH($w=NULL,$h=NULL)
	{
	   	 if($w) $this->chartw=$w;
		 if($h) $this->charth=$h;
		 Return $this;
	}
	//设置画布宽度和高度
	public function setPgridWH($w=NULL,$h=NULL)
	{
	   	 if($w) $this->dw=$w;
		 if($h) $this->dh=$h;
		 Return $this;
	}
	//设置画布宽度和高度
	public function setPitemsWH($w=NULL,$h=NULL)
	{
	   	 if($w) $this->iw=$w;
		 if($h) $this->ih=$h;
		 Return $this;
	}
	/*
	*画图表线示例 就表格中线的颜色是什么意思
	*来源于数据中的title说明
	*/
	public function drawLinesStyle($p,$table)
	{
	  $ndx=0;

	  $x=$this->bx+10;
	  $y=$this->by+10;
	  foreach($this->data as $v)
	  {
		$table->lineStyle($p,$v['title'],$x,$y,$v['r'],$v['g'],$v['b'],1);
        $ndx=strlen($v['title'])*12;
		$x=$x+$ndx;
	  }
	  Return $this;
	}
	/*
	*画图表线示例 就表格中线的颜色是什么意思
	*来源于数据中的title说明
	*/
	public function drawBarsStyle($p,$table)
	{
	  $ndx=0;

	  $x=$this->bx+10;
	  $y=$this->by+10;
	  foreach($this->data as $v)
	  {
		$table->barStyle($p,$v['title'],$x,$y,$v['r'],$v['g'],$v['b'],1);
        $ndx=strlen($v['title'])*10;
		$x=$x+$ndx;
	  }
	  Return $this;
	}
	//画项目，就是垂直项目
	public function drawItems($p,$table)
	{
		 $i=0;
		 foreach($this->items as $v)
		 {
		   $table->addItem($i,$v);
		   $i++;
		 }
		 $table->drawItemText($p);	 
		  Return $this;
	}
	//画折线
	public function drawVline($p,$table)
	{
		foreach($this->data as $v)
		  {
            $table->drawVline($p,$v['data'],$v['r'],$v['g'],$v['b']);
		  }
		  Return $this;  
	}
	//画直方图
	public function drawVrect($p,$table)
	{
		foreach($this->data as $v)
		  {
            $table->drawVrect($p,$v['data'],$v['r'],$v['g'],$v['b']);
		  }
		  Return $this;  
	}
	//画直方图百分比
	public function drawVfullbar($p,$table,$text=false)
	{
		foreach($this->data as $v)
		  {
            $table->drawVfullbar($p,$v['data'],$v['r'],$v['g'],$v['b'],$text);
		  }
		  Return $this;  
	}
	//画直直条
	public function drawVbar($p,$table)
	{
		foreach($this->data as $v)
		  {
            $table->drawVbar($p,$v['data'],$v['r'],$v['g'],$v['b']);
		  }
		  Return $this;  
	}
	//画直直条
	public function drawVBiaoline($p,$table)
	{
		foreach($this->data as $v)
		  {
            $table->drawVBiaoline($p,$v['data'],$v['r'],$v['g'],$v['b']);
		  }
		  Return $this;  
	}
	/*画数据表格
	*$this->itemrow+1,$this->itemcol+1
	*多加一行放项目，项目会被截为4个字符
	*
	*/
	public function drawDataTable($p) {
	  $table=new pChartTable($this->ix,$this->iy,$this->ix+$this->iw,$this->iy+$this->ih,$this->itemrow+1,$this->itemcol+1,0,0,0,2,230,230,230);
	  $p->setFontProperties($GLOBALS['config']['frameworkpath']."Fonts/yahei.ttf",8);
      $j=1;
       foreach($this->items as $value)
		 {
		   //多于六项截取长度
		   $txt=$this->itemcol>6?mb_substr($value,0,4,'utf-8'):$value;
		   $table->setTDtext(0,$j,$txt,ALIGN_CENTER,$v['r'],$v['g'],$v['b']); 
		   $j++;
		 }
      $i=1;
	  foreach($this->data as $v)
  	  {
	     $table->setTDtext($i,0,$v['title'],ALIGN_CENTER,$v['r'],$v['g'],$v['b']);
       	 $j=1;
		 foreach($v['data'] as $value)
		 {
		   $table->setTDtext($i,$j,$value,ALIGN_CENTER,$v['r'],$v['g'],$v['b']); 
		   $j++;
		 }
		 $i++;
	  }
	  $table->draw($p);
	}
	public function setHdotte($i){
	  $this->hdotte=$i;
	  Return $this;
	}
	public function setVdotte($i){
	  $this->vdotte=$i;
	  Return $this;
	}
	//画出图表
	public function drawPchart($p)
	{
	 //画折线表 
	 //$this->itemcol,10,100
	 //表示有多少个项目，分格10列，最大数为100
	 $table=new pGridTable($this->dx,$this->dy,$this->dx+$this->dw,$this->dy+$this->dh,$this->itemcol,10,100,0,0,0,0,230,230,230);
	  $table->setVdotte($this->vdotte);
	  $table->setHdotte($this->hdotte); 
	 $table->fontpath=$GLOBALS['config']['frameworkpath'];
     $table->drawTable($p);

	 switch($this->style)
	 {
	  case '5':
		   //画直方图
	  	   $this->drawVfullbar($p,$table,true);
		   	 //画线颜色示例
		$this->drawBarsStyle($p,$table); 
	  	break;
	  case '4':
		   //画直方图
	  	   $this->drawVfullbar($p,$table);
		   	 //画线颜色示例
		$this->drawBarsStyle($p,$table); 
	  	break;
	  case '3':
		   //画直方图
	  	   $this->drawVbar($p,$table);
		   	 //画线颜色示例
		$this->drawBarsStyle($p,$table); 
	  	break;
	  case '2':
		   //画直方图
	  	   $this->drawVrect($p,$table);
		   	 //画线颜色示例
		$this->drawBarsStyle($p,$table); 
	  	break;
	  default:
		  //画折线
		  $this->drawVline($p,$table);
		//画线颜色示例
		$this->drawLinesStyle($p,$table); 
	 } 
	 //画项目
	 $this->drawItems($p,$table);

     //画数据表格
	 if($this->datatable)
	  $this->drawDataTable($p,$table);	 
	}
	//画出图表
	public function drawBiaoPchart($p)
	{
	 //画折线表 
	 //$this->itemcol,10,100
	 //表示有多少个项目，分格10列，最大数为100
	 $table=new pGridTable($this->dx,$this->dy,$this->dx+$this->dw,$this->dy+$this->dh,$this->itemcol,5,5,0,0,0,0,230,230,230);
	 $table->fontpath=$GLOBALS['config']['frameworkpath'];
     $table->drawBiaoTable($p);
	 $this->drawVBiaoline($p,$table);
	 //画项目
	 $this->drawItems($p,$table);
     //画数据表格
	 if($this->datatable)
	  $this->drawDataTable($p,$table);	 
	}
	//可以自由画项目折线
	//画出图表
	public function drawdataPchart($p,$lineper=0)
	{
	 //画折线表 
	 //$this->itemcol,10,100
	 //表示有多少个项目，分格10列，最大数为100
	 $table=new pGridTable($this->dx,$this->dy,$this->dx+$this->dw,$this->dy+$this->dh,$this->itemcol,10,100,0,0,0,0,230,230,230);
	  $table->setVdotte($this->vdotte);
	  $table->setHdotte($this->hdotte); 
	 $table->fontpath=$GLOBALS['config']['frameworkpath'];
     $table->drawTable($p);


     //画数据表格
	 if($this->datatable)
	  $this->drawDataTable($p,$table);	 
	 //画项目
	 $this->drawItems($p,$table);
	 if($lineper!=0)
$this->data=array_slice($this->data,0,$lineper);
	 switch($this->style)
	 {
	  case '5':
		   //画直方图
	  	   $this->drawVfullbar($p,$table,true);
		   	 //画线颜色示例
		$this->drawBarsStyle($p,$table); 
	  	break;
	  case '4':
		   //画直方图
	  	   $this->drawVfullbar($p,$table);
		   	 //画线颜色示例
		$this->drawBarsStyle($p,$table); 
	  	break;
	  case '3':
		   //画直方图
	  	   $this->drawVbar($p,$table);
		   	 //画线颜色示例
		$this->drawBarsStyle($p,$table); 
	  	break;
	  case '2':
		   //画直方图
	  	   $this->drawVrect($p,$table);
		   	 //画线颜色示例
		$this->drawBarsStyle($p,$table); 
	  	break;
	  default:
		  //画折线
		  $this->drawVline($p,$table);
		//画线颜色示例
		$this->drawLinesStyle($p,$table); 
	 } 
	}
 } 
?>