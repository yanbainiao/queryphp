<?php
include("model.php");
include("model.function.php");
include("router.php");
include("view.php");
$config["rootpath"]=dirname(__FILE__)."/";
$dispaths =C("router")->start();
$view=C("view");
$router=R($dispaths->controller);
if (method_exists($router,$dispaths->action)) {
     call_user_func(array($router,$dispaths->action));
	 $view->display(R($dispaths->controller)->view($dispaths->action));
}
?>