<?php
//核心代码开始
error_reporting(E_ALL ^ E_NOTICE);
include '../data/config.php';
session_id(md5($_GET['username']));
session_start();
if ($_GET['username']==""){
    echo "no";
    exit(0);
}
elseif($_GET['ip']==""){
    echo "badip";
    exit(0);
}
elseif(md5(md5($_SESSION['ip'])) != $_GET['ip']&&$checkIp){
    echo "no";
    exit(0);
}
elseif(md5(md5($_SESSION['token'])) != $_GET['token']&&$usingMod){
    echo "no";
    exit(0);
}
elseif($_GET['spsd']!=md5(md5($spsd))){
    echo "badserver";
    exit(0);
}
else
{
    if (!$_SESSION['isLoged']){
        echo 'no';
    }
    elseif ($_SESSION['isLoged'])
    {
        echo 'yes';
    }
}
//核心代码结束
?>