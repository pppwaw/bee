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
$name=htmlspecialchars($_REQUEST['username']);  
$pwd_again=htmlspecialchars($_REQUEST['pwd_again']);  
$salt=saltgen();
$password=htmlspecialchars($_REQUEST['password']); 
$mac=htmlspecialchars($_REQUEST['mac']);
$regip=htmlspecialchars($_REQUEST['regip']);
$showing=$_REQUEST['code'];
if(mysqli_connect_errno())
{
    echo mysqli_connect_error();
    exit(0);
}
if(isset($_REQUEST['id']))
{
    session_id(md5(md5($_REQUEST['id'])));
}else{
    echo "bee5";
    exit(0);
}
session_start();
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
    $stmt2->store_result();
    if ($stmt2->num_rows>$maxreg-1)
    {
        echo"bee3";
        session_destroy();
        exit(0);    
    }
    $stmt2->close();
    $sql3="SELECT COUNT(*) AS count FROM ".$table." WHERE ".$regipl."=?";
    $stmt3=$mysqli->prepare($sql3);
    $stmt3->bind_param("s",$regip);
    $stmt3->execute();
    $stmt3->store_result();
    if ($stmt3->num_rows>$maxreg-1)
    {
        echo"bee3";
        session_destroy();
        exit(0);    
    }
    $stmt3->close();
    $sql1= "SELECT * FROM ".$table." WHERE ".$userl."=?"; 
    $stmt1=$mysqli->prepare($sql1);
    $stmt1->bind_param("s",$name);
    $stmt1->execute();
    $stmt1->store_result();
    if ($stmt1->num_rows>0)
    {
        echo "bee0";  
        session_destroy();
        exit(0);
    }
    $stmt1->close();
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
    $stmt4->close();
}  
//核心代码结束
?>  