<?php
if(file_exists('data/install.lock'))
    die('<meta charset="utf-8">请使用正确的方式访问api.php！');
echo'
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
        <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <title>BeeLogin网页端安装</title>
    </head>'
    ;
if($_REQUEST['step']=1){
    echo'
    <body>
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">服务器访问密码</span>
            <input type="password" class="form-control" placeholder="密码" aria-describedby="basic-addon1">
        </div>
        
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                验证模式 <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">IP验证（经典模式）</a></li>
                <li><a href="#">Token验证（Mod模式）</a></li>
                <li><a href="#">IP+Token（混合模式，不推荐）</a></li>
            </ul>
        </div>
        
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">玩家注册上限</span>
            <input type="number" class="form-control" placeholder="2" aria-describedby="basic-addon1">
        </div>
        
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">登录密码错误次数限制</span>
            <input type="number" class="form-control" placeholder="2" aria-describedby="basic-addon1">
        </div>
        
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                超过限制后使用验证码 <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">使用</a></li>
                <li><a href="#">不使用</a></li>
            </ul>
        </div>
        
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                登录时必须使用验证码 <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">使用</a></li>
                <li><a href="#">不使用</a></li>
            </ul>
        </div>
        
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                允许使用特殊符号作为用户名 <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">允许</a></li>
                <li><a href="#">不允许</a></li>
            </ul>
        </div>
        
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                注册时验证邮箱 <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">验证</a></li>
                <li><a href="#">不验证</a></li>
            </ul>
        </div>
        
        <br>
        <br>
        <br>
        
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">数据库名</span>
            <input type="text" class="form-control" placeholder="" aria-describedby="basic-addon1">
        </div>
        
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">数据库uid列名</span>
            <input type="text" class="form-control" placeholder="uid" aria-describedby="basic-addon1">
        </div>
        
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">数据库用户名列名</span>
            <input type="text" class="form-control" placeholder="username" aria-describedby="basic-addon1">
        </div>
        
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">数据库密码列名</span>
            <input type="text" class="form-control" placeholder="password" aria-describedby="basic-addon1">
        </div>
        
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">数据库ip列名</span>
            <input type="text" class="form-control" placeholder="regip" aria-describedby="basic-addon1">
        </div>
        
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">数据库salt列名</span>
            <input type="text" class="form-control" placeholder="salt" aria-describedby="basic-addon1">
        </div>
        
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">数据库邮箱列名</span>
            <input type="text" class="form-control" placeholder="email" aria-describedby="basic-addon1">
        </div>
        
        
'
;}?>
    </body>
</html>