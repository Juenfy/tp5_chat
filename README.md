
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

2.新建tp5_chat数据库，导入数据库文件，ThinkPHP数据库配置文件默认连接数据库tp5_chat，密码root，如有不同，请自行修改

3.进入项目的extend\GatewayWorker目录下，windows直接双击start_for_win.bat或者运行[^php start.php start]开启服务，linux运行[^php start.php start -d ]以守护进程的方式开启服务，windows不支持守护进程，服务窗口关掉后服务就会关掉。

4.配置本地虚拟域名，浏览器输入域名回车，项目即可正常运行。
  
 ## 持续更新。。。
 后期会持续更新更多功能
