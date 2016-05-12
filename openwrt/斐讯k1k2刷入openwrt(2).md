# Introdutction
本文针对原来的文章进行了修正，以及后续刷openwrt步骤的说明。
# 开启telnet
斐讯后来的新版本固件默认都关闭了原来的那个接口，有人做了配置文件来开启路由器的telnet。
配置文件下载地址，里面的`config_telnetd.rar`，包含k1,k2两个型号的配置文件：
[斐讯 百度网盘分享][1]
进入斐讯的`默认后台-->高级功能-->系统工具-->系统管理-->配置文件管理-->浏览-->上传`，重启后，telnet服务即开启了。
用`telnet 192.168.1.1`即可进入。
# 固件的选择
## 华硕固件
[华硕的固件](http://pan.baidu.com/s/1qWr367y)
这是right.com论坛有人维护的。[改华硕[N14U N54U]5G 2G的7620老毛子Padavan固件(百度云同步 aria2 QOS)](http://www.right.com.cn/forum/thread-161324-1-1.html)
我刷进去之后，一直进不去后台。。但是anywlan还是哪个论坛里说斐讯可以刷这个，而且有截图成功了。
## PandoraBox
潘多拉盒子是国内基于openwrt做的。也挺好用，内置了shadowsocks和chinadns等服务，比较大，在7M左右一般。但是由于校园网有ipv6加成必须用，我一直没有配置成功ipv6 dhcp，所以最后放弃了。家用可以考虑这个。
[下载地址](http://downloads.openwrt.org.cn/PandoraBox/Xiaomi-Mini-R1CM/testing/)
## openwrt
最精简的，基本上很多固件都是基于它做的。下一篇写文说明配置ipv6的方法。
进入[openwrt](https://downloads.openwrt.org/)下载之后，选择openwrt的版本，一般置顶的都是最新的release版本，进入后根据cpu型号选择，比如K1的cpu是ramips的，然后再根据Flash类型选择，k1，k2都是mt7620的，理论上下面所有的都通刷，但是一般都选择最相近的xiaomi mini。

注意，由于flash只有8M，所以**固件大小最好在7M，甚至不要到7.5M**，否则刷入之后，会出现无法保存配置问题。重启路由器以后，所有的配置就都丢了。刷入rom之后一定要先试着修改一些配置，重启看看修改是否仍然生效。
# 多次刷
只要刷入了breed，那么以后都可以通过以下操作进入路由器的“引导”：

1. 断开路由器电源
2. 按住reset键
3. 接入电源
4. 看到路由器所有灯连续闪几下后松开reset键（k2只有一个灯，，所以大概4秒中就行）
5. 有线连接路由器的电脑上打开192.168.1.1就看到进入了breed的界面，然后可以选择固件刷入。

跟刷手机rom类似

# Reference
[ 斐讯K1&K2官方新固件免拆机免降级开启Telnet服务新方法](http://forum.anywlan.com/thread-390090-1-1.html)
[史上最详细的OpenWrt shadowsocks路由器自动翻墙教程](https://softwaredownload.gitbooks.io/openwrt-fanqiang/content/index.html)

[1]: http://pan.baidu.com/s/1kVKpVxl "斐讯"