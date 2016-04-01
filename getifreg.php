<?php
//核心代码开始
error_reporting(E_ALL ^ E_NOTICE);
include 'config.php';
$mysqli=new mysqli($db_host,$db_user,$db_psw,$db_name,$db_port);
$name=htmlspecialchars($_REQUEST['username']);  
$sql1= "SELECT * FROM `".$table."` WHERE `".$userl."`=?"; 
$stmt1=$mysqli->prepare($sql1);
$stmt1->bind_param("s",$name);
$stmt1->execute();
$stmt1->store_result();
$stmt1->num_rows;
$count=$stmt1->num_rows;
if ($count>0)
{
    echo "yes";
    exit (0);
}
else
{
    echo "no";
    exit(0);
}
//核心代码结束
?>