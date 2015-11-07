<?php
//核心代码开始
error_reporting(E_ALL ^ E_NOTICE);
include 'config.php';
$mysqli=new mysqli($db_host,$db_user,$db_psw,$db_name,$db_port);
$sql1= "SELECT * FROM ".$table." WHERE ".$userl."='$name'";
$rs=mysqli_query($mysqli,$sql1);
$row=mysqli_affected_rows($mysqli);
if ($row)
{
    echo "yes";
    exit ();
}
else
{
    echo "no";
    exit();
}
?>