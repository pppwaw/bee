<?php
//核心代码开始
error_reporting(E_ALL ^ E_NOTICE);

$hc='<meta charset="utf-8">';//htmlCharset
if(file_exists('install.lock'))exit($hc.'请删除当前目录下的install.lock后继续安装');

include '../data/config.php';

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
    exit($hc.'数据表已经被建立了,请尝试修改数据表名');  
}else{
    echo 'yes<br>';
    $f=@fopen('install.lock','w');
    if(!$f){
        exit($hc.'锁定文件创建失败,为保证安全,请手动删除install.php!');
    }else{
        fwrite($f,time());
        fclose($f);
    }
}
//核心代码结束
?>