<?php 
error_reporting(E_ALL ^ E_NOTICE);
include 'config.php';
require("smtp.php"); 
function saltgen( $length = 6 ) {
    $chars = 'abcdefghijklmnopqrstuvwxyz012345678901234567890123456789';
    $password = '';
    for ( $i = 0; $i < $length; $i++ ) 
    {
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $password;
}
if(isset($_GET['id']))
{
    session_id($_GET['id']);
}else{
    echo "beemail0";
    exit(0);
}
session_start();
if($mailmode==true){
    echo "unopen";
    $_SESSION['mailcheck'] = "false";
    exit(0);
}
if($_SESSION['check'] !=$showing||$showing=="")
{
    echo "beemail1";
    session_destroy();
    exit(0);
}
if(isset($_GET['mail']))
{
    $smtpemailto = $_GET['mail'];
}else{
    echo "beemail2";
    exit(0);
}
$code = saltgen(16);
$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
$smtp->debug = FALSE;
$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody.$code.$mailbodysec, $mailtype); 
$_SESSION['mailcheck'] = $code;
?>