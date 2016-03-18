<?php
//核心代码开始
error_reporting(E_ALL ^ E_NOTICE);
include 'config.php';
session_id(md5($_REQUEST['username']));
session_start();
if ($_REQUEST['username']==""){
    echo "no";
    exit(0);
}
elseif($_REQUEST['ip']==""){
    echo "no";
    exit(0);
}
elseif(md5(md5($_SESSION['ip'])) != $_REQUEST['ip']){
    echo "no";
    exit(0);
}
elseif($_REQUEST['spsd']!=md5(md5($spsd))){
    echo "badserver";
    exit(0);
}
else
{
    if ($_SESSION['islogin']=="no"){
        $_SESSION['ischecked']="no";
        echo "no";
    }
    elseif ($_SESSION['islogin'] == "yes")
    {
        $_SESSION['ischecked'] = "yes";
		if ($usingToken){
			if ($_REQUEST['token'] == $_SESSION['token']){
				echo "yes";
			}else{
				echo "no";
			}
		}else{
			echo "yes";
		}
    }
}
//核心代码结束
?>