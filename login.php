<?php
//核心代码开始
error_reporting(E_ALL ^ E_NOTICE);
include 'config.php';
$mysqli=new mysqli($db_host,$db_user,$db_psw,$db_name,$db_port);
if(mysqli_connect_errno())
{
    echo mysqli_connect_error();
    exit;
}
$username=htmlspecialchars($_GET['username']);
$sql="SELECT * FROM ".$table." where ".$userl."='".$username."'";       
$rs=mysqli_query($mysqli,$sql);
$row=mysqli_fetch_assoc($rs);
$salt=$row[$saltl];
$password=$row[$psdl];
$psd = md5(md5($_GET['psd']).$salt);
session_id(md5(md5($_GET['username'])));
session_start();
if($psd==$password){
    $_SESSION['islogin'] = "yes";
    $_SESSION['ip'] = $_SERVER["REMOTE_ADDR"];
    echo "yes";  
}else {
    echo 'no';
}
$rs->free_result();
//核心代码结束
?>