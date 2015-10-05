#BeeLogin网页端
网页端所需环境：<br>
1.支持Mysqli的php<br>
2.Mysql数据库<br>

客户端所需环境：<br>
1.没有被DNS劫持/污染（如果有请尝试修改其DNS服务器或者HOSTS）<br>

服务端所需环境：<br>
1.BeeLogin插件<br>
2.1.7.2 r0.2以上的服务端核心文件（1.7.2以下未经测试），另外推荐使用spigot服务端<br>

安装步骤：<br>
1.依照config.php文件中的提示修改相关参数<br>
2.将所有文件上传至网页端，如果您使用github、coding等git平台，请在新建项目时通过项目导入仓库复制代码<br>Tips:使用coding可以直接点击右上角Fork，github的并不清楚<br>
3.在浏览器中访问install.php，不出意外您将看见“yes”，如果出现“Wrong”，说明数据表已经被建立了，请尝试修改数据表名<br>
4.***重要*** 删除install.php ***重要***<br>
5.修改启动器或者使用LSK编写的BeeLauncher启动器（暂未发布）以便支持网页端<br>