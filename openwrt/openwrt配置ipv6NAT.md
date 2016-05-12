# Introduction
教育网仅有的特权也就是ipv6了。国内学校的网一般都很渣，唯有靠ipv6还有些速度。
买了搬瓦工的vps，自带ipv6,翻墙用的ss也支持ipv6,这样配置好本地支持ipv6的ss，看youtube 720p非常流畅。而且下载东西的时候，可以先通过vps上下载，然后ipv6到本地，平均速度也就自然达到了2MB/s以上。
# 修改hosts
而且谷歌本身也有ipv6,修改好ipv6的hosts，速度自然飞快，一般ping在40ms左右。
~~修改了路由器的hosts，这样所有的客户端不用任何修改，都可以以ipv6的速度上谷歌了。~~
建议路由器上的hosts还是用ipv4格式的，只需要在路由器上执行以下命令：
```bash
wget http://googlehosts-hostsfiles.stor.sinaapp.com/hosts
mv hosts /etc/hosts
```
即可替换掉hosts，则各个终端上就默认翻墙了。
实际上更建议在路由器的后台添加附加hosts

本文主要系转载。
# 修改
## 安装内核支持
刷入openwrt之后，安装 IPv6 内核 nat 模块及路由追踪软件：
```bash
opkg update
opkg install kmod-ipt-nat6
opkg install iputils-tracepath6
```
##修改dhcp
修改`/etc/config/dhcp`, 在设置 lan 那节添加内容，odhcpd 为内网设备设置 IPv6 地址及路由等，如下：
```bash
config dhcp 'lan'
    option interface 'lan'
    option start '100'
    option limit '150'
    option leasetime '12h'
    option dhcpv6 'server'
    option ra 'server'
    option ra_management '1'
    option ra_default '1'
```
一般只需要加最后两行
## 修改防火墙
更改 /etc/firewall.user ，添加一行，为内网访问外网 IPv6 时提供IP伪装
```bash
ip6tables -t nat -A POSTROUTING -o $(uci -q get network.wan6.ifname) -j MASQUERADE
```
## 添加开机自启动脚本
创建`/etc/hotplug.d/iface/90-ipv6`，设置外网 IPv6 路由，修改文件属性为755， 内容如下
```bash
#!/bin/sh 
[ "$ACTION" = ifup ] || exit 0
[ "$INTERFACE" = wan6 ] && {              
  route -A inet6 add ::/0 gw $(tracepath6 -n tv.byr.cn | grep ' 1: ' | awk 'NR==1 {print $2}') dev $(uci -q get network.wan6.ifname)
}
```
通过`tracepath6 -n tv.byr.cn`获取外网 IPv6 网关，可选择其它较快且能连通的 IPv6 服务器，注意不要用 ipv6.google.com 。
20160118 补充：tracepath6 在不同的 IPv6 环境中得到的结果略有不同，所以 grep ' 1: ' 可能是错误的，可以根据 tracepath6 -n tv.byr.cn 的实际结果，例如换成 grep ' 1 '， 总之要搜索到正确的网关。

# 说明
在原生openwrt上没问题，但是在pandorabox上没有配置成功。
`注意重启路由器要重新把电脑连接路由器一次。`
# Reference

[在 Openwrt Chaos Calmer 中配置 IPv6 NAT](http://my.oschina.net/u/444663/blog/509427?fromerr=JEGTryMR)