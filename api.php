<?php
ini_set('display_errors','1');

$beeLoginTable="acode_ucenter_members"; 			//选择相应的表

$uidColumn="uid";				//选择用户UID所在的列，注意不是UUID，这个UID仅用于插件内部

$userColumn="username"; 			//选择用户名所在的列

$passwordColumn="password"; 			//选择密码所在列

$regIpColumn="regip"; 			//选择用户注册ip所在列

$saltColumn="salt"; 				//选择salt(密码散列)所在列

$emailColumn="email"; 				//选择用户注册mac所在列
try{
    error_reporting(E_ALL ^ E_NOTICE);
    include_once("data/config.php");
    set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
    if (0 === error_reporting()) {
        return false;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    });
    $queryString = $_SERVER['QUERY_STRING'];
    $queryJSON = base64_decode($queryString);
    $_QUERY = json_decode($queryJSON,true);
    $result = init($_QUERY);
    echo $result;
    return $result;
}catch(Exception $e){
    $error = json_encode(Array("result"=>false,"reason"=>10500,"reasonHuman"=>$_MESSAGE[10500].$e->getMessage()));
    echo $error;
    return $error;
}
function init($_QUERY){
    global $_MESSAGE,$serverPassword;
    $action = $_QUERY['action'];
    switch ($action) {
        case 'login':
            return login_init($_QUERY['info']);
            break;
        case 'register':
            return register_init($_QUERY['info']);
            break;
        case 'getIp':
            // code...
            break;
        case 'sendIp':
            // code...
            break;
        case 'checkIfPasswordRight':
            if($_QUERY['serverPassword']!=$serverPassword)
                return json_encode(Array('result'=>false,"reason"=>10602,"reasonHuman"=>$_MESSAGE[10602]));
            return checkIfPasswordRight($_QUERY['info']);
            break;
        case 'checkIfUserReistered':
            return checkIfPasswordRight($_QUERY['info']['username']);
            break;
        case 'checkIfEmailRight':
            return checkIfEmailRight($_QUERY['info']['username'],$_QUERY['info']['email']);
            break;
        case 'getVerifyCode':
            require 'libraries/class.verifycode.php';
            YL_Security_Secoder::$useNoise = true;  //是否启用噪点  
            YL_Security_Secoder::$useCurve = true;   //是否启用干扰曲线
            YL_Security_Secoder::entry($_QUERY['info']['id']);
            return null;
            break;
        case 'forgetPassword':
            return forgetPassword_init($_QUERY['info']);
            break;
        case 'getId':
            return json_encode(Array("result"=>true,"id"=>codeGen(32)));
            break;
        
        default:
            return json_encode(Array("result"=>false,"reason"=>10000,"reasonHuman"=>$_MESSAGE[10000]));
            break;
    }
}


function mysql_init(){
    global $mysqli,$db_host,$db_user,$db_psw,$db_name,$db_port;
    $mysqli = new mysqli($db_host,$db_user,$db_psw,$db_name,$db_port);
    if(mysqli_connect_errno())
        return json_encode(Array("result"=>false,"reason"=>13306,"reasonHuman"=>$_MESSAGE[13306].mysqli_connect_error()));
    return true;
}

function session_init($sessionId){
    session_id(md5(md5($sessionId)));
    session_start();
}

function isPasswordUsingMd5(){
    global $usingMd5edPassword,$_QUERY;
    if($usingMd5edPassword||$_QUERY['isPasswordUsingMd5'])
        return true;
    return false;
}

function login_init($_LOGIN){
    global $_MESSAGE,$maxWorngTime,$enableCodeAfterWorng,$usingMod;
    $verifyCode = $_LOGIN['verifyCode'];
    session_init(md5(md5($_LOGIN['username'])));
    if($_SESSION['wrongCount']>$maxWorngTime&&$enableCodeAfterWorng){
        if(empty($verifyCode)||empty($_SESSION['verifyCode'])){
            return json_encode(Array("result"=>false,"reason"=>10001,"reasonHuman"=>$_MESSAGE[10001]));
        }
        $usingVerifyCode=true;
    }
    if(($usingVerifyCode==true)&&($_SESSION['verifyCode']!=$verifyCode||$verifyCode==""||!isset($_SESSION['verifyCode']))){
        unset($_SESSION['verifyCode']);
        return json_encode(Array("result"=>false,"reason"=>10002,"reasonHuman"=>$_MESSAGE[10002]));
    }
    $resultJSON = checkPassword_init($_LOGIN);
    $_RESULT = json_decode($resultJSON,true);
    if($_RESULT['result']){
        $_SESSION['isLoged'] = true;
        $_SESSION['loginIp'] = $_LOGIN['ip'];
        unset($_SESSION['wrongCount']);
        if($usingMod){
            $_SESSION['token'] = tokengen();
            $_RESULT['useToken']=true;
            $_RESULT['token']=$_SESSION['token'];
        }else{
            $_RESULT['useToken']=false;
        }
        return json_encode($_RESULT);
    }else{
        $_SESSION['wrongcount']=$_SESSION['wrongcount']+1;
        return $resultJSON;
    }
}

function register_init($_REGISTER){
    global $verifyEmail,$_MESSAGE,$userColumn,$saltColumn,$passwordColumn,$beeLoginTable,$mysqli;
    session_init($_REGISTER['id']);
    $verifyCode = $_REGISTER['verifyCode'];
    if($_SESSION['verifyCode']!=$verifyCode||empty($verifyCode)||empty($_SESSION['verifyCode'])){
        unset($_SESSION['verifyCode']);
        unset($_SESSION['email']);
        return json_encode(Array("result"=>false,"reason"=>10102,"reasonHuman"=>$_MESSAGE[10102]));
    }
    if($verifyEmail&&(empty($_REGISTER['emailVerifyCode'])||empty($_SESSION['emailVerifyCode'])||$_SESSION['emailVerifyCode']!=$_REGISTER['emailVerifyCode'])){
        $_SESSION['emailVerifyCode']=codeGen(8);
        $_SESSION['email']=$_REGISTER['email'];
        return sendVerifyEmail_init(Array('email'=>$_REGISTER['email'],'username'=>$_REGISTER['username'],'verifyCode'=>$_SESSION['emailVerifyCode'],'reason'=>$_MESSAGE['register']));
    }
    if($_SESSION['email']!=$_REGISTER['email']){
        unset($_SESSION['verifyCode']);
        return json_encode(Array("result"=>false,"reason"=>10102,"reasonHuman"=>$_MESSAGE[10102]));
    }
    $mysql = mysql_init();
    if(!$mysql){
        unset($_SESSION['verifyCode']);
        return $mysql;
    }
    if(empty($_REGISTER['username'])||empty($_REGISTER['password'])){
        unset($_SESSION['verifyCode']);
        return json_encode(Array("result"=>false,"reason"=>10103,"reasonHuman"=>$_MESSAGE[10103]));
    }
    $sqlAccountCount="SELECT * FROM ".$beeLoginTable." WHERE ".$emailColumn."=? or".$regIpColumn."=?"; 
    $stmtAccountCount=$mysqli->prepare($sqlAccountCount);
    $stmtAccountCount->bind_param("ss",$_REGISTER['email'],$_REGISTER['ip']);
    $stmtAccountCount->execute();
    $stmtAccountCount->store_result();
    if ($stmtAccountCount->num_rows>$maxreg-1)
    {
        unset($_SESSION['verifyCode']);
        return json_encode(Array("result"=>false,"reason"=>10104,"reasonHuman"=>$_MESSAGE[10104]));
    }
    if(hasRegistered($_REGISTER['username'],true)){
        unset($_SESSION['verifyCode']);
        return json_encode(Array("result"=>false,"reason"=>10101,"reasonHuman"=>$_MESSAGE[10101]));
    }
    $password = $_REGISTER['password'];
    if(!isPasswordUsingMd5()){
        $password = md5($password);
    }
    $salt = codeGen(6);
    $password = md5($password.$salt);
    $sqlRegister="INSERT INTO ".$beeLoginTable." (".$userColumn.", ".$passwordColumn.", ".$regIpColumn.", ".$saltColumn.", ".$emailColumn.") VALUES (?,?,?,?,?)";
    $stmtRegister = $mysqli->prepare($sql);
    $stmtRegister->bind_param('sssss', $_REGISTER['username'], $password, $_REGISTER['ip'],$salt,$_REGISTER['email']);
    session_destroy();
    $result = $stmtRegister->execute();
    $stmtRegister->close();
    if($result)
    {
        return json_encode(Array("result"=>true,"reason"=>10100,"reasonHuman"=>$_MESSAGE[10100]));
    }
    return json_encode(Array("result"=>false,"reason"=>13306,"reasonHuman"=>$_MESSAGE[13306].$mysqli->error));
}

function hasRegistered($username,$hasMysqlInited=false){
    global $_MESSAGE,$userColumn,$saltColumn,$passwordColumn,$beeLoginTable,$mysqli;
    if(!$hasMysqlInited){
        $mysql = mysql_init();
        if(!$mysql){
            return $mysql;
        }
    }
    $stmtAccountCount->close();
    $sqlCheckIfReg= "SELECT * FROM ".$beeLoginTable." WHERE ".$userColumn."=?"; 
    $stmtCheckIfReg=$mysqli->prepare($sqlCheckIfReg);
    $stmtCheckIfReg->bind_param("s",$name);
    $stmtCheckIfReg->execute();
    $stmtCheckIfReg->store_result();
    $count=$stmtCheckIfReg->num_rows;
    $stmtCheckIfReg->close();
    if ($count>0)
    {
        return false;
    }
    return true;
}

function sendVerifyEmail_init($_VERFINFO){
    global $smtpHost,$smtpAuth,$smtpUsername,$smtpPassword,$smtpSecure,$smtpPort,$smtpEmailAddress,$smtpAuthor,$smtpUsingHtml,$smtpSubject,$smtpBody,$smtpNonHtmlBody,$_MESSAGE;
    $smtpBody = str_replace("%reason%",$_VERFINFO['reason'],$smtpBody);
    $smtpBody = str_replace("%verifyCode%",$_VERFINFO['verifyCode'],$smtpBody);
    
    require 'libraries/class.phpmailer.php';

    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = $smtpAuth;
    $mail->Username = $smtpUsername;
    $mail->Password = $smtpPassword;
    $mail->SMTPSecure = $smtpSecure;
    $mail->Port = $smtpPort;
    $mail->setFrom($smtpEmailAddress, $smtpAuthor);
    $mail->addAddress($_VERFINFO['email'], $_VERFINFO['username']);
    $mail->isHTML($smtpUsingHtml);
    
    $mail->Subject = $smtpSubject;
    $mail->Body    = $smtpBody;
    $mail->AltBody = $smtpNonHtmlBody;
    
    if(!$mail->send()) {
        return json_encode(Array("result"=>false,"reason"=>10301,"reasonHuman"=>$_MESSAGE[10301].$mail->ErrorInfo));
    } else {
        return json_encode(Array("result"=>true,"reason"=>10300,"reasonHuman"=>$_MESSAGE[10300]));
    }
}

function forgetPassword_init($_ACCOUNTINFO){
    global $verifyEmail;
    if(!$verifyEmail){
        return json_encode(Array("result"=>false,"reason"=>10701,"reasonHuman"=>$_MESSAGE[10701]));
    }
    session_init($_ACCOUNTINFO['username']);
    $verifyCode = $_ACCOUNTINFO['verifyCode'];
    if($_SESSION['verifyCode']!=$verifyCode||empty($verifyCode)||empty($_SESSION['verifyCode'])){
        unset($_SESSION['verifyCode']);
        unset($_SESSION['email']);
        return json_encode(Array("result"=>false,"reason"=>10102,"reasonHuman"=>$_MESSAGE[10102]));
    }
    $checkResult = checkIfEmailRight($_ACCOUNTINFO['username'],$_ACCOUNTINFO['email']);
    $resultArray = json_decode($checkResult);
    $result = $resultArray['result'];
    if(!$result){
        return $checkResult;
    }
    if($verifyEmail&&(empty($_ACCOUNTINFO['emailVerifyCode'])||empty($_SESSION['emailVerifyCode'])||$_SESSION['emailVerifyCode']!=$_ACCOUNTINFO['emailVerifyCode'])){
        $_SESSION['emailVerifyCode']=codeGen(8);
        $_SESSION['email']=$_ACCOUNTINFO['email'];
        return sendVerifyEmail_init(Array('email'=>$_ACCOUNTINFO['email'],'username'=>$_ACCOUNTINFO['username'],'verifyCode'=>$_SESSION['emailVerifyCode'],'reason'=>$_MESSAGE['changePassword']));
    }
    $mysql = mysql_init();
    if(!$mysql){
        return $mysql;
    }
    return changePassword_init($_ACCOUNTINFO,true);
}

function changePassword_init($_CHANGEINFO,$forceChange,$hasMysqlInited = false){
    global $_MESSAGE,$userColumn,$saltColumn,$passwordColumn,$beeLoginTable,$mysqli;
    if(!$hasMysqlInited){
        $mysql = mysql_init();
        if(!$mysql){
            return $mysql;
        }
    }
    if(!$forceChange){
        $resultJSON = checkPassword_init($_CHANGEINFO['loginInfo']);
        $result = json_decode($resultJSON,true)['result'];
    }else{
        $result = true;
    }
    if($result){
        $username = $_CHANGEINFO['username'];
        $password = $_CHANGEINFO['password'];
        if(!isPasswordUsingMd5()){
            $password = md5(password);
        }
        $salt = codeGen(6);
        $stmtAccountCount->close();
        $sqlChangePassword="UPDATE $beeLoginTable SET $passwordColumn = ".md5($password.$salt).", $saltColumn = $salt WHERE $userColumn = $username"; 
        $stmtChangePassword=$mysqli->prepare($sqlChangePassword);
        $stmtChangePassword->bind_param("s",$name);
        if($stmtChangePassword->execute()){
            return json_encode(Array("result"=>true,"reason"=>10800,"reasonHuman"=>$_MESSAGE[10800]));
        }
        return json_encode(Array("result"=>false,"reason"=>13306,"reasonHuman"=>$_MESSAGE[13306].$mysqli->error));
    }else{
        return $resultJSON;
    }
}

function checkIfEmailRight($username,$email,$hasMysqlInited = false){
    global $_MESSAGE,$userColumn,$saltColumn,$passwordColumn,$beeLoginTable,$mysqli;
    if(!$hasMysqlInited){
        $mysql = mysql_init();
        if(!$mysql){
            return $mysql;
        }
    }
    $sqlGetEmail="SELECT $emailColumn FROM `".$beeLoginTable."` where `".$userColumn."`=?";
    $stmtGetEmail=$mysqli->prepare($sqlGetEmail);
    $stmtGetEmail->bind_param('s', $username);
    $stmtGetEmail->execute();
    $stmtGetEmail->bind_result($correctEmail);
    $stmtGetEmail->fetch();
    if($email==$correctEmail){
        return json_encode(Array("result"=>true,"reason"=>10900,"reasonHuman"=>$_MESSAGE[10900]));
    }else{
        return json_encode(Array("result"=>false,"reason"=>10901,"reasonHuman"=>$_MESSAGE[10901]));
    }
    
}

function checkPassword_init($_LOGIN,$hasMysqlInited = false){
    global $_MESSAGE,$userColumn,$saltColumn,$passwordColumn,$beeLoginTable,$mysqli;
    if(!$hasMysqlInited){
        $mysql = mysql_init();
        if(!$mysql){
            return $mysql;
        }
    }
    $username = $_LOGIN['username'];
    $password = $_LOGIN['password'];
    $ip = $_LOGIN['ip'];
    if(!isPasswordUsingMd5()){
        $password = md5($password);
    }
    $sqlLogin="SELECT ".$saltColumn.",".$passwordColumn." FROM `".$beeLoginTable."` where `".$userColumn."`=?";
    $stmtLogin=$mysqli->prepare($sqlLogin);
    $stmtLogin->bind_param('s', $username);
    $stmtLogin->execute();
    $stmtLogin->bind_result($salt, $correctPassword);
    $stmtLogin->fetch();
    $password = md5($password.$salt);
    if($password==$correctPassword){
        return json_encode(Array("result"=>true,"reason"=>10200,"reasonHuman"=>$_MESSAGE[10200]));
    }else{
        return json_encode(Array("result"=>false,"reason"=>10201,"reasonHuman"=>$_MESSAGE[10201]));
    }
}

function codeGen( $length = 0 ) {
    $chars = 'abcdefghijklmnopqrstuvwxyz012345678901234567890123456789';
    $password = '';
    for ( $i = 0; $i < $length; $i++ ) 
    {
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $password;
}
?>