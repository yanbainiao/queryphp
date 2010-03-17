<?php
class pager
{
    var $total;    
    var $onepage;
    var $num;
    var $page;
    var $total_page;
    var $offset;
    var $linkhead;    

    function opb($total, $onepage, $pagenumer = '') 
    {
        $page             = $pagenumer?$_GET[$pagenumer]:$_GET['page'];
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
    function offset()
    {
        return $this->offset;
    }

}
?> 