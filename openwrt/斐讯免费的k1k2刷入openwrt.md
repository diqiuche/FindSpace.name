wr# Introduction
斐讯从2016年1月份开始搞免费送路由器的活动，我最近(2016.3)才入手，看到已经确认这个活动是没有问题的，只不过官方固件貌似会发送你的浏览记录到斐讯的服务器，但是可以通过刷固件的方式解决。
本文记录了我刷openwrt的过程。
# 硬件配置
|||
|-|-|
|CPU|MediaTek MT7620A ver 2,eco 6|
|内存|64MB DDR2|
|CPU频率|580MHZ|
|总线|193MHZ|
|FLASH|8MB|

文中涉及的固件、breed等都可以从百度网盘分享下载：
[百度网盘分享 斐讯k1 刷固件][1]
**下面的部分太繁琐，可以直接[上传配置到路由器](http://www.findspace.name/easycoding/1668)然后就可以telnet了。**
~~# 刷入旧版本的rom
由于k1新的固件做了处理，屏蔽了调试接口，导致后面步骤中无法开启telnetd调试，所以需要先刷入老版本的固件。
[下载SW_K1_703004480_V21.4.1.0老版本的固件][1]
用有线连接路由器的wlan口，最好用有线，因为速度快，用无线操作太慢了。
在浏览器中打开`http://192.168.2.1`这是k1默认的管理地址，默认用户名和密码都是admin
![新版本的管理界面](http://www.findspace.name/wp-content/uploads/2016/03/k1_before.jpg)
找到系统设置的固件升级部分，刷入上面的固件。等一会刷入结束后，再登录管理地址确认刷入成功。会发现管理界面变得很丑。。~~
~~# 刷入breed bootleader
因为这个K1路由器可以开启telnet服务，所以此处刷Breed可以不使用编程器刷Flash芯片的方法进行。
此处的breed 相当于手机的recovery。~~
~~## 打开K1的Telentd服务
在浏览器中输入`http://192.168.2.1/goform/Diagnosis?pingAddr=192.168.2.100|echo""|telnetd`
注意其中的`192.168.2.100`需要替换成你的电脑的ip地址。
出现如下图：
![k1系统诊断](http://www.findspace.name/wp-content/uploads/2016/03/k1_system_judge.jpg)~~
## 连接路由器的telnet
```bash
telnet 192.168.2.1
```
linux下可直接执行，win下可能需要从 控制面板->程序->打开或关闭windows功能 选中telnet客户端服务勾选，确定。然后打开命令行窗口，输入以上命令。
默认的登录用户名和密码都是admin

##　备份原uboot （我跳过了。。）
查看系统分区，确定有bootleader
```bash
# cat /proc/mtd  
dev:    size   erasesize  name  
mtd0: 00800000 00010000 "ALL"  
mtd1: 00030000 00010000 "Bootloader  
mtd2: 00010000 00010000 "Config"  
mtd3: 00010000 00010000 "Factory"  
mtd4: 00790000 00010000 "Kernel"  
mtd5: 00010000 00010000 "nvbackup"  
mtd6: 00010000 00010000 "nvram"  
```
导出到tmp目录下：
```bash
 cat /dev/mtd1 > /tmp/uboot.bin  
```
通过TFTP服务，将备份的bootloader导入到电脑上。
```bash
tftp -p -r mtd1.bin -l mtd1.bin 192.168.2.100  
```
##开始刷入breed
如果此时路由器已经连上了有线，能上网，可以直接通过网络下载。
```bash
cd /tmp
wget http://breed.hackpascal.net/breed-mt7620-reset1.bin
```
可以进入http://breed.hackpascal.net 自行查找。注意型号。
或者在电脑上使用tftp工具传上去：
```bash
tftp -g -l /tmp/breed-mt7620-reset1.bin -r breed-mt7620-reset1.bin 192.168.2.100
```
最后执行：
```bash
mtd_write write breed-mt7620-reset1.bin Bootloader
```
## 按键步骤（重要）
上一步结束后，**断开路由器电源，按住复位键，插上电源，看到指示灯不停闪烁后松开**
在浏览器输入`192.168.1.1`，进入了breed：
![breed管理](http://www.findspace.name/wp-content/uploads/2016/03/breed_manage.jpg)

## 固件更新
从固件更新里就可以选择下载的固件进行更新了。
我使用的固件是openwrt：
https://downloads.openwrt.org/chaos_calmer/15.05.1/ramips/mt7620/openwrt-15.05.1-ramips-mt7620-xiaomi-miwifi-mini-squashfs-sysupgrade.bin
从名字可以看出是给小米mini路由器做的，同样适用于k1.
这个固件默认是英文的，后续可以设置为中文。

# Reference

[anywlan  斐讯k1路由器刷Breed BootLoader](http://forum.anywlan.com/thread-384830-1-1.html)
[斐讯k1刷Breed出错](http://www.right.com.cn/forum/thread-182610-1-1.html)
[斐讯K1三月新固件下的刷机](http://post.smzdm.com/p/433244)

[1]: http://pan.baidu.com/s/1i4KJq2h "百度网盘分享"