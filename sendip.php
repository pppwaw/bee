<?php
if(isset($_REQUEST['ip'])&&isset($_REQUEST['n'])){
    session_id(md5(md5($_REQUEST['n'])));
    session_start();
    $_SESSION['ips'] = $_REQUEST['ip'];
}
?>