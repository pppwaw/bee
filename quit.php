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
else{
    session_destroy();
    echo "yes";
}
//核心代码结束
?>
