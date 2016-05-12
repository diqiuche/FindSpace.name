# Introducation
termux是个非常强大的终端模拟器。
[官方网站](https://termux.com/)
[酷安网下载地址](http://www.coolapk.com/apk/com.termux)

高级终端Termux组合了强大的终端模拟和拓展Linux包收集支持。

+ 享受bash 和 zsh。
+ 使用nano 和 vim编辑文件。
+ 通过ssh访问服务器。
+ 使用gcc和clang编译代码。
+ 使用python控制台来作为口袋计算器。
+ 使用git 和 subversion检查项目。
+ 使用frotz运行基于文本的游戏。

# 使用openssh从桌面连接安卓
由于termux甚至支持apt命令，所以从他建立。
[官方英文ssh连接说明](https://termux.com/ssh.html)比较简陋，这里详细描述一下。

# 安装termux
安装完成后，第一次打开会需要下载一些包库。
下载安装包库完成进入后，先跟普通的debian一样，注意是apt，手机上执行：
```
apt update
```
再安装openssh
```
apt install openssh
```
会自动生成keygen。并给出路径地址。
# 设置keygen
## 生成key
在电脑上运行
```
ssh-keygen -t rsa
```
交互式地生成一个key，默认保存在`~/.ssh/`下，两个文件`id_rsa`和`id_rsa.pub`，pub是公钥，另一个是私钥。ssh登录的原理不再赘述，自行谷歌。
## 连接adb
通过各种方式（直接usb或者通过网络adb）adb连接到手机上，将公钥adb push到手机上
```bash
adb push ~/.ssh/id_rsa.pub /data/data/com.termux/files/home/.ssh/id_rsa.pub
```
##设置授权key
通过`adb shell`进入手机，cd到`data/data/com.termux/files/home/.ssh/`，将公钥内容添加到ssh的授权文件里
```bash
cat ./id_rsa.pub >> authorized_keys
```
##注意权限问题
在手机上执行
```bash
whoami
```
得到用户名，比如得到的是`u0_a97`。
要求`.ssh`文件夹的权限是700（即`rwx------`）,且用户都是termux的用户才可以。
在电脑上执行：
在`home`文件夹下执行`ll -a`，输出类似：
```
root@libra:/data/data/com.termux/files/home # ll -a
-rw------- u0_a97   u0_a97        635 2016-03-06 11:20 .bash_history
drwx------ u0_a97   u0_a97            2016-03-06 12:20 .ssh
```
如果`.ssh`文件夹不是如图权限，则
```
chmod 700 .ssh
```
所属用户和组修改：
```
chown u0_a97:u0_a97 .ssh
```
同样查看`authorized_keys`文件权限是否是600(即`rw-------`)和其owner即所属组。不是的话按上述方法修改。
此时我们的授权已经做好了
# ssh连接
在手机上的termux里输入`sshd`开启ssh服务器，
在电脑上
```
ssh u0_a97@192.168.1.100 -p 8022
```
ip地址是手机的ip，端口默认是8022,可以通过`sshd -p 9000`来指定ssh服务器的端口。如果普通登录ssh一样，第一次连接会问你是否继续，输入yes继续，看到`$`符，ok，登录成功。
这里用户是普通用户，root用户如何ssh我还不清楚，但是由于我给小米刷了cm，cm官方wiki里有个说明，然而我没有配置成功
[cm官方wiki说明ssh登录](https://wiki.cyanogenmod.org/w/Doc:_sshd)
# 修改源

由于是termux自己建的一个模拟用户，有`home`和`usr`两个主要的文件，默认系统的文件都在`usr`下，如源等。默认linux的源是`/etc/apt/sources.list`，则此时就变成了`/data/data/com.termux/files/usr/etc/apt/sources.list`
清华有termux的源，可将源内容改成如下：
```
# The main termux repository:
deb [arch=all,arm] http://mirrors.tuna.tsinghua.edu.cn/termux/ stable main

```
通过`apt update`就可以更新源啦。
# 手机电脑不处于同一个局域网
手机必然至少连接路由器，在路由器里设置端口转发即可。
# tips
其实也有一些独立的ssh应用，但是termux不只是包括ssh，这里只是简单记录自己使用termux的一些经验。