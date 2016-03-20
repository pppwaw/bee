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
$username=htmlspecialchars($_REQUEST['username']);
$showing=$_REQUEST['code'];
if($_SESSION['wrongcount']>=$MaxWorngTime&&$enableCodeAfterWorng){
	if(!isset($_REQUEST['code'])||!isset($_SESSION['check'])){
			echo 'PleaseUsingCode';
			exit(0);
	}
	$loginsec=true;
}
$sql="SELECT ".$saltl.",".$psdl." FROM `".$table."` where `".$userl."`=?";
$stmt=$mysqli->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($salt, $password);
$stmt->fetch();
$psd = md5(md5($_REQUEST['psd']).$salt);
if(($loginsec==true)&&($_SESSION['check'] !=$showing||$showing==""||!isset($_SESSION['check'])))
{
	unset(_SESSION['check']);
    echo"unsec";
    exit(0);
}
session_id(md5(md5($_REQUEST['username'])));
session_start();
if($psd==$password)
{
    $_SESSION['islogin'] = "yes";
    $_SESSION['ip'] = $_REQUEST["ip"];
	unset($_SESSION['wrongcount']);
    echo "yes";  
}
else 
{
	$_SESSION['wrongcount']=$_SESSION['wrongcount']+1
    echo 'no';
}
//核心代码结束
?>