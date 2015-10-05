<?php
//核心代码开始
error_reporting(E_ALL ^ E_NOTICE);
include 'config.php';
$sql = "CREATE TABLE ".$table."(
".$uidl." int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(".$uidl."),
".$userl." varchar(255),
".$psdl." varchar(255),
".$regipl." varchar(255),
".$saltl." varchar(255),
".$macl." varchar(255)
)";
$mysqli=new mysqli($db_host,$db_user,$db_psw,$db_name,$db_port);
if(mysqli_connect_errno())
{
    echo mysqli_connect_error();
    exit;
}
$result=mysqli_query($mysqli,$sql);  
if(!$result)  
{  
echo"Wrong";
exit;  
}
echo"yes";
//核心代码结束
?>