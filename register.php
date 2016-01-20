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
    exit(0);
}
if($usecn=false&&!eregi('^[A-Za-z0-9_]$',$name))die("bee9");
if(isset($_GET['id']))
{
    session_id(md5(md5($_GET['id'])));
}else{
    echo "bee5";
    exit(0);
}
session_start();
if($_SESSION['check'] !=$showing||$showing=="")
{
    echo"bee6";
    session_destroy();
    exit(0);
}
if($name==""|| $password=="")  
{  
    echo"bee1"; 
    session_destroy();
    exit(0);
}  
elseif($password!=$pwd_again)  
{  
    echo"bee2"; 
    session_destroy();
    exit(0);  
      
}  
else  
{  
    $sql2="SELECT COUNT(*) AS count FROM ".$table." WHERE ".$macl."=?"; 
    $stmt2=$mysqli->prepare($sql2);
    $stmt2->bind_param("s",$mac);
    $stmt2->execute();
	$count=count($stmt2->get_result()->fetch_all(MYSQLI_ASSOC));
        if ($count>$maxreg-1)
        {
            echo"bee3";
            session_destroy();
            exit(0);    
        }
        $sql3="SELECT COUNT(*) AS count FROM ".$table." WHERE ".$regipl."=?";
        $stmt3=$mysqli->prepare($sql3);
        $stmt3->bind_param("s",$regip);
        $stmt3->execute();
        $count2=count($stmt3->get_result()->fetch_all(MYSQLI_ASSOC));
        if ($count2>$maxreg-1)
        {
            echo"bee3";
            session_destroy();
            exit(0);    
        }
        $sql1= "SELECT * FROM ".$table." WHERE ".$userl."=?"; 
        $stmt1=$mysqli->prepare($sql1);
        $stmt1->bind_param("s",$name);
        $stmt1->execute();
        $count3=count($stmt1->get_result()->fetch_all(MYSQLI_ASSOC));                                 
        if ($count3>0)
        {                                                             
            echo "bee0";  
            session_destroy();
            exit(0);
        }
        $password=md5(md5($password).$salt);
        $sql="INSERT INTO ".$table." (".$userl.", ".$psdl.", ".$regipl.", ".$saltl.", ".$macl.") VALUES (?,?,?,?,?)";
        $stmt4 = $mysqli->prepare($sql);
        $stmt4->bind_param('sssss', $name, $password, $regip,$salt,$mac);
        if(!$stmt4->execute())
        {  
            echo"bee4";
            session_destroy();
            exit(0);  
        }  
        else   
        {  
            echo"ok";
            session_destroy();
        }  
    }  
//核心代码结束
?>  