<?php
if(isset($_GET['ip'])&&isset($_GET['n'])){
    session_id(md5(md5($_GET['n'])));
    session_start();
    $_SESSION['ips'] = $_GET['ip'];
}
?>