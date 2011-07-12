<?php
/*
*动态分页类
*/
class pager
{
    public $total;    
    private $onepage;
    public $page;
    private $total_page;
    public $offset;
    private $linkhead;
	private $pagesplit;

   public function __construct($total='1', $onepage='1', $pagenumer = '') 
    {
      $this->setPager($total, $onepage, $pagenumer);
    }
   public function setPager($total='1', $onepage='1', $pagenumer = '') {
	    $this->pagesplit=$pagenumer?$pagenumer:'page';
        $page             = isset($_GET[$this->pagesplit])?$_GET[$this->pagesplit]:1;
        $this->total      = $total;
        $this->onepage    = $onepage;
        $this->total_page =  ceil($total/$onepage);
        if ($page=='')  
        {
            $this->page   = 1;
            $this->offset = 0;    
        }
        else
        {
            $this->page   = $page;
            $this->offset = ($page-1)*$onepage;
        }   	
   }
   public function pageNum() {
   	  Return $this->onepage;
   }
   public function offset()
    {
        Return $this->offset;
    }
   public function firstPage()
    {
        Return 1;
    }
   public function lastPage()
    {
        Return $this->total_page;
    }	
   public function prePage()
    {
       Return max($this->page-1,1);
	}
   public function nextPage() 
    {
	  Return min($this->page+1,$this->total_page);
	}
	/*
	*取得链接数组
	*返回array(1,2,3,4,5,6)这样的数组
	*foreach($pager->getLinks() as $page)
	*echo url_for("/model/action/page/").$page;
	*/
   public function getLinks($num=10) {
		$links=array();
        if($this->total_page==0) Return array();
	    $mid       =  floor($num/2);
        $last      =  $num - 1; 
        $minpage   =  ($this->page-$mid)<1 ? 1 : $this->page-$mid;
        $maxpage   =  $minpage + $last;
        if ($maxpage>$this->total_page)
        {
            $maxpage =$this->total_page;
            $minpage =  $maxpage - $last;
            $minpage =  $minpage<1 ? 1 : $minpage;
        }

		$links=range($minpage,$maxpage);
		Return $links;
	}
	/*
	*取得带url链接数组
	*key=>value方式
	*返回array(1=>url,2=>url,3=>url,4=>url)这样的数组
	*foreach($pager->getBar(url_for("model/action/page/:page")) as $key=>$page)
	*echo "<a href=".$page.">".$key."</a>";
	*/
  public function getBar($url){
	    if($this->total_page==0) Return array();
	   $this->linkhead=str_replace(":".$this->pagesplit,"%d",$url);
	   $links=array();
	   $links[L('第一页')]=sprintf($this->linkhead,1);
	   $n=1;
	   if($this->prePage()>1)
	   {
	    $links[L('上一页')]=sprintf($this->linkhead,$this->prePage());
	    $n++;
	   }
	   foreach($this->getLinks() as $page)
	   {
	     $links[$n]=sprintf($this->linkhead,$page);
		 $n++;
	   }
	   if($this->nextPage()<$this->total_page)
	   {
	    $links[L('下一页')]=sprintf($this->linkhead,$this->nextPage());
	    $n++;
	   }
	   $links[L('最后一页')]=sprintf($this->linkhead,$this->total_page);
	   Return $links;
   }
   //返回数字json数组
  public function arraynum() {
  	 Return array("total"=>$this->total_page,
							  "prepage"=>$this->prePage(),
							  "nextpage"=>$this->nextPage(),
							  "links"=>$this->getLinks(),
							  "page"=>$this->page);
  }
   /*
   *取得整行分页html
   *返回html
   *echo $pager->getWholeBar();
   *<a href="url">5</a>
   */
  public function getWholeBar($url){
	  if($this->total_page==0) Return "";
	   $this->linkhead=str_replace(":".$this->pagesplit,"%d",$url);
	   $links='';
	   $links.='<a href="'.sprintf($this->linkhead,1).'"><span>'.L('第一页').'</span></a>';
	   $n=1;
	   if($this->prePage()>1)
	   {
		$links.='<a href="'.sprintf($this->linkhead,$this->prePage()).'"><span>'.L('上一页').'</span></a>';
	    $n++;
	   }
	   foreach($this->getLinks() as $page)
	   {
	     
		 if($page==$this->page)
		 {
		   $links.='<a href="'.sprintf($this->linkhead,$page).'" class="currentpage"><span class="currentpage">'.$page.'</span></a>';
		 }else{
		   $links.='<a href="'.sprintf($this->linkhead,$page).'"><span>'.$page.'</span></a>';
		 }
		 $n++;
	   }
	   if($this->nextPage()<$this->total_page)
	   {
	    $links.='<a href="'.sprintf($this->linkhead,$this->nextPage()).'"><span>'.L('下一页').'</span></a>';
	    $n++;
	   }
	   $links.='<a href="'.sprintf($this->linkhead,$this->total_page).'"><span>'.L('最后一页').'</span></a>';
	   Return $links;
   }
}
?> 