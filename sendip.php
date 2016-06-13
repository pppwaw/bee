<?php
include_once('config.php');
if(isset($_REQUEST['ip'])&&isset($_REQUEST['n'])){
    session_id(md5(md5($_REQUEST['n'])));
    session_start();
    $_SESSION['ips'] = $_REQUEST['ip'];
    echo json_encode(Array("result"=>true,"userName"=>$_REQUEST['n'],"ip"=>$_SESSION['ip']));
}else{
    echo json_encode(Array("result"=>false,"reason"=>0,"reasonHuman"=>$messageRequiredMoreArgs));
}
?>