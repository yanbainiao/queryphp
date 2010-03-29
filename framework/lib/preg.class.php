<?php

class preg {
  /*
  *正则表达式提取表格tr行
  *
  */
  static function match_tr($content) {
  	Return preg_match_all('/<tr.*?>[\r\n]{0,2}(<td.*?>.*?<\/td>[\r\n]{0,2})*<td.*?>.*?hidden.*?<\/td>[\r\n]{0,2}(<td.*?>.*?<\/td>[\r\n]{0,2})*<\/tr>/i',$content,$matchs)?$matchs:null;
  }	
  /*
  *正则提取图片
  *
  *
  */
  static function match_images($content)
        {
        //获取内容中图片
        //取得第一个匹配的图片路径
        $retimg="";
        $matches=null;
         //标准的src="xxxxx"或者src='xxxxx'写法
        preg_match("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i", $content, $matches);
        if(isset($matches[2])){
                $retimg=$matches[2];
                unset($matches);
                return $retimg;
        }
        //非标准的src=xxxxx 写法
        unset($matches);
        $matches=null;
        preg_match("/<\s*img\s+[^>]*?src\s*=\s*(.*?)[\s\"\'>][^>]*?\/?\s*>/i", $content, $matches);
        if(isset($matches[1])){
                $retimg=$matches[1];
        }
        unset($matches);
        return $retimg;
 }
}
?>