#Pre
实验室的IP都是自动分配的，但是如果电脑关机仍插着网线，就很容易出现开机后，ip和上次的ip不变，但是上不了网。
#解决方法
```linux
#开启有线网卡
ifconfig eth0 up
#发送dhcp请求，获取新的ip
dhcpcd eth0
```
#tips
如果不行，试着先关闭有线再重新开启一次
关闭：
```linux
ifconfig eth0 down
```

#Reference:
archwiki的，貌似和我装的那个不太一样，起码man出来的手册不一样
https://wiki.archlinux.org/index.php/Dhcpcd
百度知道的问题：
http://zhidao.baidu.com/link?url=___BPmT-46_3SgDlRXtYFbx9fcgUPJMvV3ebs-ClGtmkG1yg38x8QCXJ7C4BsnE4aQNoGQsw3MGl_p-neqWmbq

#update:2015.10.7 
经过[Ubuntu网络不通][0]这件事，觉得可能之前自己考虑错误了，原来以为是路由器的问题，现在觉得应该就是网络配置问题，把`/etc/network/interfaces`内容改成下面应该就行了：
```
auto lo
iface lo inet loopback

auto eth0
iface eth0 inet dhcp
```

[0]: http://www.findspace.name/easycoding/1471 "Ubuntu网络不通"