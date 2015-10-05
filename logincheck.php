<?php
//核心代码开始
error_reporting(E_ALL ^ E_NOTICE);
include 'config.php';
session_id(md5($_GET['username']));
session_start();
if ($_GET['username']==""){
    echo "no";
    exit;
}
elseif($_GET['ip']==""){
    echo "no";
    exit;
}
elseif(md5(md5($_SESSION['ip'])) != $_GET['ip']){
    echo "no";
    exit;
}
elseif($_GET['spsd']!=md5(md5($spsd))){
    echo "badserver";
    exit;
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
        echo "yes";
    }
}
//核心代码结束
?>