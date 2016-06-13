<?php
if(isset($_REQUEST['n'])){
    session_id(md5(md5($_REQUEST['n'])));
    session_start();
    echo json_encode(Array("ip"=>$_SESSION['ips'],"userName"=>$_REQUEST['n']));
}
?>