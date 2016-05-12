[TOC]
自己配了下试了试，发现速度比goagent快了不止一点，当然和hosts相比，有些hosts的ip飞速，有些龟速，但是hosts一般是没法观看youtube的。
# 配置环境准备

##[美国低价VPS购买和使用教程][1]

# 服务器配置

##首先确保安装了python2.6或者2.7：

检查版本：
```python --version```
##安装软件包（这里的命令，记得一条一条跑）
```
apt-get update && apt-get install build-essential python-pip python-m2crypto python-dev

pip install gevent shadowsocks
```
##创建配置文件：
下面是两条命令，第一条创建文件，第二条用vi编辑器编辑文件：
```
touch shadowsocks.json
vi shadowsocks.json
```
此时会进入这样的界面
![][4]
下面是shadowsocks配置文件的介绍：
```
{
"server":"服务器 IP 地址", #VPS的IP地址
"server_port":8388, #监听的端口
"local_address": "127.0.0.1", #本地监听的IP地址，默认为主机
"local_port":1080, #本地监听的端口
"password":"mypassword", #服务密码
"timeout":300, #用于加密的密码
"method":"aes-256-cfb", #加密方法，推荐 "aes-256-cfb"
"fast_open": false, #是否使用 TCP_FASTOPEN, true / false
"workers": 1 #worker 数量，Unix/Linux 可用，如果不理解含义请不要改
}
```
去掉所有的注释，并且修改服务器IP地址和密码，如下（注意修改server的ip和password的密码成自己的）：
```
{
"server":"123.45.67.8",
"server_port":8388,
"local_address": "127.0.0.1",
"local_port":1080,
"password":"mypassword", 
"timeout":300,
"method":"aes-256-cfb",
"fast_open": false, 
"workers": 1
}
```
复制上面的信息，在putty的窗口中点击鼠标右键，信息就被粘贴进去了，但是开头的信息有些遗漏，如下：
![][5]
注意，此时左下角有个`-- INSERT --`的白字,按方向键移动光标到第一行，然后按delete键删除错误的信息，对照着上面修改成正确的，修改完成之后是这样的：
![][6]
然后按下`ESC`键，白字消失，输入`:wq`注意是英文冒号，然后回车，就退回了命令行。
##配置开机启动项
输入
```
vi /etc/rc.local
```
在最后的`exit0`之前添加下面一行：
>nohup /usr/local/bin/ssserver -c /root/shadowsocks.json &

和上面一样的编辑方式，wq保存之后，输入重启服务器的命令：
```
reboot
```
此时终端会自动关闭。

服务器已经配置好了
#客户端配置
##win下配置
下载这个文件
https://github.com/shadowsocks/shadowsocks-csharp/releases
选择Shadowsocks-win-2.3.1.zip
如果打不开,从[百度网盘分享][7]也行
就一个简单的文件，解压出来，打开运行，填入参数：

![][8]

## linux（以ubuntu为例）：
各发行版linux shadowsocks安装方法找官网的说明，python版本的安装可以直接用
```
sudo pip install shadowsocks
```
提示有缺什么就安什么。
这里说下配置方法：
在自己喜欢的地方新建一个配置文件：
文件内容
```
{
"server":"服务器 IP 地址", #VPS的IP地址
"server_port":8388, #监听的端口
"local_address": "127.0.0.1", #本地监听的IP地址，默认为主机
"local_port":1080, #本地监听的端口
"password":"mypassword", #服务密码
"timeout":300, #超时设置
"method":"aes-256-cfb" #加密方法，推荐 "aes-256-cfb"
}
```
同样，记得把注释删掉
然后按照上面的方法修改系统启动项重启即可
```
nohup /usr/local/bin/sslocal -c /你自己定义的配置文件的路径/shadowsocks.json &
```


##android和ios则需要去应用市场搜索shadowsocks，安装即可。或者用fqrouter2。
## 桌面浏览器配置
（移动端当然不用配置浏览器）以chrome为例：
先去chrome应用商店搜索这个拓展SwitchyOmega，如果上不去，可以先参考这里替换hosts：
[修改hosts使用google应用商店][9]
安装插件成功以后，在情景模式里新建情景模式为shadowsocks（这是你建立的情景模式的名字），协议选择sock5,服务器输入127.0.0.1,端口是1080,就是和前面下载的那个软件里的配置一样，左下角保存更改。
设置规则列表更新，如果没有东西，那么输入：
>http://autoproxy-gfwlist.googlecode.com/svn/trunk/gfwlist.txt

打开百度，点击chrome浏览器右上角的新安装的插件的图标，选中自动切换。然后登录facebook.com试试吧。


#附加tips
##update 2015.2.5 
linux下可以用**proxychains**来对某个命令进行单独翻墙。
比如
```proxychains git push origin master```

win下的gui客户端可以直接设置全局翻。

[1]: http://www.findspace.name/res/1417 "VPS"
[2]: http://pan.baidu.com/s/1o6FRxR8 "switchproxy"
[4]: http://www.findspace.name/wp-content/uploads/2015/07/vijson.jpg
[5]: http://www.findspace.name/wp-content/uploads/2015/07/vijson0.jpg
[6]: http://www.findspace.name/wp-content/uploads/2015/07/vijson1.jpg
[7]: http://pan.baidu.com/s/1mgxMLjM
[8]: http://www.findspace.name/wp-content/uploads/2015/07/shadowsockswin.png
[9]: http://www.findspace.name/res/72 