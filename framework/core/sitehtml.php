<?php
/*
*生成html文件类
*
*
*/ 
class sitehtml {
 static public function realhtml($content,$fileurl) { 	  
	  $filename=filename_safe(basename($fileurl));
	  $filename=substr($filename,0,-strlen($GLOBALS['config']['html'])).$GLOBALS['config']['html'];
	  try{
        mkdir(dirname($fileurl),0777,true);
	    file_put_contents(dirname($fileurl)."/".$filename,$content);
	  }catch (PDOException $e) 
		{
		   throw new mylog('html ['.$e->getMessage()."]".$fileurl,0011);
		}
 }
}
?>