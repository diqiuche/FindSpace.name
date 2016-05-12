#Pre
从网络上一直找到的ubuntu下重启网络的方法是
```
sudo service networking restart
or 
sudo /etc/init.d/networing restart
```
但是根本不管用，查了几次，才发现这篇bug提交
[Networking does not restart][0]
#Networking does not restart
大体描述下：
Simon在Server上运行`sudo /etc/init.d/networking restart`，得到如下输出
```
stop: Job failed while stopping
start: Job is already running: networking
```
graber回复他：
这种方法从ubuntu14.04开始已经不再支持了。请用`ifdown`和`ifup`来重置你想重置的网卡（网络接口）。
当然下面还有人给了一些好用的命令：
```
ifdown --exclude=lo -a && sudo ifup --exclude=lo -a
```
重启所有网卡适配器，除了loopback。
具体`ifdown ifup`的命令可以使用`--help`查看到。
但是我在使用中发现，这个命令只能重启你在`/etc/networking/interfaces`里面写的网络适配器（不知道我一直认为的名词都对不对，这里的意思就是虚拟网卡，如有错误，请指正）
#Ubuntu网络管理
Linux里面有两套管理网络连接的方案：

+ `/etc/network/interfaces`也就是`/etc/init.d/networking`
+ Network_Manager

这两套方案冲突，不能并存。
第一个方案适用于没有X的环境，如：服务器；或者那些完全不需要改动连接的场合。
第二套方案使用于有桌面的环境，特别是笔记本，搬来搬去，网络连接情况随时会变的。

他们两个为了避免冲突，又能共享配置，就有了下面的解决方案：
1. 当Network-Manager发现/etc/network/interfaces被改动的时候，则关闭自己（显示为未托管），除非managed设置成真。
2. 当managed设置成真时，/etc/network/interfaces，则不生效。

而默认`/etc/NetworkManager/NetworkManager.conf`里面managed是false。所以会显示未托管。
```
[ifupdown]
managed=false
```
所以默认情况下，是使用
```
sudo service network-manager restart
```
来重启网络。
#Reference
[networkmanager 显示有线网络设备未托管-Ubuntu论坛][1]
[Bug提交][0]
[1]: http://forum.ubuntu.org.cn/viewtopic.php?f=116&t=198297
[0]: https://bugs.launchpad.net/ubuntu/+source/ifupdown/+bug/1301015