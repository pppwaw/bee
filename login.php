<?php
//核心代码开始
error_reporting(E_ALL ^ E_NOTICE);
include 'config.php';
$mysqli=new mysqli($db_host,$db_user,$db_psw,$db_name,$db_port);
if(mysqli_connect_errno())
{
    echo mysqli_connect_error();
    exit(0);
}
$username=htmlspecialchars($_GET['username']);
$sql="SELECT * FROM `".$table."` where `".$userl."`=?";
$stmt=$mysqli->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$row=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$salt=$row[0][$saltl];
$password=$row[0][$psdl];
$psd = md5(md5($_GET['psd']).$salt);
session_id(md5(md5($_GET['username'])));
session_start();
if($psd==$password)
{
    $_SESSION['islogin'] = "yes";
    $_SESSION['ip'] = $_GET["ip"];
    echo "yes";  
}
else 
{
    echo 'no';
}
//核心代码结束
?>