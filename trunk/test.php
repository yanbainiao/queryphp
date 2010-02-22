<?php
include("framework/model.php");
include("framework/model.function.php");

//M(FIX."confweb");
//exit;
$site=M("supply");
//$beian=M('beian');
//$beian->analyseTable();
//$beian->setFieldvalue(array("aaa","bbbb"));
//print_r($site->get(12,10,'asc'));
$booktype=M("booktype");
//print_r($booktype=M("booktype")->getAll('FETCH_OBJ'));
//print_r($booktype->get(1,2,3,'FETCH_OBJ')->record);
echo $booktype->fetch('FETCH_OBJ')->up()->bookid;
print_r($booktype->data);
echo $booktype->classname;
$booktype->User->name;
//$booktype->save();
//$beian->userid=2;
//$beian->language=1;
//print_r($beian->data);
//echo $beian->save()->pkid();
//echo($beian->pkid(69)->delete());
//echo $beian->Totalnum();
//print_r($beian->getAll("userid,language")->record);
//print_r($beian->whereUseridAndLanguage('1','5')->fetch()->record);
//print_r($beian->get('confid,userid,language',53,54,'asc')->record);
//initModelclass(FIX."confweb");
//echo $beian->confid;
//$beian->colupdate('tplid');

?>