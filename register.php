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
if(isset($_GET['id']))
{
    session_id($_GET['id']);
}else{
    echo "bee5";
    exit;
}
session_start();
if($_SESSION['check'] !=$showing||$showing=="")
{
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
    $sql2="SELECT COUNT(*) AS count FROM ".$table." WHERE ".$macl."=?"; 
    $stmt2=$mysqli->prepare($sql2);
    $stmt2->bind_param("s",$mac);
    $stmt2->execute();
    $result1=$stmt2->get_result();
    $resultdata1=$result1->fetch_all(MYSQLI_ASSOC);
	$count=count($resultdata1);
        if ($count>$maxreg-1)
        {
            echo"bee3";
            session_destroy();
            exit ();    
        }
        $sql3="SELECT COUNT(*) AS count FROM ".$table." WHERE ".$regipl."=?";
        $stmt3=$mysqli->prepare($sql3);
        $stmt3->bind_param("s",$regip);
        $stmt3->execute();
        $result2=$stmt3->get_result();
        $resultdata2=$result2->fetch_all(MYSQLI_ASSOC);
        $count2=count($resultdata2);
        if ($count2>$maxreg-1)
        {
            echo"bee3";
            session_destroy();
            exit ();    
        }
        $sql1= "SELECT * FROM ".$table." WHERE ".$userl."=?"; 
        $stmt1=$mysqli->prepare($sql1);
        $stmt1->bind_param("s",$name);
        $stmt1->execute();
        $result3=$stmt1->get_result();
        $resultdata3=$result3->fetch_all(MYSQLI_ASSOC);
        $count3=count($resultdata3);                                 
        if ($count3>0)
        {                                                             
            echo "bee0";  
            session_destroy();
            exit ();
        }
        $password=md5(md5($password).$salt);
        $sql="INSERT INTO ".$table." (".$userl.", ".$psdl.", ".$regipl.", ".$saltl.", ".$macl.") VALUES (?,?,?,?,?)";
        $stmt4 = $mysqli->prepare($sql);
        $stmt4->bind_param('sssss', $name, $password, $regip,$salt,$mac);
        $stmt4->execute();
        if($stmt4->affected_rows)  
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