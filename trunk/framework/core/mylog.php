<?php
class mylog extends Exception {
    // 重定义构造器使 message 变为必须被指定的属性
    public function __construct($message, $code = 0) {
        // 自定义的代码
        // 确保所有变量都被正确赋值
        parent::__construct($message, $code);
		ob_start();
		print_r($GLOBALS); 
		$str="\n-----------------------------------".$this->getCode()."----------------------------------------\n";
		file_put_contents(P("frameworkpath")."log/".date("Y_m_d").".txt", $str.ob_get_clean().$this->__toString().$str, FILE_APPEND);
    }
  public function __toString() {
    return "\n---------------- '".$this->getMessage()."' File: ".$this->getFile()." Line:".$this->getLine()."\nStack trace:\n".$this->getTraceAsString();
  }
}

?>