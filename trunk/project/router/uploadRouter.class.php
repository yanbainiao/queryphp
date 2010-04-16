<?php
class uploadRouter extends controller{
  function index()
  {

  }
  function webimages()
  {
    print_r($_FILES);
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
		        "uploadsize"=>420000
	            )
	      )->setBasename($_FILES['upload']['name'],true)->init();//,'size_ico','auto_ico','fix_ico','fill_size''fix_side'
	if($img->upload(array('fix_side')))
	{
	  echo("上传成功");
	}else{
	  echo("上传失败");
	  echo $img->message;
	}
	return false;
  }
  /*
  *验证码测试
  */
 function testverfier() {
 	
	echo '<FORM METHOD=POST ACTION="'.url_for("upload/show",true).'">';	
	echo C("imgcode")->getInputHTML(); //输出输入验证码输入框
	echo C("imgcode")->getImgHTML(url_for("upload/verifier",true));//输出验证码
	echo '<INPUT TYPE="submit">';
	echo '</FORM>';
	Return false;
 }
 /*
 *输出中文验证码，要自己拷贝一个ttf字体到
 *framework/config目录 命名为mask.TTF
 */
 function verifier() {
	session_start();
 	C("imgcode")->cncode();	
	Return false;
 }
 /*
 *测试提交验证码是正确
 */
 function show() {
	 session_start();
 	echo($_SESSION['verifier']);
	if(C("imgcode")->checkcode($_POST['checkcode']))
	{
	  echo "通过";
	}else{
	  echo "验证失败";
	}
	Return false;
 }
}
?>