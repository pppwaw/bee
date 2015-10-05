<?php
//核心代码开始
error_reporting(E_ALL ^ E_NOTICE);
include 'config.php';
$mysqli=new mysqli($db_host,$db_user,$db_psw,$db_name,$db_port);
function saltgen( $length = 6 ) {
    $chars = 'abcdefghijklmnopqrstuvwxyz012345678901234567890123456789';
    $password = '';
    for ( $i = 0; $i < $length; $i++ ) 
    {
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }

    return $password;
}
$name=htmlspecialchars($_GET['username']);  
$pwd_again=htmlspecialchars($_GET['pwd_again']);  
$salt=saltgen();
$password=htmlspecialchars($_GET['password']); 
$mac=htmlspecialchars($_GET['mac']);
$regip=htmlspecialchars($_GET['regip']);
$showing=$_GET['code'];
if(mysqli_connect_errno())
{
    echo mysqli_connect_error();
    exit;
}
if(!isset($_GET['id']))
{
    session_id($_GET['id']);
}else{
    echo "bee5";
    exit;
}
session_start();
if($_SESSION['check'] !=$showing){
    echo"bee6";
    session_destroy();
    exit;
}
if($name==""|| $password=="")  
{  
    echo"bee1"; 
    session_destroy();
    exit;
}  
elseif($password!=$pwd_again)  
{  
    echo"bee2"; 
    session_destroy();
    exit;  
      
}  
else  
{  
    $sql2="SELECT COUNT(*) AS count FROM ".$table." WHERE ".$macl."='$mac'"; 
    $result1=mysqli_query($mysqli,$sql2);
	$count=mysqli_affected_rows($mysqli);
        if ($count>$maxreg-1)
        {
            echo"bee3";
            session_destroy();
            exit ();    
        }
        $sql3="SELECT COUNT(*) AS count FROM ".$table." WHERE ".$regipl."='$regip'"; 
		$result2=mysqli_query($mysqli,$sql3);
        $count2=mysqli_affected_rows($mysqli); 
        if ($count2>$maxreg-1)
        {
            echo"bee3";
            session_destroy();
            exit ();    
        }
        $sql1= "SELECT * FROM ".$table." WHERE ".$userl."='$name'"; 
        $rs=mysqli_query($mysqli,$sql1);                                          
        $row=mysqli_affected_rows($mysqli);                                    
        if ($row)
        {                                                             
            echo "bee0";  
            session_destroy();
            exit ();
        }
        $password=md5(md5($password).$salt);
        $sql="INSERT INTO ".$table." (".$userl.", ".$psdl.", ".$regipl.", ".$saltl.", ".$macl.") VALUES ('$name','$password','$regip','$salt','$mac')";
        $result=mysqli_query($mysqli,$sql);  
        if(!$result)  
        {  
            echo"bee4";
            session_destroy();
            exit;  
        }  
        else   
        {  
            echo"ok";
            session_destroy();
        }  
    }  
//核心代码结束
?>  