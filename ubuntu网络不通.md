#Pre
回实验室之后发现板子上不去网，ubuntu14.04 arm，
#思路
先排除非板子的因素，换了另一个网口，不行，原来的网线插到我笔记本上，可以用，确定是板子问题。
看板子网口的灯，两个灯正常（板子有两个灯，一个红色表示电力连接正常，一个不停闪烁是数据），[网口灯定义][0]。应该不是板子硬件坏了，而且`ifconfig`的结果完全正常，但是`ping`域名都是`unknownhosts`,怀疑是dns问题，直接从网络设置（GUI）里改dns，不行，`ping 8.8.8.8`也接不到数据，不是dns问题。多换借个dns也没用，证明的确不是dns问题。
`route`出来的结果是空的，网络配置有问题。
修改
```
sudo vim /etc/network/interfaces
```
看到里面只有这些
```
# interfaces(5) file used by ifup(8) and ifdown(8)
# Include files from /etc/network/interfaces.d
```
实际上还有一句，忘记了。。
然而`/etc/network/interfaces.d`是个空目录，看来这配置文件的确有点问题啊。
#修复
修改`/etc/network/interfaces`文件如下（当然根据自己情况需要备份）：
```
auto lo
iface lo inet loopback
```
顺便贴上设置静态ip的内容：
```
auto lo
iface lo inet loopback
# The primary network interface
auto eth0
#iface eth0 inet dhcp

iface eth0 inet static
address 192.168.80.129
netmask 255.255.255.0
gateway 192.168.80.2

保存退出。
注意：只需要设置address（IP地址）、netmask（子网掩码）、gateway（网关）这三项就OK，network和broadcast这两项参数是可以不写的。
```
重启网络：
```
sudo /etc/init.d/networking restart
```
不管用，的确不管用。。因为正常的话，如果你在图形界面下，右上角的网络连接应该是断一下，重新连接上，但是，没反应。这个命令坑爹。
换这个：
```
sudo service network-manager restart
```
ok了。
至于为什么networking restart不管用了，在这里找到了原因：
[Networking does not restart][1]


#Reference：
http://askubuntu.com/questions/71159/network-manager-says-device-not-managed
http://unix.stackexchange.com/questions/155485/default-contents-of-etc-network-interfaces-on-crunchbang-debian

[1]:https://bugs.launchpad.net/ubuntu/+source/ifupdown/+bug/1301015
[0]: http://liuxh.blog.51cto.com/225067/42283 