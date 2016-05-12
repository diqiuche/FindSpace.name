[TOC]
#Pre
X出了问题，调了几次没调成功，今下午又出现了一次，怒了，重装。
首先，我的所有代码都在Dropbox网盘里，所以不担心，只是配置一些东西比较麻烦。
#安装
##OS
还是用Ubuntu 14.04吧，习惯了，而且LTS，5年长期支持版本。主要还是各种东西都比较习惯了。暂时不想折腾，还有别的工作要做。
##分区
```
/boot	200M
/home	200G
/		250G
/swap	8G
```
注意boot最小200MB，因为一般安装完成boot是50MB左右，但是后续更新剩余的50MB就不够了。
上一次装的时候，home没有单独出来，然后这次装实际上是直接格盘了，反正win7后来也几乎没进去过，等装完ubuntu装个虚拟机就行了。
##安装完成
安装完成之后进入了系统，先修改`/etc/apt/sources.list`，添加中科大的源：
```
deb http://debian.ustc.edu.cn/ubuntu/ trusty main multiverse restricted universe
deb http://debian.ustc.edu.cn/ubuntu/ trusty-backports main multiverse restricted universe
deb http://debian.ustc.edu.cn/ubuntu/ trusty-proposed main multiverse restricted universe
deb http://debian.ustc.edu.cn/ubuntu/ trusty-security main multiverse restricted universe
deb http://debian.ustc.edu.cn/ubuntu/ trusty-updates main multiverse restricted universe
deb-src http://debian.ustc.edu.cn/ubuntu/ trusty main multiverse restricted universe
deb-src http://debian.ustc.edu.cn/ubuntu/ trusty-backports main multiverse restricted universe
deb-src http://debian.ustc.edu.cn/ubuntu/ trusty-proposed main multiverse restricted universe
deb-src http://debian.ustc.edu.cn/ubuntu/ trusty-security main multiverse restricted universe
deb-src http://debian.ustc.edu.cn/ubuntu/ trusty-updates main multiverse restricted universe
#下面是ipv6的源，教育网下，速度非常快，一般6MB/s
deb http://mirrors6.ustc.edu.cn/ubuntu/ trusty main multiverse restricted universe
deb http://mirrors6.ustc.edu.cn/ubuntu/ trusty-backports main multiverse restricted universe
deb http://mirrors6.ustc.edu.cn/ubuntu/ trusty-proposed main multiverse restricted universe
deb http://mirrors6.ustc.edu.cn/ubuntu/ trusty-security main multiverse restricted universe
deb http://mirrors6.ustc.edu.cn/ubuntu/ trusty-updates main multiverse restricted universe
deb-src http://mirrors6.ustc.edu.cn/ubuntu/ trusty main multiverse restricted universe
deb-src http://mirrors6.ustc.edu.cn/ubuntu/ trusty-backports main multiverse restricted universe
deb-src http://mirrors6.ustc.edu.cn/ubuntu/ trusty-proposed main multiverse restricted universe
deb-src http://mirrors6.ustc.edu.cn/ubuntu/ trusty-security main multiverse restricted universe
deb-src http://mirrors6.ustc.edu.cn/ubuntu/ trusty-updates main multiverse restricted universe
```
然后
```
sudo apt-get update 
sudo apt-get upgrade
```
先更新下一些补丁或者软件的更新。
#配置
##下载必备的软件
首先从findspace博客下载hosts，然后就能访问chrome官网下载了。下载好chrome的文件之后，
```
sudo dpkg -i google-chrome-stable.deb
```
来安装。安装完成后，登录账户，先把谷歌账户的东西同步着。一个很好用的管理代理的chrome插件：SwitchyOmega。可以在商店搜索，配置也很简单， 新建profile（中文翻译的应该是规则），然后我用的shadowsocks代理，所以设置sock5，server:127.0.0.1:1080,当然现在系统里还没有开启代理。
##设置代理
ss现在也不继续开发了，但是在python的pip库里还有，所以：
```
sudo apt-get install python-gevent python-pip
pip install shadowsocks
```
中间提示一些依赖问题的话，照着提示安装就行。
[配置文件参考][0],新建好配置文件，然后填入自己的ss服务器，在终端里先测试下能不能连上，
```
/usr/local/bin/sslocal -c /home/find/shadowsocks.json
```
后面就是配置文件的位置。
参考[配置开机启动项][1]，可以在重启后自动开启代理，注意文章里是服务器的ssserver，所以要改成sslocal。
##安装Dropbox
首先到 https://www.dropbox.com/install 下载debian的64位包，dpkg安装上，这只是个安装器，所以先不要运行。
~~Dropbox的一个弊端就是，http代理和sock5代理没法下载，但是Dropbox的同步可以用。所以下载需要先登录自己的VPN，~~官方给的下载命令是：
```
#32-bit:
cd ~ && wget -O - "https://www.dropbox.com/download?plat=lnx.x86" | tar xzf -
#64-bit:
cd ~ && wget -O - "https://www.dropbox.com/download?plat=lnx.x86_64" | tar xzf -
#Next, run the Dropbox daemon from the newly created .dropbox-dist folder.
~/.dropbox-dist/dropboxd
```
~~最后一条命令登录了之后，在Dropbox里面设置好自己的本地代理，然后vpn就可以退下来了。后面都用http代理或者sock5代理就可以了。~~
直接在vps上用上面的命令下载好，然后scp传下来，直接传到本地的`~/.dropbox-dist/`
然后再从dash 打开dropbox设置代理即可。
在Dropbox的选项里面本来就有开机自启动的选项，可以勾上。
##ubuntu vps搭建vpn
我主要参考了这两篇文章，注意里面有些设置信息要改成自己的。
[Xen Ubuntu VPS搭建VPN方法][2]
["PPTP VPN Setup Guide for a Debian OpenVZ VPS"][3]
然后就可以用自己的vpn来做上一步的安装Dropbox了。至于搭建shadowsocks的教程，可以参看这里：
[SHADOWSOCKS科学上网][4]
##安装设置sublime
从官网下载安装之后，需要注意以下的问题：
[解决Sublime无法输入中文| FindSpace][5]
##系统语言和输入法
一开始我选择的英语，然后再进入系统之后，中文输入法，需要设置中文语言支持才可以。
先从系统设置`-->language support-->install /remove language`勾选`chinese simplified`,然后等待安装完成，在Language的列表里将`汉语（中国）`拖动到最上面，在命令行里安装Fcitx输入法：
```
sudo apt-get install fcitx fcitx-googlepinyin fcitx-module-cloudpinyin
```
第三个是云拼音模块，可以在配置里设置它使用百度的库，毕竟访问谷歌有点慢。
然后从重新进入系统设置的语言设置，设置输入法框架为fcitx，ibus的输入法太难用了。

参考[fcitx quickphrase的配置][7]，并在那个文件里填入[Emoji表情大全][6]的内容，就可以在谷歌拼音的中文输入下，按下;分号键，然后输入前面表情大全里面前面的缩写就可以用这些emoji表情啦。
##好用的小工具
在当前窗口打开命令行
安装如下软件
```
sudo apt-get install nautilus-open-terminal
```
注销之后重新登陆，在鹦鹉螺（文件管理器nautilus）右键，就能在右键菜单里看到在当前窗口打开命令行，很好用的工具。
##设置ssh
SSH空闲一段时间之后自动退出
cd进用户主目录`/home/find/.ssh/`,一般配置文件都是在用户文件夹下，以.开头的隐藏文件夹，可以通过`ls -a`查看，默认ls不会列出隐藏文件夹。
在该目录创建config文件
```
vi /root/.ssh/config
```
加入下面一句：
```
ServerAliveInterval 60
```
同时修改config文件的属性
```
$ chmod 600 config
```
保存退出，重新开启root用户的shell，则再ssh远程服务器的时候，不会因为长时间操作断开。应该是加入这句之后，ssh客户端会每隔一段时间自动与ssh服务器通信一次，所以长时间操作不会断开。
这里是设置的root用户的，普通用户需要改文件权限为660,否则会提示权限错误。
#这次多学了一点
##命令行代理
在命令行里使用代理，我原来一直都用的proxychains这个工具，这次发现了可以临时配置环境变量
```
export http_proxy=proxy_addr:port
export ftp_proxy=proxy_addr:port
```
上面是http和ftp代理，在命令行里设置之后，知道这个窗口eixt之前，涉及到网络的命令都是走的代理，
假设你的代理服务器为192.168.1.1，端口是8080，用户名为easwy，密码是123456，那么应该这样设置这两个环境变量：
```
export http_proxy=http://easwy:123456@192.168.1.1:8080
export ftp_proxy=http://easwy:123456@192.168.1.1:8080
```
如果想要使命令行持久化走代理，添加到`~/.bashrc`然后重启就行。
##手残党误删除字体库
本来正在配置source code pro这款很舒服的等宽字体，结果不小心把所有的字体都删除了，就是`/usr/share/fonts/`下的所有文件(￣_￣|||)，然后所有的窗口成了这样的样子：

![][8]

最简单快速的方法：
我用u盘装的ubuntu，所以直接重启进入u盘的那个ubuntu，就是try ubuntu，cp所有的字体文件到当前分区的目录下面，注意因为是在u盘的系统，所以在硬盘的系统所在的分区需要手动挂载，直接从文件管理器单击左边的几个找一找硬盘的系统装在哪个分区就行，点一下就会挂载上，默认跟u盘一样挂载在`/media/find/`下面，find是我的用户名。

现在基本的东西都弄好了，其他的软件还在下载，如果遇到一些不正常的情况，我会再写记录。

[0]: http://www.findspace.name/res/956#_6
[1]: http://www.findspace.name/res/956#_5
[2]: https://dickwu.com/posts/2012/01/1046.html "Xen Ubuntu VPS搭建VPN方法"
[3]: http://www.putdispenserhere.com/pptp-vpn-setup-guide-for-a-debian-openvz-vps/ "PPTP VPN Setup Guide for a Debian OpenVZ VPS"
[4]: http://www.findspace.name/res/956
[5]: http://www.findspace.name/res/291 "解决Sublime无法输入中文| FindSpace"
[6]: https://fcitx-im.org/wiki/QuickPhrase_Emoji "QuickPhrase Emoji"
[7]: https://fcitx-im.org/wiki/QuickPhrase/zh-cn "快速输入"
[8]: http://www.findspace.name/wp-content/uploads/2015/10/rmUbuntuFonts.jpg