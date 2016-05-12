  

##无法注销系统：
>ps -ef|grep gnome-session
 
然后kill掉它。

##终端模式下，进入U盘
>cd /media

然后ls发现用户名，cd进用户，然后ls可以看到u盘的卷标和挂载的win的分区的标示
## Ubuntu 下用echo写入到文件
>echo "123" >> 1.sh
##查看用户
>cat /etc/passwd
##在当前窗口打开命令行

安装如下软件
>sudo apt-get install nautilus-open-terminal

注销之后重新登陆，在鹦鹉螺（文件管理器）右键，就能看到在当前窗口打开命令行
##查看CPU信息
>cat /proc/cpuinfo
##显示当前硬件信息
>sudo lshw
##特殊符号的用法
http://www.codesky.net/article/201112/132720.html

例如：cat a.txt >> b.txt 将a的内容追加到b
## 双系统修改启动顺序
>sudo gedit /etc/default/grub

把**GRUB_DEFAULT=0**的0修改成你想要的序号，就是开机启动时显示的系统的序号，从0开始。然后更新下grub
>sudo update-grub
## 如何kill掉进程名包含某个字符串的一批进程: 
```
kill -9 $(ps -ef|grep 进程名关键字|gawk '$0 !~/grep/ {print $2}' |tr -s '\n' ' ')  
```
in ubuntu, the gawk will be replaced with awk: 
##观测进程名包含某个字符串的进程详细信息: 
```
top -c -p $(ps -ef|grep 进程名关键字|gawk '$0 !~/grep/ {print $2}' |tr -s '\n' ','|sed 's/,$/\n/') 
kill -9 $(ps -ef|grep java|grep 'dev-jboss-csc'|gawk '$0 !~/grep/ {print $2}' |tr -s '\n' ' ') 
```
## 查看列表
>ps -e 

查看当前的进程 (如果显示不完整，在终端命令下可以用滚轮来操作，可是，如果是在滚轮无响应或者是在Ctrl+Alt+F1下，可以这样用：ps -e | more，然后按s是一页一页的显示。)

##提示无法解析主机
修改/etc/hosts 
>127.0.0.1 (然后是提示你的主机名)
##单独查看内存使用情况的命令：free -m
查看内存及cpu使用情况的命令：top
也可以安装htop工具，这样更直观，
安装命令如下：
>sudo apt-get install htop
安装完后，直接输入命令：htop就可以看到内存或cpu的使用情况了。
##命令行后台运行程序
只要加上 &即可

比如 fcitx &则fcitx就在后台了。最近fcitx不太稳定，经常100%cpu，sudo kill之后，这样运行就行
## 修改文件夹及其子文件夹和内容权限的命令
>chmod -R a+w ./hello/
-R指的是循环递归地修改
a是ll显示出的权限组a表示所有用户，u表示拥有者，o表示other其他人，g表示group，ll命令显示的顺序是ago
## redhat 挂载u盘
插入U盘之后，按照下面的步骤：

+  fdisk -l /dev/sd*

fdisk -l 列出指定设备的分区表信息。由于usb盘是被模拟为scsi设备访问，所以会被自动命名为sd*。

通常这一步就能找到U盘，如果U盘有指示灯也会亮，表示被找到。

+ 如果执行上一个命令没有反应，或者某些信息显示模块没有加入,可以lsmod查看一下是否有usb-storage scsi_mod sd_mod模块。没有就modprobe [module]，添加所缺模块。然后再试就ok

+ 最后就是把U盘mount上去，这里需要先在mnt目录下面建一个usb目录
> mount /dev/sda /mnt/usb

	某些系统需要指定文件系统的类型，可以用
>mount -t vfat /dev/sda /mnt/usb
## 添加启动项

> gedit /etc/rc.local

在exit之前添加要执行的命令

## 查看网络及时速度

>sar -n DEV 1 100

1代表一秒统计并显示一次
100代表统计一百次
使用ntop等工具，就更方便简单了，不过这个更灵活

P.S.

sar在sysstat包
##ssh空闲一段时间之后自动退出

+ 服务器端：修改/etc/ssh/sshd_config配置文件，找到ClientAliveCountMax（单位为分钟）修改你想要的值，
执行service sshd reload，安全性欠佳。

+ 客户端：
找到所在用户的.ssh目录,如root用户该目录在：

>/root/.ssh/
在该目录创建config文件
>>vi /root/.ssh/config

>加入下面一句：
>>ServerAliveInterval 60

>同时修改config文件的属性
>>$ chmod 600 config

保存退出，重新开启root用户的shell，则再ssh远程服务器的时候，不会因为长时间操作断开。应该是加入这句之后，ssh客户端会每隔一
段时间自动与ssh服务器通信一次，所以长时间操作不会断开。
##dropbox客户端

国内被墙，与其修改不断修改hosts，不如直接用vpn或者shadowsocks，这是我从vps上利用下面的命令下载的：
[Dropbox Linux 客户端（Ubuntu）][1]

官方说明：
>通过命令行安装 Dropbox
Dropbox 守护程序可在所有 32 位与 64 位 Linux 服务器上正常运行。若要安装，请在 Linux 终端运行下列命令。
32-bit:
cd ~ && wget -O - "https://www.dropbox.com/download?plat=lnx.x86" | tar xzf -
64-bit:
cd ~ && wget -O - "https://www.dropbox.com/download?plat=lnx.x86_64" | tar xzf -
接着，从新建的 .dropbox-dist 文件夹运行 Dropbox 守护程序。
~/.dropbox-dist/dropboxd




##引用：
+ http://zhidao.baidu.com/link?url=LFZUp3v7wIazYiMJZgF7WqmZnSGIug6ZCtH19XB28x9m52Kdz2U4RcyGHzr6j7tvG9F4XKf_ZDUgKLwfJbdMJ_
+ http://os.chinaunix.net/a2008/1005/987/000000987543.shtml
 

[1]: http://pan.baidu.com/s/1eQpfeHc "dropbox linux客户端"