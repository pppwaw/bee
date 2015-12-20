<?php
if(isset($_GET['n'])){
    session_id(md5(md5($_GET['n'])));
    session_start();
    echo $_SESSION['ips'];
}
?>