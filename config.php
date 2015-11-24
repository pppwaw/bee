<?php
error_reporting(E_ALL ^ E_NOTICE);

//配置代码开始
$spsd='服务器访问密码';		//服务器访问密码

$maxreg='2';				//单个用户最大注册数

//以下选项请按照邮箱smtp服务器配置填写
$smtpserver = "SMTP服务器";			    	//SMTP服务器 

$smtpserverport = 25;						//SMTP服务器端口，默认情况下为25

$smtpusermail = "SMTP服务器的用户邮箱";	    //SMTP服务器的用户邮箱 

$smtpuser = "SMTP服务器的用户帐号";			//SMTP服务器的用户帐号

$smtppass = "SMTP服务器的用户密码";			//SMTP服务器的用户密码

$mailsubject = "BeeLogin注册激活码";		//邮件主题

$mailbody = "您的BeeLogin注册激活码是：";	//邮件内容前段

$mailbodysec = "，请在20分钟之内进行注册";  //邮件内容后段

$mailtype = "TXT";							//邮件格式（HTML/TXT）,TXT为文本邮件 

//以上选项请按照邮箱smtp服务器配置填写

//以下选项请按照Mysql配置填写
$db_host="数据库地址"; 		//连接的数据库地址

$db_port="数据库端口"; 		//连接的数据库端口，默认情况下为3306

$db_user="数据库用户名"; 	//连接数据库的用户名

$db_psw="数据库密码";		//连接数据库的密码

$db_name="数据库名称"; 		//连接的数据库名称
//以上选项请按照Mysql配置填写

//以下选项如不明白或不完全清楚请保持默认
$table="BeeLogin"; 			//选择相应的表

$uidl="uid";				//选择用户UID所在的列，注意不是UUID，这个UID仅用于插件内部

$userl="username"; 			//选择用户名所在的列

$psdl="password"; 			//选择密码所在列

$regipl="regip"; 			//选择用户注册ip所在列

$saltl="salt"; 				//选择salt(密码散列)所在列

$macl="mac"; 				//选择用户注册mac所在列
//以上选项如不明白或不完全清楚请保持默认

//配置代码结束
?>