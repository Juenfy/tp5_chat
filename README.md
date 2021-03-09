
简易聊天室
===============

[![Php Version](https://img.shields.io/badge/php-%3E=7.1.0-brightgreen.svg?maxAge=2592000&color=yellow)](https://github.com/php/php-src)
[![Mysql Version](https://img.shields.io/badge/mysql-%3E=5.5-brightgreen.svg?maxAge=2592000&color=orange)](https://www.mysql.com/)
[![Thinkphp Version](https://img.shields.io/badge/thinkphp-%3E=5.0-brightgreen.svg?maxAge=2592000)](https://github.com/top-think/framework/tree/master)
[![GateWayWorker Version](https://img.shields.io/badge/gatewayworker-%3E=3.0-brightgreen.svg?maxAge=2592000&color=critical)](https://github.com/walkor/GatewayWorker)
[![GateWayWorker Doc](https://img.shields.io/badge/docs-passing-green.svg?maxAge=2592000)](http://www.workerman.net/gatewaydoc/)
[![GatewayClient Version](https://img.shields.io/badge/gatewayclient-%3E=3.0-brightgreen.svg?maxAge=2592000&color=ff69b4)](https://github.com/walkor/GatewayClient)
[![Layui Version](https://img.shields.io/badge/layui-=2.5.5-brightgreen.svg?maxAge=2592000&color=critical)](https://github.com/sentsin/layui)

## 项目介绍

基于ThinkPHP5.0+GateWayWorker+layui开发的简易聊天室

## 图片预览
![私聊](public/preview/preview01.png?raw=true)
![!新建群聊](public/preview/preview02.png?raw=true)
![!邀请好友](public/preview/preview03.png?raw=true)
## 项目特性

* 实时
    * 更新消息徽章
    * 聊天列表顶置
	* 消息提示
	* 好友上线离线通知
	* 上线离线状态更新
* 功能
    * 私聊、群聊
    * 新建群聊
    * 邀请用户进群  
    * 支持文本消息、qq表情、图片发送
    * 聊天记录持久化

## 项目运行说明

1.在服务器根路径克隆项目并安装相关依赖

~~~shell
git clone https://gitee.com/Juenfy/tp5_chat.git
~~~

~~~shell
cd tp5_chat
~~~

~~~shell
composer install
~~~

~~~shell
cd extend
~~~

~~~shell
git clone https://github.com/walkor/GatewayClient.git
~~~

2.修改thinkphp里的start.php，默认绑定index模块
~~~php
// ThinkPHP 引导文件
// 1. 加载基础文件
require __DIR__ . '/base.php';

//添加这一行代码绑定index模块
Route::bind('index');

// 2. 执行应用
App::run()->send();
~~~

3.新建tp5_chat数据库，导入数据库文件，ThinkPHP数据库配置文件默认连接数据库tp5_chat，密码root，如有不同，请自行修改

4.开启GatewayWorker服务

windows:
~~~textmate
直接双击extend/GatewayWorker里的start_for_win.bat
~~~
linux:
~~~shell
php extend/GatewayWorker/start.php start -d
~~~

5.配置本地虚拟域名，浏览器输入域名回车，项目即可正常运行。
  
 ## 持续更新。。。
 后期会持续更新更多功能
